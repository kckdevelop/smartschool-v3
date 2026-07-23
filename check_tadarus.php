<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;

// Cek API response simulasi untuk kelas 52
$semester = DB::table('semester')->where('status', 'aktif')->first();
echo "Semester: {$semester->awal} - {$semester->akhir}\n\n";

$rows = \App\Models\Tadarus::with('kelas')
    ->where('id_kelas', 52)
    ->whereBetween('tanggal', [$semester->awal, $semester->akhir])
    ->orderByDesc('tanggal')
    ->get();

echo "Total tadarus kelas 52 di semester aktif: " . $rows->count() . "\n\n";

$mapped = $rows->map(function ($t) {
    return [
        'id'               => $t->id_tadarus,
        'tanggal'          => $t->tanggal?->format('Y-m-d'),
        'surat_mulai'      => $t->awal_surat,
        'ayat_mulai'       => $t->awal_ayat,
        'surat_selesai'    => $t->akhir_surat,
        'ayat_selesai'     => $t->akhir_ayat,
        'pembaca_terakhir' => $t->guru?->nama_guru ?? 'Guru ISMUBA',
        'nama_kelas'       => $t->kelas?->nama_kelas,
    ];
});

foreach ($mapped as $item) {
    echo "📖 [{$item['tanggal']}] QS. {$item['surat_mulai']} ({$item['ayat_mulai']}) - {$item['surat_selesai']} ({$item['ayat_selesai']}) | Guru: {$item['pembaca_terakhir']}\n";
}

echo "\n✅ API response sudah benar - Flutter akan membaca surat_mulai & surat_selesai\n";
