<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Btaq;
use App\Models\Tadarus;
use App\Models\PantauIbadah;
use App\Models\Kelas;
use App\Models\UserSiswa;
use Illuminate\Http\Request;

class LaporanIsmubaController extends Controller
{
    public function index(Request $request)
    {
        $request->validate([
            'id_kelas' => 'required|integer|exists:kelas,id_kelas',
            'bulan' => 'nullable|string|regex:/^\d{4}-\d{2}$/',
        ]);

        $kelas = Kelas::with('siswa')->findOrFail($request->id_kelas);
        $bulanStr = $request->bulan ?? date('Y-m');

        // Get date range from active semester
        $activeSemester = \App\Models\Semester::where('status', 'aktif')->first();
        if ($activeSemester) {
            $awal = $activeSemester->awal->format('Y-m-d');
            $akhir = $activeSemester->akhir->format('Y-m-d');
            $labelPeriode = 'Semester ' . $activeSemester->semester . ' TP ' . ($activeSemester->tahunAjaran->tahun_ajaran ?? '');
        } else {
            $tahun = (int) substr($bulanStr, 0, 4);
            $bulan = (int) substr($bulanStr, 5, 2);
            $awal = "$tahun-$bulan-01";
            $akhir = date('Y-m-t', strtotime($awal));
            $labelPeriode = date('F Y', strtotime($awal));
        }

        $laporan = [];

        foreach ($kelas->siswa as $siswa) {
            // Count BTAQ
            $btaqCount = Btaq::where('nis', $siswa->nis)
                ->whereBetween('tanggal', [$awal, $akhir])
                ->count();

            // Last BTAQ Progress
            $lastBtaq = Btaq::where('nis', $siswa->nis)
                ->whereBetween('tanggal', [$awal, $akhir])
                ->orderByDesc('tanggal')
                ->orderByDesc('id_btaq')
                ->first();

            $progressBtaq = '-';
            if ($lastBtaq) {
                $lvlStr = $lastBtaq->level;
                if (strtolower(substr($lvlStr, 0, 4)) === 'iqro') {
                    $angka = trim(substr($lvlStr, 4));
                    $levelFormatted = $angka ? 'Iqro ' . $angka : 'Iqro';
                } else if (str_contains(strtolower($lvlStr), 'alquran') || str_contains(strtolower($lvlStr), 'al-quran') || str_contains(strtolower($lvlStr), 'al-qur')) {
                    $levelFormatted = "Al-Qur'an";
                } else {
                    $levelFormatted = ucfirst($lvlStr);
                }
                
                $progressBtaq = $levelFormatted . ' • Awal: ' . $lastBtaq->awal . ' s.d. Akhir: ' . $lastBtaq->akhir;
            }

            // Nilai Praktik Gerakan Wudhu
            $wudhu = PantauIbadah::where('nis', $siswa->nis)
                ->where('jenis_ibadah', 'gerakan_wudhu')
                ->whereBetween('tanggal', [$awal, $akhir])
                ->orderByDesc('tanggal')
                ->first();

            // Nilai Praktik Sholat Fardu
            $sholatFardu = PantauIbadah::where('nis', $siswa->nis)
                ->where('jenis_ibadah', 'sholat_fardu')
                ->whereBetween('tanggal', [$awal, $akhir])
                ->orderByDesc('tanggal')
                ->first();

            // Nilai Praktik Sholat Jenazah
            $sholatJenazah = PantauIbadah::where('nis', $siswa->nis)
                ->where('jenis_ibadah', 'sholat_jenazah')
                ->whereBetween('tanggal', [$awal, $akhir])
                ->orderByDesc('tanggal')
                ->first();

            $laporan[] = [
                'nis' => $siswa->nis,
                'nama_siswa' => $siswa->nama_siswa,
                'total_btaq_bulan_ini' => $btaqCount,
                'progress_btaq_terakhir' => $progressBtaq,
                'nilai_wudhu' => $wudhu ? $wudhu->nilai : null,
                'catatan_wudhu' => $wudhu ? $wudhu->catatan : null,
                'tanggal_wudhu' => $wudhu ? $wudhu->tanggal->format('Y-m-d') : null,
                'nilai_sholat_fardu' => $sholatFardu ? $sholatFardu->nilai : null,
                'catatan_sholat_fardu' => $sholatFardu ? $sholatFardu->catatan : null,
                'tanggal_sholat_fardu' => $sholatFardu ? $sholatFardu->tanggal->format('Y-m-d') : null,
                'nilai_sholat_jenazah' => $sholatJenazah ? $sholatJenazah->nilai : null,
                'catatan_sholat_jenazah' => $sholatJenazah ? $sholatJenazah->catatan : null,
                'tanggal_sholat_jenazah' => $sholatJenazah ? $sholatJenazah->tanggal->format('Y-m-d') : null,
            ];
        }

        return response()->json([
            'success' => true,
            'kelas' => $kelas->nama_kelas,
            'bulan' => $bulanStr,
            'periode' => $labelPeriode,
            'data' => $laporan,
        ]);
    }

    public function dashboard(Request $request)
    {
        $user = $request->user();
        $idGuru = $user ? ($user->id_guru ?? null) : null;
        $idKaryawan = $user ? ($user->id_karyawan ?? null) : null;

        // Get date range from active semester
        $activeSemester = \App\Models\Semester::where('status', 'aktif')->first();
        if ($activeSemester) {
            $awal = $activeSemester->awal->format('Y-m-d');
            $akhir = $activeSemester->akhir->format('Y-m-d');
            $namaSemester = 'Semester ' . $activeSemester->semester . ' TP ' . ($activeSemester->tahunAjaran->tahun_ajaran ?? '');
        } else {
            $awal = date('Y') . '-01-01';
            $akhir = date('Y') . '-12-31';
            $namaSemester = 'Tahun ' . date('Y');
        }

        // 1. Ringkasan Pengisian Tadarus Kelas (di semester aktif)
        $totalTadarus = Tadarus::whereBetween('tanggal', [$awal, $akhir])->count();

        // 2. Ringkasan Kehadiran Pengajian / Total Jadwal Pengajian (di semester aktif)
        $kehadiranQuery = \App\Models\KehadiranPengajian::whereHas('jadwal', function ($q) use ($awal, $akhir) {
            $q->whereBetween('tanggal', [$awal, $akhir]);
        });

        if ($idGuru) {
            $kehadiranQuery->where('id_guru', $idGuru);
        } elseif ($idKaryawan) {
            $kehadiranQuery->where('id_karyawan', $idKaryawan);
        }

        $totalHadirPengajian = (clone $kehadiranQuery)->where('status', 'hadir')->count();
        $totalJadwalPengajian = \App\Models\JadwalPengajian::whereBetween('tanggal', [$awal, $akhir])->count();

        // 3. Riwayat Tadarus Kelas Terbaru (5 terakhir)
        $riwayatTadarus = Tadarus::with(['kelas', 'guru'])
            ->whereBetween('tanggal', [$awal, $akhir])
            ->orderByDesc('tanggal')
            ->orderByDesc('id_tadarus')
            ->limit(5)
            ->get()
            ->map(function ($t) {
                return [
                    'id'               => $t->id_tadarus,
                    'id_kelas'         => $t->id_kelas,
                    'nama_kelas'       => $t->kelas->nama_kelas ?? 'Kelas',
                    'tanggal'          => $t->tanggal ? $t->tanggal->format('Y-m-d') : null,
                    'surat_mulai'      => $t->awal_surat,
                    'ayat_mulai'       => $t->awal_ayat,
                    'surat_selesai'    => $t->akhir_surat,
                    'ayat_selesai'     => $t->akhir_ayat,
                    'pembaca_terakhir' => $t->guru->nama_guru ?? 'Guru ISMUBA',
                    'keterangan'       => $t->keterangan ?? null,
                ];
            });

        // 4. Kehadiran Pengajian Terbaru (5 terakhir)
        $jadwalLatest = \App\Models\JadwalPengajian::whereBetween('tanggal', [$awal, $akhir])
            ->orderByDesc('tanggal')
            ->limit(5)
            ->get();

        $riwayatKehadiran = [];
        foreach ($jadwalLatest as $j) {
            $k = \App\Models\KehadiranPengajian::where('id_jadwal', $j->id_jadwal);
            if ($idGuru) {
                $k->where('id_guru', $idGuru);
            } elseif ($idKaryawan) {
                $k->where('id_karyawan', $idKaryawan);
            }
            $kRecord = $k->first();
            $riwayatKehadiran[] = [
                'id' => $kRecord ? $kRecord->id : 0,
                'id_jadwal' => $j->id_jadwal,
                'nama_kegiatan' => $j->nama_kegiatan,
                'tanggal' => $j->tanggal ? $j->tanggal->format('Y-m-d') : '',
                'tempat' => $j->tempat,
                'status' => $kRecord ? $kRecord->status : 'alpha',
                'jam_absen' => $kRecord ? $kRecord->jam_absen : null,
                'keterangan' => $kRecord ? $kRecord->keterangan : null,
            ];
        }

        return response()->json([
            'success' => true,
            'semester' => $namaSemester,
            'ringkasan' => [
                'total_tadarus' => $totalTadarus,
                'kehadiran_pengajian' => $totalHadirPengajian,
                'total_pengajian' => $totalJadwalPengajian,
            ],
            'riwayat_tadarus' => $riwayatTadarus,
            'riwayat_pengajian' => $riwayatKehadiran,
        ]);
    }
}
