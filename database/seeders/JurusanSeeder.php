<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class JurusanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Schema::disableForeignKeyConstraints();

        // Table: jurusan (5 rows)
        DB::table('jurusan')->truncate();
        DB::table('jurusan')->insert(array (
  0 => 
  array (
    'id_jurusan' => 1,
    'kode_jurusan' => 'RPL',
    'nama_jurusan' => 'Rekayasa Perangkat Lunak',
    'status' => 'aktif',
  ),
  1 => 
  array (
    'id_jurusan' => 3,
    'kode_jurusan' => 'TSM',
    'nama_jurusan' => 'Teknik Sepeda Motor',
    'status' => 'aktif',
  ),
  2 => 
  array (
    'id_jurusan' => 4,
    'kode_jurusan' => 'TPM',
    'nama_jurusan' => 'Teknik Pemesinan',
    'status' => 'aktif',
  ),
  3 => 
  array (
    'id_jurusan' => 5,
    'kode_jurusan' => 'TAV',
    'nama_jurusan' => 'Teknik Audio Video',
    'status' => 'aktif',
  ),
  4 => 
  array (
    'id_jurusan' => 6,
    'kode_jurusan' => 'TKR',
    'nama_jurusan' => 'Teknik Kendaraan Ringan',
    'status' => 'aktif',
  ),
));

        Schema::enableForeignKeyConstraints();
    }
}