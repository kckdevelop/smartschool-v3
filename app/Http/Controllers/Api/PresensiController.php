<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Presensi;
use Illuminate\Http\Request;

class PresensiController extends Controller
{
    public function index(Request $request)
    {
        $query = Presensi::query();

        if ($request->filled('nis')) {
            $query->where('nis', $request->nis);
        }

        if ($request->filled('tanggal')) {
            $query->where('tanggal', $request->tanggal);
        }

        $perPage = $request->get('per_page', 15);
        $data = $query->orderByDesc('tanggal')->paginate($perPage);

        return response()->json([
            'success' => true,
            'data' => $data,
        ]);
    }

    public function byNis($nis)
    {
        $query = Presensi::where('nis', $nis)
            ->orderByDesc('tanggal');

        $perPage = request()->get('per_page', 15);
        $data = $query->paginate($perPage);

        return response()->json([
            'success' => true,
            'data' => $data,
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'nis' => 'required|integer|exists:user_siswa,nis',
            'tanggal' => 'required|date_format:Y-m-d',
            'jam' => 'nullable|string|max:10',
            'status' => 'required|string|max:15',
            'keterangan' => 'nullable|string|max:25',
        ]);

        $presensi = Presensi::create($request->only('nis', 'tanggal', 'jam', 'status', 'keterangan'));

        return response()->json([
            'success' => true,
            'message' => 'Data presensi berhasil ditambahkan.',
            'data' => $presensi,
        ], 201);
    }

    public function show($id)
    {
        $presensi = Presensi::findOrFail($id);

        return response()->json([
            'success' => true,
            'data' => $presensi,
        ]);
    }

    public function update(Request $request, $id)
    {
        $presensi = Presensi::findOrFail($id);

        $request->validate([
            'jam' => 'nullable|string|max:10',
            'status' => 'sometimes|required|string|max:15',
            'keterangan' => 'nullable|string|max:25',
        ]);

        $presensi->update($request->only('jam', 'status', 'keterangan'));

        return response()->json([
            'success' => true,
            'message' => 'Data presensi berhasil diperbarui.',
            'data' => $presensi,
        ]);
    }

    public function destroy($id)
    {
        Presensi::findOrFail($id)->delete();

        return response()->json([
            'success' => true,
            'message' => 'Data presensi berhasil dihapus.',
        ]);
    }
}
