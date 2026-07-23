<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\UserSiswa;
use App\Models\DataCheckup;
use App\Models\KunjunganUks;
use Illuminate\Http\Request;

class UksDashboardController extends Controller
{
    public function index(Request $request)
    {
        // 1. Data Siswa per Jenis Kelamin (Jenkel)
        $totalSiswa = UserSiswa::count();
        $totalLaki  = UserSiswa::where('jenkel', 'L')->count();
        $totalPerempuan = UserSiswa::where('jenkel', 'P')->count();

        // 2. Data Kesehatan Siswa (Summary Checkup & Kunjungan)
        $totalDiperiksa = DataCheckup::distinct('nis')->count('nis');
        $totalBelumDiperiksa = max(0, $totalSiswa - $totalDiperiksa);
        $totalKunjungan = KunjunganUks::count();

        // Kategori IMT dari checkup terbaru per siswa
        $latestCheckupIds = DataCheckup::selectRaw('MAX(id_checkup) as id_checkup')
            ->groupBy('nis')
            ->pluck('id_checkup');

        $latestCheckups = DataCheckup::whereIn('id_checkup', $latestCheckupIds)->get();

        $kategoriIMT = [
            'Kurus'    => 0,
            'Normal'   => 0,
            'Gemuk'    => 0,
            'Obesitas' => 0,
        ];

        foreach ($latestCheckups as $c) {
            $kat = $c->kategori;
            if ($kat) {
                $katLower = strtolower($kat);
                if (str_contains($katLower, 'kurus')) {
                    $kategoriIMT['Kurus']++;
                } elseif (str_contains($katLower, 'normal')) {
                    $kategoriIMT['Normal']++;
                } elseif (str_contains($katLower, 'gemuk')) {
                    $kategoriIMT['Gemuk']++;
                } elseif (str_contains($katLower, 'obesitas')) {
                    $kategoriIMT['Obesitas']++;
                }
            }
        }

        return response()->json([
            'success' => true,
            'data' => [
                'siswa_jenkel' => [
                    'total' => $totalSiswa,
                    'laki_laki' => $totalLaki,
                    'perempuan' => $totalPerempuan,
                ],
                'kesehatan_siswa' => [
                    'total_diperiksa' => $totalDiperiksa,
                    'total_belum_diperiksa' => $totalBelumDiperiksa,
                    'total_kunjungan' => $totalKunjungan,
                    'kategori_imt' => $kategoriIMT,
                ],
            ]
        ]);
    }
}
