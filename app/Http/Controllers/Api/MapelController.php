<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Mapel;
use Illuminate\Http\Request;

class MapelController extends Controller
{
    /**
     * Daftar semua mata pelajaran.
     */
    public function index(Request $request)
    {
        $query = Mapel::query();

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('nama_mapel', 'like', '%' . $request->search . '%')
                  ->orWhere('kode_mapel', 'like', '%' . $request->search . '%');
            });
        }

        $data = $query->orderBy('nama_mapel')->get();

        return response()->json([
            'success' => true,
            'data'    => $data,
        ]);
    }

    /**
     * Simpan mata pelajaran baru.
     */
    public function store(Request $request)
    {
        $request->validate([
            'kode_mapel' => 'required|string|max:20|unique:mapel,kode_mapel',
            'nama_mapel' => 'required|string|max:100|unique:mapel,nama_mapel',
        ]);

        $mapel = Mapel::create($request->only('kode_mapel', 'nama_mapel'));

        return response()->json([
            'success' => true,
            'message' => 'Mata pelajaran berhasil ditambahkan.',
            'data'    => $mapel,
        ], 201);
    }

    /**
     * Detail mata pelajaran.
     */
    public function show($id)
    {
        $mapel = Mapel::findOrFail($id);

        return response()->json([
            'success' => true,
            'data'    => $mapel,
        ]);
    }

    /**
     * Update mata pelajaran.
     */
    public function update(Request $request, $id)
    {
        $mapel = Mapel::findOrFail($id);

        $request->validate([
            'kode_mapel' => 'sometimes|required|string|max:20|unique:mapel,kode_mapel,' . $id . ',id_mapel',
            'nama_mapel' => 'sometimes|required|string|max:100|unique:mapel,nama_mapel,' . $id . ',id_mapel',
        ]);

        $mapel->update($request->only('kode_mapel', 'nama_mapel'));

        return response()->json([
            'success' => true,
            'message' => 'Mata pelajaran berhasil diperbarui.',
            'data'    => $mapel,
        ]);
    }

    /**
     * Hapus mata pelajaran.
     */
    public function destroy($id)
    {
        $mapel = Mapel::findOrFail($id);

        if ($mapel->kemajuan()->count() > 0) {
            return response()->json([
                'success' => false,
                'message' => 'Mata pelajaran masih memiliki data kemajuan belajar. Hapus terlebih dahulu.',
            ], 422);
        }

        $mapel->delete();

        return response()->json([
            'success' => true,
            'message' => 'Mata pelajaran berhasil dihapus.',
        ]);
    }
}
