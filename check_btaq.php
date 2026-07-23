<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

// Count btaq entries per level (latest per student)
$totalSiswa = DB::table('user_siswa')->where('status', 'aktif')->count();
echo "Total siswa aktif: $totalSiswa" . PHP_EOL;

// Get latest btaq per student NIS
$latestBtaq = DB::table('btaq')
    ->select('nis', DB::raw('MAX(id_btaq) as latest_id'))
    ->groupBy('nis');

$statusCount = DB::table(DB::raw("({$latestBtaq->toSql()}) as latest"))
    ->mergeBindings($latestBtaq)
    ->join('btaq', 'btaq.id_btaq', '=', 'latest.latest_id')
    ->select('btaq.level', DB::raw('count(*) as total'))
    ->groupBy('btaq.level')
    ->get();

echo "Status count from latest btaq per student:" . PHP_EOL;
foreach ($statusCount as $s) {
    echo "  " . $s->level . ": " . $s->total . PHP_EOL;
}

$nisBtaq = DB::table('btaq')->distinct()->pluck('nis')->toArray();
$siswaWithoutBtaq = DB::table('user_siswa')
    ->where('status', 'aktif')
    ->whereNotIn('nis', $nisBtaq)
    ->count();
echo "  Kosong (no btaq): " . $siswaWithoutBtaq . PHP_EOL;
