<?php

namespace App\Http\Controllers\Lms;

use App\Http\Controllers\Controller;
use App\Models\LmsPengumpulan;
use App\Models\LmsTugas;
use App\Models\Kelas;
use Illuminate\Http\Request;

class TagihanTugasController extends Controller
{
    public function index(Request $request)
    {
        $id_tugas = $request->input('id_tugas');
        $id_kelas = $request->input('id_kelas');
        $status_tugas = $request->input('status_tugas');
        $search = $request->input('search');

        $query = LmsPengumpulan::with(['tugas.kursus.kelas.jurusan', 'tugas.kursus.guru', 'siswa.kelas.jurusan']);

        if (!empty($id_tugas)) {
            $query->where('id_tugas', $id_tugas);
        }

        if (!empty($id_kelas)) {
            $query->whereHas('siswa', function ($q) use ($id_kelas) {
                $q->where('id_kelas', $id_kelas);
            });
        }

        if (!empty($status_tugas)) {
            // Map status_tugas web filter values to database status
            // web: 'belum'|'sudah'|'cek' => db: 'belum'|'diserahkan'|'dinilai'
            $dbStatus = 'belum';
            if ($status_tugas === 'sudah') $dbStatus = 'diserahkan';
            if ($status_tugas === 'cek') $dbStatus = 'dinilai';
            $query->where('status', $dbStatus);
        }

        if (!empty($search)) {
            $query->whereHas('siswa', function ($q) use ($search) {
                $q->where('nama_siswa', 'like', "%{$search}%")
                  ->orWhere('nis', 'like', "%{$search}%");
            });
        }

        $tagihanList = $query->orderBy('id_pengumpulan', 'desc')->paginate(15);

        $tugas = LmsTugas::orderBy('id_tugas', 'desc')->get();
        $kelas = Kelas::where('status', 'aktif')->orderBy('tingkat')->orderBy('rombel')->get();

        // Convert the paginated items to map database schema to blade expected schema
        $tagihanList->getCollection()->transform(function ($item) {
            $statusWeb = 'belum';
            if ($item->status === 'diserahkan') $statusWeb = 'sudah';
            if ($item->status === 'dinilai') $statusWeb = 'cek';

            // Fake fields so blade template remains compatible
            $item->id_tagihan = $item->id_pengumpulan;
            $item->status_tugas = $statusWeb;
            $item->upload_tugas = $item->file_path;
            
            // Adjust tugas properties
            if ($item->tugas) {
                $item->tugas->judul_tugas = $item->tugas->judul;
                $item->tugas->id_kelas = $item->tugas->kursus->id_kelas ?? null;
                $item->tugas->kelas = $item->tugas->kursus->kelas ?? null;
                $item->tugas->guru = $item->tugas->kursus->guru ?? null;
            }
            return $item;
        });

        return view('lms.tagihan.index', compact('tagihanList', 'tugas', 'kelas'));
    }

    public function show($id)
    {
        $tagihan = LmsPengumpulan::with(['tugas.kursus.guru', 'siswa.kelas.jurusan'])->findOrFail($id);

        // Map status to web template expected status
        $statusWeb = 'belum';
        if ($tagihan->status === 'diserahkan') $statusWeb = 'sudah';
        if ($tagihan->status === 'dinilai') $statusWeb = 'cek';

        $tagihan->id_tagihan = $tagihan->id_pengumpulan;
        $tagihan->status_tugas = $statusWeb;
        $tagihan->upload_tugas = $tagihan->file_path;
        
        if ($tagihan->tugas) {
            $tagihan->tugas->judul_tugas = $tagihan->tugas->judul;
            $tagihan->tugas->id_kelas = $tagihan->tugas->kursus->id_kelas ?? null;
            $tagihan->tugas->kelas = $tagihan->tugas->kursus->kelas ?? null;
            $tagihan->tugas->guru = $tagihan->tugas->kursus->guru ?? null;
        }

        return view('lms.tagihan.show', compact('tagihan'));
    }

    public function periksa(Request $request, $id)
    {
        $tagihan = LmsPengumpulan::findOrFail($id);

        $request->validate([
            'status_tugas' => 'required|in:belum,sudah,cek',
            'nilai'        => 'nullable|integer|min:0|max:100',
        ]);

        // Map web status back to database status
        $dbStatus = 'belum';
        if ($request->status_tugas === 'sudah') $dbStatus = 'diserahkan';
        if ($request->status_tugas === 'cek') $dbStatus = 'dinilai';

        $updateData = [
            'status' => $dbStatus,
        ];

        if ($request->has('nilai')) {
            $updateData['nilai'] = $request->nilai;
        }

        $tagihan->update($updateData);

        return redirect()->route('lms.tagihan.show', $tagihan->id_pengumpulan)
            ->with('success', 'Status tagihan tugas siswa berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $tagihan = LmsPengumpulan::findOrFail($id);
        if ($tagihan->file_path) {
            \Illuminate\Support\Facades\Storage::disk('public')->delete($tagihan->file_path);
        }
        $tagihan->delete();

        return redirect()->route('lms.tagihan.index')
            ->with('success', 'Tagihan tugas berhasil dihapus.');
    }
}
