<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Kelas;
use App\Models\UserSiswa;
use App\Models\Presensi;
use App\Models\Sekolah;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
use OpenSpout\Writer\XLSX\Writer;
use OpenSpout\Common\Entity\Row;

class PresensiSiswaController extends Controller
{
    /**
     * Map status database value to display label.
     */
    private function mapStatusToName($status)
    {
        switch (strval($status)) {
            case '1':
            case 'Hadir':
                return 'Hadir';
            case '2':
            case 'Sakit':
                return 'Sakit';
            case '3':
            case 'Izin':
                return 'Izin';
            case '4':
            case 'Alfa':
            case 'Alpha':
                return 'Alfa';
            default:
                return 'Tidak Diketahui';
        }
    }

    /**
     * Map status database value to CSS badge class.
     */
    private function mapStatusToBadge($status)
    {
        switch (strval($status)) {
            case '1':
            case 'Hadir':
                return 'badge-success';
            case '2':
            case 'Sakit':
                return 'badge-warning';
            case '3':
            case 'Izin':
                return 'badge-info';
            case '4':
            case 'Alfa':
            case 'Alpha':
                return 'badge-danger';
            default:
                return 'badge-muted';
        }
    }

    // ── 1. Input Presensi ──
    public function inputIndex(Request $request)
    {
        $kelasList = Kelas::where('status', 'aktif')
            ->with('jurusan')
            ->orderBy('tingkat')
            ->orderBy('rombel')
            ->get();

        $tanggal = $request->get('tanggal', Carbon::today()->toDateString());
        $id_kelas = $request->get('id_kelas');

        $siswaList = collect();
        $existingPresensi = collect();

        if ($id_kelas) {
            $siswaList = UserSiswa::where('id_kelas', $id_kelas)
                ->where('status', 'aktif')
                ->orderBy('nama_siswa')
                ->get();

            if ($siswaList->isNotEmpty()) {
                $existingPresensi = Presensi::whereIn('nis', $siswaList->pluck('nis'))
                    ->whereDate('tanggal', $tanggal)
                    ->get()
                    ->keyBy('nis');
            }
        }

        return view('presensi-siswa.input', compact('kelasList', 'tanggal', 'id_kelas', 'siswaList', 'existingPresensi'));
    }

    public function inputStore(Request $request)
    {
        $request->validate([
            'tanggal'  => 'required|date',
            'id_kelas' => 'required|exists:kelas,id_kelas',
            'presensi' => 'required|array',
        ]);

        $tanggal = $request->input('tanggal');
        $id_kelas = $request->input('id_kelas');

        // Pastikan input presensi menggunakan validasi file jika ada yang diupload
        foreach ($request->file('presensi', []) as $nis => $fileData) {
            if (isset($fileData['file'])) {
                $request->validate([
                    "presensi.{$nis}.file" => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048'
                ], [
                    "presensi.{$nis}.file.mimes" => "Berkas siswa dengan NIS {$nis} harus berupa format gambar atau PDF.",
                    "presensi.{$nis}.file.max"   => "Ukuran berkas siswa dengan NIS {$nis} maksimal 2MB."
                ]);
            }
        }

        DB::beginTransaction();
        try {
            foreach ($request->input('presensi') as $nis => $data) {
                $status = $data['status'] ?? '1'; // Default: Hadir (1)
                $keterangan = $data['keterangan'] ?? null;

                $existing = Presensi::where('nis', $nis)
                    ->whereDate('tanggal', $tanggal)
                    ->first();

                $filePath = $existing ? $existing->file : null;

                // Handle file upload
                if ($request->hasFile("presensi.{$nis}.file")) {
                    if ($existing && $existing->file) {
                        Storage::disk('public')->delete($existing->file);
                    }
                    $file = $request->file("presensi.{$nis}.file");
                    $filePath = $file->store('siswa/presensi', 'public');
                }

                // Clean up file if user checked 'delete_file' or if status is set to Hadir/Alfa and they want to clear it
                if (isset($data['delete_file']) && $data['delete_file'] == '1') {
                    if ($filePath) {
                        Storage::disk('public')->delete($filePath);
                        $filePath = null;
                    }
                }

                if ($existing) {
                    $existing->update([
                        'status'     => $status,
                        'keterangan' => $keterangan,
                        'file'       => $filePath,
                    ]);
                } else {
                    Presensi::create([
                        'nis'        => $nis,
                        'tanggal'    => $tanggal,
                        'jam'        => Carbon::today()->toDateString() === $tanggal ? Carbon::now()->format('H:i:s') : '07:00:00',
                        'status'     => $status,
                        'keterangan' => $keterangan,
                        'file'       => $filePath,
                    ]);
                }
            }

            DB::commit();
            return redirect()->route('presensi-siswa.input', ['id_kelas' => $id_kelas, 'tanggal' => $tanggal])
                ->with('success', 'Presensi siswa berhasil disimpan.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan saat menyimpan presensi: ' . $e->getMessage());
        }
    }

    // ── 2. Rekap Presensi ──
    public function rekapIndex(Request $request)
    {
        $kelasList = Kelas::where('status', 'aktif')
            ->with('jurusan')
            ->orderBy('tingkat')
            ->orderBy('rombel')
            ->get();

        $id_kelas = $request->get('id_kelas');
        $bulan = $request->get('bulan', Carbon::today()->format('Y-m'));

        $siswaList = collect();
        $daysInMonth = 0;
        $year = null;
        $month = null;
        $rekapData = [];

        if ($id_kelas && $bulan) {
            $siswaList = UserSiswa::where('id_kelas', $id_kelas)
                ->where('status', 'aktif')
                ->orderBy('nama_siswa')
                ->get();

            [$year, $month] = explode('-', $bulan);
            $daysInMonth = Carbon::create($year, $month)->daysInMonth;

            if ($siswaList->isNotEmpty()) {
                $presensiList = Presensi::whereIn('nis', $siswaList->pluck('nis'))
                    ->whereYear('tanggal', $year)
                    ->whereMonth('tanggal', $month)
                    ->get()
                    ->groupBy('nis');

                foreach ($siswaList as $siswa) {
                    $siswaPresensi = $presensiList->get($siswa->nis, collect())->keyBy(function($p) {
                        return (int) Carbon::parse($p->tanggal)->format('d');
                    });

                    $hadir = 0;
                    $sakit = 0;
                    $izin = 0;
                    $alfa = 0;
                    $grid = [];

                    for ($d = 1; $d <= $daysInMonth; $d++) {
                        $date = Carbon::create($year, $month, $d);
                        if ($date->isWeekend()) {
                            $grid[$d] = 'W'; // weekend — tidak dihitung
                            continue;
                        }
                        $p = $siswaPresensi->get($d);
                        if ($p) {
                            $normStatus = $this->mapStatusToName($p->status);
                            if ($normStatus === 'Hadir') {
                                $hadir++;
                                $grid[$d] = 'H';
                            } elseif ($normStatus === 'Sakit') {
                                $sakit++;
                                $grid[$d] = 'S';
                            } elseif ($normStatus === 'Izin') {
                                $izin++;
                                $grid[$d] = 'I';
                            } else {
                                $alfa++;
                                $grid[$d] = 'A';
                            }
                        } else {
                            $grid[$d] = '-';
                        }
                    }

                    $total = $hadir + $sakit + $izin + $alfa;
                    $persentase = $total > 0 ? round(($hadir / $total) * 100, 1) : 0;

                    $rekapData[] = [
                        'siswa'      => $siswa,
                        'grid'       => $grid,
                        'hadir'      => $hadir,
                        'sakit'      => $sakit,
                        'izin'       => $izin,
                        'alfa'       => $alfa,
                        'persentase' => $persentase,
                    ];
                }
            }
        }

        return view('presensi-siswa.rekap', compact(
            'kelasList', 'id_kelas', 'bulan', 'siswaList', 'daysInMonth', 'year', 'month', 'rekapData'
        ));
    }

    // ── 3. Laporan Presensi ──
    public function laporanIndex(Request $request)
    {
        $kelasList = Kelas::where('status', 'aktif')
            ->with('jurusan')
            ->orderBy('tingkat')
            ->orderBy('rombel')
            ->get();

        $id_kelas = $request->get('id_kelas');
        $tanggal_dari = $request->get('tanggal_dari', Carbon::today()->startOfMonth()->toDateString());
        $tanggal_sampai = $request->get('tanggal_sampai', Carbon::today()->toDateString());

        $siswaList = collect();
        $laporanData = [];

        if ($id_kelas) {
            $siswaList = UserSiswa::where('id_kelas', $id_kelas)
                ->where('status', 'aktif')
                ->orderBy('nama_siswa')
                ->get();

            if ($siswaList->isNotEmpty()) {
                $presensiList = Presensi::whereIn('nis', $siswaList->pluck('nis'))
                    ->whereBetween('tanggal', [$tanggal_dari, $tanggal_sampai])
                    ->get()
                    ->groupBy('nis');

                foreach ($siswaList as $siswa) {
                    $siswaPresensi = $presensiList->get($siswa->nis, collect());

                    $hadir = 0;
                    $sakit = 0;
                    $izin = 0;
                    $alfa = 0;

                    foreach ($siswaPresensi as $p) {
                        // Abaikan data di hari Sabtu dan Minggu
                        if (Carbon::parse($p->tanggal)->isWeekend()) continue;
                        $normStatus = $this->mapStatusToName($p->status);
                        if ($normStatus === 'Hadir') $hadir++;
                        elseif ($normStatus === 'Sakit') $sakit++;
                        elseif ($normStatus === 'Izin') $izin++;
                        elseif ($normStatus === 'Alfa') $alfa++;
                    }

                    $total = $hadir + $sakit + $izin + $alfa;
                    $persentase = $total > 0 ? round(($hadir / $total) * 100, 1) : 0;

                    $laporanData[] = [
                        'nis'        => $siswa->nis,
                        'nama_siswa' => $siswa->nama_siswa,
                        'hadir'      => $hadir,
                        'sakit'      => $sakit,
                        'izin'       => $izin,
                        'alfa'       => $alfa,
                        'total'      => $total,
                        'persentase' => $persentase,
                    ];
                }
            }
        }

        return view('presensi-siswa.laporan', compact(
            'kelasList', 'id_kelas', 'tanggal_dari', 'tanggal_sampai', 'laporanData'
        ));
    }

    public function laporanExportExcel(Request $request)
    {
        $id_kelas = $request->get('id_kelas');
        $tanggal_dari = $request->get('tanggal_dari');
        $tanggal_sampai = $request->get('tanggal_sampai');

        if (!$id_kelas || !$tanggal_dari || !$tanggal_sampai) {
            return back()->with('error', 'Filter tidak lengkap untuk ekspor excel.');
        }

        $kelas = Kelas::with('jurusan')->findOrFail($id_kelas);
        $sekolah = Sekolah::where('id_sekolah', 1)->first();
        $sekolahNama = $sekolah ? $sekolah->nama_sekolah : 'SmartSchool';

        $siswaList = UserSiswa::where('id_kelas', $id_kelas)
            ->where('status', 'aktif')
            ->orderBy('nama_siswa')
            ->get();

        $presensiList = Presensi::whereIn('nis', $siswaList->pluck('nis'))
            ->whereBetween('tanggal', [$tanggal_dari, $tanggal_sampai])
            ->get()
            ->groupBy('nis');

        $filename = "Laporan_Presensi_" . str_replace(' ', '_', $kelas->nama_kelas) . "_" . $tanggal_dari . "_to_" . $tanggal_sampai . ".xlsx";
        $tmpFile = tempnam(sys_get_temp_dir(), 'laporan_presensi_') . '.xlsx';

        $writer = new Writer();
        $writer->openToFile($tmpFile);

        // Header Rows
        $writer->addRow(Row::fromValues(['LAPORAN KEHADIRAN SISWA']));
        $writer->addRow(Row::fromValues([$sekolahNama]));
        $writer->addRow(Row::fromValues(['Kelas:', $kelas->tingkat . ' ' . $kelas->rombel]));
        $writer->addRow(Row::fromValues(['Periode:', Carbon::parse($tanggal_dari)->translatedFormat('d M Y') . ' s.d ' . Carbon::parse($tanggal_sampai)->translatedFormat('d M Y')]));
        $writer->addRow(Row::fromValues([]));

        // Table Header
        $writer->addRow(Row::fromValues([
            'No', 'NIS', 'Nama Siswa', 'Hadir (H)', 'Sakit (S)', 'Izin (I)', 'Alfa (A)', 'Total Hari Efektif', 'Persentase Kehadiran'
        ]));

        foreach ($siswaList as $index => $siswa) {
            $siswaPresensi = $presensiList->get($siswa->nis, collect());

            $hadir = 0;
            $sakit = 0;
            $izin = 0;
            $alfa = 0;

            foreach ($siswaPresensi as $p) {
                // Abaikan data di hari Sabtu dan Minggu
                if (Carbon::parse($p->tanggal)->isWeekend()) continue;
                $normStatus = $this->mapStatusToName($p->status);
                if ($normStatus === 'Hadir') $hadir++;
                elseif ($normStatus === 'Sakit') $sakit++;
                elseif ($normStatus === 'Izin') $izin++;
                elseif ($normStatus === 'Alfa') $alfa++;
            }

            $total = $hadir + $sakit + $izin + $alfa;
            $persentase = $total > 0 ? round(($hadir / $total) * 100, 1) : 0;

            $writer->addRow(Row::fromValues([
                $index + 1,
                $siswa->nis,
                $siswa->nama_siswa,
                $hadir,
                $sakit,
                $izin,
                $alfa,
                $total,
                $persentase . '%'
            ]));
        }

        $writer->close();

        return response()->download($tmpFile, $filename, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        ])->deleteFileAfterSend(true);
    }

    public function laporanPrint(Request $request)
    {
        $id_kelas = $request->get('id_kelas');
        $tanggal_dari = $request->get('tanggal_dari');
        $tanggal_sampai = $request->get('tanggal_sampai');

        if (!$id_kelas || !$tanggal_dari || !$tanggal_sampai) {
            return '<script>alert("Filter tidak lengkap untuk cetak laporan."); window.close();</script>';
        }

        $kelas = Kelas::with(['jurusan', 'guru'])->findOrFail($id_kelas);
        $sekolah = Sekolah::where('id_sekolah', 1)->first();

        $siswaList = UserSiswa::where('id_kelas', $id_kelas)
            ->where('status', 'aktif')
            ->orderBy('nama_siswa')
            ->get();

        $presensiList = Presensi::whereIn('nis', $siswaList->pluck('nis'))
            ->whereBetween('tanggal', [$tanggal_dari, $tanggal_sampai])
            ->get()
            ->groupBy('nis');

        $laporanData = [];
        foreach ($siswaList as $siswa) {
            $siswaPresensi = $presensiList->get($siswa->nis, collect());

            $hadir = 0;
            $sakit = 0;
            $izin = 0;
            $alfa = 0;

            foreach ($siswaPresensi as $p) {
                // Abaikan data di hari Sabtu dan Minggu
                if (Carbon::parse($p->tanggal)->isWeekend()) continue;
                $normStatus = $this->mapStatusToName($p->status);
                if ($normStatus === 'Hadir') $hadir++;
                elseif ($normStatus === 'Sakit') $sakit++;
                elseif ($normStatus === 'Izin') $izin++;
                elseif ($normStatus === 'Alfa') $alfa++;
            }

            $total = $hadir + $sakit + $izin + $alfa;
            $persentase = $total > 0 ? round(($hadir / $total) * 100, 1) : 0;

            $laporanData[] = [
                'nis'        => $siswa->nis,
                'nama_siswa' => $siswa->nama_siswa,
                'hadir'      => $hadir,
                'sakit'      => $sakit,
                'izin'       => $izin,
                'alfa'       => $alfa,
                'total'      => $total,
                'persentase' => $persentase,
            ];
        }

        return view('presensi-siswa.print', compact('kelas', 'sekolah', 'tanggal_dari', 'tanggal_sampai', 'laporanData'));
    }

    public function rekapPrint(Request $request)
    {
        $id_kelas = $request->get('id_kelas');
        $bulan = $request->get('bulan');

        if (!$id_kelas || !$bulan) {
            return '<script>alert("Filter tidak lengkap untuk cetak rekap."); window.close();</script>';
        }

        $kelas = Kelas::with(['jurusan', 'guru'])->findOrFail($id_kelas);
        $sekolah = Sekolah::where('id_sekolah', 1)->first();
        $waliKelas = $kelas->guru ? $kelas->guru->nama_guru : null;

        $siswaList = UserSiswa::where('id_kelas', $id_kelas)
            ->where('status', 'aktif')
            ->orderBy('nama_siswa')
            ->get();

        [$year, $month] = explode('-', $bulan);
        $daysInMonth = Carbon::create($year, $month)->daysInMonth;

        $rekapData = [];
        if ($siswaList->isNotEmpty()) {
            $presensiList = Presensi::whereIn('nis', $siswaList->pluck('nis'))
                ->whereYear('tanggal', $year)
                ->whereMonth('tanggal', $month)
                ->get()
                ->groupBy('nis');

            foreach ($siswaList as $siswa) {
                $siswaPresensi = $presensiList->get($siswa->nis, collect())->keyBy(function($p) {
                    return (int) Carbon::parse($p->tanggal)->format('d');
                });

                $hadir = 0;
                $sakit = 0;
                $izin = 0;
                $alfa = 0;
                $grid = [];

                for ($d = 1; $d <= $daysInMonth; $d++) {
                    $date = Carbon::create($year, $month, $d);
                    if ($date->isWeekend()) {
                        $grid[$d] = 'W'; // weekend — tidak dihitung
                        continue;
                    }
                    $p = $siswaPresensi->get($d);
                    if ($p) {
                        $normStatus = $this->mapStatusToName($p->status);
                        if ($normStatus === 'Hadir') {
                            $hadir++;
                            $grid[$d] = 'H';
                        } elseif ($normStatus === 'Sakit') {
                            $sakit++;
                            $grid[$d] = 'S';
                        } elseif ($normStatus === 'Izin') {
                            $izin++;
                            $grid[$d] = 'I';
                        } else {
                            $alfa++;
                            $grid[$d] = 'A';
                        }
                    } else {
                        $grid[$d] = '-';
                    }
                }

                $total = $hadir + $sakit + $izin + $alfa;
                $persentase = $total > 0 ? round(($hadir / $total) * 100, 1) : 0;

                $rekapData[] = [
                    'siswa'      => $siswa,
                    'grid'       => $grid,
                    'hadir'      => $hadir,
                    'sakit'      => $sakit,
                    'izin'       => $izin,
                    'alfa'       => $alfa,
                    'persentase' => $persentase,
                ];
            }
        }

        return view('presensi-siswa.rekap-print', compact(
            'kelas', 'sekolah', 'waliKelas', 'bulan', 'siswaList', 'daysInMonth', 'year', 'month', 'rekapData'
        ));
    }
}
