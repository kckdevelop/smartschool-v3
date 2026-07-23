<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\KunjunganUks;
use App\Models\DataCheckup;
use App\Models\Kelas;
use Illuminate\Http\Request;

class UksLaporanController extends Controller
{
    public function index(Request $request)
    {
        $request->validate([
            'id_kelas' => 'required|integer|exists:kelas,id_kelas',
            'bulan' => 'required|string|regex:/^\d{4}-\d{2}$/',
        ]);

        $kelas = Kelas::with('siswa')->findOrFail($request->id_kelas);
        $bulanStr = $request->bulan;
        $tahun = (int) substr($bulanStr, 0, 4);
        $bulan = (int) substr($bulanStr, 5, 2);

        $laporan = [];

        foreach ($kelas->siswa as $siswa) {
            // Count Kunjungan UKS
            $kunjunganCount = KunjunganUks::where('nis', $siswa->nis)
                ->whereYear('tanggal', $tahun)
                ->whereMonth('tanggal', $bulan)
                ->count();

            // Last Kunjungan
            $lastKunjungan = KunjunganUks::where('nis', $siswa->nis)
                ->orderByDesc('tanggal')
                ->first();

            $keluhanTerakhir = $lastKunjungan ? $lastKunjungan->keluhan : '-';

            // Count Checkup UKS
            $checkupCount = DataCheckup::where('nis', $siswa->nis)
                ->whereYear('tanggal', $tahun)
                ->whereMonth('tanggal', $bulan)
                ->count();

            $laporan[] = [
                'nis' => $siswa->nis,
                'nama_siswa' => $siswa->nama_siswa,
                'total_kunjungan' => $kunjunganCount,
                'keluhan_terakhir' => $keluhanTerakhir,
                'total_checkup' => $checkupCount,
            ];
        }

        return response()->json([
            'success' => true,
            'kelas' => $kelas->nama_kelas,
            'bulan' => $bulanStr,
            'data' => $laporan,
        ]);
    }
}
