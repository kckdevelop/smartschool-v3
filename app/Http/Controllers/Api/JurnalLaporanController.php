<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Guru;
use App\Models\Kelas;
use App\Models\Mapel;
use App\Models\Semester;
use App\Models\Sekolah;
use App\Models\Kemajuan;
use App\Models\JadwalMengajarHarian;
use App\Models\JadwalMengajarTemplate;
use App\Models\JadwalSiklus;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

class JurnalLaporanController extends Controller
{
    private function resolveIdGuru(Request $request): ?int
    {
        $user = $request->user();
        if (!$user) return null;
        if (isset($user->id_guru)) return $user->id_guru;
        if (isset($user->no_id)) {
            return Guru::where('no_id', $user->no_id)->value('id_guru');
        }
        return null;
    }

    /**
     * Get teaching dashboard stats & filter options
     * GET /api/guru/jurnal-laporan/dashboard
     */
    public function dashboard(Request $request)
    {
        $idGuru = $this->resolveIdGuru($request);
        if (!$idGuru) {
            return response()->json(['success' => false, 'message' => 'Data guru tidak ditemukan.'], 403);
        }

        $today = Carbon::today()->toDateString();
        $startOfMonth = Carbon::now()->startOfMonth()->toDateString();
        $endOfMonth = Carbon::now()->endOfMonth()->toDateString();

        // 1. Jadwal Hari Ini
        $hariSiklus = JadwalSiklus::where('tanggal', $today)->value('hari_ke');
        $totalJadwal = JadwalMengajarHarian::where('id_guru', $idGuru)
            ->whereDate('tanggal', $today)
            ->count();

        if ($totalJadwal === 0 && $hariSiklus) {
            $totalJadwal = JadwalMengajarTemplate::where('id_guru', $idGuru)
                ->where('hari_siklus', $hariSiklus)
                ->count();
        }

        // 2. Jurnal Terisi Bulan Ini
        $jurnalBulanIni = Kemajuan::where('id_guru', $idGuru)
            ->whereBetween('tanggal', [$startOfMonth, $endOfMonth])
            ->count();

        // 3. Status Approval
        $jurnalDisetujui = Kemajuan::where('id_guru', $idGuru)
            ->whereBetween('tanggal', [$startOfMonth, $endOfMonth])
            ->where('status_approval', 'approved')
            ->count();

        $jurnalPending = Kemajuan::where('id_guru', $idGuru)
            ->whereBetween('tanggal', [$startOfMonth, $endOfMonth])
            ->where('status_approval', 'pending')
            ->count();

        // 4. Dropdown Options (Kelas & Mapel yang diampu oleh Guru tersebut)
        // Ambil kelas & mapel unik dari template jadwal mengajar
        $kelasIds = JadwalMengajarTemplate::where('id_guru', $idGuru)->pluck('id_kelas')->unique();
        $mapelIds = JadwalMengajarTemplate::where('id_guru', $idGuru)->pluck('id_mapel')->unique();

        // Jika kosong di template, coba cari dari jadwal harian
        if ($kelasIds->isEmpty()) {
            $kelasIds = JadwalMengajarHarian::where('id_guru', $idGuru)->pluck('id_kelas')->unique();
        }
        if ($mapelIds->isEmpty()) {
            $mapelIds = JadwalMengajarHarian::where('id_guru', $idGuru)->pluck('id_mapel')->unique();
        }

        $listKelas = Kelas::whereIn('id_kelas', $kelasIds)->get(['id_kelas', 'tingkat', 'rombel']);
        $listMapel = Mapel::whereIn('id_mapel', $mapelIds)->get(['id_mapel', 'nama_mapel']);
        $listSemester = Semester::with('tahunAjaran')->orderByDesc('id_semester')->get()->map(function ($s) {
            return [
                'id_semester' => $s->id_semester,
                'semester' => $s->semester,
                'tahun' => $s->tahunAjaran->tahun ?? '-',
                'status' => $s->status,
                'label' => ($s->tahunAjaran->tahun ?? '-') . ' - ' . $s->semester . ($s->status == 'aktif' ? ' (Aktif)' : '')
            ];
        });

        return response()->json([
            'success' => true,
            'data' => [
                'stats' => [
                    'jadwal_hari_ini' => $totalJadwal,
                    'jurnal_bulan_ini' => $jurnalBulanIni,
                    'jurnal_disetujui' => $jurnalDisetujui,
                    'jurnal_pending' => $jurnalPending,
                ],
                'kelas' => $listKelas,
                'mapel' => $listMapel,
                'semester' => $listSemester,
            ]
        ]);
    }

    /**
     * Get filtered report data
     * GET /api/guru/jurnal-laporan/report
     */
    public function getReportData(Request $request)
    {
        $idGuru = $this->resolveIdGuru($request);
        if (!$idGuru) {
            return response()->json(['success' => false, 'message' => 'Data guru tidak ditemukan.'], 403);
        }

        $request->validate([
            'id_kelas' => 'required|integer',
            'id_mapel' => 'required|integer',
            'id_semester' => 'required|integer',
        ]);

        $semester = Semester::findOrFail($request->id_semester);
        $awal = Carbon::parse($semester->awal)->toDateString();
        $akhir = Carbon::parse($semester->akhir)->toDateString();

        $jurnals = Kemajuan::with(['kelas', 'mapel'])
            ->where('id_guru', $idGuru)
            ->where('id_kelas', $request->id_kelas)
            ->where('id_mapel', $request->id_mapel)
            ->whereBetween('tanggal', [$awal, $akhir])
            ->orderBy('tanggal')
            ->orderBy('jam_ke')
            ->get()
            ->map(function ($j) {
                $keteranganData = json_decode($j->keterangan, true);
                $hambatan = is_array($keteranganData) ? ($keteranganData['hambatan'] ?? '') : $j->keterangan;
                $pemecahan = is_array($keteranganData) ? ($keteranganData['pemecahan'] ?? '') : '';

                return [
                    'id_kemajuan' => $j->id_kemajuan,
                    'tanggal' => $j->tanggal instanceof Carbon ? $j->tanggal->format('Y-m-d') : substr($j->tanggal, 0, 10),
                    'tanggal_formatted' => $j->tanggal instanceof Carbon ? $j->tanggal->translatedFormat('d F Y') : (string)$j->tanggal,
                    'jam_ke' => $j->jam_ke,
                    'materi' => $j->materi,
                    'jml_siswa' => $j->jml_siswa,
                    'absen' => $j->absen,
                    'hambatan' => $hambatan,
                    'pemecahan' => $pemecahan,
                    'status_approval' => $j->status_approval,
                ];
            });

        return response()->json([
            'success' => true,
            'data' => [
                'jurnal' => $jurnals,
                'total_pertemuan' => $jurnals->count()
            ]
        ]);
    }

    /**
     * Download or Preview PDF
     * GET /api/guru/jurnal-laporan/pdf
     */
    public function downloadPdf(Request $request)
    {
        $idGuru = $this->resolveIdGuru($request);
        if (!$idGuru) {
            return response()->json(['success' => false, 'message' => 'Data guru tidak ditemukan.'], 403);
        }

        $request->validate([
            'id_kelas'    => 'required|integer',
            'id_mapel'    => 'required|integer',
            'id_semester' => 'required|integer',
        ]);

        $sekolah  = Sekolah::first();
        $guru     = Guru::findOrFail($idGuru);
        $kelas    = Kelas::with(['siswa', 'guru'])->findOrFail($request->id_kelas);
        $mapel    = Mapel::findOrFail($request->id_mapel);
        $semester = Semester::with('tahunAjaran')->findOrFail($request->id_semester);

        $awal  = Carbon::parse($semester->awal)->toDateString();
        $akhir = Carbon::parse($semester->akhir)->toDateString();

        // ── Ambil semua jurnal dalam rentang semester ─────────────────────────
        $jurnals = Kemajuan::where('id_guru', $idGuru)
            ->where('id_kelas', $request->id_kelas)
            ->where('id_mapel', $request->id_mapel)
            ->whereBetween('tanggal', [$awal, $akhir])
            ->orderBy('tanggal')
            ->orderBy('jam_ke')
            ->get()
            ->map(function ($j) {
                $keteranganData = json_decode($j->keterangan, true);
                $j->hambatan  = is_array($keteranganData) ? ($keteranganData['hambatan'] ?? '') : $j->keterangan;
                $j->pemecahan = is_array($keteranganData) ? ($keteranganData['pemecahan'] ?? '') : '';

                // Parse absen string → breakdown S/I/A
                $absenStr    = $j->absen ?? '';
                $sakit       = substr_count(strtolower($absenStr), '(sakit)');
                $ijin        = substr_count(strtolower($absenStr), '(ijin)');
                $alpha       = substr_count(strtolower($absenStr), '(alpha)')
                             + substr_count(strtolower($absenStr), '(alpa)');
                $tidakHadir  = $sakit + $ijin + $alpha;
                $hadir       = max(0, ($j->jml_siswa ?? 0) - $tidakHadir);

                $j->presensi = ['H' => $hadir, 'S' => $sakit, 'I' => $ijin, 'A' => $alpha];

                // Parse daftar siswa absen menjadi array: ['nama' => 'Sakit'|'Ijin'|'Alpha']
                $absenMap = [];
                if ($absenStr) {
                    preg_match_all('/([^,(]+)\(([^)]+)\)/i', $absenStr, $matches, PREG_SET_ORDER);
                    foreach ($matches as $m) {
                        $namaAbsen  = trim($m[1]);
                        $statusAbsen = strtolower(trim($m[2]));
                        $absenMap[strtolower($namaAbsen)] = $statusAbsen;
                    }
                }
                $j->absen_map = $absenMap;

                return $j;
            });

        // ── Daftar siswa kelas, sorted by nama ───────────────────────────────
        $daftarSiswa = $kelas->siswa->sortBy('nama_siswa')->values();
        $jumlahSiswa = $daftarSiswa->count();

        // ── Bangun matrix kehadiran: siswa × pertemuan ───────────────────────
        // Hasil: [['nama' => 'X', 'kehadiran' => ['H','S','H',...], 'pct' => 80], ...]
        $matrixKehadiran = $daftarSiswa->map(function ($siswa) use ($jurnals) {
            $kehadiran = $jurnals->map(function ($j) use ($siswa) {
                $namaLower = strtolower($siswa->nama_siswa);
                if (isset($j->absen_map[$namaLower])) {
                    $st = $j->absen_map[$namaLower];
                    if (str_contains($st, 'sakit')) return 'S';
                    if (str_contains($st, 'ijin'))  return 'I';
                    return 'A';
                }
                return 'H';
            })->values()->toArray();

            $totalH  = count(array_filter($kehadiran, fn($k) => $k === 'H'));
            $total   = count($kehadiran);
            $pct     = $total > 0 ? round(($totalH / $total) * 100) : 0;

            return [
                'nama'      => $siswa->nama_siswa,
                'kehadiran' => $kehadiran,
                'hadir'     => $totalH,
                'pct'       => $pct,
            ];
        })->values()->toArray();

        // ── Statistik ringkasan ───────────────────────────────────────────────
        $totalPertemuan = $jurnals->count();
        $hadirSemua     = $jurnals->filter(fn($j) => empty($j->absen))->count();
        $adaAbsen       = $jurnals->filter(fn($j) => !empty($j->absen))->count();
        $adaHambatan    = $jurnals->filter(fn($j) => !empty($j->hambatan))->count();

        // Rata-rata kehadiran seluruh siswa
        $avgPct = count($matrixKehadiran) > 0
            ? round(collect($matrixKehadiran)->avg('pct'))
            : 0;

        // Periode pelaporan (dari tanggal jurnal pertama & terakhir)
        $periodeAwal  = $jurnals->first()?->tanggal;
        $periodeAkhir = $jurnals->last()?->tanggal;

        $data = [
            'sekolah'          => $sekolah,
            'guru'             => $guru,
            'kelas'            => $kelas,
            'mapel'            => $mapel,
            'semester'         => $semester,
            'jurnals'          => $jurnals,
            'tanggal_cetak'    => Carbon::now()->translatedFormat('d F Y'),
            'bulan_tahun'      => $periodeAwal
                ? Carbon::parse($periodeAwal)->translatedFormat('F Y')
                : Carbon::now()->translatedFormat('F Y'),
            'statistik'        => [
                'total_pertemuan' => $totalPertemuan,
                'hadir_semua'     => $hadirSemua,
                'ada_absen'       => $adaAbsen,
                'ada_hambatan'    => $adaHambatan,
                'jumlah_siswa'    => $jumlahSiswa,
                'avg_kehadiran'   => $avgPct,
                'periode_awal'    => $periodeAwal
                    ? Carbon::parse($periodeAwal)->translatedFormat('d F Y')
                    : '-',
                'periode_akhir'   => $periodeAkhir
                    ? Carbon::parse($periodeAkhir)->translatedFormat('d F Y')
                    : '-',
            ],
            'matrix_kehadiran' => $matrixKehadiran,
        ];

        // Format HTML ke PDF (A4 Portrait)
        $pdf = Pdf::loadView('pdf.laporan_jurnal', $data)
            ->setPaper('a4', 'portrait');

        return $pdf->download(
            'Laporan_Jurnal_'
            . str_replace(' ', '_', $mapel->nama_mapel)
            . '_'
            . str_replace(' ', '_', $kelas->nama_kelas)
            . '.pdf'
        );
    }
}

