<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Reward;
use App\Models\RiwayatReward;
use App\Models\UserSiswa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class RewardController extends Controller
{
    /**
     * GET /api/bk/reward
     */
    public function index(Request $request)
    {
        $query = RiwayatReward::with(['siswa.kelas', 'guru'])
            ->orderByDesc('tgl_input');

        if ($request->filled('nis')) {
            $query->where('nis', $request->nis);
        }

        if ($request->filled('id_kelas')) {
            $query->whereHas('siswa', function ($q) use ($request) {
                $q->where('id_kelas', $request->id_kelas);
            });
        }

        $perPage = $request->get('per_page', 15);
        $data = $query->paginate($perPage);

        return response()->json([
            'success' => true,
            'data' => $data,
        ]);
    }

    /**
     * POST /api/bk/reward
     */
    public function store(Request $request)
    {
        $request->validate([
            'nis' => 'required|exists:user_siswa,nis',
            'id_reward' => 'required|integer|exists:reward,id_reward',
            'tanggal' => 'required|date',
            'keterangan' => 'nullable|string|max:500',
        ]);

        $reward = Reward::findOrFail($request->id_reward);
        $poinReward = $reward->skor ?? 0;

        $siswa = UserSiswa::with('kelas')->findOrFail($request->nis);
        $tingkat = $siswa->kelas ? $siswa->kelas->tingkat : 0;

        $user = Auth::user();
        $idGuru = $user->id_guru ?? 1;

        DB::beginTransaction();
        try {
            // 1. Simpan ke riwayat_reward
            $riwayat = RiwayatReward::create([
                'tgl_input' => $request->tanggal,
                'nis' => $request->nis,
                'tingkat' => $tingkat,
                'reward' => $reward->detail_reward,
                'point_reward' => $poinReward,
                'id_guru' => $idGuru,
            ]);

            // 2. Kurangi poin pelanggaran siswa (poin minimum 0)
            if ($siswa->poin >= $poinReward) {
                $siswa->decrement('poin', $poinReward);
            } else {
                $siswa->update(['poin' => 0]);
            }

            // 3. Catat pengurangan poin di riwayat_poin (sebagai pengurang)
            DB::table('riwayat_poin')->insert([
                'tgl_input' => $request->tanggal,
                'nis' => (string) $request->nis,
                'tingkat' => $tingkat,
                'pelanggaran' => 'Reward: ' . $reward->detail_reward,
                'poin' => -$poinReward,
                'id_guru' => $idGuru,
            ]);

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Gagal mencatat reward: ' . $e->getMessage(),
            ], 500);
        }

        return response()->json([
            'success' => true,
            'message' => 'Reward berhasil dicatat dan poin pelanggaran siswa berkurang.',
            'data' => [
                'id_reward' => $riwayat->id_reward,
                'nis' => $request->nis,
                'nama_siswa' => $siswa->nama_siswa,
                'reward' => $reward->detail_reward,
                'point_reward' => $poinReward,
                'tanggal' => $request->tanggal,
            ],
        ], 201);
    }

    /**
     * GET /api/bk/reward/{id}
     */
    public function show($id)
    {
        $riwayat = RiwayatReward::with(['siswa.kelas', 'guru'])->findOrFail($id);

        return response()->json([
            'success' => true,
            'data' => $riwayat,
        ]);
    }

    /**
     * DELETE /api/bk/reward/{id}
     */
    public function destroy($id)
    {
        $riwayat = RiwayatReward::findOrFail($id);
        $poinReward = $riwayat->point_reward ?? 0;

        DB::beginTransaction();
        try {
            // 1. Kembalikan poin siswa (tambah lagi)
            $siswa = UserSiswa::findOrFail($riwayat->nis);
            $siswa->increment('poin', $poinReward);

            // 2. Hapus catatan pengurangan di riwayat_poin
            DB::table('riwayat_poin')
                ->where('nis', $riwayat->nis)
                ->where('tgl_input', $riwayat->tgl_input)
                ->where('poin', -$poinReward)
                ->where('pelanggaran', 'like', 'Reward:%')
                ->limit(1)
                ->delete();

            // 3. Hapus riwayat reward
            $riwayat->delete();
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus catatan reward: ' . $e->getMessage(),
            ], 500);
        }

        return response()->json([
            'success' => true,
            'message' => 'Catatan reward berhasil dihapus.',
        ]);
    }
}
