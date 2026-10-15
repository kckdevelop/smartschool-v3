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
        $btaqRaw = Btaq::with(['guru', 'iqroAwal', 'iqroAkhir', 'alquranAwal', 'alquranAkhir'])
            ->where('nis', $siswa->nis)
            ->whereBetween('tanggal', [$awal, $akhir])
            ->orderByDesc('tanggal')
            ->get();

        $btaq = [
            'total'       => $btaqRaw->count(),
            'level_iqro'  => $btaqRaw->filter(fn($b) => stripos($b->level ?? '', 'iqr') !== false)->count(),
            'level_quran' => $btaqRaw->filter(fn($b) => stripos($b->level ?? '', 'qur') !== false)->count(),
            'terakhir'    => $btaqRaw->first(),
            'riwayat'     => $btaqRaw->values(),
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
