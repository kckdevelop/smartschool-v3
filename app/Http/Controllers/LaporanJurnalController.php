<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Kemajuan;
use App\Models\Semester;
use App\Models\TahunAjaran;
use App\Models\Guru;
use App\Models\Kelas;
use App\Models\Mapel;
use Illuminate\Http\Request;
use Carbon\Carbon;

class LaporanJurnalController extends Controller
{
    public function index(Request $request)
    {
        // ── Semester list for filter ──────────────────────────────────────────
        $semesterList = Semester::with('tahunAjaran')
            ->orderByDesc('awal')
            ->get();

        // Selected semester — '' means "Semua Semester" (no date filter)
        // Default to '' (all data) so existing data is always visible
        $selectedSemesterId = $request->input('id_semester', '');

        $selectedSemester = $selectedSemesterId !== ''
            ? $semesterList->firstWhere('id_semester', $selectedSemesterId)
            : null;

        // ── Filter inputs ────────────────────────────────────────────────────
        $filterGuru   = $request->input('id_guru');
        $filterKelas  = $request->input('id_kelas');
        $filterMapel  = $request->input('id_mapel');
        $filterStatus = $request->input('status_approval');

        // ── Base query ───────────────────────────────────────────────────────
        $query = Kemajuan::with(['guru', 'kelas.jurusan', 'mapel'])
            ->orderBy('tanggal', 'asc')
            ->orderBy('id_guru', 'asc');

        // Apply semester date range only when a specific semester is selected
        if ($selectedSemester) {
            $awal  = $selectedSemester->awal instanceof \Carbon\Carbon
                ? $selectedSemester->awal->format('Y-m-d')
                : date('Y-m-d', strtotime($selectedSemester->awal));
            $akhir = $selectedSemester->akhir instanceof \Carbon\Carbon
                ? $selectedSemester->akhir->format('Y-m-d')
                : date('Y-m-d', strtotime($selectedSemester->akhir));

            $query->whereBetween('tanggal', [$awal, $akhir]);
        }

        if ($filterGuru)  $query->where('id_guru', $filterGuru);
        if ($filterKelas) $query->where('id_kelas', $filterKelas);
        if ($filterMapel) $query->where('id_mapel', $filterMapel);
        if ($filterStatus) {
            if ($filterStatus === 'pending') {
                $query->where(function ($q) {
                    $q->whereNull('status_approval')
                      ->orWhere('status_approval', 'pending');
                });
            } else {
                $query->where('status_approval', $filterStatus);
            }
        }

        // ── Summary stats — dihitung dari query tanpa paginate ─────────────────
        $statsQuery = clone $query;
        $allJurnals      = $statsQuery->get();
        $totalJurnal     = $allJurnals->count();
        $totalApproved   = $allJurnals->where('status_approval', 'approved')->count();
        $totalPending    = $allJurnals->filter(function ($j) {
            return is_null($j->status_approval) || $j->status_approval === 'pending';
        })->count();
        $totalRejected   = $allJurnals->where('status_approval', 'rejected')->count();
        $totalSiswaHadir = $allJurnals->sum('jml_siswa');

        // ── Paginated data ───────────────────────────────────────────────────
        $perPage = (int) $request->input('per_page', 20);
        $jurnals = $query->paginate($perPage)->withQueryString();

        // ── Rekap per Guru ───────────────────────────────────────────────────
        $rekapGuru = $allJurnals->groupBy('id_guru')->map(function ($items) {
            $guru = $items->first()->guru;
            return [
                'nama_guru' => $guru->nama_guru ?? '—',
                'total'     => $items->count(),
                'approved'  => $items->where('status_approval', 'approved')->count(),
                'pending'   => $items->filter(function ($j) {
                    return is_null($j->status_approval) || $j->status_approval === 'pending';
                })->count(),
                'rejected'  => $items->where('status_approval', 'rejected')->count(),
                'jml_siswa' => $items->sum('jml_siswa'),
            ];
        })->sortByDesc('total')->values();

        // ── Filter options ───────────────────────────────────────────────────
        $guruList  = Guru::orderBy('nama_guru')->get(['id_guru', 'nama_guru']);
        $kelasList = Kelas::where('status', 'aktif')->with('jurusan')->orderBy('tingkat')->orderBy('rombel')->get();
        $mapelList = Mapel::orderBy('nama_mapel')->get(['id_mapel', 'nama_mapel']);

        return view('laporan-jurnal.index', compact(
            'semesterList', 'selectedSemester', 'selectedSemesterId',
            'jurnals',
            'totalJurnal', 'totalApproved', 'totalPending', 'totalRejected', 'totalSiswaHadir',
            'rekapGuru',
            'guruList', 'kelasList', 'mapelList',
            'filterGuru', 'filterKelas', 'filterMapel', 'filterStatus'
        ));
    }
}
