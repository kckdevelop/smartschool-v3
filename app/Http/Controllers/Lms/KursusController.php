<?php

namespace App\Http\Controllers\Lms;

use App\Http\Controllers\Controller;
use App\Models\LmsKursus;
use App\Models\Guru;
use App\Models\Kelas;
use Illuminate\Http\Request;

class KursusController extends Controller
{
    public function index(Request $request)
    {
        $search   = $request->input('search');
        $id_kelas = $request->input('id_kelas');
        $id_guru  = $request->input('id_guru');

        $query = LmsKursus::with(['guru', 'kelas.jurusan', 'tugas']);

        if (!empty($search)) {
            $query->where('nama_kursus', 'like', "%{$search}%");
        }

        if (!empty($id_kelas)) {
            $query->where('id_kelas', $id_kelas);
        }

        if (!empty($id_guru)) {
            $query->where('id_guru', $id_guru);
        }

        $kursusList = $query->orderBy('id_kursus', 'desc')->paginate(10);

        $kelas = Kelas::where('status', 'aktif')->orderBy('tingkat')->orderBy('rombel')->get();
        $gurus = Guru::where('status', 'aktif')->orderBy('nama_guru')->get();

        return view('lms.kursus.index', compact('kursusList', 'kelas', 'gurus'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_kursus' => 'required|string|max:100',
            'id_kelas'    => 'required|integer|exists:kelas,id_kelas',
            'id_guru'     => 'required|integer|exists:guru,id_guru',
        ]);

        LmsKursus::create($request->only([
            'nama_kursus', 'id_kelas', 'id_guru'
        ]));

        return redirect()->route('lms.kursus.index')
            ->with('success', 'Kursus baru berhasil ditambahkan.');
    }

    public function update(Request $request, $id)
    {
        $kursus = LmsKursus::findOrFail($id);

        $request->validate([
            'nama_kursus' => 'required|string|max:100',
            'id_kelas'    => 'required|integer|exists:kelas,id_kelas',
            'id_guru'     => 'required|integer|exists:guru,id_guru',
        ]);

        $kursus->update($request->only([
            'nama_kursus', 'id_kelas', 'id_guru'
        ]));

        return redirect()->route('lms.kursus.index')
            ->with('success', 'Data kursus berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $kursus = LmsKursus::findOrFail($id);
        
        // Hapus tugas-tugas di dalamnya
        foreach ($kursus->tugas as $tugas) {
            if ($tugas->file_path) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($tugas->file_path);
            }
            foreach ($tugas->pengumpulan as $submisi) {
                if ($submisi->file_path) {
                    \Illuminate\Support\Facades\Storage::disk('public')->delete($submisi->file_path);
                }
            }
            $tugas->pengumpulan()->delete();
            $tugas->delete();
        }

        $kursus->delete();

        return redirect()->route('lms.kursus.index')
            ->with('success', 'Kursus berhasil dihapus.');
    }
}
