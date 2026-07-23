<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

$latestBtaq = DB::table('btaq')
    ->join('user_siswa', 'btaq.nis', '=', 'user_siswa.nis')
    ->where('user_siswa.status', 'aktif')
    ->select('btaq.nis', DB::raw('MAX(btaq.id_btaq) as latest_id'))
    ->groupBy('btaq.nis');

$btaqCounts = DB::table(DB::raw("({$latestBtaq->toSql()}) as latest"))
    ->mergeBindings($latestBtaq)
    ->join('btaq', 'btaq.id_btaq', '=', 'latest.latest_id')
    ->select('btaq.level', DB::raw('count(*) as total'))
    ->groupBy('btaq.level')
    ->get()
    ->pluck('total', 'level')
    ->toArray();

$btaqIqroCount = 0;
$btaqAlquranCount = 0;

foreach ($btaqCounts as $level => $total) {
    $lvlLower = strtolower($level);
    if (str_contains($lvlLower, 'iqro') || str_contains($lvlLower, 'iqra')) {
        $btaqIqroCount += $total;
    } elseif (str_contains($lvlLower, 'qur') || str_contains($lvlLower, 'quran')) {
        $btaqAlquranCount += $total;
    } else {
        $btaqIqroCount += $total;
    }
}

$totalSiswaAktif = DB::table('user_siswa')->where('status', 'aktif')->count();
$btaqKosongCount = max(0, $totalSiswaAktif - ($btaqIqroCount + $btaqAlquranCount));

$btaqLabels = ['Iqro', 'Alquran', 'Kosong'];
$btaqData = [$btaqIqroCount, $btaqAlquranCount, $btaqKosongCount];

echo "Labels: " . json_encode($btaqLabels) . PHP_EOL;
echo "Data: " . json_encode($btaqData) . PHP_EOL;
echo "Total: " . array_sum($btaqData) . PHP_EOL;
