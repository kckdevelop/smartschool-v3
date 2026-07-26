<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Presensi;
use App\Models\UserSiswa;
use App\Models\Kelas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PresensiSiswaController extends Controller
{
    /**
     * Daftar presensi siswa.
     *
     * @queryParam tanggal date Filter tanggal. Example: 2025-01-15
     * @queryParam id_kelas int Filter kelas. Example: 1
     * @queryParam nis int Filter siswa. Example: 12345
     * @queryParam status string Filter status: Hadir|Sakit|Izin|Alfa. Example: Hadir
     * @queryParam per_page int Jumlah per halaman (default 25). Example: 25
     */
    public function index(Request $request)
    {
        $query = Presensi::with(['siswa:nis,nama_siswa,id_kelas', 'siswa.kelas']);

        if ($request->filled('tanggal')) {
            $query->whereDate('tanggal', $request->tanggal);
        }
        if ($request->filled('id_kelas')) {
            $query->whereHas('siswa', fn($q) => $q->where('id_kelas', $request->id_kelas));
        }
        if ($request->filled('nis')) {
            $query->where('nis', $request->nis);
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $perPage = $request->get('per_page', 25);
        $data    = $query->orderByDesc('tanggal')->orderBy('nis')->paginate($perPage);

        return response()->json([
            'success' => true,
            'data'    => $data,
        ]);
    }

    /**
     * Simpan presensi baru.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nis'        => 'required|integer|exists:user_siswa,nis',
            'tanggal'    => 'required|date',
            'jam'        => 'nullable|string',
            'status'     => 'required|in:Hadir,Sakit,Izin,Alfa,hadir,sakit,izin,alfa,1,0',
            'keterangan' => 'nullable|string|max:255',
        ]);

        // Cek apakah sudah ada presensi hari ini
        $exists = Presensi::where('nis', $request->nis)
            ->whereDate('tanggal', $request->tanggal)
            ->exists();

        if ($exists) {
            return response()->json([
                'success' => false,
                'message' => 'Presensi siswa pada tanggal ini sudah ada.',
            ], 422);
        }

        $filePath = \App\Helpers\FileUploadHelper::storeFile($request, 'file', 'presensi-lampiran');

        $statusInput = $request->status;
        if ($statusInput === '1') {
            $statusInput = 'Hadir';
        } elseif ($statusInput === '0') {
            $statusInput = 'Alfa';
        }
        $status = ucfirst(strtolower($statusInput));

        $presensi = Presensi::create([
            'nis'        => $request->nis,
            'tanggal'    => $request->tanggal,
            'jam'        => $request->jam,
            'status'     => $status,
            'keterangan' => $request->keterangan ?? 'Input manual',
            'file'       => $filePath,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Presensi berhasil disimpan.',
            'data'    => $presensi->load('siswa:nis,nama_siswa'),
        ], 201);
    }

    /**
     * Detail presensi.
     */
    public function show($id)
    {
        $presensi = Presensi::with('siswa:nis,nama_siswa,id_kelas')->findOrFail($id);

        return response()->json([
            'success' => true,
            'data'    => $presensi,
        ]);
    }

    /**
     * Update presensi.
     */
    public function update(Request $request, $id)
    {
        $presensi = Presensi::findOrFail($id);

        $request->validate([
            'status'     => 'sometimes|required|in:Hadir,Sakit,Izin,Alfa,1,0',
            'keterangan' => 'nullable|string|max:255',
            'jam'        => 'nullable|string',
        ]);

        $presensi->update($request->only('status', 'keterangan', 'jam'));

        return response()->json([
            'success' => true,
            'message' => 'Presensi berhasil diperbarui.',
            'data'    => $presensi,
        ]);
    }

    /**
     * Hapus presensi.
     */
    public function destroy($id)
    {
        Presensi::findOrFail($id)->delete();

        return response()->json([
            'success' => true,
            'message' => 'Presensi berhasil dihapus.',
        ]);
    }

    /**
     * Rekap kehadiran per kelas per periode.
     *
     * @queryParam id_kelas int required ID kelas. Example: 1
     * @queryParam bulan string required Format YYYY-MM. Example: 2025-01
     */
    public function rekap(Request $request)
    {
        $request->validate([
            'id_kelas'    => 'required|integer|exists:kelas,id_kelas',
            'bulan'       => 'nullable|string|regex:/^\d{4}-\d{2}$/',
            'id_semester' => 'nullable|integer|exists:semester,id_semester',
        ]);

        if (!$request->filled('bulan') && !$request->filled('id_semester')) {
            return response()->json([
                'success' => false,
                'message' => 'Filter bulan atau id_semester harus diisi.',
            ], 422);
        }

        $kelas = Kelas::with('siswa')->findOrFail($request->id_kelas);

        $dateRange = null;
        if ($request->filled('id_semester')) {
            $sem = \App\Models\Semester::findOrFail($request->id_semester);
            $dateRange = [
                'awal'  => $sem->awal instanceof \Carbon\Carbon ? $sem->awal->toDateString() : $sem->awal,
                'akhir' => $sem->akhir instanceof \Carbon\Carbon ? $sem->akhir->toDateString() : $sem->akhir,
            ];
        }

        $rekap = [];
        foreach ($kelas->siswa as $siswa) {
            $query = Presensi::where('nis', $siswa->nis);

            if ($dateRange && $dateRange['awal'] && $dateRange['akhir']) {
                $query->whereBetween('tanggal', [$dateRange['awal'], $dateRange['akhir']]);
            } else {
                $query->whereYear('tanggal', substr($request->bulan, 0, 4))
                      ->whereMonth('tanggal', substr($request->bulan, 5, 2));
            }

            $counts = $query->selectRaw('status, count(*) as total')
                ->groupBy('status')
                ->pluck('total', 'status')
                ->toArray();

            // Normalisasi — status bisa berupa string ('Hadir','Sakit','Izin','Alfa','Alpha')
            // maupun kode numerik ('1','2','3','4') tergantung dari mana data diinputkan.
            $hadir = ($counts['Hadir'] ?? 0) + ($counts['hadir'] ?? 0) + ($counts['1']  ?? 0);
            $sakit = ($counts['Sakit'] ?? 0) + ($counts['sakit'] ?? 0) + ($counts['2']  ?? 0);
            $izin  = ($counts['Izin']  ?? 0) + ($counts['izin']  ?? 0) + ($counts['3']  ?? 0);
            $alfa  = ($counts['Alfa']  ?? 0) + ($counts['alfa']  ?? 0)
                   + ($counts['Alpha'] ?? 0) + ($counts['alpha'] ?? 0) + ($counts['4']  ?? 0)
                   + ($counts['0']  ?? 0);

            $rekap[] = [
                'nis'        => $siswa->nis,
                'nama_siswa' => $siswa->nama_siswa,
                'hadir'      => $hadir,
                'sakit'      => $sakit,
                'izin'       => $izin,
                'alfa'       => $alfa,
            ];
        }

        return response()->json([
            'success'     => true,
            'kelas'       => $kelas->nama_kelas,
            'bulan'       => $request->bulan,
            'id_semester' => $request->id_semester,
            'data'        => $rekap,
        ]);
    }

    /**
     * Input presensi bulk (satu kelas sekaligus).
     */
    public function inputBulk(Request $request)
    {
        $request->validate([
            'tanggal'               => 'required|date',
            'presensi'              => 'required|array',
            'presensi.*.nis'        => 'required|integer|exists:user_siswa,nis',
            'presensi.*.status'     => 'required|in:Hadir,Sakit,Izin,Alfa,hadir,sakit,izin,alfa',
            'presensi.*.keterangan' => 'nullable|string|max:255',
        ]);

        $inserted = 0;
        $updated  = 0;

        DB::beginTransaction();
        try {
            foreach ($request->presensi as $index => $item) {
                $presensi = Presensi::where('nis', $item['nis'])
                    ->whereDate('tanggal', $request->tanggal)
                    ->first();

                $status = ucfirst(strtolower($item['status']));

                $newFile = \App\Helpers\FileUploadHelper::storeFile($request, "presensi.{$index}.file", 'presensi-lampiran');
                if (!$newFile && isset($item['file'])) {
                    $newFile = \App\Helpers\FileUploadHelper::storeFile($item['file'], null, 'presensi-lampiran');
                }

                $filePath = $presensi ? $presensi->file : null;

                if ($newFile) {
                    if ($presensi && $presensi->file && \Storage::disk('public')->exists($presensi->file)) {
                        \Storage::disk('public')->delete($presensi->file);
                    }
                    $filePath = $newFile;
                }

                if ($presensi) {
                    $presensi->update([
                        'status'     => $status,
                        'keterangan' => $item['keterangan'] ?? $presensi->keterangan,
                        'file'       => $filePath,
                    ]);
                    $updated++;
                } else {
                    Presensi::create([
                        'nis'        => $item['nis'],
                        'tanggal'    => $request->tanggal,
                        'status'     => $status,
                        'keterangan' => $item['keterangan'] ?? 'Input manual',
                        'file'       => $filePath,
                    ]);
                    $inserted++;
                }
            }
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage(),
            ], 500);
        }

        return response()->json([
            'success'  => true,
            'message'  => "Presensi berhasil disimpan. Disimpan: {$inserted}, Diperbarui: {$updated}.",
            'inserted' => $inserted,
            'updated'  => $updated,
        ]);
    }

    /**
     * Ambil data presensi siswa berdasarkan bulan, tahun, dan NIS.
     * 
     * @queryParam bulan int required Bulan (1-12). Example: 1
     * @queryParam tahun int required Tahun. Example: 2025
     * @queryParam nis int required NIS siswa. Example: 12345
     * @queryParam per_page int Jumlah per halaman (default 50). Example: 50
     */
    public function getByMonthYearNis(Request $request)
    {
        $request->validate([
            'bulan'    => 'required|integer|min:1|max:12',
            'tahun'    => 'required|integer|min:2000|max:2099',
            'nis'      => 'required|integer|exists:user_siswa,nis',
            'per_page' => 'nullable|integer|min:1|max:500',
        ]);

        $bulan   = $request->bulan;
        $tahun   = $request->tahun;
        $nis     = $request->nis;
        $perPage = $request->get('per_page', 50);

        $data = Presensi::where('nis', $nis)
            ->whereYear('tanggal', $tahun)
            ->whereMonth('tanggal', $bulan)
            ->orderBy('tanggal')
            ->orderBy('jam')
            ->paginate($perPage);

        // Tambahkan informasi siswa
        $siswa = UserSiswa::select('nis', 'nama_siswa', 'id_kelas')
            ->with('kelas')
            ->where('nis', $nis)
            ->first();

        return response()->json([
            'success' => true,
            'siswa'   => $siswa,
            'filters' => [
                'bulan' => $bulan,
                'tahun' => $tahun,
                'nis'   => $nis,
            ],
            'data'    => $data,
        ]);
    }

    /**
     * Ambil data presensi siswa per tanggal dan NIS.
     * Menampilkan status, keterangan, dan file.
     * 
     * @queryParam tanggal date required Tanggal presensi (format: Y-m-d). Example: 2025-01-15
     * @queryParam nis int required NIS siswa. Example: 12345
     */
    public function getByTanggalNis(Request $request)
    {
        $request->validate([
            'tanggal' => 'required|date_format:Y-m-d',
            'nis'     => 'required|integer|exists:user_siswa,nis',
        ]);

        $tanggal = $request->tanggal;
        $nis     = $request->nis;

        $presensi = Presensi::where('nis', $nis)
            ->whereDate('tanggal', $tanggal)
            ->first();

        if (!$presensi) {
            return response()->json([
                'success' => false,
                'message' => 'Data presensi tidak ditemukan untuk siswa dan tanggal tersebut.',
            ], 404);
        }

        // Ambil info siswa
        $siswa = UserSiswa::select('nis', 'nama_siswa', 'id_kelas')
            ->with('kelas')
            ->where('nis', $nis)
            ->first();

        return response()->json([
            'success' => true,
            'siswa'   => $siswa,
            'presensi' => [
                'id_presensi' => $presensi->id_presensi,
                'nis'         => $presensi->nis,
                'tanggal'     => $presensi->tanggal,
                'jam'         => $presensi->jam,
                'status'      => $presensi->status,
                'keterangan'  => $presensi->keterangan,
                'file'        => $presensi->file,
            ],
        ]);
    }
    /**
     * Ambil kalender presensi siswa untuk satu bulan penuh.
     * Mengembalikan setiap hari dalam bulan beserta data presensinya (atau null jika belum absen).
     *
     * Endpoint: GET /api/presensi/siswa
     * @queryParam nis int required NIS siswa. Example: 13862
     * @queryParam bulan int required Bulan (1-12). Example: 7
     * @queryParam tahun int required Tahun. Example: 2026
     */
    public function getMonthlyCalendar(Request $request)
    {
        $request->validate([
            'nis'   => 'required|integer|exists:user_siswa,nis',
            'bulan' => 'required|integer|min:1|max:12',
            'tahun' => 'required|integer|min:2000|max:2099',
        ]);

        $nis   = $request->nis;
        $bulan = (int) $request->bulan;
        $tahun = (int) $request->tahun;

        // Ambil seluruh presensi siswa di bulan tersebut (key: tanggal string)
        $presensiMap = Presensi::where('nis', $nis)
            ->whereYear('tanggal', $tahun)
            ->whereMonth('tanggal', $bulan)
            ->get()
            ->keyBy(fn($p) => \Carbon\Carbon::parse($p->tanggal)->toDateString());

        // Nama hari dalam Bahasa Indonesia
        $hariIndo = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];

        $calendar = [];
        $daysInMonth = \Carbon\Carbon::create($tahun, $bulan, 1)->daysInMonth;

        for ($day = 1; $day <= $daysInMonth; $day++) {
            $date      = \Carbon\Carbon::create($tahun, $bulan, $day);
            $dateStr   = $date->toDateString();
            $isWeekend = $date->isWeekend();
            $hariName  = $hariIndo[$date->dayOfWeek];

            $presensi = $presensiMap->get($dateStr);

            $calendar[] = [
                'tanggal'    => $dateStr,
                'hari'       => $hariName,
                'is_weekend' => $isWeekend,
                'presensi'   => $presensi ? [
                    'id_presensi' => $presensi->id_presensi,
                    'id'          => $presensi->id_presensi,
                    'nis'         => $presensi->nis,
                    'tanggal'     => $presensi->tanggal,
                    'jam'         => $presensi->jam,
                    'status'      => $presensi->status,
                    'keterangan'  => $presensi->keterangan,
                    'file'        => $presensi->file,
                ] : null,
            ];
        }

        return response()->json([
            'success' => true,
            'nis'     => $nis,
            'bulan'   => $bulan,
            'tahun'   => $tahun,
            'data'    => $calendar,
        ]);
    }

    /**
     * Unduh / Preview Rekap Presensi Siswa PDF (Bulanan / Semester).
     * Endpoint: GET /api/presensi/rekap/pdf
     */
    public function rekapPdf(Request $request)
    {
        $request->validate([
            'id_kelas'    => 'required|integer|exists:kelas,id_kelas',
            'bulan'       => 'nullable|string',
            'id_semester' => 'nullable|integer|exists:semester,id_semester',
            'tipe'        => 'nullable|string|in:bulanan,semester',
        ]);

        $kelas = Kelas::with(['siswa', 'jurusan', 'guru'])->findOrFail($request->id_kelas);
        $sekolah = \App\Models\Sekolah::first() ?? new \App\Models\Sekolah();

        $tipe = $request->get('tipe', $request->filled('id_semester') ? 'semester' : 'bulanan');
        $periodeLabel = '';

        $dateRange = null;
        if ($tipe === 'semester' && $request->filled('id_semester')) {
            $sem = \App\Models\Semester::find($request->id_semester);
            if ($sem) {
                $dateRange = [
                    'awal'  => $sem->awal instanceof \Carbon\Carbon ? $sem->awal->toDateString() : $sem->awal,
                    'akhir' => $sem->akhir instanceof \Carbon\Carbon ? $sem->akhir->toDateString() : $sem->akhir,
                ];
                $periodeLabel = 'Semester ' . ucfirst($sem->semester ?? '') . ' TA ' . ($sem->tahun_ajaran ?? '');
            }
        }

        if (empty($periodeLabel)) {
            $bulanStr = $request->get('bulan', date('Y-m'));
            try {
                $date = \Carbon\Carbon::createFromFormat('Y-m', $bulanStr);
                $bulanIndo = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
                $periodeLabel = 'Bulan ' . $bulanIndo[$date->month - 1] . ' ' . $date->year;
            } catch (\Throwable $e) {
                $periodeLabel = 'Periode ' . $bulanStr;
            }
        }

        $rekap = [];
        foreach ($kelas->siswa as $siswa) {
            $query = Presensi::where('nis', $siswa->nis);

            if ($dateRange && $dateRange['awal'] && $dateRange['akhir']) {
                $query->whereBetween('tanggal', [$dateRange['awal'], $dateRange['akhir']]);
            } else {
                $bulanStr = $request->get('bulan', date('Y-m'));
                $query->whereYear('tanggal', substr($bulanStr, 0, 4))
                      ->whereMonth('tanggal', substr($bulanStr, 5, 2));
            }

            $counts = $query->selectRaw('status, count(*) as total')
                ->groupBy('status')
                ->pluck('total', 'status')
                ->toArray();

            $hadir = ($counts['Hadir'] ?? 0) + ($counts['hadir'] ?? 0) + ($counts['1']  ?? 0);
            $sakit = ($counts['Sakit'] ?? 0) + ($counts['sakit'] ?? 0) + ($counts['2']  ?? 0);
            $izin  = ($counts['Izin']  ?? 0) + ($counts['izin']  ?? 0) + ($counts['3']  ?? 0);
            $alfa  = ($counts['Alfa']  ?? 0) + ($counts['alfa']  ?? 0)
                   + ($counts['Alpha'] ?? 0) + ($counts['alpha'] ?? 0) + ($counts['4']  ?? 0)
                   + ($counts['0']  ?? 0);
            $totalDays = $hadir + $sakit + $izin + $alfa;
            $persenHadir = $totalDays > 0 ? round(($hadir / $totalDays) * 100, 1) : 100;

            $rekap[] = [
                'nis'          => $siswa->nis,
                'nama_siswa'   => $siswa->nama_siswa,
                'hadir'        => $hadir,
                'sakit'        => $sakit,
                'izin'         => $izin,
                'alfa'         => $alfa,
                'total_hari'   => $totalDays,
                'persen_hadir' => $persenHadir,
            ];
        }

        try {
            $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('presensi.rekap-pdf', [
                'kelas'        => $kelas,
                'sekolah'      => $sekolah,
                'periodeLabel' => $periodeLabel,
                'tipe'         => $tipe,
                'rekap'        => $rekap,
                'isPdf'        => true,
            ]);

            return response($pdf->output(), 200)
                ->header('Content-Type', 'application/pdf')
                ->header('Content-Disposition', 'inline; filename="rekap_presensi_' . $kelas->nama_kelas . '.pdf"');
        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal membuat PDF: ' . $e->getMessage(),
            ], 500);
        }
    }
}
