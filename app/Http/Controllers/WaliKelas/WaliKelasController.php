<?php

namespace App\Http\Controllers\WaliKelas;

use App\Http\Controllers\Controller;
use App\Models\Kelas;
use App\Models\UserSiswa;
use App\Models\Presensi;
use App\Models\Btaq;
use App\Models\KunjunganUks;
use App\Models\Sekolah;
use App\Models\Semester;
use App\Models\TahunAjaran;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class WaliKelasController extends Controller
{
    /**
     * Halaman Daftar Kelas (Index Cetak Hasil Belajar)
     */
    public function index()
    {
        $sekolah   = Sekolah::first();
        $kelasList = Kelas::with('jurusan', 'guru')
            ->aktif()
            ->orderBy('tingkat')
            ->orderBy('rombel')
            ->get();

        $semester = Semester::where('status', 'aktif')->with('tahunAjaran')->first();

        return view('wali-kelas.cetak-hasil-belajar.index', compact(
            'sekolah', 'kelasList', 'semester'
        ));
    }

    /**
     * Halaman Daftar Siswa Per Kelas
     */
    public function siswa(Request $request, $idKelas)
    {
        $sekolah  = Sekolah::first();
        $kelas    = Kelas::with('jurusan', 'guru')->findOrFail($idKelas);
        $semester = Semester::where('status', 'aktif')->with('tahunAjaran')->first();

        $siswaList = UserSiswa::with('detail')
            ->where('id_kelas', $idKelas)
            ->where('status', 'aktif')
            ->orderBy('nama_siswa')
            ->get();

        return view('wali-kelas.cetak-hasil-belajar.siswa', compact(
            'sekolah', 'kelas', 'siswaList', 'semester'
        ));
    }

    /**
     * Preview Raport Siswa (HTML di browser)
     */
    public function preview(Request $request, $nis)
    {
        $sekolah  = Sekolah::first();
        $semester = Semester::where('status', 'aktif')->with('tahunAjaran')->first();

        $siswa = UserSiswa::with(['detail', 'kelas.jurusan', 'kelas.guru'])->findOrFail($nis);

        [$presensi, $btaq, $kunjunganUks] = $this->loadDataRaport($siswa, $semester);

        return view('wali-kelas.cetak-hasil-belajar.preview', compact(
            'sekolah', 'siswa', 'semester', 'presensi', 'btaq', 'kunjunganUks'
        ) + ['isPdf' => false, 'isPreview' => true]);
    }

    /**
     * Download / Cetak Raport Siswa (PDF)
     */
    public function downloadPdf(Request $request, $nis)
    {
        $sekolah  = Sekolah::first();
        $semester = Semester::where('status', 'aktif')->with('tahunAjaran')->first();

        $siswa = UserSiswa::with(['detail', 'kelas.jurusan', 'kelas.guru'])->findOrFail($nis);

        [$presensi, $btaq, $kunjunganUks] = $this->loadDataRaport($siswa, $semester);

        $pdf = Pdf::loadView('wali-kelas.cetak-hasil-belajar.pdf', compact(
            'sekolah', 'siswa', 'semester', 'presensi', 'btaq', 'kunjunganUks'
        ) + ['isPdf' => true])->setPaper('a4', 'portrait');

        $namaSiswa = str_replace(' ', '_', $siswa->nama_siswa);
        $filename  = "Raport_{$namaSiswa}_{$siswa->nis}.pdf";

        return $pdf->download($filename);
    }

    /**
     * Download Raport Semua Siswa dalam 1 Kelas (1 file PDF)
     */
    public function downloadPdfKelas(Request $request, $idKelas)
    {
        @ini_set('memory_limit', '1024M');
        @set_time_limit(600);

        $sekolah  = Sekolah::first();
        $semester = Semester::where('status', 'aktif')->with('tahunAjaran')->first();
        $kelas    = Kelas::with('jurusan', 'guru')->findOrFail($idKelas);

        $siswaList = UserSiswa::with(['detail', 'kelas.jurusan', 'kelas.guru'])
            ->where('id_kelas', $idKelas)
            ->where('status', 'aktif')
            ->orderBy('nama_siswa')
            ->get();

        if ($siswaList->isEmpty()) {
            return redirect()->back()->with('error', 'Tidak ada data siswa aktif di kelas ini.');
        }

        $allNis = $siswaList->pluck('nis')->toArray();
        $awal   = $semester?->awal  ?? Carbon::now()->startOfYear();
        $akhir  = $semester?->akhir ?? Carbon::now();

        // ── 1. Bulk Queries (Hindari ratusan query N+1) ──────────────────────
        $allPresensi = Presensi::whereIn('nis', $allNis)
            ->whereBetween('tanggal', [$awal, $akhir])
            ->get()
            ->groupBy('nis');

        $allBtaq = Btaq::with(['guru', 'iqroAwal', 'iqroAkhir', 'alquranAwal', 'alquranAkhir'])
            ->whereIn('nis', $allNis)
            ->whereBetween('tanggal', [$awal, $akhir])
            ->orderBy('tanggal', 'asc')
            ->orderBy('id_btaq', 'asc')
            ->get()
            ->groupBy('nis');

        $allPrevBtaq = Btaq::with(['guru', 'iqroAwal', 'iqroAkhir', 'alquranAwal', 'alquranAkhir'])
            ->whereIn('nis', $allNis)
            ->where('tanggal', '<', $awal)
            ->orderByDesc('tanggal')
            ->orderByDesc('id_btaq')
            ->get()
            ->groupBy('nis');

        $allUks = KunjunganUks::with('riwayatObat')
            ->whereIn('nis', $allNis)
            ->whereBetween('tanggal', [$awal, $akhir])
            ->orderByDesc('tanggal')
            ->get()
            ->groupBy('nis');

        // ── 2. Mapping Data Per Siswa in-memory ───────────────────────────────
        $dataRaport = $siswaList->map(function ($siswa) use ($allPresensi, $allBtaq, $allPrevBtaq, $allUks) {
            $nis = $siswa->nis;

            // Presensi
            $presensiRaw = $allPresensi->get($nis, collect());
            $hadir = 0; $sakit = 0; $izin = 0; $alpha = 0;
            foreach ($presensiRaw as $p) {
                $st = strval($p->status);
                if (in_array($st, ['1', 'Hadir', 'hadir', 'H', 'h'], true)) {
                    $hadir++;
                } elseif (in_array($st, ['2', 'Sakit', 'sakit', 'S', 's'], true)) {
                    $sakit++;
                } elseif (in_array($st, ['3', 'Izin', 'izin', 'I', 'i'], true)) {
                    $izin++;
                } elseif (in_array($st, ['4', 'Alfa', 'alfa', 'Alpha', 'alpha', 'A', 'a'], true)) {
                    $alpha++;
                }
            }
            $presensi = [
                'hadir'   => $hadir,
                'izin'    => $izin,
                'sakit'   => $sakit,
                'alpha'   => $alpha,
                'total'   => $presensiRaw->count(),
                'riwayat' => $presensiRaw->sortByDesc('tanggal')->values(),
            ];

            // BTAQ
            $btaqChronological = $allBtaq->get($nis, collect());
            $prevBtaq = $allPrevBtaq->get($nis, collect())->first();

            foreach ($btaqChronological as $b) {
                $isIqro = (stripos($b->level ?? '', 'iqr') !== false);
                $prevIsIqro = $prevBtaq ? (stripos($prevBtaq->level ?? '', 'iqr') !== false) : null;

                if (!empty($b->awal) && !empty($b->akhir) && (string)$b->awal !== (string)$b->akhir) {
                    $b->awal_display = $b->awal_formatted;
                } elseif ($prevBtaq && $isIqro === $prevIsIqro) {
                    $b->awal_display = $prevBtaq->akhir_formatted;
                } else {
                    if ($isIqro) {
                        $jilid = $b->iqroAkhir?->jilid ?? 1;
                        $b->awal_display = "Jilid {$jilid}, Hal. 1";
                    } else {
                        $surat = $b->alquranAkhir?->surat ?? 'Al-Baqarah';
                        $b->awal_display = "QS. {$surat}: 1";
                    }
                }
                $b->akhir_display = $b->akhir_formatted;
                $prevBtaq = $b;
            }

            $riwayatBtaq = $btaqChronological->sortByDesc(function ($item) {
                return $item->tanggal . '_' . str_pad($item->id_btaq, 8, '0', STR_PAD_LEFT);
            })->values();

            $btaq = [
                'total'       => $riwayatBtaq->count(),
                'level_iqro'  => $riwayatBtaq->filter(fn($b) => stripos($b->level ?? '', 'iqr') !== false)->count(),
                'level_quran' => $riwayatBtaq->filter(fn($b) => stripos($b->level ?? '', 'qur') !== false)->count(),
                'terakhir'    => $riwayatBtaq->first(),
                'riwayat'     => $riwayatBtaq,
            ];

            // UKS
            $uksRaw = $allUks->get($nis, collect());
            $kunjunganUks = [
                'total'   => $uksRaw->count(),
                'riwayat' => $uksRaw->take(20)->values(),
            ];

            return compact('siswa', 'presensi', 'btaq', 'kunjunganUks');
        });

        // ── 3. Precompute Kop & Logo sekali saja (hemat memory & I/O) ────────
        $kopSrc = '';
        if ($sekolah && $sekolah->kop) {
            $kopPath = storage_path('app/public/' . $sekolah->kop);
            if (file_exists($kopPath)) {
                $kopSrc = 'data:image/' . pathinfo($kopPath, PATHINFO_EXTENSION) . ';base64,' . base64_encode(file_get_contents($kopPath));
            }
        }
        $logoSrc = '';
        if ($sekolah && $sekolah->logo) {
            $logoPath = storage_path('app/public/' . $sekolah->logo);
            if (file_exists($logoPath)) {
                $logoSrc = 'data:image/' . pathinfo($logoPath, PATHINFO_EXTENSION) . ';base64,' . base64_encode(file_get_contents($logoPath));
            }
        }

        // ── 4. Render PDF dengan opsi hemat memori ───────────────────────────
        $pdf = Pdf::loadView('wali-kelas.cetak-hasil-belajar.pdf-kelas', compact(
            'sekolah', 'semester', 'kelas', 'dataRaport', 'kopSrc', 'logoSrc'
        ) + ['isPdf' => true])
            ->setPaper('a4', 'portrait')
            ->setOption('isHtml5ParserEnabled', true)
            ->setOption('isRemoteEnabled', false);

        $namaKelas = str_replace([' ', '/'], '_', $kelas->nama_kelas);
        $filename  = "Raport_Kelas_{$namaKelas}.pdf";

        return $pdf->download($filename);
    }

    // ─────────────────────────────────────────────────────────────────────────
    //  PRIVATE HELPERS
    // ─────────────────────────────────────────────────────────────────────────

    private function loadDataRaport(UserSiswa $siswa, ?Semester $semester): array
    {
        $awal  = $semester?->awal  ?? Carbon::now()->startOfYear();
        $akhir = $semester?->akhir ?? Carbon::now();

        // ── Presensi ──────────────────────────────────────────────────────────
        $presensiRaw = Presensi::where('nis', $siswa->nis)
            ->whereBetween('tanggal', [$awal, $akhir])
            ->get();

        $hadir = 0;
        $sakit = 0;
        $izin  = 0;
        $alpha = 0;

        foreach ($presensiRaw as $p) {
            $st = strval($p->status);
            if (in_array($st, ['1', 'Hadir', 'hadir', 'H', 'h'], true)) {
                $hadir++;
            } elseif (in_array($st, ['2', 'Sakit', 'sakit', 'S', 's'], true)) {
                $sakit++;
            } elseif (in_array($st, ['3', 'Izin', 'izin', 'I', 'i'], true)) {
                $izin++;
            } elseif (in_array($st, ['4', 'Alfa', 'alfa', 'Alpha', 'alpha', 'A', 'a'], true)) {
                $alpha++;
            }
        }

        $presensi = [
            'hadir'   => $hadir,
            'izin'    => $izin,
            'sakit'   => $sakit,
            'alpha'   => $alpha,
            'total'   => $presensiRaw->count(),
            'riwayat' => $presensiRaw->sortByDesc('tanggal')->values(),
        ];

        // ── BTAQ ──────────────────────────────────────────────────────────────
        // Ambil data BTAQ semester ini secara kronologis untuk menghubungkan sesi awal -> akhir
        $btaqChronological = Btaq::with(['guru', 'iqroAwal', 'iqroAkhir', 'alquranAwal', 'alquranAkhir'])
            ->where('nis', $siswa->nis)
            ->whereBetween('tanggal', [$awal, $akhir])
            ->orderBy('tanggal', 'asc')
            ->orderBy('id_btaq', 'asc')
            ->get();

        // Cari sesi BTAQ terakhir sebelum awal semester ini jika ada
        $firstTanggal = $btaqChronological->first()?->tanggal;
        $prevBtaq = null;
        if ($firstTanggal) {
            $prevBtaq = Btaq::with(['guru', 'iqroAwal', 'iqroAkhir', 'alquranAwal', 'alquranAkhir'])
                ->where('nis', $siswa->nis)
                ->where('tanggal', '<', $firstTanggal)
                ->orderByDesc('tanggal')
                ->orderByDesc('id_btaq')
                ->first();
        }

        foreach ($btaqChronological as $b) {
            $isIqro = (stripos($b->level ?? '', 'iqr') !== false);
            $prevIsIqro = $prevBtaq ? (stripos($prevBtaq->level ?? '', 'iqr') !== false) : null;

            // 1. Tentukan AWAL bacaan
            if (!empty($b->awal) && !empty($b->akhir) && (string)$b->awal !== (string)$b->akhir) {
                // Jika di database sudah tersimpan nilai awal yang valid dan berbeda dari akhir
                $b->awal_display = $b->awal_formatted;
            } elseif ($prevBtaq && $isIqro === $prevIsIqro) {
                // Sesi lanjutan pada level yang sama: awal = akhir sesi sebelumnya
                $b->awal_display = $prevBtaq->akhir_formatted;
            } else {
                // Sesi pertama kali atau baru naik ke level Al-Qur'an
                if ($isIqro) {
                    $jilid = $b->iqroAkhir?->jilid ?? 1;
                    $b->awal_display = "Jilid {$jilid}, Hal. 1";
                } else {
                    $surat = $b->alquranAkhir?->surat ?? 'Al-Baqarah';
                    $b->awal_display = "QS. {$surat}: 1";
                }
            }

            // 2. Tentukan AKHIR bacaan
            $b->akhir_display = $b->akhir_formatted;

            // Update sesi sebelumnya untuk iterasi berikutnya
            $prevBtaq = $b;
        }

        $riwayatBtaq = $btaqChronological->sortByDesc(function ($item) {
            return $item->tanggal . '_' . str_pad($item->id_btaq, 8, '0', STR_PAD_LEFT);
        })->values();

        $btaq = [
            'total'       => $riwayatBtaq->count(),
            'level_iqro'  => $riwayatBtaq->filter(fn($b) => stripos($b->level ?? '', 'iqr') !== false)->count(),
            'level_quran' => $riwayatBtaq->filter(fn($b) => stripos($b->level ?? '', 'qur') !== false)->count(),
            'terakhir'    => $riwayatBtaq->first(),
            'riwayat'     => $riwayatBtaq,
        ];

        // ── Kunjungan UKS ─────────────────────────────────────────────────────
        $uksRaw = KunjunganUks::with('riwayatObat')
            ->where('nis', $siswa->nis)
            ->whereBetween('tanggal', [$awal, $akhir])
            ->orderByDesc('tanggal')
            ->get();

        $kunjunganUks = [
            'total'   => $uksRaw->count(),
            'riwayat' => $uksRaw->take(20)->values(),
        ];

        return [$presensi, $btaq, $kunjunganUks];
    }
}
