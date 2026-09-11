<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Kelas;
use App\Models\UserSiswa;
use App\Models\Presensi;
use App\Models\Sekolah;
use Illuminate\Http\Request;
use Carbon\Carbon;

class PublicPresensiController extends Controller
{
    /**
     * Tampilan Dashboard Informasi Presensi Publik
     */
    public function index(Request $request)
    {
        $tanggal = $request->get('tanggal', Carbon::today()->toDateString());
        $sekolah = Sekolah::first();
        
        $data = $this->buildPresensiData($tanggal);

        return view('public.presensi', array_merge([
            'sekolah' => $sekolah,
            'tanggal' => $tanggal,
            'formatted_tanggal' => Carbon::parse($tanggal)->translatedFormat('l, d F Y'),
        ], $data));
    }

    /**
     * Endpoint API/AJAX Publik untuk Filter Tanggal & Auto-Refresh
     */
    public function getData(Request $request)
    {
        $tanggal = $request->get('tanggal', Carbon::today()->toDateString());
        $data = $this->buildPresensiData($tanggal);
        $data['formatted_tanggal'] = Carbon::parse($tanggal)->translatedFormat('l, d F Y');

        return response()->json([
            'status' => 'success',
            'data' => $data
        ]);
    }

    /**
     * Build presensi statistics and breakdown per class
     */
    private function buildPresensiData(string $tanggal): array
    {
        // 1. Ambil semua kelas aktif
        $kelasList = Kelas::where('status', 'aktif')
            ->with(['guru', 'jurusan'])
            ->orderBy('tingkat', 'asc')
            ->orderBy('rombel', 'asc')
            ->get();

        // 2. Ambil semua siswa aktif
        $students = UserSiswa::where('status', 'aktif')
            ->select('nis', 'id_kelas', 'nama_siswa')
            ->get();

        // 3. Ambil presensi di tanggal terpilih untuk siswa aktif
        $presensiList = Presensi::whereDate('tanggal', $tanggal)
            ->whereIn('nis', $students->pluck('nis'))
            ->get();

        $classData = [];
        $tingkatData = [];

        foreach ($kelasList as $kelas) {
            $classStudents = $students->where('id_kelas', $kelas->id_kelas);
            $totalClassSiswa = $classStudents->count();
            $classNisList = $classStudents->pluck('nis');
            
            $classPresensi = $presensiList->whereIn('nis', $classNisList);

            $hadir = 0;
            $sakit = 0;
            $izin = 0;
            $alfa = 0;

            foreach ($classPresensi as $p) {
                $st = strval($p->status);
                if (in_array($st, ['1', 'Hadir', 'hadir', 'H'], true)) {
                    $hadir++;
                } elseif (in_array($st, ['2', 'Sakit', 'sakit', 'S'], true)) {
                    $sakit++;
                } elseif (in_array($st, ['3', 'Izin', 'izin', 'I'], true)) {
                    $izin++;
                } elseif (in_array($st, ['4', 'Alfa', 'alfa', 'Alpha', 'alpha', 'A'], true)) {
                    $alfa++;
                }
            }

            $belumAbsen = max(0, $totalClassSiswa - ($hadir + $sakit + $izin + $alfa));
            $persenHadir = $totalClassSiswa > 0 ? round(($hadir / $totalClassSiswa) * 100, 1) : 0;

            $classData[] = [
                'id_kelas'     => $kelas->id_kelas,
                'tingkat'      => $kelas->tingkat,
                'rombel'       => $kelas->rombel,
                'nama_kelas'   => $kelas->nama_kelas,
                'wali_kelas'   => $kelas->guru?->nama_guru ?? '—',
                'jurusan'      => $kelas->jurusan?->nama_jurusan ?? '—',
                'total_siswa'  => $totalClassSiswa,
                'hadir'        => $hadir,
                'sakit'        => $sakit,
                'izin'         => $izin,
                'alfa'         => $alfa,
                'belum_absen'  => $belumAbsen,
                'persen_hadir' => $persenHadir,
            ];

            // Aggregasi per tingkat
            $tktKey = 'Kelas ' . $kelas->tingkat;
            if (!isset($tingkatData[$tktKey])) {
                $tingkatData[$tktKey] = [
                    'tingkat'     => $kelas->tingkat,
                    'total_siswa' => 0,
                    'hadir'       => 0,
                    'sakit'       => 0,
                    'izin'        => 0,
                    'alfa'        => 0,
                    'belum_absen' => 0,
                ];
            }
            $tingkatData[$tktKey]['total_siswa'] += $totalClassSiswa;
            $tingkatData[$tktKey]['hadir']       += $hadir;
            $tingkatData[$tktKey]['sakit']       += $sakit;
            $tingkatData[$tktKey]['izin']        += $izin;
            $tingkatData[$tktKey]['alfa']        += $alfa;
            $tingkatData[$tktKey]['belum_absen'] += $belumAbsen;
        }

        // Summary Keseluruhan
        $totalSiswa = array_sum(array_column($classData, 'total_siswa'));
        $totalHadir = array_sum(array_column($classData, 'hadir'));
        $totalSakit = array_sum(array_column($classData, 'sakit'));
        $totalIzin  = array_sum(array_column($classData, 'izin'));
        $totalAlfa  = array_sum(array_column($classData, 'alfa'));
        $totalBelum = array_sum(array_column($classData, 'belum_absen'));
        $persenHadirTotal = $totalSiswa > 0 ? round(($totalHadir / $totalSiswa) * 100, 1) : 0;

        // Chart Dataset
        $chartPie = [
            'labels' => ['Hadir', 'Sakit', 'Izin', 'Alfa', 'Belum Absen'],
            'data'   => [$totalHadir, $totalSakit, $totalIzin, $totalAlfa, $totalBelum],
            'colors' => ['#10b981', '#f59e0b', '#06b6d4', '#ef4444', '#9ca3af']
        ];

        $chartBarClass = [
            'labels' => array_column($classData, 'nama_kelas'),
            'hadir'  => array_column($classData, 'hadir'),
            'sakit'  => array_column($classData, 'sakit'),
            'izin'   => array_column($classData, 'izin'),
            'alfa'   => array_column($classData, 'alfa'),
        ];

        return [
            'summary' => [
                'total_siswa'  => $totalSiswa,
                'total_hadir'  => $totalHadir,
                'total_sakit'  => $totalSakit,
                'total_izin'   => $totalIzin,
                'total_alfa'   => $totalAlfa,
                'total_belum'  => $totalBelum,
                'persen_hadir' => $persenHadirTotal,
            ],
            'classes'     => $classData,
            'tingkat'     => array_values($tingkatData),
            'chart_pie'   => $chartPie,
            'chart_bar'   => $chartBarClass,
        ];
    }
}
