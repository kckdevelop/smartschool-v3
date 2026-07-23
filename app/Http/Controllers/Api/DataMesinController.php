<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\DataMesin;
use Illuminate\Http\Request;

class DataMesinController extends Controller
{
    /**
     * Daftar semua mesin finger.
     */
    public function index(Request $request)
    {
        $query = DataMesin::query();

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('nama_mesin', 'like', '%' . $request->search . '%')
                  ->orWhere('sn', 'like', '%' . $request->search . '%');
            });
        }

        $data = $query->orderBy('nama_mesin')->get();

        return response()->json([
            'success' => true,
            'data'    => $data,
        ]);
    }

    /**
     * Simpan data mesin baru.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama_mesin' => 'required|string|max:100',
            'sn'         => 'required|string|max:100|unique:data_mesin,sn',
            'password'   => 'nullable|string|max:50',
            'data'       => 'nullable|integer',
        ]);

        $mesin = DataMesin::create([
            'nama_mesin'  => $request->nama_mesin,
            'sn'          => $request->sn,
            'password'    => $request->password,
            'data'        => $request->data ?? 0,
            'last_update' => now(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Data mesin finger berhasil ditambahkan.',
            'data'    => $mesin,
        ], 201);
    }

    /**
     * Detail mesin finger.
     */
    public function show($id)
    {
        $mesin = DataMesin::findOrFail($id);

        return response()->json([
            'success' => true,
            'data'    => $mesin,
        ]);
    }

    /**
     * Update data mesin finger.
     */
    public function update(Request $request, $id)
    {
        $mesin = DataMesin::findOrFail($id);

        $request->validate([
            'nama_mesin' => 'sometimes|required|string|max:100',
            'sn'         => 'sometimes|required|string|max:100|unique:data_mesin,sn,' . $id . ',id_mesin',
            'password'   => 'nullable|string|max:50',
            'data'       => 'nullable|integer',
        ]);

        $data = $request->only('nama_mesin', 'sn', 'password', 'data');
        $data['last_update'] = now();

        $mesin->update($data);

        return response()->json([
            'success' => true,
            'message' => 'Data mesin finger berhasil diperbarui.',
            'data'    => $mesin,
        ]);
    }

    /**
     * Hapus data mesin finger.
     */
    public function destroy($id)
    {
        $mesin = DataMesin::findOrFail($id);
        $mesin->delete();

        return response()->json([
            'success' => true,
            'message' => 'Data mesin finger berhasil dihapus.',
        ]);
    }
}
