<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// ──────────────────────────────────────────────────────────
// Jadwal Otomatis Mesin Finger
// ──────────────────────────────────────────────────────────

$sekolah = null;
try {
    $sekolah = Illuminate\Support\Facades\DB::table('sekolah')->where('id_sekolah', 1)->first();
} catch (\Exception $e) {
    // Database tidak dapat diakses atau belum dimigrasi
}

if ($sekolah && $sekolah->sync_otomatis) {
    $scheduleEvent = Schedule::command('finger:tarik');

    switch ($sekolah->sync_interval) {
        case '15':
            $scheduleEvent->everyFifteenMinutes();
            break;
        case '60':
            $scheduleEvent->everyHourly();
            break;
        case '120':
            $scheduleEvent->everyTwoHours();
            break;
        case 'daily':
            $time = $sekolah->sync_time ?: '00:00';
            $scheduleEvent->dailyAt($time);
            break;
        case '30':
        default:
            $scheduleEvent->everyThirtyMinutes();
            break;
    }

    $scheduleEvent->timezone('Asia/Jakarta')
        ->withoutOverlapping()
        ->appendOutputTo(storage_path('logs/finger-tarik.log'));
}

// Hapus log_absensi hari ini setiap hari jam 23:00 WIB
Schedule::command('finger:hapus-log')
    ->dailyAt('23:00')
    ->timezone('Asia/Jakarta')
    ->withoutOverlapping()
    ->appendOutputTo(storage_path('logs/finger-hapus.log'));

// Kirim WA Presensi Siswa Harian otomatis jam 09:00 WIB kecuali hari Sabtu dan Minggu (Senin-Jumat)
Schedule::command('wa:send-presensi-harian')
    ->weekdays()
    ->at('09:00')
    ->timezone('Asia/Jakarta')
    ->withoutOverlapping()
    ->appendOutputTo(storage_path('logs/wa-presensi-harian.log'));

