<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class MapelJamPelajaranSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Schema::disableForeignKeyConstraints();

        // Table: mapel (20 rows)
        DB::table('mapel')->truncate();
        DB::table('mapel')->insert(array (
  0 => 
  array (
    'id_mapel' => 68,
    'kode_mapel' => 'PRPL',
    'nama_mapel' => 'Produktif RPL',
  ),
  1 => 
  array (
    'id_mapel' => 69,
    'kode_mapel' => 'PAI',
    'nama_mapel' => 'Pendidikan AL ISLAM',
  ),
  2 => 
  array (
    'id_mapel' => 70,
    'kode_mapel' => 'ARAB&KEMUH',
    'nama_mapel' => 'Pendidikan Bahasa Arab & kemuh',
  ),
  3 => 
  array (
    'id_mapel' => 71,
    'kode_mapel' => 'PPKn',
    'nama_mapel' => 'Pendidikan Pancasila dan Kewarganegaraan',
  ),
  4 => 
  array (
    'id_mapel' => 72,
    'kode_mapel' => 'BNDO',
    'nama_mapel' => 'Bahasa Indonesia',
  ),
  5 => 
  array (
    'id_mapel' => 73,
    'kode_mapel' => 'PJOK',
    'nama_mapel' => 'Pendidikan Jasmani Olah Raga dan Kesehatan',
  ),
  6 => 
  array (
    'id_mapel' => 74,
    'kode_mapel' => 'SJRH',
    'nama_mapel' => 'Sejarah',
  ),
  7 => 
  array (
    'id_mapel' => 75,
    'kode_mapel' => 'SNBDY',
    'nama_mapel' => 'Seni Budaya',
  ),
  8 => 
  array (
    'id_mapel' => 76,
    'kode_mapel' => 'BJAWA',
    'nama_mapel' => 'Muatan Lokal Bahasa Jawa',
  ),
  9 => 
  array (
    'id_mapel' => 77,
    'kode_mapel' => 'MTK',
    'nama_mapel' => 'Matematika',
  ),
  10 => 
  array (
    'id_mapel' => 78,
    'kode_mapel' => 'BING',
    'nama_mapel' => 'Bahasa Inggris',
  ),
  11 => 
  array (
    'id_mapel' => 79,
    'kode_mapel' => 'INF',
    'nama_mapel' => 'Informatika',
  ),
  12 => 
  array (
    'id_mapel' => 80,
    'kode_mapel' => 'IPAS',
    'nama_mapel' => 'Proyek Ilmu Pengetahuan Alam dan Sosial',
  ),
  13 => 
  array (
    'id_mapel' => 81,
    'kode_mapel' => 'KKA',
    'nama_mapel' => 'Koding dan Kecerdasan Artifisial',
  ),
  14 => 
  array (
    'id_mapel' => 82,
    'kode_mapel' => 'PTKR',
    'nama_mapel' => 'Produktif TKR',
  ),
  15 => 
  array (
    'id_mapel' => 83,
    'kode_mapel' => 'PTSM',
    'nama_mapel' => 'Produktif TSM',
  ),
  16 => 
  array (
    'id_mapel' => 84,
    'kode_mapel' => 'PTAV',
    'nama_mapel' => 'Produktif TAV',
  ),
  17 => 
  array (
    'id_mapel' => 85,
    'kode_mapel' => 'PTPM',
    'nama_mapel' => 'Produktif TPM',
  ),
  18 => 
  array (
    'id_mapel' => 86,
    'kode_mapel' => 'PKWU',
    'nama_mapel' => 'Projek Kreatif dan Kewirausahaan',
  ),
  19 => 
  array (
    'id_mapel' => 87,
    'kode_mapel' => 'PKL',
    'nama_mapel' => 'Praktik Kerja Lapangan (PKL)',
  ),
));

        // Table: jam_pelajaran (8 rows)
        DB::table('jam_pelajaran')->truncate();
        DB::table('jam_pelajaran')->insert(array (
  0 => 
  array (
    'id_jam' => 1,
    'jam_ke' => 1,
    'normal_mulai' => '08:00:00',
    'normal_selesai' => '08:40:00',
    'upacara_mulai' => '08:00:00',
    'upacara_selesai' => '08:40:00',
    'puasa_mulai' => '08:00:00',
    'puasa_selesai' => '08:40:00',
    'created_at' => NULL,
    'updated_at' => '2026-07-14 11:34:06',
  ),
  1 => 
  array (
    'id_jam' => 2,
    'jam_ke' => 2,
    'normal_mulai' => '08:40:00',
    'normal_selesai' => '09:20:00',
    'upacara_mulai' => '08:40:00',
    'upacara_selesai' => '09:20:00',
    'puasa_mulai' => '08:40:00',
    'puasa_selesai' => '09:20:00',
    'created_at' => NULL,
    'updated_at' => '2026-07-14 11:34:06',
  ),
  2 => 
  array (
    'id_jam' => 3,
    'jam_ke' => 3,
    'normal_mulai' => '09:20:00',
    'normal_selesai' => '10:00:00',
    'upacara_mulai' => '09:20:00',
    'upacara_selesai' => '10:00:00',
    'puasa_mulai' => '09:20:00',
    'puasa_selesai' => '10:00:00',
    'created_at' => NULL,
    'updated_at' => '2026-07-14 11:34:06',
  ),
  3 => 
  array (
    'id_jam' => 4,
    'jam_ke' => 4,
    'normal_mulai' => '10:20:00',
    'normal_selesai' => '11:00:00',
    'upacara_mulai' => '10:20:00',
    'upacara_selesai' => '11:00:00',
    'puasa_mulai' => '10:20:00',
    'puasa_selesai' => '11:00:00',
    'created_at' => NULL,
    'updated_at' => '2026-07-14 11:34:06',
  ),
  4 => 
  array (
    'id_jam' => 5,
    'jam_ke' => 5,
    'normal_mulai' => '11:00:00',
    'normal_selesai' => '11:40:00',
    'upacara_mulai' => '11:00:00',
    'upacara_selesai' => '11:40:00',
    'puasa_mulai' => '11:00:00',
    'puasa_selesai' => '11:40:00',
    'created_at' => NULL,
    'updated_at' => '2026-07-14 11:34:06',
  ),
  5 => 
  array (
    'id_jam' => 6,
    'jam_ke' => 6,
    'normal_mulai' => '12:20:00',
    'normal_selesai' => '13:00:00',
    'upacara_mulai' => '12:20:00',
    'upacara_selesai' => '13:00:00',
    'puasa_mulai' => '12:20:00',
    'puasa_selesai' => '13:00:00',
    'created_at' => NULL,
    'updated_at' => '2026-07-14 11:34:06',
  ),
  6 => 
  array (
    'id_jam' => 7,
    'jam_ke' => 7,
    'normal_mulai' => '13:00:00',
    'normal_selesai' => '13:40:00',
    'upacara_mulai' => '13:00:00',
    'upacara_selesai' => '13:40:00',
    'puasa_mulai' => '13:00:00',
    'puasa_selesai' => '13:40:00',
    'created_at' => NULL,
    'updated_at' => '2026-07-14 11:34:06',
  ),
  7 => 
  array (
    'id_jam' => 8,
    'jam_ke' => 8,
    'normal_mulai' => '13:40:00',
    'normal_selesai' => '14:20:00',
    'upacara_mulai' => '13:40:00',
    'upacara_selesai' => '14:20:00',
    'puasa_mulai' => '13:40:00',
    'puasa_selesai' => '14:20:00',
    'created_at' => NULL,
    'updated_at' => '2026-07-14 11:34:06',
  ),
));


        Schema::enableForeignKeyConstraints();
    }
}