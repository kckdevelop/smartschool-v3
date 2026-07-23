<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\JenisPelanggaran;
use Illuminate\Http\Request;

class KategoriPelanggaranController extends Controller
{
    public function index(Request $request)
    {
        $query = JenisPelanggaran::query();

        if ($request->filled('search')) {
            $query->where('jenis_pelanggaran', 'like', '%' . $request->search . '%');
        }

        $data = $query->orderBy('jenis_pelanggaran')->get();

        return response()->json([
            'success' => true,
            'data' => $data,
        ]);
    }

    /**
     * AJAX / Mobile: search kategori pelanggaran by keyword
     */
    public function search(Request $request)
    {
        $q = $request->get('q', '');
        $results = JenisPelanggaran::when($q, function ($query) use ($q) {
                $query->where('jenis_pelanggaran', 'like', "%{$q}%");
            })
            ->orderBy('jenis_pelanggaran')
            ->limit(20)
            ->get(['id_jenis_pelanggaran', 'jenis_pelanggaran', 'poin']);

        return response()->json(['success' => true, 'data' => $results]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'jenis_pelanggaran' => 'required|string|max:100|unique:jenis_pelanggaran,jenis_pelanggaran',
            'poin' => 'required|integer|min:1',
        ]);

        $kategori = JenisPelanggaran::create($request->only('jenis_pelanggaran', 'poin'));

        return response()->json([
            'success' => true,
            'message' => 'Kategori pelanggaran berhasil ditambahkan.',
            'data' => $kategori,
        ], 201);
    }

    public function show($id)
    {
        $kategori = JenisPelanggaran::findOrFail($id);

        return response()->json([
            'success' => true,
            'data' => $kategori,
        ]);
    }

    public function update(Request $request, $id)
    {
        $kategori = JenisPelanggaran::findOrFail($id);

        $request->validate([
            'jenis_pelanggaran' => 'sometimes|required|string|max:100|unique:jenis_pelanggaran,jenis_pelanggaran,' . $id . ',id_jenis_pelanggaran',
            'poin' => 'sometimes|required|integer|min:1',
        ]);

        $kategori->update($request->only('jenis_pelanggaran', 'poin'));

        return response()->json([
            'success' => true,
            'message' => 'Kategori pelanggaran berhasil diperbarui.',
            'data' => $kategori,
        ]);
    }

    public function destroy($id)
    {
        $kategori = JenisPelanggaran::findOrFail($id);
        $kategori->delete();

        return response()->json([
            'success' => true,
            'message' => 'Kategori pelanggaran berhasil dihapus.',
        ]);
    }
}
