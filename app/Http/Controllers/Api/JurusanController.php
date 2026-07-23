<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Jurusan;
use Illuminate\Http\Request;

class JurusanController extends Controller
{
    /**
     * Daftar semua jurusan.
     */
    public function index(Request $request)
    {
        $query = Jurusan::query();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('nama_jurusan', 'like', '%' . $request->search . '%')
                  ->orWhere('kode_jurusan', 'like', '%' . $request->search . '%');
            });
        }

        $data = $query->orderBy('nama_jurusan')->get();

        return response()->json([
            'success' => true,
            'data'    => $data,
        ]);
    }

    /**
     * Simpan jurusan baru.
     */
    public function store(Request $request)
    {
        $request->validate([
            'kode_jurusan' => 'required|string|max:20|unique:jurusan,kode_jurusan',
            'nama_jurusan' => 'required|string|max:100|unique:jurusan,nama_jurusan',
            'status'       => 'required|in:aktif,tidak',
        ]);

        $jurusan = Jurusan::create($request->only('kode_jurusan', 'nama_jurusan', 'status'));

        return response()->json([
            'success' => true,
            'message' => 'Jurusan berhasil ditambahkan.',
            'data'    => $jurusan,
        ], 201);
    }

    /**
     * Detail jurusan.
     */
    public function show($id)
    {
        $jurusan = Jurusan::with('kelas')->findOrFail($id);

        return response()->json([
            'success' => true,
            'data'    => $jurusan,
        ]);
    }

    /**
     * Update jurusan.
     */
    public function update(Request $request, $id)
    {
        $jurusan = Jurusan::findOrFail($id);

        $request->validate([
            'kode_jurusan' => 'sometimes|required|string|max:20|unique:jurusan,kode_jurusan,' . $id . ',id_jurusan',
            'nama_jurusan' => 'sometimes|required|string|max:100|unique:jurusan,nama_jurusan,' . $id . ',id_jurusan',
            'status'       => 'sometimes|required|in:aktif,tidak',
        ]);

        $jurusan->update($request->only('kode_jurusan', 'nama_jurusan', 'status'));

        return response()->json([
            'success' => true,
            'message' => 'Jurusan berhasil diperbarui.',
            'data'    => $jurusan,
        ]);
    }

    /**
     * Hapus jurusan.
     */
    public function destroy($id)
    {
        $jurusan = Jurusan::findOrFail($id);

        if ($jurusan->kelas()->count() > 0) {
            return response()->json([
                'success' => false,
                'message' => 'Jurusan masih memiliki data kelas. Hapus kelas terlebih dahulu.',
            ], 422);
        }

        $jurusan->delete();

        return response()->json([
            'success' => true,
            'message' => 'Jurusan berhasil dihapus.',
        ]);
    }
}
