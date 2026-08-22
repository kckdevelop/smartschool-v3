<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Kemajuan;
use App\Models\Guru;
use App\Models\UserSiswa;
use Illuminate\Http\Request;

class JurnalGuruController extends Controller
{
    /**
     * Daftar jurnal guru.
     */
    public function index(Request $request)
    {
        $query = Kemajuan::with(['kelas', 'mapel', 'guru']);

        // Filter berdasarkan Guru yang login (jika user adalah Guru)
        // Jika as_wali=1, skip filter id_guru agar Wali Kelas bisa lihat semua jurnal di kelasnya
        $idGuru = $request->user()->id_guru;
        if (!$idGuru && isset($request->user()->no_id)) {
            $idGuru = Guru::where('no_id', $request->user()->no_id)->value('id_guru');
        }
        $asWali = $request->boolean('as_wali', false);
        if ($idGuru && !$asWali) {
            $query->where('id_guru', $idGuru);
        }

        if ($request->filled('tanggal')) {
            $query->whereDate('tanggal', $request->tanggal);
        }
        if ($request->filled('id_kelas')) {
            $query->where('id_kelas', $request->id_kelas);
        }
        if ($request->filled('status')) {
            $query->where('status_approval', $request->status);
        }
        if ($request->filled('id_semester')) {
            $semester = \App\Models\Semester::find($request->id_semester);
            if ($semester) {
                $awal = \Carbon\Carbon::parse($semester->awal)->toDateString();
                $akhir = \Carbon\Carbon::parse($semester->akhir)->toDateString();
                $query->whereBetween('tanggal', [$awal, $akhir]);
            }
        }

        $perPage = $asWali ? $request->get('per_page', 100) : $request->get('per_page', 15);
        $paginated = $query->orderByDesc('tanggal')->paginate($perPage);

        // Transform items: tambahkan nama_mapel, nama_kelas, dan hitung presensi
        $items = $paginated->getCollection()->map(function ($j) {
            $absenStr = $j->absen ?? '';
            // Hitung dari field absen (format: "Nama (Sakit), Nama (Ijin)")
            $sakit  = substr_count(strtolower($absenStr), '(sakit)');
            $ijin   = substr_count(strtolower($absenStr), '(ijin)');
            $alpha  = substr_count(strtolower($absenStr), '(alpha)') + substr_count(strtolower($absenStr), '(alpa)');
            $tidakHadir = $sakit + $ijin + $alpha;
            $hadir  = max(0, ($j->jml_siswa ?? 0) - $tidakHadir);

            // Build full-URL fotos list
            $rawFotos = $j->fotos ?? [];
            if (!is_array($rawFotos)) {
                $rawFotos = [];
            }
            // Fallback ke foto_1/2/3 jika fotos kosong
            if (empty($rawFotos)) {
                foreach (['foto_1', 'foto_2', 'foto_3'] as $f) {
                    if (!empty($j->$f)) {
                        $rawFotos[] = $j->$f;
                    }
                }
            }
            $baseUrl = request()->getSchemeAndHttpHost();
            $fotosUrls = array_map(fn($f) => $baseUrl . '/storage/' . $f, $rawFotos);

            // Ekstrak hambatan & pemecahan dari JSON keterangan
            $keteranganData = null;
            if (!empty($j->keterangan)) {
                $decoded = json_decode($j->keterangan, true);
                if (is_array($decoded)) {
                    $keteranganData = $decoded;
                }
            }
            $hambatan  = $keteranganData['hambatan']  ?? null;
            $pemecahan = $keteranganData['pemecahan'] ?? null;

            return [
                'id'           => $j->id_kemajuan,
                'tanggal'      => $j->tanggal,
                'jam_ke'       => $j->jam_ke,
                'id_kelas'     => $j->id_kelas,
                'nama_kelas'   => $j->kelas?->nama_kelas,
                'id_mapel'     => $j->id_mapel,
                'nama_mapel'   => $j->mapel?->nama_mapel,
                'id_guru'      => $j->id_guru,
                'nama_guru'    => $j->guru?->nama_guru ?? null,
                'materi'       => $j->materi,
                'hambatan'     => $hambatan,
                'pemecahan'    => $pemecahan,
                'jml_siswa'    => $j->jml_siswa ?? 0,
                'absen'        => $absenStr,
                'status'       => $j->status_approval,
                'fotos'        => $fotosUrls,
                'presensi'     => [
                    'H' => $hadir,
                    'S' => $sakit,
                    'I' => $ijin,
                    'A' => $alpha,
                ],
            ];

        });

        $paginated->setCollection($items);

        return response()->json([
            'success' => true,
            'data'    => $paginated,
        ]);
    }

    /**
     * Simpan jurnal mengajar baru (atau perbarui jika sudah ada).
     */
    public function store(Request $request)
    {
        $request->validate([
            'tanggal'         => 'required|date',
            'id_kelas'        => 'required|integer|exists:kelas,id_kelas',
            'id_mapel'        => 'required|integer|exists:mapel,id_mapel',
            'jam_ke'          => 'required|string|max:20',
            'materi'          => 'required|string',
            'hambatan'        => 'nullable|string',
            'pemecahan'       => 'nullable|string',
            'status'          => 'nullable|string',
            'absen'           => 'nullable|string',
        ]);

        $idGuru = $request->user()->id_guru;
        if (!$idGuru) {
            $idGuru = Guru::where('no_id', $request->user()->no_id)->value('id_guru');
        }

        if (!$idGuru) {
            return response()->json([
                'success' => false,
                'message' => 'Data guru tidak ditemukan.',
            ], 403);
        }

        $jmlSiswa = UserSiswa::where('id_kelas', $request->id_kelas)
            ->where('status', 'aktif')
            ->count();

        $keterangan = json_encode([
            'hambatan'  => $request->hambatan,
            'pemecahan' => $request->pemecahan,
        ]);

        $statusApproval = 'pending';
        if ($request->status === 'draft') {
            $statusApproval = 'draft';
        }

        $absen = $request->input('absen');

        // Handle photo uploads (supports multipart files and base64 strings)
        $uploaded = \App\Helpers\FileUploadHelper::storeMultipleFiles($request, 'fotos', 'jurnal-foto');

        // Handle individual photo fields (foto.0, foto_0, foto_1, etc.)
        for ($i = 0; $i < 10; $i++) {
            foreach (["foto.$i", "foto_$i", "foto" . ($i + 1)] as $key) {
                if ($request->has($key) || $request->hasFile($key)) {
                    $path = \App\Helpers\FileUploadHelper::storeFile($request, $key, 'jurnal-foto');
                    if ($path && !in_array($path, $uploaded)) {
                        $uploaded[] = $path;
                    }
                }
            }
        }

        // Handle existing foto URLs from mobile
        $existingPaths = [];
        $existingFotos = $request->input('existing_fotos', []);
        if (!is_array($existingFotos)) {
            $existingFotos = [$existingFotos];
        }
        foreach ($existingFotos as $url) {
            if (is_string($url) && !empty($url)) {
                $path = preg_replace('#^https?://[^/]+/storage/#', '', $url);
                $path = preg_replace('#^/storage/#', '', $path);
                if (!empty($path)) {
                    $existingPaths[] = $path;
                }
            }
        }

        // Merge: existing first, then newly uploaded
        $allFotos = array_values(array_unique(array_merge($existingPaths, $uploaded)));

        // Upsert logic
        $jurnal = Kemajuan::where('tanggal', $request->tanggal)
            ->where('id_guru', $idGuru)
            ->where('id_kelas', $request->id_kelas)
            ->where('id_mapel', $request->id_mapel)
            ->first();

        if ($jurnal) {
            $updateData = [
                'jam_ke'          => $request->jam_ke,
                'materi'          => $request->materi,
                'jml_siswa'       => $jmlSiswa,
                'keterangan'      => $keterangan,
                'status_approval' => $statusApproval,
            ];
            if ($absen !== null) {
                $updateData['absen'] = $absen;
            }
            if (!empty($allFotos)) {
                $updateData['fotos'] = $allFotos;
                $updateData['foto_1'] = $allFotos[0] ?? null;
                $updateData['foto_2'] = $allFotos[1] ?? null;
                $updateData['foto_3'] = $allFotos[2] ?? null;
            }
            $jurnal->update($updateData);
        } else {
            $jurnal = Kemajuan::create([
                'tanggal'         => $request->tanggal,
                'id_kelas'        => $request->id_kelas,
                'id_mapel'        => $request->id_mapel,
                'id_guru'         => $idGuru,
                'jam_ke'          => $request->jam_ke,
                'materi'          => $request->materi,
                'jml_siswa'       => $jmlSiswa,
                'absen'           => $absen,
                'keterangan'      => $keterangan,
                'status_approval' => $statusApproval,
                'fotos'           => !empty($allFotos) ? $allFotos : null,
                'foto_1'          => $allFotos[0] ?? null,
                'foto_2'          => $allFotos[1] ?? null,
                'foto_3'          => $allFotos[2] ?? null,
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Jurnal mengajar berhasil disimpan.',
            'data'    => $jurnal->load(['kelas', 'mapel', 'guru']),
        ], 201);
    }

    /**
     * Detail jurnal mengajar.
     */
    public function show($id)
    {
        $jurnal = Kemajuan::with(['kelas', 'mapel', 'guru'])->findOrFail($id);

        return response()->json([
            'success' => true,
            'data'    => $jurnal,
        ]);
    }

    /**
     * Update jurnal mengajar.
     */
    public function update(Request $request, $id)
    {
        $jurnal = Kemajuan::findOrFail($id);

        $request->validate([
            'materi'   => 'sometimes|required|string',
            'hambatan' => 'nullable|string',
            'pemecahan' => 'nullable|string',
        ]);

        $updateData = [];
        if ($request->has('materi')) {
            $updateData['materi'] = $request->materi;
        }

        if ($request->has('hambatan') || $request->has('pemecahan')) {
            $keteranganData = json_decode($jurnal->keterangan, true);
            if (!is_array($keteranganData)) {
                $keteranganData = [
                    'hambatan'  => $jurnal->keterangan,
                    'pemecahan' => null
                ];
            }
            if ($request->has('hambatan')) {
                $keteranganData['hambatan'] = $request->hambatan;
            }
            if ($request->has('pemecahan')) {
                $keteranganData['pemecahan'] = $request->pemecahan;
            }
            $updateData['keterangan'] = json_encode($keteranganData);
        }

        $jurnal->update($updateData);

        return response()->json([
            'success' => true,
            'message' => 'Jurnal mengajar berhasil diperbarui.',
            'data'    => $jurnal->load(['kelas', 'mapel', 'guru']),
        ]);
    }

    /**
     * Setujui jurnal mengajar.
     */
    public function approve($id)
    {
        $jurnal = Kemajuan::findOrFail($id);
        $jurnal->update(['status_approval' => 'approved']);

        return response()->json([
            'success' => true,
            'message' => 'Jurnal mengajar berhasil disetujui.',
            'data'    => $jurnal,
        ]);
    }

    /**
     * Tolak jurnal mengajar.
     */
    public function reject(Request $request, $id)
    {
        $jurnal = Kemajuan::findOrFail($id);

        $jurnal->update([
            'status_approval'  => 'rejected',
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Jurnal mengajar berhasil ditolak.',
            'data'    => $jurnal,
        ]);
    }

    /**
     * Hapus jurnal mengajar.
     */
    public function destroy($id)
    {
        Kemajuan::findOrFail($id)->delete();

        return response()->json([
            'success' => true,
            'message' => 'Jurnal mengajar berhasil dihapus.',
        ]);
    }
}
