<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class HapusLogAbsensi extends Command
{
    protected $signature   = 'finger:hapus-log {--tanggal= : Tanggal spesifik (Y-m-d), default hari ini}';
    protected $description = 'Hapus data log_absensi pada tanggal tertentu (default: hari ini)';

    public function handle(): int
    {
        $tanggal = $this->option('tanggal') ?? now()->toDateString();

        $this->info("🗑  Menghapus log_absensi tanggal: {$tanggal}");

        $deleted = DB::table('log_absensi')
            ->whereDate('tanggal', $tanggal)
            ->delete();

        $this->info("✅ {$deleted} baris log_absensi berhasil dihapus.");
        Log::info("[HapusLogAbsensi] {$deleted} baris log tanggal {$tanggal} dihapus.");

        return self::SUCCESS;
    }
}
