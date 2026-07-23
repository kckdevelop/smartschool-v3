<?php

namespace App\Http\Controllers\Uks;

use App\Http\Controllers\Controller;
use App\Models\UserSiswa;
use App\Models\Kelas;
use App\Models\DataCheckup;
use App\Models\KunjunganUks;
use App\Models\DataCheckupGukar;
use App\Models\Guru;
use App\Models\Karyawan;
use App\Models\TahunAjaran;
use App\Models\Semester;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;


class DashboardController extends Controller
{
    public function index(Request $request)
    {
        // 1. Basic Stats (Students)
        $totalSiswa = UserSiswa::count();
        $totalDiperiksa = DataCheckup::distinct('nis')->count('nis');
        $totalBelumDiperiksa = max(0, $totalSiswa - $totalDiperiksa);
        $totalKunjungan = KunjunganUks::count();

        // ── Daftar semua semester (untuk dropdown filter) ──
        $semuaSemester = Semester::with('tahunAjaran')
            ->orderByDesc('id_tahun')
            ->orderByRaw("FIELD(semester, 'Ganjil', 'Genap')")
            ->get();

        // Semester aktif sebagai default
        $semesterAktif = Semester::where('status', 'aktif')->first();

        // Semester yang dipilih (dari query param, fallback ke aktif)
        $selectedSemesterId = $request->input('semester_id',
            $semesterAktif ? $semesterAktif->id_semester : ($semuaSemester->first()?->id_semester)
        );
        $selectedSemester = $semuaSemester->firstWhere('id_semester', $selectedSemesterId)
            ?? $semesterAktif
            ?? $semuaSemester->first();

        if ($selectedSemester) {
            $semStart = Carbon::parse($selectedSemester->awal);
            $semEnd   = Carbon::parse($selectedSemester->akhir);
            $semNama  = 'Semester ' . $selectedSemester->semester; // 'Ganjil' atau 'Genap'
            $semLabel = $semNama . ' — ' . ($selectedSemester->tahunAjaran->tahun ?? '');
        } else {
            $semStart = Carbon::now()->startOfYear();
            $semEnd   = Carbon::now()->endOfYear();
            $semLabel = 'Tahun Ini';
        }

        // ── Grafik Kunjungan per Bulan ──
        $bulanLabels = [];
        $bulanData   = [];
        $cur = $semStart->copy()->startOfMonth();
        $end = $semEnd->copy()->startOfMonth();
        while ($cur->lte($end)) {
            $bulanLabels[] = $cur->translatedFormat('M');
            $bulanData[]   = KunjunganUks::whereYear('tanggal', $cur->year)
                ->whereMonth('tanggal', $cur->month)
                ->whereBetween('tanggal', [$semStart->toDateString(), $semEnd->toDateString()])
                ->count();
            $cur->addMonth();
        }

        // ── Keluhan Terbanyak ──
        $keluhanTerbanyak = KunjunganUks::whereBetween('tanggal', [$semStart->toDateString(), $semEnd->toDateString()])
            ->selectRaw('LOWER(TRIM(keluhan)) as keluhan_norm, COUNT(*) as frekuensi')
            ->groupBy('keluhan_norm')
            ->orderByDesc('frekuensi')
            ->limit(10)
            ->get();


        // 2. Student count per tingkat
        $siswaPerTingkatRaw = UserSiswa::join('kelas', 'user_siswa.id_kelas', '=', 'kelas.id_kelas')
            ->select('kelas.tingkat', DB::raw('count(user_siswa.nis) as total'))
            ->groupBy('kelas.tingkat')
            ->pluck('total', 'tingkat')
            ->toArray();

        // 3. Get the latest check-up for each student to determine current health status
        $latestCheckupIds = DataCheckup::selectRaw('MAX(id_checkup) as id_checkup')
            ->groupBy('nis')
            ->pluck('id_checkup');

        $latestCheckups = DataCheckup::whereIn('id_checkup', $latestCheckupIds)
            ->join('user_siswa', 'data_checkup.nis', '=', 'user_siswa.nis')
            ->join('kelas', 'user_siswa.id_kelas', '=', 'kelas.id_kelas')
            ->select('data_checkup.kategori', 'kelas.tingkat')
            ->get();

        // Define expected grade levels
        $allTingkat = [10, 11, 12];

        $siswaPerTingkat = [];
        $diperiksaPerTingkat = [];
        $kategoriPerTingkat = [];

        foreach ($allTingkat as $t) {
            $siswaPerTingkat[$t] = $siswaPerTingkatRaw[$t] ?? 0;
            $diperiksaPerTingkat[$t] = 0;
            $kategoriPerTingkat[$t] = [
                'Kurus' => 0,
                'Normal' => 0,
                'Gemuk' => 0,
                'Obesitas' => 0,
            ];
        }

        // Aggregate health data from latest checkups
        foreach ($latestCheckups as $c) {
            $t = (int) $c->tingkat;
            if (!in_array($t, $allTingkat)) {
                continue;
            }

            $diperiksaPerTingkat[$t]++;

            $kat = $c->kategori;
            if ($kat) {
                $katLower = strtolower($kat);
                if (str_contains($katLower, 'kurus')) {
                    $kategoriPerTingkat[$t]['Kurus']++;
                } elseif (str_contains($katLower, 'normal')) {
                    $kategoriPerTingkat[$t]['Normal']++;
                } elseif (str_contains($katLower, 'gemuk')) {
                    $kategoriPerTingkat[$t]['Gemuk']++;
                } elseif (str_contains($katLower, 'obesitas')) {
                    $kategoriPerTingkat[$t]['Obesitas']++;
                }
            }
        }

        // Sort tingkat keys
        ksort($siswaPerTingkat);
        ksort($diperiksaPerTingkat);
        ksort($kategoriPerTingkat);
        sort($allTingkat);

        // ═════════════════════════════════════════════════════════════════════
        // 4. Guru & Karyawan (Gukar) Stats and Aggregates
        // ═════════════════════════════════════════════════════════════════════
        $totalGuru = Guru::where('status', 'aktif')->count();
        $totalKaryawan = Karyawan::where('status', 'aktif')->count();
        $totalGukar = $totalGuru + $totalKaryawan;

        // Get latest check-up for each Guru & Karyawan
        $latestGuruCheckupIds = DataCheckupGukar::whereNotNull('id_guru')
            ->selectRaw('MAX(id_checkup) as id_checkup')
            ->groupBy('id_guru')
            ->pluck('id_checkup');

        $latestKaryawanCheckupIds = DataCheckupGukar::whereNotNull('id_karyawan')
            ->selectRaw('MAX(id_checkup) as id_checkup')
            ->groupBy('id_karyawan')
            ->pluck('id_checkup');

        $latestGukarCheckupIds = $latestGuruCheckupIds->merge($latestKaryawanCheckupIds);

        $latestGukarCheckups = DataCheckupGukar::with(['guru', 'karyawan'])->whereIn('id_checkup', $latestGukarCheckupIds)->get();

        $totalGukarDiperiksa = $latestGukarCheckups->count();
        $totalGukarBelumDiperiksa = max(0, $totalGukar - $totalGukarDiperiksa);

        // Initialize classifications
        $gukarKategoriIMT = [
            'Kurus' => 0,
            'Normal' => 0,
            'Gemuk' => 0,
            'Obesitas' => 0,
        ];

        $gukarTekananDarah = [
            'Normal' => 0,
            'Prehipertensi' => 0,
            'Hipertensi Tk 1' => 0,
            'Hipertensi Tk 2' => 0,
        ];

        $gukarKolesterol = [
            'Normal (<200)' => 0,
            'Batas Tinggi (200-239)' => 0, // Always 0 under new rules
            'Tinggi (>=200)' => 0, // Updated key label
        ];

        $gukarGulaDarah = [
            'Normal' => 0,
            'Prediabetes' => 0,
            'Diabetes' => 0,
        ];

        $gukarAsamUrat = [
            'Rendah (<2.4)' => 0,
            'Normal' => 0,
            'Tinggi' => 0,
        ];

        foreach ($latestGukarCheckups as $c) {
            // IMT
            $kat = $c->kategori;
            if ($kat) {
                $katLower = strtolower($kat);
                if (str_contains($katLower, 'kurus')) {
                    $gukarKategoriIMT['Kurus']++;
                } elseif (str_contains($katLower, 'normal')) {
                    $gukarKategoriIMT['Normal']++;
                } elseif (str_contains($katLower, 'gemuk')) {
                    $gukarKategoriIMT['Gemuk']++;
                } elseif (str_contains($katLower, 'obesitas')) {
                    $gukarKategoriIMT['Obesitas']++;
                }
            }

            // Tekanan Darah
            $bp = $c->tekanan_darah;
            if (!empty($bp)) {
                $parts = explode('/', $bp);
                if (count($parts) === 2) {
                    $sys = (int) trim($parts[0]);
                    $dia = (int) trim($parts[1]);
                    if ($sys > 0 && $dia > 0) {
                        if ($sys < 120 && $dia < 80) {
                            $gukarTekananDarah['Normal']++;
                        } elseif (($sys >= 120 && $sys <= 129) && $dia < 80) {
                            $gukarTekananDarah['Prehipertensi']++;
                        } elseif (($sys >= 130 && $sys <= 139) || ($dia >= 80 && $dia <= 89)) {
                            $gukarTekananDarah['Hipertensi Tk 1']++;
                        } elseif ($sys >= 140 || $dia >= 90) {
                            $gukarTekananDarah['Hipertensi Tk 2']++;
                        }
                    }
                }
            }

            // Kolesterol
            $chol = $c->kolesterol;
            if ($chol !== null) {
                if ($chol < 200) {
                    $gukarKolesterol['Normal (<200)']++;
                } else {
                    $gukarKolesterol['Tinggi (>=200)']++;
                }
            }

            // Gula Darah
            $glu = $c->gula_darah;
            $tipe = $c->tipe_gula_darah ?? 'sewaktu';
            if ($glu !== null) {
                if ($tipe === 'puasa') {
                    if ($glu < 100) {
                        $gukarGulaDarah['Normal']++;
                    } elseif ($glu <= 125) {
                        $gukarGulaDarah['Prediabetes']++;
                    } else {
                        $gukarGulaDarah['Diabetes']++;
                    }
                } else {
                    if ($glu < 140) {
                        $gukarGulaDarah['Normal']++;
                    } elseif ($glu <= 199) {
                        $gukarGulaDarah['Prediabetes']++;
                    } else {
                        $gukarGulaDarah['Diabetes']++;
                    }
                }
            }

            // Asam Urat
            $uric = $c->asam_urat;
            $gender = 'L';
            if ($c->guru) {
                $gender = $c->guru->jenkel;
            } elseif ($c->karyawan) {
                $gender = $c->karyawan->jenkel;
            }

            if ($uric !== null) {
                if ($gender === 'P') {
                    if ($uric < 2.4) {
                        $gukarAsamUrat['Rendah (<2.4)']++;
                    } elseif ($uric <= 6.0) {
                        $gukarAsamUrat['Normal']++;
                    } else {
                        $gukarAsamUrat['Tinggi']++;
                    }
                } else {
                    if ($uric < 2.4) {
                        $gukarAsamUrat['Rendah (<2.4)']++;
                    } elseif ($uric <= 7.0) {
                        $gukarAsamUrat['Normal']++;
                    } else {
                        $gukarAsamUrat['Tinggi']++;
                    }
                }
            }
        }

        return view('uks.dashboard.index', compact(
            'totalSiswa',
            'totalDiperiksa',
            'totalBelumDiperiksa',
            'totalKunjungan',
            'allTingkat',
            'siswaPerTingkat',
            'diperiksaPerTingkat',
            'kategoriPerTingkat',

            // Kunjungan chart vars
            'semLabel',
            'semStart',
            'semEnd',
            'bulanLabels',
            'bulanData',
            'keluhanTerbanyak',
            'semuaSemester',
            'selectedSemesterId',

            // Gukar vars
            'totalGukar',
            'totalGukarDiperiksa',
            'totalGukarBelumDiperiksa',
            'gukarKategoriIMT',
            'gukarTekananDarah',
            'gukarKolesterol',
            'gukarGulaDarah',
            'gukarAsamUrat'
        ));
    }
}
