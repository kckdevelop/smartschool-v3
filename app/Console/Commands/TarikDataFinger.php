<?php

namespace App\Console\Commands;

use App\Models\DataMesin;
use App\Models\LogAbsensi;
use App\Models\UserSiswa;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class TarikDataFinger extends Command
{
    protected $signature   = 'finger:tarik {--id_mesin= : ID mesin tertentu, kosong = semua mesin} {--force : Memaksa penarikan data meskipun sinkronisasi otomatis dimatikan}';
    protected $description = 'Tarik data absensi dari semua mesin finger ke log_absensi lalu sinkronkan ke presensi';

    private string $baseUrl = 'http://www.solutioncloud.co.id/';

    public function handle(): int
    {
        $force = $this->option('force');
        if (!$force) {
            $sekolah = DB::table('sekolah')->where('id_sekolah', 1)->first();
            $syncOtomatis = $sekolah ? (bool) $sekolah->sync_otomatis : true;
            if (!$syncOtomatis) {
                $this->info('Info: Sinkronisasi otomatis sedang dimatikan via pengaturan.');
                Log::info('[TarikDataFinger] Sinkronisasi otomatis dilewati karena dimatikan via pengaturan.');
                return self::SUCCESS;
            }
        }

        $idMesin = $this->option('id_mesin');
        $mesinList = $idMesin
            ? DataMesin::where('id_mesin', $idMesin)->get()
            : DataMesin::all();

        if ($mesinList->isEmpty()) {
            $this->warn('Tidak ada mesin finger yang ditemukan.');
            return self::FAILURE;
        }

        $totalBerhasil = 0;
        $totalSynced   = 0;

        foreach ($mesinList as $mesin) {
            $this->info("▶ Memproses mesin: {$mesin->nama_mesin} (SN: {$mesin->sn})");

            try {
                $cookie = $this->login($mesin->sn, $mesin->password);

                if (!$cookie) {
                    $this->error("  ✗ Gagal login ke mesin {$mesin->nama_mesin}");
                    Log::error("[TarikDataFinger] Gagal login mesin {$mesin->nama_mesin} (SN: {$mesin->sn})");
                    continue;
                }

                // Compact data di cloud terlebih dahulu
                $this->compactData($cookie);

                // Ambil data log
                $rawData = $this->fetchData($cookie);

                if (empty($rawData)) {
                    $this->warn("  ⚠ Tidak ada data dari mesin {$mesin->nama_mesin}");
                    continue;
                }

                // Simpan ke log_absensi
                $inserted = $this->insertLog($rawData, $mesin->id_mesin);
                $totalBerhasil += $inserted;
                $this->line("  ✓ {$inserted} baris log berhasil disimpan");

                // Update info mesin
                $mesin->update([
                    'data'        => $inserted,
                    'last_update' => now(),
                ]);

            } catch (\Exception $e) {
                $this->error("  ✗ Error: " . $e->getMessage());
                Log::error("[TarikDataFinger] Error mesin {$mesin->nama_mesin}: " . $e->getMessage());
            }
        }

        // Sinkronisasi log → presensi
        $this->info('');
        $this->info('⏳ Sinkronisasi log_absensi → presensi...');
        $totalSynced = $this->sinkronkan();
        $this->info("✅ Selesai. Log tersimpan: {$totalBerhasil} | Presensi baru: {$totalSynced}");

        Log::info("[TarikDataFinger] Selesai. Log: {$totalBerhasil}, Presensi baru: {$totalSynced}");

        return self::SUCCESS;
    }

    // -------------------------------------------------------------------------
    // Step 1: Login → dapat session cookie
    // -------------------------------------------------------------------------
    private function login(string $sn, string $password): ?string
    {
        $ch = curl_init();
        curl_setopt_array($ch, [
            CURLOPT_URL            => $this->baseUrl . 'sc_pro.asp',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST           => true,
            CURLOPT_POSTFIELDS     => http_build_query(['sn' => $sn, 'pass' => $password]),
            CURLOPT_HTTPHEADER     => ['Content-Type: application/x-www-form-urlencoded'],
            CURLOPT_HEADER         => true,
            CURLOPT_TIMEOUT        => 30,
            CURLOPT_SSL_VERIFYPEER => false,
        ]);

        $response = curl_exec($ch);
        if ($response === false) {
            curl_close($ch);
            return null;
        }

        $headerSize = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
        $headers    = substr($response, 0, $headerSize);
        curl_close($ch);

        preg_match_all('/^Set-Cookie:\s*([^;]*)/mi', $headers, $cookies);

        return $cookies[1][0] ?? null;
    }

    // -------------------------------------------------------------------------
    // Step 2: Compact data di cloud (hapus & download ulang)
    // -------------------------------------------------------------------------
    private function compactData(string $cookie): void
    {
        $ch = curl_init();
        curl_setopt_array($ch, [
            CURLOPT_URL            => $this->baseUrl . 'mesin.asp?hapus=1',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER     => ['Cookie: ' . $cookie],
            CURLOPT_TIMEOUT        => 30,
            CURLOPT_SSL_VERIFYPEER => false,
        ]);
        curl_exec($ch);
        curl_close($ch);
    }

    // -------------------------------------------------------------------------
    // Step 3: Fetch raw attendance data
    // -------------------------------------------------------------------------
    private function fetchData(string $cookie): string
    {
        $ch = curl_init();
        curl_setopt_array($ch, [
            CURLOPT_URL            => $this->baseUrl . 'view.asp',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER     => ['Cookie: ' . $cookie],
            CURLOPT_TIMEOUT        => 60,
            CURLOPT_SSL_VERIFYPEER => false,
        ]);
        $response = curl_exec($ch);
        curl_close($ch);

        return $response ?: '';
    }

    // -------------------------------------------------------------------------
    // Step 4: Parse raw data → simpan ke log_absensi
    // Format baris: nis tanggal jam status
    // -------------------------------------------------------------------------
    private function insertLog(string $rawData, int $idMesin): int
    {
        $rows     = explode("\n", trim($rawData));
        $inserted = 0;

        foreach ($rows as $row) {
            $cols = preg_split('/\s+/', trim($row));
            if (count($cols) < 4) {
                continue;
            }

            [$nis, $tanggal, $jam, $status] = $cols;

            // Skip jika NIS tidak valid
            if (!is_numeric($nis)) {
                continue;
            }

            $nisInt = (int) $nis;

            // Hindari duplikat di log_absensi
            $exists = DB::table('log_absensi')
                ->where('nis', $nisInt)
                ->where('tanggal', $tanggal)
                ->where('jam', $jam)
                ->exists();

            if ($exists) {
                continue;
            }

            // Tandai 'Belum Tersinkron' — akan diperbarui setelah proses sinkronisasi
            DB::table('log_absensi')->insert([
                'nis'        => $nisInt,
                'tanggal'    => $tanggal,
                'jam'        => $jam,
                'status'     => $status,
                'keterangan' => 'Belum Tersinkron',
            ]);

            $inserted++;
        }

        return $inserted;
    }

    // -------------------------------------------------------------------------
    // Step 5: Sinkronisasi log_absensi → presensi
    // -------------------------------------------------------------------------
    private function sinkronkan(): int
    {
        $logs   = LogAbsensi::orderBy('tanggal')->orderBy('jam')->get();
        $synced = 0;

        foreach ($logs as $log) {
            try {
                // Pastikan siswa terdaftar di user_siswa
                $siswaExists = UserSiswa::where('nis', $log->nis)->exists();
                if (!$siswaExists) {
                    continue;
                }

                // Cek apakah di tabel presensi belum ada data nis dan tanggal yang sama
                $sudahAda = DB::table('presensi')
                    ->where('nis', $log->nis)
                    ->whereDate('tanggal', $log->tanggal)
                    ->exists();

                if ($sudahAda) {
                    // Data sudah ada — tandai keterangan
                    DB::table('log_absensi')
                        ->where('id_presensi', $log->id_presensi)
                        ->update(['keterangan' => 'Data sudah ada']);
                    continue;
                }

                // Jika belum ada maka tambahkan ke presensi
                DB::table('presensi')->insert([
                    'nis'        => $log->nis,
                    'tanggal'    => $log->tanggal,
                    'jam'        => $log->jam,
                    'status'     => $log->status ?? '1',
                    'keterangan' => 'Mesin finger – otomatis',
                    'file'       => null,
                ]);

                // Tandai log sebagai tersinkron
                DB::table('log_absensi')
                    ->where('id_presensi', $log->id_presensi)
                    ->update(['keterangan' => 'Tersinkron']);

                $synced++;

            } catch (\Exception $e) {
                // Tandai log sebagai gagal
                DB::table('log_absensi')
                    ->where('id_presensi', $log->id_presensi)
                    ->update(['keterangan' => 'Gagal']);
                Log::error('[TarikDataFinger] Gagal sinkronisasi NIS ' . $log->nis . ': ' . $e->getMessage());
            }
        }

        return $synced;
    }
}
