<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\PklPenempatan;
use Illuminate\Http\Request;

class PklPenempatanController extends Controller
{
    public function index(Request $request)
    {
        $query = PklPenempatan::with(['gelombang', 'dudi', 'siswa.kelas', 'pembimbing.guru']);

        if ($request->filled('id_gelombang')) {
            $query->where('id_gelombang', $request->id_gelombang);
        }

        if ($request->filled('id_dudi')) {
            $query->where('id_dudi', $request->id_dudi);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

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
            'id_gelombang' => 'required|integer|exists:pkl_gelombang,id_gelombang',
            'id_dudi' => 'required|integer|exists:pkl_dudi,id_dudi',
            'nis' => 'required|integer|exists:user_siswa,nis|unique:pkl_penempatan,nis,NULL,id_penempatan,status,aktif',
            'id_pembimbing' => 'required|integer|exists:pkl_pembimbing,id_pembimbing',
            'tanggal_masuk' => 'required|date',
            'tanggal_keluar' => 'nullable|date|after_or_equal:tanggal_masuk',
            'status' => 'required|in:aktif,selesai,ditarik,batal,pindah',
            'keterangan' => 'nullable|string',
        ]);

        $penempatan = PklPenempatan::create($request->only(
            'id_gelombang', 'id_dudi', 'nis', 'id_pembimbing',
            'tanggal_masuk', 'tanggal_keluar', 'status', 'keterangan'
        ));

        return response()->json([
            'success' => true,
            'message' => 'Penempatan PKL berhasil dicatat.',
            'data' => $penempatan->load(['gelombang', 'dudi', 'siswa.kelas', 'pembimbing.guru']),
        ], 201);
    }

    public function show($id)
    {
        $penempatan = PklPenempatan::with(['gelombang', 'dudi', 'siswa.kelas', 'pembimbing.guru'])->findOrFail($id);

        return response()->json([
            'success' => true,
            'data' => $penempatan,
        ]);
    }

    public function update(Request $request, $id)
    {
        $penempatan = PklPenempatan::findOrFail($id);

        $request->validate([
            'id_dudi' => 'sometimes|required|integer|exists:pkl_dudi,id_dudi',
            'id_pembimbing' => 'sometimes|required|integer|exists:pkl_pembimbing,id_pembimbing',
            'tanggal_masuk' => 'sometimes|required|date',
            'tanggal_keluar' => 'nullable|date|after_or_equal:tanggal_masuk',
            'status' => 'sometimes|required|in:aktif,selesai,ditarik,batal,pindah',
            'keterangan' => 'nullable|string',
        ]);

        $penempatan->update($request->only('id_dudi', 'id_pembimbing', 'tanggal_masuk', 'tanggal_keluar', 'status', 'keterangan'));

        return response()->json([
            'success' => true,
            'message' => 'Penempatan PKL berhasil diperbarui.',
            'data' => $penempatan->load(['gelombang', 'dudi', 'siswa.kelas', 'pembimbing.guru']),
        ]);
    }

    public function destroy($id)
    {
        $penempatan = PklPenempatan::findOrFail($id);
        $penempatan->delete();

        return response()->json([
            'success' => true,
            'message' => 'Penempatan PKL berhasil dihapus.',
        ]);
    }
}
