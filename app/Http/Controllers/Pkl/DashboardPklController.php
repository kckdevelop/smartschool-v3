<?php

namespace App\Http\Controllers\Pkl;

use App\Http\Controllers\Controller;
use App\Models\PklGelombang;
use App\Models\PklDudi;
use App\Models\PklPenempatan;
use App\Models\PklPersuratan;
use Illuminate\Http\Request;

class DashboardPklController extends Controller
{
    public function index(Request $request)
    {
        $gelombangAktif = PklGelombang::where('status', 'aktif')->first();

        $totalGelombang   = PklGelombang::count();
        $totalDudi        = PklDudi::where('status', 'aktif')->count();
        $totalPenempatan  = PklPenempatan::when($gelombangAktif, fn($q) =>
            $q->where('id_gelombang', $gelombangAktif->id_gelombang)
        )->count();

        // Data Jurusan untuk filter
        $jurusanList = \App\Models\Jurusan::orderBy('nama_jurusan')->get();
        $idJurusan   = $request->input('id_jurusan');

        // Siswa belum ditempatkan di gelombang aktif
        $totalBelumDitempatkan = 0;
        if ($gelombangAktif) {
            $kelasIds = \App\Models\PklKelasGelombang::where('id_gelombang', $gelombangAktif->id_gelombang)
                ->pluck('id_kelas');
            $sudahNis = PklPenempatan::where('id_gelombang', $gelombangAktif->id_gelombang)
                ->whereIn('status', ['aktif', 'selesai'])->pluck('nis');
            $totalBelumDitempatkan = \App\Models\UserSiswa::whereIn('id_kelas', $kelasIds)
                ->where('status', 'aktif')
                ->whereNotIn('nis', $sudahNis)
                ->count();
        }

        $gelombangList = PklGelombang::latest()->take(5)->get();

        // Statistik per DUDI untuk gelombang aktif (bisa difilter per Jurusan)
        $statDudi = [];
        if ($gelombangAktif) {
            $statDudi = PklDudi::where('status', 'aktif')
                ->withCount(['penempatan as jumlah_siswa' => function($q) use ($gelombangAktif, $idJurusan) {
                    $q->where('id_gelombang', $gelombangAktif->id_gelombang)
                      ->where('status', 'aktif');
                    if ($idJurusan) {
                        $q->whereHas('siswa.kelas', function($k) use ($idJurusan) {
                            $k->where('id_jurusan', $idJurusan);
                        });
                    }
                }])
                ->having('jumlah_siswa', '>', 0)
                ->get();
        }

        $recentSurat = PklPersuratan::with(['dudi', 'gelombang'])
            ->latest()->take(5)->get();

        // Statistik Penempatan per Jurusan
        $jurusanLabels = [];
        $jurusanSudahData = [];
        $jurusanBelumData = [];

        if ($gelombangAktif) {
            $kelasIds = \App\Models\PklKelasGelombang::where('id_gelombang', $gelombangAktif->id_gelombang)
                ->pluck('id_kelas');

            if ($kelasIds->isNotEmpty()) {
                $siswaQuery = \App\Models\UserSiswa::whereIn('id_kelas', $kelasIds)
                    ->where('status', 'aktif')
                    ->with('kelas.jurusan')
                    ->get();

                $sudahDitempatkanNis = PklPenempatan::where('id_gelombang', $gelombangAktif->id_gelombang)
                    ->whereIn('status', ['aktif', 'selesai'])
                    ->pluck('nis')
                    ->toArray();

                $grouped = $siswaQuery->groupBy(function($s) {
                    return $s->kelas->jurusan->nama_jurusan ?? $s->kelas->jurusan->kode_jurusan ?? 'Lainnya';
                });

                foreach ($grouped as $namaJurusan => $siswas) {
                    $sudah = 0;
                    $belum = 0;
                    foreach ($siswas as $s) {
                        if (in_array($s->nis, $sudahDitempatkanNis)) {
                            $sudah++;
                        } else {
                            $belum++;
                        }
                    }
                    $jurusanLabels[] = $namaJurusan;
                    $jurusanSudahData[] = $sudah;
                    $jurusanBelumData[] = $belum;
                }
            }
        }

        return view('pkl.dashboard.index', compact(
            'gelombangAktif', 'totalGelombang', 'totalDudi',
            'totalPenempatan', 'totalBelumDitempatkan',
            'gelombangList', 'statDudi', 'recentSurat',
            'jurusanLabels', 'jurusanSudahData', 'jurusanBelumData',
            'jurusanList', 'idJurusan'
        ));
    }
}
