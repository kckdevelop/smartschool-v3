<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Kelas;
use App\Models\JadwalMengajarHarian;
use App\Models\JadwalMengajarTemplate;
use App\Models\JadwalSiklus;
use App\Models\Kemajuan;
use App\Models\UserSiswa;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DisplayController extends Controller
{
    public function index(Request $request)
    {
        $tanggal = $request->input('tanggal', date('Y-m-d'));
        $carbonDate = Carbon::parse($tanggal);
        
        // 1. Get Cycle Day (hari_siklus)
        $hariSiklus = JadwalSiklus::where('tanggal', $tanggal)->value('hari_ke');

        // 2. Fetch Active Classes
        $classes = Kelas::where('status', 'aktif')
            ->orderBy('tingkat', 'asc')
            ->orderBy('rombel', 'asc')
            ->get();

        // 3. Fetch Schedules for Today
        $schedulesRaw = JadwalMengajarHarian::with(['kelas', 'mapel', 'guru', 'jamPelajaran'])
            ->whereDate('tanggal', $tanggal)
            ->where('status', 'KBM')
            ->get();

        $sumberJadwal = 'harian';

        // Fallback to Template if Daily Schedule is Empty
        if ($schedulesRaw->isEmpty() && $hariSiklus) {
            $schedulesRaw = JadwalMengajarTemplate::with(['kelas', 'mapel', 'guru', 'jamPelajaran'])
                ->where('hari_siklus', $hariSiklus)
                ->get();
            $sumberJadwal = 'template';
        }

        // 4. Fetch Journals (Kemajuan) for Today
        $jurnals = Kemajuan::whereDate('tanggal', $tanggal)->get();

        // 5. Group Schedules by Class and process completion blocks
        $classJournalStatus = [];
        $totalSchedulesCount = 0;
        $filledSchedulesCount = 0;

        foreach ($classes as $class) {
            $classSchedules = $schedulesRaw->where('id_kelas', $class->id_kelas);
            $groupedSchedules = [];

            if ($classSchedules->isNotEmpty()) {
                // Sort by jam_ke to process chronologically
                $sorted = $classSchedules->sortBy('jam_ke')->values();
                
                $groups = [];
                $currentGroup = [$sorted[0]];
                
                for ($i = 1; $i < count($sorted); $i++) {
                    $prev = $sorted[$i - 1];
                    $next = $sorted[$i];
                    
                    $consecutive = ($next->jam_ke == $prev->jam_ke + 1);
                    $sameMapel = ($next->id_mapel == $prev->id_mapel);
                    $sameGuru = ($next->id_guru == $prev->id_guru);
                    
                    if ($consecutive && $sameMapel && $sameGuru) {
                        $currentGroup[] = $next;
                    } else {
                        $groups[] = $currentGroup;
                        $currentGroup = [$next];
                    }
                }
                $groups[] = $currentGroup;

                // Process each group of schedules
                foreach ($groups as $groupItems) {
                    $first = $groupItems[0];
                    $last = $groupItems[count($groupItems) - 1];
                    
                    $hours = array_unique(array_map(fn($item) => (int)$item->jam_ke, $groupItems));
                    sort($hours);
                    
                    $jamRangeStr = count($hours) === 1 ? (string)$hours[0] : $hours[0] . '-' . $hours[count($hours) - 1];

                    // Match with journal (Kemajuan)
                    $matchingJurnal = $jurnals->first(function ($j) use ($first) {
                        return $j->id_kelas == $first->id_kelas
                            && $j->id_mapel == $first->id_mapel
                            && $j->id_guru  == $first->id_guru;
                    });

                    $isFilled = !is_null($matchingJurnal);
                    $totalSchedulesCount++;
                    if ($isFilled) {
                        $filledSchedulesCount++;
                    }

                    $groupedSchedules[] = [
                        'jam_ke' => $jamRangeStr,
                        'mapel' => $first->mapel?->nama_mapel ?? '—',
                        'kode_mapel' => $first->mapel?->kode_mapel ?? '—',
                        'guru' => $first->guru?->nama_guru ?? '—',
                        'kode_guru' => $this->getTeacherInitials($first->guru?->nama_guru),
                        'ruang' => $first->ruang ?? '—',
                        'is_filled' => $isFilled,
                        'materi' => $matchingJurnal?->materi ?? null,
                    ];
                }
            }

            $classJournalStatus[] = [
                'id_kelas' => $class->id_kelas,
                'tingkat' => $class->tingkat,
                'nama_kelas' => $class->tingkat . ' ' . $class->rombel,
                'schedules' => $groupedSchedules,
            ];
        }

        // 6. Student Analytics
        $totalSiswa = UserSiswa::where('status', 'aktif')->count();
        $siswaLaki = UserSiswa::where('status', 'aktif')->where('jenkel', 'L')->count();
        $siswaPerempuan = UserSiswa::where('status', 'aktif')->where('jenkel', 'P')->count();

        $siswaTingkatRaw = UserSiswa::join('kelas', 'user_siswa.id_kelas', '=', 'kelas.id_kelas')
            ->where('user_siswa.status', 'aktif')
            ->select('kelas.tingkat', DB::raw('count(*) as total'))
            ->groupBy('kelas.tingkat')
            ->orderBy('kelas.tingkat', 'asc')
            ->get();

        // Per-grade gender breakdown
        $siswaTingkatGenderRaw = UserSiswa::join('kelas', 'user_siswa.id_kelas', '=', 'kelas.id_kelas')
            ->where('user_siswa.status', 'aktif')
            ->select('kelas.tingkat', 'user_siswa.jenkel', DB::raw('count(*) as total'))
            ->groupBy('kelas.tingkat', 'user_siswa.jenkel')
            ->orderBy('kelas.tingkat', 'asc')
            ->get();

        $siswaTingkatLabels = [];
        $siswaTingkatData = [];
        $siswaTingkatLaki = [];
        $siswaTingkatPerempuan = [];

        foreach ($siswaTingkatRaw as $st) {
            $siswaTingkatLabels[] = 'Kelas ' . $st->tingkat;
            $siswaTingkatData[] = $st->total;
        }

        // Build L/P arrays per tingkat in the same order
        $tingkatList = $siswaTingkatRaw->pluck('tingkat')->toArray();
        foreach ($tingkatList as $tkt) {
            $lRow = $siswaTingkatGenderRaw->first(fn($r) => $r->tingkat == $tkt && $r->jenkel === 'L');
            $pRow = $siswaTingkatGenderRaw->first(fn($r) => $r->tingkat == $tkt && $r->jenkel === 'P');
            $siswaTingkatLaki[]      = $lRow ? (int)$lRow->total : 0;
            $siswaTingkatPerempuan[] = $pRow ? (int)$pRow->total : 0;
        }

        // 7. BTAQ Analytics
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
        $btaqKosongCount = max(0, $totalSiswa - ($btaqIqroCount + $btaqAlquranCount));

        // 8. Learning Activity Statistics (Jurnal)
        $todayCompletionRate = $totalSchedulesCount > 0 
            ? round(($filledSchedulesCount / $totalSchedulesCount) * 100, 1) 
            : 0;

        $jurnalApproved = Kemajuan::where('status_approval', 'approved')->count();
        $jurnalPending = Kemajuan::where('status_approval', 'pending')
            ->orWhereNull('status_approval')->count();
        $jurnalRejected = Kemajuan::where('status_approval', 'rejected')->count();
        $jurnalTotal = Kemajuan::count();

        // Last 6 months journal trend
        $monthlyTrendRaw = Kemajuan::select(
            DB::raw("DATE_FORMAT(tanggal, '%Y-%m') as month_yr"),
            DB::raw("count(*) as total")
        )
        ->where('tanggal', '>=', now()->subMonths(5)->startOfMonth())
        ->groupBy('month_yr')
        ->orderBy('month_yr', 'asc')
        ->get();

        $monthlyTrendLabels = [];
        $monthlyTrendData = [];

        // Build list of last 6 months to ensure zeroes are filled
        for ($i = 5; $i >= 0; $i--) {
            $monthObj = now()->subMonths($i);
            $key = $monthObj->format('Y-m');
            $label = $monthObj->translatedFormat('M Y');
            
            $monthlyTrendLabels[] = $label;
            $monthlyTrendData[] = $monthlyTrendRaw->firstWhere('month_yr', $key)?->total ?? 0;
        }
        // 9. PKL Analytics
        $totalPklSiswa = \App\Models\PklPenempatan::where('status', 'aktif')->count();
        $totalDudi = \App\Models\PklDudi::count();
        
        $topDudiRaw = DB::table('pkl_penempatan')
            ->join('pkl_dudi', 'pkl_penempatan.id_dudi', '=', 'pkl_dudi.id_dudi')
            ->select('pkl_dudi.nama_dudi', DB::raw('count(*) as total'))
            ->where('pkl_penempatan.status', 'aktif')
            ->groupBy('pkl_dudi.id_dudi', 'pkl_dudi.nama_dudi')
            ->orderBy('total', 'desc')
            ->take(5)
            ->get();
            
        $topDudiLabels = $topDudiRaw->pluck('nama_dudi')->toArray();
        $topDudiData = $topDudiRaw->pluck('total')->toArray();
        
        $pklStatusRaw = \App\Models\PklPenempatan::select('status', DB::raw('count(*) as total'))
            ->groupBy('status')
            ->get()
            ->pluck('total', 'status')
            ->toArray();
            
        $pklStatusLabels = ['Aktif', 'Selesai', 'Ditarik', 'Batal', 'Pindah'];
        $pklStatusData = [
            $pklStatusRaw['aktif'] ?? 0,
            $pklStatusRaw['selesai'] ?? 0,
            $pklStatusRaw['ditarik'] ?? 0,
            $pklStatusRaw['batal'] ?? 0,
            $pklStatusRaw['pindah'] ?? 0,
        ];

        // 10. UKS Analytics
        $uksToday = \App\Models\KunjunganUks::whereDate('tanggal', $tanggal)->count();
        $uksMonth = \App\Models\KunjunganUks::whereYear('tanggal', now()->year)
            ->whereMonth('tanggal', now()->month)
            ->count();
        $totalObat = \App\Models\RiwayatObat::count();
        
        $topComplaintsRaw = \App\Models\KunjunganUks::select('keluhan', DB::raw('count(*) as total'))
            ->whereNotNull('keluhan')
            ->where('keluhan', '!=', '')
            ->groupBy('keluhan')
            ->orderBy('total', 'desc')
            ->take(5)
            ->get();
            
        $topComplaintsLabels = $topComplaintsRaw->pluck('keluhan')->toArray();
        $topComplaintsData = $topComplaintsRaw->pluck('total')->toArray();
        
        // Monthly UKS Visits trend (last 6 months)
        $monthlyUksRaw = \App\Models\KunjunganUks::select(
            DB::raw("DATE_FORMAT(tanggal, '%Y-%m') as month_yr"),
            DB::raw("count(*) as total")
        )
        ->where('tanggal', '>=', now()->subMonths(5)->startOfMonth())
        ->groupBy('month_yr')
        ->orderBy('month_yr', 'asc')
        ->get();
        
        $monthlyUksLabels = [];
        $monthlyUksData = [];
        for ($i = 5; $i >= 0; $i--) {
            $monthObj = now()->subMonths($i);
            $key = $monthObj->format('Y-m');
            $monthlyUksLabels[] = $monthObj->translatedFormat('M Y');
            $monthlyUksData[] = $monthlyUksRaw->firstWhere('month_yr', $key)?->total ?? 0;
        }

        // 11. BK Analytics
        $totalBk = \App\Models\BimbinganKonseling::count();
        $bkSelesai = \App\Models\BimbinganKonseling::where('status', 'selesai')->count();
        $bkProses = \App\Models\BimbinganKonseling::where('status', 'proses')->count();
            
        $problemTypesRaw = \App\Models\BimbinganKonseling::select('jenis_masalah', DB::raw('count(*) as total'))
            ->whereNotNull('jenis_masalah')
            ->where('jenis_masalah', '!=', '')
            ->groupBy('jenis_masalah')
            ->get();
            
        $problemTypesLabels = $problemTypesRaw->pluck('jenis_masalah')->toArray();
        $problemTypesData = $problemTypesRaw->pluck('total')->toArray();

        $sekolah = \App\Models\Sekolah::first();

        return view('display.index', compact(
            'sekolah',
            'tanggal',
            'carbonDate',
            'hariSiklus',
            'sumberJadwal',
            'classJournalStatus',
            'totalSchedulesCount',
            'filledSchedulesCount',
            'todayCompletionRate',
            'totalSiswa',
            'siswaLaki',
            'siswaPerempuan',
            'siswaTingkatLabels',
            'siswaTingkatData',
            'siswaTingkatLaki',
            'siswaTingkatPerempuan',
            'btaqIqroCount',
            'btaqAlquranCount',
            'btaqKosongCount',
            'jurnalApproved',
            'jurnalPending',
            'jurnalRejected',
            'jurnalTotal',
            'monthlyTrendLabels',
            'monthlyTrendData',
            'totalPklSiswa',
            'totalDudi',
            'topDudiLabels',
            'topDudiData',
            'pklStatusLabels',
            'pklStatusData',
            'uksToday',
            'uksMonth',
            'totalObat',
            'topComplaintsLabels',
            'topComplaintsData',
            'monthlyUksLabels',
            'monthlyUksData',
            'totalBk',
            'bkSelesai',
            'bkProses',
            'problemTypesLabels',
            'problemTypesData'
        ));
    }

    /**
     * Helper to retrieve clean 2-3 letter initials for a teacher.
     */
    private function getTeacherInitials(?string $name): string
    {
        if (!$name) return '—';
        
        // Strip titles after comma
        $parts = explode(',', $name);
        $nameWithoutTitles = $parts[0];
        
        // Remove common academic prefixes (Drs|Dr|Hj|H|Prof|Ir|Rr)
        $nameWithoutTitles = preg_replace('/\b(Drs|Dr|Hj|H|Prof|Ir|Rr)\.?\b/i', '', $nameWithoutTitles);
        
        // Extract initials
        $words = explode(' ', preg_replace('/\s+/', ' ', trim($nameWithoutTitles)));
        $initials = '';
        foreach ($words as $w) {
            if (!empty($w) && isset($w[0])) {
                $initials .= strtoupper($w[0]);
            }
        }
        
        return strlen($initials) > 0 ? substr($initials, 0, 3) : substr(strtoupper(trim($nameWithoutTitles)), 0, 3);
    }

    /**
     * ── Soft Refresh Data Endpoint ──
     * Returns fresh live stats as JSON + pre-rendered HTML for grade slides.
     * Called from JS every time the slideshow loops back to slide 0.
     */
    public function getData(Request $request)
    {
        $tanggal = $request->input('tanggal', date('Y-m-d'));

        // Cycle Day
        $hariSiklus = JadwalSiklus::where('tanggal', $tanggal)->value('hari_ke');

        // Active Classes
        $classes = Kelas::where('status', 'aktif')
            ->orderBy('tingkat', 'asc')->orderBy('rombel', 'asc')->get();

        // Schedules
        $schedulesRaw = JadwalMengajarHarian::with(['kelas', 'mapel', 'guru', 'jamPelajaran'])
            ->whereDate('tanggal', $tanggal)->where('status', 'KBM')->get();

        if ($schedulesRaw->isEmpty() && $hariSiklus) {
            $schedulesRaw = JadwalMengajarTemplate::with(['kelas', 'mapel', 'guru', 'jamPelajaran'])
                ->where('hari_siklus', $hariSiklus)->get();
        }

        // Journals for today
        $jurnals = Kemajuan::whereDate('tanggal', $tanggal)->get();

        // Build class journal status
        $classJournalStatus  = [];
        $totalSchedulesCount = 0;
        $filledSchedulesCount = 0;

        foreach ($classes as $class) {
            $classSchedules  = $schedulesRaw->where('id_kelas', $class->id_kelas);
            $groupedSchedules = [];

            if ($classSchedules->isNotEmpty()) {
                $sorted = $classSchedules->sortBy('jam_ke')->values();
                $groups = [];
                $currentGroup = [$sorted[0]];
                for ($i = 1; $i < count($sorted); $i++) {
                    $prev = $sorted[$i - 1]; $next = $sorted[$i];
                    if ($next->jam_ke == $prev->jam_ke + 1 && $next->id_mapel == $prev->id_mapel && $next->id_guru == $prev->id_guru) {
                        $currentGroup[] = $next;
                    } else { $groups[] = $currentGroup; $currentGroup = [$next]; }
                }
                $groups[] = $currentGroup;

                foreach ($groups as $groupItems) {
                    $first  = $groupItems[0];
                    $hours  = array_unique(array_map(fn($item) => (int)$item->jam_ke, $groupItems));
                    sort($hours);
                    $jamStr = count($hours) === 1 ? (string)$hours[0] : $hours[0] . '-' . $hours[count($hours) - 1];
                    $jurnal = $jurnals->first(fn($j) => $j->id_kelas == $first->id_kelas && $j->id_mapel == $first->id_mapel && $j->id_guru == $first->id_guru);
                    $totalSchedulesCount++;
                    $isFilled = $jurnal !== null;
                    if ($isFilled) $filledSchedulesCount++;
                    $groupedSchedules[] = [
                        'jam_ke'     => $jamStr,
                        'mapel'      => $first->mapel?->nama_mapel ?? '—',
                        'kode_mapel' => $first->mapel?->kode_mapel ?? '—',
                        'kode_guru'  => $this->getTeacherInitials($first->guru?->nama_guru ?? ''),
                        'ruang'      => $first->kelas?->ruang ?? '—',
                        'is_filled'  => $isFilled,
                        'materi'     => $jurnal?->materi ?? null,
                    ];
                }
            }

            $classJournalStatus[] = [
                'tingkat'    => $class->tingkat,
                'nama_kelas' => $class->tingkat . ' ' . $class->rombel,
                'schedules'  => $groupedSchedules,
            ];
        }

        // Journal stats
        $todayCompletionRate = $totalSchedulesCount > 0
            ? round(($filledSchedulesCount / $totalSchedulesCount) * 100, 1) : 0;
        $jurnalTotal    = Kemajuan::count();
        $jurnalApproved = Kemajuan::where('status_approval', 'approved')->count();
        $jurnalPending  = Kemajuan::where('status_approval', 'pending')->orWhereNull('status_approval')->count();
        $jurnalRejected = Kemajuan::where('status_approval', 'rejected')->count();

        $monthlyRaw = Kemajuan::select(DB::raw("DATE_FORMAT(tanggal,'%Y-%m') as m"), DB::raw('count(*) as t'))
            ->where('tanggal', '>=', now()->subMonths(5)->startOfMonth())->groupBy('m')->orderBy('m')->get();
        $monthlyTrendLabels = $monthlyTrendData = [];
        for ($i = 5; $i >= 0; $i--) {
            $mo = now()->subMonths($i);
            $monthlyTrendLabels[] = $mo->translatedFormat('M Y');
            $monthlyTrendData[]   = $monthlyRaw->firstWhere('m', $mo->format('Y-m'))?->t ?? 0;
        }

        // Student stats
        $totalSiswa     = UserSiswa::where('status', 'aktif')->count();
        $siswaLaki      = UserSiswa::where('status', 'aktif')->where('jenkel', 'L')->count();
        $siswaPerempuan = UserSiswa::where('status', 'aktif')->where('jenkel', 'P')->count();

        $stRaw = UserSiswa::join('kelas', 'user_siswa.id_kelas', '=', 'kelas.id_kelas')
            ->where('user_siswa.status', 'aktif')
            ->select('kelas.tingkat', DB::raw('count(*) as total'))
            ->groupBy('kelas.tingkat')->orderBy('kelas.tingkat')->get();

        $sgRaw = UserSiswa::join('kelas', 'user_siswa.id_kelas', '=', 'kelas.id_kelas')
            ->where('user_siswa.status', 'aktif')
            ->select('kelas.tingkat', 'user_siswa.jenkel', DB::raw('count(*) as total'))
            ->groupBy('kelas.tingkat', 'user_siswa.jenkel')->orderBy('kelas.tingkat')->get();

        $siswaTingkatLabels = $stRaw->map(fn($r) => 'Kelas ' . $r->tingkat)->toArray();
        $siswaTingkatLaki = $siswaTingkatPerempuan = [];
        foreach ($stRaw->pluck('tingkat') as $tkt) {
            $siswaTingkatLaki[]      = (int)($sgRaw->first(fn($r) => $r->tingkat == $tkt && $r->jenkel === 'L')?->total ?? 0);
            $siswaTingkatPerempuan[] = (int)($sgRaw->first(fn($r) => $r->tingkat == $tkt && $r->jenkel === 'P')?->total ?? 0);
        }

        // BTAQ
        $latestBtaq = DB::table('btaq')->join('user_siswa', 'btaq.nis', '=', 'user_siswa.nis')
            ->where('user_siswa.status', 'aktif')
            ->select('btaq.nis', DB::raw('MAX(btaq.id_btaq) as latest_id'))->groupBy('btaq.nis');
        $btaqCounts = DB::table(DB::raw("({$latestBtaq->toSql()}) as latest"))->mergeBindings($latestBtaq)
            ->join('btaq', 'btaq.id_btaq', '=', 'latest.latest_id')
            ->select('btaq.level', DB::raw('count(*) as total'))
            ->groupBy('btaq.level')->get()->pluck('total', 'level')->toArray();
        $btaqIqroCount = $btaqAlquranCount = 0;
        foreach ($btaqCounts as $level => $total) {
            $lvl = strtolower($level);
            if (str_contains($lvl, 'iqro') || str_contains($lvl, 'iqra')) $btaqIqroCount += $total;
            elseif (str_contains($lvl, 'qur')) $btaqAlquranCount += $total;
            else $btaqIqroCount += $total;
        }
        $btaqKosongCount = max(0, $totalSiswa - ($btaqIqroCount + $btaqAlquranCount));

        // UKS
        $uksToday = \App\Models\KunjunganUks::whereDate('tanggal', $tanggal)->count();
        $uksMonth = \App\Models\KunjunganUks::whereYear('tanggal', now()->year)->whereMonth('tanggal', now()->month)->count();
        $uksMonthlyRaw = \App\Models\KunjunganUks::select(DB::raw("DATE_FORMAT(tanggal,'%Y-%m') as m"), DB::raw('count(*) as t'))
            ->where('tanggal', '>=', now()->subMonths(5)->startOfMonth())->groupBy('m')->orderBy('m')->get();
        $monthlyUksLabels = $monthlyUksData = [];
        for ($i = 5; $i >= 0; $i--) {
            $mo = now()->subMonths($i);
            $monthlyUksLabels[] = $mo->translatedFormat('M Y');
            $monthlyUksData[]   = $uksMonthlyRaw->firstWhere('m', $mo->format('Y-m'))?->t ?? 0;
        }

        // BK
        $totalBk   = \App\Models\BimbinganKonseling::count();
        $bkSelesai = \App\Models\BimbinganKonseling::where('status', 'selesai')->count();
        $bkProses  = \App\Models\BimbinganKonseling::where('status', 'proses')->count();
        $bkTypes   = \App\Models\BimbinganKonseling::select('jenis_masalah', DB::raw('count(*) as total'))
            ->whereNotNull('jenis_masalah')->where('jenis_masalah', '!=', '')->groupBy('jenis_masalah')->get();

        // PKL
        $totalPklSiswa = \App\Models\PklPenempatan::where('status', 'aktif')->count();

        // Pre-render grade slide HTML
        $groupedClasses = collect($classJournalStatus)->groupBy('tingkat');
        $gradeHtml = [];
        foreach ($groupedClasses as $tingkat => $items) {
            $html = '';
            foreach ($items as $classData) {
                $html .= '<div class="grade-row">';
                $html .= '<div class="grade-row-class"><i class="fa-solid fa-school"></i><span>' . e($classData['nama_kelas']) . '</span></div>';
                $html .= '<div class="grade-row-schedules">';
                if (count($classData['schedules']) > 0) {
                    foreach ($classData['schedules'] as $sch) {
                        $fc = $sch['is_filled'] ? 'is-filled' : 'is-empty';
                        $html .= '<div class="schedule-block ' . $fc . '" title="Materi: ' . e($sch['materi'] ?? '—') . '">';
                        $html .= '<div class="block-top"><span>' . e($sch['jam_ke']) . '</span><span class="block-ruang">' . e($sch['ruang']) . '</span></div>';
                        $html .= '<div class="block-mapel" title="' . e($sch['mapel']) . '">' . e($sch['kode_mapel']) . '</div>';
                        $html .= '<div class="block-bottom"><span class="block-guru">' . e($sch['kode_guru']) . '</span></div>';
                        $html .= '</div>';
                    }
                } else {
                    $html .= '<div class="no-schedule-row"><i class="fa-solid fa-calendar-xmark"></i><span>Tidak ada KBM hari ini</span></div>';
                }
                $html .= '</div></div>';
            }
            $gradeHtml[(string)$tingkat] = $html;
        }

        return response()->json([
            'tanggal'               => $tanggal,
            'totalSiswa'            => $totalSiswa,
            'siswaLaki'             => $siswaLaki,
            'siswaPerempuan'        => $siswaPerempuan,
            'siswaTingkatLabels'    => $siswaTingkatLabels,
            'siswaTingkatLaki'      => $siswaTingkatLaki,
            'siswaTingkatPerempuan' => $siswaTingkatPerempuan,
            'btaqAlquranCount'      => $btaqAlquranCount,
            'btaqIqroCount'         => $btaqIqroCount,
            'btaqKosongCount'       => $btaqKosongCount,
            'todayCompletionRate'   => $todayCompletionRate,
            'filledSchedulesCount'  => $filledSchedulesCount,
            'totalSchedulesCount'   => $totalSchedulesCount,
            'jurnalTotal'           => $jurnalTotal,
            'jurnalApproved'        => $jurnalApproved,
            'jurnalPending'         => $jurnalPending,
            'jurnalRejected'        => $jurnalRejected,
            'monthlyTrendLabels'    => $monthlyTrendLabels,
            'monthlyTrendData'      => $monthlyTrendData,
            'totalPklSiswa'         => $totalPklSiswa,
            'uksToday'              => $uksToday,
            'uksMonth'              => $uksMonth,
            'monthlyUksLabels'      => $monthlyUksLabels,
            'monthlyUksData'        => $monthlyUksData,
            'totalBk'               => $totalBk,
            'bkSelesai'             => $bkSelesai,
            'bkProses'              => $bkProses,
            'problemTypesLabels'    => $bkTypes->pluck('jenis_masalah')->toArray(),
            'problemTypesData'      => $bkTypes->pluck('total')->toArray(),
            'gradeHtml'             => $gradeHtml,
        ]);
    }
}
