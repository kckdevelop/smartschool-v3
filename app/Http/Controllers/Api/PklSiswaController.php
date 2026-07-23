<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\PklGelombang;
use App\Models\PklKelasGelombang;
use App\Models\PklPenempatan;
use Illuminate\Http\Request;

class PklSiswaController extends Controller
{
    /**
     * Mengambil informasi PKL untuk siswa yang sedang login.
     *
     * - Cek apakah kelas siswa terdaftar dalam gelombang PKL yang aktif.
     * - Jika ya, kembalikan detail gelombang + penempatan siswa (jika ada).
     * - Jika tidak, kembalikan status bahwa kelas tidak termasuk gelombang PKL aktif.
     *
     * Endpoint: GET /api/pkl/siswa/info
     */
    public function info(Request $request)
    {
        $user = $request->user();

        // Pastikan user adalah siswa
        if (!$user || !isset($user->nis)) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Hanya siswa yang dapat mengakses fitur ini.',
            ], 403);
        }

        $idKelas = $user->id_kelas;

        // Cari gelombang aktif yang melibatkan kelas siswa ini
        $kelasGelombang = PklKelasGelombang::where('id_kelas', $idKelas)
            ->whereHas('gelombang', function ($q) {
                $q->where('status', 'aktif');
            })
            ->with(['gelombang.kelasGelombang.kelas'])
            ->first();

        if (!$kelasGelombang) {
            return response()->json([
                'status'               => 'not_in_gelombang',
                'message'              => 'Kelas kamu tidak termasuk dalam gelombang PKL yang aktif saat ini.',
                'in_active_gelombang'  => false,
                'gelombang'            => null,
                'penempatan'           => null,
            ]);
        }

        $gelombang = $kelasGelombang->gelombang;

        // Cari penempatan siswa ini di gelombang tersebut
        $penempatan = PklPenempatan::with(['dudi', 'pembimbing.guru'])
            ->where('id_gelombang', $gelombang->id_gelombang)
            ->where('nis', $user->nis)
            ->first();

        // Format data gelombang
        $gelombangData = [
            'id_gelombang'    => $gelombang->id_gelombang,
            'nama_gelombang'  => $gelombang->nama_gelombang,
            'tanggal_mulai'   => $gelombang->tanggal_mulai?->format('Y-m-d'),
            'tanggal_selesai' => $gelombang->tanggal_selesai?->format('Y-m-d'),
            'status'          => $gelombang->status,
            'keterangan'      => $gelombang->keterangan,
        ];

        // Format data penempatan
        $penempatanData = null;
        if ($penempatan) {
            $dudi = $penempatan->dudi;
            $guru = optional(optional($penempatan->pembimbing)->guru);

            $penempatanData = [
                'id_penempatan'   => $penempatan->id_penempatan,
                'status'          => $penempatan->status,
                'tanggal_masuk'   => $penempatan->tanggal_masuk?->format('Y-m-d'),
                'tanggal_keluar'  => $penempatan->tanggal_keluar?->format('Y-m-d'),
                'keterangan'      => $penempatan->keterangan,
                'dudi' => $dudi ? [
                    'id_dudi'      => $dudi->id_dudi,
                    'nama_dudi'    => $dudi->nama_dudi,
                    'bidang_usaha' => $dudi->bidang_usaha,
                    'alamat'       => $dudi->alamat,
                    'kota'         => $dudi->kota,
                    'no_telepon'   => $dudi->no_telepon,
                    'email'        => $dudi->email,
                    'nama_pic'     => $dudi->nama_pic,
                    'jabatan_pic'  => $dudi->jabatan_pic,
                    'no_hp_pic'    => $dudi->no_hp_pic,
                ] : null,
                'pembimbing_sekolah' => $guru && $guru->id_guru ? [
                    'id_guru'    => $guru->id_guru,
                    'nama_guru'  => $guru->nama_guru,
                    'no_id'      => $guru->no_id,
                ] : null,
            ];
        }

        return response()->json([
            'status'              => 'success',
            'in_active_gelombang' => true,
            'gelombang'           => $gelombangData,
            'penempatan'          => $penempatanData,
        ]);
    }
}
