<?php

namespace App\Http\Controllers\Lms;

use App\Http\Controllers\Controller;
use App\Models\LmsTugas;
use App\Models\LmsKursus;
use App\Models\LmsPengumpulan;
use App\Models\Kelas;
use App\Models\Guru;
use App\Models\UserSiswa;
use Illuminate\Http\Request;

class TugasController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        $id_kelas = $request->input('id_kelas');
        $id_guru = $request->input('id_guru');

        $query = LmsTugas::with(['kursus.kelas.jurusan', 'kursus.guru']);

        if (!empty($search)) {
            $query->where('judul', 'like', "%{$search}%");
        }

        if (!empty($id_kelas)) {
            $query->whereHas('kursus', function ($q) use ($id_kelas) {
                $q->where('id_kelas', $id_kelas);
            });
        }

        if (!empty($id_guru)) {
            $query->whereHas('kursus', function ($q) use ($id_guru) {
                $q->where('id_guru', $id_guru);
            });
        }

        $tugasList = $query->orderBy('id_tugas', 'desc')->paginate(10);

        $kelas = Kelas::where('status', 'aktif')->orderBy('tingkat')->orderBy('rombel')->get();
        $gurus = Guru::where('status', 'aktif')->orderBy('nama_guru')->get();

        return view('lms.tugas.index', compact('tugasList', 'kelas', 'gurus'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'judul_tugas' => 'required|string|max:150',
            'id_kelas'    => 'required|integer|exists:kelas,id_kelas',
            'id_guru'     => 'required|integer|exists:guru,id_guru',
            'deskripsi'   => 'required|string',
            'status'      => 'required|in:aktif,tidak',
        ]);

        $kelas = Kelas::findOrFail($request->id_kelas);

        // Cari atau buat LmsKursus yang sesuai
        $kursus = LmsKursus::firstOrCreate(
            ['id_kelas' => $request->id_kelas, 'id_guru' => $request->id_guru],
            ['nama_kursus' => 'Kursus ' . $kelas->tingkat . ' ' . $kelas->rombel]
        );

        LmsTugas::create([
            'id_kursus'    => $kursus->id_kursus,
            'judul'        => $request->judul_tugas,
            'deskripsi'    => $request->deskripsi,
            'tenggat'      => now()->addDays(7), // Default tenggat 7 hari
            'tipe'         => 'pdf',
            'file_path'    => null,
            'is_published' => $request->status === 'aktif',
        ]);

        return redirect()->route('lms.tugas.index')
            ->with('success', 'Tugas baru berhasil ditambahkan.');
    }

    public function show($id)
    {
        $tugas = LmsTugas::with(['kursus.kelas.jurusan', 'kursus.guru'])->findOrFail($id);
        
        // Ambil semua siswa di kelas tersebut
        $students = UserSiswa::where('id_kelas', $tugas->kursus->id_kelas)
            ->where('status', 'aktif')
            ->orderBy('nama_siswa')
            ->get();

        // Ambil semua pengumpulan yang ada untuk tugas ini
        $submissions = LmsPengumpulan::where('id_tugas', $id)->get()->keyBy('nis');

        // Petakan ke array data tagihan
        $tagihanList = $students->map(function ($s) use ($submissions) {
            $sub = $submissions->get($s->nis);
            return (object) [
                'id_pengumpulan' => $sub ? $sub->id_pengumpulan : null,
                'nis'            => $s->nis,
                'nama_siswa'     => $s->nama_siswa,
                'kelas_nama'     => $s->kelas ? $s->kelas->tingkat . ' ' . $s->kelas->rombel : '-',
                'status_tugas'   => $sub ? ($sub->status === 'dinilai' ? 'cek' : ($sub->status === 'diserahkan' ? 'sudah' : 'belum')) : 'belum',
                'file_path'      => $sub ? $sub->file_path : null,
                'catatan'        => $sub ? $sub->catatan : null,
                'nilai'          => $sub ? $sub->nilai : null,
            ];
        });

        return view('lms.tugas.show', compact('tugas', 'tagihanList'));
    }

    public function update(Request $request, $id)
    {
        $tugas = LmsTugas::findOrFail($id);

        $request->validate([
            'judul_tugas' => 'required|string|max:150',
            'id_kelas'    => 'required|integer|exists:kelas,id_kelas',
            'id_guru'     => 'required|integer|exists:guru,id_guru',
            'deskripsi'   => 'required|string',
            'status'      => 'required|in:aktif,tidak',
        ]);

        $kelas = Kelas::findOrFail($request->id_kelas);

        // Cari atau buat LmsKursus yang sesuai
        $kursus = LmsKursus::firstOrCreate(
            ['id_kelas' => $request->id_kelas, 'id_guru' => $request->id_guru],
            ['nama_kursus' => 'Kursus ' . $kelas->tingkat . ' ' . $kelas->rombel]
        );

        $tugas->update([
            'id_kursus'    => $kursus->id_kursus,
            'judul'        => $request->judul_tugas,
            'deskripsi'    => $request->deskripsi,
            'is_published' => $request->status === 'aktif',
        ]);

        return redirect()->route('lms.tugas.index')
            ->with('success', 'Tugas berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $tugas = LmsTugas::findOrFail($id);
        
        if ($tugas->file_path) {
            \Illuminate\Support\Facades\Storage::disk('public')->delete($tugas->file_path);
        }

        // Hapus pengumpulan terkait
        $submisiList = LmsPengumpulan::where('id_tugas', $id)->get();
        foreach ($submisiList as $submisi) {
            if ($submisi->file_path) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($submisi->file_path);
            }
        }
        LmsPengumpulan::where('id_tugas', $id)->delete();

        $tugas->delete();

        return redirect()->route('lms.tugas.index')
            ->with('success', 'Tugas berhasil dihapus.');
    }
}
