<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\GayaBelajar;
use Illuminate\Http\Request;

class GayaBelajarController extends Controller
{
    public function index(Request $request)
    {
        // Jika id_kelas dikirimkan, kembalikan daftar siswa per kelas + ringkasan jumlah per kategori
        if ($request->filled('id_kelas')) {
            $idKelas = $request->id_kelas;
            $kelas   = \App\Models\Kelas::find($idKelas);

            $siswas = \App\Models\UserSiswa::where('id_kelas', $idKelas)
                ->where('status', 'aktif')
                ->orderBy('nama_siswa')
                ->get();

            $gayaRecords = GayaBelajar::whereIn('nis', $siswas->pluck('nis'))
                ->get()
                ->keyBy('nis');

            $items = $siswas->map(function ($s) use ($gayaRecords) {
                $g = $gayaRecords->get($s->nis);
                return [
                    'id_gaya_belajar' => $g?->id_gaya_belajar,
                    'nis'             => $s->nis,
                    'nama_siswa'      => $s->nama_siswa,
                    'gaya_belajar'    => $g?->gaya_belajar ?? null,
                    'skor_visual'     => $g?->skor_visual ?? 0,
                    'skor_auditori'   => $g?->skor_auditori ?? 0,
                    'skor_kinestetik' => $g?->skor_kinestetik ?? 0,
                    'minat'           => $g?->minat ?? null,
                    'catatan'         => $g?->catatan ?? null,
                    'created_at'      => $g?->created_at?->toIso8601String(),
                ];
            });

            $summary = [
                'total_siswa'      => $items->count(),
                'total_visual'     => $items->where('gaya_belajar', 'visual')->count(),
                'total_auditori'   => $items->where('gaya_belajar', 'auditori')->count(),
                'total_kinestetik' => $items->where('gaya_belajar', 'kinestetik')->count(),
                'total_belum_tes'  => $items->filter(fn($i) => empty($i['gaya_belajar']))->count(),
            ];

            return response()->json([
                'success' => true,
                'kelas'   => $kelas ? [
                    'id_kelas'   => $kelas->id_kelas,
                    'nama_kelas' => $kelas->nama_kelas ?? ($kelas->tingkat . ' ' . $kelas->rombel),
                ] : null,
                'summary' => $summary,
                'data'    => $items->values(),
            ]);
        }

        $query = GayaBelajar::with('siswa.kelas');

        if ($request->filled('nis')) {
            $query->where('nis', $request->nis);
        }

        $perPage = $request->get('per_page', 100);
        $data = $query->orderByDesc('id_gaya_belajar')->paginate($perPage);

        return response()->json([
            'success' => true,
            'data'    => $data,
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'nis' => 'required|integer|exists:user_siswa,nis',
            'gaya_belajar' => 'required|string|max:100',
            'keterangan' => 'nullable|string',
        ]);

        $gaya = GayaBelajar::updateOrCreate(
            ['nis' => $request->nis],
            ['gaya_belajar' => $request->gaya_belajar, 'keterangan' => $request->keterangan]
        );

        return response()->json([
            'success' => true,
            'message' => 'Gaya belajar siswa berhasil disimpan.',
            'data' => $gaya->load('siswa.kelas'),
        ], 201);
    }

    public function show($id)
    {
        $gaya = GayaBelajar::with('siswa.kelas')->findOrFail($id);

        return response()->json([
            'success' => true,
            'data' => $gaya,
        ]);
    }

    public function destroy($id)
    {
        GayaBelajar::findOrFail($id)->delete();

        return response()->json([
            'success' => true,
            'message' => 'Gaya belajar siswa berhasil dihapus.',
        ]);
    }
}
