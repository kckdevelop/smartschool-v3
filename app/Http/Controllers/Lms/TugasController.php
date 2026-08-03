<?php

namespace App\Http\Controllers\Lms;

use App\Http\Controllers\Controller;
use App\Models\LmsTugas;
use App\Models\LmsKursus;
use App\Models\LmsPengumpulan;
use App\Models\LmsSoal;
use App\Models\LmsSoalPilihan;
use App\Models\Kelas;
use App\Models\Guru;
use App\Models\UserSiswa;
use App\Services\DocxQuizParserService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Artisan;

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

    public function uploadKuisForm()
    {
        $kelas = Kelas::where('status', 'aktif')->orderBy('tingkat')->orderBy('rombel')->get();
        $gurus = Guru::where('status', 'aktif')->orderBy('nama_guru')->get();
        return view('lms.tugas.upload_kuis', compact('kelas', 'gurus'));
    }

    public function downloadTemplate()
    {
        $filePath = public_path('templates/template_kuis_smartschool.docx');
        if (!file_exists($filePath)) {
            Artisan::call('quiz:generate-template');
        }
        return response()->download($filePath, 'template_kuis_smartschool.docx');
    }

    public function processUploadKuis(Request $request, DocxQuizParserService $parser)
    {
        $request->validate([
            'judul_tugas' => 'required|string|max:150',
            'id_kelas'    => 'required|integer|exists:kelas,id_kelas',
            'id_guru'     => 'required|integer|exists:guru,id_guru',
            'tenggat'      => 'required|date',
            'status'      => 'required|in:aktif,tidak',
            'file_word'   => 'required|file|mimes:docx|max:20480',
        ]);

        $kelas = Kelas::findOrFail($request->id_kelas);

        $kursus = LmsKursus::firstOrCreate(
            ['id_kelas' => $request->id_kelas, 'id_guru' => $request->id_guru],
            ['nama_kursus' => 'Kursus ' . $kelas->tingkat . ' ' . $kelas->rombel]
        );

        $docxPath = $request->file('file_word')->store('kuis_word_files', 'public');
        $fullPath = storage_path('app/public/' . $docxPath);

        try {
            $parsedQuestions = $parser->parseDocx($fullPath);
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Gagal memproses file Word: ' . $e->getMessage());
        }

        if (empty($parsedQuestions)) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Tidak ada soal yang ditemukan dalam file Word. Pastikan format tabel sesuai dengan template.');
        }

        $tugas = LmsTugas::create([
            'id_kursus'    => $kursus->id_kursus,
            'judul'        => $request->judul_tugas,
            'deskripsi'    => $request->deskripsi ?? 'Kuis Online',
            'tenggat'      => $request->tenggat,
            'tipe'         => 'kuis',
            'file_path'    => $docxPath,
            'is_published' => $request->status === 'aktif',
        ]);

        foreach ($parsedQuestions as $qData) {
            $soal = LmsSoal::create([
                'id_tugas'      => $tugas->id_tugas,
                'nomor_soal'    => $qData['nomor_soal'],
                'jenis_soal'    => $qData['jenis_soal'],
                'pertanyaan'    => $qData['pertanyaan'],
                'gambar'        => $qData['gambar'],
                'kunci_jawaban' => $qData['kunci_jawaban'],
            ]);

            $kunciList = array_map('trim', explode(',', strtoupper($qData['kunci_jawaban'])));

            foreach ($qData['pilihan'] as $pData) {
                $isKunci = in_array(strtoupper(trim($pData['kunci'])), $kunciList);
                LmsSoalPilihan::create([
                    'id_soal'  => $soal->id_soal,
                    'kunci'    => $pData['kunci'],
                    'teks'     => $pData['teks'],
                    'gambar'   => $pData['gambar'],
                    'is_kunci' => $isKunci,
                ]);
            }
        }

        return redirect()->route('lms.tugas.show', $tugas->id_tugas)
            ->with('success', 'Kuis berhasil dibuat dari file Word! Total ' . count($parsedQuestions) . ' soal berhasil diimpor.');
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

        $kursus = LmsKursus::firstOrCreate(
            ['id_kelas' => $request->id_kelas, 'id_guru' => $request->id_guru],
            ['nama_kursus' => 'Kursus ' . $kelas->tingkat . ' ' . $kelas->rombel]
        );

        LmsTugas::create([
            'id_kursus'    => $kursus->id_kursus,
            'judul'        => $request->judul_tugas,
            'deskripsi'    => $request->deskripsi,
            'tenggat'      => now()->addDays(7),
            'tipe'         => 'pdf',
            'file_path'    => null,
            'is_published' => $request->status === 'aktif',
        ]);

        return redirect()->route('lms.tugas.index')
            ->with('success', 'Tugas baru berhasil ditambahkan.');
    }

    public function show($id)
    {
        $tugas = LmsTugas::with(['kursus.kelas.jurusan', 'kursus.guru', 'soal.pilihan'])->findOrFail($id);
        
        $students = UserSiswa::where('id_kelas', $tugas->kursus->id_kelas)
            ->where('status', 'aktif')
            ->orderBy('nama_siswa')
            ->get();

        $submissions = LmsPengumpulan::where('id_tugas', $id)->get()->keyBy('nis');

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
            Storage::disk('public')->delete($tugas->file_path);
        }

        $submisiList = LmsPengumpulan::where('id_tugas', $id)->get();
        foreach ($submisiList as $submisi) {
            if ($submisi->file_path) {
                Storage::disk('public')->delete($submisi->file_path);
            }
        }
        LmsPengumpulan::where('id_tugas', $id)->delete();

        // Delete soal & pilihan
        $soalList = LmsSoal::where('id_tugas', $id)->get();
        foreach ($soalList as $s) {
            LmsSoalPilihan::where('id_soal', $s->id_soal)->delete();
        }
        LmsSoal::where('id_tugas', $id)->delete();

        $tugas->delete();

        return redirect()->route('lms.tugas.index')
            ->with('success', 'Tugas/Kuis berhasil dihapus.');
    }
}
