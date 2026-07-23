<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\RiwayatPoin;
use App\Models\RiwayatReward;
use App\Models\Semester;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BkRiwayatController extends Controller
{
    /**
     * Ambil semester aktif (status = 'aktif'), fallback ke semester terbaru.
     */
    private function getSemesterAktif(): ?Semester
    {
        return Semester::where('status', 'aktif')->first()
            ?? Semester::orderByDesc('id_semester')->first();
    }

    /**
     * GET /api/bk/riwayat-poin
     * Daftar riwayat poin pelanggaran + rekap per semester aktif.
     */
    public function riwayatPoin(Request $request)
    {
        $semester = $this->getSemesterAktif();

        $query = RiwayatPoin::with(['siswa', 'guru'])
            ->orderByDesc('tgl_input');

        // Filter per semester (range tanggal awal-akhir)
        if ($semester) {
            $query->whereBetween('tgl_input', [
                $semester->awal->toDateString(),
                $semester->akhir->toDateString(),
            ]);
        }

        // Filter opsional per NIS
        if ($request->filled('nis')) {
            $query->where('nis', $request->nis);
        }

        // Filter opsional per kelas
        if ($request->filled('id_kelas')) {
            $query->whereHas('siswa', function ($q) use ($request) {
                $q->where('id_kelas', $request->id_kelas);
            });
        }

        $perPage = $request->get('per_page', 100);
        $data    = $query->paginate($perPage);

        // ── Rekap semester ────────────────────────────────────────────────
        $rekapQuery = RiwayatPoin::query();
        if ($semester) {
            $rekapQuery->whereBetween('tgl_input', [
                $semester->awal->toDateString(),
                $semester->akhir->toDateString(),
            ]);
        }
        if ($request->filled('nis')) {
            $rekapQuery->where('nis', $request->nis);
        }
        if ($request->filled('id_kelas')) {
            $rekapQuery->whereHas('siswa', function ($q) use ($request) {
                $q->where('id_kelas', $request->id_kelas);
            });
        }

        $rekap = $rekapQuery->selectRaw('COUNT(*) as total_kasus, SUM(poin) as total_poin')
            ->first();

        return response()->json([
            'success' => true,
            'semester' => $semester ? [
                'id'       => $semester->id_semester,
                'nama'     => $semester->semester,
                'awal'     => $semester->awal->toDateString(),
                'akhir'    => $semester->akhir->toDateString(),
            ] : null,
            'rekap' => [
                'total_kasus' => (int) ($rekap->total_kasus ?? 0),
                'total_poin'  => (int) ($rekap->total_poin ?? 0),
            ],
            'data' => $data,
        ]);
    }

    /**
     * GET /api/bk/riwayat-reward
     * Daftar riwayat reward/prestasi + rekap per semester aktif.
     */
    public function riwayatReward(Request $request)
    {
        $semester = $this->getSemesterAktif();

        $query = RiwayatReward::with(['siswa', 'guru'])
            ->orderByDesc('tgl_input');

        if ($semester) {
            $query->whereBetween('tgl_input', [
                $semester->awal->toDateString(),
                $semester->akhir->toDateString(),
            ]);
        }

        if ($request->filled('nis')) {
            $query->where('nis', $request->nis);
        }

        // Filter opsional per kelas
        if ($request->filled('id_kelas')) {
            $query->whereHas('siswa', function ($q) use ($request) {
                $q->where('id_kelas', $request->id_kelas);
            });
        }

        $perPage = $request->get('per_page', 100);
        $data    = $query->paginate($perPage);

        // ── Rekap semester ────────────────────────────────────────────────
        $rekapQuery = RiwayatReward::query();
        if ($semester) {
            $rekapQuery->whereBetween('tgl_input', [
                $semester->awal->toDateString(),
                $semester->akhir->toDateString(),
            ]);
        }
        if ($request->filled('nis')) {
            $rekapQuery->where('nis', $request->nis);
        }
        if ($request->filled('id_kelas')) {
            $rekapQuery->whereHas('siswa', function ($q) use ($request) {
                $q->where('id_kelas', $request->id_kelas);
            });
        }

        $rekap = $rekapQuery->selectRaw('COUNT(*) as total_kasus, SUM(point_reward) as total_poin')
            ->first();

        return response()->json([
            'success' => true,
            'semester' => $semester ? [
                'id'    => $semester->id_semester,
                'nama'  => $semester->semester,
                'awal'  => $semester->awal->toDateString(),
                'akhir' => $semester->akhir->toDateString(),
            ] : null,
            'rekap' => [
                'total_kasus' => (int) ($rekap->total_kasus ?? 0),
                'total_poin'  => (int) ($rekap->total_poin ?? 0),
            ],
            'data' => $data,
        ]);
    }

    /**
     * GET /api/bk/rekap-summary
     * Summary gabungan total poin pelanggaran, total reward, dan net poin
     * untuk semester aktif. Bisa difilter per NIS.
     */
    public function rekapSummary(Request $request)
    {
        $semester = $this->getSemesterAktif();

        $qPoin   = RiwayatPoin::query();
        $qReward = RiwayatReward::query();

        if ($semester) {
            $range = [$semester->awal->toDateString(), $semester->akhir->toDateString()];
            $qPoin->whereBetween('tgl_input', $range);
            $qReward->whereBetween('tgl_input', $range);
        }

        if ($request->filled('nis')) {
            $qPoin->where('nis', $request->nis);
            $qReward->where('nis', $request->nis);
        }

        $rekapPoin   = $qPoin->selectRaw('COUNT(*) as total_kasus, SUM(poin) as total_poin')->first();
        $rekapReward = $qReward->selectRaw('COUNT(*) as total_kasus, SUM(point_reward) as total_poin')->first();

        $totalPoin   = (int) ($rekapPoin->total_poin   ?? 0);
        $totalReward = (int) ($rekapReward->total_poin ?? 0);

        return response()->json([
            'success' => true,
            'semester' => $semester ? [
                'id'    => $semester->id_semester,
                'nama'  => $semester->semester,
                'awal'  => $semester->awal->toDateString(),
                'akhir' => $semester->akhir->toDateString(),
            ] : null,
            'pelanggaran' => [
                'total_kasus' => (int) ($rekapPoin->total_kasus ?? 0),
                'total_poin'  => $totalPoin,
            ],
            'reward' => [
                'total_kasus' => (int) ($rekapReward->total_kasus ?? 0),
                'total_poin'  => $totalReward,
            ],
            'net_poin' => $totalReward - $totalPoin,
        ]);
    }
}
