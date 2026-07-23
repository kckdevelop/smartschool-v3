<?php

namespace App\Http\Controllers\Bk;

use App\Http\Controllers\Controller;
use App\Models\RiwayatPoin;
use App\Models\JenisPelanggaran;
use App\Models\Pelanggaran;
use App\Models\UserSiswa;
use App\Models\Kelas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CatatPelanggaranController extends Controller
{
    public function index(Request $request)
    {
        $kelas    = Kelas::orderBy('tingkat')->orderBy('rombel')->get();
        $jenisList = JenisPelanggaran::orderBy('jenis_pelanggaran')->get();

        $query = RiwayatPoin::with(['siswa', 'guru'])
            ->orderByDesc('tgl_input');

        if ($request->filled('nis')) {
            $query->where('nis', $request->nis);
        }

        $data = $query->paginate(20)->withQueryString();

        return view('bk.catat-pelanggaran.index', compact('data', 'kelas', 'jenisList'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'tgl_input'   => 'required|date',
            'nis'         => 'required|string|max:20',
            'tingkat'     => 'required|integer|min:1|max:20',
            'pelanggaran' => 'required|string|max:100',
            'poin'        => 'required|integer|min:1',
        ]);

        $guru = Auth::user();
        RiwayatPoin::create([
            'tgl_input'   => $request->tgl_input,
            'nis'         => $request->nis,
            'tingkat'     => $request->tingkat,
            'pelanggaran' => $request->pelanggaran,
            'poin'        => $request->poin,
            'id_guru'     => $guru->id_guru ?? 1,
        ]);

        return redirect()->route('bk.catat-pelanggaran.index')
            ->with('success', 'Pelanggaran siswa berhasil dicatat.');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'tgl_input'   => 'required|date',
            'nis'         => 'required|string|max:20',
            'tingkat'     => 'required|integer|min:1|max:20',
            'pelanggaran' => 'required|string|max:100',
            'poin'        => 'required|integer|min:1',
        ]);

        RiwayatPoin::findOrFail($id)->update($request->only([
            'tgl_input', 'nis', 'tingkat', 'pelanggaran', 'poin'
        ]));

        return redirect()->route('bk.catat-pelanggaran.index')
            ->with('success', 'Catatan pelanggaran berhasil diperbarui.');
    }

    public function destroy($id)
    {
        RiwayatPoin::findOrFail($id)->delete();
        return redirect()->route('bk.catat-pelanggaran.index')
            ->with('success', 'Catatan pelanggaran berhasil dihapus.');
    }

    public function getSiswaBykelas(Request $request)
    {
        $siswa = UserSiswa::where('id_kelas', $request->id_kelas)
            ->orderBy('nama_siswa')->get(['nis', 'nama_siswa']);
        return response()->json($siswa);
    }

    /**
     * AJAX: search siswa by name (returns nis, nama_siswa, nama_kelas)
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
