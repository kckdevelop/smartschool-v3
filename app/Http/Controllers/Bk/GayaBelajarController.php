<?php

namespace App\Http\Controllers\Bk;

use App\Http\Controllers\Controller;
use App\Models\GayaBelajar;
use App\Models\UserSiswa;
use App\Models\Kelas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GayaBelajarController extends Controller
{
    public function index(Request $request)
    {
        $kelas = Kelas::where('status', 'aktif')->orderBy('tingkat')->orderBy('rombel')->get();
        $query = GayaBelajar::with(['siswa.kelas', 'guru'])->orderByDesc('created_at');

        if ($request->filled('gaya_belajar')) {
            $query->where('gaya_belajar', $request->gaya_belajar);
        }
        if ($request->filled('nis')) {
            $query->where('nis', $request->nis);
        }
        if ($request->filled('id_kelas')) {
            $query->whereHas('siswa', function($q) use ($request) {
                $q->where('id_kelas', $request->id_kelas);
            });
        }

        $data = $query->paginate(20)->withQueryString();
        return view('bk.gaya-belajar.index', compact('data', 'kelas'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nis'          => 'required|string|max:20',
            'gaya_belajar' => 'required|in:visual,auditori,kinestetik',
            'minat'        => 'nullable|string|max:100',
            'catatan'      => 'nullable|string',
        ]);

        $guru = Auth::user();
        GayaBelajar::create([
            'nis'          => $request->nis,
            'gaya_belajar' => $request->gaya_belajar,
            'minat'        => $request->minat,
            'catatan'      => $request->catatan,
            'id_guru'      => $guru->id_guru ?? 1,
        ]);

        return redirect()->route('bk.gaya-belajar.index')
            ->with('success', 'Data gaya belajar berhasil ditambahkan.');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nis'          => 'required|string|max:20',
            'gaya_belajar' => 'required|in:visual,auditori,kinestetik',
            'minat'        => 'nullable|string|max:100',
            'catatan'      => 'nullable|string',
        ]);

        GayaBelajar::findOrFail($id)->update($request->only([
            'nis', 'gaya_belajar', 'minat', 'catatan'
        ]));

        return redirect()->route('bk.gaya-belajar.index')
            ->with('success', 'Data gaya belajar berhasil diperbarui.');
    }

    /** Hanya update kolom catatan oleh Guru BK */
    public function updateCatatan(Request $request, $id)
    {
        $request->validate([
            'catatan' => 'nullable|string|max:2000',
        ]);

        GayaBelajar::findOrFail($id)->update([
            'catatan' => $request->catatan,
            'id_guru' => Auth::id() ?? 1,
        ]);

        return redirect()->route('bk.gaya-belajar.index')
            ->with('success', 'Catatan guru BK berhasil disimpan.');
    }

    public function destroy($id)
    {
        GayaBelajar::findOrFail($id)->delete();
        return redirect()->route('bk.gaya-belajar.index')
            ->with('success', 'Data gaya belajar berhasil dihapus.');
    }
}
