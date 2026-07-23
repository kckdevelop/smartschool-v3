<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\JenisCheckup;
use Illuminate\Http\Request;

class UksJenisCheckupController extends Controller
{
    public function index(Request $request)
    {
        $data = JenisCheckup::orderBy('jenis_checkup')->get();

        return response()->json([
            'success' => true,
            'data' => $data,
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'jenis_checkup' => 'required|string|max:100|unique:jenis_checkup,jenis_checkup',
            'keterangan' => 'nullable|string',
        ]);

        $jenis = JenisCheckup::create($request->only('jenis_checkup', 'keterangan'));

        return response()->json([
            'success' => true,
            'message' => 'Jenis checkup berhasil ditambahkan.',
            'data' => $jenis,
        ], 201);
    }

    public function update(Request $request, $id)
    {
        $jenis = JenisCheckup::findOrFail($id);

        $request->validate([
            'jenis_checkup' => 'sometimes|required|string|max:100|unique:jenis_checkup,jenis_checkup,' . $id . ',id_jenis',
            'keterangan' => 'nullable|string',
        ]);

        $jenis->update($request->only('jenis_checkup', 'keterangan'));

        return response()->json([
            'success' => true,
            'message' => 'Jenis checkup berhasil diperbarui.',
            'data' => $jenis,
        ]);
    }

    public function destroy($id)
    {
        $jenis = JenisCheckup::findOrFail($id);
        $jenis->delete();

        return response()->json([
            'success' => true,
            'message' => 'Jenis checkup berhasil dihapus.',
        ]);
    }
}
