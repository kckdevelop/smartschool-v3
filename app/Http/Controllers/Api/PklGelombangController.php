<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\PklGelombang;
use Illuminate\Http\Request;

class PklGelombangController extends Controller
{
    public function index(Request $request)
    {
        $query = PklGelombang::query();

        $perPage = $request->get('per_page', 15);
        $data = $query->orderBy('nama_gelombang')->paginate($perPage);

        return response()->json([
            'success' => true,
            'data' => $data,
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_gelombang' => 'required|string|max:100',
            'tgl_mulai' => 'required|date',
            'tgl_selesai' => 'required|date|after:tgl_mulai',
            'keterangan' => 'nullable|string',
        ]);

        $gelombang = PklGelombang::create($request->only('nama_gelombang', 'tgl_mulai', 'tgl_selesai', 'keterangan'));

        return response()->json([
            'success' => true,
            'message' => 'Gelombang PKL berhasil ditambahkan.',
            'data' => $gelombang,
        ], 201);
    }

    public function show($id)
    {
        $gelombang = PklGelombang::findOrFail($id);

        return response()->json([
            'success' => true,
            'data' => $gelombang,
        ]);
    }

    public function update(Request $request, $id)
    {
        $gelombang = PklGelombang::findOrFail($id);

        $request->validate([
            'nama_gelombang' => 'sometimes|required|string|max:100',
            'tgl_mulai' => 'sometimes|required|date',
            'tgl_selesai' => 'sometimes|required|date|after:tgl_mulai',
            'keterangan' => 'nullable|string',
        ]);

        $gelombang->update($request->only('nama_gelombang', 'tgl_mulai', 'tgl_selesai', 'keterangan'));

        return response()->json([
            'success' => true,
            'message' => 'Gelombang PKL berhasil diperbarui.',
            'data' => $gelombang,
        ]);
    }

    public function destroy($id)
    {
        $gelombang = PklGelombang::findOrFail($id);
        $gelombang->delete();

        return response()->json([
            'success' => true,
            'message' => 'Gelombang PKL berhasil dihapus.',
        ]);
    }
}
