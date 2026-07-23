<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\UserSiswa;
use App\Models\Kelas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class SiswaController extends Controller
{
    /**
     * Daftar semua siswa dengan filter opsional.
     */
    public function index(Request $request)
    {
        $query = UserSiswa::with(['kelas.jurusan']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('id_kelas')) {
            $query->where('id_kelas', $request->id_kelas);
        }

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('nama_siswa', 'like', '%' . $request->search . '%')
                  ->orWhere('nis', 'like', '%' . $request->search . '%');
            });
        }

        // Pagination
        $perPage = $request->get('per_page', 15);
        $data    = $query->orderBy('nama_siswa')->paginate($perPage);

        return response()->json([
            'success' => true,
            'data'    => $data,
        ]);
    }

    /**
     * Simpan siswa baru.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nis'           => 'required|integer|unique:user_siswa,nis',
            'password'      => 'required|string|min:4',
            'password_wali' => 'nullable|string|min:4',
            'id_kelas'      => 'required|integer|exists:kelas,id_kelas',
            'nama_siswa'    => 'required|string|max:100',
            'nisn'          => 'nullable|string|max:20',
            'nik'           => 'nullable|string|max:20',
            'jenkel'        => 'required|in:L,P',
            'tempat_lahir'  => 'nullable|string|max:30',
            'tgl_lahir'     => 'nullable|date',
            'kelengkapan'   => 'nullable|integer',
            'status'        => 'required|in:aktif,tidak',
        ]);

        $data                 = $request->only([
            'nis', 'nisn', 'nik', 'id_kelas', 'nama_siswa', 'jenkel',
            'tempat_lahir', 'tgl_lahir', 'kelengkapan', 'status',
        ]);
        $data['password']      = sha1($request->password);
        $data['password_wali'] = sha1($request->password_wali ?? $request->password);

        $siswa = UserSiswa::create($data);

        return response()->json([
            'success' => true,
            'message' => 'Data siswa berhasil ditambahkan.',
            'data'    => $siswa->load('kelas.jurusan'),
        ], 201);
    }

    /**
     * Detail siswa.
     */
    public function show($nis)
    {
        $siswa = UserSiswa::with(['kelas.jurusan', 'detail'])->findOrFail($nis);

        return response()->json([
            'success' => true,
            'data'    => $siswa,
        ]);
    }

    /**
     * Update data siswa.
     */
    public function update(Request $request, $nis)
    {
        $siswa = UserSiswa::findOrFail($nis);

        $request->validate([
            'id_kelas'     => 'sometimes|required|integer|exists:kelas,id_kelas',
            'nama_siswa'   => 'sometimes|required|string|max:100',
            'nisn'         => 'nullable|string|max:20',
            'nik'          => 'nullable|string|max:20',
            'jenkel'       => 'sometimes|required|in:L,P',
            'tempat_lahir' => 'nullable|string|max:30',
            'tgl_lahir'    => 'nullable|date',
            'kelengkapan'  => 'nullable|integer',
            'status'       => 'sometimes|required|in:aktif,tidak',
        ]);

        $data = $request->only([
            'id_kelas', 'nama_siswa', 'nisn', 'nik', 'jenkel',
            'tempat_lahir', 'tgl_lahir', 'kelengkapan', 'status',
        ]);

        $siswa->update($data);

        return response()->json([
            'success' => true,
            'message' => 'Data siswa berhasil diperbarui.',
            'data'    => $siswa->load('kelas.jurusan'),
        ]);
    }

    /**
     * Reset password siswa.
     */
    public function resetPassword(Request $request, $nis)
    {
        $siswa = UserSiswa::findOrFail($nis);

        $request->validate([
            'password'      => 'required|string|min:4',
            'password_wali' => 'nullable|string|min:4',
        ]);

        $siswa->update([
            'password'      => sha1($request->password),
            'password_wali' => sha1($request->password_wali ?? $request->password),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Password siswa berhasil direset.',
        ]);
    }

    /**
     * Hapus data siswa.
     */
    public function destroy($nis)
    {
        $siswa = UserSiswa::findOrFail($nis);
        $siswa->delete();

        return response()->json([
            'success' => true,
            'message' => 'Data siswa berhasil dihapus.',
        ]);
    }

    /**
     * AJAX / Mobile BK: Cari siswa aktif by nama / NIS (untuk autocomplete catat pelanggaran / reward)
     */
    public function searchSiswa(Request $request)
    {
        $q       = $request->get('q', '');
        $idKelas = $request->get('id_kelas');
        $limit   = $q === '' ? 1000 : (int) $request->get('limit', 50);

        $siswa = UserSiswa::with('kelas')
            ->where(function ($query) use ($q) {
                if ($q !== '') {
                    $query->where('nama_siswa', 'like', "%{$q}%")
                          ->orWhere('nis', 'like', "%{$q}%");
                }
            })
            ->when($idKelas, fn($q2) => $q2->where('id_kelas', $idKelas))
            ->where('status', 'aktif')
            ->orderBy('nama_siswa')
            ->limit($limit)
            ->get(['nis', 'nama_siswa', 'id_kelas']);

        return response()->json([
            'success' => true,
            'data' => $siswa->map(function ($s) {
                return [
                    'nis'        => $s->nis,
                    'nama_siswa' => $s->nama_siswa,
                    'nama_kelas' => $s->kelas ? $s->kelas->nama_kelas : '-',
                    'tingkat'    => $s->kelas ? $s->kelas->tingkat : null,
                    'id_kelas'   => $s->id_kelas,
                ];
            }),
        ]);
    }
}
