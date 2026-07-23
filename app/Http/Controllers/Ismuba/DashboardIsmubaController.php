<?php

namespace App\Http\Controllers\Ismuba;

use App\Http\Controllers\Controller;
use App\Models\Btaq;
use App\Models\Tadarus;
use App\Models\PantauIbadah;
use App\Models\JadwalPengajian;
use App\Models\KehadiranPengajian;
use App\Models\Kelas;
use App\Models\UserSiswa;
use App\Models\Guru;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardIsmubaController extends Controller
{
    public function index()
    {
        $today = Carbon::today()->toDateString();

        // ── 1. Summary Statistics ──
        $countBtaq = Btaq::count();
        $countTadarus = Tadarus::count();
        $countIbadah = PantauIbadah::count();
        $totalPengajian = JadwalPengajian::count();

        // Pengajian Attendance Rate
        $totalKehadiranPengajian = KehadiranPengajian::count();
        $totalHadirPengajian = KehadiranPengajian::where('status', 'hadir')->count();
        $rataRataKehadiranPengajian = $totalKehadiranPengajian > 0
            ? round(($totalHadirPengajian / $totalKehadiranPengajian) * 100, 1)
            : 0;

        // ── 2. BTAQ level breakdown (Iqro vs Al-Qur'an vs Kosong) based on active students ──
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

        $totalSiswaAktif = UserSiswa::where('status', 'aktif')->count();
        $btaqKosongCount = max(0, $totalSiswaAktif - ($btaqIqroCount + $btaqAlquranCount));

        $btaqLabels = ['Iqro', 'Alquran', 'Kosong'];
        $btaqData = [$btaqIqroCount, $btaqAlquranCount, $btaqKosongCount];

        // Iqro Jilid breakdown (Jilid 1 to 6)
        $iqroBreakdown = [];
        for ($i = 1; $i <= 6; $i++) {
            $iqroBreakdown["Jilid $i"] = Btaq::where('level', "iqro$i")->count();
        }

        // ── 3. Ibadah Breakdown (A, B, C Predicates per Type) ──
        $ibadahTypes = ['sholat_fardu', 'sholat_jenazah', 'gerakan_wudhu'];
        $predicates = ['A', 'B', 'C'];
        $ibadahData = [];

        foreach ($ibadahTypes as $type) {
            $ibadahData[$type] = [];
            foreach ($predicates as $pred) {
                $ibadahData[$type][$pred] = PantauIbadah::where('jenis_ibadah', $type)
                    ->where('nilai', $pred)
                    ->count();
            }
        }

        // ── 4. Tadarus per Kelas ──
        $tadarusPerKelas = Tadarus::with('kelas')
            ->select('id_kelas', DB::raw('count(*) as count'))
            ->groupBy('id_kelas')
            ->get()
            ->map(function($item) {
                $item->nama_kelas = $item->kelas?->nama_kelas ?? ('Kelas ' . $item->id_kelas);
                return $item;
            })
            ->sortBy('nama_kelas')
            ->values();

        // ── 5. Recent Logs ──
        // Recent BTAQ Progress
        $recentBtaq = Btaq::with(['siswa.kelas', 'guru', 'iqroAwal', 'alquranAwal'])
            ->orderByDesc('tanggal')
            ->orderByDesc('id_btaq')
            ->limit(5)
            ->get();

        // Recent Tadarus Sessions
        $recentTadarus = Tadarus::with(['kelas', 'guru'])
            ->orderByDesc('tanggal')
            ->orderByDesc('id_tadarus')
            ->limit(5)
            ->get();

        // Recent Ibadah Assessments
        $recentIbadah = PantauIbadah::with(['siswa.kelas', 'guru'])
            ->orderByDesc('tanggal')
            ->orderByDesc('id_ibadah')
            ->limit(5)
            ->get();

        return view('ismuba.dashboard', compact(
            'today',
            'countBtaq',
            'countTadarus',
            'countIbadah',
            'totalPengajian',
            'rataRataKehadiranPengajian',
            'btaqIqroCount',
            'btaqAlquranCount',
            'btaqLabels',
            'btaqData',
            'iqroBreakdown',
            'ibadahData',
            'tadarusPerKelas',
            'recentBtaq',
            'recentTadarus',
            'recentIbadah'
        ));
    }
}
