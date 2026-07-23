<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Guru;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class GuruController extends Controller
{
    /**
     * Daftar semua guru dengan filter opsional.
     */
    public function index(Request $request)
    {
        $query = Guru::query();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('guru_bk')) {
            $query->where('guru_bk', $request->guru_bk);
        }

        if ($request->filled('guru_ismuba')) {
            $query->where('guru_ismuba', $request->guru_ismuba);
        }

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('nama_guru', 'like', '%' . $request->search . '%')
                  ->orWhere('no_id', 'like', '%' . $request->search . '%')
                  ->orWhere('no_hp', 'like', '%' . $request->search . '%');
            });
        }

        $data = $query->orderBy('nama_guru')->get();

        return response()->json([
            'success' => true,
            'data'    => $data,
        ]);
    }

    /**
     * Simpan data guru baru.
     */
    public function store(Request $request)
    {
        $request->validate([
            'no_id'       => 'required|integer|unique:guru,no_id',
            'nama_guru'   => 'required|string|max:100',
            'no_hp'       => 'nullable|string|max:20',
            'guru_bk'     => 'required|in:ya,tidak',
            'guru_ismuba' => 'required|in:ya,tidak',
            'status'      => 'required|in:aktif,tidak',
            'password'    => 'required|string|min:4',
        ]);

        $guru = Guru::create([
            'no_id'       => $request->no_id,
            'nama_guru'   => $request->nama_guru,
            'no_hp'       => $request->no_hp,
            'guru_bk'     => $request->guru_bk,
            'guru_ismuba' => $request->guru_ismuba,
            'status'      => $request->status,
            'password'    => Hash::make($request->password),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Data guru berhasil ditambahkan.',
            'data'    => $guru,
        ], 201);
    }

    /**
     * Detail guru beserta kelas yang diampu.
     */
    public function show($id)
    {
        $guru = Guru::with(['kelas.jurusan'])->findOrFail($id);

        return response()->json([
            'success' => true,
            'data'    => $guru,
        ]);
    }

    /**
     * Update data guru.
     */
    public function update(Request $request, $id)
    {
        $guru = Guru::findOrFail($id);

        $request->validate([
            'no_id'       => 'sometimes|required|integer|unique:guru,no_id,' . $id . ',id_guru',
            'nama_guru'   => 'sometimes|required|string|max:100',
            'no_hp'       => 'nullable|string|max:20',
            'guru_bk'     => 'sometimes|required|in:ya,tidak',
            'guru_ismuba' => 'sometimes|required|in:ya,tidak',
            'status'      => 'sometimes|required|in:aktif,tidak',
        ]);

        $guru->update($request->only('no_id', 'nama_guru', 'no_hp', 'guru_bk', 'guru_ismuba', 'status'));

        return response()->json([
            'success' => true,
            'message' => 'Data guru berhasil diperbarui.',
            'data'    => $guru,
        ]);
    }

    /**
     * Reset password guru.
     */
    public function resetPassword(Request $request, $id)
    {
        $guru = Guru::findOrFail($id);

        $request->validate([
            'password' => 'required|string|min:4',
        ]);

        $guru->update(['password' => Hash::make($request->password)]);

        return response()->json([
            'success' => true,
            'message' => 'Password guru berhasil direset.',
        ]);
    }

    /**
     * Hapus data guru.
     */
    public function destroy($id)
    {
        $guru = Guru::findOrFail($id);

        // Cegah hapus jika masih menjadi wali kelas
        if ($guru->kelas()->count() > 0) {
            return response()->json([
                'success' => false,
                'message' => 'Guru masih menjadi wali kelas. Lepaskan terlebih dahulu.',
            ], 422);
        }

        $guru->delete();

        return response()->json([
            'success' => true,
            'message' => 'Data guru berhasil dihapus.',
        ]);
    }
}
