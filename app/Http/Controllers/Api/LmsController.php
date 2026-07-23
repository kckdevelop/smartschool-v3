<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\LmsKursus;
use App\Models\LmsTugas;
use App\Models\LmsPengumpulan;
use App\Models\UserSiswa;
use App\Models\Guru;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class LmsController extends Controller
{
    /**
     * Tampilkan daftar kursus berdasarkan kelas (Siswa) atau pengampu (Guru)
     */
    public function indexKursus(Request $request)
    {
        $user = $request->user();

        if ($user instanceof UserSiswa) {
            // Siswa: Tampilkan kursus yang ditugaskan ke kelasnya
            $kursus = LmsKursus::with(['guru', 'kelas', 'tugas'])
                ->withCount('tugas')
                ->where('id_kelas', $user->id_kelas)
                ->get()
                ->map(function ($k) use ($user) {
                    // Hitung tugas yang sudah dikumpulkan siswa di kursus ini
                    $tugasIds = $k->tugas->pluck('id_tugas');
                    $selesai = LmsPengumpulan::whereIn('id_tugas', $tugasIds)
                        ->where('nis', $user->nis)
                        ->count();

                    return array_merge($k->toArray(), [
                        'nama_kelas'    => trim(($k->kelas->tingkat ?? '') . ' ' . ($k->kelas->rombel ?? '')),
                        'tugas_selesai' => $selesai,
                        'tugas_count'   => $k->tugas_count,
                    ]);
                });
        } elseif ($user instanceof Guru) {
            // Guru: Tampilkan kursus yang diampunya
            $kursus = LmsKursus::with(['guru', 'kelas'])
                ->withCount('tugas')
                ->where('id_guru', $user->id_guru)
                ->get()
                ->map(function ($k) {
                    return array_merge($k->toArray(), [
                        'nama_kelas'  => trim(($k->kelas->tingkat ?? '') . ' ' . ($k->kelas->rombel ?? '')),
                        'tugas_selesai' => 0,
                        'tugas_count'   => $k->tugas_count,
                    ]);
                });
        } else {
            // Admin / Lainnya: Tampilkan semua kursus
            $kursus = LmsKursus::with(['guru', 'kelas'])->withCount('tugas')->get()
                ->map(function ($k) {
                    return array_merge($k->toArray(), [
                        'nama_kelas'  => trim(($k->kelas->tingkat ?? '') . ' ' . ($k->kelas->rombel ?? '')),
                        'tugas_selesai' => 0,
                        'tugas_count'   => $k->tugas_count,
                    ]);
                });
        }

        return response()->json([
            'success' => true,
            'data' => $kursus
        ]);
    }

    /**
     * Tampilkan detail tugas untuk satu kursus
     */
    public function indexTugas(Request $request, $id_kursus)
    {
        $user = $request->user();
        $kursus = LmsKursus::findOrFail($id_kursus);

        $query = LmsTugas::where('id_kursus', $id_kursus);
        if ($user instanceof UserSiswa) {
            $query->where('is_published', true);
        }
        $tugas = $query->orderBy('tenggat', 'asc')->get();

        if ($user instanceof UserSiswa) {
            // Jika siswa: Petakan status pengumpulan tugas masing-masing
            $mapped = $tugas->map(function ($t) use ($user) {
                $submisi = LmsPengumpulan::where('id_tugas', $t->id_tugas)
                    ->where('nis', $user->nis)
                    ->first();

                return [
                    'id_tugas' => $t->id_tugas,
                    'judul' => $t->judul,
                    'deskripsi' => $t->deskripsi,
                    'tenggat' => $t->tenggat ? $t->tenggat->format('Y-m-d H:i:s') : null,
                    'status_pengumpulan' => $submisi ? $submisi->status : 'belum',
                    'nilai' => $submisi ? $submisi->nilai : null,
                    'file_path' => $submisi ? $submisi->file_path : null,
                    'catatan' => $submisi ? $submisi->catatan : null,
                    'tanggal_kumpul' => $submisi ? $submisi->updated_at?->format('Y-m-d H:i:s') : null,
                    'file_tugas' => $t->file_path,
                    'tipe' => $t->tipe ?? 'pdf',
                ];
            });

            return response()->json([
                'success' => true,
                'kursus' => [
                    'nama_kursus' => $kursus->nama_kursus,
                    'guru' => $kursus->guru?->nama_guru,
                ],
                'data' => $mapped
            ]);
        }

        // Jika Guru / Lainnya: Tampilkan data dasar tugas beserta hitungan submisi
        $allSubmisi = LmsPengumpulan::whereIn('id_tugas', $tugas->pluck('id_tugas'))->get();
        $jumlahSiswa = UserSiswa::where('id_kelas', $kursus->id_kelas)->count();

        $mappedTugas = $tugas->map(function ($t) use ($allSubmisi, $jumlahSiswa) {
            $tArr = $t->toArray();
            $tArr['file_tugas'] = $t->file_path;

            // Hitung submisi untuk tugas ini
            $submisiTugas = $allSubmisi->where('id_tugas', $t->id_tugas);
            $tArr['jumlah_kumpul']  = $submisiTugas->count();
            $tArr['jumlah_dinilai'] = $submisiTugas->where('status', 'dinilai')->count();
            $tArr['jumlah_siswa']   = $jumlahSiswa;

            return $tArr;
        });

        return response()->json([
            'success' => true,
            'kursus' => [
                'nama_kursus' => $kursus->nama_kursus,
                'guru' => $kursus->guru?->nama_guru,
            ],
            'data' => $mappedTugas
        ]);
    }

    /**
     * Semua tugas yang belum dikerjakan siswa (lintas kursus)
     */
    public function tugasBelum(Request $request)
    {
        $user = $request->user();

        if (!($user instanceof UserSiswa)) {
            return response()->json(['success' => false, 'message' => 'Hanya untuk siswa'], 403);
        }

        // Ambil semua kursus untuk kelas siswa
        $kursusIds = LmsKursus::where('id_kelas', $user->id_kelas)->pluck('id_kursus');

        // Ambil semua tugas dari kursus-kursus tsb yang berstatus published
        $semuaTugas = LmsTugas::with('kursus.guru')
            ->whereIn('id_kursus', $kursusIds)
            ->where('is_published', true)
            ->orderBy('tenggat', 'asc')
            ->get();

        // Filter: hanya yang belum dikumpulkan siswa
        $belum = $semuaTugas->filter(function ($t) use ($user) {
            $sudah = LmsPengumpulan::where('id_tugas', $t->id_tugas)
                ->where('nis', $user->nis)
                ->exists();
            return !$sudah;
        })->map(function ($t) {
            return [
                'id_tugas'    => $t->id_tugas,
                'id_kursus'   => $t->id_kursus,
                'nama_kursus' => $t->kursus?->nama_kursus,
                'nama_guru'   => $t->kursus?->guru?->nama_guru,
                'judul'       => $t->judul,
                'deskripsi'   => $t->deskripsi,
                'tenggat'     => $t->tenggat ? $t->tenggat->format('Y-m-d H:i:s') : null,
                'tipe'        => $t->tipe ?? 'pdf',
                'file_tugas'  => $t->file_path,
            ];
        })->values();

        return response()->json([
            'success' => true,
            'total'   => $belum->count(),
            'data'    => $belum,
        ]);
    }

    /**
     * Tampilkan detail satu tugas
     */
    public function showTugas(Request $request, $id_tugas)
    {
        $tugas = LmsTugas::with('kursus.guru')->findOrFail($id_tugas);
        $user = $request->user();

        if ($user instanceof UserSiswa && !$tugas->is_published) {
            return response()->json(['success' => false, 'message' => 'Tugas tidak ditemukan atau belum dipublish'], 404);
        }

        $submisi = null;
        if ($user instanceof UserSiswa) {
            $submisi = LmsPengumpulan::where('id_tugas', $id_tugas)
                ->where('nis', $user->nis)
                ->first();
        }

        $jumlahSiswa = UserSiswa::where('id_kelas', $tugas->kursus->id_kelas)->count();

        return response()->json([
            'success' => true,
            'data' => [
                'id_tugas' => $tugas->id_tugas,
                'id_kursus' => $tugas->id_kursus,
                'nama_kursus' => $tugas->kursus?->nama_kursus,
                'guru' => $tugas->kursus?->guru?->nama_guru,
                'judul' => $tugas->judul,
                'deskripsi' => $tugas->deskripsi,
                'tenggat' => $tugas->tenggat ? $tugas->tenggat->format('Y-m-d H:i:s') : null,
                'tipe' => $tugas->tipe ?? 'pdf',
                'file_tugas' => $tugas->file_path,
                'jumlah_siswa' => $jumlahSiswa,
                'submisi' => $submisi ? [
                    'id_pengumpulan' => $submisi->id_pengumpulan,
                    'file_path' => $submisi->file_path,
                    'catatan' => $submisi->catatan,
                    'nilai' => $submisi->nilai,
                    'status' => $submisi->status,
                    'tanggal_kumpul' => $submisi->updated_at?->format('Y-m-d H:i:s'),
                ] : null
            ]
        ]);
    }

    /**
     * Simpan Tugas baru (CRUD - Guru)
     */
    public function storeTugas(Request $request)
    {
        $request->validate([
            'id_kursus' => 'required|integer|exists:lms_kursus,id_kursus',
            'judul'     => 'required|string|max:150',
            'deskripsi' => 'required|string',
            'tenggat'   => 'nullable|date',
            'deadline'  => 'nullable|date',   // alias Flutter
            'tipe'      => 'nullable|in:pdf,gambar,teks',
            'file'      => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
        ]);

        // Dukung 'deadline' sebagai alias 'tenggat'
        $tenggat = $request->tenggat ?? $request->deadline;

        $filePath = null;
        if ($request->hasFile('file')) {
            $filePath = $request->file('file')->store('lms/assignments', 'public');
        }

        $tugas = LmsTugas::create([
            'id_kursus'    => $request->id_kursus,
            'judul'        => $request->judul,
            'deskripsi'    => $request->deskripsi,
            'tenggat'      => $tenggat,
            'tipe'         => $request->tipe ?? 'pdf',
            'file_path'    => $filePath,
            'is_published' => $request->has('is_published') ? (bool)$request->is_published : true,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Tugas berhasil dibuat.',
            'data'    => $tugas
        ], 201);
    }

    /**
     * Update Tugas (CRUD - Guru)
     */
    public function updateTugas(Request $request, $id_tugas)
    {
        $tugas = LmsTugas::findOrFail($id_tugas);

        $request->validate([
            'judul'    => 'sometimes|required|string|max:150',
            'deskripsi'=> 'sometimes|required|string',
            'tenggat'  => 'nullable|date',
            'deadline' => 'nullable|date',   // alias Flutter
            'tipe'     => 'nullable|in:pdf,gambar,teks',
            'file'     => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
        ]);

        $data = $request->only('judul', 'deskripsi', 'tipe');

        if ($request->has('is_published')) {
            $data['is_published'] = (bool)$request->is_published;
        }

        // Dukung 'deadline' sebagai alias 'tenggat'
        $tenggat = $request->tenggat ?? $request->deadline;
        if ($tenggat) {
            $data['tenggat'] = $tenggat;
        }

        if ($request->hasFile('file')) {
            // Hapus file lama jika ada
            if ($tugas->file_path) {
                Storage::disk('public')->delete($tugas->file_path);
            }
            $data['file_path'] = $request->file('file')->store('lms/assignments', 'public');
        }

        $tugas->update($data);

        return response()->json([
            'success' => true,
            'message' => 'Tugas berhasil diperbarui.',
            'data'    => $tugas
        ]);
    }

    /**
     * Hapus Tugas beserta semua file submisi siswanya (CRUD - Guru)
     */
    public function destroyTugas($id_tugas)
    {
        $tugas = LmsTugas::findOrFail($id_tugas);

        // Hapus file lampiran tugas guru jika ada
        if ($tugas->file_path) {
            Storage::disk('public')->delete($tugas->file_path);
        }

        // Hapus semua file tugas di storage
        $submisiList = LmsPengumpulan::where('id_tugas', $id_tugas)->get();
        foreach ($submisiList as $submisi) {
            if ($submisi->file_path) {
                Storage::disk('public')->delete($submisi->file_path);
            }
        }

        // Hapus record submisi
        LmsPengumpulan::where('id_tugas', $id_tugas)->delete();

        // Hapus tugas
        $tugas->delete();

        return response()->json([
            'success' => true,
            'message' => 'Tugas dan riwayat pengumpulan berhasil dihapus.'
        ]);
    }

    /**
     * Siswa: Kumpulkan Tugas (Upload PDF/Gambar, atau Teks Saja)
     */
    public function kumpulkanTugas(Request $request, $id_tugas)
    {
        $tugas = LmsTugas::findOrFail($id_tugas);
        $user = $request->user();

        if (!$user instanceof UserSiswa) {
            return response()->json([
                'success' => false,
                'message' => 'Hanya siswa yang dapat mengumpulkan tugas.'
            ], 403);
        }

        $request->validate([
            'file'    => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120', // Maks 5MB, opsional
            'catatan' => 'nullable|string',
        ]);

        // Minimal file atau catatan harus ada
        if (!$request->hasFile('file') && empty($request->catatan)) {
            return response()->json([
                'success' => false,
                'message' => 'Harap unggah file jawaban atau isi catatan.'
            ], 422);
        }

        // Upload file jika ada
        $filePath = null;
        if ($request->hasFile('file')) {
            $filePath = $request->file('file')->store('lms/submissions', 'public');
        }

        // Cari atau buat record pengumpulan
        $submisi = LmsPengumpulan::firstOrNew([
            'id_tugas' => $id_tugas,
            'nis'      => $user->nis,
        ]);

        // Jika sebelumnya sudah mengunggah file dan ada file baru, hapus file lamanya
        if ($filePath && $submisi->file_path) {
            Storage::disk('public')->delete($submisi->file_path);
        }

        if ($filePath) {
            $submisi->file_path = $filePath;
        }
        $submisi->catatan = $request->catatan;
        $submisi->status  = 'diserahkan';
        $submisi->save();

        return response()->json([
            'success' => true,
            'message' => 'Tugas berhasil dikumpulkan.',
            'data'    => $submisi
        ]);
    }

    /**
     * Guru: Lihat daftar submisi tugas
     */
    public function indexSubmisi($id_tugas)
    {
        $tugas = LmsTugas::with('kursus.kelas')->findOrFail($id_tugas);
        
        $id_kelas = $tugas->kursus->id_kelas ?? null;
        if (!$id_kelas) {
            return response()->json([
                'success' => true,
                'tugas'   => $tugas->judul,
                'data'    => []
            ]);
        }

        // Ambil semua siswa di kelas ini
        $siswa = UserSiswa::where('id_kelas', $id_kelas)->orderBy('nama_siswa')->get();

        // Ambil semua submisi untuk tugas ini
        $submisi = LmsPengumpulan::where('id_tugas', $id_tugas)->get()->keyBy('nis');

        // Gabungkan data siswa dengan data submisi
        $data = $siswa->map(function ($s) use ($submisi) {
            $sub = $submisi->get($s->nis);

            return [
                'nis'              => $s->nis,
                'nama_siswa'       => $s->nama_siswa,
                'id_pengumpulan'   => $sub ? $sub->id_pengumpulan : null,
                'file_path'        => $sub ? $sub->file_path : null,
                'catatan'          => $sub ? $sub->catatan : null,
                'nilai'            => $sub ? $sub->nilai : null,
                'status'           => $sub ? $sub->status : 'belum', // 'belum', 'diserahkan', 'dinilai'
                'waktu_kumpul'     => $sub ? $sub->updated_at->toDateTimeString() : null,
            ];
        });

        return response()->json([
            'success' => true,
            'tugas' => $tugas->judul,
            'data' => $data
        ]);
    }

    /**
     * Guru: Berikan nilai pada tugas siswa
     */
    public function nilaiSubmisi(Request $request, $id_pengumpulan)
    {
        $request->validate([
            'nilai' => 'required|integer|min:0|max:100',
        ]);

        $submisi = LmsPengumpulan::findOrFail($id_pengumpulan);
        $submisi->nilai = $request->nilai;
        $submisi->status = 'dinilai';
        $submisi->save();

        return response()->json([
            'success' => true,
            'message' => 'Nilai berhasil diberikan.',
            'data' => $submisi
        ]);
    }

    /**
     * Guru: Simpan Kursus baru
     */
    public function storeKursus(Request $request)
    {
        $user = $request->user();
        if (!($user instanceof Guru)) {
            return response()->json(['success' => false, 'message' => 'Hanya untuk guru.'], 403);
        }

        $request->validate([
            'nama_kursus' => 'required|string|max:100',
            'id_kelas'    => 'required|integer|exists:kelas,id_kelas',
        ]);

        $kursus = LmsKursus::create([
            'nama_kursus' => $request->nama_kursus,
            'id_kelas'    => $request->id_kelas,
            'id_guru'     => $user->id_guru,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Kursus berhasil dibuat.',
            'data'    => $kursus->load(['guru', 'kelas']),
        ], 201);
    }

    /**
     * Guru: Update Kursus
     */
    public function updateKursus(Request $request, $id)
    {
        $user = $request->user();
        $kursus = LmsKursus::findOrFail($id);

        if (!($user instanceof Guru) || $kursus->id_guru !== $user->id_guru) {
            return response()->json(['success' => false, 'message' => 'Hanya guru pengampu yang dapat mengubah kursus ini.'], 403);
        }

        $request->validate([
            'nama_kursus' => 'sometimes|required|string|max:100',
            'id_kelas'    => 'sometimes|required|integer|exists:kelas,id_kelas',
        ]);

        $kursus->update($request->only('nama_kursus', 'id_kelas'));

        return response()->json([
            'success' => true,
            'message' => 'Kursus berhasil diperbarui.',
            'data'    => $kursus->load(['guru', 'kelas']),
        ]);
    }

    /**
     * Guru: Hapus Kursus beserta tugas-tugasnya
     */
    public function destroyKursus(Request $request, $id)
    {
        $user = $request->user();
        $kursus = LmsKursus::findOrFail($id);

        if (!($user instanceof Guru) || $kursus->id_guru !== $user->id_guru) {
            return response()->json(['success' => false, 'message' => 'Hanya guru pengampu yang dapat menghapus kursus ini.'], 403);
        }

        $tugasList = LmsTugas::where('id_kursus', $id)->get();
        foreach ($tugasList as $tugas) {
            if ($tugas->file_path) {
                Storage::disk('public')->delete($tugas->file_path);
            }
            $submisiList = LmsPengumpulan::where('id_tugas', $tugas->id_tugas)->get();
            foreach ($submisiList as $submisi) {
                if ($submisi->file_path) {
                    Storage::disk('public')->delete($submisi->file_path);
                }
            }
            LmsPengumpulan::where('id_tugas', $tugas->id_tugas)->delete();
            $tugas->delete();
        }

        $kursus->delete();

        return response()->json([
            'success' => true,
            'message' => 'Kursus dan semua tugas di dalamnya berhasil dihapus.'
        ]);
    }
}

