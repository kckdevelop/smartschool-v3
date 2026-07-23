<?php

namespace App\Http\Controllers\Bk;

use App\Http\Controllers\Controller;
use App\Models\BimbinganKonseling;
use App\Models\BukuKasus;
use App\Models\GayaBelajar;
use App\Models\Presensi;
use App\Models\RiwayatPoin;
use App\Models\RiwayatReward;
use App\Models\HomeVisit;
use App\Models\PanggilOrtu;
use App\Models\Semester;
use App\Models\TahunAjaran;
use App\Models\UserSiswa;
use App\Models\Kelas;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $today = Carbon::today()->toDateString();
        
        // Ambil tahun ajaran aktif
        $tahunAktif = TahunAjaran::where('status', 'aktif')->first();
        if ($tahunAktif && preg_match('/^(\d{4})\/(\d{4})$/', $tahunAktif->tahun, $matches)) {
            $startYear = (int)$matches[1];
            $endYear = (int)$matches[2];
            $tahunAjaranNama = $tahunAktif->tahun;
        } else {
            $startYear = Carbon::now()->month >= 7 ? Carbon::now()->year : Carbon::now()->year - 1;
            $endYear = $startYear + 1;
            $tahunAjaranNama = "{$startYear}/{$endYear}";
        }

        // Susun 12 bulan akademik (Juli s.d. Juni)
        $monthLabelsMapping = [
            1 => 'Jan', 2 => 'Feb', 3 => 'Mar', 4 => 'Apr', 5 => 'Mei', 6 => 'Jun',
            7 => 'Jul', 8 => 'Ags', 9 => 'Sep', 10 => 'Okt', 11 => 'Nov', 12 => 'Des'
        ];

        $chartMonths = [];
        for ($m = 7; $m <= 12; $m++) {
            $chartMonths[] = [
                'month' => $m,
                'year' => $startYear,
                'label' => $monthLabelsMapping[$m]
            ];
        }
        for ($m = 1; $m <= 6; $m++) {
            $chartMonths[] = [
                'month' => $m,
                'year' => $endYear,
                'label' => $monthLabelsMapping[$m]
            ];
        }

        $months = array_column($chartMonths, 'label');

        // ── 1. Summary Stats ──
        $countPelanggaran = RiwayatPoin::count();
        $sumPelanggaranPoin = RiwayatPoin::sum('poin');
        
        $countReward = RiwayatReward::count();
        $sumRewardPoin = RiwayatReward::sum('point_reward');

        $countBukuKasus = BukuKasus::count();
        $countBukuKasusProses = BukuKasus::where('status', 'proses')->count();

        $countBukuKonsultasi = BimbinganKonseling::count();
        $countBukuKonsultasiProses = BimbinganKonseling::where('status', 'proses')->count();

        $countPanggilOrtu = PanggilOrtu::count();
        $countPanggilOrtuBelumHadir = PanggilOrtu::where('status', 'belum_hadir')->count();

        $countHomeVisit = HomeVisit::count();
        $countHomeVisitDijadwalkan = HomeVisit::where('status', 'dijadwalkan')->count();

        // ── 2. Monthly Trends (Incidents Count) ──
        $pelanggaranPerBulan = [];
        $rewardPerBulan = [];
        foreach ($chartMonths as $cm) {
            $pelanggaranPerBulan[] = RiwayatPoin::whereYear('tgl_input', $cm['year'])
                ->whereMonth('tgl_input', $cm['month'])
                ->count();

            $rewardPerBulan[] = RiwayatReward::whereYear('tgl_input', $cm['year'])
                ->whereMonth('tgl_input', $cm['month'])
                ->count();
        }

        // ── 3. Top Kategori Pelanggaran ──
        $topKategoriPelanggaran = RiwayatPoin::select('pelanggaran', DB::raw('count(*) as total'))
            ->groupBy('pelanggaran')
            ->orderByDesc('total')
            ->limit(5)
            ->get();

        // ── 4. Status Breakdown ──
        $statusKasus = [
            'proses' => BukuKasus::where('status', 'proses')->count(),
            'selesai' => BukuKasus::where('status', 'selesai')->count(),
        ];

        $statusKonsultasi = [
            'proses' => BimbinganKonseling::where('status', 'proses')->count(),
            'selesai' => BimbinganKonseling::where('status', 'selesai')->count(),
        ];

        // ── 5. Top Siswa Bermasalah (Poin Pelanggaran Terbanyak) ──
        $topSiswaPelanggaran = RiwayatPoin::select('nis', DB::raw('SUM(poin) as total_poin'), DB::raw('count(*) as total_kasus'))
            ->with(['siswa.kelas'])
            ->groupBy('nis')
            ->orderByDesc('total_poin')
            ->limit(5)
            ->get();

        // ── 6. Top Siswa Berprestasi (Poin Reward Terbanyak) ──
        $topSiswaReward = RiwayatReward::select('nis', DB::raw('SUM(point_reward) as total_poin'), DB::raw('count(*) as total_reward'))
            ->with(['siswa.kelas'])
            ->groupBy('nis')
            ->orderByDesc('total_poin')
            ->limit(5)
            ->get();

        // ── 7. Recent Cases (Prioritaskan 'proses' lalu terbaru) ──
        $recentBukuKasus = BukuKasus::with(['siswa.kelas', 'guru'])
            ->orderByRaw("FIELD(status, 'proses', 'selesai') ASC")
            ->orderByDesc('tanggal')
            ->limit(5)
            ->get();

        $recentBimbingan = BimbinganKonseling::with(['siswa.kelas', 'guru'])
            ->orderByRaw("FIELD(status, 'proses', 'selesai') ASC")
            ->orderByDesc('tanggal')
            ->limit(5)
            ->get();

        // ══════════════════════════════════════════════════════════════════
        // ── 8. PRESENSI SISWA — Analisis Semester Ini ──
        // ══════════════════════════════════════════════════════════════════
        $semesterAktif = Semester::where('status', 'aktif')->first();

        if ($semesterAktif) {
            $semAwal  = $semesterAktif->awal->toDateString();
            $semAkhir = $semesterAktif->akhir->toDateString();
            $semesterNama = $semesterAktif->semester . ' ' . $tahunAjaranNama;
        } else {
            // Fallback: semester ganjil Juli–Desember atau genap Jan–Juni berdasarkan bulan sekarang
            $now = Carbon::now();
            if ($now->month >= 7) {
                $semAwal  = "{$startYear}-07-01";
                $semAkhir = "{$startYear}-12-31";
                $semesterNama = "Ganjil {$tahunAjaranNama}";
            } else {
                $semAwal  = "{$endYear}-01-01";
                $semAkhir = "{$endYear}-06-30";
                $semesterNama = "Genap {$tahunAjaranNama}";
            }
        }

        // Summary presensi semester ini
        $presensiSummary = Presensi::whereBetween('tanggal', [$semAwal, $semAkhir])
            ->select(DB::raw("
                SUM(CASE WHEN status IN ('1','Hadir') THEN 1 ELSE 0 END) as hadir,
                SUM(CASE WHEN status IN ('2','Sakit') THEN 1 ELSE 0 END) as sakit,
                SUM(CASE WHEN status IN ('3','Izin') THEN 1 ELSE 0 END) as izin,
                SUM(CASE WHEN status IN ('4','Alfa','Alpha') THEN 1 ELSE 0 END) as alfa,
                COUNT(*) as total
            "))
            ->first();

        $totalPresensi   = $presensiSummary->total ?? 0;
        $presensiHadir   = $presensiSummary->hadir ?? 0;
        $presensiSakit   = $presensiSummary->sakit ?? 0;
        $presensiIzin    = $presensiSummary->izin  ?? 0;
        $presensiAlfa    = $presensiSummary->alfa   ?? 0;
        $persenHadir     = $totalPresensi > 0 ? round(($presensiHadir / $totalPresensi) * 100, 1) : 0;

        // Presensi per bulan dalam semester ini (untuk chart tren)
        // Tentukan bulan-bulan dalam rentang semester
        $semAwalCarbon  = Carbon::parse($semAwal);
        $semAkhirCarbon = Carbon::parse($semAkhir);
        $presensiMonths = [];
        $cur = $semAwalCarbon->copy()->startOfMonth();
        while ($cur->lte($semAkhirCarbon)) {
            $presensiMonths[] = [
                'label' => $monthLabelsMapping[$cur->month],
                'year'  => $cur->year,
                'month' => $cur->month,
            ];
            $cur->addMonth();
        }

        $presensiTrenHadir = [];
        $presensiTrenAlfa  = [];
        foreach ($presensiMonths as $pm) {
            $row = Presensi::whereYear('tanggal', $pm['year'])
                ->whereMonth('tanggal', $pm['month'])
                ->select(DB::raw("
                    SUM(CASE WHEN status IN ('1','Hadir') THEN 1 ELSE 0 END) as hadir,
                    SUM(CASE WHEN status IN ('4','Alfa','Alpha') THEN 1 ELSE 0 END) as alfa
                "))
                ->first();
            $presensiTrenHadir[] = $row->hadir ?? 0;
            $presensiTrenAlfa[]  = $row->alfa  ?? 0;
        }
        $presensiTrenLabels = array_column($presensiMonths, 'label');

        // Top 5 siswa paling banyak Alfa semester ini
        $topSiswaAlfa = Presensi::whereBetween('tanggal', [$semAwal, $semAkhir])
            ->whereIn('status', ['4', 'Alfa', 'Alpha'])
            ->select('nis', DB::raw('count(*) as total_alfa'))
            ->with(['siswa.kelas'])
            ->groupBy('nis')
            ->orderByDesc('total_alfa')
            ->limit(5)
            ->get();

        // ══════════════════════════════════════════════════════════════════
        // ── 9. PELANGGARAN (riwayat_poin) — Semester Ini Per Tingkat ──
        // ══════════════════════════════════════════════════════════════════
        $pelanggaranSemesterPerTingkat = RiwayatPoin::whereBetween('tgl_input', [$semAwal, $semAkhir])
            ->select('tingkat', DB::raw('count(*) as total_kasus'), DB::raw('SUM(poin) as total_poin'))
            ->groupBy('tingkat')
            ->orderBy('tingkat')
            ->get();

        // Total pelanggaran semester ini
        $totalPelanggaranSemester = RiwayatPoin::whereBetween('tgl_input', [$semAwal, $semAkhir])->count();
        $totalPoinSemester        = RiwayatPoin::whereBetween('tgl_input', [$semAwal, $semAkhir])->sum('poin');

        // Kasus pelanggaran per bulan dalam semester ini
        $pelanggaranSemesterPerBulan = [];
        foreach ($presensiMonths as $pm) {
            $pelanggaranSemesterPerBulan[] = RiwayatPoin::whereYear('tgl_input', $pm['year'])
                ->whereMonth('tgl_input', $pm['month'])
                ->count();
        }

        // Top 5 jenis pelanggaran semester ini
        $topKategoriSemester = RiwayatPoin::whereBetween('tgl_input', [$semAwal, $semAkhir])
            ->select('pelanggaran', DB::raw('count(*) as total'))
            ->groupBy('pelanggaran')
            ->orderByDesc('total')
            ->limit(5)
            ->get();

        // ══════════════════════════════════════════════════════════════════
        // ── 10. GAYA BELAJAR — Statistik Per Tingkat ──
        // ══════════════════════════════════════════════════════════════════
        // Ambil semua tingkat dari kelas aktif
        $tingkatList = Kelas::where('status', 'aktif')
            ->distinct()
            ->orderBy('tingkat')
            ->pluck('tingkat')
            ->toArray();

        $gayaBelajarPerTingkat = [];
        foreach ($tingkatList as $tingkat) {
            // Ambil NIS semua siswa di tingkat ini
            $nisInTingkat = UserSiswa::whereHas('kelas', function ($q) use ($tingkat) {
                $q->where('tingkat', $tingkat)->where('status', 'aktif');
            })->pluck('nis')->toArray();

            $gbCount = GayaBelajar::whereIn('nis', $nisInTingkat)
                ->select('gaya_belajar', DB::raw('count(*) as total'))
                ->groupBy('gaya_belajar')
                ->pluck('total', 'gaya_belajar')
                ->toArray();

            $gayaBelajarPerTingkat[] = [
                'tingkat'    => $tingkat,
                'visual'     => $gbCount['visual']     ?? 0,
                'auditori'   => $gbCount['auditori']   ?? 0,
                'kinestetik' => $gbCount['kinestetik'] ?? 0,
                'campuran'   => $gbCount['campuran']   ?? 0,
                'total'      => array_sum($gbCount),
            ];
        }

        // Summary gaya belajar keseluruhan
        $gayaBelajarTotal = GayaBelajar::select('gaya_belajar', DB::raw('count(*) as total'))
            ->groupBy('gaya_belajar')
            ->pluck('total', 'gaya_belajar')
            ->toArray();

        return view('bk.dashboard', compact(
            'today',
            'tahunAjaranNama',
            'months',
            'countPelanggaran',
            'sumPelanggaranPoin',
            'countReward',
            'sumRewardPoin',
            'countBukuKasus',
            'countBukuKasusProses',
            'countBukuKonsultasi',
            'countBukuKonsultasiProses',
            'countPanggilOrtu',
            'countPanggilOrtuBelumHadir',
            'countHomeVisit',
            'countHomeVisitDijadwalkan',
            'pelanggaranPerBulan',
            'rewardPerBulan',
            'topKategoriPelanggaran',
            'statusKasus',
            'statusKonsultasi',
            'topSiswaPelanggaran',
            'topSiswaReward',
            'recentBukuKasus',
            'recentBimbingan',
            // Presensi semester ini
            'semesterNama',
            'semAwal',
            'semAkhir',
            'totalPresensi',
            'presensiHadir',
            'presensiSakit',
            'presensiIzin',
            'presensiAlfa',
            'persenHadir',
            'presensiTrenLabels',
            'presensiTrenHadir',
            'presensiTrenAlfa',
            'topSiswaAlfa',
            // Pelanggaran semester ini
            'pelanggaranSemesterPerTingkat',
            'totalPelanggaranSemester',
            'totalPoinSemester',
            'pelanggaranSemesterPerBulan',
            'topKategoriSemester',
            // Gaya belajar per tingkat
            'tingkatList',
            'gayaBelajarPerTingkat',
            'gayaBelajarTotal'
        ));
    }
}
