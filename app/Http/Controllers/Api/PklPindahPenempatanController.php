<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\PklPenempatan;
use App\Models\PklRiwayatPindah;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PklPindahPenempatanController extends Controller
{
    public function index(Request $request)
    {
        $query = PklRiwayatPindah::with([
            'penempatanLama.dudi',
            'penempatanLama.pembimbing.guru',
            'penempatanBaru.dudi',
            'penempatanBaru.pembimbing.guru',
            'siswa.kelas'
        ]);

        if ($request->filled('nis')) {
            $query->where('nis', $request->nis);
        }

        $perPage = $request->get('per_page', 15);
        $data = $query->orderByDesc('tanggal_pindah')->paginate($perPage);

        return response()->json([
            'success' => true,
            'data' => $data,
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_penempatan_lama' => 'required|integer|exists:pkl_penempatan,id_penempatan',
            'id_dudi_baru' => 'required|integer|exists:pkl_dudi,id_dudi',
            'id_pembimbing_baru' => 'required|integer|exists:pkl_pembimbing,id_pembimbing',
            'tanggal_pindah' => 'required|date',
            'alasan' => 'required|string',
        ]);

        DB::beginTransaction();
        try {
            $penempatanLama = PklPenempatan::findOrFail($request->id_penempatan_lama);

            // 1. Update status penempatan lama menjadi 'pindah'
            $penempatanLama->update([
                'status' => 'pindah',
                'tanggal_keluar' => $request->tanggal_pindah,
            ]);

            // 2. Buat penempatan baru
            $penempatanBaru = PklPenempatan::create([
                'id_gelombang' => $penempatanLama->id_gelombang,
                'id_dudi' => $request->id_dudi_baru,
                'nis' => $penempatanLama->nis,
                'id_pembimbing' => $request->id_pembimbing_baru,
                'tanggal_masuk' => $request->tanggal_pindah,
                'status' => 'aktif',
                'keterangan' => 'Pindahan dari DUDI ' . ($penempatanLama->dudi->nama_dudi ?? ''),
            ]);

            // 3. Catat ke riwayat pindah
            $riwayat = PklRiwayatPindah::create([
                'id_penempatan_lama' => $penempatanLama->id_penempatan,
                'id_penempatan_baru' => $penempatanBaru->id_penempatan,
                'nis' => $penempatanLama->nis,
                'tanggal_pindah' => $request->tanggal_pindah,
                'alasan' => $request->alasan,
            ]);

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Gagal memproses pindah penempatan: ' . $e->getMessage()
            ], 500);
        }

        return response()->json([
            'success' => true,
            'message' => 'Pindah penempatan PKL berhasil diproses.',
            'data' => $riwayat->load([
                'penempatanLama.dudi',
                'penempatanBaru.dudi',
                'siswa.kelas'
            ]),
        ], 201);
    }
}
