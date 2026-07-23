<?php

namespace App\Http\Controllers\Bk;

use App\Http\Controllers\Controller;
use App\Models\RiwayatReward;
use App\Models\Reward;
use App\Models\UserSiswa;
use App\Models\Kelas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CatatRewardController extends Controller
{
    public function index(Request $request)
    {
        $kelas      = Kelas::where('status', 'aktif')->orderBy('tingkat')->orderBy('rombel')->get();
        $rewardList = Reward::orderBy('detail_reward')->get();

        $query = RiwayatReward::with(['siswa.kelas', 'guru'])
            ->orderByDesc('tgl_input');

        if ($request->filled('nis')) {
            $query->where('nis', $request->nis);
        }

        $data = $query->paginate(20)->withQueryString();

        return view('bk.catat-reward.index', compact('data', 'kelas', 'rewardList'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'tgl_input'    => 'required|date',
            'nis'          => 'required|string|max:20',
            'tingkat'      => 'required|integer|min:1|max:20',
            'reward'       => 'required|string|max:100',
            'point_reward' => 'required|integer|min:1',
        ]);

        $guru = Auth::user();
        RiwayatReward::create([
            'tgl_input'    => $request->tgl_input,
            'nis'          => $request->nis,
            'tingkat'      => $request->tingkat,
            'reward'       => $request->reward,
            'point_reward' => $request->point_reward,
            'id_guru'      => $guru->id_guru ?? 1,
        ]);

        return redirect()->route('bk.catat-reward.index')
            ->with('success', 'Reward siswa berhasil dicatat.');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'tgl_input'    => 'required|date',
            'nis'          => 'required|string|max:20',
            'tingkat'      => 'required|integer|min:1|max:20',
            'reward'       => 'required|string|max:100',
            'point_reward' => 'required|integer|min:1',
        ]);

        RiwayatReward::findOrFail($id)->update($request->only([
            'tgl_input', 'nis', 'tingkat', 'reward', 'point_reward'
        ]));

        return redirect()->route('bk.catat-reward.index')
            ->with('success', 'Catatan reward berhasil diperbarui.');
    }

    public function destroy($id)
    {
        RiwayatReward::findOrFail($id)->delete();
        return redirect()->route('bk.catat-reward.index')
            ->with('success', 'Catatan reward berhasil dihapus.');
    }

    public function getSiswaBykelas(Request $request)
    {
        $siswa = UserSiswa::where('id_kelas', $request->id_kelas)
            ->orderBy('nama_siswa')->get(['nis', 'nama_siswa']);
        return response()->json($siswa);
    }

    /**
     * AJAX: search siswa by name/NIS for catat-reward form
     */
    public function searchSiswa(Request $request)
    {
        $q = $request->get('q', '');
        $siswa = UserSiswa::with('kelas')
            ->where(function ($query) use ($q) {
                $query->where('nama_siswa', 'like', "%{$q}%")
                      ->orWhere('nis', 'like', "%{$q}%");
            })
            ->where('status', 'aktif')
            ->orderBy('nama_siswa')
            ->limit(15)
            ->get(['nis', 'nama_siswa', 'id_kelas']);

        return response()->json($siswa->map(function ($s) {
            return [
                'nis'        => $s->nis,
                'nama_siswa' => $s->nama_siswa,
                'nama_kelas' => $s->kelas ? $s->kelas->nama_kelas : '-',
                'tingkat'    => $s->kelas ? $s->kelas->tingkat : '',
            ];
        }));
    }
}
