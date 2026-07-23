<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\JenisPelanggaran;
use App\Models\RiwayatPoin;
use App\Models\UserSiswa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PelanggaranController extends Controller
{
    /**
     * GET /api/bk/pelanggaran
     * Daftar riwayat pelanggaran siswa (dari tabel riwayat_poin).
     * Filter: ?nis=, ?id_kelas=, ?per_page=
     */
    public function index(Request $request)
    {
        $query = RiwayatPoin::with(['siswa.kelas', 'guru'])
            ->orderByDesc('tgl_input');

        if ($request->filled('nis')) {
            $query->where('nis', $request->nis);
        }

        if ($request->filled('id_kelas')) {
            $query->whereHas('siswa', function ($q) use ($request) {
                $q->where('id_kelas', $request->id_kelas);
            });
        }

        $perPage = $request->get('per_page', 15);
        $data = $query->paginate($perPage);

        return response()->json([
            'success' => true,
            'data'    => $data,
        ]);
    }

    /**
     * POST /api/bk/pelanggaran
     * Catat pelanggaran siswa — insert ke riwayat_poin.
     *
     * Request fields:
     *   nis                   : NIS siswa
     *   id_jenis_pelanggaran  : ID dari tabel jenis_pelanggaran
     *   tanggal               : Tanggal pelanggaran (Y-m-d)
     *   keterangan            : Opsional – kronologi / detail
     */
    public function store(Request $request)
    {
        $request->validate([
            'nis'                  => 'required|exists:user_siswa,nis',
            'id_jenis_pelanggaran' => 'required|integer|exists:jenis_pelanggaran,id_jenis_pelanggaran',
            'tanggal'              => 'required|date',
            'keterangan'           => 'nullable|string|max:500',
        ]);

        // Ambil data jenis pelanggaran (nama & poin)
        $jenis = JenisPelanggaran::findOrFail($request->id_jenis_pelanggaran);
        $poin  = $jenis->poin ?? 0;

        // Ambil tingkat kelas siswa
        $siswa   = UserSiswa::with('kelas')->findOrFail($request->nis);
        $tingkat = $siswa->kelas ? $siswa->kelas->tingkat : 0;

        // Tentukan id_guru dari user yang sedang login
        $user   = Auth::user();
        $idGuru = $user->id_guru ?? 1;

        // Nama pelanggaran yang disimpan: pakai nama jenis pelanggaran
        // Keterangan/kronologi disimpan sebagai bagian dari catatan jika perlu,
        // namun kolom `pelanggaran` (varchar 100) menyimpan nama jenisnya.
        $namaPelanggaran = $jenis->jenis_pelanggaran;

        DB::beginTransaction();
        try {
            RiwayatPoin::create([
                'tgl_input'   => $request->tanggal,
                'nis'         => (string) $request->nis,
                'tingkat'     => $tingkat,
                'pelanggaran' => $namaPelanggaran,
                'poin'        => $poin,
                'id_guru'     => $idGuru,
            ]);

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Gagal mencatat pelanggaran: ' . $e->getMessage(),
            ], 500);
        }

        return response()->json([
            'success' => true,
            'message' => 'Pelanggaran berhasil dicatat.',
            'data'    => [
                'nis'          => $request->nis,
                'nama_siswa'   => $siswa->nama_siswa,
                'pelanggaran'  => $namaPelanggaran,
                'poin'         => $poin,
                'tanggal'      => $request->tanggal,
                'keterangan'   => $request->keterangan,
            ],
        ], 201);
    }

    /**
     * GET /api/bk/pelanggaran/{id}
     * Detail satu record riwayat poin.
     */
    public function show($id)
    {
        $riwayat = RiwayatPoin::with(['siswa.kelas', 'guru'])->findOrFail($id);

        return response()->json([
            'success' => true,
            'data'    => $riwayat,
        ]);
    }

    /**
     * DELETE /api/bk/pelanggaran/{id}
     * Hapus catatan pelanggaran dari riwayat_poin.
     */
    public function destroy($id)
    {
        $riwayat = RiwayatPoin::findOrFail($id);

        DB::beginTransaction();
        try {
            $riwayat->delete();
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus catatan: ' . $e->getMessage(),
            ], 500);
        }

        return response()->json([
            'success' => true,
            'message' => 'Catatan pelanggaran berhasil dihapus.',
        ]);
    }
}
