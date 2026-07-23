<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\UserSiswa;
use App\Models\Presensi;
use App\Models\LmsTugas;
use App\Models\LmsPengumpulan;
use App\Models\LmsKursus;
use Carbon\Carbon;

class WaliController extends Controller
{
    /**
     * Dashboard ringkasan untuk wali yang sedang login.
     * Endpoint: GET /api/wali/dashboard
     *
     * Returns:
     *  - info siswa & kelas
     *  - presensi hari ini
     *  - rekap presensi bulan ini
     *  - jumlah tagihan belum lunas
     */
    public function dashboard(Request $request)
    {
        $user = $request->user();

        // Untuk wali, NIS disimpan sebagai 'nis_siswa' di token ability / user object
        $nis = $user->nis ?? null;

        if (!$nis) {
            return response()->json([
                'success' => false,
                'message' => 'Data siswa tidak ditemukan.',
            ], 403);
        }

        // Info siswa + kelas
        $siswa = UserSiswa::with('kelas')->where('nis', $nis)->first();

        // Presensi hari ini
        $today = Carbon::today()->toDateString();
        $presensiHariIni = Presensi::where('nis', $nis)
            ->whereDate('tanggal', $today)
            ->first();

        // Get active semester dates
        $activeSemester = \App\Models\Semester::where('status', 'aktif')->first();
        if ($activeSemester) {
            $awal = $activeSemester->awal ? $activeSemester->awal->toDateString() : Carbon::now()->startOfYear()->toDateString();
            $akhir = $activeSemester->akhir ? $activeSemester->akhir->toDateString() : Carbon::now()->endOfYear()->toDateString();
            $namaSemester = $activeSemester->semester;
        } else {
            $awal = Carbon::now()->startOfYear()->toDateString();
            $akhir = Carbon::now()->endOfYear()->toDateString();
            $namaSemester = 'Ganjil';
        }

        // Rekap presensi semester ini
        $rekapSemesterIni = Presensi::where('nis', $nis)
            ->whereBetween('tanggal', [$awal, $akhir])
            ->selectRaw("
                COUNT(*) as total,
                SUM(CASE WHEN status = 'hadir' THEN 1 ELSE 0 END) as hadir,
                SUM(CASE WHEN status = 'sakit' THEN 1 ELSE 0 END) as sakit,
                SUM(CASE WHEN status = 'izin' THEN 1 ELSE 0 END) as izin,
                SUM(CASE WHEN status = 'alfa' THEN 1 ELSE 0 END) as alfa
            ")
            ->first();

        // Tugas yang belum dikerjakan dari LMS (semua kursus di kelas siswa)
        $tugas = [];
        if ($siswa && $siswa->id_kelas) {
            // Semua tugas dari kursus yang ada di kelas siswa
            $semuaTugas = LmsTugas::with(['kursus.guru'])
                ->whereHas('kursus', fn($q) => $q->where('id_kelas', $siswa->id_kelas))
                ->get();

            // NIS siswa yang sudah mengumpulkan
            $sudahKumpul = LmsPengumpulan::where('nis', $nis)
                ->whereIn('id_tugas', $semuaTugas->pluck('id_tugas'))
                ->pluck('id_tugas')
                ->toArray();

            $tugas = $semuaTugas
                ->filter(fn($t) => !in_array($t->id_tugas, $sudahKumpul))
                ->map(function ($t) {
                    $guru = $t->kursus?->guru;
                    return [
                        'id_tugas'     => $t->id_tugas,
                        'judul_tugas'  => $t->judul ?? 'Tugas Tanpa Judul',
                        'nama_kursus'  => $t->kursus?->nama_kursus ?? 'Kursus',
                        'nama_guru'    => $guru?->nama_guru ?? 'Guru',
                        'deskripsi'    => $t->deskripsi ?? '',
                        'tenggat'      => $t->tenggat ? $t->tenggat->toDateString() : null,
                        'tipe'         => $t->tipe ?? 'tugas',
                    ];
                })
                ->values();
        }

        return response()->json([
            'success' => true,
            'data' => [
                'siswa' => $siswa ? [
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
                'presensi_hari_ini' => $presensiHariIni ? [
                    'status'  => $presensiHariIni->status,
                    'jam'     => $presensiHariIni->jam,
                    'tanggal' => $presensiHariIni->tanggal,
                ] : null,
                'nama_semester' => $namaSemester,
                'rekap_semester_ini' => $rekapSemesterIni ? [
                    'total'  => (int) $rekapSemesterIni->total,
                    'hadir'  => (int) $rekapSemesterIni->hadir,
                    'sakit'  => (int) $rekapSemesterIni->sakit,
                    'izin'   => (int) $rekapSemesterIni->izin,
                    'alfa'   => (int) $rekapSemesterIni->alfa,
                ] : null,
                'tugas_belum_selesai' => $tugas,
            ],
        ]);
    }

    /**
     * Data akademik lengkap: rekap presensi per-bulan + rekap tugas & nilai
     * Endpoint: GET /api/wali/akademik
     */
    public function akademik(Request $request)
    {
        $user = $request->user();
        $nis  = $user->nis ?? null;

        if (!$nis) {
            return response()->json([
                'success' => false,
                'message' => 'Data siswa tidak ditemukan.',
            ], 403);
        }

        $siswa = UserSiswa::with('kelas')->where('nis', $nis)->first();

        // ── Semester aktif ────────────────────────────────────────────────────
        $activeSemester = \App\Models\Semester::where('status', 'aktif')->first();
        if ($activeSemester) {
            $awal         = $activeSemester->awal
                ? $activeSemester->awal->toDateString()
                : Carbon::now()->startOfYear()->toDateString();
            $akhir        = $activeSemester->akhir
                ? $activeSemester->akhir->toDateString()
                : Carbon::now()->endOfYear()->toDateString();
            $namaSemester = $activeSemester->semester;
        } else {
            $awal         = Carbon::now()->startOfYear()->toDateString();
            $akhir        = Carbon::now()->endOfYear()->toDateString();
            $namaSemester = 'Ganjil';
        }

        // ── Rekap presensi total semester ─────────────────────────────────────
        $rekapTotal = Presensi::where('nis', $nis)
            ->whereBetween('tanggal', [$awal, $akhir])
            ->selectRaw("
                COUNT(*) as total,
                SUM(CASE WHEN LOWER(status) = 'hadir' THEN 1 ELSE 0 END) as hadir,
                SUM(CASE WHEN LOWER(status) = 'sakit' THEN 1 ELSE 0 END) as sakit,
                SUM(CASE WHEN LOWER(status) = 'izin'  THEN 1 ELSE 0 END) as izin,
                SUM(CASE WHEN LOWER(status) = 'alfa'  THEN 1 ELSE 0 END) as alfa
            ")
            ->first();

        // ── Rekap presensi per-bulan dalam semester ────────────────────────────
        $presensiData = Presensi::where('nis', $nis)
            ->whereBetween('tanggal', [$awal, $akhir])
            ->selectRaw("
                MONTH(tanggal) as bulan,
                YEAR(tanggal) as tahun,
                COUNT(*) as total,
                SUM(CASE WHEN LOWER(status) = 'hadir' THEN 1 ELSE 0 END) as hadir,
                SUM(CASE WHEN LOWER(status) = 'sakit' THEN 1 ELSE 0 END) as sakit,
                SUM(CASE WHEN LOWER(status) = 'izin'  THEN 1 ELSE 0 END) as izin,
                SUM(CASE WHEN LOWER(status) = 'alfa'  THEN 1 ELSE 0 END) as alfa
            ")
            ->groupBy('tahun', 'bulan')
            ->orderBy('tahun')
            ->orderBy('bulan')
            ->get()
            ->map(function ($row) {
                $bulanNames = [
                    1 => 'Januari', 2 => 'Februari', 3 => 'Maret',
                    4 => 'April', 5 => 'Mei', 6 => 'Juni',
                    7 => 'Juli', 8 => 'Agustus', 9 => 'September',
                    10 => 'Oktober', 11 => 'November', 12 => 'Desember',
                ];
                return [
                    'bulan'      => (int) $row->bulan,
                    'tahun'      => (int) $row->tahun,
                    'nama_bulan' => $bulanNames[(int) $row->bulan] ?? '',
                    'total'      => (int) $row->total,
                    'hadir'      => (int) $row->hadir,
                    'sakit'      => (int) $row->sakit,
                    'izin'       => (int) $row->izin,
                    'alfa'       => (int) $row->alfa,
                ];
            });

        // ── Rekap tugas & nilai ────────────────────────────────────────────────
        $rekapTugas = [];
        if ($siswa && $siswa->id_kelas) {
            $kursusAll = LmsKursus::with('guru')
                ->where('id_kelas', $siswa->id_kelas)
                ->get();

            foreach ($kursusAll as $kursus) {
                $tugasList = LmsTugas::where('id_kursus', $kursus->id_kursus)
                    ->where('is_published', true)
                    ->orderBy('tenggat', 'asc')
                    ->get();

                if ($tugasList->isEmpty()) {
                    continue;
                }

                $tugasIds    = $tugasList->pluck('id_tugas');
                $submisiMap  = LmsPengumpulan::where('nis', $nis)
                    ->whereIn('id_tugas', $tugasIds)
                    ->get()
                    ->keyBy('id_tugas');

                $tugasMapped = $tugasList->map(function ($t) use ($submisiMap) {
                    $submisi = $submisiMap->get($t->id_tugas);
                    return [
                        'id_tugas'         => $t->id_tugas,
                        'judul'            => $t->judul ?? 'Tugas',
                        'tipe'             => $t->tipe ?? 'tugas',
                        'tenggat'          => $t->tenggat ? $t->tenggat->toDateString() : null,
                        'status'           => $submisi ? $submisi->status : 'belum',
                        'nilai'            => $submisi ? $submisi->nilai : null,
                        'tanggal_kumpul'   => $submisi ? $submisi->updated_at?->toDateTimeString() : null,
                    ];
                });

                // Statistik ringkasan kursus
                $sudahKumpul = $tugasMapped->where('status', '!=', 'belum')->count();
                $sudahDinilai = $tugasMapped->where('status', 'dinilai')->count();
                $nilaiValues = $tugasMapped->pluck('nilai')->filter()->map(fn($v) => (float) $v);
                $rataRata = $nilaiValues->isNotEmpty() ? round($nilaiValues->avg(), 1) : null;

                $rekapTugas[] = [
                    'id_kursus'     => $kursus->id_kursus,
                    'nama_kursus'   => $kursus->nama_kursus,
                    'nama_guru'     => $kursus->guru?->nama_guru ?? '-',
                    'total_tugas'   => $tugasList->count(),
                    'sudah_kumpul'  => $sudahKumpul,
                    'sudah_dinilai' => $sudahDinilai,
                    'rata_rata'     => $rataRata,
                    'tugas'         => $tugasMapped->values(),
                ];
            }
        }

        return response()->json([
            'success' => true,
            'data' => [
                'nama_semester'    => $namaSemester,
                'periode'          => $awal . ' s/d ' . $akhir,
                'rekap_total'      => [
                    'total' => (int) ($rekapTotal->total ?? 0),
                    'hadir' => (int) ($rekapTotal->hadir ?? 0),
                    'sakit' => (int) ($rekapTotal->sakit ?? 0),
                    'izin'  => (int) ($rekapTotal->izin  ?? 0),
                    'alfa'  => (int) ($rekapTotal->alfa  ?? 0),
                ],
                'rekap_per_bulan'  => $presensiData,
                'rekap_tugas'      => $rekapTugas,
            ],
        ]);
    }

    /**
     * Daftar tagihan pembayaran untuk siswa yang terkait dengan wali.
     * Endpoint: GET /api/wali/tagihan
     *
     * Note: Karena tabel SPP/tagihan pembayaran belum tersedia di database,
     * endpoint ini mengembalikan data dummy yang realistis.
     * Ganti dengan query ke tabel tagihan_spp jika sudah tersedia.
     */
    public function tagihan(Request $request)
    {
        $user = $request->user();
        $nis  = $user->nis ?? null;

        if (!$nis) {
            return response()->json([
                'success' => false,
                'message' => 'Data siswa tidak ditemukan.',
            ], 403);
        }

        $siswa = UserSiswa::with('kelas')->where('nis', $nis)->first();
        $namaSiswa = $siswa ? $siswa->nama_siswa : 'Siswa';

        // ─── DATA DUMMY (Ganti dengan query nyata jika tabel sudah tersedia) ───
        // Contoh implementasi nyata:
        // $tagihan = TagihanSpp::where('nis', $nis)->orderByDesc('tahun')->orderByDesc('bulan')->get();
        $tahunAjaran = '2025/2026';
        $bulanList = [
            ['no' => 7,  'nama' => 'Juli',     'status' => 'lunas',  'tanggal_bayar' => '2025-07-05', 'jumlah' => 250000],
            ['no' => 8,  'nama' => 'Agustus',  'status' => 'lunas',  'tanggal_bayar' => '2025-08-03', 'jumlah' => 250000],
            ['no' => 9,  'nama' => 'September','status' => 'lunas',  'tanggal_bayar' => '2025-09-07', 'jumlah' => 250000],
            ['no' => 10, 'nama' => 'Oktober',  'status' => 'lunas',  'tanggal_bayar' => '2025-10-04', 'jumlah' => 250000],
            ['no' => 11, 'nama' => 'November', 'status' => 'lunas',  'tanggal_bayar' => '2025-11-02', 'jumlah' => 250000],
            ['no' => 12, 'nama' => 'Desember', 'status' => 'lunas',  'tanggal_bayar' => '2025-12-06', 'jumlah' => 250000],
            ['no' => 1,  'nama' => 'Januari',  'status' => 'lunas',  'tanggal_bayar' => '2026-01-04', 'jumlah' => 250000],
            ['no' => 2,  'nama' => 'Februari', 'status' => 'lunas',  'tanggal_bayar' => '2026-02-01', 'jumlah' => 250000],
            ['no' => 3,  'nama' => 'Maret',    'status' => 'lunas',  'tanggal_bayar' => '2026-03-05', 'jumlah' => 250000],
            ['no' => 4,  'nama' => 'April',    'status' => 'lunas',  'tanggal_bayar' => '2026-04-06', 'jumlah' => 250000],
            ['no' => 5,  'nama' => 'Mei',      'status' => 'belum',  'tanggal_bayar' => null,          'jumlah' => 250000],
            ['no' => 6,  'nama' => 'Juni',     'status' => 'belum',  'tanggal_bayar' => null,          'jumlah' => 250000],
        ];

        $tagihanList = array_map(function ($item, $index) use ($tahunAjaran, $nis) {
            return [
                'id'            => $index + 1,
                'nis'           => $nis,
                'jenis'         => 'SPP',
                'keterangan'    => "SPP Bulan {$item['nama']} {$tahunAjaran}",
                'bulan'         => $item['no'],
                'nama_bulan'    => $item['nama'],
                'tahun_ajaran'  => $tahunAjaran,
                'jumlah'        => $item['jumlah'],
                'status'        => $item['status'],
                'tanggal_bayar' => $item['tanggal_bayar'],
            ];
        }, $bulanList, array_keys($bulanList));

        // Tambah tagihan lain (contoh: seragam, buku, dll)
        $tagihanLain = [
            [
                'id'            => 101,
                'nis'           => $nis,
                'jenis'         => 'Seragam',
                'keterangan'    => 'Pembelian Seragam Sekolah 2025/2026',
                'bulan'         => null,
                'nama_bulan'    => null,
                'tahun_ajaran'  => $tahunAjaran,
                'jumlah'        => 450000,
                'status'        => 'lunas',
                'tanggal_bayar' => '2025-07-10',
            ],
            [
                'id'            => 102,
                'nis'           => $nis,
                'jenis'         => 'Buku',
                'keterangan'    => 'Pembelian Buku Pelajaran Semester Ganjil',
                'bulan'         => null,
                'nama_bulan'    => null,
                'tahun_ajaran'  => $tahunAjaran,
                'jumlah'        => 375000,
                'status'        => 'lunas',
                'tanggal_bayar' => '2025-07-12',
            ],
        ];

        $allTagihan = array_merge($tagihanList, $tagihanLain);

        $totalTagihan  = array_sum(array_column($allTagihan, 'jumlah'));
        $totalLunas    = array_sum(array_map(fn($t) => $t['status'] === 'lunas' ? $t['jumlah'] : 0, $allTagihan));
        $totalBelumBayar = $totalTagihan - $totalLunas;
        $jumlahBelumBayar = count(array_filter($allTagihan, fn($t) => $t['status'] !== 'lunas'));

        // Breakdown per jenis
        $sppItems    = array_filter($allTagihan, fn($t) => strtolower($t['jenis']) === 'spp');
        $nonSppItems = array_filter($allTagihan, fn($t) => strtolower($t['jenis']) !== 'spp');

        $totalSpp       = array_sum(array_column($sppItems, 'jumlah'));
        $totalNonSpp    = array_sum(array_column($nonSppItems, 'jumlah'));

        // Tunggakan = SPP yang belum dibayar
        $tunggakanItems = array_filter($sppItems, fn($t) => $t['status'] !== 'lunas');
        $totalTunggakan = array_sum(array_column($tunggakanItems, 'jumlah'));
        $jumlahTunggakan = count($tunggakanItems);

        return response()->json([
            'success' => true,
            'data' => [
                'nis'              => $nis,
                'nama_siswa'       => $namaSiswa,
                'tahun_ajaran'     => $tahunAjaran,
                'last_update'      => Carbon::now()->locale('id')->translatedFormat('d F Y, H:i') . ' WIB',
                'ringkasan' => [
                    'total_tagihan'     => $totalTagihan,
                    'total_lunas'       => $totalLunas,
                    'total_belum_bayar' => $totalBelumBayar,
                    'jumlah_belum_bayar'=> $jumlahBelumBayar,
                    'total_spp'         => $totalSpp,
                    'total_non_spp'     => $totalNonSpp,
                    'total_tunggakan'   => $totalTunggakan,
                    'jumlah_tunggakan'  => $jumlahTunggakan,
                ],
                'tagihan' => $allTagihan,
            ],
        ]);
    }
}
