<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Karyawan;
use Illuminate\Http\Request;

class KaryawanProfilController extends Controller
{
    /**
     * Ambil profil karyawan yang sedang login.
     * Endpoint: GET /api/mobile/karyawan/profil
     */
    public function show(Request $request)
    {
        $user = $request->user();

        if (!($user instanceof Karyawan)) {
            return response()->json(['success' => false, 'message' => 'Akses ditolak.'], 403);
        }

        $karyawan = Karyawan::findOrFail($user->id_karyawan);

        return response()->json([
            'success' => true,
            'data' => [
                'id_karyawan'  => $karyawan->id_karyawan,
                'no_id'        => $karyawan->no_id,
                'nama_karyawan'=> $karyawan->nama_karyawan,
                'status'       => $karyawan->status,
                'foto_url'     => $karyawan->foto_url,
            ],
        ]);
    }

    /**
     * Upload foto profil karyawan.
     * Endpoint: POST /api/mobile/karyawan/foto
     */
    public function uploadFoto(Request $request)
    {
        $user = $request->user();

        if (!($user instanceof Karyawan)) {
            return response()->json(['success' => false, 'message' => 'Akses ditolak.'], 403);
        }

        $request->validate([
            'foto' => 'required|image|max:2048', // Maksimal 2MB
        ]);

        $karyawan = Karyawan::findOrFail($user->id_karyawan);

        if ($request->hasFile('foto')) {
            // Hapus foto lama jika ada
            if ($karyawan->foto) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($karyawan->foto);
            }

            // Simpan foto baru
            $path = $request->file('foto')->store('karyawan-foto', 'public');
            $karyawan->foto = $path;
            $karyawan->save();

            return response()->json([
                'success' => true,
                'message' => 'Foto profil berhasil diperbarui.',
                'foto_url' => $karyawan->foto_url,
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'File foto tidak ditemukan.',
        ], 400);
    }
}
