<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\PklDudi;
use Illuminate\Http\Request;

class PklDudiController extends Controller
{
    public function index(Request $request)
    {
        $query = PklDudi::query();

        if ($request->filled('search')) {
            $query->where('nama_dudi', 'like', '%' . $request->search . '%')
                  ->orWhere('pimpinan', 'like', '%' . $request->search . '%');
        }

        $perPage = $request->get('per_page', 15);
        $data = $query->orderBy('nama_dudi')->paginate($perPage);

        return response()->json([
            'success' => true,
            'data' => $data,
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_dudi' => 'required|string|max:150',
            'alamat' => 'required|string',
            'no_telp' => 'nullable|string|max:20',
            'pimpinan' => 'nullable|string|max:100',
            'kuota' => 'required|integer|min:1',
            'keterangan' => 'nullable|string',
        ]);

        $dudi = PklDudi::create($request->only('nama_dudi', 'alamat', 'no_telp', 'pimpinan', 'kuota', 'keterangan'));

        return response()->json([
            'success' => true,
            'message' => 'Data DUDI berhasil ditambahkan.',
            'data' => $dudi,
        ], 201);
    }

    public function show($id)
    {
        $dudi = PklDudi::findOrFail($id);

        return response()->json([
            'success' => true,
            'data' => $dudi,
        ]);
    }

    public function update(Request $request, $id)
    {
        $dudi = PklDudi::findOrFail($id);

        $request->validate([
            'nama_dudi' => 'sometimes|required|string|max:150',
            'alamat' => 'sometimes|required|string',
            'no_telp' => 'nullable|string|max:20',
            'pimpinan' => 'nullable|string|max:100',
            'kuota' => 'sometimes|required|integer|min:1',
            'keterangan' => 'nullable|string',
        ]);

        $dudi->update($request->only('nama_dudi', 'alamat', 'no_telp', 'pimpinan', 'kuota', 'keterangan'));

        return response()->json([
            'success' => true,
            'message' => 'Data DUDI berhasil diperbarui.',
            'data' => $dudi,
        ]);
    }

    public function destroy($id)
    {
        $dudi = PklDudi::findOrFail($id);
        $dudi->delete();

        return response()->json([
            'success' => true,
            'message' => 'Data DUDI berhasil dihapus.',
        ]);
    }
}
