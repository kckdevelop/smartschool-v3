<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Karyawan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class KaryawanController extends Controller
{
    /**
     * Daftar karyawan dengan filter opsional.
     *
     * @queryParam status string Filter status: aktif|tidak. Example: aktif
     * @queryParam search string Cari berdasarkan nama atau no_id. Example: Budi
     * @queryParam per_page int Jumlah per halaman (default 15). Example: 20
     */
    public function index(Request $request)
    {
        $query = Karyawan::query();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('nama_karyawan', 'like', '%' . $request->search . '%')
                  ->orWhere('no_id', 'like', '%' . $request->search . '%');
            });
        }

        $perPage = $request->get('per_page', 15);
        $data    = $query->orderBy('nama_karyawan')->paginate($perPage);

        return response()->json([
            'success' => true,
            'data'    => $data,
        ]);
    }

    /**
     * Simpan karyawan baru.
     */
    public function store(Request $request)
    {
        $request->validate([
            'no_id'         => 'required|integer|unique:karyawan,no_id',
            'nama_karyawan' => 'required|string|max:100',
            'status'        => 'required|in:aktif,tidak',
            'password'      => 'required|string|min:4',
        ]);

        $karyawan = Karyawan::create([
            'no_id'         => $request->no_id,
            'nama_karyawan' => $request->nama_karyawan,
            'status'        => $request->status,
            'password'      => Hash::make($request->password),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Data karyawan berhasil ditambahkan.',
            'data'    => $karyawan,
        ], 201);
    }

    /**
     * Detail karyawan.
     */
    public function show($id)
    {
        $karyawan = Karyawan::findOrFail($id);

        return response()->json([
            'success' => true,
            'data'    => $karyawan,
        ]);
    }

    /**
     * Update data karyawan.
     */
    public function update(Request $request, $id)
    {
        $karyawan = Karyawan::findOrFail($id);

        $request->validate([
            'no_id'         => 'sometimes|required|integer|unique:karyawan,no_id,' . $id . ',id_karyawan',
            'nama_karyawan' => 'sometimes|required|string|max:100',
            'status'        => 'sometimes|required|in:aktif,tidak',
        ]);

        $karyawan->update($request->only('no_id', 'nama_karyawan', 'status'));

        return response()->json([
            'success' => true,
            'message' => 'Data karyawan berhasil diperbarui.',
            'data'    => $karyawan,
        ]);
    }

    /**
     * Reset password karyawan.
     */
    public function resetPassword(Request $request, $id)
    {
        $karyawan = Karyawan::findOrFail($id);

        $request->validate([
            'password' => 'required|string|min:4',
        ]);

        $karyawan->update(['password' => Hash::make($request->password)]);

        return response()->json([
            'success' => true,
            'message' => 'Password karyawan berhasil direset.',
        ]);
    }

    /**
     * Hapus data karyawan.
     */
    public function destroy($id)
    {
        $karyawan = Karyawan::findOrFail($id);
        $karyawan->delete();

        return response()->json([
            'success' => true,
            'message' => 'Data karyawan berhasil dihapus.',
        ]);
    }
}
