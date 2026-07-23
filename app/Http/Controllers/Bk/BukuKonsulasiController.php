<?php

namespace App\Http\Controllers\Bk;

use App\Http\Controllers\Controller;
use App\Models\BimbinganKonseling;
use App\Models\UserSiswa;
use App\Models\Kelas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BukuKonsulasiController extends Controller
{
    public function index(Request $request)
    {
        $kelas = Kelas::where('status', 'aktif')->orderBy('tingkat')->orderBy('rombel')->get();
        $query = BimbinganKonseling::with(['siswa.kelas', 'guru'])->orderByDesc('tanggal');

        if ($request->filled('id_kelas')) {
            $query->whereHas('siswa', function($q) use ($request) {
                $q->where('id_kelas', $request->id_kelas);
            });
        }

        $data = $query->paginate(15)->withQueryString();
        return view('bk.buku-konsultasi.index', compact('data', 'kelas'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'tanggal'      => 'required|date',
            'nis'          => 'required|string|max:20',
            'jenis_masalah'=> 'required|string|max:100',
            'uraian'       => 'required|string',
        ]);

        $guru = Auth::user();
        BimbinganKonseling::create([
            'tanggal'       => $request->tanggal,
            'nis'           => $request->nis,
            'jenis_masalah' => $request->jenis_masalah,
            'uraian'        => $request->uraian,
            'tindak_lanjut' => $request->tindak_lanjut,
            'status'        => $request->status ?? 'proses',
            'id_guru'       => $guru->id_guru ?? 1,
        ]);

        return redirect()->route('bk.buku-konsultasi.index')
            ->with('success', 'Buku konsultasi berhasil dicatat.');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'tanggal'      => 'required|date',
            'nis'          => 'required|string|max:20',
            'jenis_masalah'=> 'required|string|max:100',
            'uraian'       => 'required|string',
            'status'       => 'required|in:proses,selesai',
        ]);

        BimbinganKonseling::findOrFail($id)->update($request->only([
            'tanggal', 'nis', 'jenis_masalah', 'uraian', 'tindak_lanjut', 'status'
        ]));

        return redirect()->route('bk.buku-konsultasi.index')
            ->with('success', 'Buku konsultasi berhasil diperbarui.');
    }

    public function destroy($id)
    {
        BimbinganKonseling::findOrFail($id)->delete();
        return redirect()->route('bk.buku-konsultasi.index')
            ->with('success', 'Data konsultasi berhasil dihapus.');
    }
}
