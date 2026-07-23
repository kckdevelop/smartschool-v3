<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$siswa = \App\Models\UserSiswa::with('kelas')->where('nis', '13862')->first();
if ($siswa) {
    echo "NIS: {$siswa->nis}\n";
    echo "Nama: {$siswa->nama_siswa}\n";
    echo "ID Kelas: {$siswa->id_kelas}\n";
    echo "Nama Kelas: " . ($siswa->kelas ? $siswa->kelas->nama_kelas : 'null') . "\n";
} else {
    echo "Siswa not found.\n";
}
