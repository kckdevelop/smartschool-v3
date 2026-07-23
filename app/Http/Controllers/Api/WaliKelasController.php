<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Kelas;
use App\Models\Guru;
use Illuminate\Http\Request;

class WaliKelasController extends Controller
{
    /**
     * Daftar semua kelas beserta wali kelas yang ditetapkan.
     */
    public function index(Request $request)
    {
        $query = Kelas::with(['jurusan', 'guru']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('tingkat')) {
            $query->where('tingkat', $request->tingkat);
        }

        $data = $query->orderBy('tingkat')->orderBy('rombel')->get()->map(function ($kelas) {
            return [
                'id_kelas'   => $kelas->id_kelas,
                'nama_kelas' => $kelas->tingkat . ' ' . $kelas->rombel,
                'tingkat'    => $kelas->tingkat,
                'rombel'     => $kelas->rombel,
                'status'     => $kelas->status,
                'jurusan'    => $kelas->jurusan,
                'wali_kelas' => $kelas->guru ? [
                    'id_guru'   => $kelas->guru->id_guru,
                    'no_id'     => $kelas->guru->no_id,
                    'nama_guru' => $kelas->guru->nama_guru,
                ] : null,
            ];
        });

        return response()->json([
            'success' => true,
            'data'    => $data,
        ]);
    }

    /**
     * Daftar guru yang tersedia untuk menjadi wali kelas.
     */
    public function guruTersedia()
    {
        $data = Guru::where('status', 'aktif')
                    ->orderBy('nama_guru')
                    ->get(['id_guru', 'no_id', 'nama_guru', 'guru_bk']);

        return response()->json([
            'success' => true,
            'data'    => $data,
        ]);
    }

    /**
     * Tetapkan atau ganti wali kelas.
     */
    public function tetapkan(Request $request, $id_kelas)
    {
        $kelas = Kelas::findOrFail($id_kelas);

        $request->validate([
            'id_guru' => 'required|integer|exists:guru,id_guru',
        ]);

        // Cek apakah guru sudah menjadi wali kelas di kelas lain
        $existing = Kelas::where('walikelas', $request->id_guru)
                         ->where('id_kelas', '!=', $id_kelas)
                         ->first();

        if ($existing) {
            return response()->json([
                'success' => false,
                'message' => "Guru sudah menjadi wali kelas {$existing->tingkat} {$existing->rombel}. Lepaskan terlebih dahulu.",
            ], 422);
        }

        $kelas->update(['walikelas' => $request->id_guru]);

        return response()->json([
            'success' => true,
            'message' => 'Wali kelas berhasil ditetapkan.',
            'data'    => $kelas->load(['jurusan', 'guru']),
        ]);
    }

    /**
     * Lepas wali kelas dari kelas.
     */
    public function lepas($id_kelas)
    {
        $kelas = Kelas::findOrFail($id_kelas);
        $kelas->update(['walikelas' => null]);

        return response()->json([
            'success' => true,
            'message' => 'Wali kelas berhasil dilepas dari kelas.',
        ]);
    }
}
