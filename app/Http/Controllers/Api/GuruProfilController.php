<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Guru;
use Illuminate\Http\Request;

class GuruProfilController extends Controller
{
    /**
     * Ambil profil guru yang sedang login.
     * Endpoint: GET /api/mobile/guru/profil
     */
    public function show(Request $request)
    {
        $user = $request->user();

        if (!($user instanceof Guru)) {
            return response()->json(['success' => false, 'message' => 'Akses ditolak.'], 403);
        }

        $guru = Guru::findOrFail($user->id_guru);

        return response()->json([
            'success' => true,
            'data' => [
                'id_guru'     => $guru->id_guru,
                'no_id'       => $guru->no_id,
                'nama_guru'   => $guru->nama_guru,
                'no_hp'       => $guru->no_hp,
                'guru_bk'     => $guru->guru_bk,
                'guru_ismuba' => $guru->guru_ismuba,
                'status'      => $guru->status,
                'foto_url'    => $guru->foto_url,
            ],
        ]);
    }

    /**
     * Update profil guru (misal no_hp).
     * Endpoint: POST /api/mobile/guru/profil
     */
    public function update(Request $request)
    {
        $user = $request->user();

        if (!($user instanceof Guru)) {
            return response()->json(['success' => false, 'message' => 'Akses ditolak.'], 403);
        }

        $request->validate([
            'no_hp' => 'nullable|string|max:20',
        ]);

        $guru = Guru::findOrFail($user->id_guru);
        $guru->no_hp = $request->no_hp;
        $guru->save();

        return response()->json([
            'success' => true,
            'message' => 'Nomor HP berhasil diperbarui.',
            'data' => [
                'id_guru'     => $guru->id_guru,
                'no_id'       => $guru->no_id,
                'nama_guru'   => $guru->nama_guru,
                'no_hp'       => $guru->no_hp,
                'guru_bk'     => $guru->guru_bk,
                'guru_ismuba' => $guru->guru_ismuba,
                'status'      => $guru->status,
                'foto_url'    => $guru->foto_url,
            ],
        ]);
    }

    /**
     * Upload foto profil guru.
     * Endpoint: POST /api/mobile/guru/foto
     */
    public function uploadFoto(Request $request)
    {
        $user = $request->user();

        if (!($user instanceof Guru)) {
            return response()->json(['success' => false, 'message' => 'Akses ditolak.'], 403);
        }

        $request->validate([
            'foto' => 'required|image|max:2048', // Maksimal 2MB
        ]);

        $guru = Guru::findOrFail($user->id_guru);

        if ($request->hasFile('foto')) {
            // Hapus foto lama jika ada
            if ($guru->foto) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($guru->foto);
            }

            // Simpan foto baru
            $path = $request->file('foto')->store('guru-foto', 'public');
            $guru->foto = $path;
            $guru->save();

            return response()->json([
                'success' => true,
                'message' => 'Foto profil berhasil diperbarui.',
                'foto_url' => $guru->foto_url,
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'File foto tidak ditemukan.',
        ], 400);
    }
}
