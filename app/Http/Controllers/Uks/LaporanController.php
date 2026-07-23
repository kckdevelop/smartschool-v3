<?php

namespace App\Http\Controllers\Uks;

use App\Http\Controllers\Controller;
use App\Models\KunjunganUks;
use App\Models\DataCheckup;
use App\Models\UserSiswa;
use App\Models\Kelas;
use App\Models\DataCheckupGukar;
use App\Models\TahunAjaran;
use App\Models\Semester;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class LaporanController extends Controller
{
    // ─────────────────────────────────────────────────────────────────────────
    //  AJAX – Get semesters by tahun ajaran (for dynamic dropdown)
    // ─────────────────────────────────────────────────────────────────────────
    public function getSemesterByTahun(Request $request)
    {
        $idTahun = $request->input('id_tahun');
        $semesters = Semester::where('id_tahun', $idTahun)
            ->orderBy('awal')
            ->get(['id_semester', 'semester', 'awal', 'akhir', 'status']);

        return response()->json($semesters);
    }

    // ─────────────────────────────────────────────────────────────────────────
    //  MAIN LAPORAN INDEX
    // ─────────────────────────────────────────────────────────────────────────
    public function index(Request $request)
    {
        // ── Resolve active tahun ajaran & semester ──────────────────────────
        $tahunAktif    = TahunAjaran::where('status', 'aktif')->first();
        $semesterAktif = Semester::where('status', 'aktif')->first();

        // Selected tahun ajaran
        $idTahun = $request->input('id_tahun',
            $tahunAktif ? $tahunAktif->id_tahun : optional(TahunAjaran::orderByDesc('id_tahun')->first())->id_tahun
        );

        $tahunAjaran = TahunAjaran::find($idTahun);

        // Semesters for this tahun ajaran
        $semesterListForTahun = Semester::where('id_tahun', $idTahun)
            ->orderBy('awal')
            ->get();

        // Selected semester
        $defaultSemesterId = null;
        if ($semesterAktif && $semesterAktif->id_tahun == $idTahun) {
            $defaultSemesterId = $semesterAktif->id_semester;
        } else {
            $defaultSemesterId = optional($semesterListForTahun->first())->id_semester;
        }
        $idSemester = $request->input('id_semester', $defaultSemesterId);

        $semesterObj = Semester::find($idSemester);

        // Fallback if semester not found
        if (!$semesterObj) {
            $semesterObj = $semesterListForTahun->first();
            $idSemester  = optional($semesterObj)->id_semester;
        }

        $periodeAwal  = $semesterObj ? Carbon::parse($semesterObj->awal)  : Carbon::now()->startOfYear();
        $periodeAkhir = $semesterObj ? Carbon::parse($semesterObj->akhir) : Carbon::now()->endOfYear();

        $semesterLabel = $semesterObj
            ? ($semesterObj->semester === 'Ganjil'
                ? 'Semester 1 (Ganjil)'
                : 'Semester 2 (Genap)')
              . ' — ' . $periodeAwal->translatedFormat('d M Y')
              . ' s/d ' . $periodeAkhir->translatedFormat('d M Y')
            : '-';

        // ── Monthly rekap (within period) ──────────────────────────────────
        $rekapBulanan  = [];
        $monthsInRange = [];
        if ($semesterObj) {
            $cur = $periodeAwal->copy()->startOfMonth();
            while ($cur->lte($periodeAkhir)) {
                $m = $cur->month;
                $monthsInRange[] = $m;
                $rekapBulanan[$m] = KunjunganUks::whereBetween('tanggal', [
                    $cur->copy()->startOfMonth()->format('Y-m-d'),
                    $cur->copy()->endOfMonth()->format('Y-m-d'),
                ])->whereBetween('tanggal', [
                    $periodeAwal->format('Y-m-d'),
                    $periodeAkhir->format('Y-m-d'),
                ])->count();
                $cur->addMonth();
            }
        }

        // ── Main kunjungan query ────────────────────────────────────────────
        $query = KunjunganUks::with(['siswa.kelas.jurusan', 'riwayatObat'])
            ->whereBetween('tanggal', [$periodeAwal->format('Y-m-d'), $periodeAkhir->format('Y-m-d')]);

        // ── Top keluhan ─────────────────────────────────────────────────────
        $topKeluhan = KunjunganUks::whereBetween('tanggal', [$periodeAwal->format('Y-m-d'), $periodeAkhir->format('Y-m-d')])
            ->selectRaw('keluhan, COUNT(*) as total')
            ->groupBy('keluhan')
            ->orderByDesc('total')
            ->limit(5)
            ->get();

        $perPage       = (int) $request->input('per_page', 20);
        $kunjunganList = $query->orderByDesc('tanggal')->paginate($perPage)->withQueryString();

        $totalKunjungan = KunjunganUks::whereBetween('tanggal', [$periodeAwal->format('Y-m-d'), $periodeAkhir->format('Y-m-d')])->count();
        $uniqueSiswa    = KunjunganUks::whereBetween('tanggal', [$periodeAwal->format('Y-m-d'), $periodeAkhir->format('Y-m-d')])->distinct('nis')->count('nis');

        // ── All tahun ajaran for dropdown ───────────────────────────────────
        $tahunList = TahunAjaran::orderByDesc('id_tahun')->get();
        if ($tahunList->isEmpty()) {
            $tahunList = collect();
        }

        $namaBulan = [
            1=>'Januari', 2=>'Februari', 3=>'Maret',    4=>'April',
            5=>'Mei',     6=>'Juni',     7=>'Juli',      8=>'Agustus',
            9=>'September',10=>'Oktober',11=>'November', 12=>'Desember',
        ];

        // ── IMT per Kelas ──────────────────────────────────────────────────
        $imtPerKelas = $this->buildImtPerKelas($periodeAwal, $periodeAkhir, $semesterObj);

        // ── Gukar Checkups ─────────────────────────────────────────────────
        $gukarCheckupList = DataCheckupGukar::with(['guru', 'karyawan'])
            ->whereBetween('tanggal', [$periodeAwal->format('Y-m-d'), $periodeAkhir->format('Y-m-d')])
            ->orderByDesc('tanggal')
            ->get();

        return view('uks.laporan.index', compact(
            'idTahun', 'idSemester', 'tahunAjaran', 'semesterObj',
            'semesterListForTahun', 'monthsInRange', 'rekapBulanan',
            'kunjunganList', 'topKeluhan', 'totalKunjungan', 'uniqueSiswa',
            'tahunList', 'namaBulan', 'semesterLabel',
            'periodeAwal', 'periodeAkhir',
            'imtPerKelas', 'gukarCheckupList'
        ));
    }

    // ─────────────────────────────────────────────────────────────────────────
    //  PRINT – Kunjungan UKS
    // ─────────────────────────────────────────────────────────────────────────
    public function print(Request $request)
    {
        [$periodeAwal, $periodeAkhir, $semesterObj, $semesterLabel] = $this->resolvePeriod($request);

        $kunjunganList = KunjunganUks::with(['siswa.kelas.jurusan', 'riwayatObat'])
            ->whereBetween('tanggal', [$periodeAwal->format('Y-m-d'), $periodeAkhir->format('Y-m-d')])
            ->orderByDesc('tanggal')
            ->get();

        $sekolah = \App\Models\Sekolah::where('id_sekolah', 1)->first();

        $namaBulan = [
            1=>'Januari', 2=>'Februari', 3=>'Maret',    4=>'April',
            5=>'Mei',     6=>'Juni',     7=>'Juli',      8=>'Agustus',
            9=>'September',10=>'Oktober',11=>'November', 12=>'Desember',
        ];

        return view('uks.laporan.print', compact(
            'semesterObj', 'kunjunganList', 'sekolah', 'namaBulan', 'semesterLabel',
            'periodeAwal', 'periodeAkhir'
        ));
    }

    // ─────────────────────────────────────────────────────────────────────────
    //  PRINT – IMT Siswa per Kelas
    // ─────────────────────────────────────────────────────────────────────────
    public function printImt(Request $request)
    {
        [$periodeAwal, $periodeAkhir, $semesterObj, $semesterLabel] = $this->resolvePeriod($request);

        $idKelas     = $request->input('id_kelas');
        $imtPerKelas = $this->buildImtPerKelas($periodeAwal, $periodeAkhir, $semesterObj);

        if ($idKelas) {
            $imtPerKelas = array_filter($imtPerKelas, fn($k) => $k['kelas']->id_kelas == $idKelas);
        }

        $sekolah = \App\Models\Sekolah::where('id_sekolah', 1)->first();

        return view('uks.laporan.print-imt', compact(
            'semesterObj', 'semesterLabel', 'imtPerKelas', 'sekolah',
            'periodeAwal', 'periodeAkhir'
        ));
    }

    // ─────────────────────────────────────────────────────────────────────────
    //  PRINT – Check-Up Guru & Karyawan (Gukar)
    // ─────────────────────────────────────────────────────────────────────────
    public function printGukar(Request $request)
    {
        [$periodeAwal, $periodeAkhir, $semesterObj, $semesterLabel] = $this->resolvePeriod($request);

        $gukarCheckupList = DataCheckupGukar::with(['guru', 'karyawan'])
            ->whereBetween('tanggal', [$periodeAwal->format('Y-m-d'), $periodeAkhir->format('Y-m-d')])
            ->orderByDesc('tanggal')
            ->get();

        $sekolah = \App\Models\Sekolah::where('id_sekolah', 1)->first();

        return view('uks.laporan.print-gukar', compact(
            'semesterObj', 'semesterLabel', 'gukarCheckupList', 'sekolah',
            'periodeAwal', 'periodeAkhir'
        ));
    }

    // ─────────────────────────────────────────────────────────────────────────
    //  HELPER – Resolve period from request (id_semester)
    // ─────────────────────────────────────────────────────────────────────────
    private function resolvePeriod(Request $request): array
    {
        $semesterAktif = Semester::where('status', 'aktif')->first();
        $idSemester    = $request->input('id_semester', optional($semesterAktif)->id_semester);
        $semesterObj   = Semester::find($idSemester) ?? $semesterAktif;

        $periodeAwal  = $semesterObj ? Carbon::parse($semesterObj->awal)  : Carbon::now()->startOfYear();
        $periodeAkhir = $semesterObj ? Carbon::parse($semesterObj->akhir) : Carbon::now()->endOfYear();

        $semesterLabel = $semesterObj
            ? ($semesterObj->semester === 'Ganjil'
                ? 'Semester 1 (Ganjil)'
                : 'Semester 2 (Genap)')
              . ' — ' . $periodeAwal->translatedFormat('d M Y')
              . ' s/d ' . $periodeAkhir->translatedFormat('d M Y')
            : '-';

        return [$periodeAwal, $periodeAkhir, $semesterObj, $semesterLabel];
    }

    // ─────────────────────────────────────────────────────────────────────────
    //  HELPER – Build IMT per Kelas with trend analysis
    // ─────────────────────────────────────────────────────────────────────────
    private function buildImtPerKelas(Carbon $periodeAwal, Carbon $periodeAkhir, ?Semester $semesterObj): array
    {
        // Determine previous semester period for trend comparison
        $prevAwal  = null;
        $prevAkhir = null;

        if ($semesterObj) {
            // Find the previous semester (ordered by akhir date, just before current awal)
            $prevSemester = Semester::where('akhir', '<', $semesterObj->awal)
                ->orderByDesc('akhir')
                ->first();
            if ($prevSemester) {
                $prevAwal  = Carbon::parse($prevSemester->awal);
                $prevAkhir = Carbon::parse($prevSemester->akhir);
            }
        }

        // Fetch all active classes with students
        $kelasList = Kelas::with(['jurusan', 'guru'])
            ->where('status', 'aktif')
            ->orderBy('tingkat')
            ->orderBy('rombel')
            ->get();

        // Fetch latest checkup for CURRENT semester per NIS
        $currentCheckups = DataCheckup::whereBetween('tanggal', [$periodeAwal->format('Y-m-d'), $periodeAkhir->format('Y-m-d')])
            ->select('nis', 'tanggal', 'tinggi_badan', 'berat_badan', 'imt', 'kategori')
            ->orderBy('tanggal', 'desc')
            ->get()
            ->groupBy('nis')
            ->map(fn($rows) => $rows->first());

        // Fetch latest checkup for PREVIOUS semester per NIS (for trend)
        $prevCheckups = collect();
        if ($prevAwal && $prevAkhir) {
            $prevCheckups = DataCheckup::whereBetween('tanggal', [$prevAwal->format('Y-m-d'), $prevAkhir->format('Y-m-d')])
                ->select('nis', 'tanggal', 'imt', 'kategori')
                ->orderBy('tanggal', 'desc')
                ->get()
                ->groupBy('nis')
                ->map(fn($rows) => $rows->first());
        }

        $result = [];

        foreach ($kelasList as $kelas) {
            $students = UserSiswa::where('id_kelas', $kelas->id_kelas)
                ->where('status', 'aktif')
                ->orderBy('nama_siswa')
                ->get();

            if ($students->isEmpty()) {
                continue;
            }

            $siswaData    = [];
            $kategoriCount = ['Kurus' => 0, 'Normal' => 0, 'Gemuk' => 0, 'Obesitas' => 0];

            foreach ($students as $siswa) {
                $nis     = $siswa->nis;
                $current = $currentCheckups->get($nis);
                $prev    = $prevCheckups->get($nis);

                $trend      = 'belum';
                $trendLabel = '-';
                $imtDiff    = null;

                if ($current) {
                    if ($prev) {
                        $imtDiff = round($current->imt - $prev->imt, 1);
                        if ($imtDiff > 0.1) {
                            $trend      = 'naik';
                            $trendLabel = '+' . $imtDiff;
                        } elseif ($imtDiff < -0.1) {
                            $trend      = 'turun';
                            $trendLabel = (string) $imtDiff;
                        } else {
                            $trend      = 'tetap';
                            $trendLabel = '0.0';
                        }
                    } else {
                        $trend      = 'baru';
                        $trendLabel = 'Baru';
                    }

                    $kat = strtolower($current->kategori ?? '');
                    if (str_contains($kat, 'kurus'))        $kategoriCount['Kurus']++;
                    elseif (str_contains($kat, 'normal'))   $kategoriCount['Normal']++;
                    elseif (str_contains($kat, 'gemuk'))    $kategoriCount['Gemuk']++;
                    elseif (str_contains($kat, 'obesitas')) $kategoriCount['Obesitas']++;
                }

                $siswaData[] = [
                    'siswa'      => $siswa,
                    'current'    => $current,
                    'prev'       => $prev,
                    'trend'      => $trend,
                    'trendLabel' => $trendLabel,
                    'imtDiff'    => $imtDiff,
                ];
            }

            $result[] = [
                'kelas'          => $kelas,
                'siswaData'      => $siswaData,
                'kategoriCount'  => $kategoriCount,
                'totalSiswa'     => count($siswaData),
                'totalDiperiksa' => collect($siswaData)->filter(fn($s) => $s['current'] !== null)->count(),
            ];
        }

        return $result;
    }
}
