<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\UserSiswa;
use App\Models\Guru;
use App\Models\Presensi;
use App\Models\Kemajuan;
use App\Models\KunjunganUks;
use App\Models\Kelas;
use App\Models\Tadarus;
use App\Models\Btaq;
use App\Models\RiwayatPoin;
use App\Models\TahunAjaran;
use App\Models\GayaBelajar;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

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
            // Fallback jika tidak ada tahun ajaran aktif atau format tidak sesuai
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
        $currentYear = Carbon::now()->year;

        // ── Summary Stats ──────────────────────────────────────────
        $siswaCount       = UserSiswa::where('status', 'aktif')->count();
        $guruCount        = Guru::count();
        $kelasCount       = Kelas::where('status', 'aktif')->count();
        $presensiHariIni  = Presensi::whereDate('tanggal', $today)
            ->whereHas('siswa', function($q) {
                $q->where('status', 'aktif');
            })->count();
        $kunjunganUks     = KunjunganUks::whereDate('tanggal', $today)
            ->whereHas('siswa', function($q) {
                $q->where('status', 'aktif');
            })->count();
        $tadarusHariIni   = Tadarus::whereDate('tanggal', $today)->count();

        // ── Presensi per bulan (Hadir/Sakit/Izin/Alfa) ─────────────
        $statusMapping = [
            'Hadir' => 1,
            'Sakit' => 2,
            'Izin' => 3,
            'Alfa' => 4
        ];
        $presensiPerBulan = [];
        foreach ($statusMapping as $label => $statusCode) {
            $data = [];
            foreach ($chartMonths as $cm) {
                $data[] = Presensi::whereYear('tanggal', $cm['year'])
                    ->whereMonth('tanggal', $cm['month'])
                    ->where('status', $statusCode)
                    ->whereHas('siswa', function($q) {
                        $q->where('status', 'aktif');
                    })
                    ->count();
            }
            $presensiPerBulan[$label] = $data;
        }

        // ── Kunjungan UKS per bulan ─────────────────────────────────
        $uksPerBulan = [];
        foreach ($chartMonths as $cm) {
            $uksPerBulan[] = KunjunganUks::whereYear('tanggal', $cm['year'])
                ->whereMonth('tanggal', $cm['month'])
                ->whereHas('siswa', function($q) {
                    $q->where('status', 'aktif');
                })
                ->count();
        }

        // ── Pelanggaran (RiwayatPoin) per bulan ────────────────────
        $pelanggaranPerBulan = [];
        foreach ($chartMonths as $cm) {
            $pelanggaranPerBulan[] = RiwayatPoin::whereYear('tgl_input', $cm['year'])
                ->whereMonth('tgl_input', $cm['month'])
                ->whereHas('siswa', function($q) {
                    $q->where('status', 'aktif');
                })
                ->count();
        }

        // ── Distribusi siswa per kelas (tingkat) ───────────────────
        $siswaTingkat = UserSiswa::join('kelas', 'user_siswa.id_kelas', '=', 'kelas.id_kelas')
            ->where('user_siswa.status', 'aktif')
            ->select('kelas.tingkat', DB::raw('count(*) as total'))
            ->groupBy('kelas.tingkat')
            ->orderBy('kelas.tingkat')
            ->get();

        $siswaTingkatLabels = $siswaTingkat->map(fn($k) => 'Kelas ' . $k->tingkat)->values()->toArray();
        $siswaTingkatData   = $siswaTingkat->pluck('total')->values()->toArray();

        // ── Distribusi BTAQ Siswa ──────────────────────────────────
        $latestBtaq = DB::table('btaq')
            ->join('user_siswa', 'btaq.nis', '=', 'user_siswa.nis')
            ->where('user_siswa.status', 'aktif')
            ->select('btaq.nis', DB::raw('MAX(btaq.id_btaq) as latest_id'))
            ->groupBy('btaq.nis');

        $btaqCounts = DB::table(DB::raw("({$latestBtaq->toSql()}) as latest"))
            ->mergeBindings($latestBtaq)
            ->join('btaq', 'btaq.id_btaq', '=', 'latest.latest_id')
            ->select('btaq.level', DB::raw('count(*) as total'))
            ->groupBy('btaq.level')
            ->get()
            ->pluck('total', 'level')
            ->toArray();

        $btaqIqroCount = 0;
        $btaqAlquranCount = 0;

        foreach ($btaqCounts as $level => $total) {
            $lvlLower = strtolower($level);
            if (str_contains($lvlLower, 'iqro') || str_contains($lvlLower, 'iqra')) {
                $btaqIqroCount += $total;
            } elseif (str_contains($lvlLower, 'qur') || str_contains($lvlLower, 'quran')) {
                $btaqAlquranCount += $total;
            } else {
                $btaqIqroCount += $total;
            }
        }

        $totalSiswaAktif = DB::table('user_siswa')->where('status', 'aktif')->count();
        $btaqKosongCount = max(0, $totalSiswaAktif - ($btaqIqroCount + $btaqAlquranCount));

        $btaqLabels = ['Iqro', 'Alquran', 'Kosong'];
        $btaqData = [$btaqIqroCount, $btaqAlquranCount, $btaqKosongCount];

        // ── Tadarus per bulan ──────────────────────────────────────
        $tadarusPerBulan = [];
        foreach ($chartMonths as $cm) {
            $tadarusPerBulan[] = Tadarus::whereYear('tanggal', $cm['year'])
                ->whereMonth('tanggal', $cm['month'])
                ->count();
        }

        // ── Presensi hari ini breakdown ────────────────────────────
        $rawBreakdown = Presensi::whereDate('tanggal', $today)
            ->whereHas('siswa', function($q) {
                $q->where('status', 'aktif');
            })
            ->select('status', DB::raw('count(*) as total'))
            ->groupBy('status')
            ->pluck('total', 'status')
            ->toArray();

        $presensiTodayBreakdown = [
            'Hadir' => $rawBreakdown[1] ?? 0,
            'Sakit' => $rawBreakdown[2] ?? 0,
            'Izin'  => $rawBreakdown[3] ?? 0,
            'Alfa'  => $rawBreakdown[4] ?? 0,
        ];

        // ── Statistik Jurnal Guru ──────────────────────────────────
        $jurnalBulanIni   = Kemajuan::whereYear('tanggal', now()->year)
            ->whereMonth('tanggal', now()->month)
            ->count();
        $jurnalApproved   = Kemajuan::where('status_approval', 'approved')->count();
        $jurnalPending    = Kemajuan::where('status_approval', 'pending')
            ->orWhereNull('status_approval')->count();
        $jurnalRejected   = Kemajuan::where('status_approval', 'rejected')->count();
        $jurnalTotal      = Kemajuan::count();

        // Jurnal per bulan (tahun ajaran)
        $jurnalPerBulan = [];
        foreach ($chartMonths as $cm) {
            $jurnalPerBulan[] = Kemajuan::whereYear('tanggal', $cm['year'])
                ->whereMonth('tanggal', $cm['month'])
                ->count();
        }

        // 8 jurnal terbaru
        $recentJurnal = Kemajuan::with(['guru', 'kelas', 'mapel'])
            ->orderBy('id_kemajuan', 'desc')
            ->take(8)
            ->get();

        // ── Statistik Gaya Belajar Siswa ───────────────────────────
        $gayaTypes  = ['visual', 'auditori', 'kinestetik'];
        $gayaLabels = ['Visual', 'Auditori', 'Kinestetik'];

        // 1. Overall distribution
        $gayaOverallRaw = GayaBelajar::select('gaya_belajar', DB::raw('count(*) as total'))
            ->groupBy('gaya_belajar')
            ->pluck('total', 'gaya_belajar')
            ->toArray();
        $gayaOverallData = array_map(fn($t) => $gayaOverallRaw[$t] ?? 0, $gayaTypes);
        $gayaTotal       = array_sum($gayaOverallData);

        // 2. Per Tingkat
        $gayaRawTingkat = DB::table('gaya_belajar')
            ->join('user_siswa', 'gaya_belajar.nis', '=', 'user_siswa.nis')
            ->join('kelas', 'user_siswa.id_kelas', '=', 'kelas.id_kelas')
            ->select('kelas.tingkat', 'gaya_belajar.gaya_belajar', DB::raw('count(*) as total'))
            ->groupBy('kelas.tingkat', 'gaya_belajar.gaya_belajar')
            ->orderBy('kelas.tingkat')
            ->get();
        $tingkatList = $gayaRawTingkat->pluck('tingkat')->unique()->sort()->values();
        $gayaPerTingkatLabels = $tingkatList->map(fn($t) => 'Tingkat ' . $t)->values()->toArray();
        $gayaPerTingkatData   = [];
        foreach ($gayaTypes as $type) {
            $gayaPerTingkatData[$type] = $tingkatList->map(function($t) use ($gayaRawTingkat, $type) {
                $row = $gayaRawTingkat->first(fn($r) => $r->tingkat == $t && $r->gaya_belajar === $type);
                return $row ? $row->total : 0;
            })->values()->toArray();
        }

        // 3. Per Jurusan
        $gayaRawJurusan = DB::table('gaya_belajar')
            ->join('user_siswa', 'gaya_belajar.nis', '=', 'user_siswa.nis')
            ->join('kelas', 'user_siswa.id_kelas', '=', 'kelas.id_kelas')
            ->join('jurusan', 'kelas.id_jurusan', '=', 'jurusan.id_jurusan')
            ->select('jurusan.id_jurusan', 'jurusan.nama_jurusan', 'gaya_belajar.gaya_belajar', DB::raw('count(*) as total'))
            ->groupBy('jurusan.id_jurusan', 'jurusan.nama_jurusan', 'gaya_belajar.gaya_belajar')
            ->orderBy('jurusan.nama_jurusan')
            ->get();
        $jurusanList = $gayaRawJurusan->pluck('nama_jurusan')->unique()->values();
        $gayaPerJurusanLabels = $jurusanList->values()->toArray();
        $gayaPerJurusanData   = [];
        foreach ($gayaTypes as $type) {
            $gayaPerJurusanData[$type] = $jurusanList->map(function($j) use ($gayaRawJurusan, $type) {
                $row = $gayaRawJurusan->first(fn($r) => $r->nama_jurusan === $j && $r->gaya_belajar === $type);
                return $row ? $row->total : 0;
            })->values()->toArray();
        }

        // 4. Per Kelas
        $gayaRawKelas = DB::table('gaya_belajar')
            ->join('user_siswa', 'gaya_belajar.nis', '=', 'user_siswa.nis')
            ->join('kelas', 'user_siswa.id_kelas', '=', 'kelas.id_kelas')
            ->select('kelas.id_kelas', 'kelas.tingkat', 'kelas.rombel', 'gaya_belajar.gaya_belajar', DB::raw('count(*) as total'))
            ->groupBy('kelas.id_kelas', 'kelas.tingkat', 'kelas.rombel', 'gaya_belajar.gaya_belajar')
            ->orderBy('kelas.tingkat')->orderBy('kelas.rombel')
            ->get();
        $kelasKeyList = $gayaRawKelas->map(fn($r) => ['label' => $r->tingkat . ' ' . $r->rombel, 'tingkat' => $r->tingkat, 'rombel' => $r->rombel])
            ->unique('label')->values();
        $gayaPerKelasLabels = $kelasKeyList->pluck('label')->toArray();
        $gayaPerKelasData   = [];
        foreach ($gayaTypes as $type) {
            $gayaPerKelasData[$type] = $kelasKeyList->map(function($k) use ($gayaRawKelas, $type) {
                $row = $gayaRawKelas->first(fn($r) => $r->tingkat == $k['tingkat'] && $r->rombel === $k['rombel'] && $r->gaya_belajar === $type);
                return $row ? $row->total : 0;
            })->values()->toArray();
        }

        return view('dashboard.index', compact(
            'siswaCount',
            'guruCount',
            'kelasCount',
            'presensiHariIni',
            'kunjunganUks',
            'tadarusHariIni',
            'months',
            'presensiPerBulan',
            'uksPerBulan',
            'pelanggaranPerBulan',
            'siswaTingkatLabels',
            'siswaTingkatData',
            'btaqLabels',
            'btaqData',
            'tadarusPerBulan',
            'presensiTodayBreakdown',
            'currentYear',
            'today',
            'tahunAjaranNama',
            'jurnalBulanIni',
            'jurnalApproved',
            'jurnalPending',
            'jurnalRejected',
            'jurnalTotal',
            'jurnalPerBulan',
            'recentJurnal',
            'gayaLabels',
            'gayaTypes',
            'gayaOverallData',
            'gayaTotal',
            'gayaPerTingkatLabels',
            'gayaPerTingkatData',
            'gayaPerJurusanLabels',
            'gayaPerJurusanData',
            'gayaPerKelasLabels',
            'gayaPerKelasData'
        ));
    }
}
