<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

$setting = App\Models\SettingPembayaran::getSetting();
$service = new App\Services\BpdDiyService($setting);

// Cek total remote dengan max_records = 10
$result = $service->fetchReportTagihanVa(['max_records' => 10]);
echo "BPD DIY Total Records: " . ($result['total'] ?? 0) . "\n";
echo "Database Lokal Total Tagihan: " . App\Models\TagihanPembayaran::count() . "\n";

echo "\n=== CEK SISWA AAN ADITYA DI DB LOKAL ===\n";
$siswaAan = App\Models\UserSiswa::where('nama_siswa', 'like', '%AAN ADITYA%')->first();
if ($siswaAan) {
    echo "Siswa: {$siswaAan->nama_siswa} | NIS: {$siswaAan->nis} | Kelas ID: {$siswaAan->id_kelas}\n";
    $tags = App\Models\TagihanPembayaran::where('nis', $siswaAan->nis)->get();
    echo "Tagihan di DB lokal dengan NIS {$siswaAan->nis}: " . $tags->count() . "\n";
    foreach ($tags as $t) {
        echo "  ID: {$t->id} | VA: {$t->nomor_va} | Jenis: {$t->jenis_tagihan} | Nominal: {$t->nominal} | Bayar: {$t->nominal_terbayar}\n";
    }

    $tagsNama = App\Models\TagihanPembayaran::where('nama_tagihan', 'like', '%AAN ADITYA%')->get();
    echo "Tagihan di DB lokal dengan nama like AAN ADITYA: " . $tagsNama->count() . "\n";
    foreach ($tagsNama as $t) {
        echo "  ID: {$t->id} | NIS tersimpan: {$t->nis} | VA: {$t->nomor_va} | Jenis: {$t->jenis_tagihan} | Nominal: {$t->nominal} | Bayar: {$t->nominal_terbayar}\n";
    }
}
