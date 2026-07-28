<?php

namespace App\Http\Controllers\Bk;

use App\Http\Controllers\Controller;
use App\Models\BimbinganKonseling;
use App\Models\UserSiswa;
use App\Models\Kelas;
use App\Models\Guru;
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
        $guruBkList = Guru::where('guru_bk', 'ya')->where('status', 'aktif')->orderBy('nama_guru')->get();

        return view('bk.buku-konsultasi.index', compact('data', 'kelas', 'guruBkList'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'tanggal'      => 'required|date',
            'nis'          => 'required|string|max:20',
            'id_guru'      => 'required|exists:guru,id_guru',
            'jenis_masalah'=> 'required|string|max:100',
            'uraian'       => 'required|string',
        ]);

        BimbinganKonseling::create([
            'tanggal'       => $request->tanggal,
            'nis'           => $request->nis,
            'id_guru'       => $request->id_guru,
            'jenis_masalah' => $request->jenis_masalah,
            'uraian'        => $request->uraian,
            'tindak_lanjut' => $request->tindak_lanjut,
            'status'        => $request->status ?? 'proses',
        ]);

        return redirect()->route('bk.buku-konsultasi.index')
            ->with('success', 'Buku konsultasi berhasil dicatat.');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'tanggal'      => 'required|date',
            'nis'          => 'required|string|max:20',
            'id_guru'      => 'required|exists:guru,id_guru',
            'jenis_masalah'=> 'required|string|max:100',
            'uraian'       => 'required|string',
            'status'       => 'required|in:proses,selesai',
        ]);

        $item = $id instanceof BimbinganKonseling ? $id : BimbinganKonseling::findOrFail($id);
        $item->update($request->only([
            'tanggal', 'nis', 'id_guru', 'jenis_masalah', 'uraian', 'tindak_lanjut', 'status'
        ]));

        return redirect()->route('bk.buku-konsultasi.index')
            ->with('success', 'Buku konsultasi berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $item = $id instanceof BimbinganKonseling ? $id : BimbinganKonseling::findOrFail($id);
        $item->delete();

        return redirect()->route('bk.buku-konsultasi.index')
            ->with('success', 'Data konsultasi berhasil dihapus.');
    }
}
