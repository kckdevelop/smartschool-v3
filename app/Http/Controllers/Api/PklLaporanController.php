<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\PklPenempatan;
use App\Models\PklDudi;
use App\Models\PklGelombang;
use Illuminate\Http\Request;

class PklLaporanController extends Controller
{
    public function index(Request $request)
    {
        $request->validate([
            'id_gelombang' => 'required|integer|exists:pkl_gelombang,id_gelombang',
        ]);

        $gelombang = PklGelombang::findOrFail($request->id_gelombang);

        // Rekap Status
        $rekapStatus = PklPenempatan::where('id_gelombang', $request->id_gelombang)
            ->selectRaw('status, count(*) as total')
            ->groupBy('status')
            ->pluck('total', 'status')
            ->toArray();

        // Rekap DUDI Terisi
        $rekapDudi = PklPenempatan::where('id_gelombang', $request->id_gelombang)
            ->with('dudi')
            ->selectRaw('id_dudi, count(*) as total')
            ->groupBy('id_dudi')
            ->get()
            ->map(function ($item) {
                return [
                    'nama_dudi' => $item->dudi->nama_dudi ?? 'DUDI Terhapus',
                    'jumlah_siswa' => $item->total,
                ];
            });

        // Detail Siswa Penempatan
        $detailPenempatan = PklPenempatan::where('id_gelombang', $request->id_gelombang)
            ->with(['siswa.kelas', 'dudi', 'pembimbing.guru'])
            ->get()
            ->map(function ($item) {
                return [
                    'nis' => $item->nis,
                    'nama_siswa' => $item->siswa->nama_siswa ?? '-',
                    'kelas' => $item->siswa->kelas->nama_kelas ?? '-',
                    'dudi' => $item->dudi->nama_dudi ?? '-',
                    'pembimbing' => $item->pembimbing->guru->nama_guru ?? '-',
                    'tanggal_masuk' => $item->tanggal_masuk,
                    'status' => $item->status,
                ];
            });

        return response()->json([
            'success' => true,
            'gelombang' => $gelombang->nama_gelombang,
            'data' => [
                'rekap_status' => [
                    'aktif' => $rekapStatus['aktif'] ?? 0,
                    'selesai' => $rekapStatus['selesai'] ?? 0,
                    'ditarik' => $rekapStatus['ditarik'] ?? 0,
                    'batal' => $rekapStatus['batal'] ?? 0,
                    'pindah' => $rekapStatus['pindah'] ?? 0,
                ],
                'rekap_dudi' => $rekapDudi,
                'detail_siswa' => $detailPenempatan,
            ]
        ]);
    }
}
