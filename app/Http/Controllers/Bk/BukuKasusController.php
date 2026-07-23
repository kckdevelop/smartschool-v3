<?php

namespace App\Http\Controllers\Bk;

use App\Http\Controllers\Controller;
use App\Models\BukuKasus;
use App\Models\UserSiswa;
use App\Models\Kelas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BukuKasusController extends Controller
{
    public function index(Request $request)
    {
        $kelas = Kelas::orderBy('tingkat')->orderBy('rombel')->get();
        $query = BukuKasus::with(['siswa.kelas', 'guru'])->orderByDesc('tanggal');

        // Filter hanya kelas
        if ($request->filled('id_kelas')) {
            $query->whereHas('siswa', function ($q) use ($request) {
                $q->where('id_kelas', $request->id_kelas);
            });
        }

        $data = $query->paginate(15)->withQueryString();
        return view('bk.buku-kasus.index', compact('data', 'kelas'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'tanggal'      => 'required|date',
            'nis'          => 'required|string|max:20',
            'judul_kasus'  => 'required|string|max:150',
            'uraian_kasus' => 'required|string',
        ]);

        $guru = Auth::user();
        BukuKasus::create([
            'tanggal'       => $request->tanggal,
            'nis'           => $request->nis,
            'judul_kasus'   => $request->judul_kasus,
            'uraian_kasus'  => $request->uraian_kasus,
            'tindak_lanjut' => $request->tindak_lanjut,
            'status'        => $request->status ?? 'proses',
            'id_guru'       => $guru->id_guru ?? 1,
        ]);

        return redirect()->route('bk.buku-kasus.index')
            ->with('success', 'Buku kasus berhasil dicatat.');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'tanggal'      => 'required|date',
            'nis'          => 'required|string|max:20',
            'judul_kasus'  => 'required|string|max:150',
            'uraian_kasus' => 'required|string',
            'status'       => 'required|in:proses,selesai',
        ]);

        BukuKasus::findOrFail($id)->update($request->only([
            'tanggal', 'nis', 'judul_kasus', 'uraian_kasus', 'tindak_lanjut', 'status'
        ]));

        return redirect()->route('bk.buku-kasus.index')
            ->with('success', 'Buku kasus berhasil diperbarui.');
    }

    public function destroy($id)
    {
        BukuKasus::findOrFail($id)->delete();
        return redirect()->route('bk.buku-kasus.index')
            ->with('success', 'Buku kasus berhasil dihapus.');
    }

    /**
     * AJAX: search siswa by name / NIS
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
