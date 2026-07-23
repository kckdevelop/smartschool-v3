<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\UserSiswa;
use App\Models\Guru;
use App\Models\Presensi;
use App\Models\Btaq;
use App\Models\Tadarus;
use Carbon\Carbon;

class DashboardController extends Controller
{
    /**
     * Statistik umum dashboard admin.
     * Endpoint: GET /api/dashboard
     */
    public function getStats(Request $request)
    {
        $siswaCount = UserSiswa::count();
        $guruCount = Guru::count();
        $today = Carbon::today()->toDateString();
        $presensiHariIni = Presensi::whereDate('tanggal', $today)->count();

        return response()->json([
            'success' => true,
            'data' => [
                'siswa_count'       => $siswaCount,
                'guru_count'        => $guruCount,
                'presensi_hari_ini' => $presensiHariIni,
                'today_date'        => $today,
            ]
        ]);
    }

    /**
     * Dashboard ringkasan untuk siswa yang sedang login.
     * Endpoint: GET /api/dashboard/siswa
     *
     * Returns:
     *  - info siswa & kelas
     *  - BTAQ terakhir (latest_btaq)
     *  - Tadarus terakhir kelas (latest_tadarus)
     *  - Presensi hari ini (presensi_hari_ini)
     */
    public function getSiswaDashboard(Request $request)
    {
        $user = $request->user();

        // Dapatkan NIS dari user yang login
        $nis = null;
        if ($user && isset($user->nis)) {
            $nis = $user->nis;
        }

        if (!$nis) {
            return response()->json([
                'success' => false,
                'message' => 'Data siswa tidak ditemukan.',
            ], 403);
        }

        // Info siswa + kelas
        $siswa = UserSiswa::with('kelas')->where('nis', $nis)->first();

        // BTAQ terakhir
        $latestBtaq = Btaq::with('guru')
            ->where('nis', $nis)
            ->orderByDesc('tanggal')
            ->first();

        // Tadarus terakhir kelas siswa
        $latestTadarus = null;
        if ($siswa && $siswa->id_kelas) {
            $latestTadarus = Tadarus::where('id_kelas', $siswa->id_kelas)
                ->orderByDesc('tanggal')
                ->first();
        }

        // Presensi hari ini
        $today = Carbon::today()->toDateString();
        $presensiHariIni = Presensi::where('nis', $nis)
            ->whereDate('tanggal', $today)
            ->first();

        return response()->json([
            'success' => true,
            'data'    => [
                'siswa'           => $siswa ? [
                    'nis'       => $siswa->nis,
                    'nama'      => $siswa->nama_siswa,
                    'id_kelas'  => $siswa->id_kelas,
                    'kelas'     => $siswa->kelas ? [
                        'id_kelas'   => $siswa->kelas->id_kelas,
                        'nama_kelas' => $siswa->kelas->nama_kelas,
                        'tingkat'    => $siswa->kelas->tingkat ?? null,
                        'rombel'     => $siswa->kelas->rombel ?? null,
                    ] : null,
                ] : null,
                'latest_btaq'     => $latestBtaq ? [
                    'id_btaq'  => $latestBtaq->id_btaq,
                    'tanggal'  => $latestBtaq->tanggal,
                    'level'    => $latestBtaq->level,
                    'awal'     => $latestBtaq->awal,
                    'akhir'    => $latestBtaq->akhir,
                ] : null,
                'latest_tadarus'  => $latestTadarus ? [
                    'id'           => $latestTadarus->id_tadarus ?? $latestTadarus->id ?? null,
                    'tanggal'      => $latestTadarus->tanggal,
                    'surat_mulai'  => $latestTadarus->surat_mulai ?? $latestTadarus->awal_surat ?? null,
                    'ayat_mulai'   => $latestTadarus->ayat_mulai ?? $latestTadarus->awal_ayat ?? null,
                    'surat_selesai'=> $latestTadarus->surat_selesai ?? $latestTadarus->akhir_surat ?? null,
                    'ayat_selesai' => $latestTadarus->ayat_selesai ?? $latestTadarus->akhir_ayat ?? null,
                ] : null,
                'presensi_hari_ini' => $presensiHariIni ? [
                    'id_presensi' => $presensiHariIni->id_presensi,
                    'status'      => $presensiHariIni->status,
                    'jam'         => $presensiHariIni->jam,
                    'tanggal'     => $presensiHariIni->tanggal,
                ] : null,
            ],
        ]);
    }
}
