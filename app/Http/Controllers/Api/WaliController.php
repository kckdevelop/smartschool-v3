<?php

namespace AppHttpControllersApi;

use AppHttpControllersController;
use IlluminateHttpRequest;
use AppModelsUserSiswa;
use AppModelsPresensi;
use AppModelsLmsTugas;
use AppModelsLmsPengumpulan;
use AppModelsLmsKursus;
use AppModelsTagihanPembayaran;
use AppModelsSettingPembayaran;
use AppModelsTahunAjaran;
use CarbonCarbon;

class WaliController extends Controller
{
    /**
     * Dashboard ringkasan untuk wali yang sedang login.
     * Endpoint: GET /api/wali/dashboard
     */
    public function dashboard(Request $request)
    {
        $user = $request->user();
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
            $semuaTugas = LmsTugas::with(['kursus.guru'])
                ->whereHas('kursus', fn($q) => $q->where('id_kelas', $siswa->id_kelas))
                ->get();

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

        // Semester aktif
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

        // Rekap presensi total semester
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

        // Rekap presensi per-bulan dalam semester
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

        // Rekap tugas & nilai
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
     * Daftar tagihan pembayaran untuk siswa (berdasarkan NIS login atau parameter query nis).
     * Endpoint: GET /api/wali/tagihan
     */
    public function tagihan(Request $request)
    {
        $user = $request->user();
        $nis  = trim((string) $request->query('nis', $user->nis ?? ''));

        if (empty($nis)) {
            return response()->json([
                'success' => false,
                'message' => 'Parameter NIS siswa tidak ditemukan.',
            ], 400);
        }

        $siswa = UserSiswa::with('kelas')->where('nis', $nis)->first();
        $namaSiswa = $siswa ? $siswa->nama_siswa : 'Siswa';
        $namaKelas = $siswa?->kelas?->nama_kelas ?? '-';

        $setting = SettingPembayaran::getSetting();
        $tahunAjaranObj = TahunAjaran::where('status', 'aktif')->first();
        $tahunAjaran = $tahunAjaranObj ? $tahunAjaranObj->tahun : (date('Y') . '/' . (date('Y') + 1));

        $tahunVal = date('Y');
        if ($tahunAjaranObj && !empty($tahunAjaranObj->tahun)) {
            $parts = explode('/', $tahunAjaranObj->tahun);
            $tahunVal = trim($parts[0]);
        }

        // Generate 3 Nomor Virtual Account BPD DIY
        $vaSpp = TagihanPembayaran::generateVaNumber($setting->id_institusi, $tahunVal, $nis, $setting->kode_spp, $setting->format_tahun);
        $vaNonSpp = TagihanPembayaran::generateVaNumber($setting->id_institusi, $tahunVal, $nis, $setting->kode_non_spp, $setting->format_tahun);
        $vaTunggakan = TagihanPembayaran::generateVaNumber($setting->id_institusi, $tahunVal, $nis, $setting->kode_tunggakan, $setting->format_tahun);

        // Ambil data tagihan aktual dari tabel database tagihan_pembayaran
        $tagihanDb = TagihanPembayaran::where('nis', $nis)->get();

        $sppItems       = $tagihanDb->where('jenis_tagihan', 'spp');
        $nonSppItems    = $tagihanDb->where('jenis_tagihan', 'non_spp');
        $tunggakanItems = $tagihanDb->where('jenis_tagihan', 'tunggakan');

        $sppNominal       = (float) $sppItems->sum('nominal');
        $sppBayar         = (float) $sppItems->sum('nominal_terbayar');
        $nonSppNominal    = (float) $nonSppItems->sum('nominal');
        $nonSppBayar      = (float) $nonSppItems->sum('nominal_terbayar');
        $tunggakanNominal = (float) $tunggakanItems->sum('nominal');
        $tunggakanBayar   = (float) $tunggakanItems->sum('nominal_terbayar');

        $totalNominal     = (float) $tagihanDb->sum('nominal');
        $totalBayar       = (float) $tagihanDb->sum('nominal_terbayar');
        $sisaPembayaran   = max(0, $totalNominal - $totalBayar);

        $tagihanList = $tagihanDb->map(function ($item) {
            $nom = (float) $item->nominal;
            $bayar = (float) $item->nominal_terbayar;
            $sisa = max(0, $nom - $bayar);
            return [
                'id'               => $item->id,
                'trx_id'           => $item->trx_id,
                'nis'              => $item->nis,
                'nama_tagihan'     => $item->nama_tagihan ?? ($item->jenis_tagihan ? strtoupper($item->jenis_tagihan) : 'Tagihan'),
                'jenis_tagihan'    => $item->jenis_tagihan,
                'jenis'            => $item->jenis_tagihan,
                'keterangan'       => $item->nama_tagihan ?? 'Tagihan',
                'nomor_va'         => $item->nomor_va,
                'nominal'          => $nom,
                'jumlah'           => $nom,
                'nominal_terbayar' => $bayar,
                'sisa_pembayaran'  => $sisa,
                'status'           => $item->status,
                'tanggal_tagihan'  => $item->tanggal_tagihan ? Carbon::parse($item->tanggal_tagihan)->toDateString() : null,
                'tanggal_bayar'    => $item->tanggal_bayar ? Carbon::parse($item->tanggal_bayar)->toDateTimeString() : null,
                'keterangan_det'   => $item->keterangan,
            ];
        })->values();

        $jumlahBelumLunas = $tagihanDb->filter(fn($t) => $t->status !== 'lunas' && ($t->nominal - $t->nominal_terbayar) > 0)->count();

        return response()->json([
            'success' => true,
            'data' => [
                'nis'                => $nis,
                'nama_siswa'         => $namaSiswa,
                'nama_kelas'         => $namaKelas,
                'tahun_ajaran'       => $tahunAjaran,
                'va_spp'             => $vaSpp,
                'va_non_spp'         => $vaNonSpp,
                'va_tunggakan'       => $vaTunggakan,
                'last_update'        => Carbon::now()->locale('id')->translatedFormat('d F Y, H:i') . ' WIB',
                'ringkasan' => [
                    'total_tagihan'      => $totalNominal,
                    'total_terbayar'     => $totalBayar,
                    'total_lunas'        => $totalBayar,
                    'total_nominal'      => $totalNominal,
                    'total_bayar'        => $totalBayar,
                    'sisa_tagihan'       => $sisaPembayaran,
                    'total_belum_bayar'  => $sisaPembayaran,
                    'sisa_pembayaran'    => $sisaPembayaran,
                    'jumlah_belum_bayar' => $jumlahBelumLunas,
                    'total_spp'          => $sppNominal,
                    'spp_nominal'        => $sppNominal,
                    'spp_terbayar'       => $sppBayar,
                    'spp_bayar'          => $sppBayar,
                    'total_non_spp'      => $nonSppNominal,
                    'non_spp_nominal'    => $nonSppNominal,
                    'non_spp_terbayar'   => $nonSppBayar,
                    'non_spp_bayar'      => $nonSppBayar,
                    'total_tunggakan'    => $tunggakanNominal,
                    'tunggakan_nominal'  => $tunggakanNominal,
                    'tunggakan_terbayar' => $tunggakanBayar,
                    'tunggakan_bayar'    => $tunggakanBayar,
                ],
                'tagihan'            => $tagihanList,
            ],
        ]);
    }
}
