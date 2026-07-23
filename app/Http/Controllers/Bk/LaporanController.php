<?php

namespace App\Http\Controllers\Bk;

use App\Http\Controllers\Controller;
use App\Models\Kelas;
use App\Models\RiwayatPoin;
use App\Models\RiwayatReward;
use App\Models\Semester;
use App\Models\UserSiswa;
use App\Models\Sekolah;
use App\Models\Guru;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LaporanController extends Controller
{
    public function index(Request $request)
    {
        // 1. Get semesters & active classes list for filters
        $semesters = Semester::with('tahunAjaran')->orderByDesc('id_semester')->get();
        $kelasList = Kelas::where('status', 'aktif')->orderBy('tingkat')->orderBy('rombel')->get();

        // 2. Get BK teachers list
        $guruBkList = Guru::where('guru_bk', 'ya')->orderBy('nama_guru')->get();
        $selectedGuruBkId = $request->input('id_guru_bk');
        if (!$selectedGuruBkId && count($guruBkList) > 0) {
            $selectedGuruBkId = $guruBkList[0]->id_guru;
        }

        // 3. Determine selected filters
        $selectedSemesterId = $request->input('id_semester');
        if ($selectedSemesterId) {
            $selectedSemester = Semester::with('tahunAjaran')->find($selectedSemesterId);
        } else {
            $selectedSemester = Semester::with('tahunAjaran')->where('status', 'aktif')->first() ?? Semester::with('tahunAjaran')->orderByDesc('id_semester')->first();
        }

        $semAwal = $selectedSemester ? $selectedSemester->awal->toDateString() : null;
        $semAkhir = $selectedSemester ? $selectedSemester->akhir->toDateString() : null;

        $selectedKelasId = $request->input('id_kelas'); // Empty or 'semua' means all classes

        // 4. Top Violation Categories
        $queryPelanggaran = RiwayatPoin::select('pelanggaran', DB::raw('count(*) as total'))
            ->groupBy('pelanggaran')
            ->orderByDesc('total')
            ->limit(5);
        if ($semAwal && $semAkhir) {
            $queryPelanggaran->whereBetween('tgl_input', [$semAwal, $semAkhir]);
        }
        $topPelanggaran = $queryPelanggaran->get();

        // 5. Top Reward Categories
        $queryReward = RiwayatReward::select('reward', DB::raw('count(*) as total'))
            ->groupBy('reward')
            ->orderByDesc('total')
            ->limit(5);
        if ($semAwal && $semAkhir) {
            $queryReward->whereBetween('tgl_input', [$semAwal, $semAkhir]);
        }
        $topReward = $queryReward->get();

        // 6. Top Students by Violation Points (Ranking)
        $queryTopSiswaPelanggaran = RiwayatPoin::select('nis', DB::raw('SUM(poin) as total_poin'), DB::raw('count(*) as total_kasus'))
            ->with(['siswa.kelas'])
            ->groupBy('nis')
            ->orderByDesc('total_poin')
            ->limit(10);
        if ($semAwal && $semAkhir) {
            $queryTopSiswaPelanggaran->whereBetween('tgl_input', [$semAwal, $semAkhir]);
        }
        $topSiswaPelanggaran = $queryTopSiswaPelanggaran->get();

        // 7. Top Students by Reward Points (Ranking)
        $queryTopSiswaReward = RiwayatReward::select('nis', DB::raw('SUM(point_reward) as total_poin'), DB::raw('count(*) as total_reward'))
            ->with(['siswa.kelas'])
            ->groupBy('nis')
            ->orderByDesc('total_poin')
            ->limit(10);
        if ($semAwal && $semAkhir) {
            $queryTopSiswaReward->whereBetween('tgl_input', [$semAwal, $semAkhir]);
        }
        $topSiswaReward = $queryTopSiswaReward->get();

        // 8. Class Report Data (always per-class summary on index page)
        $reportData = Kelas::where('status', 'aktif')
            ->with(['siswa'])
            ->orderBy('tingkat')
            ->orderBy('rombel')
            ->get()
            ->map(function ($kelas) use ($semAwal, $semAkhir) {
                $nisList = $kelas->siswa->pluck('nis')->toArray();

                $totalPoin = 0;
                if (count($nisList) > 0) {
                    $qp = RiwayatPoin::whereIn('nis', $nisList);
                    if ($semAwal && $semAkhir) {
                        $qp->whereBetween('tgl_input', [$semAwal, $semAkhir]);
                    }
                    $totalPoin = $qp->sum('poin');
                }

                $totalReward = 0;
                if (count($nisList) > 0) {
                    $qr = RiwayatReward::whereIn('nis', $nisList);
                    if ($semAwal && $semAkhir) {
                        $qr->whereBetween('tgl_input', [$semAwal, $semAkhir]);
                    }
                    $totalReward = $qr->sum('point_reward');
                }

                $kelas->total_poin   = $totalPoin;
                $kelas->total_reward = $totalReward;
                $kelas->total_net    = $totalReward - $totalPoin;
                $kelas->jumlah_siswa = count($nisList);
                return $kelas;
            });

        return view('bk.laporan.index', compact(
            'semesters',
            'kelasList',
            'guruBkList',
            'selectedSemester',
            'selectedKelasId',
            'selectedGuruBkId',
            'topPelanggaran',
            'topReward',
            'topSiswaPelanggaran',
            'topSiswaReward',
            'reportData'
        ));
    }


    public function print(Request $request)
    {
        $sekolah = Sekolah::where('id_sekolah', 1)->first() ?? new Sekolah();

        // 1. Semester
        $selectedSemesterId = $request->input('id_semester');
        if ($selectedSemesterId) {
            $selectedSemester = Semester::with('tahunAjaran')->find($selectedSemesterId);
        } else {
            $selectedSemester = Semester::with('tahunAjaran')->where('status', 'aktif')->first()
                ?? Semester::with('tahunAjaran')->orderByDesc('id_semester')->first();
        }

        $semAwal  = $selectedSemester ? $selectedSemester->awal->toDateString()  : null;
        $semAkhir = $selectedSemester ? $selectedSemester->akhir->toDateString() : null;

        // 2. Class (required — always per-class)
        $selectedKelasId = $request->input('id_kelas');
        $selectedKelas   = $selectedKelasId ? Kelas::with('guru')->find($selectedKelasId) : null;
        $selectedKelasLabel = $selectedKelas ? $selectedKelas->nama_kelas : '—';

        // Wali Kelas from the class's assigned teacher
        $waliKelasName = '_____________________';
        $waliKelasNip  = '_____________________';
        if ($selectedKelas && $selectedKelas->guru) {
            $waliKelasName = $selectedKelas->guru->nama_guru;
            $waliKelasNip  = $selectedKelas->guru->no_id
                ? (string) $selectedKelas->guru->no_id
                : '_____________________';
        }

        // 3. Student data for selected class
        $reportData = [];
        if ($selectedKelas) {
            $reportData = UserSiswa::where('id_kelas', $selectedKelasId)
                ->where('status', 'aktif')
                ->withSum(['riwayatPoin as total_poin' => function ($query) use ($semAwal, $semAkhir) {
                    if ($semAwal && $semAkhir) {
                        $query->whereBetween('tgl_input', [$semAwal, $semAkhir]);
                    }
                }], 'poin')
                ->withSum(['riwayatReward as total_reward' => function ($query) use ($semAwal, $semAkhir) {
                    if ($semAwal && $semAkhir) {
                        $query->whereBetween('tgl_input', [$semAwal, $semAkhir]);
                    }
                }], 'point_reward')
                ->orderBy('nama_siswa')
                ->get()
                ->map(function ($siswa) {
                    $siswa->total_poin   = $siswa->total_poin   ?? 0;
                    $siswa->total_reward = $siswa->total_reward ?? 0;
                    $siswa->total_net    = $siswa->total_reward - $siswa->total_poin;
                    return $siswa;
                });
        }

        // 4. BK Teacher list — passed to view so the toolbar dropdown can select one
        $guruBkList = Guru::where('guru_bk', 'ya')->orderBy('nama_guru')->get();

        return view('bk.laporan.print', compact(
            'sekolah',
            'selectedSemester',
            'selectedKelas',
            'selectedKelasLabel',
            'reportData',
            'guruBkList',
            'waliKelasName',
            'waliKelasNip'
        ));
    }
}

