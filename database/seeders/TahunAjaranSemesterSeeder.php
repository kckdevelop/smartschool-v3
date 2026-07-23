<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class TahunAjaranSemesterSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Schema::disableForeignKeyConstraints();

        // Table: tahun_ajaran (1 rows)
        DB::table('tahun_ajaran')->truncate();
        DB::table('tahun_ajaran')->insert(array (
  0 => 
  array (
    'id_tahun' => 1,
    'tahun' => '2026/2027',
    'status' => 'aktif',
  ),
));

        // Table: semester (2 rows)
        DB::table('semester')->truncate();
        DB::table('semester')->insert(array (
  0 => 
  array (
    'id_semester' => 1,
    'id_tahun' => 1,
    'semester' => 'Ganjil',
    'awal' => '2026-07-01',
    'akhir' => '2026-12-31',
    'status' => 'aktif',
  ),
  1 => 
  array (
    'id_semester' => 2,
    'id_tahun' => 1,
    'semester' => 'Genap',
    'awal' => '2027-01-01',
    'akhir' => '2027-06-19',
    'status' => 'tidak',
  ),
));

        Schema::enableForeignKeyConstraints();
    }
}