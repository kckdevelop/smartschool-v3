<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\BimbinganKonseling;
use App\Models\BukuKasus;
use App\Models\RiwayatPoin;
use App\Models\RiwayatReward;
use App\Models\Presensi;
use App\Models\Semester;
use App\Models\TahunAjaran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class BkDashboardController extends Controller
{
    public function index(Request $request)
    {
        // Ambil tahun ajaran aktif
        $tahunAktif = TahunAjaran::where('status', 'aktif')->first();
        if ($tahunAktif && preg_match('/^(\d{4})\/(\d{4})$/', $tahunAktif->tahun, $matches)) {
            $startYear = (int)$matches[1];
            $endYear = (int)$matches[2];
            $tahunAjaranNama = $tahunAktif->tahun;
        } else {
            $startYear = Carbon::now()->month >= 7 ? Carbon::now()->year : Carbon::now()->year - 1;
            $endYear = $startYear + 1;
            $tahunAjaranNama = "{$startYear}/{$endYear}";
        }

        // Ambil semester aktif
        $semesterAktif = Semester::where('status', 'aktif')->first();
        if ($semesterAktif) {
            $semAwal  = $semesterAktif->awal->toDateString();
            $semAkhir = $semesterAktif->akhir->toDateString();
        } else {
            $now = Carbon::now();
            if ($now->month >= 7) {
                $semAwal  = "{$startYear}-07-01";
                $semAkhir = "{$startYear}-12-31";
            } else {
                $semAwal  = "{$endYear}-01-01";
                $semAkhir = "{$endYear}-06-30";
            }
        }

        // 1. Total pelanggaran yang dicatat (dari riwayat_poin)
        $totalPelanggaran = RiwayatPoin::count();

        // 2. Total reward yang dicatat (dari riwayat_reward)
        $totalReward = RiwayatReward::count();

        // 3. Total kasus aktif di buku kasus
        $totalBukuKasus = BukuKasus::count();

        // 4. Total bimbingan/konsultasi
        $totalKonsultasi = BimbinganKonseling::count();

        // 5. Kasus pelanggaran terbaru (dari riwayat_poin)
        $pelanggaranTerbaru = RiwayatPoin::with(['siswa.kelas'])
            ->orderByDesc('id_poin')
            ->limit(5)
            ->get()
            ->map(function ($p) {
                return [
                    'id_pelanggaran' => $p->id_poin,
                    'nis' => $p->nis,
                    'nama_siswa' => $p->siswa->nama_siswa ?? '-',
                    'kelas' => $p->siswa->kelas->nama_kelas ?? '-',
                    'pelanggaran' => $p->pelanggaran ?? '-',
                    'poin' => $p->poin ?? 0,
                    'tanggal' => $p->tgl_input instanceof \Carbon\Carbon
                        ? $p->tgl_input->format('Y-m-d')
                        : (string)($p->tgl_input ?? ''),
                ];
            });

        // 6. Konsultasi terbaru (dari bimbingan_konseling)
        $konsultasiTerbaru = BimbinganKonseling::with('siswa.kelas')
            ->orderByDesc('id_bk')
            ->limit(5)
            ->get()
            ->map(function ($k) {
                return [
                    'id_bimbingan' => $k->id_bk,
                    'nis' => $k->nis,
                    'nama_siswa' => $k->siswa->nama_siswa ?? '-',
                    'kelas' => $k->siswa->kelas->nama_kelas ?? '-',
                    'masalah' => $k->uraian ?? $k->jenis_masalah ?? '-',
                    'solusi' => $k->tindak_lanjut ?? '-',
                    'tanggal' => $k->tanggal instanceof \Carbon\Carbon
                        ? $k->tanggal->format('Y-m-d')
                        : (string)($k->tanggal ?? ''),
                ];
            });

        // 7. Presensi Stats (hari ini) — hitung robust dengan CASE WHEN agar mencakup string & numerik
        $today = Carbon::today()->toDateString();
        $presensiSummary = Presensi::where('tanggal', $today)
            ->select(DB::raw("
                SUM(CASE WHEN status IN ('1','Hadir') THEN 1 ELSE 0 END) as hadir,
                SUM(CASE WHEN status IN ('2','Sakit') THEN 1 ELSE 0 END) as sakit,
                SUM(CASE WHEN status IN ('3','Izin') THEN 1 ELSE 0 END) as izin,
                SUM(CASE WHEN status IN ('4','Alfa','Alpha') THEN 1 ELSE 0 END) as alfa
            "))
            ->first();

        // 8. Top 5 Kategori Pelanggaran Terbanyak Bulan Ini
        $nowYear  = Carbon::now()->year;
        $nowMonth = Carbon::now()->month;
        $pelanggaranTrend = RiwayatPoin::select('pelanggaran', DB::raw('count(*) as total'))
            ->whereYear('tgl_input', $nowYear)
            ->whereMonth('tgl_input', $nowMonth)
            ->whereNotNull('pelanggaran')
            ->where('pelanggaran', '!=', '')
            ->groupBy('pelanggaran')
            ->orderByDesc('total')
            ->limit(5)
            ->get()
            ->map(fn($row) => [
                'label' => $row->pelanggaran,
                'total' => (int) $row->total,
            ])
            ->values()
            ->toArray();

        // 9. Buku Kasus Stats (status breakdown)
        $kasusStats = BukuKasus::select('status', DB::raw('count(*) as total'))
            ->groupBy('status')
            ->get()
            ->pluck('total', 'status')
            ->toArray();

        // 10. Gaya Belajar Stats
        $gayaBelajarStats = \App\Models\GayaBelajar::select('gaya_belajar', DB::raw('count(*) as total'))
            ->whereNotNull('gaya_belajar')
            ->where('gaya_belajar', '!=', '')
            ->groupBy('gaya_belajar')
            ->get()
            ->pluck('total', 'gaya_belajar')
            ->toArray();

        // 11. Siswa dengan ketidakhadiran >= 3 hari minggu ini (dengan fallback 14 hari)
        $startOfWeek = Carbon::now()->startOfWeek()->toDateString();
        $endOfWeek   = Carbon::now()->endOfWeek()->toDateString();

        $absenceRecordsThisWeek = Presensi::with(['siswa.kelas'])
            ->whereBetween('tanggal', [$startOfWeek, $endOfWeek])
            ->whereIn('status', ['2', '3', '4', 'Sakit', 'Izin', 'Alfa', 'Alpa'])
            ->orderBy('tanggal', 'desc')
            ->get();

        $groupedBySiswa = $absenceRecordsThisWeek->groupBy('nis');
        $siswaAbsen = [];

        foreach ($groupedBySiswa as $nis => $records) {
            $distinctDates = $records->pluck('tanggal')->map(fn($d) => is_string($d) ? substr($d, 0, 10) : $d->format('Y-m-d'))->unique()->values();
            if ($distinctDates->count() >= 3) {
                $firstRecord = $records->first();
                $siswa = $firstRecord->siswa;

                $details = $records->map(function ($r) {
                    $st = strtolower((string)$r->status);
                    $statusLabel = 'Alfa';
                    if ($st === '2' || $st === 'sakit') $statusLabel = 'Sakit';
                    else if ($st === '3' || $st === 'izin') $statusLabel = 'Izin';
                    
                    return [
                        'tanggal' => is_string($r->tanggal) ? substr($r->tanggal, 0, 10) : $r->tanggal->format('Y-m-d'),
                        'status' => $statusLabel,
                        'keterangan' => $r->keterangan ?? '-',
                    ];
                })->values()->toArray();

                $sakitCount = count(array_filter($details, fn($d) => $d['status'] === 'Sakit'));
                $izinCount  = count(array_filter($details, fn($d) => $d['status'] === 'Izin'));
                $alfaCount  = count(array_filter($details, fn($d) => $d['status'] === 'Alfa'));

                $siswaAbsen[] = [
                    'nis' => (string)$nis,
                    'nama_siswa' => $siswa->nama_siswa ?? 'Siswa (NIS: '.$nis.')',
                    'kelas' => $siswa->kelas->nama_kelas ?? '-',
                    'total_tidak_hadir' => $distinctDates->count(),
                    'sakit' => $sakitCount,
                    'izin' => $izinCount,
                    'alfa' => $alfaCount,
                    'detail' => $details,
                ];
            }
        }

        // Fallback ke 14 hari jika minggu ini belum ada yang >= 3 hari
        if (empty($siswaAbsen)) {
            $startFallback = Carbon::now()->subDays(14)->toDateString();
            $absenceRecordsFallback = Presensi::with(['siswa.kelas'])
                ->whereBetween('tanggal', [$startFallback, Carbon::today()->toDateString()])
                ->whereIn('status', ['2', '3', '4', 'Sakit', 'Izin', 'Alfa', 'Alpa'])
                ->orderBy('tanggal', 'desc')
                ->get();

            $groupedFallback = $absenceRecordsFallback->groupBy('nis');
            foreach ($groupedFallback as $nis => $records) {
                $distinctDates = $records->pluck('tanggal')->map(fn($d) => is_string($d) ? substr($d, 0, 10) : $d->format('Y-m-d'))->unique()->values();
                if ($distinctDates->count() >= 3) {
                    $firstRecord = $records->first();
                    $siswa = $firstRecord->siswa;

                    $details = $records->map(function ($r) {
                        $st = strtolower((string)$r->status);
                        $statusLabel = 'Alfa';
                        if ($st === '2' || $st === 'sakit') $statusLabel = 'Sakit';
                        else if ($st === '3' || $st === 'izin') $statusLabel = 'Izin';
                        
                        return [
                            'tanggal' => is_string($r->tanggal) ? substr($r->tanggal, 0, 10) : $r->tanggal->format('Y-m-d'),
                            'status' => $statusLabel,
                            'keterangan' => $r->keterangan ?? '-',
                        ];
                    })->values()->toArray();

                    $sakitCount = count(array_filter($details, fn($d) => $d['status'] === 'Sakit'));
                    $izinCount  = count(array_filter($details, fn($d) => $d['status'] === 'Izin'));
                    $alfaCount  = count(array_filter($details, fn($d) => $d['status'] === 'Alfa'));

                    $siswaAbsen[] = [
                        'nis' => (string)$nis,
                        'nama_siswa' => $siswa->nama_siswa ?? 'Siswa (NIS: '.$nis.')',
                        'kelas' => $siswa->kelas->nama_kelas ?? '-',
                        'total_tidak_hadir' => $distinctDates->count(),
                        'sakit' => $sakitCount,
                        'izin' => $izinCount,
                        'alfa' => $alfaCount,
                        'detail' => $details,
                    ];
                }
            }
        }

        return response()->json([
            'success' => true,
            'data' => [
                'stats' => [
                    'total_pelanggaran' => $totalPelanggaran,
                    'total_reward' => $totalReward,
                    'total_buku_kasus' => $totalBukuKasus,
                    'total_konsultasi' => $totalKonsultasi,
                ],
                'analytics' => [
                    'presensi' => [
                        'hadir' => (int)($presensiSummary->hadir ?? 0),
                        'sakit' => (int)($presensiSummary->sakit ?? 0),
                        'izin' => (int)($presensiSummary->izin ?? 0),
                        'alfa' => (int)($presensiSummary->alfa ?? 0),
                    ],
                    'pelanggaran_trend' => $pelanggaranTrend,
                    'buku_kasus' => [
                        'proses' => (int)($kasusStats['proses'] ?? $kasusStats['Proses'] ?? 0),
                        'selesai' => (int)($kasusStats['selesai'] ?? $kasusStats['Selesai'] ?? 0),
                    ],
                    'gaya_belajar' => [
                        'visual' => (int)($gayaBelajarStats['Visual'] ?? $gayaBelajarStats['visual'] ?? 0),
                        'auditori' => (int)($gayaBelajarStats['Auditori'] ?? $gayaBelajarStats['auditori'] ?? $gayaBelajarStats['auditoris'] ?? 0),
                        'kinestetik' => (int)($gayaBelajarStats['Kinestetik'] ?? $gayaBelajarStats['kinestetik'] ?? 0),
                        'campuran' => (int)($gayaBelajarStats['Campuran'] ?? $gayaBelajarStats['campuran'] ?? 0),
                    ]
                ],
                'pelanggaran_terbaru' => $pelanggaranTerbaru,
                'konsultasi_terbaru' => $konsultasiTerbaru,
                'siswa_absen_bermasalah' => array_values($siswaAbsen),
            ]
        ]);
    }
}
