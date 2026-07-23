<?php
// Script untuk mendeteksi pola password user_siswa, guru, karyawan
require __DIR__ . '/../vendor/autoload.php';
$app = require __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;

echo "=== SISWA ===\n";
$siswaList = DB::select("SELECT nis, nama_siswa, password, password_wali, tgl_lahir FROM user_siswa WHERE status='aktif' LIMIT 10");

$commonPasswords = ['12345', '123456', '1234', 'password', 'smartschool', 'siswa'];

foreach ($siswaList as $s) {
    $candidates = [
        'NIS' => (string)$s->nis,
        'TGL_dmY' => $s->tgl_lahir ? date('dmY', strtotime($s->tgl_lahir)) : '',
        'TGL_d-m-Y' => $s->tgl_lahir ? date('d-m-Y', strtotime($s->tgl_lahir)) : '',
        'TGL_Ymd' => $s->tgl_lahir ? date('Ymd', strtotime($s->tgl_lahir)) : '',
    ];
    foreach ($commonPasswords as $cp) {
        $candidates[$cp] = $cp;
    }

    $found = 'unknown';
    foreach ($candidates as $label => $val) {
        if ($val && sha1($val) === $s->password) {
            $found = "password=$val ($label)";
            break;
        }
        if ($val && \Illuminate\Support\Facades\Hash::check($val, $s->password)) {
            $found = "password=$val (bcrypt/$label)";
            break;
        }
    }

    // Cek juga password_wali
    $foundWali = 'unknown';
    foreach ($candidates as $label => $val) {
        if ($val && sha1($val) === $s->password_wali) {
            $foundWali = "wali_pwd=$val ($label)";
            break;
        }
        if ($val && \Illuminate\Support\Facades\Hash::check($val, $s->password_wali)) {
            $foundWali = "wali_pwd=$val (bcrypt/$label)";
            break;
        }
    }

    echo "NIS: {$s->nis} | {$s->nama_siswa} | lahir: {$s->tgl_lahir} | {$found} | {$foundWali}\n";
}

echo "\n=== GURU ===\n";
$guruList = DB::select("SELECT id_guru, no_id, nama_guru, password FROM guru WHERE status='aktif' LIMIT 5");
foreach ($guruList as $g) {
    $candidates = [
        'no_id' => (string)$g->no_id,
    ];
    foreach ($commonPasswords as $cp) {
        $candidates[$cp] = $cp;
    }
    $found = 'unknown';
    foreach ($candidates as $label => $val) {
        if ($val && sha1($val) === $g->password) {
            $found = "password=$val ($label)";
            break;
        }
        if ($val && \Illuminate\Support\Facades\Hash::check($val, $g->password)) {
            $found = "password=$val (bcrypt/$label)";
            break;
        }
    }
    echo "ID_GURU: {$g->id_guru} | NO_ID: {$g->no_id} | {$g->nama_guru} | {$found}\n";
}

echo "\n=== KARYAWAN ===\n";
$karyawanList = DB::select("SELECT id_karyawan, no_id, nama_karyawan, password FROM karyawan WHERE status='aktif' LIMIT 5");
foreach ($karyawanList as $k) {
    $candidates = [
        'no_id' => (string)$k->no_id,
    ];
    foreach ($commonPasswords as $cp) {
        $candidates[$cp] = $cp;
    }
    $found = 'unknown';
    foreach ($candidates as $label => $val) {
        if ($val && sha1($val) === $k->password) {
            $found = "password=$val ($label)";
            break;
        }
        if ($val && \Illuminate\Support\Facades\Hash::check($val, $k->password)) {
            $found = "password=$val (bcrypt/$label)";
            break;
        }
    }
    echo "ID_KARYAWAN: {$k->id_karyawan} | NO_ID: {$k->no_id} | {$k->nama_karyawan} | {$found}\n";
}
