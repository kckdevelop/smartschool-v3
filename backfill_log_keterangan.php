<?php
// Backfill keterangan di tabel log_absensi berdasarkan status sinkronisasi
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;

echo "Memproses backfill keterangan log_absensi...\n";

$tersinkron = 0;
$belumSinkron = 0;
$tidakTerdaftar = 0;
$total = 0;

DB::table('log_absensi')->orderBy('id_presensi')->chunk(200, function ($logs) use (&$tersinkron, &$belumSinkron, &$tidakTerdaftar, &$total) {
    foreach ($logs as $log) {
        $total++;
        $siswaAda = DB::table('user_siswa')->where('nis', $log->nis)->exists();

        if (!$siswaAda) {
            DB::table('log_absensi')
                ->where('id_presensi', $log->id_presensi)
                ->update(['keterangan' => 'Tidak Terdaftar']);
            $tidakTerdaftar++;
            continue;
        }

        $presensiAda = DB::table('presensi')
            ->where('nis', $log->nis)
            ->whereDate('tanggal', $log->tanggal)
            ->exists();

        if ($presensiAda) {
            DB::table('log_absensi')
                ->where('id_presensi', $log->id_presensi)
                ->update(['keterangan' => 'Tersinkron']);
            $tersinkron++;
        } else {
            DB::table('log_absensi')
                ->where('id_presensi', $log->id_presensi)
                ->update(['keterangan' => 'Belum Tersinkron']);
            $belumSinkron++;
        }
    }
    echo ".";
});

echo "\n\n✅ Selesai!\n";
echo "Total diproses   : $total\n";
echo "Tersinkron       : $tersinkron\n";
echo "Belum tersinkron : $belumSinkron\n";
echo "Tidak terdaftar  : $tidakTerdaftar\n";
