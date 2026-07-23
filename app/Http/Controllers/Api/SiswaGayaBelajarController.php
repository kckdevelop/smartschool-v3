<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\GayaBelajar;
use App\Models\UserSiswa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SiswaGayaBelajarController extends Controller
{
    /**
     * GET /api/mobile/siswa/gaya-belajar
     * Cek apakah siswa sudah pernah mengerjakan tes gaya belajar.
     * Jika sudah, kembalikan data hasilnya.
     */
    public function show(Request $request)
    {
        $user = $request->user();

        // Ambil NIS dari user yang sedang login
        $nis = $user->nis ?? $user->username ?? null;

        if (!$nis) {
            return response()->json([
                'success'  => false,
                'message'  => 'NIS tidak ditemukan pada akun Anda.',
                'data'     => null,
                'sudah_tes' => false,
            ], 422);
        }

        $gaya = GayaBelajar::with('siswa')
            ->where('nis', $nis)
            ->latest()
            ->first();

        if (!$gaya) {
            return response()->json([
                'success'   => true,
                'sudah_tes' => false,
                'data'      => null,
                'message'   => 'Siswa belum pernah mengerjakan tes gaya belajar.',
            ]);
        }

        return response()->json([
            'success'   => true,
            'sudah_tes' => true,
            'data'      => [
                'id_gaya_belajar' => $gaya->id_gaya_belajar,
                'nis'             => $gaya->nis,
                'gaya_belajar'    => $gaya->gaya_belajar,
                'minat'           => $gaya->minat ?? null,
                'catatan'         => $gaya->catatan,
                'skor_visual'     => $gaya->skor_visual ?? null,
                'skor_auditori'   => $gaya->skor_auditori ?? null,
                'skor_kinestetik' => $gaya->skor_kinestetik ?? null,
                'created_at'      => $gaya->created_at,
                'updated_at'      => $gaya->updated_at,
            ],
        ]);
    }

    /**
     * POST /api/mobile/siswa/gaya-belajar/submit
     * Siswa mengirimkan jawaban kuesioner. NIS diambil dari token auth.
     *
     * Body JSON:
     * {
     *   "jawaban": [4, 3, 2, 4, 3, 2, 4, 3, 2, 4,
     *               4, 3, 2, 4, 3, 2, 4, 3, 2, 4,
     *               4, 3, 2, 4, 3, 2, 4, 3, 2, 4],
     *   "minat": "Kuliah"
     * }
     */
    public function submit(Request $request)
    {
        $request->validate([
            'jawaban'   => 'required|array|size:30',
            'jawaban.*' => 'required|integer|min:1|max:4',
            'minat'     => 'nullable|string|max:255',
        ]);

        $user = $request->user();
        $nis  = $user->nis ?? $user->username ?? null;

        if (!$nis) {
            return response()->json([
                'success' => false,
                'message' => 'NIS tidak ditemukan pada akun Anda.',
            ], 422);
        }

        $jawaban = $request->jawaban;

        // Hitung skor per kategori (masing-masing 10 soal)
        $skorVisual     = array_sum(array_slice($jawaban, 0,  10)); // soal 1-10
        $skorAuditori   = array_sum(array_slice($jawaban, 10, 10)); // soal 11-20
        $skorKinestetik = array_sum(array_slice($jawaban, 20, 10)); // soal 21-30

        // Tentukan gaya belajar dominan
        $scores = [
            'visual'     => $skorVisual,
            'auditori'   => $skorAuditori,
            'kinestetik' => $skorKinestetik,
        ];
        arsort($scores);
        $gayaDominan = array_key_first($scores);

        // Buat catatan otomatis
        $catatan = sprintf(
            'Hasil kuesioner mandiri — Visual: %d, Auditori: %d, Kinestetik: %d',
            $skorVisual,
            $skorAuditori,
            $skorKinestetik
        );

        // Simpan atau update data gaya belajar
        $gaya = GayaBelajar::updateOrCreate(
            ['nis' => $nis],
            [
                'gaya_belajar'    => $gayaDominan,
                'minat'           => $request->minat,
                'catatan'         => $catatan,
                'skor_visual'     => $skorVisual,
                'skor_auditori'   => $skorAuditori,
                'skor_kinestetik' => $skorKinestetik,
                'id_guru'         => 0, // self-assessment, tidak ada guru
            ]
        );

        return response()->json([
            'success'      => true,
            'message'      => 'Tes gaya belajar berhasil disimpan.',
            'data'         => [
                'id_gaya_belajar' => $gaya->id_gaya_belajar,
                'nis'             => $gaya->nis,
                'gaya_belajar'    => $gaya->gaya_belajar,
                'minat'           => $gaya->minat,
                'catatan'         => $gaya->catatan,
                'skor_visual'     => $gaya->skor_visual,
                'skor_auditori'   => $gaya->skor_auditori,
                'skor_kinestetik' => $gaya->skor_kinestetik,
            ],
        ], 201);
    }
}
