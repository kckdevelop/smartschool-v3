<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LinkNisPresensiSeeder extends Seeder
{
    /**
     * Ambil NIS dari log_absensi yang paling banyak data-nya,
     * lalu update NIS beberapa siswa dummy agar cocok untuk uji rekap presensi.
     * Juga generate data presensi & log_absensi yang beragam agar rekap lebih kaya.
     */
    public function run(): void
    {
        // ── Langkah 1: Ambil 30 NIS teratas dari log_absensi ──────────────────
        $logNisList = DB::table('log_absensi')
            ->select('nis', DB::raw('count(*) as total'))
            ->groupBy('nis')
            ->orderByDesc('total')
            ->limit(30)
            ->pluck('nis')
            ->toArray();

        // ── Langkah 2: Saring yang belum ada di user_siswa ────────────────────
        $existingInUsers = DB::table('user_siswa')
            ->whereIn('nis', $logNisList)
            ->pluck('nis')
            ->toArray();

        $targetLogNis = array_values(array_diff($logNisList, $existingInUsers));

        // ── Langkah 3: Ambil 20 siswa dummy (RPL 1, id_kelas=4) ──────────────
        $dummyStudents = DB::table('user_siswa')
            ->where('nis', '>=', 2026100)
            ->orderBy('nis')
            ->limit(count($targetLogNis))
            ->get();

        // ── Langkah 4: Remap NIS siswa dummy → NIS log_absensi ───────────────
        DB::statement('SET FOREIGN_KEY_CHECKS=0');

        foreach ($dummyStudents as $i => $student) {
            if (!isset($targetLogNis[$i])) break;

            $oldNis = $student->nis;
            $newNis = $targetLogNis[$i];

            // Update user_siswa
            DB::table('user_siswa')->where('nis', $oldNis)->update(['nis' => $newNis]);

            // Update detail_siswa
            DB::table('detail_siswa')->where('nis', $oldNis)->update(['nis' => $newNis]);

            // Update presensi jika ada
            DB::table('presensi')->where('nis', $oldNis)->update(['nis' => $newNis]);

            $this->command->line("  ✓ NIS {$oldNis} → {$newNis} ({$student->nama_siswa})");
        }

        DB::statement('SET FOREIGN_KEY_CHECKS=1');

        // ── Langkah 5: Generate data presensi beragam ─────────────────────────
        // Ambil semua NIS yang sudah ada di user_siswa dan cocok dengan log_absensi
        $linkedNis = DB::table('user_siswa')
            ->whereIn('nis', $logNisList)
            ->pluck('nis')
            ->toArray();

        $this->command->info('');
        $this->command->info("▶ Sekarang generate data presensi untuk " . count($linkedNis) . " siswa...");

        // Generate presensi selama 2 minggu terakhir (Senin-Jumat)
        $dates = [];
        $current = now()->subDays(14);
        while ($current->lte(now())) {
            if (!in_array($current->dayOfWeek, [0, 6])) { // Skip Minggu & Sabtu
                $dates[] = $current->toDateString();
            }
            $current->addDay();
        }

        $statusOptions = [
            ['status' => 'Hadir', 'weight' => 70],
            ['status' => 'Izin',  'weight' => 10],
            ['status' => 'Sakit', 'weight' => 10],
            ['status' => 'Alfa',  'weight' => 10],
        ];

        $presensiRows = [];
        $logRows = [];

        foreach ($linkedNis as $nis) {
            foreach ($dates as $date) {
                // 85% chance hadir, skip beberapa hari acak
                if (rand(1, 100) > 90) continue;

                // Pilih status berbobot
                $rand = rand(1, 100);
                if ($rand <= 70) {
                    $status = 'Hadir';
                } elseif ($rand <= 80) {
                    $status = 'Izin';
                } elseif ($rand <= 90) {
                    $status = 'Sakit';
                } else {
                    $status = 'Alfa';
                }

                $jamMasuk = sprintf('%02d:%02d:00', 6, rand(45, 59));

                // Cek apakah sudah ada di presensi
                $existsPresensi = DB::table('presensi')
                    ->where('nis', $nis)
                    ->whereDate('tanggal', $date)
                    ->exists();

                if (!$existsPresensi) {
                    $presensiRows[] = [
                        'nis'        => $nis,
                        'tanggal'    => $date,
                        'jam'        => $jamMasuk,
                        'status'     => $status,
                        'keterangan' => $status === 'Hadir' ? 'Mesin finger – otomatis' : 'Keterangan ' . $status,
                        'file'       => null,
                    ];
                }

                // Log absensi sudah ada dari data real mesin, tapi kalau belum ada, tambahkan
                $existsLog = DB::table('log_absensi')
                    ->where('nis', $nis)
                    ->whereDate('tanggal', $date)
                    ->exists();

                if (!$existsLog && $status === 'Hadir') {
                    $logRows[] = [
                        'nis'     => $nis,
                        'tanggal' => $date,
                        'jam'     => $jamMasuk,
                        'status'  => $status,
                    ];
                }
            }
        }

        // Bulk insert presensi
        $inserted = 0;
        foreach (array_chunk($presensiRows, 100) as $chunk) {
            DB::table('presensi')->insert($chunk);
            $inserted += count($chunk);
        }

        // Bulk insert log_absensi
        foreach (array_chunk($logRows, 100) as $chunk) {
            DB::table('log_absensi')->insert($chunk);
        }

        $this->command->info("✅ Selesai.");
        $this->command->info("   Total presensi ditambahkan : {$inserted}");
        $this->command->info("   Total log ditambahkan       : " . count($logRows));
        $this->command->info("   NIS siswa yang terhubung    : " . count($linkedNis));
    }
}
