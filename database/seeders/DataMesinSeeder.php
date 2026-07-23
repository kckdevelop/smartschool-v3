<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class DataMesinSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Schema::disableForeignKeyConstraints();

        // Table: data_mesin (7 rows)
        DB::table('data_mesin')->truncate();
        DB::table('data_mesin')->insert(array (
  0 => 
  array (
    'id_mesin' => 1,
    'nama_mesin' => 'Unit 1 (Mesin 1)',
    'sn' => 'NJF7243603633',
    'password' => 'solution',
    'data' => 0,
    'last_update' => '2026-07-23 08:29:16',
  ),
  1 => 
  array (
    'id_mesin' => 2,
    'nama_mesin' => 'Unit 1 (Mesin 2)',
    'sn' => 'BWXP233560696',
    'password' => 'solution',
    'data' => 0,
    'last_update' => '2026-07-23 08:29:18',
  ),
  2 => 
  array (
    'id_mesin' => 3,
    'nama_mesin' => 'Unit 1 (Mesin 3)',
    'sn' => 'NJF7243603906',
    'password' => 'solution',
    'data' => 0,
    'last_update' => '2026-07-23 08:29:20',
  ),
  3 => 
  array (
    'id_mesin' => 4,
    'nama_mesin' => 'Unit 1 (Mesin 4)',
    'sn' => 'NJF7243600804',
    'password' => 'solution',
    'data' => 0,
    'last_update' => '2026-07-23 08:29:34',
  ),
  4 => 
  array (
    'id_mesin' => 5,
    'nama_mesin' => 'Unit 2 (Mesin 1)',
    'sn' => 'NJF7243600115',
    'password' => 'solution',
    'data' => 0,
    'last_update' => '2026-07-23 08:29:37',
  ),
  5 => 
  array (
    'id_mesin' => 6,
    'nama_mesin' => 'Unit 3 (Mesin 1)',
    'sn' => 'NJF7243603629',
    'password' => 'solution',
    'data' => 0,
    'last_update' => '2026-07-23 08:30:31',
  ),
  6 => 
  array (
    'id_mesin' => 7,
    'nama_mesin' => 'Unit 4 (Mesin 1)',
    'sn' => 'NJF7243601217',
    'password' => 'solution',
    'data' => 0,
    'last_update' => '2026-07-23 08:30:17',
  ),
));


        Schema::enableForeignKeyConstraints();
    }
}