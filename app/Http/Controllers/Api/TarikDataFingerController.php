<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\LogAbsensi;
use App\Models\DataMesin;
use App\Models\UserSiswa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TarikDataFingerController extends Controller
{
    /**
     * Ambil log absensi dari database (hasil tarikan mesin).
     */
    public function index(Request $request)
    {
        $query = LogAbsensi::with(['siswa:nis,nama_siswa,id_kelas']);

        if ($request->filled('tanggal')) {
            $query->whereDate('tanggal', $request->tanggal);
        }

        if ($request->filled('tanggal_dari') && $request->filled('tanggal_sampai')) {
            $query->whereBetween('tanggal', [$request->tanggal_dari, $request->tanggal_sampai]);
        }

        if ($request->filled('nis')) {
            $query->where('nis', $request->nis);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $perPage = $request->get('per_page', 20);
        $data    = $query->orderByDesc('tanggal')->orderBy('jam')->paginate($perPage);

        return response()->json([
            'success' => true,
            'data'    => $data,
        ]);
    }

    /**
     * Rekap total log absensi per mesin.
     */
    public function rekapMesin()
    {
        $mesin = DataMesin::orderBy('nama_mesin')->get()->map(function ($m) {
            return [
                'id_mesin'    => $m->id_mesin,
                'nama_mesin'  => $m->nama_mesin,
                'sn'          => $m->sn,
                'total_data'  => $m->data,
                'last_update' => $m->last_update,
            ];
        });

        return response()->json([
            'success' => true,
            'data'    => $mesin,
        ]);
    }

    /**
     * Sinkronkan data log_absensi ke tabel presensi.
     * Menarik data dari log_absensi lalu memprosesnya menjadi data presensi resmi.
     */
    public function sinkronkan(Request $request)
    {
        $request->validate([
            'tanggal_dari'   => 'required|date',
            'tanggal_sampai' => 'required|date|after_or_equal:tanggal_dari',
        ]);

        $logs = LogAbsensi::whereBetween('tanggal', [
            $request->tanggal_dari,
            $request->tanggal_sampai,
        ])->get();

        if ($logs->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' => 'Tidak ada data log absensi pada rentang tanggal tersebut.',
            ], 404);
        }

        $synced  = 0;
        $skipped = 0;

        DB::beginTransaction();
        try {
            foreach ($logs as $log) {
                // Cek apakah siswa ada
                $siswaExists = UserSiswa::where('nis', $log->nis)->exists();
                if (!$siswaExists) {
                    $skipped++;
                    continue;
                }

                // Cek duplikat di tabel presensi
                $exists = DB::table('presensi')
                            ->where('nis', $log->nis)
                            ->whereDate('tanggal', $log->tanggal)
                            ->exists();

                if ($exists) {
                    $skipped++;
                    continue;
                }

                DB::table('presensi')->insert([
                    'nis'        => $log->nis,
                    'tanggal'    => $log->tanggal,
                    'jam'        => $log->jam,
                    'status'     => $log->status ?? 'Hadir',
                    'keterangan' => $log->keterangan ?? 'Sinkronisasi mesin finger',
                    'file'       => null,
                ]);

                $synced++;
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Sinkronisasi gagal: ' . $e->getMessage(),
            ], 500);
        }

        return response()->json([
            'success' => true,
            'message' => "Sinkronisasi selesai. Berhasil: {$synced}, Dilewati: {$skipped}.",
            'total'   => $logs->count(),
            'synced'  => $synced,
            'skipped' => $skipped,
        ]);
    }

    /**
     * Hapus log absensi berdasarkan tanggal.
     */
    public function hapusByTanggal(Request $request)
    {
        $request->validate([
            'tanggal_dari'   => 'required|date',
            'tanggal_sampai' => 'required|date|after_or_equal:tanggal_dari',
        ]);

        $deleted = LogAbsensi::whereBetween('tanggal', [
            $request->tanggal_dari,
            $request->tanggal_sampai,
        ])->delete();

        return response()->json([
            'success' => true,
            'message' => "{$deleted} data log absensi berhasil dihapus.",
        ]);
    }
}
