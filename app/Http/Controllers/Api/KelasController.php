<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Kelas;
use App\Models\Guru;
use Illuminate\Http\Request;

class KelasController extends Controller
{
    /**
     * Daftar semua kelas.
     */
    public function index(Request $request)
    {
        $query = Kelas::with(['jurusan', 'guru']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('tingkat')) {
            $query->where('tingkat', $request->tingkat);
        }

        if ($request->filled('id_jurusan')) {
            $query->where('id_jurusan', $request->id_jurusan);
        }

        if ($request->filled('search')) {
            $query->where('rombel', 'like', '%' . $request->search . '%');
        }

        $data = $query->orderBy('tingkat')->orderBy('rombel')->get();

        return response()->json([
            'success' => true,
            'data'    => $data,
        ]);
    }

    /**
     * Simpan kelas baru.
     */
    public function store(Request $request)
    {
        $request->validate([
            'tahun_masuk' => 'required|string|max:10',
            'tingkat'     => 'required|integer|in:10,11,12',
            'id_jurusan'  => 'required|integer|exists:jurusan,id_jurusan',
            'rombel'      => 'required|string|max:50',
            'walikelas'   => 'nullable|integer|exists:guru,id_guru',
            'status'      => 'required|in:aktif,tidak',
        ]);

        $kelas = Kelas::create($request->only(
            'tahun_masuk', 'tingkat', 'id_jurusan', 'rombel', 'walikelas', 'status'
        ));

        return response()->json([
            'success' => true,
            'message' => 'Kelas berhasil ditambahkan.',
            'data'    => $kelas->load(['jurusan', 'guru']),
        ], 201);
    }

    /**
     * Detail kelas beserta siswa.
     */
    public function show($id)
    {
        $kelas = Kelas::with(['jurusan', 'guru', 'siswa'])->findOrFail($id);

        return response()->json([
            'success' => true,
            'data'    => $kelas,
        ]);
    }

    /**
     * Update kelas.
     */
    public function update(Request $request, $id)
    {
        $kelas = Kelas::findOrFail($id);

        $request->validate([
            'tahun_masuk' => 'sometimes|required|string|max:10',
            'tingkat'     => 'sometimes|required|integer|in:10,11,12',
            'id_jurusan'  => 'sometimes|required|integer|exists:jurusan,id_jurusan',
            'rombel'      => 'sometimes|required|string|max:50',
            'walikelas'   => 'nullable|integer|exists:guru,id_guru',
            'status'      => 'sometimes|required|in:aktif,tidak',
        ]);

        $kelas->update($request->only(
            'tahun_masuk', 'tingkat', 'id_jurusan', 'rombel', 'walikelas', 'status'
        ));

        return response()->json([
            'success' => true,
            'message' => 'Kelas berhasil diperbarui.',
            'data'    => $kelas->load(['jurusan', 'guru']),
        ]);
    }

    /**
     * Hapus kelas.
     */
    public function destroy($id)
    {
        $kelas = Kelas::findOrFail($id);

        if ($kelas->siswa()->count() > 0) {
            return response()->json([
                'success' => false,
                'message' => 'Kelas masih memiliki data siswa. Pindahkan atau hapus siswa terlebih dahulu.',
            ], 422);
        }

        $kelas->delete();

        return response()->json([
            'success' => true,
            'message' => 'Kelas berhasil dihapus.',
        ]);
    }
}
