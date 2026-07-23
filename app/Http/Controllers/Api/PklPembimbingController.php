<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\PklPembimbing;
use Illuminate\Http\Request;

class PklPembimbingController extends Controller
{
    public function index(Request $request)
    {
        $query = PklPembimbing::with('guru');

        $perPage = $request->get('per_page', 15);
        $data = $query->paginate($perPage);

        return response()->json([
            'success' => true,
            'data' => $data,
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_guru' => 'required|integer|exists:guru,id_guru|unique:pkl_pembimbing,id_guru',
            'keterangan' => 'nullable|string',
        ]);

        $pembimbing = PklPembimbing::create($request->only('id_guru', 'keterangan'));

        return response()->json([
            'success' => true,
            'message' => 'Pembimbing PKL berhasil ditambahkan.',
            'data' => $pembimbing->load('guru'),
        ], 201);
    }

    public function show($id)
    {
        $pembimbing = PklPembimbing::with('guru')->findOrFail($id);

        return response()->json([
            'success' => true,
            'data' => $pembimbing,
        ]);
    }

    public function update(Request $request, $id)
    {
        $pembimbing = PklPembimbing::findOrFail($id);

        $request->validate([
            'id_guru' => 'sometimes|required|integer|exists:guru,id_guru|unique:pkl_pembimbing,id_guru,' . $id . ',id_pembimbing',
            'keterangan' => 'nullable|string',
        ]);

        $pembimbing->update($request->only('id_guru', 'keterangan'));

        return response()->json([
            'success' => true,
            'message' => 'Pembimbing PKL berhasil diperbarui.',
            'data' => $pembimbing->load('guru'),
        ]);
    }

    public function destroy($id)
    {
        $pembimbing = PklPembimbing::findOrFail($id);
        $pembimbing->delete();

        return response()->json([
            'success' => true,
            'message' => 'Pembimbing PKL berhasil dihapus.',
        ]);
    }
}
