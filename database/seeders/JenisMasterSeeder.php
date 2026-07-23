<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class JenisMasterSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Schema::disableForeignKeyConstraints();

        // Table: jenis_checkup (8 rows)
        DB::table('jenis_checkup')->truncate();
        DB::table('jenis_checkup')->insert(array (
  0 => 
  array (
    'id_checkup' => 1,
    'jenis_checkup' => 'Tekanan DarahTekanan Darah',
    'status' => 'aktif',
  ),
  1 => 
  array (
    'id_checkup' => 2,
    'jenis_checkup' => 'Penimbangan Berat Badan',
    'status' => 'aktif',
  ),
  2 => 
  array (
    'id_checkup' => 3,
    'jenis_checkup' => 'Pengukuran Tinggi Badan',
    'status' => 'aktif',
  ),
  3 => 
  array (
    'id_checkup' => 4,
    'jenis_checkup' => 'Tes Tajam Penglihatan',
    'status' => 'aktif',
  ),
  4 => 
  array (
    'id_checkup' => 5,
    'jenis_checkup' => 'Pemeriksaan Tekanan Darah',
    'status' => 'aktif',
  ),
  5 => 
  array (
    'id_checkup' => 6,
    'jenis_checkup' => 'Pemeriksaan Gigi & Mulut',
    'status' => 'aktif',
  ),
  6 => 
  array (
    'id_checkup' => 7,
    'jenis_checkup' => 'Pemeriksaan Golongan Darah',
    'status' => 'aktif',
  ),
  7 => 
  array (
    'id_checkup' => 8,
    'jenis_checkup' => 'Tes Hemoglobin (Hb)',
    'status' => 'aktif',
  ),
));

        // Table: jenis_pelanggaran (10 rows)
        DB::table('jenis_pelanggaran')->truncate();
        DB::table('jenis_pelanggaran')->insert(array (
  0 => 
  array (
    'id_jenis_pelanggaran' => 1,
    'jenis_pelanggaran' => 'Terlambat Masuk Sekolah',
    'poin' => 10,
  ),
  1 => 
  array (
    'id_jenis_pelanggaran' => 2,
    'jenis_pelanggaran' => 'Tidak Membawa Buku',
    'poin' => 10,
  ),
  2 => 
  array (
    'id_jenis_pelanggaran' => 3,
    'jenis_pelanggaran' => 'Seragam Tidak Lengkap',
    'poin' => 10,
  ),
  3 => 
  array (
    'id_jenis_pelanggaran' => 4,
    'jenis_pelanggaran' => 'Berkelahi',
    'poin' => 10,
  ),
  4 => 
  array (
    'id_jenis_pelanggaran' => 5,
    'jenis_pelanggaran' => 'Membawa HP Saat Pelajaran',
    'poin' => 10,
  ),
  5 => 
  array (
    'id_jenis_pelanggaran' => 6,
    'jenis_pelanggaran' => 'Membolos',
    'poin' => 10,
  ),
  6 => 
  array (
    'id_jenis_pelanggaran' => 7,
    'jenis_pelanggaran' => 'Merokok',
    'poin' => 10,
  ),
  7 => 
  array (
    'id_jenis_pelanggaran' => 8,
    'jenis_pelanggaran' => 'Tidak Mengerjakan PR',
    'poin' => 10,
  ),
  8 => 
  array (
    'id_jenis_pelanggaran' => 9,
    'jenis_pelanggaran' => 'Tidak Ikut Upacara',
    'poin' => 10,
  ),
  9 => 
  array (
    'id_jenis_pelanggaran' => 10,
    'jenis_pelanggaran' => 'Merusak Fasilitas Sekolah',
    'poin' => 10,
  ),
));


        Schema::enableForeignKeyConstraints();
    }
}