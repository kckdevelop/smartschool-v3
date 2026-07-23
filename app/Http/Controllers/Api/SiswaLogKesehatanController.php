<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\DataCheckup;
use App\Models\KunjunganUks;
use App\Models\UserSiswa;
use Illuminate\Http\Request;

class SiswaLogKesehatanController extends Controller
{
    /**
     * Ambil data log kesehatan, riwayat checkup, riwayat kunjungan UKS,
     * dan analisis perkembangan kesehatan untuk siswa yang login.
     * Endpoint: GET /api/siswa/log-kesehatan
     */
    public function index(Request $request)
    {
        $user = $request->user();

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'User tidak terotentikasi.',
            ], 401);
        }

        $nis = null;
        $namaSiswa = '';

        if ($request->filled('nis')) {
            $nis = $request->nis;
        } elseif ($user instanceof UserSiswa) {
            $nis = $user->nis;
            $namaSiswa = $user->nama_siswa;
        } elseif (isset($user->nis)) {
            $nis = $user->nis;
        } else {
            // Fallback untuk Wali, Guru, Karyawan, atau Admin
            $nis = UserSiswa::where('status', 'aktif')->value('nis');
        }

        if (!$nis) {
            return response()->json([
                'success' => false,
                'message' => 'Data NIS siswa tidak ditemukan.',
            ], 404);
        }

        $siswa = UserSiswa::where('nis', $nis)->first();
        if ($siswa) {
            $namaSiswa = $siswa->nama_siswa;
        } else {
            $namaSiswa = $user->nama_siswa ?? $user->nama_guru ?? $user->nama_karyawan ?? $user->nama_lengkap ?? 'Siswa';
        }

        // 1. Data Checkup Fisik Siswa (Tinggi, Berat, IMT, Kategori, Gigi, Mata)
        $checkupDesc = DataCheckup::where('nis', $nis)
            ->orderByDesc('tanggal')
            ->orderByDesc('id_checkup')
            ->get()
            ->map(function ($item) {
            return [
                'id_checkup'    => $item->id_checkup,
                'tanggal'       => $item->tanggal ? $item->tanggal->format('Y-m-d') : null,
                'tinggi_badan'  => $item->tinggi_badan,
                'berat_badan'   => $item->berat_badan,
                'imt'           => $item->imt,
                'kategori'      => $item->kategori,
                'tekanan_darah' => $item->tekanan_darah,
                'is_merokok'    => $item->is_merokok ?? 'Tidak',
                'kondisi_gigi'  => $item->kondisi_gigi ?? $item->keterangan,
                'keterangan'    => $item->keterangan,
            ];
        });

        $terakhir = $checkupDesc->first();

        // 2. Data Kunjungan Ke UKS (with obat list)
        $kunjungan = KunjunganUks::with('riwayatObat')
            ->where('nis', $nis)
            ->orderBy('tanggal', 'desc')
            ->get()
            ->map(function ($item) {
                return [
                    'id_kunjungan' => $item->id_kunjungan,
                    'tanggal'      => $item->tanggal ? $item->tanggal->format('Y-m-d') : null,
                    'jam'          => $item->jam,
                    'keluhan'      => $item->keluhan,
                    'diagnosa'     => $item->diagnosa,
                    'penanganan'   => $item->penanganan,
                    'obat'         => $item->obat,
                    'petugas'      => $item->petugas,
                    'daftar_obat'  => $item->riwayatObat->map(function ($o) {
                        return [
                            'id_riwayat' => $o->id_riwayat,
                            'nama_obat'  => $o->nama_obat,
                            'dosis'      => $o->dosis,
                            'jumlah'     => $o->jumlah,
                        ];
                    })->values()->all(),
                ];
            });

        // 3. Data Deret Waktu (Trend) untuk Grafik Siswa
        $tanggalList    = [];
        $beratBadanList = [];
        $tinggiBadanList= [];
        $imtList        = [];

        foreach ($allCheckups as $rec) {
            $tglStr = $rec->tanggal ? $rec->tanggal->format('d M Y') : '-';
            $tanggalList[]     = $tglStr;
            $beratBadanList[]  = $rec->berat_badan;
            $tinggiBadanList[] = $rec->tinggi_badan;
            $imtList[]         = $rec->imt;
        }

        // 4. Analisis Status Gizi & Rekomendasi Kesehatan Siswa
        $analisis = $this->generateAnalisisSiswa($terakhir);

        return response()->json([
            'success'           => true,
            'nama'              => $namaSiswa,
            'nis'               => (string) $nis,
            'terakhir'          => $terakhir,
            'riwayat_checkup'   => $checkupDesc,
            'riwayat_kunjungan' => $kunjungan,
            'tren'              => [
                'tanggal_list'     => $tanggalList,
                'berat_badan_list'  => $beratBadanList,
                'tinggi_badan_list' => $tinggiBadanList,
                'imt_list'         => $imtList,
            ],
            'analisis'          => $analisis,
        ]);
    }

    private function generateAnalisisSiswa(?array $terakhir): array
    {
        if (!$terakhir) {
            return [
                'status_gizi' => 'Belum Ada Data',
                'catatan'     => 'Belum ada data pemeriksaan fisik berkala UKS untuk Anda.',
                'rekomendasi' => ['Ikuti pemeriksaan fisik berkala di UKS sekolah.'],
            ];
        }

        $kat = strtolower($terakhir['kategori'] ?? 'normal');
        $statusGizi = $terakhir['kategori'] ?? 'Normal';
        $rekomendasi = [];

        if (str_contains($kat, 'kurang') || str_contains($kat, 'kurus')) {
            $rekomendasi[] = 'Tingkatkan asupan kalori & gizi seimbang serta konsumsi makanan kaya protein.';
        } elseif (str_contains($kat, 'lebih') || str_contains($kat, 'obesitas') || str_contains($kat, 'gemuk')) {
            $rekomendasi[] = 'Kurangi konsumsi makanan/minuman berkalori tinggi & rutin berolahraga min 30 menit sehari.';
        } else {
            $rekomendasi[] = 'Pertahankan pola gizi seimbang, tidur cukup 8 jam sehari, dan minum air putih secukupnya.';
        }

        return [
            'status_gizi' => $statusGizi,
            'catatan'     => 'IMT saat ini ' . ($terakhir['imt'] ?? '-') . ' kg/m² (' . $statusGizi . ').',
            'rekomendasi' => $rekomendasi,
        ];
    }
}
