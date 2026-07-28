<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\DataCheckupGukar;
use App\Models\KunjunganUksGukar;
use App\Models\Guru;
use App\Models\Karyawan;
use Illuminate\Http\Request;

class GukarLogKesehatanController extends Controller
{
    /**
     * Ambil riwayat dan analisis perkembangan kesehatan UKS Guru & Karyawan.
     * Endpoint: GET /api/gukar/log-kesehatan
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

        $idGuru = null;
        $idKaryawan = null;
        $namaUser = '';

        if ($user instanceof Guru) {
            $idGuru = $user->id_guru;
            $namaUser = $user->nama_guru;
        } elseif ($user instanceof Karyawan) {
            $idKaryawan = $user->id_karyawan;
            $namaUser = $user->nama_karyawan;
        } else {
            // Fallback jika login via UserSmartschool
            if (isset($user->no_id)) {
                $guru = Guru::where('no_id', $user->no_id)->first();
                if ($guru) {
                    $idGuru = $guru->id_guru;
                    $namaUser = $guru->nama_guru;
                } else {
                    $karyawan = Karyawan::where('no_id', $user->no_id)->first();
                    if ($karyawan) {
                        $idKaryawan = $karyawan->id_karyawan;
                        $namaUser = $karyawan->nama_karyawan;
                    }
                }
            }
        }

        if (!$idGuru && !$idKaryawan) {
            return response()->json([
                'success' => false,
                'message' => 'Data Guru / Karyawan tidak ditemukan untuk akun ini.',
            ], 404);
        }

        $query = DataCheckupGukar::query();
        if ($idGuru) {
            $query->where('id_guru', $idGuru);
        } else {
            $query->where('id_karyawan', $idKaryawan);
        }

        $allRecords = $query->orderBy('tanggal', 'asc')->get();

        $riwayatDesc = $allRecords->sortByDesc('tanggal')->values()->map(function ($item) {
            return [
                'id_checkup'     => $item->id_checkup,
                'tanggal'        => $item->tanggal ? $item->tanggal->format('Y-m-d') : null,
                'jam'            => $item->jam,
                'tinggi_badan'   => $item->tinggi_badan,
                'berat_badan'    => $item->berat_badan,
                'imt'            => $item->imt,
                'kategori'       => $item->kategori,
                'tekanan_darah'  => $item->tekanan_darah,
                'kolesterol'     => $item->kolesterol,
                'gula_darah'     => $item->gula_darah,
                'tipe_gula_darah'=> $item->tipe_gula_darah,
                'asam_urat'      => $item->asam_urat,
            ];
        });

        $terakhir = $riwayatDesc->first();

        // ── Data Deret Waktu (Trend) untuk Diagram/Grafik ──────────────────
        $tanggalList    = [];
        $sistolikList   = [];
        $diastolikList  = [];
        $gulaDarahList  = [];
        $kolesterolList = [];
        $asamUratList   = [];
        $beratBadanList = [];
        $imtList        = [];

        foreach ($allRecords as $rec) {
            $tglStr = $rec->tanggal ? $rec->tanggal->format('d M Y') : '-';
            $tanggalList[]    = $tglStr;
            $gulaDarahList[]  = $rec->gula_darah;
            $kolesterolList[] = $rec->kolesterol;
            $asamUratList[]   = $rec->asam_urat;
            $beratBadanList[] = $rec->berat_badan;
            $imtList[]        = $rec->imt;

            // Parse Tekanan Darah misal "120/80"
            $sys = null;
            $dia = null;
            if (!empty($rec->tekanan_darah) && str_contains($rec->tekanan_darah, '/')) {
                $parts = explode('/', $rec->tekanan_darah);
                $sys = isset($parts[0]) ? (int) trim($parts[0]) : null;
                $dia = isset($parts[1]) ? (int) trim($parts[1]) : null;
            }
            $sistolikList[]  = $sys;
            $diastolikList[] = $dia;
        }

        // ── Analisis & Rekomendasi Kesehatan ──────────────────────────────
        $analisis = $this->generateAnalisis($terakhir);

        // ── Riwayat Kunjungan UKS (with obat) ─────────────────────────────
        $kunjunganQuery = KunjunganUksGukar::with('riwayatObat');
        if ($idGuru) {
            $kunjunganQuery->where('id_guru', $idGuru);
        } else {
            $kunjunganQuery->where('id_karyawan', $idKaryawan);
        }
        $riwayatKunjungan = $kunjunganQuery
            ->orderByDesc('tanggal')
            ->orderByDesc('id_kunjungan')
            ->get()
            ->map(function ($item) {
                return [
                    'id_kunjungan' => $item->id_kunjungan,
                    'tanggal'      => $item->tanggal ? $item->tanggal->format('Y-m-d') : null,
                    'jam'          => $item->jam ?? '',
                    'keluhan'      => $item->keluhan ?? '',
                    'diagnosa'     => $item->diagnosa ?? '',
                    'tindakan'     => $item->tindakan ?? '',
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

        return response()->json([
            'success'           => true,
            'nama'              => $namaUser,
            'terakhir'          => $terakhir,
            'riwayat'           => $riwayatDesc,
            'riwayat_kunjungan' => $riwayatKunjungan,
            'tren'              => [
                'tanggal_list'    => $tanggalList,
                'sistolik_list'   => $sistolikList,
                'diastolik_list'  => $diastolikList,
                'gula_darah_list' => $gulaDarahList,
                'kolesterol_list' => $kolesterolList,
                'asam_urat_list'  => $asamUratList,
                'berat_badan_list'=> $beratBadanList,
                'imt_list'        => $imtList,
            ],
            'analisis'          => $analisis,
        ]);
    }

    /**
     * Helper membuat kesimpulan analisis & saran rekomendasi kesehatan.
     */
    private function generateAnalisis(?array $terakhir): array
    {
        if (!$terakhir) {
            return [
                'status_umum' => 'Belum Ada Data',
                'catatan'     => 'Belum ada data pemeriksaan UKS yang tercatat. Silakan lakukan pemeriksaan kesehatan di UKS secara berkala.',
                'rekomendasi' => ['Lakukan pemeriksaan fisik dasar di UKS sekolah.'],
            ];
        }

        $rekomendasi = [];
        $warnings = 0;

        // 1. Tensi
        $tensiStatus = 'Normal';
        if (!empty($terakhir['tekanan_darah']) && str_contains($terakhir['tekanan_darah'], '/')) {
            $parts = explode('/', $terakhir['tekanan_darah']);
            $sys = (int) trim($parts[0] ?? 0);
            if ($sys >= 140) {
                $tensiStatus = 'Tinggi (Hipertensi)';
                $rekomendasi[] = 'Kurangi konsumsi garam & makanan tinggi natrium, serta kelola stres.';
                $warnings++;
            } elseif ($sys < 90 && $sys > 0) {
                $tensiStatus = 'Rendah (Hipotensi)';
                $rekomendasi[] = 'Perbanyak asupan cairan & istirahat yang cukup.';
            }
        }

        // 2. Gula Darah
        $gulaStatus = 'Normal';
        if (!empty($terakhir['gula_darah'])) {
            $gd = (float) $terakhir['gula_darah'];
            if ($gd >= 200) {
                $gulaStatus = 'Tinggi';
                $rekomendasi[] = 'Batasi konsumsi makanan/minuman manis dan karbohidrat sederhana.';
                $warnings++;
            } elseif ($gd > 140) {
                $gulaStatus = 'Waspada (Pre-Diabetes)';
                $rekomendasi[] = 'Jaga pola makan rendah gula dan tingkatkan aktivitas fisik.';
                $warnings++;
            }
        }

        // 3. Kolesterol
        $kolesterolStatus = 'Normal';
        if (!empty($terakhir['kolesterol'])) {
            $chol = (float) $terakhir['kolesterol'];
            if ($chol >= 240) {
                $kolesterolStatus = 'Tinggi';
                $rekomendasi[] = 'Hindari makanan gorengan, lemak jenuh, dan makanan bersantan.';
                $warnings++;
            } elseif ($chol >= 200) {
                $kolesterolStatus = 'Waspada';
                $rekomendasi[] = 'Perbanyak makan buah, sayur, dan serat alami.';
                $warnings++;
            }
        }

        // 4. Asam Urat
        $asamUratStatus = 'Normal';
        if (!empty($terakhir['asam_urat'])) {
            $au = (float) $terakhir['asam_urat'];
            if ($au >= 7.0) {
                $asamUratStatus = 'Tinggi';
                $rekomendasi[] = 'Kurangi makanan tinggi purin seperti jeroan, emping, dan jengkol.';
                $warnings++;
            }
        }

        if (empty($rekomendasi)) {
            $rekomendasi[] = 'Pertahankan pola hidup sehat, olahraga teratur 30 menit sehari, dan cukup minum air putih.';
        }

        $statusUmum = $warnings === 0 ? 'Kondisi Prima (Sehat)' : ($warnings <= 2 ? 'Perlu Perhatian (Waspada)' : 'Perlu Evaluasi Medis');

        return [
            'status_umum'       => $statusUmum,
            'status_tensi'      => $tensiStatus,
            'status_gula_darah' => $gulaStatus,
            'status_kolesterol' => $kolesterolStatus,
            'status_asam_urat'  => $asamUratStatus,
            'catatan'           => 'Hasil analisis kesehatan berdasarkan pengukuran fisik UKS terakhir tanggal ' . ($terakhir['tanggal'] ?? '-'),
            'rekomendasi'       => $rekomendasi,
        ];
    }
}
