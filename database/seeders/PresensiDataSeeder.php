<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class PresensiDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Schema::disableForeignKeyConstraints();

        // Table: presensi (3544 rows)
        DB::table('presensi')->truncate();
        DB::table('presensi')->insert(array (
  0 => 
  array (
    'id_presensi' => 2561,
    'nis' => 14570,
    'tanggal' => '2026-07-14',
    'jam' => '06:14:50',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  1 => 
  array (
    'id_presensi' => 2562,
    'nis' => 14378,
    'tanggal' => '2026-07-14',
    'jam' => '06:14:56',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  2 => 
  array (
    'id_presensi' => 2563,
    'nis' => 14774,
    'tanggal' => '2026-07-14',
    'jam' => '06:15:59',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  3 => 
  array (
    'id_presensi' => 2564,
    'nis' => 14213,
    'tanggal' => '2026-07-14',
    'jam' => '06:16:51',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  4 => 
  array (
    'id_presensi' => 2565,
    'nis' => 14759,
    'tanggal' => '2026-07-14',
    'jam' => '06:29:21',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  5 => 
  array (
    'id_presensi' => 2566,
    'nis' => 14379,
    'tanggal' => '2026-07-14',
    'jam' => '06:29:38',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  6 => 
  array (
    'id_presensi' => 2567,
    'nis' => 14744,
    'tanggal' => '2026-07-14',
    'jam' => '06:32:12',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  7 => 
  array (
    'id_presensi' => 2568,
    'nis' => 14630,
    'tanggal' => '2026-07-14',
    'jam' => '06:35:40',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  8 => 
  array (
    'id_presensi' => 2569,
    'nis' => 14170,
    'tanggal' => '2026-07-14',
    'jam' => '06:38:32',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  9 => 
  array (
    'id_presensi' => 2570,
    'nis' => 14631,
    'tanggal' => '2026-07-14',
    'jam' => '06:38:35',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  10 => 
  array (
    'id_presensi' => 2571,
    'nis' => 14227,
    'tanggal' => '2026-07-14',
    'jam' => '06:39:11',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  11 => 
  array (
    'id_presensi' => 2572,
    'nis' => 14290,
    'tanggal' => '2026-07-14',
    'jam' => '06:39:55',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  12 => 
  array (
    'id_presensi' => 2573,
    'nis' => 14762,
    'tanggal' => '2026-07-14',
    'jam' => '06:40:05',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  13 => 
  array (
    'id_presensi' => 2574,
    'nis' => 14546,
    'tanggal' => '2026-07-14',
    'jam' => '06:40:09',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  14 => 
  array (
    'id_presensi' => 2575,
    'nis' => 14567,
    'tanggal' => '2026-07-14',
    'jam' => '06:40:11',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  15 => 
  array (
    'id_presensi' => 2576,
    'nis' => 14763,
    'tanggal' => '2026-07-14',
    'jam' => '06:40:18',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  16 => 
  array (
    'id_presensi' => 2577,
    'nis' => 14573,
    'tanggal' => '2026-07-14',
    'jam' => '06:40:21',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  17 => 
  array (
    'id_presensi' => 2578,
    'nis' => 14302,
    'tanggal' => '2026-07-14',
    'jam' => '06:40:24',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  18 => 
  array (
    'id_presensi' => 2579,
    'nis' => 14741,
    'tanggal' => '2026-07-14',
    'jam' => '06:41:32',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  19 => 
  array (
    'id_presensi' => 2580,
    'nis' => 14547,
    'tanggal' => '2026-07-14',
    'jam' => '06:41:57',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  20 => 
  array (
    'id_presensi' => 2581,
    'nis' => 14555,
    'tanggal' => '2026-07-14',
    'jam' => '06:42:00',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  21 => 
  array (
    'id_presensi' => 2582,
    'nis' => 14568,
    'tanggal' => '2026-07-14',
    'jam' => '06:42:02',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  22 => 
  array (
    'id_presensi' => 2583,
    'nis' => 14575,
    'tanggal' => '2026-07-14',
    'jam' => '06:42:42',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  23 => 
  array (
    'id_presensi' => 2584,
    'nis' => 14518,
    'tanggal' => '2026-07-14',
    'jam' => '06:43:07',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  24 => 
  array (
    'id_presensi' => 2585,
    'nis' => 14749,
    'tanggal' => '2026-07-14',
    'jam' => '06:43:16',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  25 => 
  array (
    'id_presensi' => 2586,
    'nis' => 14745,
    'tanggal' => '2026-07-14',
    'jam' => '06:43:33',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  26 => 
  array (
    'id_presensi' => 2587,
    'nis' => 14286,
    'tanggal' => '2026-07-14',
    'jam' => '06:43:39',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  27 => 
  array (
    'id_presensi' => 2588,
    'nis' => 14625,
    'tanggal' => '2026-07-14',
    'jam' => '06:43:42',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  28 => 
  array (
    'id_presensi' => 2589,
    'nis' => 14621,
    'tanggal' => '2026-07-14',
    'jam' => '06:43:48',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  29 => 
  array (
    'id_presensi' => 2590,
    'nis' => 14641,
    'tanggal' => '2026-07-14',
    'jam' => '06:43:58',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  30 => 
  array (
    'id_presensi' => 2591,
    'nis' => 14634,
    'tanggal' => '2026-07-14',
    'jam' => '06:44:01',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  31 => 
  array (
    'id_presensi' => 2592,
    'nis' => 14340,
    'tanggal' => '2026-07-14',
    'jam' => '06:44:04',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  32 => 
  array (
    'id_presensi' => 2593,
    'nis' => 14317,
    'tanggal' => '2026-07-14',
    'jam' => '06:44:11',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  33 => 
  array (
    'id_presensi' => 2594,
    'nis' => 14380,
    'tanggal' => '2026-07-14',
    'jam' => '06:44:16',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  34 => 
  array (
    'id_presensi' => 2595,
    'nis' => 14386,
    'tanggal' => '2026-07-14',
    'jam' => '06:44:22',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  35 => 
  array (
    'id_presensi' => 2596,
    'nis' => 14756,
    'tanggal' => '2026-07-14',
    'jam' => '06:45:09',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  36 => 
  array (
    'id_presensi' => 2597,
    'nis' => 13927,
    'tanggal' => '2026-07-14',
    'jam' => '06:45:21',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  37 => 
  array (
    'id_presensi' => 2598,
    'nis' => 13915,
    'tanggal' => '2026-07-14',
    'jam' => '06:45:26',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  38 => 
  array (
    'id_presensi' => 2599,
    'nis' => 14608,
    'tanggal' => '2026-07-14',
    'jam' => '06:45:31',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  39 => 
  array (
    'id_presensi' => 2600,
    'nis' => 13891,
    'tanggal' => '2026-07-14',
    'jam' => '06:46:02',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  40 => 
  array (
    'id_presensi' => 2601,
    'nis' => 14747,
    'tanggal' => '2026-07-14',
    'jam' => '06:46:12',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  41 => 
  array (
    'id_presensi' => 2602,
    'nis' => 14339,
    'tanggal' => '2026-07-14',
    'jam' => '06:46:14',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  42 => 
  array (
    'id_presensi' => 2603,
    'nis' => 14294,
    'tanggal' => '2026-07-14',
    'jam' => '06:46:17',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  43 => 
  array (
    'id_presensi' => 2604,
    'nis' => 14313,
    'tanggal' => '2026-07-14',
    'jam' => '06:46:22',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  44 => 
  array (
    'id_presensi' => 2605,
    'nis' => 14605,
    'tanggal' => '2026-07-14',
    'jam' => '06:46:41',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  45 => 
  array (
    'id_presensi' => 2606,
    'nis' => 14576,
    'tanggal' => '2026-07-14',
    'jam' => '06:46:47',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  46 => 
  array (
    'id_presensi' => 2607,
    'nis' => 14153,
    'tanggal' => '2026-07-14',
    'jam' => '06:46:48',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  47 => 
  array (
    'id_presensi' => 2608,
    'nis' => 14571,
    'tanggal' => '2026-07-14',
    'jam' => '06:46:50',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  48 => 
  array (
    'id_presensi' => 2609,
    'nis' => 13906,
    'tanggal' => '2026-07-14',
    'jam' => '06:46:59',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  49 => 
  array (
    'id_presensi' => 2610,
    'nis' => 14770,
    'tanggal' => '2026-07-14',
    'jam' => '06:47:05',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  50 => 
  array (
    'id_presensi' => 2611,
    'nis' => 14769,
    'tanggal' => '2026-07-14',
    'jam' => '06:47:49',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  51 => 
  array (
    'id_presensi' => 2612,
    'nis' => 14311,
    'tanggal' => '2026-07-14',
    'jam' => '06:48:10',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  52 => 
  array (
    'id_presensi' => 2613,
    'nis' => 14162,
    'tanggal' => '2026-07-14',
    'jam' => '06:48:27',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  53 => 
  array (
    'id_presensi' => 2614,
    'nis' => 14549,
    'tanggal' => '2026-07-14',
    'jam' => '06:48:28',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  54 => 
  array (
    'id_presensi' => 2615,
    'nis' => 14766,
    'tanggal' => '2026-07-14',
    'jam' => '06:48:35',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  55 => 
  array (
    'id_presensi' => 2616,
    'nis' => 13902,
    'tanggal' => '2026-07-14',
    'jam' => '06:48:37',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  56 => 
  array (
    'id_presensi' => 2617,
    'nis' => 14385,
    'tanggal' => '2026-07-14',
    'jam' => '06:48:39',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  57 => 
  array (
    'id_presensi' => 2618,
    'nis' => 14381,
    'tanggal' => '2026-07-14',
    'jam' => '06:48:42',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  58 => 
  array (
    'id_presensi' => 2619,
    'nis' => 14753,
    'tanggal' => '2026-07-14',
    'jam' => '06:48:45',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  59 => 
  array (
    'id_presensi' => 2620,
    'nis' => 13868,
    'tanggal' => '2026-07-14',
    'jam' => '06:49:01',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  60 => 
  array (
    'id_presensi' => 2621,
    'nis' => 14548,
    'tanggal' => '2026-07-14',
    'jam' => '06:49:08',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  61 => 
  array (
    'id_presensi' => 2622,
    'nis' => 14611,
    'tanggal' => '2026-07-14',
    'jam' => '06:49:15',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  62 => 
  array (
    'id_presensi' => 2623,
    'nis' => 14639,
    'tanggal' => '2026-07-14',
    'jam' => '06:49:17',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  63 => 
  array (
    'id_presensi' => 2624,
    'nis' => 14382,
    'tanggal' => '2026-07-14',
    'jam' => '06:49:25',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  64 => 
  array (
    'id_presensi' => 2625,
    'nis' => 14633,
    'tanggal' => '2026-07-14',
    'jam' => '06:49:35',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  65 => 
  array (
    'id_presensi' => 2626,
    'nis' => 14115,
    'tanggal' => '2026-07-14',
    'jam' => '06:49:39',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  66 => 
  array (
    'id_presensi' => 2627,
    'nis' => 14617,
    'tanggal' => '2026-07-14',
    'jam' => '06:49:59',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  67 => 
  array (
    'id_presensi' => 2628,
    'nis' => 14612,
    'tanggal' => '2026-07-14',
    'jam' => '06:50:04',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  68 => 
  array (
    'id_presensi' => 2629,
    'nis' => 14160,
    'tanggal' => '2026-07-14',
    'jam' => '06:50:13',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  69 => 
  array (
    'id_presensi' => 2630,
    'nis' => 13874,
    'tanggal' => '2026-07-14',
    'jam' => '06:50:43',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  70 => 
  array (
    'id_presensi' => 2631,
    'nis' => 14291,
    'tanggal' => '2026-07-14',
    'jam' => '06:51:00',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  71 => 
  array (
    'id_presensi' => 2632,
    'nis' => 14417,
    'tanggal' => '2026-07-14',
    'jam' => '06:51:11',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  72 => 
  array (
    'id_presensi' => 2633,
    'nis' => 14405,
    'tanggal' => '2026-07-14',
    'jam' => '06:51:12',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  73 => 
  array (
    'id_presensi' => 2634,
    'nis' => 14389,
    'tanggal' => '2026-07-14',
    'jam' => '06:51:16',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  74 => 
  array (
    'id_presensi' => 2635,
    'nis' => 14413,
    'tanggal' => '2026-07-14',
    'jam' => '06:51:20',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  75 => 
  array (
    'id_presensi' => 2636,
    'nis' => 14316,
    'tanggal' => '2026-07-14',
    'jam' => '06:51:24',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  76 => 
  array (
    'id_presensi' => 2637,
    'nis' => 14395,
    'tanggal' => '2026-07-14',
    'jam' => '06:51:26',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  77 => 
  array (
    'id_presensi' => 2638,
    'nis' => 14752,
    'tanggal' => '2026-07-14',
    'jam' => '06:51:31',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  78 => 
  array (
    'id_presensi' => 2639,
    'nis' => 14403,
    'tanggal' => '2026-07-14',
    'jam' => '06:51:31',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  79 => 
  array (
    'id_presensi' => 2640,
    'nis' => 14397,
    'tanggal' => '2026-07-14',
    'jam' => '06:51:36',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  80 => 
  array (
    'id_presensi' => 2641,
    'nis' => 14396,
    'tanggal' => '2026-07-14',
    'jam' => '06:51:48',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  81 => 
  array (
    'id_presensi' => 2642,
    'nis' => 14559,
    'tanggal' => '2026-07-14',
    'jam' => '06:51:51',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  82 => 
  array (
    'id_presensi' => 2643,
    'nis' => 13864,
    'tanggal' => '2026-07-14',
    'jam' => '06:51:55',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  83 => 
  array (
    'id_presensi' => 2644,
    'nis' => 14623,
    'tanggal' => '2026-07-14',
    'jam' => '06:51:56',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  84 => 
  array (
    'id_presensi' => 2645,
    'nis' => 14525,
    'tanggal' => '2026-07-14',
    'jam' => '06:51:57',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  85 => 
  array (
    'id_presensi' => 2646,
    'nis' => 14391,
    'tanggal' => '2026-07-14',
    'jam' => '06:52:00',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  86 => 
  array (
    'id_presensi' => 2647,
    'nis' => 13877,
    'tanggal' => '2026-07-14',
    'jam' => '06:52:01',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  87 => 
  array (
    'id_presensi' => 2648,
    'nis' => 14805,
    'tanggal' => '2026-07-14',
    'jam' => '06:52:08',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  88 => 
  array (
    'id_presensi' => 2649,
    'nis' => 14561,
    'tanggal' => '2026-07-14',
    'jam' => '06:52:11',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  89 => 
  array (
    'id_presensi' => 2650,
    'nis' => 14298,
    'tanggal' => '2026-07-14',
    'jam' => '06:52:16',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  90 => 
  array (
    'id_presensi' => 2651,
    'nis' => 14297,
    'tanggal' => '2026-07-14',
    'jam' => '06:52:19',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  91 => 
  array (
    'id_presensi' => 2652,
    'nis' => 14771,
    'tanggal' => '2026-07-14',
    'jam' => '06:52:22',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  92 => 
  array (
    'id_presensi' => 2653,
    'nis' => 14414,
    'tanggal' => '2026-07-14',
    'jam' => '06:52:24',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  93 => 
  array (
    'id_presensi' => 2654,
    'nis' => 14651,
    'tanggal' => '2026-07-14',
    'jam' => '06:52:28',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  94 => 
  array (
    'id_presensi' => 2655,
    'nis' => 14375,
    'tanggal' => '2026-07-14',
    'jam' => '06:52:31',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  95 => 
  array (
    'id_presensi' => 2656,
    'nis' => 14531,
    'tanggal' => '2026-07-14',
    'jam' => '06:52:33',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  96 => 
  array (
    'id_presensi' => 2657,
    'nis' => 13910,
    'tanggal' => '2026-07-14',
    'jam' => '06:52:35',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  97 => 
  array (
    'id_presensi' => 2658,
    'nis' => 14519,
    'tanggal' => '2026-07-14',
    'jam' => '06:52:38',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  98 => 
  array (
    'id_presensi' => 2659,
    'nis' => 14538,
    'tanggal' => '2026-07-14',
    'jam' => '06:52:40',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  99 => 
  array (
    'id_presensi' => 2660,
    'nis' => 14671,
    'tanggal' => '2026-07-14',
    'jam' => '06:52:41',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  100 => 
  array (
    'id_presensi' => 2661,
    'nis' => 14657,
    'tanggal' => '2026-07-14',
    'jam' => '06:52:43',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  101 => 
  array (
    'id_presensi' => 2662,
    'nis' => 14303,
    'tanggal' => '2026-07-14',
    'jam' => '06:52:48',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  102 => 
  array (
    'id_presensi' => 2663,
    'nis' => 14577,
    'tanggal' => '2026-07-14',
    'jam' => '06:52:50',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  103 => 
  array (
    'id_presensi' => 2664,
    'nis' => 14751,
    'tanggal' => '2026-07-14',
    'jam' => '06:52:50',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  104 => 
  array (
    'id_presensi' => 2665,
    'nis' => 14331,
    'tanggal' => '2026-07-14',
    'jam' => '06:53:05',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  105 => 
  array (
    'id_presensi' => 2666,
    'nis' => 14023,
    'tanggal' => '2026-07-14',
    'jam' => '06:53:17',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  106 => 
  array (
    'id_presensi' => 2667,
    'nis' => 14793,
    'tanggal' => '2026-07-14',
    'jam' => '06:53:22',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  107 => 
  array (
    'id_presensi' => 2668,
    'nis' => 14022,
    'tanggal' => '2026-07-14',
    'jam' => '06:53:26',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  108 => 
  array (
    'id_presensi' => 2669,
    'nis' => 14321,
    'tanggal' => '2026-07-14',
    'jam' => '06:53:27',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  109 => 
  array (
    'id_presensi' => 2670,
    'nis' => 14796,
    'tanggal' => '2026-07-14',
    'jam' => '06:53:28',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  110 => 
  array (
    'id_presensi' => 2671,
    'nis' => 14795,
    'tanggal' => '2026-07-14',
    'jam' => '06:53:30',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  111 => 
  array (
    'id_presensi' => 2672,
    'nis' => 14026,
    'tanggal' => '2026-07-14',
    'jam' => '06:53:33',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  112 => 
  array (
    'id_presensi' => 2673,
    'nis' => 14779,
    'tanggal' => '2026-07-14',
    'jam' => '06:53:34',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  113 => 
  array (
    'id_presensi' => 2674,
    'nis' => 14785,
    'tanggal' => '2026-07-14',
    'jam' => '06:53:38',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  114 => 
  array (
    'id_presensi' => 2675,
    'nis' => 14786,
    'tanggal' => '2026-07-14',
    'jam' => '06:53:43',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  115 => 
  array (
    'id_presensi' => 2676,
    'nis' => 14767,
    'tanggal' => '2026-07-14',
    'jam' => '06:53:49',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  116 => 
  array (
    'id_presensi' => 2677,
    'nis' => 14399,
    'tanggal' => '2026-07-14',
    'jam' => '06:53:52',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  117 => 
  array (
    'id_presensi' => 2678,
    'nis' => 14412,
    'tanggal' => '2026-07-14',
    'jam' => '06:53:54',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  118 => 
  array (
    'id_presensi' => 2679,
    'nis' => 14406,
    'tanggal' => '2026-07-14',
    'jam' => '06:53:54',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  119 => 
  array (
    'id_presensi' => 2680,
    'nis' => 14777,
    'tanggal' => '2026-07-14',
    'jam' => '06:53:55',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  120 => 
  array (
    'id_presensi' => 2681,
    'nis' => 14416,
    'tanggal' => '2026-07-14',
    'jam' => '06:53:56',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  121 => 
  array (
    'id_presensi' => 2682,
    'nis' => 13912,
    'tanggal' => '2026-07-14',
    'jam' => '06:53:59',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  122 => 
  array (
    'id_presensi' => 2683,
    'nis' => 14803,
    'tanggal' => '2026-07-14',
    'jam' => '06:54:02',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  123 => 
  array (
    'id_presensi' => 2684,
    'nis' => 13911,
    'tanggal' => '2026-07-14',
    'jam' => '06:54:03',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  124 => 
  array (
    'id_presensi' => 2685,
    'nis' => 14800,
    'tanggal' => '2026-07-14',
    'jam' => '06:54:05',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  125 => 
  array (
    'id_presensi' => 2686,
    'nis' => 14798,
    'tanggal' => '2026-07-14',
    'jam' => '06:54:08',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  126 => 
  array (
    'id_presensi' => 2687,
    'nis' => 14296,
    'tanggal' => '2026-07-14',
    'jam' => '06:54:09',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  127 => 
  array (
    'id_presensi' => 2688,
    'nis' => 14615,
    'tanggal' => '2026-07-14',
    'jam' => '06:54:12',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  128 => 
  array (
    'id_presensi' => 2689,
    'nis' => 14312,
    'tanggal' => '2026-07-14',
    'jam' => '06:54:14',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  129 => 
  array (
    'id_presensi' => 2690,
    'nis' => 14647,
    'tanggal' => '2026-07-14',
    'jam' => '06:54:17',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  130 => 
  array (
    'id_presensi' => 2691,
    'nis' => 14782,
    'tanggal' => '2026-07-14',
    'jam' => '06:54:19',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  131 => 
  array (
    'id_presensi' => 2692,
    'nis' => 14335,
    'tanggal' => '2026-07-14',
    'jam' => '06:54:21',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  132 => 
  array (
    'id_presensi' => 2693,
    'nis' => 14330,
    'tanggal' => '2026-07-14',
    'jam' => '06:54:24',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  133 => 
  array (
    'id_presensi' => 2694,
    'nis' => 14327,
    'tanggal' => '2026-07-14',
    'jam' => '06:54:27',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  134 => 
  array (
    'id_presensi' => 2695,
    'nis' => 14661,
    'tanggal' => '2026-07-14',
    'jam' => '06:54:30',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  135 => 
  array (
    'id_presensi' => 2696,
    'nis' => 13875,
    'tanggal' => '2026-07-14',
    'jam' => '06:54:33',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  136 => 
  array (
    'id_presensi' => 2697,
    'nis' => 14653,
    'tanggal' => '2026-07-14',
    'jam' => '06:54:33',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  137 => 
  array (
    'id_presensi' => 2698,
    'nis' => 14318,
    'tanggal' => '2026-07-14',
    'jam' => '06:54:34',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  138 => 
  array (
    'id_presensi' => 2699,
    'nis' => 14804,
    'tanggal' => '2026-07-14',
    'jam' => '06:54:36',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  139 => 
  array (
    'id_presensi' => 2700,
    'nis' => 13872,
    'tanggal' => '2026-07-14',
    'jam' => '06:54:37',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  140 => 
  array (
    'id_presensi' => 2701,
    'nis' => 14018,
    'tanggal' => '2026-07-14',
    'jam' => '06:54:39',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  141 => 
  array (
    'id_presensi' => 2702,
    'nis' => 14167,
    'tanggal' => '2026-07-14',
    'jam' => '06:54:40',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  142 => 
  array (
    'id_presensi' => 2703,
    'nis' => 14618,
    'tanggal' => '2026-07-14',
    'jam' => '06:54:40',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  143 => 
  array (
    'id_presensi' => 2704,
    'nis' => 13866,
    'tanggal' => '2026-07-14',
    'jam' => '06:54:41',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  144 => 
  array (
    'id_presensi' => 2705,
    'nis' => 14164,
    'tanggal' => '2026-07-14',
    'jam' => '06:54:42',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  145 => 
  array (
    'id_presensi' => 2706,
    'nis' => 14544,
    'tanggal' => '2026-07-14',
    'jam' => '06:54:45',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  146 => 
  array (
    'id_presensi' => 2707,
    'nis' => 14662,
    'tanggal' => '2026-07-14',
    'jam' => '06:54:47',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  147 => 
  array (
    'id_presensi' => 2708,
    'nis' => 14161,
    'tanggal' => '2026-07-14',
    'jam' => '06:54:50',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  148 => 
  array (
    'id_presensi' => 2709,
    'nis' => 14624,
    'tanggal' => '2026-07-14',
    'jam' => '06:54:51',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  149 => 
  array (
    'id_presensi' => 2710,
    'nis' => 14142,
    'tanggal' => '2026-07-14',
    'jam' => '06:54:52',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  150 => 
  array (
    'id_presensi' => 2711,
    'nis' => 14646,
    'tanggal' => '2026-07-14',
    'jam' => '06:55:00',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  151 => 
  array (
    'id_presensi' => 2712,
    'nis' => 14552,
    'tanggal' => '2026-07-14',
    'jam' => '06:55:07',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  152 => 
  array (
    'id_presensi' => 2713,
    'nis' => 14550,
    'tanggal' => '2026-07-14',
    'jam' => '06:55:09',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  153 => 
  array (
    'id_presensi' => 2714,
    'nis' => 14545,
    'tanggal' => '2026-07-14',
    'jam' => '06:55:12',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  154 => 
  array (
    'id_presensi' => 2715,
    'nis' => 14554,
    'tanggal' => '2026-07-14',
    'jam' => '06:55:14',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  155 => 
  array (
    'id_presensi' => 2716,
    'nis' => 14542,
    'tanggal' => '2026-07-14',
    'jam' => '06:55:16',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  156 => 
  array (
    'id_presensi' => 2717,
    'nis' => 14144,
    'tanggal' => '2026-07-14',
    'jam' => '06:55:17',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  157 => 
  array (
    'id_presensi' => 2718,
    'nis' => 14173,
    'tanggal' => '2026-07-14',
    'jam' => '06:55:19',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  158 => 
  array (
    'id_presensi' => 2719,
    'nis' => 14168,
    'tanggal' => '2026-07-14',
    'jam' => '06:55:22',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  159 => 
  array (
    'id_presensi' => 2720,
    'nis' => 14801,
    'tanggal' => '2026-07-14',
    'jam' => '06:55:34',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  160 => 
  array (
    'id_presensi' => 2721,
    'nis' => 14376,
    'tanggal' => '2026-07-14',
    'jam' => '06:55:45',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  161 => 
  array (
    'id_presensi' => 2722,
    'nis' => 14784,
    'tanggal' => '2026-07-14',
    'jam' => '06:55:48',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  162 => 
  array (
    'id_presensi' => 2723,
    'nis' => 14765,
    'tanggal' => '2026-07-14',
    'jam' => '06:55:51',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  163 => 
  array (
    'id_presensi' => 2724,
    'nis' => 14558,
    'tanggal' => '2026-07-14',
    'jam' => '06:56:02',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  164 => 
  array (
    'id_presensi' => 2725,
    'nis' => 14635,
    'tanggal' => '2026-07-14',
    'jam' => '06:56:11',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  165 => 
  array (
    'id_presensi' => 2726,
    'nis' => 14772,
    'tanggal' => '2026-07-14',
    'jam' => '06:56:12',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  166 => 
  array (
    'id_presensi' => 2727,
    'nis' => 14629,
    'tanggal' => '2026-07-14',
    'jam' => '06:56:14',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  167 => 
  array (
    'id_presensi' => 2728,
    'nis' => 14764,
    'tanggal' => '2026-07-14',
    'jam' => '06:56:14',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  168 => 
  array (
    'id_presensi' => 2729,
    'nis' => 14746,
    'tanggal' => '2026-07-14',
    'jam' => '06:56:15',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  169 => 
  array (
    'id_presensi' => 2730,
    'nis' => 14214,
    'tanggal' => '2026-07-14',
    'jam' => '06:56:17',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  170 => 
  array (
    'id_presensi' => 2731,
    'nis' => 14123,
    'tanggal' => '2026-07-14',
    'jam' => '06:56:19',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  171 => 
  array (
    'id_presensi' => 2732,
    'nis' => 13980,
    'tanggal' => '2026-07-14',
    'jam' => '06:56:20',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  172 => 
  array (
    'id_presensi' => 2733,
    'nis' => 14121,
    'tanggal' => '2026-07-14',
    'jam' => '06:56:22',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  173 => 
  array (
    'id_presensi' => 2734,
    'nis' => 13969,
    'tanggal' => '2026-07-14',
    'jam' => '06:56:23',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  174 => 
  array (
    'id_presensi' => 2735,
    'nis' => 14238,
    'tanggal' => '2026-07-14',
    'jam' => '06:56:24',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  175 => 
  array (
    'id_presensi' => 2736,
    'nis' => 14230,
    'tanggal' => '2026-07-14',
    'jam' => '06:56:27',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  176 => 
  array (
    'id_presensi' => 2737,
    'nis' => 14134,
    'tanggal' => '2026-07-14',
    'jam' => '06:56:28',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  177 => 
  array (
    'id_presensi' => 2738,
    'nis' => 13968,
    'tanggal' => '2026-07-14',
    'jam' => '06:56:28',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  178 => 
  array (
    'id_presensi' => 2739,
    'nis' => 14217,
    'tanggal' => '2026-07-14',
    'jam' => '06:56:30',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  179 => 
  array (
    'id_presensi' => 2740,
    'nis' => 14119,
    'tanggal' => '2026-07-14',
    'jam' => '06:56:30',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  180 => 
  array (
    'id_presensi' => 2741,
    'nis' => 14323,
    'tanggal' => '2026-07-14',
    'jam' => '06:56:31',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  181 => 
  array (
    'id_presensi' => 2742,
    'nis' => 14788,
    'tanggal' => '2026-07-14',
    'jam' => '06:56:32',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  182 => 
  array (
    'id_presensi' => 2743,
    'nis' => 14261,
    'tanggal' => '2026-07-14',
    'jam' => '06:56:33',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  183 => 
  array (
    'id_presensi' => 2744,
    'nis' => 14787,
    'tanggal' => '2026-07-14',
    'jam' => '06:56:34',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  184 => 
  array (
    'id_presensi' => 2745,
    'nis' => 14235,
    'tanggal' => '2026-07-14',
    'jam' => '06:56:37',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  185 => 
  array (
    'id_presensi' => 2746,
    'nis' => 13922,
    'tanggal' => '2026-07-14',
    'jam' => '06:56:37',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  186 => 
  array (
    'id_presensi' => 2747,
    'nis' => 14222,
    'tanggal' => '2026-07-14',
    'jam' => '06:56:39',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  187 => 
  array (
    'id_presensi' => 2748,
    'nis' => 13925,
    'tanggal' => '2026-07-14',
    'jam' => '06:56:40',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  188 => 
  array (
    'id_presensi' => 2749,
    'nis' => 14225,
    'tanggal' => '2026-07-14',
    'jam' => '06:56:42',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  189 => 
  array (
    'id_presensi' => 2750,
    'nis' => 14392,
    'tanggal' => '2026-07-14',
    'jam' => '06:56:42',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  190 => 
  array (
    'id_presensi' => 2751,
    'nis' => 14292,
    'tanggal' => '2026-07-14',
    'jam' => '06:56:43',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  191 => 
  array (
    'id_presensi' => 2752,
    'nis' => 13901,
    'tanggal' => '2026-07-14',
    'jam' => '06:56:44',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  192 => 
  array (
    'id_presensi' => 2753,
    'nis' => 14390,
    'tanggal' => '2026-07-14',
    'jam' => '06:56:47',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  193 => 
  array (
    'id_presensi' => 2754,
    'nis' => 14652,
    'tanggal' => '2026-07-14',
    'jam' => '06:56:49',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  194 => 
  array (
    'id_presensi' => 2755,
    'nis' => 13929,
    'tanggal' => '2026-07-14',
    'jam' => '06:56:51',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  195 => 
  array (
    'id_presensi' => 2756,
    'nis' => 14659,
    'tanggal' => '2026-07-14',
    'jam' => '06:56:56',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  196 => 
  array (
    'id_presensi' => 2757,
    'nis' => 14666,
    'tanggal' => '2026-07-14',
    'jam' => '06:56:58',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  197 => 
  array (
    'id_presensi' => 2758,
    'nis' => 13881,
    'tanggal' => '2026-07-14',
    'jam' => '06:57:00',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  198 => 
  array (
    'id_presensi' => 2759,
    'nis' => 14644,
    'tanggal' => '2026-07-14',
    'jam' => '06:57:02',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  199 => 
  array (
    'id_presensi' => 2760,
    'nis' => 14648,
    'tanggal' => '2026-07-14',
    'jam' => '06:57:04',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
));

        DB::table('presensi')->insert(array (
  0 => 
  array (
    'id_presensi' => 2761,
    'nis' => 13888,
    'tanggal' => '2026-07-14',
    'jam' => '06:57:05',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  1 => 
  array (
    'id_presensi' => 2762,
    'nis' => 14393,
    'tanggal' => '2026-07-14',
    'jam' => '06:57:07',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  2 => 
  array (
    'id_presensi' => 2763,
    'nis' => 14799,
    'tanggal' => '2026-07-14',
    'jam' => '06:57:11',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  3 => 
  array (
    'id_presensi' => 2764,
    'nis' => 14332,
    'tanggal' => '2026-07-14',
    'jam' => '06:57:13',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  4 => 
  array (
    'id_presensi' => 2765,
    'nis' => 14791,
    'tanggal' => '2026-07-14',
    'jam' => '06:57:16',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  5 => 
  array (
    'id_presensi' => 2766,
    'nis' => 14338,
    'tanggal' => '2026-07-14',
    'jam' => '06:57:22',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  6 => 
  array (
    'id_presensi' => 2767,
    'nis' => 14566,
    'tanggal' => '2026-07-14',
    'jam' => '06:57:22',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  7 => 
  array (
    'id_presensi' => 2768,
    'nis' => 14415,
    'tanggal' => '2026-07-14',
    'jam' => '06:57:24',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  8 => 
  array (
    'id_presensi' => 2769,
    'nis' => 14305,
    'tanggal' => '2026-07-14',
    'jam' => '06:57:25',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  9 => 
  array (
    'id_presensi' => 2770,
    'nis' => 14797,
    'tanggal' => '2026-07-14',
    'jam' => '06:57:30',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  10 => 
  array (
    'id_presensi' => 2771,
    'nis' => 14394,
    'tanggal' => '2026-07-14',
    'jam' => '06:57:35',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  11 => 
  array (
    'id_presensi' => 2772,
    'nis' => 14557,
    'tanggal' => '2026-07-14',
    'jam' => '06:57:37',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  12 => 
  array (
    'id_presensi' => 2773,
    'nis' => 14132,
    'tanggal' => '2026-07-14',
    'jam' => '06:57:39',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  13 => 
  array (
    'id_presensi' => 2774,
    'nis' => 13918,
    'tanggal' => '2026-07-14',
    'jam' => '06:57:40',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  14 => 
  array (
    'id_presensi' => 2775,
    'nis' => 14120,
    'tanggal' => '2026-07-14',
    'jam' => '06:57:46',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  15 => 
  array (
    'id_presensi' => 2776,
    'nis' => 14755,
    'tanggal' => '2026-07-14',
    'jam' => '06:57:47',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  16 => 
  array (
    'id_presensi' => 2777,
    'nis' => 13913,
    'tanggal' => '2026-07-14',
    'jam' => '06:57:51',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  17 => 
  array (
    'id_presensi' => 2778,
    'nis' => 14287,
    'tanggal' => '2026-07-14',
    'jam' => '06:57:53',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  18 => 
  array (
    'id_presensi' => 2779,
    'nis' => 14171,
    'tanggal' => '2026-07-14',
    'jam' => '06:57:53',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  19 => 
  array (
    'id_presensi' => 2780,
    'nis' => 14011,
    'tanggal' => '2026-07-14',
    'jam' => '06:57:57',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  20 => 
  array (
    'id_presensi' => 2781,
    'nis' => 14314,
    'tanggal' => '2026-07-14',
    'jam' => '06:58:01',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  21 => 
  array (
    'id_presensi' => 2782,
    'nis' => 14778,
    'tanggal' => '2026-07-14',
    'jam' => '06:58:02',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  22 => 
  array (
    'id_presensi' => 2783,
    'nis' => 14154,
    'tanggal' => '2026-07-14',
    'jam' => '06:58:08',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  23 => 
  array (
    'id_presensi' => 2784,
    'nis' => 14669,
    'tanggal' => '2026-07-14',
    'jam' => '06:58:10',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  24 => 
  array (
    'id_presensi' => 2785,
    'nis' => 14172,
    'tanggal' => '2026-07-14',
    'jam' => '06:58:10',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  25 => 
  array (
    'id_presensi' => 2786,
    'nis' => 14400,
    'tanggal' => '2026-07-14',
    'jam' => '06:58:13',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  26 => 
  array (
    'id_presensi' => 2787,
    'nis' => 14158,
    'tanggal' => '2026-07-14',
    'jam' => '06:58:13',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  27 => 
  array (
    'id_presensi' => 2788,
    'nis' => 14033,
    'tanggal' => '2026-07-14',
    'jam' => '06:58:16',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  28 => 
  array (
    'id_presensi' => 2789,
    'nis' => 14789,
    'tanggal' => '2026-07-14',
    'jam' => '06:58:20',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  29 => 
  array (
    'id_presensi' => 2790,
    'nis' => 14004,
    'tanggal' => '2026-07-14',
    'jam' => '06:58:20',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  30 => 
  array (
    'id_presensi' => 2791,
    'nis' => 14783,
    'tanggal' => '2026-07-14',
    'jam' => '06:58:23',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  31 => 
  array (
    'id_presensi' => 2792,
    'nis' => 14013,
    'tanggal' => '2026-07-14',
    'jam' => '06:58:23',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  32 => 
  array (
    'id_presensi' => 2793,
    'nis' => 14792,
    'tanggal' => '2026-07-14',
    'jam' => '06:58:28',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  33 => 
  array (
    'id_presensi' => 2794,
    'nis' => 14016,
    'tanggal' => '2026-07-14',
    'jam' => '06:58:29',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  34 => 
  array (
    'id_presensi' => 2795,
    'nis' => 14802,
    'tanggal' => '2026-07-14',
    'jam' => '06:58:31',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  35 => 
  array (
    'id_presensi' => 2796,
    'nis' => 14362,
    'tanggal' => '2026-07-14',
    'jam' => '06:58:32',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  36 => 
  array (
    'id_presensi' => 2797,
    'nis' => 14626,
    'tanggal' => '2026-07-14',
    'jam' => '06:58:35',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  37 => 
  array (
    'id_presensi' => 2798,
    'nis' => 14118,
    'tanggal' => '2026-07-14',
    'jam' => '06:58:44',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  38 => 
  array (
    'id_presensi' => 2799,
    'nis' => 14620,
    'tanggal' => '2026-07-14',
    'jam' => '06:58:44',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  39 => 
  array (
    'id_presensi' => 2800,
    'nis' => 14112,
    'tanggal' => '2026-07-14',
    'jam' => '06:58:47',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  40 => 
  array (
    'id_presensi' => 2801,
    'nis' => 14748,
    'tanggal' => '2026-07-14',
    'jam' => '06:58:47',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  41 => 
  array (
    'id_presensi' => 2802,
    'nis' => 14110,
    'tanggal' => '2026-07-14',
    'jam' => '06:58:49',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  42 => 
  array (
    'id_presensi' => 2803,
    'nis' => 13894,
    'tanggal' => '2026-07-14',
    'jam' => '06:58:52',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  43 => 
  array (
    'id_presensi' => 2804,
    'nis' => 14650,
    'tanggal' => '2026-07-14',
    'jam' => '06:58:53',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  44 => 
  array (
    'id_presensi' => 2805,
    'nis' => 14308,
    'tanggal' => '2026-07-14',
    'jam' => '06:58:57',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  45 => 
  array (
    'id_presensi' => 2806,
    'nis' => 14574,
    'tanggal' => '2026-07-14',
    'jam' => '06:58:57',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  46 => 
  array (
    'id_presensi' => 2807,
    'nis' => 13895,
    'tanggal' => '2026-07-14',
    'jam' => '06:59:00',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  47 => 
  array (
    'id_presensi' => 2808,
    'nis' => 14372,
    'tanggal' => '2026-07-14',
    'jam' => '06:59:00',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  48 => 
  array (
    'id_presensi' => 2809,
    'nis' => 13999,
    'tanggal' => '2026-07-14',
    'jam' => '06:59:01',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  49 => 
  array (
    'id_presensi' => 2810,
    'nis' => 13908,
    'tanggal' => '2026-07-14',
    'jam' => '06:59:05',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  50 => 
  array (
    'id_presensi' => 2811,
    'nis' => 14301,
    'tanggal' => '2026-07-14',
    'jam' => '06:59:05',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  51 => 
  array (
    'id_presensi' => 2812,
    'nis' => 13923,
    'tanggal' => '2026-07-14',
    'jam' => '06:59:07',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  52 => 
  array (
    'id_presensi' => 2813,
    'nis' => 14300,
    'tanggal' => '2026-07-14',
    'jam' => '06:59:07',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  53 => 
  array (
    'id_presensi' => 2814,
    'nis' => 14760,
    'tanggal' => '2026-07-14',
    'jam' => '06:59:14',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  54 => 
  array (
    'id_presensi' => 2815,
    'nis' => 14288,
    'tanggal' => '2026-07-14',
    'jam' => '06:59:16',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  55 => 
  array (
    'id_presensi' => 2816,
    'nis' => 14319,
    'tanggal' => '2026-07-14',
    'jam' => '06:59:21',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  56 => 
  array (
    'id_presensi' => 2817,
    'nis' => 13900,
    'tanggal' => '2026-07-14',
    'jam' => '06:59:31',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  57 => 
  array (
    'id_presensi' => 2818,
    'nis' => 13879,
    'tanggal' => '2026-07-14',
    'jam' => '06:59:33',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  58 => 
  array (
    'id_presensi' => 2819,
    'nis' => 13867,
    'tanggal' => '2026-07-14',
    'jam' => '06:59:36',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  59 => 
  array (
    'id_presensi' => 2820,
    'nis' => 13873,
    'tanggal' => '2026-07-14',
    'jam' => '06:59:38',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  60 => 
  array (
    'id_presensi' => 2821,
    'nis' => 13983,
    'tanggal' => '2026-07-14',
    'jam' => '06:59:40',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  61 => 
  array (
    'id_presensi' => 2822,
    'nis' => 14535,
    'tanggal' => '2026-07-14',
    'jam' => '06:59:42',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  62 => 
  array (
    'id_presensi' => 2823,
    'nis' => 14541,
    'tanggal' => '2026-07-14',
    'jam' => '06:59:44',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  63 => 
  array (
    'id_presensi' => 2824,
    'nis' => 14333,
    'tanggal' => '2026-07-14',
    'jam' => '06:59:49',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  64 => 
  array (
    'id_presensi' => 2825,
    'nis' => 14539,
    'tanggal' => '2026-07-14',
    'jam' => '06:59:54',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  65 => 
  array (
    'id_presensi' => 2826,
    'nis' => 14143,
    'tanggal' => '2026-07-14',
    'jam' => '06:59:57',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  66 => 
  array (
    'id_presensi' => 2827,
    'nis' => 14663,
    'tanggal' => '2026-07-14',
    'jam' => '06:59:57',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  67 => 
  array (
    'id_presensi' => 2828,
    'nis' => 14540,
    'tanggal' => '2026-07-14',
    'jam' => '07:00:00',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  68 => 
  array (
    'id_presensi' => 2829,
    'nis' => 14157,
    'tanggal' => '2026-07-14',
    'jam' => '07:00:02',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  69 => 
  array (
    'id_presensi' => 2830,
    'nis' => 14002,
    'tanggal' => '2026-07-14',
    'jam' => '07:00:03',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  70 => 
  array (
    'id_presensi' => 2831,
    'nis' => 14537,
    'tanggal' => '2026-07-14',
    'jam' => '07:00:05',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  71 => 
  array (
    'id_presensi' => 2832,
    'nis' => 14529,
    'tanggal' => '2026-07-14',
    'jam' => '07:00:06',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  72 => 
  array (
    'id_presensi' => 2833,
    'nis' => 13977,
    'tanggal' => '2026-07-14',
    'jam' => '07:00:09',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  73 => 
  array (
    'id_presensi' => 2834,
    'nis' => 14336,
    'tanggal' => '2026-07-14',
    'jam' => '07:00:09',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  74 => 
  array (
    'id_presensi' => 2835,
    'nis' => 14572,
    'tanggal' => '2026-07-14',
    'jam' => '07:00:12',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  75 => 
  array (
    'id_presensi' => 2836,
    'nis' => 14152,
    'tanggal' => '2026-07-14',
    'jam' => '07:00:13',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  76 => 
  array (
    'id_presensi' => 2837,
    'nis' => 13992,
    'tanggal' => '2026-07-14',
    'jam' => '07:00:16',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  77 => 
  array (
    'id_presensi' => 2838,
    'nis' => 14328,
    'tanggal' => '2026-07-14',
    'jam' => '07:00:16',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  78 => 
  array (
    'id_presensi' => 2839,
    'nis' => 14636,
    'tanggal' => '2026-07-14',
    'jam' => '07:00:16',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  79 => 
  array (
    'id_presensi' => 2840,
    'nis' => 14628,
    'tanggal' => '2026-07-14',
    'jam' => '07:00:18',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  80 => 
  array (
    'id_presensi' => 2841,
    'nis' => 14174,
    'tanggal' => '2026-07-14',
    'jam' => '07:00:19',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  81 => 
  array (
    'id_presensi' => 2842,
    'nis' => 13996,
    'tanggal' => '2026-07-14',
    'jam' => '07:00:21',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  82 => 
  array (
    'id_presensi' => 2843,
    'nis' => 13997,
    'tanggal' => '2026-07-14',
    'jam' => '07:00:23',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  83 => 
  array (
    'id_presensi' => 2844,
    'nis' => 14776,
    'tanggal' => '2026-07-14',
    'jam' => '07:00:23',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  84 => 
  array (
    'id_presensi' => 2845,
    'nis' => 13993,
    'tanggal' => '2026-07-14',
    'jam' => '07:00:26',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  85 => 
  array (
    'id_presensi' => 2846,
    'nis' => 14758,
    'tanggal' => '2026-07-14',
    'jam' => '07:00:26',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  86 => 
  array (
    'id_presensi' => 2847,
    'nis' => 14768,
    'tanggal' => '2026-07-14',
    'jam' => '07:00:28',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  87 => 
  array (
    'id_presensi' => 2848,
    'nis' => 13905,
    'tanggal' => '2026-07-14',
    'jam' => '07:00:28',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  88 => 
  array (
    'id_presensi' => 2849,
    'nis' => 13970,
    'tanggal' => '2026-07-14',
    'jam' => '07:00:29',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  89 => 
  array (
    'id_presensi' => 2850,
    'nis' => 14761,
    'tanggal' => '2026-07-14',
    'jam' => '07:00:31',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  90 => 
  array (
    'id_presensi' => 2851,
    'nis' => 13903,
    'tanggal' => '2026-07-14',
    'jam' => '07:00:32',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  91 => 
  array (
    'id_presensi' => 2852,
    'nis' => 14007,
    'tanggal' => '2026-07-14',
    'jam' => '07:00:33',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  92 => 
  array (
    'id_presensi' => 2853,
    'nis' => 14757,
    'tanggal' => '2026-07-14',
    'jam' => '07:00:35',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  93 => 
  array (
    'id_presensi' => 2854,
    'nis' => 14790,
    'tanggal' => '2026-07-14',
    'jam' => '07:00:39',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  94 => 
  array (
    'id_presensi' => 2855,
    'nis' => 14522,
    'tanggal' => '2026-07-14',
    'jam' => '07:00:42',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  95 => 
  array (
    'id_presensi' => 2856,
    'nis' => 14015,
    'tanggal' => '2026-07-14',
    'jam' => '07:00:43',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  96 => 
  array (
    'id_presensi' => 2857,
    'nis' => 14649,
    'tanggal' => '2026-07-14',
    'jam' => '07:00:43',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  97 => 
  array (
    'id_presensi' => 2858,
    'nis' => 14543,
    'tanggal' => '2026-07-14',
    'jam' => '07:00:44',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  98 => 
  array (
    'id_presensi' => 2859,
    'nis' => 14017,
    'tanggal' => '2026-07-14',
    'jam' => '07:00:47',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  99 => 
  array (
    'id_presensi' => 2860,
    'nis' => 14293,
    'tanggal' => '2026-07-14',
    'jam' => '07:00:48',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  100 => 
  array (
    'id_presensi' => 2861,
    'nis' => 14031,
    'tanggal' => '2026-07-14',
    'jam' => '07:00:50',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  101 => 
  array (
    'id_presensi' => 2862,
    'nis' => 14309,
    'tanggal' => '2026-07-14',
    'jam' => '07:00:50',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  102 => 
  array (
    'id_presensi' => 2863,
    'nis' => 14295,
    'tanggal' => '2026-07-14',
    'jam' => '07:00:50',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  103 => 
  array (
    'id_presensi' => 2864,
    'nis' => 14794,
    'tanggal' => '2026-07-14',
    'jam' => '07:00:52',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  104 => 
  array (
    'id_presensi' => 2865,
    'nis' => 14006,
    'tanggal' => '2026-07-14',
    'jam' => '07:00:53',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  105 => 
  array (
    'id_presensi' => 2866,
    'nis' => 13890,
    'tanggal' => '2026-07-14',
    'jam' => '07:00:56',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  106 => 
  array (
    'id_presensi' => 2867,
    'nis' => 14299,
    'tanggal' => '2026-07-14',
    'jam' => '07:00:56',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  107 => 
  array (
    'id_presensi' => 2868,
    'nis' => 14030,
    'tanggal' => '2026-07-14',
    'jam' => '07:00:59',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  108 => 
  array (
    'id_presensi' => 2869,
    'nis' => 14108,
    'tanggal' => '2026-07-14',
    'jam' => '07:00:59',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  109 => 
  array (
    'id_presensi' => 2870,
    'nis' => 14337,
    'tanggal' => '2026-07-14',
    'jam' => '07:01:00',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  110 => 
  array (
    'id_presensi' => 2871,
    'nis' => 14116,
    'tanggal' => '2026-07-14',
    'jam' => '07:01:02',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  111 => 
  array (
    'id_presensi' => 2872,
    'nis' => 14005,
    'tanggal' => '2026-07-14',
    'jam' => '07:01:04',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  112 => 
  array (
    'id_presensi' => 2873,
    'nis' => 14660,
    'tanggal' => '2026-07-14',
    'jam' => '07:01:04',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  113 => 
  array (
    'id_presensi' => 2874,
    'nis' => 14032,
    'tanggal' => '2026-07-14',
    'jam' => '07:01:06',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  114 => 
  array (
    'id_presensi' => 2875,
    'nis' => 14122,
    'tanggal' => '2026-07-14',
    'jam' => '07:01:08',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  115 => 
  array (
    'id_presensi' => 2876,
    'nis' => 13898,
    'tanggal' => '2026-07-14',
    'jam' => '07:01:11',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  116 => 
  array (
    'id_presensi' => 2877,
    'nis' => 14009,
    'tanggal' => '2026-07-14',
    'jam' => '07:01:16',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  117 => 
  array (
    'id_presensi' => 2878,
    'nis' => 13990,
    'tanggal' => '2026-07-14',
    'jam' => '07:01:18',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  118 => 
  array (
    'id_presensi' => 2879,
    'nis' => 13974,
    'tanggal' => '2026-07-14',
    'jam' => '07:01:20',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  119 => 
  array (
    'id_presensi' => 2880,
    'nis' => 13984,
    'tanggal' => '2026-07-14',
    'jam' => '07:01:23',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  120 => 
  array (
    'id_presensi' => 2881,
    'nis' => 13995,
    'tanggal' => '2026-07-14',
    'jam' => '07:01:28',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  121 => 
  array (
    'id_presensi' => 2882,
    'nis' => 14622,
    'tanggal' => '2026-07-14',
    'jam' => '07:01:30',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  122 => 
  array (
    'id_presensi' => 2883,
    'nis' => 13871,
    'tanggal' => '2026-07-14',
    'jam' => '07:01:36',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  123 => 
  array (
    'id_presensi' => 2884,
    'nis' => 13886,
    'tanggal' => '2026-07-14',
    'jam' => '07:01:46',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  124 => 
  array (
    'id_presensi' => 2885,
    'nis' => 14322,
    'tanggal' => '2026-07-14',
    'jam' => '07:01:51',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  125 => 
  array (
    'id_presensi' => 2886,
    'nis' => 14025,
    'tanggal' => '2026-07-14',
    'jam' => '07:01:52',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  126 => 
  array (
    'id_presensi' => 2887,
    'nis' => 13966,
    'tanggal' => '2026-07-14',
    'jam' => '07:01:54',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  127 => 
  array (
    'id_presensi' => 2888,
    'nis' => 14563,
    'tanggal' => '2026-07-14',
    'jam' => '07:01:59',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  128 => 
  array (
    'id_presensi' => 2889,
    'nis' => 14127,
    'tanggal' => '2026-07-14',
    'jam' => '07:02:01',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  129 => 
  array (
    'id_presensi' => 2890,
    'nis' => 13896,
    'tanggal' => '2026-07-14',
    'jam' => '07:02:06',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  130 => 
  array (
    'id_presensi' => 2891,
    'nis' => 14614,
    'tanggal' => '2026-07-14',
    'jam' => '07:02:06',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  131 => 
  array (
    'id_presensi' => 2892,
    'nis' => 14780,
    'tanggal' => '2026-07-14',
    'jam' => '07:02:10',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  132 => 
  array (
    'id_presensi' => 2893,
    'nis' => 14021,
    'tanggal' => '2026-07-14',
    'jam' => '07:02:15',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  133 => 
  array (
    'id_presensi' => 2894,
    'nis' => 13987,
    'tanggal' => '2026-07-14',
    'jam' => '07:02:18',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  134 => 
  array (
    'id_presensi' => 2895,
    'nis' => 14324,
    'tanggal' => '2026-07-14',
    'jam' => '07:02:19',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  135 => 
  array (
    'id_presensi' => 2896,
    'nis' => 14320,
    'tanggal' => '2026-07-14',
    'jam' => '07:02:22',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  136 => 
  array (
    'id_presensi' => 2897,
    'nis' => 13981,
    'tanggal' => '2026-07-14',
    'jam' => '07:02:25',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  137 => 
  array (
    'id_presensi' => 2898,
    'nis' => 13869,
    'tanggal' => '2026-07-14',
    'jam' => '07:02:29',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  138 => 
  array (
    'id_presensi' => 2899,
    'nis' => 13870,
    'tanggal' => '2026-07-14',
    'jam' => '07:02:33',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  139 => 
  array (
    'id_presensi' => 2900,
    'nis' => 13989,
    'tanggal' => '2026-07-14',
    'jam' => '07:02:45',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  140 => 
  array (
    'id_presensi' => 2901,
    'nis' => 14236,
    'tanggal' => '2026-07-14',
    'jam' => '07:02:45',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  141 => 
  array (
    'id_presensi' => 2902,
    'nis' => 14223,
    'tanggal' => '2026-07-14',
    'jam' => '07:02:49',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  142 => 
  array (
    'id_presensi' => 2903,
    'nis' => 13991,
    'tanggal' => '2026-07-14',
    'jam' => '07:02:50',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  143 => 
  array (
    'id_presensi' => 2904,
    'nis' => 14242,
    'tanggal' => '2026-07-14',
    'jam' => '07:02:51',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  144 => 
  array (
    'id_presensi' => 2905,
    'nis' => 14237,
    'tanggal' => '2026-07-14',
    'jam' => '07:02:53',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  145 => 
  array (
    'id_presensi' => 2906,
    'nis' => 14029,
    'tanggal' => '2026-07-14',
    'jam' => '07:02:55',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  146 => 
  array (
    'id_presensi' => 2907,
    'nis' => 14664,
    'tanggal' => '2026-07-14',
    'jam' => '07:03:03',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  147 => 
  array (
    'id_presensi' => 2908,
    'nis' => 14643,
    'tanggal' => '2026-07-14',
    'jam' => '07:03:07',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  148 => 
  array (
    'id_presensi' => 2909,
    'nis' => 13904,
    'tanggal' => '2026-07-14',
    'jam' => '07:03:15',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  149 => 
  array (
    'id_presensi' => 2910,
    'nis' => 14326,
    'tanggal' => '2026-07-14',
    'jam' => '07:03:16',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  150 => 
  array (
    'id_presensi' => 2911,
    'nis' => 14306,
    'tanggal' => '2026-07-14',
    'jam' => '07:03:18',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  151 => 
  array (
    'id_presensi' => 2912,
    'nis' => 13862,
    'tanggal' => '2026-07-14',
    'jam' => '07:03:20',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  152 => 
  array (
    'id_presensi' => 2913,
    'nis' => 14369,
    'tanggal' => '2026-07-14',
    'jam' => '07:03:31',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  153 => 
  array (
    'id_presensi' => 2914,
    'nis' => 14247,
    'tanggal' => '2026-07-14',
    'jam' => '07:03:35',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  154 => 
  array (
    'id_presensi' => 2915,
    'nis' => 13885,
    'tanggal' => '2026-07-14',
    'jam' => '07:03:38',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  155 => 
  array (
    'id_presensi' => 2916,
    'nis' => 14367,
    'tanggal' => '2026-07-14',
    'jam' => '07:03:40',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  156 => 
  array (
    'id_presensi' => 2917,
    'nis' => 14239,
    'tanggal' => '2026-07-14',
    'jam' => '07:03:45',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  157 => 
  array (
    'id_presensi' => 2918,
    'nis' => 14148,
    'tanggal' => '2026-07-14',
    'jam' => '07:03:46',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  158 => 
  array (
    'id_presensi' => 2919,
    'nis' => 14155,
    'tanggal' => '2026-07-14',
    'jam' => '07:03:48',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  159 => 
  array (
    'id_presensi' => 2920,
    'nis' => 14014,
    'tanggal' => '2026-07-14',
    'jam' => '07:03:59',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  160 => 
  array (
    'id_presensi' => 2921,
    'nis' => 14156,
    'tanggal' => '2026-07-14',
    'jam' => '07:03:59',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  161 => 
  array (
    'id_presensi' => 2922,
    'nis' => 14388,
    'tanggal' => '2026-07-14',
    'jam' => '07:04:14',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  162 => 
  array (
    'id_presensi' => 2923,
    'nis' => 14411,
    'tanggal' => '2026-07-14',
    'jam' => '07:04:18',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  163 => 
  array (
    'id_presensi' => 2924,
    'nis' => 13883,
    'tanggal' => '2026-07-14',
    'jam' => '07:04:21',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  164 => 
  array (
    'id_presensi' => 2925,
    'nis' => 14232,
    'tanggal' => '2026-07-14',
    'jam' => '07:04:40',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  165 => 
  array (
    'id_presensi' => 2926,
    'nis' => 14163,
    'tanggal' => '2026-07-14',
    'jam' => '07:04:41',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  166 => 
  array (
    'id_presensi' => 2927,
    'nis' => 13972,
    'tanggal' => '2026-07-14',
    'jam' => '07:05:41',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  167 => 
  array (
    'id_presensi' => 2928,
    'nis' => 14231,
    'tanggal' => '2026-07-14',
    'jam' => '07:18:43',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  168 => 
  array (
    'id_presensi' => 2929,
    'nis' => 14233,
    'tanggal' => '2026-07-14',
    'jam' => '07:19:04',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  169 => 
  array (
    'id_presensi' => 2930,
    'nis' => 14224,
    'tanggal' => '2026-07-14',
    'jam' => '07:20:15',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  170 => 
  array (
    'id_presensi' => 2931,
    'nis' => 14246,
    'tanggal' => '2026-07-14',
    'jam' => '07:24:19',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  171 => 
  array (
    'id_presensi' => 2932,
    'nis' => 14226,
    'tanggal' => '2026-07-14',
    'jam' => '07:24:27',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  172 => 
  array (
    'id_presensi' => 2933,
    'nis' => 14000,
    'tanggal' => '2026-07-14',
    'jam' => '07:33:26',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  173 => 
  array (
    'id_presensi' => 2934,
    'nis' => 13882,
    'tanggal' => '2026-07-14',
    'jam' => '07:41:16',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  174 => 
  array (
    'id_presensi' => 2935,
    'nis' => 13876,
    'tanggal' => '2026-07-14',
    'jam' => '07:45:39',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  175 => 
  array (
    'id_presensi' => 2936,
    'nis' => 14117,
    'tanggal' => '2026-07-14',
    'jam' => '07:50:58',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  176 => 
  array (
    'id_presensi' => 2937,
    'nis' => 14133,
    'tanggal' => '2026-07-14',
    'jam' => '07:51:09',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  177 => 
  array (
    'id_presensi' => 2938,
    'nis' => 14109,
    'tanggal' => '2026-07-14',
    'jam' => '08:00:05',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  178 => 
  array (
    'id_presensi' => 2939,
    'nis' => 14228,
    'tanggal' => '2026-07-14',
    'jam' => '09:57:28',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  179 => 
  array (
    'id_presensi' => 2940,
    'nis' => 14640,
    'tanggal' => '2026-07-14',
    'jam' => '10:01:43',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  180 => 
  array (
    'id_presensi' => 2941,
    'nis' => 14742,
    'tanggal' => '2026-07-14',
    'jam' => '10:45:28',
    'status' => '1',
    'keterangan' => NULL,
    'file' => NULL,
  ),
  181 => 
  array (
    'id_presensi' => 2944,
    'nis' => 14754,
    'tanggal' => '2026-07-14',
    'jam' => '10:45:28',
    'status' => '3',
    'keterangan' => 'Mengurus PIP',
    'file' => NULL,
  ),
  182 => 
  array (
    'id_presensi' => 2945,
    'nis' => 14773,
    'tanggal' => '2026-07-14',
    'jam' => '10:45:28',
    'status' => '2',
    'keterangan' => 'Sakit Demam',
    'file' => NULL,
  ),
  183 => 
  array (
    'id_presensi' => 2946,
    'nis' => 15382,
    'tanggal' => '2026-07-14',
    'jam' => '13:34:03',
    'status' => '1',
    'keterangan' => NULL,
    'file' => NULL,
  ),
  184 => 
  array (
    'id_presensi' => 2947,
    'nis' => 15383,
    'tanggal' => '2026-07-14',
    'jam' => '13:34:03',
    'status' => '1',
    'keterangan' => NULL,
    'file' => NULL,
  ),
  185 => 
  array (
    'id_presensi' => 2948,
    'nis' => 15384,
    'tanggal' => '2026-07-14',
    'jam' => '13:34:03',
    'status' => '1',
    'keterangan' => NULL,
    'file' => NULL,
  ),
  186 => 
  array (
    'id_presensi' => 2949,
    'nis' => 15385,
    'tanggal' => '2026-07-14',
    'jam' => '13:34:03',
    'status' => '1',
    'keterangan' => NULL,
    'file' => NULL,
  ),
  187 => 
  array (
    'id_presensi' => 2950,
    'nis' => 15386,
    'tanggal' => '2026-07-14',
    'jam' => '13:34:03',
    'status' => '1',
    'keterangan' => NULL,
    'file' => NULL,
  ),
  188 => 
  array (
    'id_presensi' => 2951,
    'nis' => 15387,
    'tanggal' => '2026-07-14',
    'jam' => '13:34:03',
    'status' => '1',
    'keterangan' => NULL,
    'file' => NULL,
  ),
  189 => 
  array (
    'id_presensi' => 2952,
    'nis' => 15388,
    'tanggal' => '2026-07-14',
    'jam' => '13:34:03',
    'status' => '1',
    'keterangan' => NULL,
    'file' => NULL,
  ),
  190 => 
  array (
    'id_presensi' => 2953,
    'nis' => 15389,
    'tanggal' => '2026-07-14',
    'jam' => '13:34:03',
    'status' => '1',
    'keterangan' => NULL,
    'file' => NULL,
  ),
  191 => 
  array (
    'id_presensi' => 2954,
    'nis' => 15390,
    'tanggal' => '2026-07-14',
    'jam' => '13:34:03',
    'status' => '1',
    'keterangan' => NULL,
    'file' => NULL,
  ),
  192 => 
  array (
    'id_presensi' => 2955,
    'nis' => 15391,
    'tanggal' => '2026-07-14',
    'jam' => '13:34:03',
    'status' => '1',
    'keterangan' => NULL,
    'file' => NULL,
  ),
  193 => 
  array (
    'id_presensi' => 2956,
    'nis' => 15392,
    'tanggal' => '2026-07-14',
    'jam' => '13:34:03',
    'status' => '1',
    'keterangan' => NULL,
    'file' => NULL,
  ),
  194 => 
  array (
    'id_presensi' => 2957,
    'nis' => 15393,
    'tanggal' => '2026-07-14',
    'jam' => '13:34:03',
    'status' => '1',
    'keterangan' => NULL,
    'file' => NULL,
  ),
  195 => 
  array (
    'id_presensi' => 2958,
    'nis' => 15394,
    'tanggal' => '2026-07-14',
    'jam' => '13:34:03',
    'status' => '1',
    'keterangan' => NULL,
    'file' => NULL,
  ),
  196 => 
  array (
    'id_presensi' => 2959,
    'nis' => 15395,
    'tanggal' => '2026-07-14',
    'jam' => '13:34:03',
    'status' => '1',
    'keterangan' => NULL,
    'file' => NULL,
  ),
  197 => 
  array (
    'id_presensi' => 2960,
    'nis' => 15396,
    'tanggal' => '2026-07-14',
    'jam' => '13:34:03',
    'status' => '1',
    'keterangan' => NULL,
    'file' => NULL,
  ),
  198 => 
  array (
    'id_presensi' => 2961,
    'nis' => 15397,
    'tanggal' => '2026-07-14',
    'jam' => '13:34:03',
    'status' => '1',
    'keterangan' => NULL,
    'file' => NULL,
  ),
  199 => 
  array (
    'id_presensi' => 2962,
    'nis' => 15398,
    'tanggal' => '2026-07-14',
    'jam' => '13:34:03',
    'status' => '1',
    'keterangan' => NULL,
    'file' => NULL,
  ),
));

        DB::table('presensi')->insert(array (
  0 => 
  array (
    'id_presensi' => 2963,
    'nis' => 15399,
    'tanggal' => '2026-07-14',
    'jam' => '13:34:03',
    'status' => '1',
    'keterangan' => NULL,
    'file' => NULL,
  ),
  1 => 
  array (
    'id_presensi' => 2964,
    'nis' => 15400,
    'tanggal' => '2026-07-14',
    'jam' => '13:34:03',
    'status' => '1',
    'keterangan' => NULL,
    'file' => NULL,
  ),
  2 => 
  array (
    'id_presensi' => 2965,
    'nis' => 15401,
    'tanggal' => '2026-07-14',
    'jam' => '13:34:03',
    'status' => '1',
    'keterangan' => NULL,
    'file' => NULL,
  ),
  3 => 
  array (
    'id_presensi' => 2966,
    'nis' => 15402,
    'tanggal' => '2026-07-14',
    'jam' => '13:34:03',
    'status' => '2',
    'keterangan' => 'kakinya masih sakit',
    'file' => 'siswa/presensi/eyUUOd7GzQ5b3WMaG5S8ZeovEdbFTB3Q7ePmVby3.jpg',
  ),
  4 => 
  array (
    'id_presensi' => 2967,
    'nis' => 15403,
    'tanggal' => '2026-07-14',
    'jam' => '13:34:03',
    'status' => '1',
    'keterangan' => NULL,
    'file' => NULL,
  ),
  5 => 
  array (
    'id_presensi' => 2968,
    'nis' => 15404,
    'tanggal' => '2026-07-14',
    'jam' => '13:34:03',
    'status' => '1',
    'keterangan' => NULL,
    'file' => NULL,
  ),
  6 => 
  array (
    'id_presensi' => 2969,
    'nis' => 15405,
    'tanggal' => '2026-07-14',
    'jam' => '13:34:03',
    'status' => '1',
    'keterangan' => NULL,
    'file' => NULL,
  ),
  7 => 
  array (
    'id_presensi' => 2970,
    'nis' => 15406,
    'tanggal' => '2026-07-14',
    'jam' => '13:34:03',
    'status' => '1',
    'keterangan' => NULL,
    'file' => NULL,
  ),
  8 => 
  array (
    'id_presensi' => 2971,
    'nis' => 15407,
    'tanggal' => '2026-07-14',
    'jam' => '13:34:03',
    'status' => '1',
    'keterangan' => NULL,
    'file' => NULL,
  ),
  9 => 
  array (
    'id_presensi' => 2972,
    'nis' => 15408,
    'tanggal' => '2026-07-14',
    'jam' => '13:34:03',
    'status' => '1',
    'keterangan' => NULL,
    'file' => NULL,
  ),
  10 => 
  array (
    'id_presensi' => 2973,
    'nis' => 15409,
    'tanggal' => '2026-07-14',
    'jam' => '13:34:03',
    'status' => '1',
    'keterangan' => NULL,
    'file' => NULL,
  ),
  11 => 
  array (
    'id_presensi' => 2974,
    'nis' => 15410,
    'tanggal' => '2026-07-14',
    'jam' => '13:34:03',
    'status' => '1',
    'keterangan' => NULL,
    'file' => NULL,
  ),
  12 => 
  array (
    'id_presensi' => 2975,
    'nis' => 15411,
    'tanggal' => '2026-07-14',
    'jam' => '13:34:03',
    'status' => '1',
    'keterangan' => NULL,
    'file' => NULL,
  ),
  13 => 
  array (
    'id_presensi' => 2976,
    'nis' => 15412,
    'tanggal' => '2026-07-14',
    'jam' => '13:34:03',
    'status' => '1',
    'keterangan' => NULL,
    'file' => NULL,
  ),
  14 => 
  array (
    'id_presensi' => 2977,
    'nis' => 15413,
    'tanggal' => '2026-07-14',
    'jam' => '13:34:03',
    'status' => '1',
    'keterangan' => NULL,
    'file' => NULL,
  ),
  15 => 
  array (
    'id_presensi' => 2978,
    'nis' => 15414,
    'tanggal' => '2026-07-14',
    'jam' => '13:34:03',
    'status' => '1',
    'keterangan' => NULL,
    'file' => NULL,
  ),
  16 => 
  array (
    'id_presensi' => 2979,
    'nis' => 15415,
    'tanggal' => '2026-07-14',
    'jam' => '13:34:03',
    'status' => '1',
    'keterangan' => NULL,
    'file' => NULL,
  ),
  17 => 
  array (
    'id_presensi' => 2980,
    'nis' => 15416,
    'tanggal' => '2026-07-14',
    'jam' => '13:34:03',
    'status' => '1',
    'keterangan' => NULL,
    'file' => NULL,
  ),
  18 => 
  array (
    'id_presensi' => 2981,
    'nis' => 15417,
    'tanggal' => '2026-07-14',
    'jam' => '13:34:03',
    'status' => '1',
    'keterangan' => NULL,
    'file' => NULL,
  ),
  19 => 
  array (
    'id_presensi' => 2982,
    'nis' => 15418,
    'tanggal' => '2026-07-14',
    'jam' => '13:34:03',
    'status' => '1',
    'keterangan' => NULL,
    'file' => NULL,
  ),
  20 => 
  array (
    'id_presensi' => 2983,
    'nis' => 15419,
    'tanggal' => '2026-07-14',
    'jam' => '13:34:03',
    'status' => '1',
    'keterangan' => NULL,
    'file' => NULL,
  ),
  21 => 
  array (
    'id_presensi' => 2984,
    'nis' => 15420,
    'tanggal' => '2026-07-14',
    'jam' => '13:34:03',
    'status' => '1',
    'keterangan' => NULL,
    'file' => NULL,
  ),
  22 => 
  array (
    'id_presensi' => 2985,
    'nis' => 14321,
    'tanggal' => '2026-07-08',
    'jam' => '09:56:49',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  23 => 
  array (
    'id_presensi' => 2986,
    'nis' => 13871,
    'tanggal' => '2026-07-08',
    'jam' => '15:55:55',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  24 => 
  array (
    'id_presensi' => 2987,
    'nis' => 14321,
    'tanggal' => '2026-07-09',
    'jam' => '16:57:54',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  25 => 
  array (
    'id_presensi' => 2988,
    'nis' => 13890,
    'tanggal' => '2026-07-09',
    'jam' => '17:18:32',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  26 => 
  array (
    'id_presensi' => 2989,
    'nis' => 14321,
    'tanggal' => '2026-07-10',
    'jam' => '06:27:25',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  27 => 
  array (
    'id_presensi' => 2990,
    'nis' => 14375,
    'tanggal' => '2026-07-10',
    'jam' => '17:16:56',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  28 => 
  array (
    'id_presensi' => 2991,
    'nis' => 14375,
    'tanggal' => '2026-07-11',
    'jam' => '12:43:12',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  29 => 
  array (
    'id_presensi' => 2992,
    'nis' => 14291,
    'tanggal' => '2026-07-11',
    'jam' => '14:05:03',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  30 => 
  array (
    'id_presensi' => 2993,
    'nis' => 13876,
    'tanggal' => '2026-07-12',
    'jam' => '12:40:28',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  31 => 
  array (
    'id_presensi' => 2994,
    'nis' => 14025,
    'tanggal' => '2026-07-12',
    'jam' => '22:09:40',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  32 => 
  array (
    'id_presensi' => 2995,
    'nis' => 14434,
    'tanggal' => '2026-07-13',
    'jam' => '06:38:11',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  33 => 
  array (
    'id_presensi' => 2996,
    'nis' => 14325,
    'tanggal' => '2026-07-14',
    'jam' => '11:44:18',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  34 => 
  array (
    'id_presensi' => 2997,
    'nis' => 13914,
    'tanggal' => '2026-07-14',
    'jam' => '12:07:00',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  35 => 
  array (
    'id_presensi' => 2998,
    'nis' => 14244,
    'tanggal' => '2026-07-14',
    'jam' => '12:09:43',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  36 => 
  array (
    'id_presensi' => 2999,
    'nis' => 14600,
    'tanggal' => '2026-07-14',
    'jam' => '12:20:11',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  37 => 
  array (
    'id_presensi' => 3000,
    'nis' => 14581,
    'tanggal' => '2026-07-14',
    'jam' => '12:20:28',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  38 => 
  array (
    'id_presensi' => 3001,
    'nis' => 14495,
    'tanggal' => '2026-07-14',
    'jam' => '12:20:39',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  39 => 
  array (
    'id_presensi' => 3002,
    'nis' => 14424,
    'tanggal' => '2026-07-14',
    'jam' => '12:21:24',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  40 => 
  array (
    'id_presensi' => 3003,
    'nis' => 14446,
    'tanggal' => '2026-07-14',
    'jam' => '12:22:32',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  41 => 
  array (
    'id_presensi' => 3004,
    'nis' => 14465,
    'tanggal' => '2026-07-14',
    'jam' => '12:23:50',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  42 => 
  array (
    'id_presensi' => 3005,
    'nis' => 14598,
    'tanggal' => '2026-07-14',
    'jam' => '12:24:00',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  43 => 
  array (
    'id_presensi' => 3006,
    'nis' => 14595,
    'tanggal' => '2026-07-14',
    'jam' => '12:24:02',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  44 => 
  array (
    'id_presensi' => 3007,
    'nis' => 14601,
    'tanggal' => '2026-07-14',
    'jam' => '12:24:05',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  45 => 
  array (
    'id_presensi' => 3008,
    'nis' => 14587,
    'tanggal' => '2026-07-14',
    'jam' => '12:27:01',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  46 => 
  array (
    'id_presensi' => 3009,
    'nis' => 14592,
    'tanggal' => '2026-07-14',
    'jam' => '12:27:03',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  47 => 
  array (
    'id_presensi' => 3010,
    'nis' => 14487,
    'tanggal' => '2026-07-14',
    'jam' => '12:27:06',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  48 => 
  array (
    'id_presensi' => 3011,
    'nis' => 14131,
    'tanggal' => '2026-07-14',
    'jam' => '12:28:56',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  49 => 
  array (
    'id_presensi' => 3012,
    'nis' => 14591,
    'tanggal' => '2026-07-14',
    'jam' => '12:29:00',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  50 => 
  array (
    'id_presensi' => 3013,
    'nis' => 14585,
    'tanggal' => '2026-07-14',
    'jam' => '12:29:05',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  51 => 
  array (
    'id_presensi' => 3014,
    'nis' => 14579,
    'tanggal' => '2026-07-14',
    'jam' => '12:29:10',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  52 => 
  array (
    'id_presensi' => 3015,
    'nis' => 14485,
    'tanggal' => '2026-07-14',
    'jam' => '12:29:13',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  53 => 
  array (
    'id_presensi' => 3016,
    'nis' => 14490,
    'tanggal' => '2026-07-14',
    'jam' => '12:29:15',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  54 => 
  array (
    'id_presensi' => 3017,
    'nis' => 14492,
    'tanggal' => '2026-07-14',
    'jam' => '12:29:23',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  55 => 
  array (
    'id_presensi' => 3018,
    'nis' => 14496,
    'tanggal' => '2026-07-14',
    'jam' => '12:29:39',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  56 => 
  array (
    'id_presensi' => 3019,
    'nis' => 14477,
    'tanggal' => '2026-07-14',
    'jam' => '12:29:42',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  57 => 
  array (
    'id_presensi' => 3020,
    'nis' => 14503,
    'tanggal' => '2026-07-14',
    'jam' => '12:29:56',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  58 => 
  array (
    'id_presensi' => 3021,
    'nis' => 14498,
    'tanggal' => '2026-07-14',
    'jam' => '12:30:10',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  59 => 
  array (
    'id_presensi' => 3022,
    'nis' => 14471,
    'tanggal' => '2026-07-14',
    'jam' => '12:30:13',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  60 => 
  array (
    'id_presensi' => 3023,
    'nis' => 14460,
    'tanggal' => '2026-07-14',
    'jam' => '12:30:15',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  61 => 
  array (
    'id_presensi' => 3024,
    'nis' => 14447,
    'tanggal' => '2026-07-14',
    'jam' => '12:30:30',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  62 => 
  array (
    'id_presensi' => 3025,
    'nis' => 14491,
    'tanggal' => '2026-07-14',
    'jam' => '12:30:39',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  63 => 
  array (
    'id_presensi' => 3026,
    'nis' => 14709,
    'tanggal' => '2026-07-14',
    'jam' => '12:30:51',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  64 => 
  array (
    'id_presensi' => 3027,
    'nis' => 14438,
    'tanggal' => '2026-07-14',
    'jam' => '12:31:01',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  65 => 
  array (
    'id_presensi' => 3028,
    'nis' => 14721,
    'tanggal' => '2026-07-14',
    'jam' => '12:31:20',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  66 => 
  array (
    'id_presensi' => 3029,
    'nis' => 14718,
    'tanggal' => '2026-07-14',
    'jam' => '12:31:22',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  67 => 
  array (
    'id_presensi' => 3030,
    'nis' => 14712,
    'tanggal' => '2026-07-14',
    'jam' => '12:31:26',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  68 => 
  array (
    'id_presensi' => 3031,
    'nis' => 14425,
    'tanggal' => '2026-07-14',
    'jam' => '12:31:28',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  69 => 
  array (
    'id_presensi' => 3032,
    'nis' => 14453,
    'tanggal' => '2026-07-14',
    'jam' => '12:31:34',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  70 => 
  array (
    'id_presensi' => 3033,
    'nis' => 14717,
    'tanggal' => '2026-07-14',
    'jam' => '12:31:34',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  71 => 
  array (
    'id_presensi' => 3034,
    'nis' => 14470,
    'tanggal' => '2026-07-14',
    'jam' => '12:31:37',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  72 => 
  array (
    'id_presensi' => 3035,
    'nis' => 14720,
    'tanggal' => '2026-07-14',
    'jam' => '12:31:43',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  73 => 
  array (
    'id_presensi' => 3036,
    'nis' => 14723,
    'tanggal' => '2026-07-14',
    'jam' => '12:31:46',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  74 => 
  array (
    'id_presensi' => 3037,
    'nis' => 14710,
    'tanggal' => '2026-07-14',
    'jam' => '12:31:48',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  75 => 
  array (
    'id_presensi' => 3038,
    'nis' => 14735,
    'tanggal' => '2026-07-14',
    'jam' => '12:31:53',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  76 => 
  array (
    'id_presensi' => 3039,
    'nis' => 14737,
    'tanggal' => '2026-07-14',
    'jam' => '12:31:56',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  77 => 
  array (
    'id_presensi' => 3040,
    'nis' => 14736,
    'tanggal' => '2026-07-14',
    'jam' => '12:32:03',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  78 => 
  array (
    'id_presensi' => 3041,
    'nis' => 14715,
    'tanggal' => '2026-07-14',
    'jam' => '12:32:09',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  79 => 
  array (
    'id_presensi' => 3042,
    'nis' => 14725,
    'tanggal' => '2026-07-14',
    'jam' => '12:32:13',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  80 => 
  array (
    'id_presensi' => 3043,
    'nis' => 14687,
    'tanggal' => '2026-07-14',
    'jam' => '12:32:19',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  81 => 
  array (
    'id_presensi' => 3044,
    'nis' => 14677,
    'tanggal' => '2026-07-14',
    'jam' => '12:32:25',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  82 => 
  array (
    'id_presensi' => 3045,
    'nis' => 14727,
    'tanggal' => '2026-07-14',
    'jam' => '12:32:28',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  83 => 
  array (
    'id_presensi' => 3046,
    'nis' => 14730,
    'tanggal' => '2026-07-14',
    'jam' => '12:32:35',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  84 => 
  array (
    'id_presensi' => 3047,
    'nis' => 14738,
    'tanggal' => '2026-07-14',
    'jam' => '12:32:38',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  85 => 
  array (
    'id_presensi' => 3048,
    'nis' => 14683,
    'tanggal' => '2026-07-14',
    'jam' => '12:32:42',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  86 => 
  array (
    'id_presensi' => 3049,
    'nis' => 14724,
    'tanggal' => '2026-07-14',
    'jam' => '12:32:47',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  87 => 
  array (
    'id_presensi' => 3050,
    'nis' => 14689,
    'tanggal' => '2026-07-14',
    'jam' => '12:32:56',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  88 => 
  array (
    'id_presensi' => 3051,
    'nis' => 14704,
    'tanggal' => '2026-07-14',
    'jam' => '12:32:58',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  89 => 
  array (
    'id_presensi' => 3052,
    'nis' => 14705,
    'tanggal' => '2026-07-14',
    'jam' => '12:33:03',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  90 => 
  array (
    'id_presensi' => 3053,
    'nis' => 14703,
    'tanggal' => '2026-07-14',
    'jam' => '12:33:05',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  91 => 
  array (
    'id_presensi' => 3054,
    'nis' => 14688,
    'tanggal' => '2026-07-14',
    'jam' => '12:33:08',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  92 => 
  array (
    'id_presensi' => 3055,
    'nis' => 14690,
    'tanggal' => '2026-07-14',
    'jam' => '12:33:13',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  93 => 
  array (
    'id_presensi' => 3056,
    'nis' => 14679,
    'tanggal' => '2026-07-14',
    'jam' => '12:33:18',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  94 => 
  array (
    'id_presensi' => 3057,
    'nis' => 14706,
    'tanggal' => '2026-07-14',
    'jam' => '12:33:21',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  95 => 
  array (
    'id_presensi' => 3058,
    'nis' => 14685,
    'tanggal' => '2026-07-14',
    'jam' => '12:33:23',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  96 => 
  array (
    'id_presensi' => 3059,
    'nis' => 14701,
    'tanggal' => '2026-07-14',
    'jam' => '12:33:26',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  97 => 
  array (
    'id_presensi' => 3060,
    'nis' => 14697,
    'tanggal' => '2026-07-14',
    'jam' => '12:33:28',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  98 => 
  array (
    'id_presensi' => 3061,
    'nis' => 14684,
    'tanggal' => '2026-07-14',
    'jam' => '12:33:30',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  99 => 
  array (
    'id_presensi' => 3062,
    'nis' => 14696,
    'tanggal' => '2026-07-14',
    'jam' => '12:33:34',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  100 => 
  array (
    'id_presensi' => 3063,
    'nis' => 14726,
    'tanggal' => '2026-07-14',
    'jam' => '12:33:37',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  101 => 
  array (
    'id_presensi' => 3064,
    'nis' => 14691,
    'tanggal' => '2026-07-14',
    'jam' => '12:33:44',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  102 => 
  array (
    'id_presensi' => 3065,
    'nis' => 14693,
    'tanggal' => '2026-07-14',
    'jam' => '12:33:50',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  103 => 
  array (
    'id_presensi' => 3066,
    'nis' => 14686,
    'tanggal' => '2026-07-14',
    'jam' => '12:34:05',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  104 => 
  array (
    'id_presensi' => 3067,
    'nis' => 14675,
    'tanggal' => '2026-07-14',
    'jam' => '12:34:08',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  105 => 
  array (
    'id_presensi' => 3068,
    'nis' => 14682,
    'tanggal' => '2026-07-14',
    'jam' => '12:34:17',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  106 => 
  array (
    'id_presensi' => 3069,
    'nis' => 14699,
    'tanggal' => '2026-07-14',
    'jam' => '12:34:26',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  107 => 
  array (
    'id_presensi' => 3070,
    'nis' => 14698,
    'tanggal' => '2026-07-14',
    'jam' => '12:34:32',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  108 => 
  array (
    'id_presensi' => 3071,
    'nis' => 14694,
    'tanggal' => '2026-07-14',
    'jam' => '12:34:38',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  109 => 
  array (
    'id_presensi' => 3072,
    'nis' => 14695,
    'tanggal' => '2026-07-14',
    'jam' => '12:34:46',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  110 => 
  array (
    'id_presensi' => 3073,
    'nis' => 14674,
    'tanggal' => '2026-07-14',
    'jam' => '12:34:52',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  111 => 
  array (
    'id_presensi' => 3074,
    'nis' => 14582,
    'tanggal' => '2026-07-14',
    'jam' => '12:37:00',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  112 => 
  array (
    'id_presensi' => 3075,
    'nis' => 14604,
    'tanggal' => '2026-07-14',
    'jam' => '12:37:02',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  113 => 
  array (
    'id_presensi' => 3076,
    'nis' => 14596,
    'tanggal' => '2026-07-14',
    'jam' => '12:37:10',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  114 => 
  array (
    'id_presensi' => 3077,
    'nis' => 14676,
    'tanggal' => '2026-07-14',
    'jam' => '12:39:34',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  115 => 
  array (
    'id_presensi' => 3078,
    'nis' => 14599,
    'tanggal' => '2026-07-14',
    'jam' => '12:39:49',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  116 => 
  array (
    'id_presensi' => 3079,
    'nis' => 14437,
    'tanggal' => '2026-07-14',
    'jam' => '12:41:30',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  117 => 
  array (
    'id_presensi' => 3080,
    'nis' => 14593,
    'tanggal' => '2026-07-14',
    'jam' => '12:41:52',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  118 => 
  array (
    'id_presensi' => 3081,
    'nis' => 14433,
    'tanggal' => '2026-07-14',
    'jam' => '12:42:00',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  119 => 
  array (
    'id_presensi' => 3082,
    'nis' => 14580,
    'tanggal' => '2026-07-14',
    'jam' => '12:58:22',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  120 => 
  array (
    'id_presensi' => 3083,
    'nis' => 14606,
    'tanggal' => '2026-07-14',
    'jam' => '14:39:28',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  121 => 
  array (
    'id_presensi' => 3084,
    'nis' => 14680,
    'tanggal' => '2026-07-14',
    'jam' => '15:02:13',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  122 => 
  array (
    'id_presensi' => 3085,
    'nis' => 14422,
    'tanggal' => '2026-07-14',
    'jam' => '15:27:15',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  123 => 
  array (
    'id_presensi' => 3086,
    'nis' => 14426,
    'tanggal' => '2026-07-14',
    'jam' => '15:27:33',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  124 => 
  array (
    'id_presensi' => 3087,
    'nis' => 14418,
    'tanggal' => '2026-07-14',
    'jam' => '15:27:41',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  125 => 
  array (
    'id_presensi' => 3088,
    'nis' => 14420,
    'tanggal' => '2026-07-14',
    'jam' => '15:56:27',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  126 => 
  array (
    'id_presensi' => 3089,
    'nis' => 14443,
    'tanggal' => '2026-07-14',
    'jam' => '15:56:47',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  127 => 
  array (
    'id_presensi' => 3090,
    'nis' => 14429,
    'tanggal' => '2026-07-14',
    'jam' => '15:56:54',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  128 => 
  array (
    'id_presensi' => 3091,
    'nis' => 14378,
    'tanggal' => '2026-07-15',
    'jam' => '06:09:46',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  129 => 
  array (
    'id_presensi' => 3092,
    'nis' => 14570,
    'tanggal' => '2026-07-15',
    'jam' => '06:10:58',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  130 => 
  array (
    'id_presensi' => 3093,
    'nis' => 14379,
    'tanggal' => '2026-07-15',
    'jam' => '06:11:16',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  131 => 
  array (
    'id_presensi' => 3094,
    'nis' => 14773,
    'tanggal' => '2026-07-15',
    'jam' => '06:12:45',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  132 => 
  array (
    'id_presensi' => 3095,
    'nis' => 14213,
    'tanggal' => '2026-07-15',
    'jam' => '06:18:14',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  133 => 
  array (
    'id_presensi' => 3096,
    'nis' => 14774,
    'tanggal' => '2026-07-15',
    'jam' => '06:20:18',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  134 => 
  array (
    'id_presensi' => 3097,
    'nis' => 14742,
    'tanggal' => '2026-07-15',
    'jam' => '06:20:23',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  135 => 
  array (
    'id_presensi' => 3098,
    'nis' => 14630,
    'tanggal' => '2026-07-15',
    'jam' => '06:27:44',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  136 => 
  array (
    'id_presensi' => 3099,
    'nis' => 14381,
    'tanggal' => '2026-07-15',
    'jam' => '06:31:12',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  137 => 
  array (
    'id_presensi' => 3100,
    'nis' => 14749,
    'tanggal' => '2026-07-15',
    'jam' => '06:33:15',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  138 => 
  array (
    'id_presensi' => 3101,
    'nis' => 14745,
    'tanggal' => '2026-07-15',
    'jam' => '06:34:08',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  139 => 
  array (
    'id_presensi' => 3102,
    'nis' => 14518,
    'tanggal' => '2026-07-15',
    'jam' => '06:34:25',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  140 => 
  array (
    'id_presensi' => 3103,
    'nis' => 14238,
    'tanggal' => '2026-07-15',
    'jam' => '06:35:23',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  141 => 
  array (
    'id_presensi' => 3104,
    'nis' => 14170,
    'tanggal' => '2026-07-15',
    'jam' => '06:37:35',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  142 => 
  array (
    'id_presensi' => 3105,
    'nis' => 14321,
    'tanggal' => '2026-07-15',
    'jam' => '06:37:59',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  143 => 
  array (
    'id_presensi' => 3106,
    'nis' => 14573,
    'tanggal' => '2026-07-15',
    'jam' => '06:38:16',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  144 => 
  array (
    'id_presensi' => 3107,
    'nis' => 14563,
    'tanggal' => '2026-07-15',
    'jam' => '06:38:18',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  145 => 
  array (
    'id_presensi' => 3108,
    'nis' => 14759,
    'tanggal' => '2026-07-15',
    'jam' => '06:39:04',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  146 => 
  array (
    'id_presensi' => 3109,
    'nis' => 14375,
    'tanggal' => '2026-07-15',
    'jam' => '06:39:17',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  147 => 
  array (
    'id_presensi' => 3110,
    'nis' => 14767,
    'tanggal' => '2026-07-15',
    'jam' => '06:39:30',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  148 => 
  array (
    'id_presensi' => 3111,
    'nis' => 14385,
    'tanggal' => '2026-07-15',
    'jam' => '06:39:45',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  149 => 
  array (
    'id_presensi' => 3112,
    'nis' => 14382,
    'tanggal' => '2026-07-15',
    'jam' => '06:40:00',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  150 => 
  array (
    'id_presensi' => 3113,
    'nis' => 14631,
    'tanggal' => '2026-07-15',
    'jam' => '06:40:25',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  151 => 
  array (
    'id_presensi' => 3114,
    'nis' => 13868,
    'tanggal' => '2026-07-15',
    'jam' => '06:40:36',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  152 => 
  array (
    'id_presensi' => 3115,
    'nis' => 14339,
    'tanggal' => '2026-07-15',
    'jam' => '06:41:04',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  153 => 
  array (
    'id_presensi' => 3116,
    'nis' => 14227,
    'tanggal' => '2026-07-15',
    'jam' => '06:41:58',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  154 => 
  array (
    'id_presensi' => 3117,
    'nis' => 14633,
    'tanggal' => '2026-07-15',
    'jam' => '06:42:04',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  155 => 
  array (
    'id_presensi' => 3118,
    'nis' => 14634,
    'tanggal' => '2026-07-15',
    'jam' => '06:42:10',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  156 => 
  array (
    'id_presensi' => 3119,
    'nis' => 14614,
    'tanggal' => '2026-07-15',
    'jam' => '06:42:13',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  157 => 
  array (
    'id_presensi' => 3120,
    'nis' => 14567,
    'tanggal' => '2026-07-15',
    'jam' => '06:42:17',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  158 => 
  array (
    'id_presensi' => 3121,
    'nis' => 14641,
    'tanggal' => '2026-07-15',
    'jam' => '06:42:17',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  159 => 
  array (
    'id_presensi' => 3122,
    'nis' => 14608,
    'tanggal' => '2026-07-15',
    'jam' => '06:42:19',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  160 => 
  array (
    'id_presensi' => 3123,
    'nis' => 14741,
    'tanggal' => '2026-07-15',
    'jam' => '06:42:40',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  161 => 
  array (
    'id_presensi' => 3124,
    'nis' => 14555,
    'tanggal' => '2026-07-15',
    'jam' => '06:42:59',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  162 => 
  array (
    'id_presensi' => 3125,
    'nis' => 14317,
    'tanggal' => '2026-07-15',
    'jam' => '06:43:47',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  163 => 
  array (
    'id_presensi' => 3126,
    'nis' => 14295,
    'tanggal' => '2026-07-15',
    'jam' => '06:44:49',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  164 => 
  array (
    'id_presensi' => 3127,
    'nis' => 14625,
    'tanggal' => '2026-07-15',
    'jam' => '06:45:02',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  165 => 
  array (
    'id_presensi' => 3128,
    'nis' => 14547,
    'tanggal' => '2026-07-15',
    'jam' => '06:45:07',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  166 => 
  array (
    'id_presensi' => 3129,
    'nis' => 13875,
    'tanggal' => '2026-07-15',
    'jam' => '06:45:07',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  167 => 
  array (
    'id_presensi' => 3130,
    'nis' => 14770,
    'tanggal' => '2026-07-15',
    'jam' => '06:45:11',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  168 => 
  array (
    'id_presensi' => 3131,
    'nis' => 14793,
    'tanggal' => '2026-07-15',
    'jam' => '06:45:35',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  169 => 
  array (
    'id_presensi' => 3132,
    'nis' => 14775,
    'tanggal' => '2026-07-15',
    'jam' => '06:45:40',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  170 => 
  array (
    'id_presensi' => 3133,
    'nis' => 14763,
    'tanggal' => '2026-07-15',
    'jam' => '06:45:44',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  171 => 
  array (
    'id_presensi' => 3134,
    'nis' => 14769,
    'tanggal' => '2026-07-15',
    'jam' => '06:45:47',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  172 => 
  array (
    'id_presensi' => 3135,
    'nis' => 14548,
    'tanggal' => '2026-07-15',
    'jam' => '06:45:47',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  173 => 
  array (
    'id_presensi' => 3136,
    'nis' => 13915,
    'tanggal' => '2026-07-15',
    'jam' => '06:45:50',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  174 => 
  array (
    'id_presensi' => 3137,
    'nis' => 13929,
    'tanggal' => '2026-07-15',
    'jam' => '06:45:52',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  175 => 
  array (
    'id_presensi' => 3138,
    'nis' => 14744,
    'tanggal' => '2026-07-15',
    'jam' => '06:45:52',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  176 => 
  array (
    'id_presensi' => 3139,
    'nis' => 14128,
    'tanggal' => '2026-07-15',
    'jam' => '06:45:54',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  177 => 
  array (
    'id_presensi' => 3140,
    'nis' => 14340,
    'tanggal' => '2026-07-15',
    'jam' => '06:45:54',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  178 => 
  array (
    'id_presensi' => 3141,
    'nis' => 13891,
    'tanggal' => '2026-07-15',
    'jam' => '06:47:01',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  179 => 
  array (
    'id_presensi' => 3142,
    'nis' => 14161,
    'tanggal' => '2026-07-15',
    'jam' => '06:47:09',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  180 => 
  array (
    'id_presensi' => 3143,
    'nis' => 14380,
    'tanggal' => '2026-07-15',
    'jam' => '06:47:11',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  181 => 
  array (
    'id_presensi' => 3144,
    'nis' => 14386,
    'tanggal' => '2026-07-15',
    'jam' => '06:47:22',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  182 => 
  array (
    'id_presensi' => 3145,
    'nis' => 13906,
    'tanggal' => '2026-07-15',
    'jam' => '06:47:27',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  183 => 
  array (
    'id_presensi' => 3146,
    'nis' => 14612,
    'tanggal' => '2026-07-15',
    'jam' => '06:47:51',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  184 => 
  array (
    'id_presensi' => 3147,
    'nis' => 13924,
    'tanggal' => '2026-07-15',
    'jam' => '06:48:25',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  185 => 
  array (
    'id_presensi' => 3148,
    'nis' => 14163,
    'tanggal' => '2026-07-15',
    'jam' => '06:48:38',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  186 => 
  array (
    'id_presensi' => 3149,
    'nis' => 14571,
    'tanggal' => '2026-07-15',
    'jam' => '06:49:02',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  187 => 
  array (
    'id_presensi' => 3150,
    'nis' => 14753,
    'tanggal' => '2026-07-15',
    'jam' => '06:49:06',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  188 => 
  array (
    'id_presensi' => 3151,
    'nis' => 14550,
    'tanggal' => '2026-07-15',
    'jam' => '06:49:08',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  189 => 
  array (
    'id_presensi' => 3152,
    'nis' => 14546,
    'tanggal' => '2026-07-15',
    'jam' => '06:49:08',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  190 => 
  array (
    'id_presensi' => 3153,
    'nis' => 14153,
    'tanggal' => '2026-07-15',
    'jam' => '06:49:10',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  191 => 
  array (
    'id_presensi' => 3154,
    'nis' => 14576,
    'tanggal' => '2026-07-15',
    'jam' => '06:49:10',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  192 => 
  array (
    'id_presensi' => 3155,
    'nis' => 14286,
    'tanggal' => '2026-07-15',
    'jam' => '06:49:29',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  193 => 
  array (
    'id_presensi' => 3156,
    'nis' => 14302,
    'tanggal' => '2026-07-15',
    'jam' => '06:50:01',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  194 => 
  array (
    'id_presensi' => 3157,
    'nis' => 13993,
    'tanggal' => '2026-07-15',
    'jam' => '06:50:07',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  195 => 
  array (
    'id_presensi' => 3158,
    'nis' => 14658,
    'tanggal' => '2026-07-15',
    'jam' => '06:50:09',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  196 => 
  array (
    'id_presensi' => 3159,
    'nis' => 14671,
    'tanggal' => '2026-07-15',
    'jam' => '06:50:11',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  197 => 
  array (
    'id_presensi' => 3160,
    'nis' => 14762,
    'tanggal' => '2026-07-15',
    'jam' => '06:50:12',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  198 => 
  array (
    'id_presensi' => 3161,
    'nis' => 14648,
    'tanggal' => '2026-07-15',
    'jam' => '06:50:16',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  199 => 
  array (
    'id_presensi' => 3162,
    'nis' => 14298,
    'tanggal' => '2026-07-15',
    'jam' => '06:50:21',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
));

        DB::table('presensi')->insert(array (
  0 => 
  array (
    'id_presensi' => 3163,
    'nis' => 14644,
    'tanggal' => '2026-07-15',
    'jam' => '06:50:21',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  1 => 
  array (
    'id_presensi' => 3164,
    'nis' => 14662,
    'tanggal' => '2026-07-15',
    'jam' => '06:50:23',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  2 => 
  array (
    'id_presensi' => 3165,
    'nis' => 14294,
    'tanggal' => '2026-07-15',
    'jam' => '06:50:24',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  3 => 
  array (
    'id_presensi' => 3166,
    'nis' => 14297,
    'tanggal' => '2026-07-15',
    'jam' => '06:50:27',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  4 => 
  array (
    'id_presensi' => 3167,
    'nis' => 14575,
    'tanggal' => '2026-07-15',
    'jam' => '06:50:30',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  5 => 
  array (
    'id_presensi' => 3168,
    'nis' => 13927,
    'tanggal' => '2026-07-15',
    'jam' => '06:50:33',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  6 => 
  array (
    'id_presensi' => 3169,
    'nis' => 14618,
    'tanggal' => '2026-07-15',
    'jam' => '06:50:36',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  7 => 
  array (
    'id_presensi' => 3170,
    'nis' => 13922,
    'tanggal' => '2026-07-15',
    'jam' => '06:50:38',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  8 => 
  array (
    'id_presensi' => 3171,
    'nis' => 14561,
    'tanggal' => '2026-07-15',
    'jam' => '06:50:48',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  9 => 
  array (
    'id_presensi' => 3172,
    'nis' => 14657,
    'tanggal' => '2026-07-15',
    'jam' => '06:50:59',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  10 => 
  array (
    'id_presensi' => 3173,
    'nis' => 13912,
    'tanggal' => '2026-07-15',
    'jam' => '06:51:00',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  11 => 
  array (
    'id_presensi' => 3174,
    'nis' => 14667,
    'tanggal' => '2026-07-15',
    'jam' => '06:51:02',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  12 => 
  array (
    'id_presensi' => 3175,
    'nis' => 14673,
    'tanggal' => '2026-07-15',
    'jam' => '06:51:04',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  13 => 
  array (
    'id_presensi' => 3176,
    'nis' => 13902,
    'tanggal' => '2026-07-15',
    'jam' => '06:51:05',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  14 => 
  array (
    'id_presensi' => 3177,
    'nis' => 13977,
    'tanggal' => '2026-07-15',
    'jam' => '06:51:07',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  15 => 
  array (
    'id_presensi' => 3178,
    'nis' => 13908,
    'tanggal' => '2026-07-15',
    'jam' => '06:51:08',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  16 => 
  array (
    'id_presensi' => 3179,
    'nis' => 13864,
    'tanggal' => '2026-07-15',
    'jam' => '06:51:09',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  17 => 
  array (
    'id_presensi' => 3180,
    'nis' => 14752,
    'tanggal' => '2026-07-15',
    'jam' => '06:51:11',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  18 => 
  array (
    'id_presensi' => 3181,
    'nis' => 13980,
    'tanggal' => '2026-07-15',
    'jam' => '06:51:13',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  19 => 
  array (
    'id_presensi' => 3182,
    'nis' => 13877,
    'tanggal' => '2026-07-15',
    'jam' => '06:51:13',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  20 => 
  array (
    'id_presensi' => 3183,
    'nis' => 14747,
    'tanggal' => '2026-07-15',
    'jam' => '06:51:16',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  21 => 
  array (
    'id_presensi' => 3184,
    'nis' => 14395,
    'tanggal' => '2026-07-15',
    'jam' => '06:51:22',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  22 => 
  array (
    'id_presensi' => 3185,
    'nis' => 14771,
    'tanggal' => '2026-07-15',
    'jam' => '06:51:33',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  23 => 
  array (
    'id_presensi' => 3186,
    'nis' => 14291,
    'tanggal' => '2026-07-15',
    'jam' => '06:51:40',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  24 => 
  array (
    'id_presensi' => 3187,
    'nis' => 13874,
    'tanggal' => '2026-07-15',
    'jam' => '06:51:40',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  25 => 
  array (
    'id_presensi' => 3188,
    'nis' => 14766,
    'tanggal' => '2026-07-15',
    'jam' => '06:51:52',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  26 => 
  array (
    'id_presensi' => 3189,
    'nis' => 14313,
    'tanggal' => '2026-07-15',
    'jam' => '06:52:00',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  27 => 
  array (
    'id_presensi' => 3190,
    'nis' => 14288,
    'tanggal' => '2026-07-15',
    'jam' => '06:52:02',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  28 => 
  array (
    'id_presensi' => 3191,
    'nis' => 14647,
    'tanggal' => '2026-07-15',
    'jam' => '06:52:05',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  29 => 
  array (
    'id_presensi' => 3192,
    'nis' => 14651,
    'tanggal' => '2026-07-15',
    'jam' => '06:52:07',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  30 => 
  array (
    'id_presensi' => 3193,
    'nis' => 14296,
    'tanggal' => '2026-07-15',
    'jam' => '06:52:09',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  31 => 
  array (
    'id_presensi' => 3194,
    'nis' => 14557,
    'tanggal' => '2026-07-15',
    'jam' => '06:52:11',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  32 => 
  array (
    'id_presensi' => 3195,
    'nis' => 14558,
    'tanggal' => '2026-07-15',
    'jam' => '06:52:13',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  33 => 
  array (
    'id_presensi' => 3196,
    'nis' => 14312,
    'tanggal' => '2026-07-15',
    'jam' => '06:52:13',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  34 => 
  array (
    'id_presensi' => 3197,
    'nis' => 14757,
    'tanggal' => '2026-07-15',
    'jam' => '06:52:20',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  35 => 
  array (
    'id_presensi' => 3198,
    'nis' => 14549,
    'tanggal' => '2026-07-15',
    'jam' => '06:52:21',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  36 => 
  array (
    'id_presensi' => 3199,
    'nis' => 14318,
    'tanggal' => '2026-07-15',
    'jam' => '06:52:24',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  37 => 
  array (
    'id_presensi' => 3200,
    'nis' => 14309,
    'tanggal' => '2026-07-15',
    'jam' => '06:52:28',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  38 => 
  array (
    'id_presensi' => 3201,
    'nis' => 14758,
    'tanggal' => '2026-07-15',
    'jam' => '06:52:29',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  39 => 
  array (
    'id_presensi' => 3202,
    'nis' => 14751,
    'tanggal' => '2026-07-15',
    'jam' => '06:52:32',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  40 => 
  array (
    'id_presensi' => 3203,
    'nis' => 14788,
    'tanggal' => '2026-07-15',
    'jam' => '06:52:33',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  41 => 
  array (
    'id_presensi' => 3204,
    'nis' => 14785,
    'tanggal' => '2026-07-15',
    'jam' => '06:52:39',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  42 => 
  array (
    'id_presensi' => 3205,
    'nis' => 14786,
    'tanggal' => '2026-07-15',
    'jam' => '06:52:41',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  43 => 
  array (
    'id_presensi' => 3206,
    'nis' => 14787,
    'tanggal' => '2026-07-15',
    'jam' => '06:52:41',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  44 => 
  array (
    'id_presensi' => 3207,
    'nis' => 14778,
    'tanggal' => '2026-07-15',
    'jam' => '06:52:46',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  45 => 
  array (
    'id_presensi' => 3208,
    'nis' => 14796,
    'tanggal' => '2026-07-15',
    'jam' => '06:52:51',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  46 => 
  array (
    'id_presensi' => 3209,
    'nis' => 14577,
    'tanggal' => '2026-07-15',
    'jam' => '06:52:58',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  47 => 
  array (
    'id_presensi' => 3210,
    'nis' => 14765,
    'tanggal' => '2026-07-15',
    'jam' => '06:52:59',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  48 => 
  array (
    'id_presensi' => 3211,
    'nis' => 14513,
    'tanggal' => '2026-07-15',
    'jam' => '06:53:01',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  49 => 
  array (
    'id_presensi' => 3212,
    'nis' => 14519,
    'tanggal' => '2026-07-15',
    'jam' => '06:53:03',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  50 => 
  array (
    'id_presensi' => 3213,
    'nis' => 14327,
    'tanggal' => '2026-07-15',
    'jam' => '06:53:07',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  51 => 
  array (
    'id_presensi' => 3214,
    'nis' => 14522,
    'tanggal' => '2026-07-15',
    'jam' => '06:53:09',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  52 => 
  array (
    'id_presensi' => 3215,
    'nis' => 14779,
    'tanggal' => '2026-07-15',
    'jam' => '06:53:11',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  53 => 
  array (
    'id_presensi' => 3216,
    'nis' => 14523,
    'tanggal' => '2026-07-15',
    'jam' => '06:53:14',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  54 => 
  array (
    'id_presensi' => 3217,
    'nis' => 14628,
    'tanggal' => '2026-07-15',
    'jam' => '06:53:17',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  55 => 
  array (
    'id_presensi' => 3218,
    'nis' => 14539,
    'tanggal' => '2026-07-15',
    'jam' => '06:53:19',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  56 => 
  array (
    'id_presensi' => 3219,
    'nis' => 14629,
    'tanggal' => '2026-07-15',
    'jam' => '06:53:23',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  57 => 
  array (
    'id_presensi' => 3220,
    'nis' => 14659,
    'tanggal' => '2026-07-15',
    'jam' => '06:53:23',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  58 => 
  array (
    'id_presensi' => 3221,
    'nis' => 14525,
    'tanggal' => '2026-07-15',
    'jam' => '06:53:28',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  59 => 
  array (
    'id_presensi' => 3222,
    'nis' => 14142,
    'tanggal' => '2026-07-15',
    'jam' => '06:53:28',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  60 => 
  array (
    'id_presensi' => 3223,
    'nis' => 14333,
    'tanggal' => '2026-07-15',
    'jam' => '06:53:32',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  61 => 
  array (
    'id_presensi' => 3224,
    'nis' => 14022,
    'tanggal' => '2026-07-15',
    'jam' => '06:53:34',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  62 => 
  array (
    'id_presensi' => 3225,
    'nis' => 14331,
    'tanggal' => '2026-07-15',
    'jam' => '06:53:35',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  63 => 
  array (
    'id_presensi' => 3226,
    'nis' => 14399,
    'tanggal' => '2026-07-15',
    'jam' => '06:53:38',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  64 => 
  array (
    'id_presensi' => 3227,
    'nis' => 14391,
    'tanggal' => '2026-07-15',
    'jam' => '06:53:40',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  65 => 
  array (
    'id_presensi' => 3228,
    'nis' => 14397,
    'tanggal' => '2026-07-15',
    'jam' => '06:53:40',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  66 => 
  array (
    'id_presensi' => 3229,
    'nis' => 14164,
    'tanggal' => '2026-07-15',
    'jam' => '06:53:40',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  67 => 
  array (
    'id_presensi' => 3230,
    'nis' => 14405,
    'tanggal' => '2026-07-15',
    'jam' => '06:53:43',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  68 => 
  array (
    'id_presensi' => 3231,
    'nis' => 14406,
    'tanggal' => '2026-07-15',
    'jam' => '06:53:45',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  69 => 
  array (
    'id_presensi' => 3232,
    'nis' => 14396,
    'tanggal' => '2026-07-15',
    'jam' => '06:53:47',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  70 => 
  array (
    'id_presensi' => 3233,
    'nis' => 14417,
    'tanggal' => '2026-07-15',
    'jam' => '06:53:48',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  71 => 
  array (
    'id_presensi' => 3234,
    'nis' => 14414,
    'tanggal' => '2026-07-15',
    'jam' => '06:53:49',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  72 => 
  array (
    'id_presensi' => 3235,
    'nis' => 14416,
    'tanggal' => '2026-07-15',
    'jam' => '06:53:52',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  73 => 
  array (
    'id_presensi' => 3236,
    'nis' => 14389,
    'tanggal' => '2026-07-15',
    'jam' => '06:53:54',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  74 => 
  array (
    'id_presensi' => 3237,
    'nis' => 14026,
    'tanggal' => '2026-07-15',
    'jam' => '06:53:54',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  75 => 
  array (
    'id_presensi' => 3238,
    'nis' => 14394,
    'tanggal' => '2026-07-15',
    'jam' => '06:53:56',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  76 => 
  array (
    'id_presensi' => 3239,
    'nis' => 13887,
    'tanggal' => '2026-07-15',
    'jam' => '06:53:59',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  77 => 
  array (
    'id_presensi' => 3240,
    'nis' => 14413,
    'tanggal' => '2026-07-15',
    'jam' => '06:54:02',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  78 => 
  array (
    'id_presensi' => 3241,
    'nis' => 13997,
    'tanggal' => '2026-07-15',
    'jam' => '06:54:02',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  79 => 
  array (
    'id_presensi' => 3242,
    'nis' => 14403,
    'tanggal' => '2026-07-15',
    'jam' => '06:54:04',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  80 => 
  array (
    'id_presensi' => 3243,
    'nis' => 14292,
    'tanggal' => '2026-07-15',
    'jam' => '06:54:06',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  81 => 
  array (
    'id_presensi' => 3244,
    'nis' => 14305,
    'tanggal' => '2026-07-15',
    'jam' => '06:54:11',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  82 => 
  array (
    'id_presensi' => 3245,
    'nis' => 14004,
    'tanggal' => '2026-07-15',
    'jam' => '06:54:12',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  83 => 
  array (
    'id_presensi' => 3246,
    'nis' => 14023,
    'tanggal' => '2026-07-15',
    'jam' => '06:54:15',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  84 => 
  array (
    'id_presensi' => 3247,
    'nis' => 14656,
    'tanggal' => '2026-07-15',
    'jam' => '06:54:25',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  85 => 
  array (
    'id_presensi' => 3248,
    'nis' => 14171,
    'tanggal' => '2026-07-15',
    'jam' => '06:54:26',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  86 => 
  array (
    'id_presensi' => 3249,
    'nis' => 14174,
    'tanggal' => '2026-07-15',
    'jam' => '06:54:29',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  87 => 
  array (
    'id_presensi' => 3250,
    'nis' => 14330,
    'tanggal' => '2026-07-15',
    'jam' => '06:54:32',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  88 => 
  array (
    'id_presensi' => 3251,
    'nis' => 14173,
    'tanggal' => '2026-07-15',
    'jam' => '06:54:35',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  89 => 
  array (
    'id_presensi' => 3252,
    'nis' => 14115,
    'tanggal' => '2026-07-15',
    'jam' => '06:54:41',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  90 => 
  array (
    'id_presensi' => 3253,
    'nis' => 14338,
    'tanggal' => '2026-07-15',
    'jam' => '06:54:44',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  91 => 
  array (
    'id_presensi' => 3254,
    'nis' => 14106,
    'tanggal' => '2026-07-15',
    'jam' => '06:54:48',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  92 => 
  array (
    'id_presensi' => 3255,
    'nis' => 14160,
    'tanggal' => '2026-07-15',
    'jam' => '06:54:51',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  93 => 
  array (
    'id_presensi' => 3256,
    'nis' => 14136,
    'tanggal' => '2026-07-15',
    'jam' => '06:54:53',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  94 => 
  array (
    'id_presensi' => 3257,
    'nis' => 14316,
    'tanggal' => '2026-07-15',
    'jam' => '06:54:54',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  95 => 
  array (
    'id_presensi' => 3258,
    'nis' => 14117,
    'tanggal' => '2026-07-15',
    'jam' => '06:55:00',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  96 => 
  array (
    'id_presensi' => 3259,
    'nis' => 14412,
    'tanggal' => '2026-07-15',
    'jam' => '06:55:02',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  97 => 
  array (
    'id_presensi' => 3260,
    'nis' => 13905,
    'tanggal' => '2026-07-15',
    'jam' => '06:55:03',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  98 => 
  array (
    'id_presensi' => 3261,
    'nis' => 14544,
    'tanggal' => '2026-07-15',
    'jam' => '06:55:04',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  99 => 
  array (
    'id_presensi' => 3262,
    'nis' => 14545,
    'tanggal' => '2026-07-15',
    'jam' => '06:55:06',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  100 => 
  array (
    'id_presensi' => 3263,
    'nis' => 14411,
    'tanggal' => '2026-07-15',
    'jam' => '06:55:09',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  101 => 
  array (
    'id_presensi' => 3264,
    'nis' => 14119,
    'tanggal' => '2026-07-15',
    'jam' => '06:55:10',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  102 => 
  array (
    'id_presensi' => 3265,
    'nis' => 14566,
    'tanggal' => '2026-07-15',
    'jam' => '06:55:11',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  103 => 
  array (
    'id_presensi' => 3266,
    'nis' => 13888,
    'tanggal' => '2026-07-15',
    'jam' => '06:55:13',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  104 => 
  array (
    'id_presensi' => 3267,
    'nis' => 14415,
    'tanggal' => '2026-07-15',
    'jam' => '06:55:13',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  105 => 
  array (
    'id_presensi' => 3268,
    'nis' => 14372,
    'tanggal' => '2026-07-15',
    'jam' => '06:55:16',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  106 => 
  array (
    'id_presensi' => 3269,
    'nis' => 13901,
    'tanggal' => '2026-07-15',
    'jam' => '06:55:20',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  107 => 
  array (
    'id_presensi' => 3270,
    'nis' => 14559,
    'tanggal' => '2026-07-15',
    'jam' => '06:55:26',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  108 => 
  array (
    'id_presensi' => 3271,
    'nis' => 14134,
    'tanggal' => '2026-07-15',
    'jam' => '06:55:32',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  109 => 
  array (
    'id_presensi' => 3272,
    'nis' => 14108,
    'tanggal' => '2026-07-15',
    'jam' => '06:55:34',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  110 => 
  array (
    'id_presensi' => 3273,
    'nis' => 14116,
    'tanggal' => '2026-07-15',
    'jam' => '06:55:37',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  111 => 
  array (
    'id_presensi' => 3274,
    'nis' => 14620,
    'tanggal' => '2026-07-15',
    'jam' => '06:55:39',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  112 => 
  array (
    'id_presensi' => 3275,
    'nis' => 14118,
    'tanggal' => '2026-07-15',
    'jam' => '06:55:41',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  113 => 
  array (
    'id_presensi' => 3276,
    'nis' => 14617,
    'tanggal' => '2026-07-15',
    'jam' => '06:55:42',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  114 => 
  array (
    'id_presensi' => 3277,
    'nis' => 14293,
    'tanggal' => '2026-07-15',
    'jam' => '06:55:47',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  115 => 
  array (
    'id_presensi' => 3278,
    'nis' => 14615,
    'tanggal' => '2026-07-15',
    'jam' => '06:55:49',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  116 => 
  array (
    'id_presensi' => 3279,
    'nis' => 14300,
    'tanggal' => '2026-07-15',
    'jam' => '06:55:52',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  117 => 
  array (
    'id_presensi' => 3280,
    'nis' => 13886,
    'tanggal' => '2026-07-15',
    'jam' => '06:55:53',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  118 => 
  array (
    'id_presensi' => 3281,
    'nis' => 14323,
    'tanggal' => '2026-07-15',
    'jam' => '06:56:09',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  119 => 
  array (
    'id_presensi' => 3282,
    'nis' => 14167,
    'tanggal' => '2026-07-15',
    'jam' => '06:56:12',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  120 => 
  array (
    'id_presensi' => 3283,
    'nis' => 14562,
    'tanggal' => '2026-07-15',
    'jam' => '06:56:12',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  121 => 
  array (
    'id_presensi' => 3284,
    'nis' => 14623,
    'tanggal' => '2026-07-15',
    'jam' => '06:56:14',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  122 => 
  array (
    'id_presensi' => 3285,
    'nis' => 13971,
    'tanggal' => '2026-07-15',
    'jam' => '06:56:17',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  123 => 
  array (
    'id_presensi' => 3286,
    'nis' => 14624,
    'tanggal' => '2026-07-15',
    'jam' => '06:56:17',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  124 => 
  array (
    'id_presensi' => 3287,
    'nis' => 14392,
    'tanggal' => '2026-07-15',
    'jam' => '06:56:19',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  125 => 
  array (
    'id_presensi' => 3288,
    'nis' => 14376,
    'tanggal' => '2026-07-15',
    'jam' => '06:56:20',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  126 => 
  array (
    'id_presensi' => 3289,
    'nis' => 14146,
    'tanggal' => '2026-07-15',
    'jam' => '06:56:22',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  127 => 
  array (
    'id_presensi' => 3290,
    'nis' => 13872,
    'tanggal' => '2026-07-15',
    'jam' => '06:56:23',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  128 => 
  array (
    'id_presensi' => 3291,
    'nis' => 14760,
    'tanggal' => '2026-07-15',
    'jam' => '06:56:26',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  129 => 
  array (
    'id_presensi' => 3292,
    'nis' => 14158,
    'tanggal' => '2026-07-15',
    'jam' => '06:56:27',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  130 => 
  array (
    'id_presensi' => 3293,
    'nis' => 14754,
    'tanggal' => '2026-07-15',
    'jam' => '06:56:30',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  131 => 
  array (
    'id_presensi' => 3294,
    'nis' => 14542,
    'tanggal' => '2026-07-15',
    'jam' => '06:56:31',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  132 => 
  array (
    'id_presensi' => 3295,
    'nis' => 14538,
    'tanggal' => '2026-07-15',
    'jam' => '06:56:36',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  133 => 
  array (
    'id_presensi' => 3296,
    'nis' => 14219,
    'tanggal' => '2026-07-15',
    'jam' => '06:56:36',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  134 => 
  array (
    'id_presensi' => 3297,
    'nis' => 14531,
    'tanggal' => '2026-07-15',
    'jam' => '06:56:39',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  135 => 
  array (
    'id_presensi' => 3298,
    'nis' => 14802,
    'tanggal' => '2026-07-15',
    'jam' => '06:56:42',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  136 => 
  array (
    'id_presensi' => 3299,
    'nis' => 14130,
    'tanggal' => '2026-07-15',
    'jam' => '06:56:45',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  137 => 
  array (
    'id_presensi' => 3300,
    'nis' => 14761,
    'tanggal' => '2026-07-15',
    'jam' => '06:56:48',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  138 => 
  array (
    'id_presensi' => 3301,
    'nis' => 14795,
    'tanggal' => '2026-07-15',
    'jam' => '06:56:58',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  139 => 
  array (
    'id_presensi' => 3302,
    'nis' => 14552,
    'tanggal' => '2026-07-15',
    'jam' => '06:57:03',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  140 => 
  array (
    'id_presensi' => 3303,
    'nis' => 14335,
    'tanggal' => '2026-07-15',
    'jam' => '06:57:06',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  141 => 
  array (
    'id_presensi' => 3304,
    'nis' => 14329,
    'tanggal' => '2026-07-15',
    'jam' => '06:57:10',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  142 => 
  array (
    'id_presensi' => 3305,
    'nis' => 13898,
    'tanggal' => '2026-07-15',
    'jam' => '06:57:13',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  143 => 
  array (
    'id_presensi' => 3306,
    'nis' => 14804,
    'tanggal' => '2026-07-15',
    'jam' => '06:57:14',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  144 => 
  array (
    'id_presensi' => 3307,
    'nis' => 14123,
    'tanggal' => '2026-07-15',
    'jam' => '06:57:14',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  145 => 
  array (
    'id_presensi' => 3308,
    'nis' => 14777,
    'tanggal' => '2026-07-15',
    'jam' => '06:57:16',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  146 => 
  array (
    'id_presensi' => 3309,
    'nis' => 14325,
    'tanggal' => '2026-07-15',
    'jam' => '06:57:18',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  147 => 
  array (
    'id_presensi' => 3310,
    'nis' => 13904,
    'tanggal' => '2026-07-15',
    'jam' => '06:57:19',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  148 => 
  array (
    'id_presensi' => 3311,
    'nis' => 14287,
    'tanggal' => '2026-07-15',
    'jam' => '06:57:20',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  149 => 
  array (
    'id_presensi' => 3312,
    'nis' => 14124,
    'tanggal' => '2026-07-15',
    'jam' => '06:57:22',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  150 => 
  array (
    'id_presensi' => 3313,
    'nis' => 14803,
    'tanggal' => '2026-07-15',
    'jam' => '06:57:23',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  151 => 
  array (
    'id_presensi' => 3314,
    'nis' => 14792,
    'tanggal' => '2026-07-15',
    'jam' => '06:57:25',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  152 => 
  array (
    'id_presensi' => 3315,
    'nis' => 14111,
    'tanggal' => '2026-07-15',
    'jam' => '06:57:25',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  153 => 
  array (
    'id_presensi' => 3316,
    'nis' => 14121,
    'tanggal' => '2026-07-15',
    'jam' => '06:57:29',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  154 => 
  array (
    'id_presensi' => 3317,
    'nis' => 14635,
    'tanggal' => '2026-07-15',
    'jam' => '06:57:37',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  155 => 
  array (
    'id_presensi' => 3318,
    'nis' => 13866,
    'tanggal' => '2026-07-15',
    'jam' => '06:57:39',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  156 => 
  array (
    'id_presensi' => 3319,
    'nis' => 14247,
    'tanggal' => '2026-07-15',
    'jam' => '06:57:40',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  157 => 
  array (
    'id_presensi' => 3320,
    'nis' => 14764,
    'tanggal' => '2026-07-15',
    'jam' => '06:57:40',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  158 => 
  array (
    'id_presensi' => 3321,
    'nis' => 14794,
    'tanggal' => '2026-07-15',
    'jam' => '06:57:41',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  159 => 
  array (
    'id_presensi' => 3322,
    'nis' => 14768,
    'tanggal' => '2026-07-15',
    'jam' => '06:57:42',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  160 => 
  array (
    'id_presensi' => 3323,
    'nis' => 14782,
    'tanggal' => '2026-07-15',
    'jam' => '06:57:43',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  161 => 
  array (
    'id_presensi' => 3324,
    'nis' => 14746,
    'tanggal' => '2026-07-15',
    'jam' => '06:57:46',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  162 => 
  array (
    'id_presensi' => 3325,
    'nis' => 13925,
    'tanggal' => '2026-07-15',
    'jam' => '06:57:50',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  163 => 
  array (
    'id_presensi' => 3326,
    'nis' => 14776,
    'tanggal' => '2026-07-15',
    'jam' => '06:57:54',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  164 => 
  array (
    'id_presensi' => 3327,
    'nis' => 14231,
    'tanggal' => '2026-07-15',
    'jam' => '06:57:55',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  165 => 
  array (
    'id_presensi' => 3328,
    'nis' => 14790,
    'tanggal' => '2026-07-15',
    'jam' => '06:57:57',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  166 => 
  array (
    'id_presensi' => 3329,
    'nis' => 14217,
    'tanggal' => '2026-07-15',
    'jam' => '06:58:00',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  167 => 
  array (
    'id_presensi' => 3330,
    'nis' => 13881,
    'tanggal' => '2026-07-15',
    'jam' => '06:58:01',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  168 => 
  array (
    'id_presensi' => 3331,
    'nis' => 14668,
    'tanggal' => '2026-07-15',
    'jam' => '06:58:03',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  169 => 
  array (
    'id_presensi' => 3332,
    'nis' => 14222,
    'tanggal' => '2026-07-15',
    'jam' => '06:58:04',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  170 => 
  array (
    'id_presensi' => 3333,
    'nis' => 14801,
    'tanggal' => '2026-07-15',
    'jam' => '06:58:06',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  171 => 
  array (
    'id_presensi' => 3334,
    'nis' => 14409,
    'tanggal' => '2026-07-15',
    'jam' => '06:58:06',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  172 => 
  array (
    'id_presensi' => 3335,
    'nis' => 14798,
    'tanggal' => '2026-07-15',
    'jam' => '06:58:08',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  173 => 
  array (
    'id_presensi' => 3336,
    'nis' => 14800,
    'tanggal' => '2026-07-15',
    'jam' => '06:58:09',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  174 => 
  array (
    'id_presensi' => 3337,
    'nis' => 14242,
    'tanggal' => '2026-07-15',
    'jam' => '06:58:10',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  175 => 
  array (
    'id_presensi' => 3338,
    'nis' => 14756,
    'tanggal' => '2026-07-15',
    'jam' => '06:58:12',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  176 => 
  array (
    'id_presensi' => 3339,
    'nis' => 14239,
    'tanggal' => '2026-07-15',
    'jam' => '06:58:13',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  177 => 
  array (
    'id_presensi' => 3340,
    'nis' => 14337,
    'tanggal' => '2026-07-15',
    'jam' => '06:58:16',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  178 => 
  array (
    'id_presensi' => 3341,
    'nis' => 14234,
    'tanggal' => '2026-07-15',
    'jam' => '06:58:18',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  179 => 
  array (
    'id_presensi' => 3342,
    'nis' => 13910,
    'tanggal' => '2026-07-15',
    'jam' => '06:58:27',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  180 => 
  array (
    'id_presensi' => 3343,
    'nis' => 13869,
    'tanggal' => '2026-07-15',
    'jam' => '06:58:28',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  181 => 
  array (
    'id_presensi' => 3344,
    'nis' => 13907,
    'tanggal' => '2026-07-15',
    'jam' => '06:58:31',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  182 => 
  array (
    'id_presensi' => 3345,
    'nis' => 14224,
    'tanggal' => '2026-07-15',
    'jam' => '06:58:33',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  183 => 
  array (
    'id_presensi' => 3346,
    'nis' => 13895,
    'tanggal' => '2026-07-15',
    'jam' => '06:58:36',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  184 => 
  array (
    'id_presensi' => 3347,
    'nis' => 14016,
    'tanggal' => '2026-07-15',
    'jam' => '06:58:37',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  185 => 
  array (
    'id_presensi' => 3348,
    'nis' => 14018,
    'tanggal' => '2026-07-15',
    'jam' => '06:58:40',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  186 => 
  array (
    'id_presensi' => 3349,
    'nis' => 14520,
    'tanggal' => '2026-07-15',
    'jam' => '06:58:41',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  187 => 
  array (
    'id_presensi' => 3350,
    'nis' => 14626,
    'tanggal' => '2026-07-15',
    'jam' => '06:58:41',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  188 => 
  array (
    'id_presensi' => 3351,
    'nis' => 14006,
    'tanggal' => '2026-07-15',
    'jam' => '06:58:44',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  189 => 
  array (
    'id_presensi' => 3352,
    'nis' => 14214,
    'tanggal' => '2026-07-15',
    'jam' => '06:58:47',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  190 => 
  array (
    'id_presensi' => 3353,
    'nis' => 14002,
    'tanggal' => '2026-07-15',
    'jam' => '06:58:48',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  191 => 
  array (
    'id_presensi' => 3354,
    'nis' => 14306,
    'tanggal' => '2026-07-15',
    'jam' => '06:58:49',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  192 => 
  array (
    'id_presensi' => 3355,
    'nis' => 14152,
    'tanggal' => '2026-07-15',
    'jam' => '06:58:50',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  193 => 
  array (
    'id_presensi' => 3356,
    'nis' => 13894,
    'tanggal' => '2026-07-15',
    'jam' => '06:58:50',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  194 => 
  array (
    'id_presensi' => 3357,
    'nis' => 13981,
    'tanggal' => '2026-07-15',
    'jam' => '06:58:52',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  195 => 
  array (
    'id_presensi' => 3358,
    'nis' => 14168,
    'tanggal' => '2026-07-15',
    'jam' => '06:58:54',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  196 => 
  array (
    'id_presensi' => 3359,
    'nis' => 14535,
    'tanggal' => '2026-07-15',
    'jam' => '06:58:54',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  197 => 
  array (
    'id_presensi' => 3360,
    'nis' => 14013,
    'tanggal' => '2026-07-15',
    'jam' => '06:58:56',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  198 => 
  array (
    'id_presensi' => 3361,
    'nis' => 14537,
    'tanggal' => '2026-07-15',
    'jam' => '06:58:57',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  199 => 
  array (
    'id_presensi' => 3362,
    'nis' => 13873,
    'tanggal' => '2026-07-15',
    'jam' => '06:58:58',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
));

        DB::table('presensi')->insert(array (
  0 => 
  array (
    'id_presensi' => 3363,
    'nis' => 14011,
    'tanggal' => '2026-07-15',
    'jam' => '06:58:59',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  1 => 
  array (
    'id_presensi' => 3364,
    'nis' => 14314,
    'tanggal' => '2026-07-15',
    'jam' => '06:58:59',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  2 => 
  array (
    'id_presensi' => 3365,
    'nis' => 14797,
    'tanggal' => '2026-07-15',
    'jam' => '06:59:01',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  3 => 
  array (
    'id_presensi' => 3366,
    'nis' => 14541,
    'tanggal' => '2026-07-15',
    'jam' => '06:59:02',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  4 => 
  array (
    'id_presensi' => 3367,
    'nis' => 14301,
    'tanggal' => '2026-07-15',
    'jam' => '06:59:03',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  5 => 
  array (
    'id_presensi' => 3368,
    'nis' => 14540,
    'tanggal' => '2026-07-15',
    'jam' => '06:59:04',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  6 => 
  array (
    'id_presensi' => 3369,
    'nis' => 14669,
    'tanggal' => '2026-07-15',
    'jam' => '06:59:07',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  7 => 
  array (
    'id_presensi' => 3370,
    'nis' => 13992,
    'tanggal' => '2026-07-15',
    'jam' => '06:59:08',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  8 => 
  array (
    'id_presensi' => 3371,
    'nis' => 14653,
    'tanggal' => '2026-07-15',
    'jam' => '06:59:11',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  9 => 
  array (
    'id_presensi' => 3372,
    'nis' => 14367,
    'tanggal' => '2026-07-15',
    'jam' => '06:59:11',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  10 => 
  array (
    'id_presensi' => 3373,
    'nis' => 14308,
    'tanggal' => '2026-07-15',
    'jam' => '06:59:13',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  11 => 
  array (
    'id_presensi' => 3374,
    'nis' => 13900,
    'tanggal' => '2026-07-15',
    'jam' => '06:59:14',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  12 => 
  array (
    'id_presensi' => 3375,
    'nis' => 13967,
    'tanggal' => '2026-07-15',
    'jam' => '06:59:14',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  13 => 
  array (
    'id_presensi' => 3376,
    'nis' => 13978,
    'tanggal' => '2026-07-15',
    'jam' => '06:59:17',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  14 => 
  array (
    'id_presensi' => 3377,
    'nis' => 13998,
    'tanggal' => '2026-07-15',
    'jam' => '06:59:19',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  15 => 
  array (
    'id_presensi' => 3378,
    'nis' => 14332,
    'tanggal' => '2026-07-15',
    'jam' => '06:59:19',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  16 => 
  array (
    'id_presensi' => 3379,
    'nis' => 14322,
    'tanggal' => '2026-07-15',
    'jam' => '06:59:24',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  17 => 
  array (
    'id_presensi' => 3380,
    'nis' => 14663,
    'tanggal' => '2026-07-15',
    'jam' => '06:59:24',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  18 => 
  array (
    'id_presensi' => 3381,
    'nis' => 14652,
    'tanggal' => '2026-07-15',
    'jam' => '06:59:28',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  19 => 
  array (
    'id_presensi' => 3382,
    'nis' => 14299,
    'tanggal' => '2026-07-15',
    'jam' => '06:59:30',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  20 => 
  array (
    'id_presensi' => 3383,
    'nis' => 14289,
    'tanggal' => '2026-07-15',
    'jam' => '06:59:31',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  21 => 
  array (
    'id_presensi' => 3384,
    'nis' => 13966,
    'tanggal' => '2026-07-15',
    'jam' => '06:59:34',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  22 => 
  array (
    'id_presensi' => 3385,
    'nis' => 14400,
    'tanggal' => '2026-07-15',
    'jam' => '06:59:36',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  23 => 
  array (
    'id_presensi' => 3386,
    'nis' => 14554,
    'tanggal' => '2026-07-15',
    'jam' => '06:59:36',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  24 => 
  array (
    'id_presensi' => 3387,
    'nis' => 13918,
    'tanggal' => '2026-07-15',
    'jam' => '06:59:39',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  25 => 
  array (
    'id_presensi' => 3388,
    'nis' => 14157,
    'tanggal' => '2026-07-15',
    'jam' => '06:59:53',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  26 => 
  array (
    'id_presensi' => 3389,
    'nis' => 14122,
    'tanggal' => '2026-07-15',
    'jam' => '06:59:54',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  27 => 
  array (
    'id_presensi' => 3390,
    'nis' => 14650,
    'tanggal' => '2026-07-15',
    'jam' => '07:00:02',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  28 => 
  array (
    'id_presensi' => 3391,
    'nis' => 14649,
    'tanggal' => '2026-07-15',
    'jam' => '07:00:07',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  29 => 
  array (
    'id_presensi' => 3392,
    'nis' => 14319,
    'tanggal' => '2026-07-15',
    'jam' => '07:00:14',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  30 => 
  array (
    'id_presensi' => 3393,
    'nis' => 13890,
    'tanggal' => '2026-07-15',
    'jam' => '07:00:18',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  31 => 
  array (
    'id_presensi' => 3394,
    'nis' => 14326,
    'tanggal' => '2026-07-15',
    'jam' => '07:00:21',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  32 => 
  array (
    'id_presensi' => 3395,
    'nis' => 13871,
    'tanggal' => '2026-07-15',
    'jam' => '07:00:22',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  33 => 
  array (
    'id_presensi' => 3396,
    'nis' => 14393,
    'tanggal' => '2026-07-15',
    'jam' => '07:00:24',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  34 => 
  array (
    'id_presensi' => 3397,
    'nis' => 14799,
    'tanggal' => '2026-07-15',
    'jam' => '07:00:30',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  35 => 
  array (
    'id_presensi' => 3398,
    'nis' => 13990,
    'tanggal' => '2026-07-15',
    'jam' => '07:00:38',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  36 => 
  array (
    'id_presensi' => 3399,
    'nis' => 14791,
    'tanggal' => '2026-07-15',
    'jam' => '07:00:39',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  37 => 
  array (
    'id_presensi' => 3400,
    'nis' => 13973,
    'tanggal' => '2026-07-15',
    'jam' => '07:00:40',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  38 => 
  array (
    'id_presensi' => 3401,
    'nis' => 14112,
    'tanggal' => '2026-07-15',
    'jam' => '07:00:40',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  39 => 
  array (
    'id_presensi' => 3402,
    'nis' => 14789,
    'tanggal' => '2026-07-15',
    'jam' => '07:00:41',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  40 => 
  array (
    'id_presensi' => 3403,
    'nis' => 13996,
    'tanggal' => '2026-07-15',
    'jam' => '07:00:43',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  41 => 
  array (
    'id_presensi' => 3404,
    'nis' => 14110,
    'tanggal' => '2026-07-15',
    'jam' => '07:00:45',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  42 => 
  array (
    'id_presensi' => 3405,
    'nis' => 14005,
    'tanggal' => '2026-07-15',
    'jam' => '07:00:46',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  43 => 
  array (
    'id_presensi' => 3406,
    'nis' => 14748,
    'tanggal' => '2026-07-15',
    'jam' => '07:00:47',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  44 => 
  array (
    'id_presensi' => 3407,
    'nis' => 13999,
    'tanggal' => '2026-07-15',
    'jam' => '07:00:49',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  45 => 
  array (
    'id_presensi' => 3408,
    'nis' => 14109,
    'tanggal' => '2026-07-15',
    'jam' => '07:00:51',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  46 => 
  array (
    'id_presensi' => 3409,
    'nis' => 13883,
    'tanggal' => '2026-07-15',
    'jam' => '07:00:55',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  47 => 
  array (
    'id_presensi' => 3410,
    'nis' => 13914,
    'tanggal' => '2026-07-15',
    'jam' => '07:00:57',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  48 => 
  array (
    'id_presensi' => 3411,
    'nis' => 14303,
    'tanggal' => '2026-07-15',
    'jam' => '07:01:07',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  49 => 
  array (
    'id_presensi' => 3412,
    'nis' => 14384,
    'tanggal' => '2026-07-15',
    'jam' => '07:01:14',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  50 => 
  array (
    'id_presensi' => 3413,
    'nis' => 14780,
    'tanggal' => '2026-07-15',
    'jam' => '07:01:15',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  51 => 
  array (
    'id_presensi' => 3414,
    'nis' => 14320,
    'tanggal' => '2026-07-15',
    'jam' => '07:01:17',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  52 => 
  array (
    'id_presensi' => 3415,
    'nis' => 14324,
    'tanggal' => '2026-07-15',
    'jam' => '07:01:20',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  53 => 
  array (
    'id_presensi' => 3416,
    'nis' => 14636,
    'tanggal' => '2026-07-15',
    'jam' => '07:01:22',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  54 => 
  array (
    'id_presensi' => 3417,
    'nis' => 14328,
    'tanggal' => '2026-07-15',
    'jam' => '07:01:25',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  55 => 
  array (
    'id_presensi' => 3418,
    'nis' => 14336,
    'tanggal' => '2026-07-15',
    'jam' => '07:01:26',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  56 => 
  array (
    'id_presensi' => 3419,
    'nis' => 13862,
    'tanggal' => '2026-07-15',
    'jam' => '07:01:28',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  57 => 
  array (
    'id_presensi' => 3420,
    'nis' => 14783,
    'tanggal' => '2026-07-15',
    'jam' => '07:01:32',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  58 => 
  array (
    'id_presensi' => 3421,
    'nis' => 14120,
    'tanggal' => '2026-07-15',
    'jam' => '07:01:33',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  59 => 
  array (
    'id_presensi' => 3422,
    'nis' => 13995,
    'tanggal' => '2026-07-15',
    'jam' => '07:01:36',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  60 => 
  array (
    'id_presensi' => 3423,
    'nis' => 14144,
    'tanggal' => '2026-07-15',
    'jam' => '07:01:36',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  61 => 
  array (
    'id_presensi' => 3424,
    'nis' => 13916,
    'tanggal' => '2026-07-15',
    'jam' => '07:01:38',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  62 => 
  array (
    'id_presensi' => 3425,
    'nis' => 13974,
    'tanggal' => '2026-07-15',
    'jam' => '07:01:41',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  63 => 
  array (
    'id_presensi' => 3426,
    'nis' => 14032,
    'tanggal' => '2026-07-15',
    'jam' => '07:01:46',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  64 => 
  array (
    'id_presensi' => 3427,
    'nis' => 14534,
    'tanggal' => '2026-07-15',
    'jam' => '07:02:07',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  65 => 
  array (
    'id_presensi' => 3428,
    'nis' => 14127,
    'tanggal' => '2026-07-15',
    'jam' => '07:02:10',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  66 => 
  array (
    'id_presensi' => 3429,
    'nis' => 14017,
    'tanggal' => '2026-07-15',
    'jam' => '07:02:18',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  67 => 
  array (
    'id_presensi' => 3430,
    'nis' => 14009,
    'tanggal' => '2026-07-15',
    'jam' => '07:02:22',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  68 => 
  array (
    'id_presensi' => 3431,
    'nis' => 14030,
    'tanggal' => '2026-07-15',
    'jam' => '07:02:25',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  69 => 
  array (
    'id_presensi' => 3432,
    'nis' => 13913,
    'tanggal' => '2026-07-15',
    'jam' => '07:02:33',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  70 => 
  array (
    'id_presensi' => 3433,
    'nis' => 13983,
    'tanggal' => '2026-07-15',
    'jam' => '07:03:23',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  71 => 
  array (
    'id_presensi' => 3434,
    'nis' => 13989,
    'tanggal' => '2026-07-15',
    'jam' => '07:03:53',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  72 => 
  array (
    'id_presensi' => 3435,
    'nis' => 14012,
    'tanggal' => '2026-07-15',
    'jam' => '07:03:59',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  73 => 
  array (
    'id_presensi' => 3436,
    'nis' => 13991,
    'tanggal' => '2026-07-15',
    'jam' => '07:04:02',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  74 => 
  array (
    'id_presensi' => 3437,
    'nis' => 13984,
    'tanggal' => '2026-07-15',
    'jam' => '07:04:05',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  75 => 
  array (
    'id_presensi' => 3438,
    'nis' => 14025,
    'tanggal' => '2026-07-15',
    'jam' => '07:04:30',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  76 => 
  array (
    'id_presensi' => 3439,
    'nis' => 14015,
    'tanggal' => '2026-07-15',
    'jam' => '07:04:35',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  77 => 
  array (
    'id_presensi' => 3440,
    'nis' => 14014,
    'tanggal' => '2026-07-15',
    'jam' => '07:04:38',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  78 => 
  array (
    'id_presensi' => 3441,
    'nis' => 14029,
    'tanggal' => '2026-07-15',
    'jam' => '07:04:41',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  79 => 
  array (
    'id_presensi' => 3442,
    'nis' => 14007,
    'tanggal' => '2026-07-15',
    'jam' => '07:09:38',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  80 => 
  array (
    'id_presensi' => 3443,
    'nis' => 14236,
    'tanggal' => '2026-07-15',
    'jam' => '07:13:44',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  81 => 
  array (
    'id_presensi' => 3444,
    'nis' => 14244,
    'tanggal' => '2026-07-15',
    'jam' => '07:14:38',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  82 => 
  array (
    'id_presensi' => 3445,
    'nis' => 14223,
    'tanggal' => '2026-07-15',
    'jam' => '07:14:41',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  83 => 
  array (
    'id_presensi' => 3446,
    'nis' => 14261,
    'tanggal' => '2026-07-15',
    'jam' => '07:14:53',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  84 => 
  array (
    'id_presensi' => 3447,
    'nis' => 14235,
    'tanggal' => '2026-07-15',
    'jam' => '07:15:00',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  85 => 
  array (
    'id_presensi' => 3448,
    'nis' => 14240,
    'tanggal' => '2026-07-15',
    'jam' => '07:15:02',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  86 => 
  array (
    'id_presensi' => 3449,
    'nis' => 14232,
    'tanggal' => '2026-07-15',
    'jam' => '07:15:05',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  87 => 
  array (
    'id_presensi' => 3450,
    'nis' => 14237,
    'tanggal' => '2026-07-15',
    'jam' => '07:15:11',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  88 => 
  array (
    'id_presensi' => 3451,
    'nis' => 14248,
    'tanggal' => '2026-07-15',
    'jam' => '07:15:16',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  89 => 
  array (
    'id_presensi' => 3452,
    'nis' => 14230,
    'tanggal' => '2026-07-15',
    'jam' => '07:15:22',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  90 => 
  array (
    'id_presensi' => 3453,
    'nis' => 14233,
    'tanggal' => '2026-07-15',
    'jam' => '07:15:25',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  91 => 
  array (
    'id_presensi' => 3454,
    'nis' => 14220,
    'tanggal' => '2026-07-15',
    'jam' => '07:15:32',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  92 => 
  array (
    'id_presensi' => 3455,
    'nis' => 14000,
    'tanggal' => '2026-07-15',
    'jam' => '07:32:20',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  93 => 
  array (
    'id_presensi' => 3456,
    'nis' => 13876,
    'tanggal' => '2026-07-15',
    'jam' => '07:37:16',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  94 => 
  array (
    'id_presensi' => 3457,
    'nis' => 13882,
    'tanggal' => '2026-07-15',
    'jam' => '07:40:04',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  95 => 
  array (
    'id_presensi' => 3458,
    'nis' => 14610,
    'tanggal' => '2026-07-15',
    'jam' => '07:50:02',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  96 => 
  array (
    'id_presensi' => 3459,
    'nis' => 14311,
    'tanggal' => '2026-07-15',
    'jam' => '07:51:16',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  97 => 
  array (
    'id_presensi' => 3460,
    'nis' => 14172,
    'tanggal' => '2026-07-15',
    'jam' => '07:57:18',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  98 => 
  array (
    'id_presensi' => 3461,
    'nis' => 14156,
    'tanggal' => '2026-07-15',
    'jam' => '07:57:24',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  99 => 
  array (
    'id_presensi' => 3462,
    'nis' => 14148,
    'tanggal' => '2026-07-15',
    'jam' => '07:57:26',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  100 => 
  array (
    'id_presensi' => 3463,
    'nis' => 14138,
    'tanggal' => '2026-07-15',
    'jam' => '07:57:50',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  101 => 
  array (
    'id_presensi' => 3464,
    'nis' => 14155,
    'tanggal' => '2026-07-15',
    'jam' => '07:57:53',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  102 => 
  array (
    'id_presensi' => 3465,
    'nis' => 14755,
    'tanggal' => '2026-07-15',
    'jam' => '08:14:13',
    'status' => '2',
    'keterangan' => NULL,
    'file' => NULL,
  ),
  103 => 
  array (
    'id_presensi' => 3466,
    'nis' => 14772,
    'tanggal' => '2026-07-15',
    'jam' => '08:14:13',
    'status' => '2',
    'keterangan' => NULL,
    'file' => NULL,
  ),
  104 => 
  array (
    'id_presensi' => 3467,
    'nis' => 15382,
    'tanggal' => '2026-07-15',
    'jam' => '08:23:25',
    'status' => '1',
    'keterangan' => NULL,
    'file' => NULL,
  ),
  105 => 
  array (
    'id_presensi' => 3468,
    'nis' => 15383,
    'tanggal' => '2026-07-15',
    'jam' => '08:23:25',
    'status' => '1',
    'keterangan' => NULL,
    'file' => NULL,
  ),
  106 => 
  array (
    'id_presensi' => 3469,
    'nis' => 15384,
    'tanggal' => '2026-07-15',
    'jam' => '08:23:25',
    'status' => '1',
    'keterangan' => NULL,
    'file' => NULL,
  ),
  107 => 
  array (
    'id_presensi' => 3470,
    'nis' => 15385,
    'tanggal' => '2026-07-15',
    'jam' => '08:23:25',
    'status' => '1',
    'keterangan' => NULL,
    'file' => NULL,
  ),
  108 => 
  array (
    'id_presensi' => 3471,
    'nis' => 15386,
    'tanggal' => '2026-07-15',
    'jam' => '08:23:25',
    'status' => '1',
    'keterangan' => NULL,
    'file' => NULL,
  ),
  109 => 
  array (
    'id_presensi' => 3472,
    'nis' => 15387,
    'tanggal' => '2026-07-15',
    'jam' => '08:23:25',
    'status' => '1',
    'keterangan' => NULL,
    'file' => NULL,
  ),
  110 => 
  array (
    'id_presensi' => 3473,
    'nis' => 15388,
    'tanggal' => '2026-07-15',
    'jam' => '08:23:25',
    'status' => '1',
    'keterangan' => NULL,
    'file' => NULL,
  ),
  111 => 
  array (
    'id_presensi' => 3474,
    'nis' => 15389,
    'tanggal' => '2026-07-15',
    'jam' => '08:23:25',
    'status' => '1',
    'keterangan' => NULL,
    'file' => NULL,
  ),
  112 => 
  array (
    'id_presensi' => 3475,
    'nis' => 15390,
    'tanggal' => '2026-07-15',
    'jam' => '08:23:25',
    'status' => '2',
    'keterangan' => NULL,
    'file' => 'siswa/presensi/hY2TgFDDYM1hClB7v5OZlUlUsrsV8yrNtfYxhTPY.jpg',
  ),
  113 => 
  array (
    'id_presensi' => 3476,
    'nis' => 15391,
    'tanggal' => '2026-07-15',
    'jam' => '08:23:25',
    'status' => '2',
    'keterangan' => NULL,
    'file' => NULL,
  ),
  114 => 
  array (
    'id_presensi' => 3477,
    'nis' => 15392,
    'tanggal' => '2026-07-15',
    'jam' => '08:23:25',
    'status' => '1',
    'keterangan' => NULL,
    'file' => NULL,
  ),
  115 => 
  array (
    'id_presensi' => 3478,
    'nis' => 15393,
    'tanggal' => '2026-07-15',
    'jam' => '08:23:25',
    'status' => '1',
    'keterangan' => NULL,
    'file' => NULL,
  ),
  116 => 
  array (
    'id_presensi' => 3479,
    'nis' => 15394,
    'tanggal' => '2026-07-15',
    'jam' => '08:23:25',
    'status' => '1',
    'keterangan' => NULL,
    'file' => NULL,
  ),
  117 => 
  array (
    'id_presensi' => 3480,
    'nis' => 15395,
    'tanggal' => '2026-07-15',
    'jam' => '08:23:25',
    'status' => '1',
    'keterangan' => NULL,
    'file' => NULL,
  ),
  118 => 
  array (
    'id_presensi' => 3481,
    'nis' => 15396,
    'tanggal' => '2026-07-15',
    'jam' => '08:23:25',
    'status' => '1',
    'keterangan' => NULL,
    'file' => NULL,
  ),
  119 => 
  array (
    'id_presensi' => 3482,
    'nis' => 15397,
    'tanggal' => '2026-07-15',
    'jam' => '08:23:25',
    'status' => '1',
    'keterangan' => NULL,
    'file' => NULL,
  ),
  120 => 
  array (
    'id_presensi' => 3483,
    'nis' => 15398,
    'tanggal' => '2026-07-15',
    'jam' => '08:23:25',
    'status' => '1',
    'keterangan' => NULL,
    'file' => NULL,
  ),
  121 => 
  array (
    'id_presensi' => 3484,
    'nis' => 15399,
    'tanggal' => '2026-07-15',
    'jam' => '08:23:25',
    'status' => '1',
    'keterangan' => NULL,
    'file' => NULL,
  ),
  122 => 
  array (
    'id_presensi' => 3485,
    'nis' => 15400,
    'tanggal' => '2026-07-15',
    'jam' => '08:23:25',
    'status' => '1',
    'keterangan' => NULL,
    'file' => NULL,
  ),
  123 => 
  array (
    'id_presensi' => 3486,
    'nis' => 15401,
    'tanggal' => '2026-07-15',
    'jam' => '08:23:25',
    'status' => '1',
    'keterangan' => NULL,
    'file' => NULL,
  ),
  124 => 
  array (
    'id_presensi' => 3487,
    'nis' => 15402,
    'tanggal' => '2026-07-15',
    'jam' => '08:23:25',
    'status' => '1',
    'keterangan' => NULL,
    'file' => NULL,
  ),
  125 => 
  array (
    'id_presensi' => 3488,
    'nis' => 15403,
    'tanggal' => '2026-07-15',
    'jam' => '08:23:25',
    'status' => '1',
    'keterangan' => NULL,
    'file' => NULL,
  ),
  126 => 
  array (
    'id_presensi' => 3489,
    'nis' => 15404,
    'tanggal' => '2026-07-15',
    'jam' => '08:23:25',
    'status' => '1',
    'keterangan' => NULL,
    'file' => NULL,
  ),
  127 => 
  array (
    'id_presensi' => 3490,
    'nis' => 15405,
    'tanggal' => '2026-07-15',
    'jam' => '08:23:25',
    'status' => '1',
    'keterangan' => NULL,
    'file' => NULL,
  ),
  128 => 
  array (
    'id_presensi' => 3491,
    'nis' => 15406,
    'tanggal' => '2026-07-15',
    'jam' => '08:23:25',
    'status' => '1',
    'keterangan' => NULL,
    'file' => NULL,
  ),
  129 => 
  array (
    'id_presensi' => 3492,
    'nis' => 15407,
    'tanggal' => '2026-07-15',
    'jam' => '08:23:25',
    'status' => '1',
    'keterangan' => NULL,
    'file' => NULL,
  ),
  130 => 
  array (
    'id_presensi' => 3493,
    'nis' => 15408,
    'tanggal' => '2026-07-15',
    'jam' => '08:23:25',
    'status' => '1',
    'keterangan' => NULL,
    'file' => NULL,
  ),
  131 => 
  array (
    'id_presensi' => 3494,
    'nis' => 15409,
    'tanggal' => '2026-07-15',
    'jam' => '08:23:25',
    'status' => '1',
    'keterangan' => NULL,
    'file' => NULL,
  ),
  132 => 
  array (
    'id_presensi' => 3495,
    'nis' => 15410,
    'tanggal' => '2026-07-15',
    'jam' => '08:23:25',
    'status' => '1',
    'keterangan' => NULL,
    'file' => NULL,
  ),
  133 => 
  array (
    'id_presensi' => 3496,
    'nis' => 15411,
    'tanggal' => '2026-07-15',
    'jam' => '08:23:25',
    'status' => '1',
    'keterangan' => NULL,
    'file' => NULL,
  ),
  134 => 
  array (
    'id_presensi' => 3497,
    'nis' => 15412,
    'tanggal' => '2026-07-15',
    'jam' => '08:23:25',
    'status' => '1',
    'keterangan' => NULL,
    'file' => NULL,
  ),
  135 => 
  array (
    'id_presensi' => 3498,
    'nis' => 15413,
    'tanggal' => '2026-07-15',
    'jam' => '08:23:25',
    'status' => '1',
    'keterangan' => NULL,
    'file' => NULL,
  ),
  136 => 
  array (
    'id_presensi' => 3499,
    'nis' => 15414,
    'tanggal' => '2026-07-15',
    'jam' => '08:23:25',
    'status' => '1',
    'keterangan' => NULL,
    'file' => NULL,
  ),
  137 => 
  array (
    'id_presensi' => 3500,
    'nis' => 15415,
    'tanggal' => '2026-07-15',
    'jam' => '08:23:25',
    'status' => '1',
    'keterangan' => NULL,
    'file' => NULL,
  ),
  138 => 
  array (
    'id_presensi' => 3501,
    'nis' => 15416,
    'tanggal' => '2026-07-15',
    'jam' => '08:23:25',
    'status' => '1',
    'keterangan' => NULL,
    'file' => NULL,
  ),
  139 => 
  array (
    'id_presensi' => 3502,
    'nis' => 15417,
    'tanggal' => '2026-07-15',
    'jam' => '08:23:25',
    'status' => '1',
    'keterangan' => NULL,
    'file' => NULL,
  ),
  140 => 
  array (
    'id_presensi' => 3503,
    'nis' => 15418,
    'tanggal' => '2026-07-15',
    'jam' => '08:23:25',
    'status' => '1',
    'keterangan' => NULL,
    'file' => NULL,
  ),
  141 => 
  array (
    'id_presensi' => 3504,
    'nis' => 15419,
    'tanggal' => '2026-07-15',
    'jam' => '08:23:25',
    'status' => '1',
    'keterangan' => NULL,
    'file' => NULL,
  ),
  142 => 
  array (
    'id_presensi' => 3505,
    'nis' => 15420,
    'tanggal' => '2026-07-15',
    'jam' => '08:23:25',
    'status' => '1',
    'keterangan' => NULL,
    'file' => NULL,
  ),
  143 => 
  array (
    'id_presensi' => 3506,
    'nis' => 13893,
    'tanggal' => '2026-07-15',
    'jam' => '11:14:45',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  144 => 
  array (
    'id_presensi' => 3507,
    'nis' => 13921,
    'tanggal' => '2026-07-15',
    'jam' => '11:14:47',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  145 => 
  array (
    'id_presensi' => 3508,
    'nis' => 14379,
    'tanggal' => '2026-07-16',
    'jam' => '06:17:35',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  146 => 
  array (
    'id_presensi' => 3509,
    'nis' => 14774,
    'tanggal' => '2026-07-16',
    'jam' => '06:17:41',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  147 => 
  array (
    'id_presensi' => 3510,
    'nis' => 14773,
    'tanggal' => '2026-07-16',
    'jam' => '06:23:49',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  148 => 
  array (
    'id_presensi' => 3511,
    'nis' => 14630,
    'tanggal' => '2026-07-16',
    'jam' => '06:30:55',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  149 => 
  array (
    'id_presensi' => 3512,
    'nis' => 14321,
    'tanggal' => '2026-07-16',
    'jam' => '06:35:46',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  150 => 
  array (
    'id_presensi' => 3513,
    'nis' => 14317,
    'tanggal' => '2026-07-16',
    'jam' => '06:36:00',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  151 => 
  array (
    'id_presensi' => 3514,
    'nis' => 13891,
    'tanggal' => '2026-07-16',
    'jam' => '06:36:25',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  152 => 
  array (
    'id_presensi' => 3515,
    'nis' => 14633,
    'tanggal' => '2026-07-16',
    'jam' => '06:37:31',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  153 => 
  array (
    'id_presensi' => 3516,
    'nis' => 14381,
    'tanggal' => '2026-07-16',
    'jam' => '06:38:06',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  154 => 
  array (
    'id_presensi' => 3517,
    'nis' => 14573,
    'tanggal' => '2026-07-16',
    'jam' => '06:38:13',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  155 => 
  array (
    'id_presensi' => 3518,
    'nis' => 14518,
    'tanggal' => '2026-07-16',
    'jam' => '06:38:14',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  156 => 
  array (
    'id_presensi' => 3519,
    'nis' => 14567,
    'tanggal' => '2026-07-16',
    'jam' => '06:38:15',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  157 => 
  array (
    'id_presensi' => 3520,
    'nis' => 14759,
    'tanggal' => '2026-07-16',
    'jam' => '06:38:16',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  158 => 
  array (
    'id_presensi' => 3521,
    'nis' => 14741,
    'tanggal' => '2026-07-16',
    'jam' => '06:38:40',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  159 => 
  array (
    'id_presensi' => 3522,
    'nis' => 13868,
    'tanggal' => '2026-07-16',
    'jam' => '06:39:31',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  160 => 
  array (
    'id_presensi' => 3523,
    'nis' => 14339,
    'tanggal' => '2026-07-16',
    'jam' => '06:40:23',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  161 => 
  array (
    'id_presensi' => 3524,
    'nis' => 14290,
    'tanggal' => '2026-07-16',
    'jam' => '06:40:51',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  162 => 
  array (
    'id_presensi' => 3525,
    'nis' => 14378,
    'tanggal' => '2026-07-16',
    'jam' => '06:41:24',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  163 => 
  array (
    'id_presensi' => 3526,
    'nis' => 14382,
    'tanggal' => '2026-07-16',
    'jam' => '06:41:27',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  164 => 
  array (
    'id_presensi' => 3527,
    'nis' => 13887,
    'tanggal' => '2026-07-16',
    'jam' => '06:41:34',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  165 => 
  array (
    'id_presensi' => 3528,
    'nis' => 14744,
    'tanggal' => '2026-07-16',
    'jam' => '06:41:45',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  166 => 
  array (
    'id_presensi' => 3529,
    'nis' => 14575,
    'tanggal' => '2026-07-16',
    'jam' => '06:42:26',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  167 => 
  array (
    'id_presensi' => 3530,
    'nis' => 13908,
    'tanggal' => '2026-07-16',
    'jam' => '06:42:31',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  168 => 
  array (
    'id_presensi' => 3531,
    'nis' => 14621,
    'tanggal' => '2026-07-16',
    'jam' => '06:42:43',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  169 => 
  array (
    'id_presensi' => 3532,
    'nis' => 14547,
    'tanggal' => '2026-07-16',
    'jam' => '06:42:43',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  170 => 
  array (
    'id_presensi' => 3533,
    'nis' => 14625,
    'tanggal' => '2026-07-16',
    'jam' => '06:42:46',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  171 => 
  array (
    'id_presensi' => 3534,
    'nis' => 14608,
    'tanggal' => '2026-07-16',
    'jam' => '06:42:48',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  172 => 
  array (
    'id_presensi' => 3535,
    'nis' => 14555,
    'tanggal' => '2026-07-16',
    'jam' => '06:44:07',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  173 => 
  array (
    'id_presensi' => 3536,
    'nis' => 14770,
    'tanggal' => '2026-07-16',
    'jam' => '06:46:16',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  174 => 
  array (
    'id_presensi' => 3537,
    'nis' => 14763,
    'tanggal' => '2026-07-16',
    'jam' => '06:46:19',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  175 => 
  array (
    'id_presensi' => 3538,
    'nis' => 14631,
    'tanggal' => '2026-07-16',
    'jam' => '06:46:24',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  176 => 
  array (
    'id_presensi' => 3539,
    'nis' => 14302,
    'tanggal' => '2026-07-16',
    'jam' => '06:46:27',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  177 => 
  array (
    'id_presensi' => 3540,
    'nis' => 14375,
    'tanggal' => '2026-07-16',
    'jam' => '06:46:38',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  178 => 
  array (
    'id_presensi' => 3541,
    'nis' => 14380,
    'tanggal' => '2026-07-16',
    'jam' => '06:46:41',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  179 => 
  array (
    'id_presensi' => 3542,
    'nis' => 14386,
    'tanggal' => '2026-07-16',
    'jam' => '06:46:47',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  180 => 
  array (
    'id_presensi' => 3543,
    'nis' => 14800,
    'tanggal' => '2026-07-16',
    'jam' => '06:47:03',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  181 => 
  array (
    'id_presensi' => 3544,
    'nis' => 14785,
    'tanggal' => '2026-07-16',
    'jam' => '06:47:09',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  182 => 
  array (
    'id_presensi' => 3545,
    'nis' => 14668,
    'tanggal' => '2026-07-16',
    'jam' => '06:47:43',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  183 => 
  array (
    'id_presensi' => 3546,
    'nis' => 14618,
    'tanggal' => '2026-07-16',
    'jam' => '06:47:49',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  184 => 
  array (
    'id_presensi' => 3547,
    'nis' => 14634,
    'tanggal' => '2026-07-16',
    'jam' => '06:47:49',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  185 => 
  array (
    'id_presensi' => 3548,
    'nis' => 14641,
    'tanggal' => '2026-07-16',
    'jam' => '06:47:54',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  186 => 
  array (
    'id_presensi' => 3549,
    'nis' => 13877,
    'tanggal' => '2026-07-16',
    'jam' => '06:48:16',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  187 => 
  array (
    'id_presensi' => 3550,
    'nis' => 13874,
    'tanggal' => '2026-07-16',
    'jam' => '06:48:18',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  188 => 
  array (
    'id_presensi' => 3551,
    'nis' => 14153,
    'tanggal' => '2026-07-16',
    'jam' => '06:48:29',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  189 => 
  array (
    'id_presensi' => 3552,
    'nis' => 14571,
    'tanggal' => '2026-07-16',
    'jam' => '06:48:32',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  190 => 
  array (
    'id_presensi' => 3553,
    'nis' => 14385,
    'tanggal' => '2026-07-16',
    'jam' => '06:49:23',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  191 => 
  array (
    'id_presensi' => 3554,
    'nis' => 14144,
    'tanggal' => '2026-07-16',
    'jam' => '06:49:37',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  192 => 
  array (
    'id_presensi' => 3555,
    'nis' => 13927,
    'tanggal' => '2026-07-16',
    'jam' => '06:49:44',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  193 => 
  array (
    'id_presensi' => 3556,
    'nis' => 14786,
    'tanggal' => '2026-07-16',
    'jam' => '06:49:47',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  194 => 
  array (
    'id_presensi' => 3557,
    'nis' => 14295,
    'tanggal' => '2026-07-16',
    'jam' => '06:49:50',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  195 => 
  array (
    'id_presensi' => 3558,
    'nis' => 13915,
    'tanggal' => '2026-07-16',
    'jam' => '06:49:55',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  196 => 
  array (
    'id_presensi' => 3559,
    'nis' => 13902,
    'tanggal' => '2026-07-16',
    'jam' => '06:50:02',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  197 => 
  array (
    'id_presensi' => 3560,
    'nis' => 14308,
    'tanggal' => '2026-07-16',
    'jam' => '06:50:11',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  198 => 
  array (
    'id_presensi' => 3561,
    'nis' => 14574,
    'tanggal' => '2026-07-16',
    'jam' => '06:50:11',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  199 => 
  array (
    'id_presensi' => 3562,
    'nis' => 14549,
    'tanggal' => '2026-07-16',
    'jam' => '06:50:13',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
));

        DB::table('presensi')->insert(array (
  0 => 
  array (
    'id_presensi' => 3563,
    'nis' => 14301,
    'tanggal' => '2026-07-16',
    'jam' => '06:50:18',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  1 => 
  array (
    'id_presensi' => 3564,
    'nis' => 14612,
    'tanggal' => '2026-07-16',
    'jam' => '06:50:25',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  2 => 
  array (
    'id_presensi' => 3565,
    'nis' => 14331,
    'tanggal' => '2026-07-16',
    'jam' => '06:50:25',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  3 => 
  array (
    'id_presensi' => 3566,
    'nis' => 14559,
    'tanggal' => '2026-07-16',
    'jam' => '06:50:36',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  4 => 
  array (
    'id_presensi' => 3567,
    'nis' => 13872,
    'tanggal' => '2026-07-16',
    'jam' => '06:50:42',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  5 => 
  array (
    'id_presensi' => 3568,
    'nis' => 14561,
    'tanggal' => '2026-07-16',
    'jam' => '06:50:43',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  6 => 
  array (
    'id_presensi' => 3569,
    'nis' => 14756,
    'tanggal' => '2026-07-16',
    'jam' => '06:50:49',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  7 => 
  array (
    'id_presensi' => 3570,
    'nis' => 14765,
    'tanggal' => '2026-07-16',
    'jam' => '06:50:55',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  8 => 
  array (
    'id_presensi' => 3571,
    'nis' => 14164,
    'tanggal' => '2026-07-16',
    'jam' => '06:51:12',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  9 => 
  array (
    'id_presensi' => 3572,
    'nis' => 14169,
    'tanggal' => '2026-07-16',
    'jam' => '06:51:27',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  10 => 
  array (
    'id_presensi' => 3573,
    'nis' => 13876,
    'tanggal' => '2026-07-16',
    'jam' => '06:51:29',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  11 => 
  array (
    'id_presensi' => 3574,
    'nis' => 14777,
    'tanggal' => '2026-07-16',
    'jam' => '06:51:32',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  12 => 
  array (
    'id_presensi' => 3575,
    'nis' => 13880,
    'tanggal' => '2026-07-16',
    'jam' => '06:51:33',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  13 => 
  array (
    'id_presensi' => 3576,
    'nis' => 14803,
    'tanggal' => '2026-07-16',
    'jam' => '06:51:34',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  14 => 
  array (
    'id_presensi' => 3577,
    'nis' => 13866,
    'tanggal' => '2026-07-16',
    'jam' => '06:51:42',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  15 => 
  array (
    'id_presensi' => 3578,
    'nis' => 14757,
    'tanggal' => '2026-07-16',
    'jam' => '06:52:10',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  16 => 
  array (
    'id_presensi' => 3579,
    'nis' => 14333,
    'tanggal' => '2026-07-16',
    'jam' => '06:52:13',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  17 => 
  array (
    'id_presensi' => 3580,
    'nis' => 14755,
    'tanggal' => '2026-07-16',
    'jam' => '06:52:17',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  18 => 
  array (
    'id_presensi' => 3581,
    'nis' => 14673,
    'tanggal' => '2026-07-16',
    'jam' => '06:52:29',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  19 => 
  array (
    'id_presensi' => 3582,
    'nis' => 14667,
    'tanggal' => '2026-07-16',
    'jam' => '06:52:34',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  20 => 
  array (
    'id_presensi' => 3583,
    'nis' => 14653,
    'tanggal' => '2026-07-16',
    'jam' => '06:52:39',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  21 => 
  array (
    'id_presensi' => 3584,
    'nis' => 14548,
    'tanggal' => '2026-07-16',
    'jam' => '06:53:12',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  22 => 
  array (
    'id_presensi' => 3585,
    'nis' => 14109,
    'tanggal' => '2026-07-16',
    'jam' => '06:53:26',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  23 => 
  array (
    'id_presensi' => 3586,
    'nis' => 14116,
    'tanggal' => '2026-07-16',
    'jam' => '06:53:28',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  24 => 
  array (
    'id_presensi' => 3587,
    'nis' => 14772,
    'tanggal' => '2026-07-16',
    'jam' => '06:53:28',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  25 => 
  array (
    'id_presensi' => 3588,
    'nis' => 14749,
    'tanggal' => '2026-07-16',
    'jam' => '06:53:30',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  26 => 
  array (
    'id_presensi' => 3589,
    'nis' => 14628,
    'tanggal' => '2026-07-16',
    'jam' => '06:53:32',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  27 => 
  array (
    'id_presensi' => 3590,
    'nis' => 14767,
    'tanggal' => '2026-07-16',
    'jam' => '06:53:33',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  28 => 
  array (
    'id_presensi' => 3591,
    'nis' => 14629,
    'tanggal' => '2026-07-16',
    'jam' => '06:53:35',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  29 => 
  array (
    'id_presensi' => 3592,
    'nis' => 14389,
    'tanggal' => '2026-07-16',
    'jam' => '06:53:39',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  30 => 
  array (
    'id_presensi' => 3593,
    'nis' => 14399,
    'tanggal' => '2026-07-16',
    'jam' => '06:53:44',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  31 => 
  array (
    'id_presensi' => 3594,
    'nis' => 14397,
    'tanggal' => '2026-07-16',
    'jam' => '06:53:46',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  32 => 
  array (
    'id_presensi' => 3595,
    'nis' => 14412,
    'tanggal' => '2026-07-16',
    'jam' => '06:53:48',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  33 => 
  array (
    'id_presensi' => 3596,
    'nis' => 14563,
    'tanggal' => '2026-07-16',
    'jam' => '06:53:49',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  34 => 
  array (
    'id_presensi' => 3597,
    'nis' => 14403,
    'tanggal' => '2026-07-16',
    'jam' => '06:53:51',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  35 => 
  array (
    'id_presensi' => 3598,
    'nis' => 14406,
    'tanggal' => '2026-07-16',
    'jam' => '06:53:53',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  36 => 
  array (
    'id_presensi' => 3599,
    'nis' => 14413,
    'tanggal' => '2026-07-16',
    'jam' => '06:53:55',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  37 => 
  array (
    'id_presensi' => 3600,
    'nis' => 14752,
    'tanggal' => '2026-07-16',
    'jam' => '06:53:55',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  38 => 
  array (
    'id_presensi' => 3601,
    'nis' => 13898,
    'tanggal' => '2026-07-16',
    'jam' => '06:53:58',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  39 => 
  array (
    'id_presensi' => 3602,
    'nis' => 14762,
    'tanggal' => '2026-07-16',
    'jam' => '06:53:58',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  40 => 
  array (
    'id_presensi' => 3603,
    'nis' => 13911,
    'tanggal' => '2026-07-16',
    'jam' => '06:54:00',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  41 => 
  array (
    'id_presensi' => 3604,
    'nis' => 14330,
    'tanggal' => '2026-07-16',
    'jam' => '06:54:00',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  42 => 
  array (
    'id_presensi' => 3605,
    'nis' => 14620,
    'tanggal' => '2026-07-16',
    'jam' => '06:54:06',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  43 => 
  array (
    'id_presensi' => 3606,
    'nis' => 14659,
    'tanggal' => '2026-07-16',
    'jam' => '06:54:11',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  44 => 
  array (
    'id_presensi' => 3607,
    'nis' => 14614,
    'tanggal' => '2026-07-16',
    'jam' => '06:54:11',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  45 => 
  array (
    'id_presensi' => 3608,
    'nis' => 14646,
    'tanggal' => '2026-07-16',
    'jam' => '06:54:14',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  46 => 
  array (
    'id_presensi' => 3609,
    'nis' => 14657,
    'tanggal' => '2026-07-16',
    'jam' => '06:54:16',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  47 => 
  array (
    'id_presensi' => 3610,
    'nis' => 14550,
    'tanggal' => '2026-07-16',
    'jam' => '06:54:17',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  48 => 
  array (
    'id_presensi' => 3611,
    'nis' => 14644,
    'tanggal' => '2026-07-16',
    'jam' => '06:54:19',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  49 => 
  array (
    'id_presensi' => 3612,
    'nis' => 14576,
    'tanggal' => '2026-07-16',
    'jam' => '06:54:19',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  50 => 
  array (
    'id_presensi' => 3613,
    'nis' => 14286,
    'tanggal' => '2026-07-16',
    'jam' => '06:54:22',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  51 => 
  array (
    'id_presensi' => 3614,
    'nis' => 14544,
    'tanggal' => '2026-07-16',
    'jam' => '06:54:22',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  52 => 
  array (
    'id_presensi' => 3615,
    'nis' => 14554,
    'tanggal' => '2026-07-16',
    'jam' => '06:54:24',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  53 => 
  array (
    'id_presensi' => 3616,
    'nis' => 14546,
    'tanggal' => '2026-07-16',
    'jam' => '06:54:26',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  54 => 
  array (
    'id_presensi' => 3617,
    'nis' => 14414,
    'tanggal' => '2026-07-16',
    'jam' => '06:54:30',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  55 => 
  array (
    'id_presensi' => 3618,
    'nis' => 14318,
    'tanggal' => '2026-07-16',
    'jam' => '06:54:31',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  56 => 
  array (
    'id_presensi' => 3619,
    'nis' => 14651,
    'tanggal' => '2026-07-16',
    'jam' => '06:54:35',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  57 => 
  array (
    'id_presensi' => 3620,
    'nis' => 14519,
    'tanggal' => '2026-07-16',
    'jam' => '06:54:37',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  58 => 
  array (
    'id_presensi' => 3621,
    'nis' => 14545,
    'tanggal' => '2026-07-16',
    'jam' => '06:54:38',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  59 => 
  array (
    'id_presensi' => 3622,
    'nis' => 14539,
    'tanggal' => '2026-07-16',
    'jam' => '06:54:39',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  60 => 
  array (
    'id_presensi' => 3623,
    'nis' => 14782,
    'tanggal' => '2026-07-16',
    'jam' => '06:54:41',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  61 => 
  array (
    'id_presensi' => 3624,
    'nis' => 14294,
    'tanggal' => '2026-07-16',
    'jam' => '06:54:44',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  62 => 
  array (
    'id_presensi' => 3625,
    'nis' => 14313,
    'tanggal' => '2026-07-16',
    'jam' => '06:54:47',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  63 => 
  array (
    'id_presensi' => 3626,
    'nis' => 14288,
    'tanggal' => '2026-07-16',
    'jam' => '06:54:50',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  64 => 
  array (
    'id_presensi' => 3627,
    'nis' => 14298,
    'tanggal' => '2026-07-16',
    'jam' => '06:54:51',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  65 => 
  array (
    'id_presensi' => 3628,
    'nis' => 14293,
    'tanggal' => '2026-07-16',
    'jam' => '06:54:54',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  66 => 
  array (
    'id_presensi' => 3629,
    'nis' => 14395,
    'tanggal' => '2026-07-16',
    'jam' => '06:54:55',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  67 => 
  array (
    'id_presensi' => 3630,
    'nis' => 14671,
    'tanggal' => '2026-07-16',
    'jam' => '06:54:57',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  68 => 
  array (
    'id_presensi' => 3631,
    'nis' => 14658,
    'tanggal' => '2026-07-16',
    'jam' => '06:55:00',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  69 => 
  array (
    'id_presensi' => 3632,
    'nis' => 14297,
    'tanggal' => '2026-07-16',
    'jam' => '06:55:00',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  70 => 
  array (
    'id_presensi' => 3633,
    'nis' => 14662,
    'tanggal' => '2026-07-16',
    'jam' => '06:55:02',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  71 => 
  array (
    'id_presensi' => 3634,
    'nis' => 13899,
    'tanggal' => '2026-07-16',
    'jam' => '06:55:06',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  72 => 
  array (
    'id_presensi' => 3635,
    'nis' => 14300,
    'tanggal' => '2026-07-16',
    'jam' => '06:55:07',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  73 => 
  array (
    'id_presensi' => 3636,
    'nis' => 13916,
    'tanggal' => '2026-07-16',
    'jam' => '06:55:09',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  74 => 
  array (
    'id_presensi' => 3637,
    'nis' => 13909,
    'tanggal' => '2026-07-16',
    'jam' => '06:55:12',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  75 => 
  array (
    'id_presensi' => 3638,
    'nis' => 14128,
    'tanggal' => '2026-07-16',
    'jam' => '06:55:13',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  76 => 
  array (
    'id_presensi' => 3639,
    'nis' => 14119,
    'tanggal' => '2026-07-16',
    'jam' => '06:55:15',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  77 => 
  array (
    'id_presensi' => 3640,
    'nis' => 14303,
    'tanggal' => '2026-07-16',
    'jam' => '06:55:16',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  78 => 
  array (
    'id_presensi' => 3641,
    'nis' => 14531,
    'tanggal' => '2026-07-16',
    'jam' => '06:55:22',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  79 => 
  array (
    'id_presensi' => 3642,
    'nis' => 14291,
    'tanggal' => '2026-07-16',
    'jam' => '06:55:26',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  80 => 
  array (
    'id_presensi' => 3643,
    'nis' => 14332,
    'tanggal' => '2026-07-16',
    'jam' => '06:55:27',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  81 => 
  array (
    'id_presensi' => 3644,
    'nis' => 14329,
    'tanggal' => '2026-07-16',
    'jam' => '06:55:30',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  82 => 
  array (
    'id_presensi' => 3645,
    'nis' => 14792,
    'tanggal' => '2026-07-16',
    'jam' => '06:55:51',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  83 => 
  array (
    'id_presensi' => 3646,
    'nis' => 14776,
    'tanggal' => '2026-07-16',
    'jam' => '06:55:53',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  84 => 
  array (
    'id_presensi' => 3647,
    'nis' => 14118,
    'tanggal' => '2026-07-16',
    'jam' => '06:55:54',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  85 => 
  array (
    'id_presensi' => 3648,
    'nis' => 14648,
    'tanggal' => '2026-07-16',
    'jam' => '06:55:58',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  86 => 
  array (
    'id_presensi' => 3649,
    'nis' => 13929,
    'tanggal' => '2026-07-16',
    'jam' => '06:55:58',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  87 => 
  array (
    'id_presensi' => 3650,
    'nis' => 14642,
    'tanggal' => '2026-07-16',
    'jam' => '06:56:01',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  88 => 
  array (
    'id_presensi' => 3651,
    'nis' => 14123,
    'tanggal' => '2026-07-16',
    'jam' => '06:56:02',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  89 => 
  array (
    'id_presensi' => 3652,
    'nis' => 14624,
    'tanggal' => '2026-07-16',
    'jam' => '06:56:05',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  90 => 
  array (
    'id_presensi' => 3653,
    'nis' => 14775,
    'tanggal' => '2026-07-16',
    'jam' => '06:56:10',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  91 => 
  array (
    'id_presensi' => 3654,
    'nis' => 14745,
    'tanggal' => '2026-07-16',
    'jam' => '06:56:12',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  92 => 
  array (
    'id_presensi' => 3655,
    'nis' => 14771,
    'tanggal' => '2026-07-16',
    'jam' => '06:56:13',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  93 => 
  array (
    'id_presensi' => 3656,
    'nis' => 14753,
    'tanggal' => '2026-07-16',
    'jam' => '06:56:17',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  94 => 
  array (
    'id_presensi' => 3657,
    'nis' => 14784,
    'tanggal' => '2026-07-16',
    'jam' => '06:56:18',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  95 => 
  array (
    'id_presensi' => 3658,
    'nis' => 14372,
    'tanggal' => '2026-07-16',
    'jam' => '06:56:20',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  96 => 
  array (
    'id_presensi' => 3659,
    'nis' => 14335,
    'tanggal' => '2026-07-16',
    'jam' => '06:56:22',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  97 => 
  array (
    'id_presensi' => 3660,
    'nis' => 13900,
    'tanggal' => '2026-07-16',
    'jam' => '06:56:23',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  98 => 
  array (
    'id_presensi' => 3661,
    'nis' => 14747,
    'tanggal' => '2026-07-16',
    'jam' => '06:56:25',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  99 => 
  array (
    'id_presensi' => 3662,
    'nis' => 14152,
    'tanggal' => '2026-07-16',
    'jam' => '06:56:26',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  100 => 
  array (
    'id_presensi' => 3663,
    'nis' => 14805,
    'tanggal' => '2026-07-16',
    'jam' => '06:56:28',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  101 => 
  array (
    'id_presensi' => 3664,
    'nis' => 14416,
    'tanggal' => '2026-07-16',
    'jam' => '06:56:28',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  102 => 
  array (
    'id_presensi' => 3665,
    'nis' => 14394,
    'tanggal' => '2026-07-16',
    'jam' => '06:56:30',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  103 => 
  array (
    'id_presensi' => 3666,
    'nis' => 14143,
    'tanggal' => '2026-07-16',
    'jam' => '06:56:43',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  104 => 
  array (
    'id_presensi' => 3667,
    'nis' => 14168,
    'tanggal' => '2026-07-16',
    'jam' => '06:56:47',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  105 => 
  array (
    'id_presensi' => 3668,
    'nis' => 14316,
    'tanggal' => '2026-07-16',
    'jam' => '06:56:53',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  106 => 
  array (
    'id_presensi' => 3669,
    'nis' => 14617,
    'tanggal' => '2026-07-16',
    'jam' => '06:56:53',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  107 => 
  array (
    'id_presensi' => 3670,
    'nis' => 14154,
    'tanggal' => '2026-07-16',
    'jam' => '06:56:57',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  108 => 
  array (
    'id_presensi' => 3671,
    'nis' => 14652,
    'tanggal' => '2026-07-16',
    'jam' => '06:56:58',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  109 => 
  array (
    'id_presensi' => 3672,
    'nis' => 14768,
    'tanggal' => '2026-07-16',
    'jam' => '06:57:03',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  110 => 
  array (
    'id_presensi' => 3673,
    'nis' => 14626,
    'tanggal' => '2026-07-16',
    'jam' => '06:57:04',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  111 => 
  array (
    'id_presensi' => 3674,
    'nis' => 14761,
    'tanggal' => '2026-07-16',
    'jam' => '06:57:05',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  112 => 
  array (
    'id_presensi' => 3675,
    'nis' => 14663,
    'tanggal' => '2026-07-16',
    'jam' => '06:57:17',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  113 => 
  array (
    'id_presensi' => 3676,
    'nis' => 13895,
    'tanggal' => '2026-07-16',
    'jam' => '06:57:18',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  114 => 
  array (
    'id_presensi' => 3677,
    'nis' => 14323,
    'tanggal' => '2026-07-16',
    'jam' => '06:57:22',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  115 => 
  array (
    'id_presensi' => 3678,
    'nis' => 13888,
    'tanggal' => '2026-07-16',
    'jam' => '06:57:29',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  116 => 
  array (
    'id_presensi' => 3679,
    'nis' => 13864,
    'tanggal' => '2026-07-16',
    'jam' => '06:57:32',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  117 => 
  array (
    'id_presensi' => 3680,
    'nis' => 13875,
    'tanggal' => '2026-07-16',
    'jam' => '06:57:35',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  118 => 
  array (
    'id_presensi' => 3681,
    'nis' => 14615,
    'tanggal' => '2026-07-16',
    'jam' => '06:57:36',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  119 => 
  array (
    'id_presensi' => 3682,
    'nis' => 14758,
    'tanggal' => '2026-07-16',
    'jam' => '06:57:39',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  120 => 
  array (
    'id_presensi' => 3683,
    'nis' => 13922,
    'tanggal' => '2026-07-16',
    'jam' => '06:57:43',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  121 => 
  array (
    'id_presensi' => 3684,
    'nis' => 13869,
    'tanggal' => '2026-07-16',
    'jam' => '06:57:48',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  122 => 
  array (
    'id_presensi' => 3685,
    'nis' => 13901,
    'tanggal' => '2026-07-16',
    'jam' => '06:57:48',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  123 => 
  array (
    'id_presensi' => 3686,
    'nis' => 14558,
    'tanggal' => '2026-07-16',
    'jam' => '06:57:57',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  124 => 
  array (
    'id_presensi' => 3687,
    'nis' => 14160,
    'tanggal' => '2026-07-16',
    'jam' => '06:58:04',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  125 => 
  array (
    'id_presensi' => 3688,
    'nis' => 14540,
    'tanggal' => '2026-07-16',
    'jam' => '06:58:09',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  126 => 
  array (
    'id_presensi' => 3689,
    'nis' => 13907,
    'tanggal' => '2026-07-16',
    'jam' => '06:58:09',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  127 => 
  array (
    'id_presensi' => 3690,
    'nis' => 14415,
    'tanggal' => '2026-07-16',
    'jam' => '06:58:13',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  128 => 
  array (
    'id_presensi' => 3691,
    'nis' => 14577,
    'tanggal' => '2026-07-16',
    'jam' => '06:58:17',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  129 => 
  array (
    'id_presensi' => 3692,
    'nis' => 14566,
    'tanggal' => '2026-07-16',
    'jam' => '06:58:18',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  130 => 
  array (
    'id_presensi' => 3693,
    'nis' => 14552,
    'tanggal' => '2026-07-16',
    'jam' => '06:58:20',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  131 => 
  array (
    'id_presensi' => 3694,
    'nis' => 14799,
    'tanggal' => '2026-07-16',
    'jam' => '06:58:25',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  132 => 
  array (
    'id_presensi' => 3695,
    'nis' => 14804,
    'tanggal' => '2026-07-16',
    'jam' => '06:58:28',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  133 => 
  array (
    'id_presensi' => 3696,
    'nis' => 14798,
    'tanggal' => '2026-07-16',
    'jam' => '06:58:29',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  134 => 
  array (
    'id_presensi' => 3697,
    'nis' => 14794,
    'tanggal' => '2026-07-16',
    'jam' => '06:58:32',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  135 => 
  array (
    'id_presensi' => 3698,
    'nis' => 14791,
    'tanggal' => '2026-07-16',
    'jam' => '06:58:32',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  136 => 
  array (
    'id_presensi' => 3699,
    'nis' => 14748,
    'tanggal' => '2026-07-16',
    'jam' => '06:58:34',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  137 => 
  array (
    'id_presensi' => 3700,
    'nis' => 14669,
    'tanggal' => '2026-07-16',
    'jam' => '06:58:38',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  138 => 
  array (
    'id_presensi' => 3701,
    'nis' => 14409,
    'tanggal' => '2026-07-16',
    'jam' => '06:58:41',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  139 => 
  array (
    'id_presensi' => 3702,
    'nis' => 14338,
    'tanggal' => '2026-07-16',
    'jam' => '06:58:43',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  140 => 
  array (
    'id_presensi' => 3703,
    'nis' => 14797,
    'tanggal' => '2026-07-16',
    'jam' => '06:58:52',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  141 => 
  array (
    'id_presensi' => 3704,
    'nis' => 14541,
    'tanggal' => '2026-07-16',
    'jam' => '06:58:52',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  142 => 
  array (
    'id_presensi' => 3705,
    'nis' => 14523,
    'tanggal' => '2026-07-16',
    'jam' => '06:58:54',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  143 => 
  array (
    'id_presensi' => 3706,
    'nis' => 14537,
    'tanggal' => '2026-07-16',
    'jam' => '06:58:57',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  144 => 
  array (
    'id_presensi' => 3707,
    'nis' => 14417,
    'tanggal' => '2026-07-16',
    'jam' => '06:59:01',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  145 => 
  array (
    'id_presensi' => 3708,
    'nis' => 14174,
    'tanggal' => '2026-07-16',
    'jam' => '06:59:02',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  146 => 
  array (
    'id_presensi' => 3709,
    'nis' => 14392,
    'tanggal' => '2026-07-16',
    'jam' => '06:59:03',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  147 => 
  array (
    'id_presensi' => 3710,
    'nis' => 14142,
    'tanggal' => '2026-07-16',
    'jam' => '06:59:04',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  148 => 
  array (
    'id_presensi' => 3711,
    'nis' => 13883,
    'tanggal' => '2026-07-16',
    'jam' => '06:59:09',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  149 => 
  array (
    'id_presensi' => 3712,
    'nis' => 14314,
    'tanggal' => '2026-07-16',
    'jam' => '06:59:15',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  150 => 
  array (
    'id_presensi' => 3713,
    'nis' => 14108,
    'tanggal' => '2026-07-16',
    'jam' => '06:59:15',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  151 => 
  array (
    'id_presensi' => 3714,
    'nis' => 14790,
    'tanggal' => '2026-07-16',
    'jam' => '06:59:22',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  152 => 
  array (
    'id_presensi' => 3715,
    'nis' => 14789,
    'tanggal' => '2026-07-16',
    'jam' => '06:59:24',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  153 => 
  array (
    'id_presensi' => 3716,
    'nis' => 14124,
    'tanggal' => '2026-07-16',
    'jam' => '06:59:31',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  154 => 
  array (
    'id_presensi' => 3717,
    'nis' => 14622,
    'tanggal' => '2026-07-16',
    'jam' => '06:59:35',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  155 => 
  array (
    'id_presensi' => 3718,
    'nis' => 14635,
    'tanggal' => '2026-07-16',
    'jam' => '06:59:36',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  156 => 
  array (
    'id_presensi' => 3719,
    'nis' => 14146,
    'tanggal' => '2026-07-16',
    'jam' => '06:59:38',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  157 => 
  array (
    'id_presensi' => 3720,
    'nis' => 14664,
    'tanggal' => '2026-07-16',
    'jam' => '06:59:42',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  158 => 
  array (
    'id_presensi' => 3721,
    'nis' => 14327,
    'tanggal' => '2026-07-16',
    'jam' => '06:59:46',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  159 => 
  array (
    'id_presensi' => 3722,
    'nis' => 14158,
    'tanggal' => '2026-07-16',
    'jam' => '06:59:49',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  160 => 
  array (
    'id_presensi' => 3723,
    'nis' => 14643,
    'tanggal' => '2026-07-16',
    'jam' => '06:59:51',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  161 => 
  array (
    'id_presensi' => 3724,
    'nis' => 14764,
    'tanggal' => '2026-07-16',
    'jam' => '06:59:51',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  162 => 
  array (
    'id_presensi' => 3725,
    'nis' => 14309,
    'tanggal' => '2026-07-16',
    'jam' => '06:59:54',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  163 => 
  array (
    'id_presensi' => 3726,
    'nis' => 13925,
    'tanggal' => '2026-07-16',
    'jam' => '06:59:57',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  164 => 
  array (
    'id_presensi' => 3727,
    'nis' => 14172,
    'tanggal' => '2026-07-16',
    'jam' => '06:59:58',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  165 => 
  array (
    'id_presensi' => 3728,
    'nis' => 13870,
    'tanggal' => '2026-07-16',
    'jam' => '06:59:59',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  166 => 
  array (
    'id_presensi' => 3729,
    'nis' => 13904,
    'tanggal' => '2026-07-16',
    'jam' => '07:00:02',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  167 => 
  array (
    'id_presensi' => 3730,
    'nis' => 14173,
    'tanggal' => '2026-07-16',
    'jam' => '07:00:03',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  168 => 
  array (
    'id_presensi' => 3731,
    'nis' => 14520,
    'tanggal' => '2026-07-16',
    'jam' => '07:00:06',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  169 => 
  array (
    'id_presensi' => 3732,
    'nis' => 14795,
    'tanggal' => '2026-07-16',
    'jam' => '07:00:06',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  170 => 
  array (
    'id_presensi' => 3733,
    'nis' => 14535,
    'tanggal' => '2026-07-16',
    'jam' => '07:00:10',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  171 => 
  array (
    'id_presensi' => 3734,
    'nis' => 13881,
    'tanggal' => '2026-07-16',
    'jam' => '07:00:17',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  172 => 
  array (
    'id_presensi' => 3735,
    'nis' => 13862,
    'tanggal' => '2026-07-16',
    'jam' => '07:00:20',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  173 => 
  array (
    'id_presensi' => 3736,
    'nis' => 14299,
    'tanggal' => '2026-07-16',
    'jam' => '07:00:44',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  174 => 
  array (
    'id_presensi' => 3737,
    'nis' => 13871,
    'tanggal' => '2026-07-16',
    'jam' => '07:00:47',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  175 => 
  array (
    'id_presensi' => 3738,
    'nis' => 14802,
    'tanggal' => '2026-07-16',
    'jam' => '07:00:47',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  176 => 
  array (
    'id_presensi' => 3739,
    'nis' => 14636,
    'tanggal' => '2026-07-16',
    'jam' => '07:01:02',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  177 => 
  array (
    'id_presensi' => 3740,
    'nis' => 14337,
    'tanggal' => '2026-07-16',
    'jam' => '07:01:12',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  178 => 
  array (
    'id_presensi' => 3741,
    'nis' => 14336,
    'tanggal' => '2026-07-16',
    'jam' => '07:01:15',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  179 => 
  array (
    'id_presensi' => 3742,
    'nis' => 14328,
    'tanggal' => '2026-07-16',
    'jam' => '07:01:17',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  180 => 
  array (
    'id_presensi' => 3743,
    'nis' => 14296,
    'tanggal' => '2026-07-16',
    'jam' => '07:01:21',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  181 => 
  array (
    'id_presensi' => 3744,
    'nis' => 14311,
    'tanggal' => '2026-07-16',
    'jam' => '07:01:24',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  182 => 
  array (
    'id_presensi' => 3745,
    'nis' => 13913,
    'tanggal' => '2026-07-16',
    'jam' => '07:01:29',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  183 => 
  array (
    'id_presensi' => 3746,
    'nis' => 14610,
    'tanggal' => '2026-07-16',
    'jam' => '07:01:30',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  184 => 
  array (
    'id_presensi' => 3747,
    'nis' => 13892,
    'tanggal' => '2026-07-16',
    'jam' => '07:01:31',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  185 => 
  array (
    'id_presensi' => 3748,
    'nis' => 13918,
    'tanggal' => '2026-07-16',
    'jam' => '07:01:53',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  186 => 
  array (
    'id_presensi' => 3749,
    'nis' => 14304,
    'tanggal' => '2026-07-16',
    'jam' => '07:01:55',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  187 => 
  array (
    'id_presensi' => 3750,
    'nis' => 14334,
    'tanggal' => '2026-07-16',
    'jam' => '07:01:57',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  188 => 
  array (
    'id_presensi' => 3751,
    'nis' => 14312,
    'tanggal' => '2026-07-16',
    'jam' => '07:02:01',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  189 => 
  array (
    'id_presensi' => 3752,
    'nis' => 14138,
    'tanggal' => '2026-07-16',
    'jam' => '07:02:03',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  190 => 
  array (
    'id_presensi' => 3753,
    'nis' => 14780,
    'tanggal' => '2026-07-16',
    'jam' => '07:02:04',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  191 => 
  array (
    'id_presensi' => 3754,
    'nis' => 14127,
    'tanggal' => '2026-07-16',
    'jam' => '07:02:06',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  192 => 
  array (
    'id_presensi' => 3755,
    'nis' => 14111,
    'tanggal' => '2026-07-16',
    'jam' => '07:02:08',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  193 => 
  array (
    'id_presensi' => 3756,
    'nis' => 14122,
    'tanggal' => '2026-07-16',
    'jam' => '07:02:12',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  194 => 
  array (
    'id_presensi' => 3757,
    'nis' => 14326,
    'tanggal' => '2026-07-16',
    'jam' => '07:02:15',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  195 => 
  array (
    'id_presensi' => 3758,
    'nis' => 14155,
    'tanggal' => '2026-07-16',
    'jam' => '07:02:19',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  196 => 
  array (
    'id_presensi' => 3759,
    'nis' => 14287,
    'tanggal' => '2026-07-16',
    'jam' => '07:02:21',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  197 => 
  array (
    'id_presensi' => 3760,
    'nis' => 13894,
    'tanggal' => '2026-07-16',
    'jam' => '07:02:37',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  198 => 
  array (
    'id_presensi' => 3761,
    'nis' => 14129,
    'tanggal' => '2026-07-16',
    'jam' => '07:02:38',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  199 => 
  array (
    'id_presensi' => 3762,
    'nis' => 14289,
    'tanggal' => '2026-07-16',
    'jam' => '07:02:43',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
));

        DB::table('presensi')->insert(array (
  0 => 
  array (
    'id_presensi' => 3763,
    'nis' => 14783,
    'tanggal' => '2026-07-16',
    'jam' => '07:02:50',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  1 => 
  array (
    'id_presensi' => 3764,
    'nis' => 14125,
    'tanggal' => '2026-07-16',
    'jam' => '07:02:52',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  2 => 
  array (
    'id_presensi' => 3765,
    'nis' => 14163,
    'tanggal' => '2026-07-16',
    'jam' => '07:04:00',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  3 => 
  array (
    'id_presensi' => 3766,
    'nis' => 14228,
    'tanggal' => '2026-07-15',
    'jam' => '09:51:35',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  4 => 
  array (
    'id_presensi' => 3767,
    'nis' => 14642,
    'tanggal' => '2026-07-15',
    'jam' => '11:45:16',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  5 => 
  array (
    'id_presensi' => 3768,
    'nis' => 14738,
    'tanggal' => '2026-07-15',
    'jam' => '12:14:33',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  6 => 
  array (
    'id_presensi' => 3769,
    'nis' => 14694,
    'tanggal' => '2026-07-15',
    'jam' => '12:15:30',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  7 => 
  array (
    'id_presensi' => 3770,
    'nis' => 14690,
    'tanggal' => '2026-07-15',
    'jam' => '12:15:33',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  8 => 
  array (
    'id_presensi' => 3771,
    'nis' => 14431,
    'tanggal' => '2026-07-15',
    'jam' => '12:18:03',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  9 => 
  array (
    'id_presensi' => 3772,
    'nis' => 14434,
    'tanggal' => '2026-07-15',
    'jam' => '12:18:13',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  10 => 
  array (
    'id_presensi' => 3773,
    'nis' => 14704,
    'tanggal' => '2026-07-15',
    'jam' => '12:20:33',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  11 => 
  array (
    'id_presensi' => 3774,
    'nis' => 14720,
    'tanggal' => '2026-07-15',
    'jam' => '12:20:41',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  12 => 
  array (
    'id_presensi' => 3775,
    'nis' => 14733,
    'tanggal' => '2026-07-15',
    'jam' => '12:20:44',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  13 => 
  array (
    'id_presensi' => 3776,
    'nis' => 14710,
    'tanggal' => '2026-07-15',
    'jam' => '12:20:47',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  14 => 
  array (
    'id_presensi' => 3777,
    'nis' => 14693,
    'tanggal' => '2026-07-15',
    'jam' => '12:20:49',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  15 => 
  array (
    'id_presensi' => 3778,
    'nis' => 14715,
    'tanggal' => '2026-07-15',
    'jam' => '12:21:26',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  16 => 
  array (
    'id_presensi' => 3779,
    'nis' => 14724,
    'tanggal' => '2026-07-15',
    'jam' => '12:21:29',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  17 => 
  array (
    'id_presensi' => 3780,
    'nis' => 14703,
    'tanggal' => '2026-07-15',
    'jam' => '12:21:58',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  18 => 
  array (
    'id_presensi' => 3781,
    'nis' => 14722,
    'tanggal' => '2026-07-15',
    'jam' => '12:22:05',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  19 => 
  array (
    'id_presensi' => 3782,
    'nis' => 14464,
    'tanggal' => '2026-07-15',
    'jam' => '12:22:08',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  20 => 
  array (
    'id_presensi' => 3783,
    'nis' => 14732,
    'tanggal' => '2026-07-15',
    'jam' => '12:22:09',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  21 => 
  array (
    'id_presensi' => 3784,
    'nis' => 14460,
    'tanggal' => '2026-07-15',
    'jam' => '12:22:11',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  22 => 
  array (
    'id_presensi' => 3785,
    'nis' => 14735,
    'tanggal' => '2026-07-15',
    'jam' => '12:22:12',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  23 => 
  array (
    'id_presensi' => 3786,
    'nis' => 14454,
    'tanggal' => '2026-07-15',
    'jam' => '12:22:13',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  24 => 
  array (
    'id_presensi' => 3787,
    'nis' => 14458,
    'tanggal' => '2026-07-15',
    'jam' => '12:22:16',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  25 => 
  array (
    'id_presensi' => 3788,
    'nis' => 14713,
    'tanggal' => '2026-07-15',
    'jam' => '12:22:21',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  26 => 
  array (
    'id_presensi' => 3789,
    'nis' => 14683,
    'tanggal' => '2026-07-15',
    'jam' => '12:22:26',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  27 => 
  array (
    'id_presensi' => 3790,
    'nis' => 14674,
    'tanggal' => '2026-07-15',
    'jam' => '12:22:33',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  28 => 
  array (
    'id_presensi' => 3791,
    'nis' => 14728,
    'tanggal' => '2026-07-15',
    'jam' => '12:22:39',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  29 => 
  array (
    'id_presensi' => 3792,
    'nis' => 14730,
    'tanggal' => '2026-07-15',
    'jam' => '12:22:50',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  30 => 
  array (
    'id_presensi' => 3793,
    'nis' => 14682,
    'tanggal' => '2026-07-15',
    'jam' => '12:23:09',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  31 => 
  array (
    'id_presensi' => 3794,
    'nis' => 14705,
    'tanggal' => '2026-07-15',
    'jam' => '12:23:20',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  32 => 
  array (
    'id_presensi' => 3795,
    'nis' => 14712,
    'tanggal' => '2026-07-15',
    'jam' => '12:23:27',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  33 => 
  array (
    'id_presensi' => 3796,
    'nis' => 14688,
    'tanggal' => '2026-07-15',
    'jam' => '12:23:29',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  34 => 
  array (
    'id_presensi' => 3797,
    'nis' => 14689,
    'tanggal' => '2026-07-15',
    'jam' => '12:23:35',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  35 => 
  array (
    'id_presensi' => 3798,
    'nis' => 14685,
    'tanggal' => '2026-07-15',
    'jam' => '12:23:45',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  36 => 
  array (
    'id_presensi' => 3799,
    'nis' => 14686,
    'tanggal' => '2026-07-15',
    'jam' => '12:23:47',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  37 => 
  array (
    'id_presensi' => 3800,
    'nis' => 14680,
    'tanggal' => '2026-07-15',
    'jam' => '12:23:54',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  38 => 
  array (
    'id_presensi' => 3801,
    'nis' => 14691,
    'tanggal' => '2026-07-15',
    'jam' => '12:23:59',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  39 => 
  array (
    'id_presensi' => 3802,
    'nis' => 14737,
    'tanggal' => '2026-07-15',
    'jam' => '12:24:14',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  40 => 
  array (
    'id_presensi' => 3803,
    'nis' => 14687,
    'tanggal' => '2026-07-15',
    'jam' => '12:24:31',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  41 => 
  array (
    'id_presensi' => 3804,
    'nis' => 14679,
    'tanggal' => '2026-07-15',
    'jam' => '12:24:36',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  42 => 
  array (
    'id_presensi' => 3805,
    'nis' => 14706,
    'tanggal' => '2026-07-15',
    'jam' => '12:24:38',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  43 => 
  array (
    'id_presensi' => 3806,
    'nis' => 14716,
    'tanggal' => '2026-07-15',
    'jam' => '12:24:42',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  44 => 
  array (
    'id_presensi' => 3807,
    'nis' => 14677,
    'tanggal' => '2026-07-15',
    'jam' => '12:24:47',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  45 => 
  array (
    'id_presensi' => 3808,
    'nis' => 14717,
    'tanggal' => '2026-07-15',
    'jam' => '12:24:57',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  46 => 
  array (
    'id_presensi' => 3809,
    'nis' => 14697,
    'tanggal' => '2026-07-15',
    'jam' => '12:25:04',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  47 => 
  array (
    'id_presensi' => 3810,
    'nis' => 14684,
    'tanggal' => '2026-07-15',
    'jam' => '12:25:06',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  48 => 
  array (
    'id_presensi' => 3811,
    'nis' => 14726,
    'tanggal' => '2026-07-15',
    'jam' => '12:25:18',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  49 => 
  array (
    'id_presensi' => 3812,
    'nis' => 14707,
    'tanggal' => '2026-07-15',
    'jam' => '12:26:50',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  50 => 
  array (
    'id_presensi' => 3813,
    'nis' => 14592,
    'tanggal' => '2026-07-15',
    'jam' => '12:26:52',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  51 => 
  array (
    'id_presensi' => 3814,
    'nis' => 14598,
    'tanggal' => '2026-07-15',
    'jam' => '12:26:55',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  52 => 
  array (
    'id_presensi' => 3815,
    'nis' => 14579,
    'tanggal' => '2026-07-15',
    'jam' => '12:26:57',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  53 => 
  array (
    'id_presensi' => 3816,
    'nis' => 14605,
    'tanggal' => '2026-07-15',
    'jam' => '12:27:02',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  54 => 
  array (
    'id_presensi' => 3817,
    'nis' => 14495,
    'tanggal' => '2026-07-15',
    'jam' => '12:27:06',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  55 => 
  array (
    'id_presensi' => 3818,
    'nis' => 14721,
    'tanggal' => '2026-07-15',
    'jam' => '12:27:18',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  56 => 
  array (
    'id_presensi' => 3819,
    'nis' => 14511,
    'tanggal' => '2026-07-15',
    'jam' => '12:27:56',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  57 => 
  array (
    'id_presensi' => 3820,
    'nis' => 14491,
    'tanggal' => '2026-07-15',
    'jam' => '12:27:59',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  58 => 
  array (
    'id_presensi' => 3821,
    'nis' => 14492,
    'tanggal' => '2026-07-15',
    'jam' => '12:28:01',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  59 => 
  array (
    'id_presensi' => 3822,
    'nis' => 14584,
    'tanggal' => '2026-07-15',
    'jam' => '12:28:20',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  60 => 
  array (
    'id_presensi' => 3823,
    'nis' => 14453,
    'tanggal' => '2026-07-15',
    'jam' => '12:28:44',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  61 => 
  array (
    'id_presensi' => 3824,
    'nis' => 14588,
    'tanggal' => '2026-07-15',
    'jam' => '12:29:38',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  62 => 
  array (
    'id_presensi' => 3825,
    'nis' => 14604,
    'tanggal' => '2026-07-15',
    'jam' => '12:29:44',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  63 => 
  array (
    'id_presensi' => 3826,
    'nis' => 14490,
    'tanggal' => '2026-07-15',
    'jam' => '12:30:19',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  64 => 
  array (
    'id_presensi' => 3827,
    'nis' => 14503,
    'tanggal' => '2026-07-15',
    'jam' => '12:30:23',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  65 => 
  array (
    'id_presensi' => 3828,
    'nis' => 14599,
    'tanggal' => '2026-07-15',
    'jam' => '12:31:17',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  66 => 
  array (
    'id_presensi' => 3829,
    'nis' => 14452,
    'tanggal' => '2026-07-15',
    'jam' => '12:31:21',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  67 => 
  array (
    'id_presensi' => 3830,
    'nis' => 14606,
    'tanggal' => '2026-07-15',
    'jam' => '12:31:37',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  68 => 
  array (
    'id_presensi' => 3831,
    'nis' => 14583,
    'tanggal' => '2026-07-15',
    'jam' => '12:31:43',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  69 => 
  array (
    'id_presensi' => 3832,
    'nis' => 14482,
    'tanggal' => '2026-07-15',
    'jam' => '12:31:52',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  70 => 
  array (
    'id_presensi' => 3833,
    'nis' => 14498,
    'tanggal' => '2026-07-15',
    'jam' => '12:31:55',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  71 => 
  array (
    'id_presensi' => 3834,
    'nis' => 14512,
    'tanggal' => '2026-07-15',
    'jam' => '12:31:58',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  72 => 
  array (
    'id_presensi' => 3835,
    'nis' => 14709,
    'tanggal' => '2026-07-15',
    'jam' => '12:32:43',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  73 => 
  array (
    'id_presensi' => 3836,
    'nis' => 14696,
    'tanggal' => '2026-07-15',
    'jam' => '12:32:51',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  74 => 
  array (
    'id_presensi' => 3837,
    'nis' => 14596,
    'tanggal' => '2026-07-15',
    'jam' => '12:32:55',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  75 => 
  array (
    'id_presensi' => 3838,
    'nis' => 14424,
    'tanggal' => '2026-07-15',
    'jam' => '12:33:05',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  76 => 
  array (
    'id_presensi' => 3839,
    'nis' => 14725,
    'tanggal' => '2026-07-15',
    'jam' => '12:33:10',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  77 => 
  array (
    'id_presensi' => 3840,
    'nis' => 14440,
    'tanggal' => '2026-07-15',
    'jam' => '12:33:11',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  78 => 
  array (
    'id_presensi' => 3841,
    'nis' => 14591,
    'tanggal' => '2026-07-15',
    'jam' => '12:33:12',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  79 => 
  array (
    'id_presensi' => 3842,
    'nis' => 14587,
    'tanggal' => '2026-07-15',
    'jam' => '12:33:14',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  80 => 
  array (
    'id_presensi' => 3843,
    'nis' => 14446,
    'tanggal' => '2026-07-15',
    'jam' => '12:33:21',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  81 => 
  array (
    'id_presensi' => 3844,
    'nis' => 14727,
    'tanggal' => '2026-07-15',
    'jam' => '12:33:33',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  82 => 
  array (
    'id_presensi' => 3845,
    'nis' => 14445,
    'tanggal' => '2026-07-15',
    'jam' => '12:33:35',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  83 => 
  array (
    'id_presensi' => 3846,
    'nis' => 14736,
    'tanggal' => '2026-07-15',
    'jam' => '12:33:44',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  84 => 
  array (
    'id_presensi' => 3847,
    'nis' => 14723,
    'tanggal' => '2026-07-15',
    'jam' => '12:34:07',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  85 => 
  array (
    'id_presensi' => 3848,
    'nis' => 14714,
    'tanggal' => '2026-07-15',
    'jam' => '12:34:11',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  86 => 
  array (
    'id_presensi' => 3849,
    'nis' => 14734,
    'tanggal' => '2026-07-15',
    'jam' => '12:34:14',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  87 => 
  array (
    'id_presensi' => 3850,
    'nis' => 14676,
    'tanggal' => '2026-07-15',
    'jam' => '12:35:51',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  88 => 
  array (
    'id_presensi' => 3851,
    'nis' => 14700,
    'tanggal' => '2026-07-15',
    'jam' => '12:35:56',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  89 => 
  array (
    'id_presensi' => 3852,
    'nis' => 14593,
    'tanggal' => '2026-07-15',
    'jam' => '12:37:47',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  90 => 
  array (
    'id_presensi' => 3853,
    'nis' => 14602,
    'tanggal' => '2026-07-15',
    'jam' => '12:37:57',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  91 => 
  array (
    'id_presensi' => 3854,
    'nis' => 14444,
    'tanggal' => '2026-07-15',
    'jam' => '12:46:54',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  92 => 
  array (
    'id_presensi' => 3855,
    'nis' => 14603,
    'tanggal' => '2026-07-15',
    'jam' => '13:04:06',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  93 => 
  array (
    'id_presensi' => 3856,
    'nis' => 14594,
    'tanggal' => '2026-07-15',
    'jam' => '13:04:08',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  94 => 
  array (
    'id_presensi' => 3857,
    'nis' => 14510,
    'tanggal' => '2026-07-15',
    'jam' => '13:28:41',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  95 => 
  array (
    'id_presensi' => 3858,
    'nis' => 14441,
    'tanggal' => '2026-07-15',
    'jam' => '13:44:47',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  96 => 
  array (
    'id_presensi' => 3859,
    'nis' => 14473,
    'tanggal' => '2026-07-15',
    'jam' => '13:45:12',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  97 => 
  array (
    'id_presensi' => 3860,
    'nis' => 14459,
    'tanggal' => '2026-07-15',
    'jam' => '13:50:09',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  98 => 
  array (
    'id_presensi' => 3861,
    'nis' => 14581,
    'tanggal' => '2026-07-15',
    'jam' => '13:54:39',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  99 => 
  array (
    'id_presensi' => 3862,
    'nis' => 14466,
    'tanggal' => '2026-07-15',
    'jam' => '13:57:04',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  100 => 
  array (
    'id_presensi' => 3863,
    'nis' => 14698,
    'tanggal' => '2026-07-15',
    'jam' => '14:59:31',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  101 => 
  array (
    'id_presensi' => 3864,
    'nis' => 14435,
    'tanggal' => '2026-07-15',
    'jam' => '15:07:16',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  102 => 
  array (
    'id_presensi' => 3865,
    'nis' => 14439,
    'tanggal' => '2026-07-15',
    'jam' => '15:53:05',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  103 => 
  array (
    'id_presensi' => 3866,
    'nis' => 14420,
    'tanggal' => '2026-07-15',
    'jam' => '15:57:17',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  104 => 
  array (
    'id_presensi' => 3867,
    'nis' => 14742,
    'tanggal' => '2026-07-16',
    'jam' => '06:27:23',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  105 => 
  array (
    'id_presensi' => 3868,
    'nis' => 14170,
    'tanggal' => '2026-07-16',
    'jam' => '06:37:19',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  106 => 
  array (
    'id_presensi' => 3869,
    'nis' => 14227,
    'tanggal' => '2026-07-16',
    'jam' => '06:38:43',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  107 => 
  array (
    'id_presensi' => 3870,
    'nis' => 14769,
    'tanggal' => '2026-07-16',
    'jam' => '06:50:51',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  108 => 
  array (
    'id_presensi' => 3871,
    'nis' => 14751,
    'tanggal' => '2026-07-16',
    'jam' => '06:50:54',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  109 => 
  array (
    'id_presensi' => 3872,
    'nis' => 14572,
    'tanggal' => '2026-07-16',
    'jam' => '06:52:19',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  110 => 
  array (
    'id_presensi' => 3873,
    'nis' => 14391,
    'tanggal' => '2026-07-16',
    'jam' => '06:53:44',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  111 => 
  array (
    'id_presensi' => 3874,
    'nis' => 14112,
    'tanggal' => '2026-07-16',
    'jam' => '06:53:47',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  112 => 
  array (
    'id_presensi' => 3875,
    'nis' => 14242,
    'tanggal' => '2026-07-16',
    'jam' => '06:54:11',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  113 => 
  array (
    'id_presensi' => 3876,
    'nis' => 14787,
    'tanggal' => '2026-07-16',
    'jam' => '06:54:12',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  114 => 
  array (
    'id_presensi' => 3877,
    'nis' => 14247,
    'tanggal' => '2026-07-16',
    'jam' => '06:54:16',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  115 => 
  array (
    'id_presensi' => 3878,
    'nis' => 14231,
    'tanggal' => '2026-07-16',
    'jam' => '06:54:18',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  116 => 
  array (
    'id_presensi' => 3879,
    'nis' => 14793,
    'tanggal' => '2026-07-16',
    'jam' => '06:54:21',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  117 => 
  array (
    'id_presensi' => 3880,
    'nis' => 14788,
    'tanggal' => '2026-07-16',
    'jam' => '06:54:24',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  118 => 
  array (
    'id_presensi' => 3881,
    'nis' => 14796,
    'tanggal' => '2026-07-16',
    'jam' => '06:54:26',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  119 => 
  array (
    'id_presensi' => 3882,
    'nis' => 13884,
    'tanggal' => '2026-07-16',
    'jam' => '06:54:30',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  120 => 
  array (
    'id_presensi' => 3883,
    'nis' => 14292,
    'tanggal' => '2026-07-16',
    'jam' => '06:54:33',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  121 => 
  array (
    'id_presensi' => 3884,
    'nis' => 14647,
    'tanggal' => '2026-07-16',
    'jam' => '06:54:44',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  122 => 
  array (
    'id_presensi' => 3885,
    'nis' => 14161,
    'tanggal' => '2026-07-16',
    'jam' => '06:54:48',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  123 => 
  array (
    'id_presensi' => 3886,
    'nis' => 14222,
    'tanggal' => '2026-07-16',
    'jam' => '06:55:05',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  124 => 
  array (
    'id_presensi' => 3887,
    'nis' => 14778,
    'tanggal' => '2026-07-16',
    'jam' => '06:55:08',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  125 => 
  array (
    'id_presensi' => 3888,
    'nis' => 14223,
    'tanggal' => '2026-07-16',
    'jam' => '06:55:22',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  126 => 
  array (
    'id_presensi' => 3889,
    'nis' => 14623,
    'tanggal' => '2026-07-16',
    'jam' => '06:55:23',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  127 => 
  array (
    'id_presensi' => 3890,
    'nis' => 14656,
    'tanggal' => '2026-07-16',
    'jam' => '06:55:26',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  128 => 
  array (
    'id_presensi' => 3891,
    'nis' => 13886,
    'tanggal' => '2026-07-16',
    'jam' => '06:55:38',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  129 => 
  array (
    'id_presensi' => 3892,
    'nis' => 14754,
    'tanggal' => '2026-07-16',
    'jam' => '06:56:24',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  130 => 
  array (
    'id_presensi' => 3893,
    'nis' => 14306,
    'tanggal' => '2026-07-16',
    'jam' => '06:56:27',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  131 => 
  array (
    'id_presensi' => 3894,
    'nis' => 14611,
    'tanggal' => '2026-07-16',
    'jam' => '06:56:29',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  132 => 
  array (
    'id_presensi' => 3895,
    'nis' => 14760,
    'tanggal' => '2026-07-16',
    'jam' => '06:56:33',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  133 => 
  array (
    'id_presensi' => 3896,
    'nis' => 14639,
    'tanggal' => '2026-07-16',
    'jam' => '06:56:36',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  134 => 
  array (
    'id_presensi' => 3897,
    'nis' => 14234,
    'tanggal' => '2026-07-16',
    'jam' => '06:56:40',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  135 => 
  array (
    'id_presensi' => 3898,
    'nis' => 14230,
    'tanggal' => '2026-07-16',
    'jam' => '06:56:43',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  136 => 
  array (
    'id_presensi' => 3899,
    'nis' => 14388,
    'tanggal' => '2026-07-16',
    'jam' => '06:57:35',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  137 => 
  array (
    'id_presensi' => 3900,
    'nis' => 13905,
    'tanggal' => '2026-07-16',
    'jam' => '06:58:49',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  138 => 
  array (
    'id_presensi' => 3901,
    'nis' => 13903,
    'tanggal' => '2026-07-16',
    'jam' => '06:58:55',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  139 => 
  array (
    'id_presensi' => 3902,
    'nis' => 14319,
    'tanggal' => '2026-07-16',
    'jam' => '06:59:03',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  140 => 
  array (
    'id_presensi' => 3903,
    'nis' => 14167,
    'tanggal' => '2026-07-16',
    'jam' => '06:59:12',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  141 => 
  array (
    'id_presensi' => 3904,
    'nis' => 14666,
    'tanggal' => '2026-07-16',
    'jam' => '06:59:46',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  142 => 
  array (
    'id_presensi' => 3905,
    'nis' => 14305,
    'tanggal' => '2026-07-16',
    'jam' => '07:00:00',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  143 => 
  array (
    'id_presensi' => 3906,
    'nis' => 14236,
    'tanggal' => '2026-07-16',
    'jam' => '07:13:04',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  144 => 
  array (
    'id_presensi' => 3907,
    'nis' => 14244,
    'tanggal' => '2026-07-16',
    'jam' => '07:14:43',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  145 => 
  array (
    'id_presensi' => 3908,
    'nis' => 14367,
    'tanggal' => '2026-07-16',
    'jam' => '07:14:46',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  146 => 
  array (
    'id_presensi' => 3909,
    'nis' => 14217,
    'tanggal' => '2026-07-16',
    'jam' => '07:14:48',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  147 => 
  array (
    'id_presensi' => 3910,
    'nis' => 14246,
    'tanggal' => '2026-07-16',
    'jam' => '07:14:51',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  148 => 
  array (
    'id_presensi' => 3911,
    'nis' => 14226,
    'tanggal' => '2026-07-16',
    'jam' => '07:14:55',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  149 => 
  array (
    'id_presensi' => 3912,
    'nis' => 14232,
    'tanggal' => '2026-07-16',
    'jam' => '07:15:13',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  150 => 
  array (
    'id_presensi' => 3913,
    'nis' => 14261,
    'tanggal' => '2026-07-16',
    'jam' => '07:15:16',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  151 => 
  array (
    'id_presensi' => 3914,
    'nis' => 14225,
    'tanggal' => '2026-07-16',
    'jam' => '07:15:18',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  152 => 
  array (
    'id_presensi' => 3915,
    'nis' => 14233,
    'tanggal' => '2026-07-16',
    'jam' => '07:15:23',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  153 => 
  array (
    'id_presensi' => 3916,
    'nis' => 14237,
    'tanggal' => '2026-07-16',
    'jam' => '07:15:28',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  154 => 
  array (
    'id_presensi' => 3917,
    'nis' => 13897,
    'tanggal' => '2026-07-16',
    'jam' => '07:22:55',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  155 => 
  array (
    'id_presensi' => 3918,
    'nis' => 13914,
    'tanggal' => '2026-07-16',
    'jam' => '08:00:26',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  156 => 
  array (
    'id_presensi' => 3919,
    'nis' => 14746,
    'tanggal' => '2026-07-16',
    'jam' => '08:12:22',
    'status' => '1',
    'keterangan' => NULL,
    'file' => NULL,
  ),
  157 => 
  array (
    'id_presensi' => 3920,
    'nis' => 14766,
    'tanggal' => '2026-07-16',
    'jam' => '08:12:22',
    'status' => '4',
    'keterangan' => NULL,
    'file' => NULL,
  ),
  158 => 
  array (
    'id_presensi' => 3921,
    'nis' => 15382,
    'tanggal' => '2026-07-16',
    'jam' => '08:13:33',
    'status' => '1',
    'keterangan' => NULL,
    'file' => NULL,
  ),
  159 => 
  array (
    'id_presensi' => 3922,
    'nis' => 15383,
    'tanggal' => '2026-07-16',
    'jam' => '08:13:33',
    'status' => '1',
    'keterangan' => NULL,
    'file' => NULL,
  ),
  160 => 
  array (
    'id_presensi' => 3923,
    'nis' => 15384,
    'tanggal' => '2026-07-16',
    'jam' => '08:13:33',
    'status' => '1',
    'keterangan' => NULL,
    'file' => NULL,
  ),
  161 => 
  array (
    'id_presensi' => 3924,
    'nis' => 15385,
    'tanggal' => '2026-07-16',
    'jam' => '08:13:33',
    'status' => '1',
    'keterangan' => NULL,
    'file' => NULL,
  ),
  162 => 
  array (
    'id_presensi' => 3925,
    'nis' => 15386,
    'tanggal' => '2026-07-16',
    'jam' => '08:13:33',
    'status' => '1',
    'keterangan' => NULL,
    'file' => NULL,
  ),
  163 => 
  array (
    'id_presensi' => 3926,
    'nis' => 15387,
    'tanggal' => '2026-07-16',
    'jam' => '08:13:33',
    'status' => '1',
    'keterangan' => NULL,
    'file' => NULL,
  ),
  164 => 
  array (
    'id_presensi' => 3927,
    'nis' => 15388,
    'tanggal' => '2026-07-16',
    'jam' => '08:13:33',
    'status' => '1',
    'keterangan' => NULL,
    'file' => NULL,
  ),
  165 => 
  array (
    'id_presensi' => 3928,
    'nis' => 15389,
    'tanggal' => '2026-07-16',
    'jam' => '08:13:33',
    'status' => '1',
    'keterangan' => NULL,
    'file' => NULL,
  ),
  166 => 
  array (
    'id_presensi' => 3929,
    'nis' => 15390,
    'tanggal' => '2026-07-16',
    'jam' => '08:13:34',
    'status' => '2',
    'keterangan' => NULL,
    'file' => 'siswa/presensi/NTQVRm31zArOujTyY9rdp6fTTJIOXLRYLbS0gJ2t.jpg',
  ),
  167 => 
  array (
    'id_presensi' => 3930,
    'nis' => 15391,
    'tanggal' => '2026-07-16',
    'jam' => '08:13:34',
    'status' => '1',
    'keterangan' => NULL,
    'file' => NULL,
  ),
  168 => 
  array (
    'id_presensi' => 3931,
    'nis' => 15392,
    'tanggal' => '2026-07-16',
    'jam' => '08:13:34',
    'status' => '1',
    'keterangan' => NULL,
    'file' => NULL,
  ),
  169 => 
  array (
    'id_presensi' => 3932,
    'nis' => 15393,
    'tanggal' => '2026-07-16',
    'jam' => '08:13:34',
    'status' => '1',
    'keterangan' => NULL,
    'file' => NULL,
  ),
  170 => 
  array (
    'id_presensi' => 3933,
    'nis' => 15394,
    'tanggal' => '2026-07-16',
    'jam' => '08:13:34',
    'status' => '1',
    'keterangan' => NULL,
    'file' => NULL,
  ),
  171 => 
  array (
    'id_presensi' => 3934,
    'nis' => 15395,
    'tanggal' => '2026-07-16',
    'jam' => '08:13:34',
    'status' => '1',
    'keterangan' => NULL,
    'file' => NULL,
  ),
  172 => 
  array (
    'id_presensi' => 3935,
    'nis' => 15396,
    'tanggal' => '2026-07-16',
    'jam' => '08:13:34',
    'status' => '1',
    'keterangan' => NULL,
    'file' => NULL,
  ),
  173 => 
  array (
    'id_presensi' => 3936,
    'nis' => 15397,
    'tanggal' => '2026-07-16',
    'jam' => '08:13:34',
    'status' => '1',
    'keterangan' => NULL,
    'file' => NULL,
  ),
  174 => 
  array (
    'id_presensi' => 3937,
    'nis' => 15398,
    'tanggal' => '2026-07-16',
    'jam' => '08:13:34',
    'status' => '1',
    'keterangan' => NULL,
    'file' => NULL,
  ),
  175 => 
  array (
    'id_presensi' => 3938,
    'nis' => 15399,
    'tanggal' => '2026-07-16',
    'jam' => '08:13:34',
    'status' => '1',
    'keterangan' => NULL,
    'file' => NULL,
  ),
  176 => 
  array (
    'id_presensi' => 3939,
    'nis' => 15400,
    'tanggal' => '2026-07-16',
    'jam' => '08:13:34',
    'status' => '1',
    'keterangan' => NULL,
    'file' => NULL,
  ),
  177 => 
  array (
    'id_presensi' => 3940,
    'nis' => 15401,
    'tanggal' => '2026-07-16',
    'jam' => '08:13:34',
    'status' => '1',
    'keterangan' => NULL,
    'file' => NULL,
  ),
  178 => 
  array (
    'id_presensi' => 3941,
    'nis' => 15402,
    'tanggal' => '2026-07-16',
    'jam' => '08:13:34',
    'status' => '1',
    'keterangan' => NULL,
    'file' => NULL,
  ),
  179 => 
  array (
    'id_presensi' => 3942,
    'nis' => 15403,
    'tanggal' => '2026-07-16',
    'jam' => '08:13:34',
    'status' => '1',
    'keterangan' => NULL,
    'file' => NULL,
  ),
  180 => 
  array (
    'id_presensi' => 3943,
    'nis' => 15404,
    'tanggal' => '2026-07-16',
    'jam' => '08:13:34',
    'status' => '1',
    'keterangan' => NULL,
    'file' => NULL,
  ),
  181 => 
  array (
    'id_presensi' => 3944,
    'nis' => 15405,
    'tanggal' => '2026-07-16',
    'jam' => '08:13:34',
    'status' => '1',
    'keterangan' => NULL,
    'file' => NULL,
  ),
  182 => 
  array (
    'id_presensi' => 3945,
    'nis' => 15406,
    'tanggal' => '2026-07-16',
    'jam' => '08:13:34',
    'status' => '1',
    'keterangan' => NULL,
    'file' => NULL,
  ),
  183 => 
  array (
    'id_presensi' => 3946,
    'nis' => 15407,
    'tanggal' => '2026-07-16',
    'jam' => '08:13:34',
    'status' => '1',
    'keterangan' => NULL,
    'file' => NULL,
  ),
  184 => 
  array (
    'id_presensi' => 3947,
    'nis' => 15408,
    'tanggal' => '2026-07-16',
    'jam' => '08:13:34',
    'status' => '1',
    'keterangan' => NULL,
    'file' => NULL,
  ),
  185 => 
  array (
    'id_presensi' => 3948,
    'nis' => 15409,
    'tanggal' => '2026-07-16',
    'jam' => '08:13:34',
    'status' => '1',
    'keterangan' => NULL,
    'file' => NULL,
  ),
  186 => 
  array (
    'id_presensi' => 3949,
    'nis' => 15410,
    'tanggal' => '2026-07-16',
    'jam' => '08:13:34',
    'status' => '1',
    'keterangan' => NULL,
    'file' => NULL,
  ),
  187 => 
  array (
    'id_presensi' => 3950,
    'nis' => 15411,
    'tanggal' => '2026-07-16',
    'jam' => '08:13:34',
    'status' => '1',
    'keterangan' => NULL,
    'file' => NULL,
  ),
  188 => 
  array (
    'id_presensi' => 3951,
    'nis' => 15412,
    'tanggal' => '2026-07-16',
    'jam' => '08:13:34',
    'status' => '1',
    'keterangan' => NULL,
    'file' => NULL,
  ),
  189 => 
  array (
    'id_presensi' => 3952,
    'nis' => 15413,
    'tanggal' => '2026-07-16',
    'jam' => '08:13:34',
    'status' => '1',
    'keterangan' => NULL,
    'file' => NULL,
  ),
  190 => 
  array (
    'id_presensi' => 3953,
    'nis' => 15414,
    'tanggal' => '2026-07-16',
    'jam' => '08:13:34',
    'status' => '1',
    'keterangan' => NULL,
    'file' => NULL,
  ),
  191 => 
  array (
    'id_presensi' => 3954,
    'nis' => 15415,
    'tanggal' => '2026-07-16',
    'jam' => '08:13:34',
    'status' => '1',
    'keterangan' => NULL,
    'file' => NULL,
  ),
  192 => 
  array (
    'id_presensi' => 3955,
    'nis' => 15416,
    'tanggal' => '2026-07-16',
    'jam' => '08:13:34',
    'status' => '1',
    'keterangan' => NULL,
    'file' => NULL,
  ),
  193 => 
  array (
    'id_presensi' => 3956,
    'nis' => 15417,
    'tanggal' => '2026-07-16',
    'jam' => '08:13:34',
    'status' => '1',
    'keterangan' => NULL,
    'file' => NULL,
  ),
  194 => 
  array (
    'id_presensi' => 3957,
    'nis' => 15418,
    'tanggal' => '2026-07-16',
    'jam' => '08:13:34',
    'status' => '1',
    'keterangan' => NULL,
    'file' => NULL,
  ),
  195 => 
  array (
    'id_presensi' => 3958,
    'nis' => 15419,
    'tanggal' => '2026-07-16',
    'jam' => '08:13:34',
    'status' => '1',
    'keterangan' => NULL,
    'file' => NULL,
  ),
  196 => 
  array (
    'id_presensi' => 3959,
    'nis' => 15420,
    'tanggal' => '2026-07-16',
    'jam' => '08:13:34',
    'status' => '1',
    'keterangan' => NULL,
    'file' => NULL,
  ),
  197 => 
  array (
    'id_presensi' => 3960,
    'nis' => 14376,
    'tanggal' => '2026-07-16',
    'jam' => '08:12:44',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  198 => 
  array (
    'id_presensi' => 3961,
    'nis' => 14248,
    'tanggal' => '2026-07-16',
    'jam' => '09:32:39',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  199 => 
  array (
    'id_presensi' => 3962,
    'nis' => 14521,
    'tanggal' => '2026-07-16',
    'jam' => '09:49:10',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
));

        DB::table('presensi')->insert(array (
  0 => 
  array (
    'id_presensi' => 3963,
    'nis' => 14542,
    'tanggal' => '2026-07-16',
    'jam' => '10:58:18',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  1 => 
  array (
    'id_presensi' => 3964,
    'nis' => 14214,
    'tanggal' => '2026-07-16',
    'jam' => '11:29:01',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  2 => 
  array (
    'id_presensi' => 3965,
    'nis' => 14224,
    'tanggal' => '2026-07-16',
    'jam' => '12:01:31',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  3 => 
  array (
    'id_presensi' => 3966,
    'nis' => 14689,
    'tanggal' => '2026-07-16',
    'jam' => '12:16:27',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  4 => 
  array (
    'id_presensi' => 3967,
    'nis' => 14704,
    'tanggal' => '2026-07-16',
    'jam' => '12:16:29',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  5 => 
  array (
    'id_presensi' => 3968,
    'nis' => 14691,
    'tanggal' => '2026-07-16',
    'jam' => '12:16:31',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  6 => 
  array (
    'id_presensi' => 3969,
    'nis' => 14683,
    'tanggal' => '2026-07-16',
    'jam' => '12:16:33',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  7 => 
  array (
    'id_presensi' => 3970,
    'nis' => 14693,
    'tanggal' => '2026-07-16',
    'jam' => '12:16:36',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  8 => 
  array (
    'id_presensi' => 3971,
    'nis' => 14687,
    'tanggal' => '2026-07-16',
    'jam' => '12:16:41',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  9 => 
  array (
    'id_presensi' => 3972,
    'nis' => 14674,
    'tanggal' => '2026-07-16',
    'jam' => '12:16:45',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  10 => 
  array (
    'id_presensi' => 3973,
    'nis' => 14703,
    'tanggal' => '2026-07-16',
    'jam' => '12:16:47',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  11 => 
  array (
    'id_presensi' => 3974,
    'nis' => 14690,
    'tanggal' => '2026-07-16',
    'jam' => '12:16:50',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  12 => 
  array (
    'id_presensi' => 3975,
    'nis' => 14688,
    'tanggal' => '2026-07-16',
    'jam' => '12:16:53',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  13 => 
  array (
    'id_presensi' => 3976,
    'nis' => 14722,
    'tanggal' => '2026-07-16',
    'jam' => '12:16:59',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  14 => 
  array (
    'id_presensi' => 3977,
    'nis' => 14737,
    'tanggal' => '2026-07-16',
    'jam' => '12:17:02',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  15 => 
  array (
    'id_presensi' => 3978,
    'nis' => 14713,
    'tanggal' => '2026-07-16',
    'jam' => '12:17:07',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  16 => 
  array (
    'id_presensi' => 3979,
    'nis' => 14679,
    'tanggal' => '2026-07-16',
    'jam' => '12:19:13',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  17 => 
  array (
    'id_presensi' => 3980,
    'nis' => 14711,
    'tanggal' => '2026-07-16',
    'jam' => '12:22:58',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  18 => 
  array (
    'id_presensi' => 3981,
    'nis' => 14712,
    'tanggal' => '2026-07-16',
    'jam' => '12:23:00',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  19 => 
  array (
    'id_presensi' => 3982,
    'nis' => 14733,
    'tanggal' => '2026-07-16',
    'jam' => '12:23:02',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  20 => 
  array (
    'id_presensi' => 3983,
    'nis' => 14136,
    'tanggal' => '2026-07-16',
    'jam' => '12:23:04',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  21 => 
  array (
    'id_presensi' => 3984,
    'nis' => 14720,
    'tanggal' => '2026-07-16',
    'jam' => '12:23:04',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  22 => 
  array (
    'id_presensi' => 3985,
    'nis' => 14710,
    'tanggal' => '2026-07-16',
    'jam' => '12:23:07',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  23 => 
  array (
    'id_presensi' => 3986,
    'nis' => 14677,
    'tanggal' => '2026-07-16',
    'jam' => '12:23:09',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  24 => 
  array (
    'id_presensi' => 3987,
    'nis' => 14705,
    'tanggal' => '2026-07-16',
    'jam' => '12:23:16',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  25 => 
  array (
    'id_presensi' => 3988,
    'nis' => 14735,
    'tanggal' => '2026-07-16',
    'jam' => '12:23:23',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  26 => 
  array (
    'id_presensi' => 3989,
    'nis' => 14446,
    'tanggal' => '2026-07-16',
    'jam' => '12:23:43',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  27 => 
  array (
    'id_presensi' => 3990,
    'nis' => 14716,
    'tanggal' => '2026-07-16',
    'jam' => '12:23:47',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  28 => 
  array (
    'id_presensi' => 3991,
    'nis' => 14433,
    'tanggal' => '2026-07-16',
    'jam' => '12:23:49',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  29 => 
  array (
    'id_presensi' => 3992,
    'nis' => 14715,
    'tanggal' => '2026-07-16',
    'jam' => '12:23:52',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  30 => 
  array (
    'id_presensi' => 3993,
    'nis' => 14707,
    'tanggal' => '2026-07-16',
    'jam' => '12:24:07',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  31 => 
  array (
    'id_presensi' => 3994,
    'nis' => 14443,
    'tanggal' => '2026-07-16',
    'jam' => '12:24:08',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  32 => 
  array (
    'id_presensi' => 3995,
    'nis' => 14440,
    'tanggal' => '2026-07-16',
    'jam' => '12:24:11',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  33 => 
  array (
    'id_presensi' => 3996,
    'nis' => 14724,
    'tanggal' => '2026-07-16',
    'jam' => '12:24:13',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  34 => 
  array (
    'id_presensi' => 3997,
    'nis' => 14725,
    'tanggal' => '2026-07-16',
    'jam' => '12:24:16',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  35 => 
  array (
    'id_presensi' => 3998,
    'nis' => 14706,
    'tanggal' => '2026-07-16',
    'jam' => '12:24:26',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  36 => 
  array (
    'id_presensi' => 3999,
    'nis' => 14680,
    'tanggal' => '2026-07-16',
    'jam' => '12:24:37',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  37 => 
  array (
    'id_presensi' => 4000,
    'nis' => 14723,
    'tanggal' => '2026-07-16',
    'jam' => '12:24:40',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  38 => 
  array (
    'id_presensi' => 4001,
    'nis' => 14732,
    'tanggal' => '2026-07-16',
    'jam' => '12:25:26',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  39 => 
  array (
    'id_presensi' => 4002,
    'nis' => 14686,
    'tanggal' => '2026-07-16',
    'jam' => '12:26:45',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  40 => 
  array (
    'id_presensi' => 4003,
    'nis' => 14721,
    'tanggal' => '2026-07-16',
    'jam' => '12:26:48',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  41 => 
  array (
    'id_presensi' => 4004,
    'nis' => 14726,
    'tanggal' => '2026-07-16',
    'jam' => '12:27:25',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  42 => 
  array (
    'id_presensi' => 4005,
    'nis' => 14694,
    'tanggal' => '2026-07-16',
    'jam' => '12:27:50',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  43 => 
  array (
    'id_presensi' => 4006,
    'nis' => 14697,
    'tanggal' => '2026-07-16',
    'jam' => '12:28:39',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  44 => 
  array (
    'id_presensi' => 4007,
    'nis' => 14701,
    'tanggal' => '2026-07-16',
    'jam' => '12:29:24',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  45 => 
  array (
    'id_presensi' => 4008,
    'nis' => 14676,
    'tanggal' => '2026-07-16',
    'jam' => '12:29:42',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  46 => 
  array (
    'id_presensi' => 4009,
    'nis' => 14718,
    'tanggal' => '2026-07-16',
    'jam' => '12:30:24',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  47 => 
  array (
    'id_presensi' => 4010,
    'nis' => 14445,
    'tanggal' => '2026-07-16',
    'jam' => '12:31:01',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  48 => 
  array (
    'id_presensi' => 4011,
    'nis' => 14441,
    'tanggal' => '2026-07-16',
    'jam' => '12:31:16',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  49 => 
  array (
    'id_presensi' => 4012,
    'nis' => 14685,
    'tanggal' => '2026-07-16',
    'jam' => '12:31:18',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  50 => 
  array (
    'id_presensi' => 4013,
    'nis' => 14700,
    'tanggal' => '2026-07-16',
    'jam' => '12:31:22',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  51 => 
  array (
    'id_presensi' => 4014,
    'nis' => 14684,
    'tanggal' => '2026-07-16',
    'jam' => '12:31:35',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  52 => 
  array (
    'id_presensi' => 4015,
    'nis' => 14696,
    'tanggal' => '2026-07-16',
    'jam' => '12:33:01',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  53 => 
  array (
    'id_presensi' => 4016,
    'nis' => 14675,
    'tanggal' => '2026-07-16',
    'jam' => '12:35:41',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  54 => 
  array (
    'id_presensi' => 4017,
    'nis' => 14695,
    'tanggal' => '2026-07-16',
    'jam' => '12:36:04',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  55 => 
  array (
    'id_presensi' => 4018,
    'nis' => 14727,
    'tanggal' => '2026-07-16',
    'jam' => '12:36:56',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  56 => 
  array (
    'id_presensi' => 4019,
    'nis' => 14429,
    'tanggal' => '2026-07-16',
    'jam' => '12:37:15',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  57 => 
  array (
    'id_presensi' => 4020,
    'nis' => 14734,
    'tanggal' => '2026-07-16',
    'jam' => '12:37:16',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  58 => 
  array (
    'id_presensi' => 4021,
    'nis' => 14594,
    'tanggal' => '2026-07-16',
    'jam' => '12:53:27',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  59 => 
  array (
    'id_presensi' => 4022,
    'nis' => 14640,
    'tanggal' => '2026-07-16',
    'jam' => '12:58:32',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  60 => 
  array (
    'id_presensi' => 4023,
    'nis' => 14738,
    'tanggal' => '2026-07-16',
    'jam' => '14:59:40',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  61 => 
  array (
    'id_presensi' => 4024,
    'nis' => 14435,
    'tanggal' => '2026-07-16',
    'jam' => '15:09:47',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  62 => 
  array (
    'id_presensi' => 4025,
    'nis' => 14320,
    'tanggal' => '2026-07-16',
    'jam' => '15:21:58',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  63 => 
  array (
    'id_presensi' => 4026,
    'nis' => 14322,
    'tanggal' => '2026-07-16',
    'jam' => '15:22:08',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  64 => 
  array (
    'id_presensi' => 4027,
    'nis' => 14325,
    'tanggal' => '2026-07-16',
    'jam' => '15:26:27',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  65 => 
  array (
    'id_presensi' => 4028,
    'nis' => 13893,
    'tanggal' => '2026-07-16',
    'jam' => '15:34:05',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  66 => 
  array (
    'id_presensi' => 4029,
    'nis' => 13921,
    'tanggal' => '2026-07-16',
    'jam' => '15:34:27',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  67 => 
  array (
    'id_presensi' => 4030,
    'nis' => 14709,
    'tanggal' => '2026-07-16',
    'jam' => '15:39:22',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  68 => 
  array (
    'id_presensi' => 4031,
    'nis' => 14439,
    'tanggal' => '2026-07-16',
    'jam' => '15:57:44',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  69 => 
  array (
    'id_presensi' => 4032,
    'nis' => 14442,
    'tanggal' => '2026-07-16',
    'jam' => '15:57:48',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  70 => 
  array (
    'id_presensi' => 4033,
    'nis' => 14447,
    'tanggal' => '2026-07-16',
    'jam' => '15:57:56',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  71 => 
  array (
    'id_presensi' => 4034,
    'nis' => 13890,
    'tanggal' => '2026-07-16',
    'jam' => '18:01:47',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  72 => 
  array (
    'id_presensi' => 4035,
    'nis' => 14025,
    'tanggal' => '2026-07-16',
    'jam' => '21:03:34',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  73 => 
  array (
    'id_presensi' => 4036,
    'nis' => 14773,
    'tanggal' => '2026-07-17',
    'jam' => '06:12:02',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  74 => 
  array (
    'id_presensi' => 4037,
    'nis' => 14213,
    'tanggal' => '2026-07-17',
    'jam' => '06:13:08',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  75 => 
  array (
    'id_presensi' => 4038,
    'nis' => 14774,
    'tanggal' => '2026-07-17',
    'jam' => '06:25:06',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  76 => 
  array (
    'id_presensi' => 4039,
    'nis' => 14742,
    'tanggal' => '2026-07-17',
    'jam' => '06:25:11',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  77 => 
  array (
    'id_presensi' => 4040,
    'nis' => 14379,
    'tanggal' => '2026-07-17',
    'jam' => '06:26:29',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  78 => 
  array (
    'id_presensi' => 4041,
    'nis' => 14378,
    'tanggal' => '2026-07-17',
    'jam' => '06:28:33',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  79 => 
  array (
    'id_presensi' => 4042,
    'nis' => 14382,
    'tanggal' => '2026-07-17',
    'jam' => '06:28:43',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  80 => 
  array (
    'id_presensi' => 4043,
    'nis' => 14656,
    'tanggal' => '2026-07-17',
    'jam' => '06:29:10',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  81 => 
  array (
    'id_presensi' => 4044,
    'nis' => 14769,
    'tanggal' => '2026-07-17',
    'jam' => '06:31:41',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  82 => 
  array (
    'id_presensi' => 4045,
    'nis' => 14518,
    'tanggal' => '2026-07-17',
    'jam' => '06:31:54',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  83 => 
  array (
    'id_presensi' => 4046,
    'nis' => 14170,
    'tanggal' => '2026-07-17',
    'jam' => '06:34:04',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  84 => 
  array (
    'id_presensi' => 4047,
    'nis' => 14630,
    'tanggal' => '2026-07-17',
    'jam' => '06:34:20',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  85 => 
  array (
    'id_presensi' => 4048,
    'nis' => 14763,
    'tanggal' => '2026-07-17',
    'jam' => '06:35:21',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  86 => 
  array (
    'id_presensi' => 4049,
    'nis' => 14759,
    'tanggal' => '2026-07-17',
    'jam' => '06:37:50',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  87 => 
  array (
    'id_presensi' => 4050,
    'nis' => 14573,
    'tanggal' => '2026-07-17',
    'jam' => '06:38:37',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  88 => 
  array (
    'id_presensi' => 4051,
    'nis' => 13891,
    'tanggal' => '2026-07-17',
    'jam' => '06:38:40',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  89 => 
  array (
    'id_presensi' => 4052,
    'nis' => 14227,
    'tanggal' => '2026-07-17',
    'jam' => '06:38:51',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  90 => 
  array (
    'id_presensi' => 4053,
    'nis' => 14757,
    'tanggal' => '2026-07-17',
    'jam' => '06:38:53',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  91 => 
  array (
    'id_presensi' => 4054,
    'nis' => 14749,
    'tanggal' => '2026-07-17',
    'jam' => '06:38:56',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  92 => 
  array (
    'id_presensi' => 4055,
    'nis' => 14631,
    'tanggal' => '2026-07-17',
    'jam' => '06:39:01',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  93 => 
  array (
    'id_presensi' => 4056,
    'nis' => 14744,
    'tanggal' => '2026-07-17',
    'jam' => '06:39:21',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  94 => 
  array (
    'id_presensi' => 4057,
    'nis' => 14567,
    'tanggal' => '2026-07-17',
    'jam' => '06:39:35',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  95 => 
  array (
    'id_presensi' => 4058,
    'nis' => 14340,
    'tanggal' => '2026-07-17',
    'jam' => '06:40:38',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  96 => 
  array (
    'id_presensi' => 4059,
    'nis' => 14575,
    'tanggal' => '2026-07-17',
    'jam' => '06:41:27',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  97 => 
  array (
    'id_presensi' => 4060,
    'nis' => 14555,
    'tanggal' => '2026-07-17',
    'jam' => '06:41:33',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  98 => 
  array (
    'id_presensi' => 4061,
    'nis' => 13868,
    'tanggal' => '2026-07-17',
    'jam' => '06:41:35',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  99 => 
  array (
    'id_presensi' => 4062,
    'nis' => 14673,
    'tanggal' => '2026-07-17',
    'jam' => '06:44:02',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  100 => 
  array (
    'id_presensi' => 4063,
    'nis' => 14380,
    'tanggal' => '2026-07-17',
    'jam' => '06:44:18',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  101 => 
  array (
    'id_presensi' => 4064,
    'nis' => 14321,
    'tanggal' => '2026-07-17',
    'jam' => '06:44:20',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  102 => 
  array (
    'id_presensi' => 4065,
    'nis' => 14547,
    'tanggal' => '2026-07-17',
    'jam' => '06:44:24',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  103 => 
  array (
    'id_presensi' => 4066,
    'nis' => 14548,
    'tanggal' => '2026-07-17',
    'jam' => '06:44:26',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  104 => 
  array (
    'id_presensi' => 4067,
    'nis' => 14381,
    'tanggal' => '2026-07-17',
    'jam' => '06:44:28',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  105 => 
  array (
    'id_presensi' => 4068,
    'nis' => 14621,
    'tanggal' => '2026-07-17',
    'jam' => '06:45:31',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  106 => 
  array (
    'id_presensi' => 4069,
    'nis' => 14625,
    'tanggal' => '2026-07-17',
    'jam' => '06:45:35',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  107 => 
  array (
    'id_presensi' => 4070,
    'nis' => 14317,
    'tanggal' => '2026-07-17',
    'jam' => '06:45:47',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  108 => 
  array (
    'id_presensi' => 4071,
    'nis' => 14741,
    'tanggal' => '2026-07-17',
    'jam' => '06:45:55',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  109 => 
  array (
    'id_presensi' => 4072,
    'nis' => 14612,
    'tanggal' => '2026-07-17',
    'jam' => '06:46:05',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  110 => 
  array (
    'id_presensi' => 4073,
    'nis' => 14571,
    'tanggal' => '2026-07-17',
    'jam' => '06:46:12',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  111 => 
  array (
    'id_presensi' => 4074,
    'nis' => 14339,
    'tanggal' => '2026-07-17',
    'jam' => '06:46:30',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  112 => 
  array (
    'id_presensi' => 4075,
    'nis' => 14633,
    'tanggal' => '2026-07-17',
    'jam' => '06:46:35',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  113 => 
  array (
    'id_presensi' => 4076,
    'nis' => 14608,
    'tanggal' => '2026-07-17',
    'jam' => '06:46:47',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  114 => 
  array (
    'id_presensi' => 4077,
    'nis' => 14622,
    'tanggal' => '2026-07-17',
    'jam' => '06:46:49',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  115 => 
  array (
    'id_presensi' => 4078,
    'nis' => 14634,
    'tanggal' => '2026-07-17',
    'jam' => '06:46:57',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  116 => 
  array (
    'id_presensi' => 4079,
    'nis' => 14641,
    'tanggal' => '2026-07-17',
    'jam' => '06:46:59',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  117 => 
  array (
    'id_presensi' => 4080,
    'nis' => 14375,
    'tanggal' => '2026-07-17',
    'jam' => '06:47:04',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  118 => 
  array (
    'id_presensi' => 4081,
    'nis' => 14516,
    'tanggal' => '2026-07-17',
    'jam' => '06:47:19',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  119 => 
  array (
    'id_presensi' => 4082,
    'nis' => 14770,
    'tanggal' => '2026-07-17',
    'jam' => '06:47:29',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  120 => 
  array (
    'id_presensi' => 4083,
    'nis' => 14762,
    'tanggal' => '2026-07-17',
    'jam' => '06:47:32',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  121 => 
  array (
    'id_presensi' => 4084,
    'nis' => 13877,
    'tanggal' => '2026-07-17',
    'jam' => '06:48:06',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  122 => 
  array (
    'id_presensi' => 4085,
    'nis' => 14546,
    'tanggal' => '2026-07-17',
    'jam' => '06:48:36',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  123 => 
  array (
    'id_presensi' => 4086,
    'nis' => 14771,
    'tanggal' => '2026-07-17',
    'jam' => '06:48:40',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  124 => 
  array (
    'id_presensi' => 4087,
    'nis' => 14745,
    'tanggal' => '2026-07-17',
    'jam' => '06:48:46',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  125 => 
  array (
    'id_presensi' => 4088,
    'nis' => 13874,
    'tanggal' => '2026-07-17',
    'jam' => '06:48:53',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  126 => 
  array (
    'id_presensi' => 4089,
    'nis' => 14302,
    'tanggal' => '2026-07-17',
    'jam' => '06:49:02',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  127 => 
  array (
    'id_presensi' => 4090,
    'nis' => 14290,
    'tanggal' => '2026-07-17',
    'jam' => '06:49:05',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  128 => 
  array (
    'id_presensi' => 4091,
    'nis' => 14386,
    'tanggal' => '2026-07-17',
    'jam' => '06:49:12',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  129 => 
  array (
    'id_presensi' => 4092,
    'nis' => 14331,
    'tanggal' => '2026-07-17',
    'jam' => '06:49:16',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  130 => 
  array (
    'id_presensi' => 4093,
    'nis' => 14747,
    'tanggal' => '2026-07-17',
    'jam' => '06:50:05',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  131 => 
  array (
    'id_presensi' => 4094,
    'nis' => 14753,
    'tanggal' => '2026-07-17',
    'jam' => '06:50:16',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  132 => 
  array (
    'id_presensi' => 4095,
    'nis' => 14287,
    'tanggal' => '2026-07-17',
    'jam' => '06:50:26',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  133 => 
  array (
    'id_presensi' => 4096,
    'nis' => 14295,
    'tanggal' => '2026-07-17',
    'jam' => '06:50:33',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  134 => 
  array (
    'id_presensi' => 4097,
    'nis' => 14294,
    'tanggal' => '2026-07-17',
    'jam' => '06:50:37',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  135 => 
  array (
    'id_presensi' => 4098,
    'nis' => 14391,
    'tanggal' => '2026-07-17',
    'jam' => '06:50:43',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  136 => 
  array (
    'id_presensi' => 4099,
    'nis' => 14309,
    'tanggal' => '2026-07-17',
    'jam' => '06:50:44',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  137 => 
  array (
    'id_presensi' => 4100,
    'nis' => 14286,
    'tanggal' => '2026-07-17',
    'jam' => '06:50:46',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  138 => 
  array (
    'id_presensi' => 4101,
    'nis' => 14405,
    'tanggal' => '2026-07-17',
    'jam' => '06:50:46',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  139 => 
  array (
    'id_presensi' => 4102,
    'nis' => 14389,
    'tanggal' => '2026-07-17',
    'jam' => '06:50:48',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  140 => 
  array (
    'id_presensi' => 4103,
    'nis' => 14390,
    'tanggal' => '2026-07-17',
    'jam' => '06:50:49',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  141 => 
  array (
    'id_presensi' => 4104,
    'nis' => 14142,
    'tanggal' => '2026-07-17',
    'jam' => '06:50:51',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  142 => 
  array (
    'id_presensi' => 4105,
    'nis' => 13866,
    'tanggal' => '2026-07-17',
    'jam' => '06:50:53',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  143 => 
  array (
    'id_presensi' => 4106,
    'nis' => 14164,
    'tanggal' => '2026-07-17',
    'jam' => '06:50:58',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  144 => 
  array (
    'id_presensi' => 4107,
    'nis' => 14116,
    'tanggal' => '2026-07-17',
    'jam' => '06:51:42',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  145 => 
  array (
    'id_presensi' => 4108,
    'nis' => 14671,
    'tanggal' => '2026-07-17',
    'jam' => '06:52:00',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  146 => 
  array (
    'id_presensi' => 4109,
    'nis' => 14651,
    'tanggal' => '2026-07-17',
    'jam' => '06:52:02',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  147 => 
  array (
    'id_presensi' => 4110,
    'nis' => 14657,
    'tanggal' => '2026-07-17',
    'jam' => '06:52:03',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  148 => 
  array (
    'id_presensi' => 4111,
    'nis' => 14658,
    'tanggal' => '2026-07-17',
    'jam' => '06:52:05',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  149 => 
  array (
    'id_presensi' => 4112,
    'nis' => 13895,
    'tanggal' => '2026-07-17',
    'jam' => '06:52:10',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  150 => 
  array (
    'id_presensi' => 4113,
    'nis' => 14399,
    'tanggal' => '2026-07-17',
    'jam' => '06:52:11',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  151 => 
  array (
    'id_presensi' => 4114,
    'nis' => 14397,
    'tanggal' => '2026-07-17',
    'jam' => '06:52:13',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  152 => 
  array (
    'id_presensi' => 4115,
    'nis' => 14627,
    'tanggal' => '2026-07-17',
    'jam' => '06:52:38',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  153 => 
  array (
    'id_presensi' => 4116,
    'nis' => 13910,
    'tanggal' => '2026-07-17',
    'jam' => '06:52:39',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  154 => 
  array (
    'id_presensi' => 4117,
    'nis' => 14623,
    'tanggal' => '2026-07-17',
    'jam' => '06:53:08',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  155 => 
  array (
    'id_presensi' => 4118,
    'nis' => 14545,
    'tanggal' => '2026-07-17',
    'jam' => '06:53:11',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  156 => 
  array (
    'id_presensi' => 4119,
    'nis' => 14803,
    'tanggal' => '2026-07-17',
    'jam' => '06:53:28',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  157 => 
  array (
    'id_presensi' => 4120,
    'nis' => 14777,
    'tanggal' => '2026-07-17',
    'jam' => '06:53:35',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  158 => 
  array (
    'id_presensi' => 4121,
    'nis' => 14782,
    'tanggal' => '2026-07-17',
    'jam' => '06:53:35',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  159 => 
  array (
    'id_presensi' => 4122,
    'nis' => 13869,
    'tanggal' => '2026-07-17',
    'jam' => '06:53:36',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  160 => 
  array (
    'id_presensi' => 4123,
    'nis' => 14800,
    'tanggal' => '2026-07-17',
    'jam' => '06:53:38',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  161 => 
  array (
    'id_presensi' => 4124,
    'nis' => 14785,
    'tanggal' => '2026-07-17',
    'jam' => '06:53:41',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  162 => 
  array (
    'id_presensi' => 4125,
    'nis' => 14752,
    'tanggal' => '2026-07-17',
    'jam' => '06:53:41',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  163 => 
  array (
    'id_presensi' => 4126,
    'nis' => 14614,
    'tanggal' => '2026-07-17',
    'jam' => '06:53:44',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  164 => 
  array (
    'id_presensi' => 4127,
    'nis' => 14786,
    'tanggal' => '2026-07-17',
    'jam' => '06:53:44',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  165 => 
  array (
    'id_presensi' => 4128,
    'nis' => 14557,
    'tanggal' => '2026-07-17',
    'jam' => '06:53:47',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  166 => 
  array (
    'id_presensi' => 4129,
    'nis' => 14563,
    'tanggal' => '2026-07-17',
    'jam' => '06:53:47',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  167 => 
  array (
    'id_presensi' => 4130,
    'nis' => 14385,
    'tanggal' => '2026-07-17',
    'jam' => '06:53:49',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  168 => 
  array (
    'id_presensi' => 4131,
    'nis' => 14558,
    'tanggal' => '2026-07-17',
    'jam' => '06:53:49',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  169 => 
  array (
    'id_presensi' => 4132,
    'nis' => 14617,
    'tanggal' => '2026-07-17',
    'jam' => '06:53:52',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  170 => 
  array (
    'id_presensi' => 4133,
    'nis' => 14646,
    'tanggal' => '2026-07-17',
    'jam' => '06:53:52',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  171 => 
  array (
    'id_presensi' => 4134,
    'nis' => 14292,
    'tanggal' => '2026-07-17',
    'jam' => '06:53:54',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  172 => 
  array (
    'id_presensi' => 4135,
    'nis' => 14644,
    'tanggal' => '2026-07-17',
    'jam' => '06:53:55',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  173 => 
  array (
    'id_presensi' => 4136,
    'nis' => 14796,
    'tanggal' => '2026-07-17',
    'jam' => '06:53:56',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  174 => 
  array (
    'id_presensi' => 4137,
    'nis' => 14662,
    'tanggal' => '2026-07-17',
    'jam' => '06:53:57',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  175 => 
  array (
    'id_presensi' => 4138,
    'nis' => 14388,
    'tanggal' => '2026-07-17',
    'jam' => '06:54:00',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  176 => 
  array (
    'id_presensi' => 4139,
    'nis' => 14668,
    'tanggal' => '2026-07-17',
    'jam' => '06:54:02',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  177 => 
  array (
    'id_presensi' => 4140,
    'nis' => 14303,
    'tanggal' => '2026-07-17',
    'jam' => '06:54:03',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  178 => 
  array (
    'id_presensi' => 4141,
    'nis' => 14793,
    'tanggal' => '2026-07-17',
    'jam' => '06:54:04',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  179 => 
  array (
    'id_presensi' => 4142,
    'nis' => 14787,
    'tanggal' => '2026-07-17',
    'jam' => '06:54:05',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  180 => 
  array (
    'id_presensi' => 4143,
    'nis' => 14775,
    'tanggal' => '2026-07-17',
    'jam' => '06:54:07',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  181 => 
  array (
    'id_presensi' => 4144,
    'nis' => 14333,
    'tanggal' => '2026-07-17',
    'jam' => '06:54:09',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  182 => 
  array (
    'id_presensi' => 4145,
    'nis' => 14327,
    'tanggal' => '2026-07-17',
    'jam' => '06:54:12',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  183 => 
  array (
    'id_presensi' => 4146,
    'nis' => 14318,
    'tanggal' => '2026-07-17',
    'jam' => '06:54:15',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  184 => 
  array (
    'id_presensi' => 4147,
    'nis' => 14778,
    'tanggal' => '2026-07-17',
    'jam' => '06:54:16',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  185 => 
  array (
    'id_presensi' => 4148,
    'nis' => 14169,
    'tanggal' => '2026-07-17',
    'jam' => '06:54:17',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  186 => 
  array (
    'id_presensi' => 4149,
    'nis' => 14615,
    'tanggal' => '2026-07-17',
    'jam' => '06:54:23',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  187 => 
  array (
    'id_presensi' => 4150,
    'nis' => 14173,
    'tanggal' => '2026-07-17',
    'jam' => '06:54:28',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  188 => 
  array (
    'id_presensi' => 4151,
    'nis' => 13918,
    'tanggal' => '2026-07-17',
    'jam' => '06:54:30',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  189 => 
  array (
    'id_presensi' => 4152,
    'nis' => 14298,
    'tanggal' => '2026-07-17',
    'jam' => '06:54:32',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  190 => 
  array (
    'id_presensi' => 4153,
    'nis' => 14297,
    'tanggal' => '2026-07-17',
    'jam' => '06:54:35',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  191 => 
  array (
    'id_presensi' => 4154,
    'nis' => 14372,
    'tanggal' => '2026-07-17',
    'jam' => '06:54:37',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  192 => 
  array (
    'id_presensi' => 4155,
    'nis' => 14559,
    'tanggal' => '2026-07-17',
    'jam' => '06:54:42',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  193 => 
  array (
    'id_presensi' => 4156,
    'nis' => 14406,
    'tanggal' => '2026-07-17',
    'jam' => '06:54:51',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  194 => 
  array (
    'id_presensi' => 4157,
    'nis' => 14396,
    'tanggal' => '2026-07-17',
    'jam' => '06:54:56',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  195 => 
  array (
    'id_presensi' => 4158,
    'nis' => 14538,
    'tanggal' => '2026-07-17',
    'jam' => '06:54:56',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  196 => 
  array (
    'id_presensi' => 4159,
    'nis' => 14413,
    'tanggal' => '2026-07-17',
    'jam' => '06:54:58',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  197 => 
  array (
    'id_presensi' => 4160,
    'nis' => 14531,
    'tanggal' => '2026-07-17',
    'jam' => '06:54:58',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  198 => 
  array (
    'id_presensi' => 4161,
    'nis' => 14519,
    'tanggal' => '2026-07-17',
    'jam' => '06:54:59',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  199 => 
  array (
    'id_presensi' => 4162,
    'nis' => 14403,
    'tanggal' => '2026-07-17',
    'jam' => '06:55:01',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
));

        DB::table('presensi')->insert(array (
  0 => 
  array (
    'id_presensi' => 4163,
    'nis' => 14756,
    'tanggal' => '2026-07-17',
    'jam' => '06:55:03',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  1 => 
  array (
    'id_presensi' => 4164,
    'nis' => 14539,
    'tanggal' => '2026-07-17',
    'jam' => '06:55:05',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  2 => 
  array (
    'id_presensi' => 4165,
    'nis' => 14628,
    'tanggal' => '2026-07-17',
    'jam' => '06:55:07',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  3 => 
  array (
    'id_presensi' => 4166,
    'nis' => 14416,
    'tanggal' => '2026-07-17',
    'jam' => '06:55:08',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  4 => 
  array (
    'id_presensi' => 4167,
    'nis' => 14620,
    'tanggal' => '2026-07-17',
    'jam' => '06:55:09',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  5 => 
  array (
    'id_presensi' => 4168,
    'nis' => 14144,
    'tanggal' => '2026-07-17',
    'jam' => '06:55:25',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  6 => 
  array (
    'id_presensi' => 4169,
    'nis' => 14106,
    'tanggal' => '2026-07-17',
    'jam' => '06:55:30',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  7 => 
  array (
    'id_presensi' => 4170,
    'nis' => 14108,
    'tanggal' => '2026-07-17',
    'jam' => '06:55:33',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  8 => 
  array (
    'id_presensi' => 4171,
    'nis' => 14291,
    'tanggal' => '2026-07-17',
    'jam' => '06:55:35',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  9 => 
  array (
    'id_presensi' => 4172,
    'nis' => 14117,
    'tanggal' => '2026-07-17',
    'jam' => '06:55:36',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  10 => 
  array (
    'id_presensi' => 4173,
    'nis' => 14659,
    'tanggal' => '2026-07-17',
    'jam' => '06:55:38',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  11 => 
  array (
    'id_presensi' => 4174,
    'nis' => 14653,
    'tanggal' => '2026-07-17',
    'jam' => '06:55:40',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  12 => 
  array (
    'id_presensi' => 4175,
    'nis' => 14794,
    'tanggal' => '2026-07-17',
    'jam' => '06:55:40',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  13 => 
  array (
    'id_presensi' => 4176,
    'nis' => 14772,
    'tanggal' => '2026-07-17',
    'jam' => '06:55:48',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  14 => 
  array (
    'id_presensi' => 4177,
    'nis' => 13875,
    'tanggal' => '2026-07-17',
    'jam' => '06:55:48',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  15 => 
  array (
    'id_presensi' => 4178,
    'nis' => 14647,
    'tanggal' => '2026-07-17',
    'jam' => '06:55:50',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  16 => 
  array (
    'id_presensi' => 4179,
    'nis' => 13862,
    'tanggal' => '2026-07-17',
    'jam' => '06:55:51',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  17 => 
  array (
    'id_presensi' => 4180,
    'nis' => 14751,
    'tanggal' => '2026-07-17',
    'jam' => '06:55:51',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  18 => 
  array (
    'id_presensi' => 4181,
    'nis' => 13888,
    'tanggal' => '2026-07-17',
    'jam' => '06:55:53',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  19 => 
  array (
    'id_presensi' => 4182,
    'nis' => 14797,
    'tanggal' => '2026-07-17',
    'jam' => '06:55:57',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  20 => 
  array (
    'id_presensi' => 4183,
    'nis' => 14412,
    'tanggal' => '2026-07-17',
    'jam' => '06:56:05',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  21 => 
  array (
    'id_presensi' => 4184,
    'nis' => 14767,
    'tanggal' => '2026-07-17',
    'jam' => '06:56:15',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  22 => 
  array (
    'id_presensi' => 4185,
    'nis' => 14335,
    'tanggal' => '2026-07-17',
    'jam' => '06:56:18',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  23 => 
  array (
    'id_presensi' => 4186,
    'nis' => 14288,
    'tanggal' => '2026-07-17',
    'jam' => '06:56:22',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  24 => 
  array (
    'id_presensi' => 4187,
    'nis' => 14324,
    'tanggal' => '2026-07-17',
    'jam' => '06:56:25',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  25 => 
  array (
    'id_presensi' => 4188,
    'nis' => 14313,
    'tanggal' => '2026-07-17',
    'jam' => '06:56:25',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  26 => 
  array (
    'id_presensi' => 4189,
    'nis' => 14320,
    'tanggal' => '2026-07-17',
    'jam' => '06:56:27',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  27 => 
  array (
    'id_presensi' => 4190,
    'nis' => 14332,
    'tanggal' => '2026-07-17',
    'jam' => '06:56:32',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  28 => 
  array (
    'id_presensi' => 4191,
    'nis' => 14376,
    'tanggal' => '2026-07-17',
    'jam' => '06:56:34',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  29 => 
  array (
    'id_presensi' => 4192,
    'nis' => 14780,
    'tanggal' => '2026-07-17',
    'jam' => '06:56:34',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  30 => 
  array (
    'id_presensi' => 4193,
    'nis' => 14306,
    'tanggal' => '2026-07-17',
    'jam' => '06:56:37',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  31 => 
  array (
    'id_presensi' => 4194,
    'nis' => 14400,
    'tanggal' => '2026-07-17',
    'jam' => '06:56:39',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  32 => 
  array (
    'id_presensi' => 4195,
    'nis' => 14415,
    'tanggal' => '2026-07-17',
    'jam' => '06:56:42',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  33 => 
  array (
    'id_presensi' => 4196,
    'nis' => 14801,
    'tanggal' => '2026-07-17',
    'jam' => '06:56:42',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  34 => 
  array (
    'id_presensi' => 4197,
    'nis' => 14804,
    'tanggal' => '2026-07-17',
    'jam' => '06:56:44',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  35 => 
  array (
    'id_presensi' => 4198,
    'nis' => 14561,
    'tanggal' => '2026-07-17',
    'jam' => '06:56:47',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  36 => 
  array (
    'id_presensi' => 4199,
    'nis' => 14392,
    'tanggal' => '2026-07-17',
    'jam' => '06:56:53',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  37 => 
  array (
    'id_presensi' => 4200,
    'nis' => 13908,
    'tanggal' => '2026-07-17',
    'jam' => '06:56:55',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  38 => 
  array (
    'id_presensi' => 4201,
    'nis' => 14160,
    'tanggal' => '2026-07-17',
    'jam' => '06:56:57',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  39 => 
  array (
    'id_presensi' => 4202,
    'nis' => 14242,
    'tanggal' => '2026-07-17',
    'jam' => '06:57:06',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  40 => 
  array (
    'id_presensi' => 4203,
    'nis' => 14115,
    'tanggal' => '2026-07-17',
    'jam' => '06:57:06',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  41 => 
  array (
    'id_presensi' => 4204,
    'nis' => 14648,
    'tanggal' => '2026-07-17',
    'jam' => '06:57:07',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  42 => 
  array (
    'id_presensi' => 4205,
    'nis' => 14223,
    'tanggal' => '2026-07-17',
    'jam' => '06:57:08',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  43 => 
  array (
    'id_presensi' => 4206,
    'nis' => 14132,
    'tanggal' => '2026-07-17',
    'jam' => '06:57:13',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  44 => 
  array (
    'id_presensi' => 4207,
    'nis' => 14124,
    'tanggal' => '2026-07-17',
    'jam' => '06:57:14',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  45 => 
  array (
    'id_presensi' => 4208,
    'nis' => 14236,
    'tanggal' => '2026-07-17',
    'jam' => '06:57:15',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  46 => 
  array (
    'id_presensi' => 4209,
    'nis' => 14544,
    'tanggal' => '2026-07-17',
    'jam' => '06:57:16',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  47 => 
  array (
    'id_presensi' => 4210,
    'nis' => 14123,
    'tanggal' => '2026-07-17',
    'jam' => '06:57:28',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  48 => 
  array (
    'id_presensi' => 4211,
    'nis' => 13904,
    'tanggal' => '2026-07-17',
    'jam' => '06:57:29',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  49 => 
  array (
    'id_presensi' => 4212,
    'nis' => 14301,
    'tanggal' => '2026-07-17',
    'jam' => '06:57:32',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  50 => 
  array (
    'id_presensi' => 4213,
    'nis' => 14293,
    'tanggal' => '2026-07-17',
    'jam' => '06:57:32',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  51 => 
  array (
    'id_presensi' => 4214,
    'nis' => 14308,
    'tanggal' => '2026-07-17',
    'jam' => '06:57:37',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  52 => 
  array (
    'id_presensi' => 4215,
    'nis' => 14748,
    'tanggal' => '2026-07-17',
    'jam' => '06:57:41',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  53 => 
  array (
    'id_presensi' => 4216,
    'nis' => 14248,
    'tanggal' => '2026-07-17',
    'jam' => '06:57:42',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  54 => 
  array (
    'id_presensi' => 4217,
    'nis' => 14802,
    'tanggal' => '2026-07-17',
    'jam' => '06:57:44',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  55 => 
  array (
    'id_presensi' => 4218,
    'nis' => 14666,
    'tanggal' => '2026-07-17',
    'jam' => '06:57:44',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  56 => 
  array (
    'id_presensi' => 4219,
    'nis' => 13929,
    'tanggal' => '2026-07-17',
    'jam' => '06:57:46',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  57 => 
  array (
    'id_presensi' => 4220,
    'nis' => 14755,
    'tanggal' => '2026-07-17',
    'jam' => '06:57:47',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  58 => 
  array (
    'id_presensi' => 4221,
    'nis' => 14217,
    'tanggal' => '2026-07-17',
    'jam' => '06:57:49',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  59 => 
  array (
    'id_presensi' => 4222,
    'nis' => 14234,
    'tanggal' => '2026-07-17',
    'jam' => '06:57:52',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  60 => 
  array (
    'id_presensi' => 4223,
    'nis' => 14552,
    'tanggal' => '2026-07-17',
    'jam' => '06:57:52',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  61 => 
  array (
    'id_presensi' => 4224,
    'nis' => 14577,
    'tanggal' => '2026-07-17',
    'jam' => '06:57:57',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  62 => 
  array (
    'id_presensi' => 4225,
    'nis' => 14235,
    'tanggal' => '2026-07-17',
    'jam' => '06:57:59',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  63 => 
  array (
    'id_presensi' => 4226,
    'nis' => 13922,
    'tanggal' => '2026-07-17',
    'jam' => '06:58:04',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  64 => 
  array (
    'id_presensi' => 4227,
    'nis' => 14611,
    'tanggal' => '2026-07-17',
    'jam' => '06:58:05',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  65 => 
  array (
    'id_presensi' => 4228,
    'nis' => 14639,
    'tanggal' => '2026-07-17',
    'jam' => '06:58:07',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  66 => 
  array (
    'id_presensi' => 4229,
    'nis' => 13915,
    'tanggal' => '2026-07-17',
    'jam' => '06:58:30',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  67 => 
  array (
    'id_presensi' => 4230,
    'nis' => 14799,
    'tanggal' => '2026-07-17',
    'jam' => '06:58:33',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  68 => 
  array (
    'id_presensi' => 4231,
    'nis' => 14791,
    'tanggal' => '2026-07-17',
    'jam' => '06:58:36',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  69 => 
  array (
    'id_presensi' => 4232,
    'nis' => 14520,
    'tanggal' => '2026-07-17',
    'jam' => '06:58:40',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  70 => 
  array (
    'id_presensi' => 4233,
    'nis' => 14535,
    'tanggal' => '2026-07-17',
    'jam' => '06:58:42',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  71 => 
  array (
    'id_presensi' => 4234,
    'nis' => 14754,
    'tanggal' => '2026-07-17',
    'jam' => '06:58:44',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  72 => 
  array (
    'id_presensi' => 4235,
    'nis' => 14537,
    'tanggal' => '2026-07-17',
    'jam' => '06:58:45',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  73 => 
  array (
    'id_presensi' => 4236,
    'nis' => 14760,
    'tanggal' => '2026-07-17',
    'jam' => '06:58:47',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  74 => 
  array (
    'id_presensi' => 4237,
    'nis' => 14119,
    'tanggal' => '2026-07-17',
    'jam' => '06:58:49',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  75 => 
  array (
    'id_presensi' => 4238,
    'nis' => 14229,
    'tanggal' => '2026-07-17',
    'jam' => '06:58:58',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  76 => 
  array (
    'id_presensi' => 4239,
    'nis' => 14417,
    'tanggal' => '2026-07-17',
    'jam' => '06:58:59',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  77 => 
  array (
    'id_presensi' => 4240,
    'nis' => 14134,
    'tanggal' => '2026-07-17',
    'jam' => '06:59:00',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  78 => 
  array (
    'id_presensi' => 4241,
    'nis' => 14788,
    'tanggal' => '2026-07-17',
    'jam' => '06:59:04',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  79 => 
  array (
    'id_presensi' => 4242,
    'nis' => 14322,
    'tanggal' => '2026-07-17',
    'jam' => '06:59:11',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  80 => 
  array (
    'id_presensi' => 4243,
    'nis' => 14296,
    'tanggal' => '2026-07-17',
    'jam' => '06:59:16',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  81 => 
  array (
    'id_presensi' => 4244,
    'nis' => 14338,
    'tanggal' => '2026-07-17',
    'jam' => '06:59:17',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  82 => 
  array (
    'id_presensi' => 4245,
    'nis' => 14312,
    'tanggal' => '2026-07-17',
    'jam' => '06:59:18',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  83 => 
  array (
    'id_presensi' => 4246,
    'nis' => 14305,
    'tanggal' => '2026-07-17',
    'jam' => '06:59:22',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  84 => 
  array (
    'id_presensi' => 4247,
    'nis' => 14764,
    'tanggal' => '2026-07-17',
    'jam' => '06:59:25',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  85 => 
  array (
    'id_presensi' => 4248,
    'nis' => 14336,
    'tanggal' => '2026-07-17',
    'jam' => '06:59:25',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  86 => 
  array (
    'id_presensi' => 4249,
    'nis' => 14328,
    'tanggal' => '2026-07-17',
    'jam' => '06:59:27',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  87 => 
  array (
    'id_presensi' => 4250,
    'nis' => 14121,
    'tanggal' => '2026-07-17',
    'jam' => '06:59:34',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  88 => 
  array (
    'id_presensi' => 4251,
    'nis' => 14776,
    'tanggal' => '2026-07-17',
    'jam' => '06:59:37',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  89 => 
  array (
    'id_presensi' => 4252,
    'nis' => 14319,
    'tanggal' => '2026-07-17',
    'jam' => '06:59:37',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  90 => 
  array (
    'id_presensi' => 4253,
    'nis' => 14790,
    'tanggal' => '2026-07-17',
    'jam' => '06:59:40',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  91 => 
  array (
    'id_presensi' => 4254,
    'nis' => 14649,
    'tanggal' => '2026-07-17',
    'jam' => '06:59:42',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  92 => 
  array (
    'id_presensi' => 4255,
    'nis' => 14323,
    'tanggal' => '2026-07-17',
    'jam' => '06:59:43',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  93 => 
  array (
    'id_presensi' => 4256,
    'nis' => 14540,
    'tanggal' => '2026-07-17',
    'jam' => '06:59:49',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  94 => 
  array (
    'id_presensi' => 4257,
    'nis' => 14168,
    'tanggal' => '2026-07-17',
    'jam' => '07:00:02',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  95 => 
  array (
    'id_presensi' => 4258,
    'nis' => 14161,
    'tanggal' => '2026-07-17',
    'jam' => '07:00:04',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  96 => 
  array (
    'id_presensi' => 4259,
    'nis' => 14171,
    'tanggal' => '2026-07-17',
    'jam' => '07:00:05',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  97 => 
  array (
    'id_presensi' => 4260,
    'nis' => 14158,
    'tanggal' => '2026-07-17',
    'jam' => '07:00:07',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  98 => 
  array (
    'id_presensi' => 4261,
    'nis' => 13907,
    'tanggal' => '2026-07-17',
    'jam' => '07:00:08',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  99 => 
  array (
    'id_presensi' => 4262,
    'nis' => 14758,
    'tanggal' => '2026-07-17',
    'jam' => '07:00:10',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  100 => 
  array (
    'id_presensi' => 4263,
    'nis' => 14146,
    'tanggal' => '2026-07-17',
    'jam' => '07:00:11',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  101 => 
  array (
    'id_presensi' => 4264,
    'nis' => 14167,
    'tanggal' => '2026-07-17',
    'jam' => '07:00:13',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  102 => 
  array (
    'id_presensi' => 4265,
    'nis' => 13911,
    'tanggal' => '2026-07-17',
    'jam' => '07:00:13',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  103 => 
  array (
    'id_presensi' => 4266,
    'nis' => 14663,
    'tanggal' => '2026-07-17',
    'jam' => '07:00:14',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  104 => 
  array (
    'id_presensi' => 4267,
    'nis' => 14154,
    'tanggal' => '2026-07-17',
    'jam' => '07:00:16',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  105 => 
  array (
    'id_presensi' => 4268,
    'nis' => 14789,
    'tanggal' => '2026-07-17',
    'jam' => '07:00:16',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  106 => 
  array (
    'id_presensi' => 4269,
    'nis' => 13898,
    'tanggal' => '2026-07-17',
    'jam' => '07:00:19',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  107 => 
  array (
    'id_presensi' => 4270,
    'nis' => 13867,
    'tanggal' => '2026-07-17',
    'jam' => '07:00:24',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  108 => 
  array (
    'id_presensi' => 4271,
    'nis' => 14311,
    'tanggal' => '2026-07-17',
    'jam' => '07:00:27',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  109 => 
  array (
    'id_presensi' => 4272,
    'nis' => 14624,
    'tanggal' => '2026-07-17',
    'jam' => '07:00:30',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  110 => 
  array (
    'id_presensi' => 4273,
    'nis' => 13901,
    'tanggal' => '2026-07-17',
    'jam' => '07:00:30',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  111 => 
  array (
    'id_presensi' => 4274,
    'nis' => 13925,
    'tanggal' => '2026-07-17',
    'jam' => '07:00:32',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  112 => 
  array (
    'id_presensi' => 4275,
    'nis' => 13927,
    'tanggal' => '2026-07-17',
    'jam' => '07:00:34',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  113 => 
  array (
    'id_presensi' => 4276,
    'nis' => 13903,
    'tanggal' => '2026-07-17',
    'jam' => '07:00:37',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  114 => 
  array (
    'id_presensi' => 4277,
    'nis' => 14652,
    'tanggal' => '2026-07-17',
    'jam' => '07:00:39',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  115 => 
  array (
    'id_presensi' => 4278,
    'nis' => 13906,
    'tanggal' => '2026-07-17',
    'jam' => '07:00:40',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  116 => 
  array (
    'id_presensi' => 4279,
    'nis' => 13882,
    'tanggal' => '2026-07-17',
    'jam' => '07:00:42',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  117 => 
  array (
    'id_presensi' => 4280,
    'nis' => 13912,
    'tanggal' => '2026-07-17',
    'jam' => '07:00:43',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  118 => 
  array (
    'id_presensi' => 4281,
    'nis' => 14326,
    'tanggal' => '2026-07-17',
    'jam' => '07:00:45',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  119 => 
  array (
    'id_presensi' => 4282,
    'nis' => 13923,
    'tanggal' => '2026-07-17',
    'jam' => '07:00:46',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  120 => 
  array (
    'id_presensi' => 4283,
    'nis' => 14314,
    'tanggal' => '2026-07-17',
    'jam' => '07:00:48',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  121 => 
  array (
    'id_presensi' => 4284,
    'nis' => 13889,
    'tanggal' => '2026-07-17',
    'jam' => '07:00:50',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  122 => 
  array (
    'id_presensi' => 4285,
    'nis' => 14761,
    'tanggal' => '2026-07-17',
    'jam' => '07:00:51',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  123 => 
  array (
    'id_presensi' => 4286,
    'nis' => 13899,
    'tanggal' => '2026-07-17',
    'jam' => '07:00:54',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  124 => 
  array (
    'id_presensi' => 4287,
    'nis' => 14162,
    'tanggal' => '2026-07-17',
    'jam' => '07:00:58',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  125 => 
  array (
    'id_presensi' => 4288,
    'nis' => 13909,
    'tanggal' => '2026-07-17',
    'jam' => '07:01:02',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  126 => 
  array (
    'id_presensi' => 4289,
    'nis' => 14768,
    'tanggal' => '2026-07-17',
    'jam' => '07:01:02',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  127 => 
  array (
    'id_presensi' => 4290,
    'nis' => 14795,
    'tanggal' => '2026-07-17',
    'jam' => '07:01:09',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  128 => 
  array (
    'id_presensi' => 4291,
    'nis' => 14626,
    'tanggal' => '2026-07-17',
    'jam' => '07:01:12',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  129 => 
  array (
    'id_presensi' => 4292,
    'nis' => 14111,
    'tanggal' => '2026-07-17',
    'jam' => '07:01:18',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  130 => 
  array (
    'id_presensi' => 4293,
    'nis' => 13879,
    'tanggal' => '2026-07-17',
    'jam' => '07:01:19',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  131 => 
  array (
    'id_presensi' => 4294,
    'nis' => 14118,
    'tanggal' => '2026-07-17',
    'jam' => '07:01:21',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  132 => 
  array (
    'id_presensi' => 4295,
    'nis' => 14329,
    'tanggal' => '2026-07-17',
    'jam' => '07:01:25',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  133 => 
  array (
    'id_presensi' => 4296,
    'nis' => 13872,
    'tanggal' => '2026-07-17',
    'jam' => '07:01:26',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  134 => 
  array (
    'id_presensi' => 4297,
    'nis' => 14527,
    'tanggal' => '2026-07-17',
    'jam' => '07:01:31',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  135 => 
  array (
    'id_presensi' => 4298,
    'nis' => 14783,
    'tanggal' => '2026-07-17',
    'jam' => '07:01:31',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  136 => 
  array (
    'id_presensi' => 4299,
    'nis' => 14130,
    'tanggal' => '2026-07-17',
    'jam' => '07:01:33',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  137 => 
  array (
    'id_presensi' => 4300,
    'nis' => 13873,
    'tanggal' => '2026-07-17',
    'jam' => '07:01:34',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  138 => 
  array (
    'id_presensi' => 4301,
    'nis' => 14136,
    'tanggal' => '2026-07-17',
    'jam' => '07:01:35',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  139 => 
  array (
    'id_presensi' => 4302,
    'nis' => 14133,
    'tanggal' => '2026-07-17',
    'jam' => '07:01:36',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  140 => 
  array (
    'id_presensi' => 4303,
    'nis' => 13871,
    'tanggal' => '2026-07-17',
    'jam' => '07:01:38',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  141 => 
  array (
    'id_presensi' => 4304,
    'nis' => 14122,
    'tanggal' => '2026-07-17',
    'jam' => '07:01:39',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  142 => 
  array (
    'id_presensi' => 4305,
    'nis' => 14148,
    'tanggal' => '2026-07-17',
    'jam' => '07:01:42',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  143 => 
  array (
    'id_presensi' => 4306,
    'nis' => 14635,
    'tanggal' => '2026-07-17',
    'jam' => '07:01:46',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  144 => 
  array (
    'id_presensi' => 4307,
    'nis' => 14636,
    'tanggal' => '2026-07-17',
    'jam' => '07:01:46',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  145 => 
  array (
    'id_presensi' => 4308,
    'nis' => 14127,
    'tanggal' => '2026-07-17',
    'jam' => '07:01:50',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  146 => 
  array (
    'id_presensi' => 4309,
    'nis' => 14156,
    'tanggal' => '2026-07-17',
    'jam' => '07:01:52',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  147 => 
  array (
    'id_presensi' => 4310,
    'nis' => 14299,
    'tanggal' => '2026-07-17',
    'jam' => '07:01:52',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  148 => 
  array (
    'id_presensi' => 4311,
    'nis' => 14174,
    'tanggal' => '2026-07-17',
    'jam' => '07:01:58',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  149 => 
  array (
    'id_presensi' => 4312,
    'nis' => 14157,
    'tanggal' => '2026-07-17',
    'jam' => '07:01:58',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  150 => 
  array (
    'id_presensi' => 4313,
    'nis' => 13878,
    'tanggal' => '2026-07-17',
    'jam' => '07:01:59',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  151 => 
  array (
    'id_presensi' => 4314,
    'nis' => 14369,
    'tanggal' => '2026-07-17',
    'jam' => '07:02:03',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  152 => 
  array (
    'id_presensi' => 4315,
    'nis' => 14664,
    'tanggal' => '2026-07-17',
    'jam' => '07:02:07',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  153 => 
  array (
    'id_presensi' => 4316,
    'nis' => 13916,
    'tanggal' => '2026-07-17',
    'jam' => '07:02:08',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  154 => 
  array (
    'id_presensi' => 4317,
    'nis' => 14643,
    'tanggal' => '2026-07-17',
    'jam' => '07:02:11',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  155 => 
  array (
    'id_presensi' => 4318,
    'nis' => 14566,
    'tanggal' => '2026-07-17',
    'jam' => '07:02:16',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  156 => 
  array (
    'id_presensi' => 4319,
    'nis' => 14138,
    'tanggal' => '2026-07-17',
    'jam' => '07:02:20',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  157 => 
  array (
    'id_presensi' => 4320,
    'nis' => 13870,
    'tanggal' => '2026-07-17',
    'jam' => '07:04:41',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  158 => 
  array (
    'id_presensi' => 4321,
    'nis' => 14231,
    'tanggal' => '2026-07-17',
    'jam' => '07:10:36',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  159 => 
  array (
    'id_presensi' => 4322,
    'nis' => 14237,
    'tanggal' => '2026-07-17',
    'jam' => '07:10:45',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  160 => 
  array (
    'id_presensi' => 4323,
    'nis' => 14224,
    'tanggal' => '2026-07-17',
    'jam' => '07:10:50',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  161 => 
  array (
    'id_presensi' => 4324,
    'nis' => 14225,
    'tanggal' => '2026-07-17',
    'jam' => '07:11:48',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  162 => 
  array (
    'id_presensi' => 4325,
    'nis' => 14220,
    'tanggal' => '2026-07-17',
    'jam' => '07:11:56',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  163 => 
  array (
    'id_presensi' => 4326,
    'nis' => 14244,
    'tanggal' => '2026-07-17',
    'jam' => '07:12:02',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  164 => 
  array (
    'id_presensi' => 4327,
    'nis' => 14222,
    'tanggal' => '2026-07-17',
    'jam' => '07:12:05',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  165 => 
  array (
    'id_presensi' => 4328,
    'nis' => 14214,
    'tanggal' => '2026-07-17',
    'jam' => '07:12:29',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  166 => 
  array (
    'id_presensi' => 4329,
    'nis' => 14233,
    'tanggal' => '2026-07-17',
    'jam' => '07:12:40',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  167 => 
  array (
    'id_presensi' => 4330,
    'nis' => 14232,
    'tanggal' => '2026-07-17',
    'jam' => '07:12:45',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  168 => 
  array (
    'id_presensi' => 4331,
    'nis' => 14230,
    'tanggal' => '2026-07-17',
    'jam' => '07:13:13',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  169 => 
  array (
    'id_presensi' => 4332,
    'nis' => 14261,
    'tanggal' => '2026-07-17',
    'jam' => '07:13:15',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  170 => 
  array (
    'id_presensi' => 4333,
    'nis' => 13894,
    'tanggal' => '2026-07-17',
    'jam' => '07:21:28',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  171 => 
  array (
    'id_presensi' => 4334,
    'nis' => 13921,
    'tanggal' => '2026-07-17',
    'jam' => '07:26:48',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  172 => 
  array (
    'id_presensi' => 4335,
    'nis' => 13893,
    'tanggal' => '2026-07-17',
    'jam' => '07:26:55',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  173 => 
  array (
    'id_presensi' => 4336,
    'nis' => 13885,
    'tanggal' => '2026-07-17',
    'jam' => '09:50:50',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  174 => 
  array (
    'id_presensi' => 4337,
    'nis' => 13905,
    'tanggal' => '2026-07-17',
    'jam' => '11:27:00',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  175 => 
  array (
    'id_presensi' => 4338,
    'nis' => 14691,
    'tanggal' => '2026-07-17',
    'jam' => '12:41:56',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  176 => 
  array (
    'id_presensi' => 4339,
    'nis' => 14703,
    'tanggal' => '2026-07-17',
    'jam' => '12:42:16',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  177 => 
  array (
    'id_presensi' => 4340,
    'nis' => 14677,
    'tanggal' => '2026-07-17',
    'jam' => '12:43:19',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  178 => 
  array (
    'id_presensi' => 4341,
    'nis' => 14721,
    'tanggal' => '2026-07-17',
    'jam' => '12:46:07',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  179 => 
  array (
    'id_presensi' => 4342,
    'nis' => 14712,
    'tanggal' => '2026-07-17',
    'jam' => '12:46:46',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  180 => 
  array (
    'id_presensi' => 4343,
    'nis' => 14717,
    'tanggal' => '2026-07-17',
    'jam' => '12:46:54',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  181 => 
  array (
    'id_presensi' => 4344,
    'nis' => 14732,
    'tanggal' => '2026-07-17',
    'jam' => '12:46:56',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  182 => 
  array (
    'id_presensi' => 4345,
    'nis' => 14730,
    'tanggal' => '2026-07-17',
    'jam' => '12:47:04',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  183 => 
  array (
    'id_presensi' => 4346,
    'nis' => 14737,
    'tanggal' => '2026-07-17',
    'jam' => '12:47:06',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  184 => 
  array (
    'id_presensi' => 4347,
    'nis' => 14724,
    'tanggal' => '2026-07-17',
    'jam' => '12:47:09',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  185 => 
  array (
    'id_presensi' => 4348,
    'nis' => 14720,
    'tanggal' => '2026-07-17',
    'jam' => '12:47:11',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  186 => 
  array (
    'id_presensi' => 4349,
    'nis' => 14722,
    'tanggal' => '2026-07-17',
    'jam' => '12:47:46',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  187 => 
  array (
    'id_presensi' => 4350,
    'nis' => 14735,
    'tanggal' => '2026-07-17',
    'jam' => '12:47:55',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  188 => 
  array (
    'id_presensi' => 4351,
    'nis' => 14706,
    'tanggal' => '2026-07-17',
    'jam' => '12:48:13',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  189 => 
  array (
    'id_presensi' => 4352,
    'nis' => 14446,
    'tanggal' => '2026-07-17',
    'jam' => '12:48:17',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  190 => 
  array (
    'id_presensi' => 4353,
    'nis' => 14711,
    'tanggal' => '2026-07-17',
    'jam' => '12:48:19',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  191 => 
  array (
    'id_presensi' => 4354,
    'nis' => 14734,
    'tanggal' => '2026-07-17',
    'jam' => '12:48:23',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  192 => 
  array (
    'id_presensi' => 4355,
    'nis' => 14709,
    'tanggal' => '2026-07-17',
    'jam' => '12:49:12',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  193 => 
  array (
    'id_presensi' => 4356,
    'nis' => 14715,
    'tanggal' => '2026-07-17',
    'jam' => '12:49:20',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  194 => 
  array (
    'id_presensi' => 4357,
    'nis' => 14728,
    'tanggal' => '2026-07-17',
    'jam' => '12:49:54',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  195 => 
  array (
    'id_presensi' => 4358,
    'nis' => 14686,
    'tanggal' => '2026-07-17',
    'jam' => '12:51:01',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  196 => 
  array (
    'id_presensi' => 4359,
    'nis' => 14689,
    'tanggal' => '2026-07-17',
    'jam' => '12:51:26',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  197 => 
  array (
    'id_presensi' => 4360,
    'nis' => 14693,
    'tanggal' => '2026-07-17',
    'jam' => '12:51:28',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  198 => 
  array (
    'id_presensi' => 4361,
    'nis' => 14690,
    'tanggal' => '2026-07-17',
    'jam' => '12:51:32',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  199 => 
  array (
    'id_presensi' => 4362,
    'nis' => 14674,
    'tanggal' => '2026-07-17',
    'jam' => '12:51:35',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
));

        DB::table('presensi')->insert(array (
  0 => 
  array (
    'id_presensi' => 4363,
    'nis' => 14710,
    'tanggal' => '2026-07-17',
    'jam' => '12:55:16',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  1 => 
  array (
    'id_presensi' => 4364,
    'nis' => 14725,
    'tanggal' => '2026-07-17',
    'jam' => '12:55:49',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  2 => 
  array (
    'id_presensi' => 4365,
    'nis' => 14676,
    'tanggal' => '2026-07-17',
    'jam' => '12:56:24',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  3 => 
  array (
    'id_presensi' => 4366,
    'nis' => 14699,
    'tanggal' => '2026-07-17',
    'jam' => '12:56:30',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  4 => 
  array (
    'id_presensi' => 4367,
    'nis' => 14679,
    'tanggal' => '2026-07-17',
    'jam' => '12:57:07',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  5 => 
  array (
    'id_presensi' => 4368,
    'nis' => 14675,
    'tanggal' => '2026-07-17',
    'jam' => '12:57:39',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  6 => 
  array (
    'id_presensi' => 4369,
    'nis' => 14723,
    'tanggal' => '2026-07-17',
    'jam' => '12:57:48',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  7 => 
  array (
    'id_presensi' => 4370,
    'nis' => 14726,
    'tanggal' => '2026-07-17',
    'jam' => '12:58:01',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  8 => 
  array (
    'id_presensi' => 4371,
    'nis' => 14733,
    'tanggal' => '2026-07-17',
    'jam' => '12:58:47',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  9 => 
  array (
    'id_presensi' => 4372,
    'nis' => 14727,
    'tanggal' => '2026-07-17',
    'jam' => '12:58:51',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  10 => 
  array (
    'id_presensi' => 4373,
    'nis' => 14694,
    'tanggal' => '2026-07-17',
    'jam' => '12:59:57',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  11 => 
  array (
    'id_presensi' => 4374,
    'nis' => 14687,
    'tanggal' => '2026-07-17',
    'jam' => '13:00:07',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  12 => 
  array (
    'id_presensi' => 4375,
    'nis' => 14705,
    'tanggal' => '2026-07-17',
    'jam' => '13:00:12',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  13 => 
  array (
    'id_presensi' => 4376,
    'nis' => 14688,
    'tanggal' => '2026-07-17',
    'jam' => '13:00:20',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  14 => 
  array (
    'id_presensi' => 4377,
    'nis' => 14438,
    'tanggal' => '2026-07-17',
    'jam' => '13:01:15',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  15 => 
  array (
    'id_presensi' => 4378,
    'nis' => 14422,
    'tanggal' => '2026-07-17',
    'jam' => '13:01:21',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  16 => 
  array (
    'id_presensi' => 4379,
    'nis' => 14704,
    'tanggal' => '2026-07-17',
    'jam' => '13:01:25',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  17 => 
  array (
    'id_presensi' => 4380,
    'nis' => 14701,
    'tanggal' => '2026-07-17',
    'jam' => '13:01:47',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  18 => 
  array (
    'id_presensi' => 4381,
    'nis' => 14680,
    'tanggal' => '2026-07-17',
    'jam' => '13:02:01',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  19 => 
  array (
    'id_presensi' => 4382,
    'nis' => 14683,
    'tanggal' => '2026-07-17',
    'jam' => '13:02:06',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  20 => 
  array (
    'id_presensi' => 4383,
    'nis' => 14698,
    'tanggal' => '2026-07-17',
    'jam' => '13:02:38',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  21 => 
  array (
    'id_presensi' => 4384,
    'nis' => 14697,
    'tanggal' => '2026-07-17',
    'jam' => '13:04:37',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  22 => 
  array (
    'id_presensi' => 4385,
    'nis' => 14685,
    'tanggal' => '2026-07-17',
    'jam' => '13:04:40',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  23 => 
  array (
    'id_presensi' => 4386,
    'nis' => 14435,
    'tanggal' => '2026-07-17',
    'jam' => '13:04:46',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  24 => 
  array (
    'id_presensi' => 4387,
    'nis' => 14684,
    'tanggal' => '2026-07-17',
    'jam' => '13:04:49',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  25 => 
  array (
    'id_presensi' => 4388,
    'nis' => 14682,
    'tanggal' => '2026-07-17',
    'jam' => '13:04:57',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  26 => 
  array (
    'id_presensi' => 4389,
    'nis' => 14444,
    'tanggal' => '2026-07-17',
    'jam' => '13:12:00',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  27 => 
  array (
    'id_presensi' => 4390,
    'nis' => 14424,
    'tanggal' => '2026-07-17',
    'jam' => '13:16:17',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  28 => 
  array (
    'id_presensi' => 4391,
    'nis' => 14714,
    'tanggal' => '2026-07-17',
    'jam' => '14:59:52',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  29 => 
  array (
    'id_presensi' => 4392,
    'nis' => 14738,
    'tanggal' => '2026-07-17',
    'jam' => '14:59:58',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  30 => 
  array (
    'id_presensi' => 4393,
    'nis' => 14433,
    'tanggal' => '2026-07-17',
    'jam' => '15:25:13',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  31 => 
  array (
    'id_presensi' => 4394,
    'nis' => 14439,
    'tanggal' => '2026-07-17',
    'jam' => '15:25:57',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  32 => 
  array (
    'id_presensi' => 4395,
    'nis' => 14443,
    'tanggal' => '2026-07-17',
    'jam' => '16:47:58',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  33 => 
  array (
    'id_presensi' => 4396,
    'nis' => 14434,
    'tanggal' => '2026-07-17',
    'jam' => '16:48:04',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  34 => 
  array (
    'id_presensi' => 4397,
    'nis' => 14447,
    'tanggal' => '2026-07-17',
    'jam' => '16:48:45',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  35 => 
  array (
    'id_presensi' => 4398,
    'nis' => 13871,
    'tanggal' => '2026-07-18',
    'jam' => '16:53:19',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  36 => 
  array (
    'id_presensi' => 4399,
    'nis' => 13871,
    'tanggal' => '2026-07-19',
    'jam' => '07:03:03',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  37 => 
  array (
    'id_presensi' => 4400,
    'nis' => 14382,
    'tanggal' => '2026-07-19',
    'jam' => '07:03:20',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  38 => 
  array (
    'id_presensi' => 4401,
    'nis' => 14773,
    'tanggal' => '2026-07-20',
    'jam' => '06:17:25',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  39 => 
  array (
    'id_presensi' => 4402,
    'nis' => 14774,
    'tanggal' => '2026-07-20',
    'jam' => '06:18:33',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  40 => 
  array (
    'id_presensi' => 4403,
    'nis' => 14213,
    'tanggal' => '2026-07-20',
    'jam' => '06:18:58',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  41 => 
  array (
    'id_presensi' => 4404,
    'nis' => 14379,
    'tanggal' => '2026-07-20',
    'jam' => '06:24:15',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  42 => 
  array (
    'id_presensi' => 4405,
    'nis' => 14378,
    'tanggal' => '2026-07-20',
    'jam' => '06:24:21',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  43 => 
  array (
    'id_presensi' => 4406,
    'nis' => 13868,
    'tanggal' => '2026-07-20',
    'jam' => '06:28:30',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  44 => 
  array (
    'id_presensi' => 4407,
    'nis' => 14759,
    'tanggal' => '2026-07-20',
    'jam' => '06:32:09',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  45 => 
  array (
    'id_presensi' => 4408,
    'nis' => 14578,
    'tanggal' => '2026-07-20',
    'jam' => '06:32:23',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  46 => 
  array (
    'id_presensi' => 4409,
    'nis' => 13891,
    'tanggal' => '2026-07-20',
    'jam' => '06:32:26',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  47 => 
  array (
    'id_presensi' => 4410,
    'nis' => 14763,
    'tanggal' => '2026-07-20',
    'jam' => '06:34:56',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  48 => 
  array (
    'id_presensi' => 4411,
    'nis' => 14744,
    'tanggal' => '2026-07-20',
    'jam' => '06:34:57',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  49 => 
  array (
    'id_presensi' => 4412,
    'nis' => 14724,
    'tanggal' => '2026-07-20',
    'jam' => '06:36:02',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  50 => 
  array (
    'id_presensi' => 4413,
    'nis' => 14695,
    'tanggal' => '2026-07-20',
    'jam' => '06:37:59',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  51 => 
  array (
    'id_presensi' => 4414,
    'nis' => 14381,
    'tanggal' => '2026-07-20',
    'jam' => '06:38:18',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  52 => 
  array (
    'id_presensi' => 4415,
    'nis' => 14693,
    'tanggal' => '2026-07-20',
    'jam' => '06:39:17',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  53 => 
  array (
    'id_presensi' => 4416,
    'nis' => 14674,
    'tanggal' => '2026-07-20',
    'jam' => '06:39:48',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  54 => 
  array (
    'id_presensi' => 4417,
    'nis' => 14479,
    'tanggal' => '2026-07-20',
    'jam' => '06:39:56',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  55 => 
  array (
    'id_presensi' => 4418,
    'nis' => 14605,
    'tanggal' => '2026-07-20',
    'jam' => '06:40:25',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  56 => 
  array (
    'id_presensi' => 4419,
    'nis' => 14385,
    'tanggal' => '2026-07-20',
    'jam' => '06:40:40',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  57 => 
  array (
    'id_presensi' => 4420,
    'nis' => 14717,
    'tanggal' => '2026-07-20',
    'jam' => '06:40:49',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  58 => 
  array (
    'id_presensi' => 4421,
    'nis' => 14741,
    'tanggal' => '2026-07-20',
    'jam' => '06:41:03',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  59 => 
  array (
    'id_presensi' => 4422,
    'nis' => 13927,
    'tanggal' => '2026-07-20',
    'jam' => '06:41:37',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  60 => 
  array (
    'id_presensi' => 4423,
    'nis' => 14502,
    'tanggal' => '2026-07-20',
    'jam' => '06:42:42',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  61 => 
  array (
    'id_presensi' => 4424,
    'nis' => 14375,
    'tanggal' => '2026-07-20',
    'jam' => '06:42:43',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  62 => 
  array (
    'id_presensi' => 4425,
    'nis' => 14339,
    'tanggal' => '2026-07-20',
    'jam' => '06:43:54',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  63 => 
  array (
    'id_presensi' => 4426,
    'nis' => 14382,
    'tanggal' => '2026-07-20',
    'jam' => '06:43:58',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  64 => 
  array (
    'id_presensi' => 4427,
    'nis' => 13906,
    'tanggal' => '2026-07-20',
    'jam' => '06:44:16',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  65 => 
  array (
    'id_presensi' => 4428,
    'nis' => 13908,
    'tanggal' => '2026-07-20',
    'jam' => '06:45:30',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  66 => 
  array (
    'id_presensi' => 4429,
    'nis' => 14290,
    'tanggal' => '2026-07-20',
    'jam' => '06:45:32',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  67 => 
  array (
    'id_presensi' => 4430,
    'nis' => 14302,
    'tanggal' => '2026-07-20',
    'jam' => '06:45:34',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  68 => 
  array (
    'id_presensi' => 4431,
    'nis' => 14769,
    'tanggal' => '2026-07-20',
    'jam' => '06:45:46',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  69 => 
  array (
    'id_presensi' => 4432,
    'nis' => 14749,
    'tanggal' => '2026-07-20',
    'jam' => '06:45:48',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  70 => 
  array (
    'id_presensi' => 4433,
    'nis' => 14380,
    'tanggal' => '2026-07-20',
    'jam' => '06:45:52',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  71 => 
  array (
    'id_presensi' => 4434,
    'nis' => 14766,
    'tanggal' => '2026-07-20',
    'jam' => '06:45:59',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  72 => 
  array (
    'id_presensi' => 4435,
    'nis' => 14340,
    'tanggal' => '2026-07-20',
    'jam' => '06:46:18',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  73 => 
  array (
    'id_presensi' => 4436,
    'nis' => 14604,
    'tanggal' => '2026-07-20',
    'jam' => '06:46:49',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  74 => 
  array (
    'id_presensi' => 4437,
    'nis' => 14434,
    'tanggal' => '2026-07-20',
    'jam' => '06:46:59',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  75 => 
  array (
    'id_presensi' => 4438,
    'nis' => 14786,
    'tanggal' => '2026-07-20',
    'jam' => '06:47:06',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  76 => 
  array (
    'id_presensi' => 4439,
    'nis' => 14785,
    'tanggal' => '2026-07-20',
    'jam' => '06:47:09',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  77 => 
  array (
    'id_presensi' => 4440,
    'nis' => 14311,
    'tanggal' => '2026-07-20',
    'jam' => '06:47:25',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  78 => 
  array (
    'id_presensi' => 4441,
    'nis' => 14426,
    'tanggal' => '2026-07-20',
    'jam' => '06:47:30',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  79 => 
  array (
    'id_presensi' => 4442,
    'nis' => 13919,
    'tanggal' => '2026-07-20',
    'jam' => '06:47:31',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  80 => 
  array (
    'id_presensi' => 4443,
    'nis' => 14508,
    'tanggal' => '2026-07-20',
    'jam' => '06:47:33',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  81 => 
  array (
    'id_presensi' => 4444,
    'nis' => 14747,
    'tanggal' => '2026-07-20',
    'jam' => '06:47:34',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  82 => 
  array (
    'id_presensi' => 4445,
    'nis' => 14496,
    'tanggal' => '2026-07-20',
    'jam' => '06:47:35',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  83 => 
  array (
    'id_presensi' => 4446,
    'nis' => 14437,
    'tanggal' => '2026-07-20',
    'jam' => '06:47:42',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  84 => 
  array (
    'id_presensi' => 4447,
    'nis' => 13900,
    'tanggal' => '2026-07-20',
    'jam' => '06:47:58',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  85 => 
  array (
    'id_presensi' => 4448,
    'nis' => 14775,
    'tanggal' => '2026-07-20',
    'jam' => '06:48:15',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  86 => 
  array (
    'id_presensi' => 4449,
    'nis' => 14495,
    'tanggal' => '2026-07-20',
    'jam' => '06:48:16',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  87 => 
  array (
    'id_presensi' => 4450,
    'nis' => 14145,
    'tanggal' => '2026-07-20',
    'jam' => '06:48:24',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  88 => 
  array (
    'id_presensi' => 4451,
    'nis' => 14753,
    'tanggal' => '2026-07-20',
    'jam' => '06:48:33',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  89 => 
  array (
    'id_presensi' => 4452,
    'nis' => 14805,
    'tanggal' => '2026-07-20',
    'jam' => '06:48:39',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  90 => 
  array (
    'id_presensi' => 4453,
    'nis' => 14691,
    'tanggal' => '2026-07-20',
    'jam' => '06:48:42',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  91 => 
  array (
    'id_presensi' => 4454,
    'nis' => 14317,
    'tanggal' => '2026-07-20',
    'jam' => '06:48:49',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  92 => 
  array (
    'id_presensi' => 4455,
    'nis' => 14770,
    'tanggal' => '2026-07-20',
    'jam' => '06:49:10',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  93 => 
  array (
    'id_presensi' => 4456,
    'nis' => 14732,
    'tanggal' => '2026-07-20',
    'jam' => '06:49:31',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  94 => 
  array (
    'id_presensi' => 4457,
    'nis' => 14737,
    'tanggal' => '2026-07-20',
    'jam' => '06:49:34',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  95 => 
  array (
    'id_presensi' => 4458,
    'nis' => 14712,
    'tanggal' => '2026-07-20',
    'jam' => '06:49:36',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  96 => 
  array (
    'id_presensi' => 4459,
    'nis' => 14728,
    'tanggal' => '2026-07-20',
    'jam' => '06:49:37',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  97 => 
  array (
    'id_presensi' => 4460,
    'nis' => 14734,
    'tanggal' => '2026-07-20',
    'jam' => '06:49:39',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  98 => 
  array (
    'id_presensi' => 4461,
    'nis' => 14214,
    'tanggal' => '2026-07-20',
    'jam' => '06:49:41',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  99 => 
  array (
    'id_presensi' => 4462,
    'nis' => 14715,
    'tanggal' => '2026-07-20',
    'jam' => '06:49:42',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  100 => 
  array (
    'id_presensi' => 4463,
    'nis' => 14709,
    'tanggal' => '2026-07-20',
    'jam' => '06:49:43',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  101 => 
  array (
    'id_presensi' => 4464,
    'nis' => 14239,
    'tanggal' => '2026-07-20',
    'jam' => '06:49:43',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  102 => 
  array (
    'id_presensi' => 4465,
    'nis' => 14745,
    'tanggal' => '2026-07-20',
    'jam' => '06:49:46',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  103 => 
  array (
    'id_presensi' => 4466,
    'nis' => 14431,
    'tanggal' => '2026-07-20',
    'jam' => '06:49:51',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  104 => 
  array (
    'id_presensi' => 4467,
    'nis' => 14418,
    'tanggal' => '2026-07-20',
    'jam' => '06:49:56',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  105 => 
  array (
    'id_presensi' => 4468,
    'nis' => 14428,
    'tanggal' => '2026-07-20',
    'jam' => '06:49:58',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  106 => 
  array (
    'id_presensi' => 4469,
    'nis' => 14329,
    'tanggal' => '2026-07-20',
    'jam' => '06:49:59',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  107 => 
  array (
    'id_presensi' => 4470,
    'nis' => 14430,
    'tanggal' => '2026-07-20',
    'jam' => '06:50:01',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  108 => 
  array (
    'id_presensi' => 4471,
    'nis' => 14713,
    'tanggal' => '2026-07-20',
    'jam' => '06:50:14',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  109 => 
  array (
    'id_presensi' => 4472,
    'nis' => 14730,
    'tanggal' => '2026-07-20',
    'jam' => '06:50:16',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  110 => 
  array (
    'id_presensi' => 4473,
    'nis' => 14689,
    'tanggal' => '2026-07-20',
    'jam' => '06:50:17',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  111 => 
  array (
    'id_presensi' => 4474,
    'nis' => 14688,
    'tanggal' => '2026-07-20',
    'jam' => '06:50:19',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  112 => 
  array (
    'id_presensi' => 4475,
    'nis' => 14682,
    'tanggal' => '2026-07-20',
    'jam' => '06:50:24',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  113 => 
  array (
    'id_presensi' => 4476,
    'nis' => 14435,
    'tanggal' => '2026-07-20',
    'jam' => '06:50:27',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  114 => 
  array (
    'id_presensi' => 4477,
    'nis' => 14703,
    'tanggal' => '2026-07-20',
    'jam' => '06:50:32',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  115 => 
  array (
    'id_presensi' => 4478,
    'nis' => 14321,
    'tanggal' => '2026-07-20',
    'jam' => '06:50:33',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  116 => 
  array (
    'id_presensi' => 4479,
    'nis' => 14701,
    'tanggal' => '2026-07-20',
    'jam' => '06:50:34',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  117 => 
  array (
    'id_presensi' => 4480,
    'nis' => 14767,
    'tanggal' => '2026-07-20',
    'jam' => '06:50:40',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  118 => 
  array (
    'id_presensi' => 4481,
    'nis' => 14461,
    'tanggal' => '2026-07-20',
    'jam' => '06:50:42',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  119 => 
  array (
    'id_presensi' => 4482,
    'nis' => 14469,
    'tanggal' => '2026-07-20',
    'jam' => '06:50:45',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  120 => 
  array (
    'id_presensi' => 4483,
    'nis' => 14585,
    'tanggal' => '2026-07-20',
    'jam' => '06:50:46',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  121 => 
  array (
    'id_presensi' => 4484,
    'nis' => 14463,
    'tanggal' => '2026-07-20',
    'jam' => '06:50:47',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  122 => 
  array (
    'id_presensi' => 4485,
    'nis' => 14787,
    'tanggal' => '2026-07-20',
    'jam' => '06:50:50',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  123 => 
  array (
    'id_presensi' => 4486,
    'nis' => 14788,
    'tanggal' => '2026-07-20',
    'jam' => '06:50:52',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  124 => 
  array (
    'id_presensi' => 4487,
    'nis' => 13922,
    'tanggal' => '2026-07-20',
    'jam' => '06:50:53',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  125 => 
  array (
    'id_presensi' => 4488,
    'nis' => 14783,
    'tanggal' => '2026-07-20',
    'jam' => '06:51:27',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  126 => 
  array (
    'id_presensi' => 4489,
    'nis' => 14779,
    'tanggal' => '2026-07-20',
    'jam' => '06:51:35',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  127 => 
  array (
    'id_presensi' => 4490,
    'nis' => 14793,
    'tanggal' => '2026-07-20',
    'jam' => '06:51:41',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  128 => 
  array (
    'id_presensi' => 4491,
    'nis' => 14711,
    'tanggal' => '2026-07-20',
    'jam' => '06:51:50',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  129 => 
  array (
    'id_presensi' => 4492,
    'nis' => 14720,
    'tanggal' => '2026-07-20',
    'jam' => '06:51:54',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  130 => 
  array (
    'id_presensi' => 4493,
    'nis' => 13876,
    'tanggal' => '2026-07-20',
    'jam' => '06:52:01',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  131 => 
  array (
    'id_presensi' => 4494,
    'nis' => 14752,
    'tanggal' => '2026-07-20',
    'jam' => '06:52:10',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  132 => 
  array (
    'id_presensi' => 4495,
    'nis' => 14687,
    'tanggal' => '2026-07-20',
    'jam' => '06:52:13',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  133 => 
  array (
    'id_presensi' => 4496,
    'nis' => 14584,
    'tanggal' => '2026-07-20',
    'jam' => '06:52:16',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  134 => 
  array (
    'id_presensi' => 4497,
    'nis' => 14582,
    'tanggal' => '2026-07-20',
    'jam' => '06:52:19',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  135 => 
  array (
    'id_presensi' => 4498,
    'nis' => 14588,
    'tanggal' => '2026-07-20',
    'jam' => '06:52:21',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  136 => 
  array (
    'id_presensi' => 4499,
    'nis' => 14294,
    'tanggal' => '2026-07-20',
    'jam' => '06:52:25',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  137 => 
  array (
    'id_presensi' => 4500,
    'nis' => 14295,
    'tanggal' => '2026-07-20',
    'jam' => '06:52:27',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  138 => 
  array (
    'id_presensi' => 4501,
    'nis' => 14331,
    'tanggal' => '2026-07-20',
    'jam' => '06:52:29',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  139 => 
  array (
    'id_presensi' => 4502,
    'nis' => 14288,
    'tanggal' => '2026-07-20',
    'jam' => '06:52:29',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  140 => 
  array (
    'id_presensi' => 4503,
    'nis' => 14492,
    'tanggal' => '2026-07-20',
    'jam' => '06:52:33',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  141 => 
  array (
    'id_presensi' => 4504,
    'nis' => 14736,
    'tanggal' => '2026-07-20',
    'jam' => '06:52:35',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  142 => 
  array (
    'id_presensi' => 4505,
    'nis' => 13875,
    'tanggal' => '2026-07-20',
    'jam' => '06:52:38',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  143 => 
  array (
    'id_presensi' => 4506,
    'nis' => 14486,
    'tanggal' => '2026-07-20',
    'jam' => '06:52:39',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  144 => 
  array (
    'id_presensi' => 4507,
    'nis' => 13877,
    'tanggal' => '2026-07-20',
    'jam' => '06:52:41',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  145 => 
  array (
    'id_presensi' => 4508,
    'nis' => 13864,
    'tanggal' => '2026-07-20',
    'jam' => '06:52:44',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  146 => 
  array (
    'id_presensi' => 4509,
    'nis' => 14327,
    'tanggal' => '2026-07-20',
    'jam' => '06:52:56',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  147 => 
  array (
    'id_presensi' => 4510,
    'nis' => 14318,
    'tanggal' => '2026-07-20',
    'jam' => '06:52:58',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  148 => 
  array (
    'id_presensi' => 4511,
    'nis' => 14286,
    'tanggal' => '2026-07-20',
    'jam' => '06:53:10',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  149 => 
  array (
    'id_presensi' => 4512,
    'nis' => 14707,
    'tanggal' => '2026-07-20',
    'jam' => '06:53:17',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  150 => 
  array (
    'id_presensi' => 4513,
    'nis' => 14735,
    'tanggal' => '2026-07-20',
    'jam' => '06:53:20',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  151 => 
  array (
    'id_presensi' => 4514,
    'nis' => 14679,
    'tanggal' => '2026-07-20',
    'jam' => '06:53:24',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  152 => 
  array (
    'id_presensi' => 4515,
    'nis' => 14483,
    'tanggal' => '2026-07-20',
    'jam' => '06:53:26',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  153 => 
  array (
    'id_presensi' => 4516,
    'nis' => 14507,
    'tanggal' => '2026-07-20',
    'jam' => '06:53:29',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  154 => 
  array (
    'id_presensi' => 4517,
    'nis' => 13901,
    'tanggal' => '2026-07-20',
    'jam' => '06:53:32',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  155 => 
  array (
    'id_presensi' => 4518,
    'nis' => 13903,
    'tanggal' => '2026-07-20',
    'jam' => '06:53:35',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  156 => 
  array (
    'id_presensi' => 4519,
    'nis' => 13929,
    'tanggal' => '2026-07-20',
    'jam' => '06:53:41',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  157 => 
  array (
    'id_presensi' => 4520,
    'nis' => 14606,
    'tanggal' => '2026-07-20',
    'jam' => '06:53:43',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  158 => 
  array (
    'id_presensi' => 4521,
    'nis' => 14746,
    'tanggal' => '2026-07-20',
    'jam' => '06:53:45',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  159 => 
  array (
    'id_presensi' => 4522,
    'nis' => 14601,
    'tanggal' => '2026-07-20',
    'jam' => '06:53:46',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  160 => 
  array (
    'id_presensi' => 4523,
    'nis' => 14768,
    'tanggal' => '2026-07-20',
    'jam' => '06:53:52',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  161 => 
  array (
    'id_presensi' => 4524,
    'nis' => 14599,
    'tanggal' => '2026-07-20',
    'jam' => '06:53:52',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  162 => 
  array (
    'id_presensi' => 4525,
    'nis' => 14706,
    'tanggal' => '2026-07-20',
    'jam' => '06:53:56',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  163 => 
  array (
    'id_presensi' => 4526,
    'nis' => 13897,
    'tanggal' => '2026-07-20',
    'jam' => '06:53:59',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  164 => 
  array (
    'id_presensi' => 4527,
    'nis' => 14602,
    'tanggal' => '2026-07-20',
    'jam' => '06:54:01',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  165 => 
  array (
    'id_presensi' => 4528,
    'nis' => 14725,
    'tanggal' => '2026-07-20',
    'jam' => '06:54:03',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  166 => 
  array (
    'id_presensi' => 4529,
    'nis' => 14764,
    'tanggal' => '2026-07-20',
    'jam' => '06:54:05',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  167 => 
  array (
    'id_presensi' => 4530,
    'nis' => 14710,
    'tanggal' => '2026-07-20',
    'jam' => '06:54:08',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  168 => 
  array (
    'id_presensi' => 4531,
    'nis' => 14504,
    'tanggal' => '2026-07-20',
    'jam' => '06:54:11',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  169 => 
  array (
    'id_presensi' => 4532,
    'nis' => 14474,
    'tanggal' => '2026-07-20',
    'jam' => '06:54:14',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  170 => 
  array (
    'id_presensi' => 4533,
    'nis' => 13911,
    'tanggal' => '2026-07-20',
    'jam' => '06:54:15',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  171 => 
  array (
    'id_presensi' => 4534,
    'nis' => 14476,
    'tanggal' => '2026-07-20',
    'jam' => '06:54:17',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  172 => 
  array (
    'id_presensi' => 4535,
    'nis' => 14335,
    'tanggal' => '2026-07-20',
    'jam' => '06:54:20',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  173 => 
  array (
    'id_presensi' => 4536,
    'nis' => 14450,
    'tanggal' => '2026-07-20',
    'jam' => '06:54:22',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  174 => 
  array (
    'id_presensi' => 4537,
    'nis' => 14777,
    'tanggal' => '2026-07-20',
    'jam' => '06:54:23',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  175 => 
  array (
    'id_presensi' => 4538,
    'nis' => 14782,
    'tanggal' => '2026-07-20',
    'jam' => '06:54:25',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  176 => 
  array (
    'id_presensi' => 4539,
    'nis' => 14803,
    'tanggal' => '2026-07-20',
    'jam' => '06:54:25',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  177 => 
  array (
    'id_presensi' => 4540,
    'nis' => 14778,
    'tanggal' => '2026-07-20',
    'jam' => '06:54:28',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  178 => 
  array (
    'id_presensi' => 4541,
    'nis' => 13915,
    'tanggal' => '2026-07-20',
    'jam' => '06:54:35',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  179 => 
  array (
    'id_presensi' => 4542,
    'nis' => 14491,
    'tanggal' => '2026-07-20',
    'jam' => '06:54:38',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  180 => 
  array (
    'id_presensi' => 4543,
    'nis' => 14800,
    'tanggal' => '2026-07-20',
    'jam' => '06:54:40',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  181 => 
  array (
    'id_presensi' => 4544,
    'nis' => 14440,
    'tanggal' => '2026-07-20',
    'jam' => '06:54:43',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  182 => 
  array (
    'id_presensi' => 4545,
    'nis' => 14425,
    'tanggal' => '2026-07-20',
    'jam' => '06:54:45',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  183 => 
  array (
    'id_presensi' => 4546,
    'nis' => 14804,
    'tanggal' => '2026-07-20',
    'jam' => '06:54:46',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  184 => 
  array (
    'id_presensi' => 4547,
    'nis' => 14419,
    'tanggal' => '2026-07-20',
    'jam' => '06:54:48',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  185 => 
  array (
    'id_presensi' => 4548,
    'nis' => 14447,
    'tanggal' => '2026-07-20',
    'jam' => '06:54:50',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  186 => 
  array (
    'id_presensi' => 4549,
    'nis' => 14441,
    'tanggal' => '2026-07-20',
    'jam' => '06:54:54',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  187 => 
  array (
    'id_presensi' => 4550,
    'nis' => 14420,
    'tanggal' => '2026-07-20',
    'jam' => '06:54:54',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  188 => 
  array (
    'id_presensi' => 4551,
    'nis' => 14423,
    'tanggal' => '2026-07-20',
    'jam' => '06:54:56',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  189 => 
  array (
    'id_presensi' => 4552,
    'nis' => 14727,
    'tanggal' => '2026-07-20',
    'jam' => '06:54:57',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  190 => 
  array (
    'id_presensi' => 4553,
    'nis' => 14427,
    'tanggal' => '2026-07-20',
    'jam' => '06:55:00',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  191 => 
  array (
    'id_presensi' => 4554,
    'nis' => 13913,
    'tanggal' => '2026-07-20',
    'jam' => '06:55:02',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  192 => 
  array (
    'id_presensi' => 4555,
    'nis' => 14445,
    'tanggal' => '2026-07-20',
    'jam' => '06:55:03',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  193 => 
  array (
    'id_presensi' => 4556,
    'nis' => 14316,
    'tanggal' => '2026-07-20',
    'jam' => '06:55:03',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  194 => 
  array (
    'id_presensi' => 4557,
    'nis' => 14424,
    'tanggal' => '2026-07-20',
    'jam' => '06:55:05',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  195 => 
  array (
    'id_presensi' => 4558,
    'nis' => 14333,
    'tanggal' => '2026-07-20',
    'jam' => '06:55:09',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  196 => 
  array (
    'id_presensi' => 4559,
    'nis' => 14433,
    'tanggal' => '2026-07-20',
    'jam' => '06:55:09',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  197 => 
  array (
    'id_presensi' => 4560,
    'nis' => 14439,
    'tanggal' => '2026-07-20',
    'jam' => '06:55:11',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  198 => 
  array (
    'id_presensi' => 4561,
    'nis' => 14429,
    'tanggal' => '2026-07-20',
    'jam' => '06:55:13',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  199 => 
  array (
    'id_presensi' => 4562,
    'nis' => 14776,
    'tanggal' => '2026-07-20',
    'jam' => '06:55:15',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
));

        DB::table('presensi')->insert(array (
  0 => 
  array (
    'id_presensi' => 4563,
    'nis' => 14292,
    'tanggal' => '2026-07-20',
    'jam' => '06:55:17',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  1 => 
  array (
    'id_presensi' => 4564,
    'nis' => 14324,
    'tanggal' => '2026-07-20',
    'jam' => '06:55:17',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  2 => 
  array (
    'id_presensi' => 4565,
    'nis' => 14320,
    'tanggal' => '2026-07-20',
    'jam' => '06:55:19',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  3 => 
  array (
    'id_presensi' => 4566,
    'nis' => 14498,
    'tanggal' => '2026-07-20',
    'jam' => '06:55:25',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  4 => 
  array (
    'id_presensi' => 4567,
    'nis' => 14772,
    'tanggal' => '2026-07-20',
    'jam' => '06:55:29',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  5 => 
  array (
    'id_presensi' => 4568,
    'nis' => 13918,
    'tanggal' => '2026-07-20',
    'jam' => '06:55:32',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  6 => 
  array (
    'id_presensi' => 4569,
    'nis' => 14761,
    'tanggal' => '2026-07-20',
    'jam' => '06:55:33',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  7 => 
  array (
    'id_presensi' => 4570,
    'nis' => 13869,
    'tanggal' => '2026-07-20',
    'jam' => '06:55:35',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  8 => 
  array (
    'id_presensi' => 4571,
    'nis' => 13874,
    'tanggal' => '2026-07-20',
    'jam' => '06:55:38',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  9 => 
  array (
    'id_presensi' => 4572,
    'nis' => 14714,
    'tanggal' => '2026-07-20',
    'jam' => '06:55:43',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  10 => 
  array (
    'id_presensi' => 4573,
    'nis' => 14696,
    'tanggal' => '2026-07-20',
    'jam' => '06:55:55',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  11 => 
  array (
    'id_presensi' => 4574,
    'nis' => 14422,
    'tanggal' => '2026-07-20',
    'jam' => '06:55:55',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  12 => 
  array (
    'id_presensi' => 4575,
    'nis' => 14326,
    'tanggal' => '2026-07-20',
    'jam' => '06:56:07',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  13 => 
  array (
    'id_presensi' => 4576,
    'nis' => 14298,
    'tanggal' => '2026-07-20',
    'jam' => '06:56:09',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  14 => 
  array (
    'id_presensi' => 4577,
    'nis' => 14322,
    'tanggal' => '2026-07-20',
    'jam' => '06:56:10',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  15 => 
  array (
    'id_presensi' => 4578,
    'nis' => 14297,
    'tanggal' => '2026-07-20',
    'jam' => '06:56:11',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  16 => 
  array (
    'id_presensi' => 4579,
    'nis' => 14384,
    'tanggal' => '2026-07-20',
    'jam' => '06:56:13',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  17 => 
  array (
    'id_presensi' => 4580,
    'nis' => 14771,
    'tanggal' => '2026-07-20',
    'jam' => '06:56:15',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  18 => 
  array (
    'id_presensi' => 4581,
    'nis' => 14595,
    'tanggal' => '2026-07-20',
    'jam' => '06:56:16',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  19 => 
  array (
    'id_presensi' => 4582,
    'nis' => 14690,
    'tanggal' => '2026-07-20',
    'jam' => '06:56:17',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  20 => 
  array (
    'id_presensi' => 4583,
    'nis' => 14680,
    'tanggal' => '2026-07-20',
    'jam' => '06:56:19',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  21 => 
  array (
    'id_presensi' => 4584,
    'nis' => 14589,
    'tanggal' => '2026-07-20',
    'jam' => '06:56:20',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  22 => 
  array (
    'id_presensi' => 4585,
    'nis' => 14462,
    'tanggal' => '2026-07-20',
    'jam' => '06:56:22',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  23 => 
  array (
    'id_presensi' => 4586,
    'nis' => 14587,
    'tanggal' => '2026-07-20',
    'jam' => '06:56:25',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  24 => 
  array (
    'id_presensi' => 4587,
    'nis' => 14798,
    'tanggal' => '2026-07-20',
    'jam' => '06:56:26',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  25 => 
  array (
    'id_presensi' => 4588,
    'nis' => 14309,
    'tanggal' => '2026-07-20',
    'jam' => '06:56:30',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  26 => 
  array (
    'id_presensi' => 4589,
    'nis' => 14468,
    'tanggal' => '2026-07-20',
    'jam' => '06:56:33',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  27 => 
  array (
    'id_presensi' => 4590,
    'nis' => 13888,
    'tanggal' => '2026-07-20',
    'jam' => '06:56:35',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  28 => 
  array (
    'id_presensi' => 4591,
    'nis' => 14477,
    'tanggal' => '2026-07-20',
    'jam' => '06:56:38',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  29 => 
  array (
    'id_presensi' => 4592,
    'nis' => 14797,
    'tanggal' => '2026-07-20',
    'jam' => '06:56:46',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  30 => 
  array (
    'id_presensi' => 4593,
    'nis' => 14593,
    'tanggal' => '2026-07-20',
    'jam' => '06:56:48',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  31 => 
  array (
    'id_presensi' => 4594,
    'nis' => 14596,
    'tanggal' => '2026-07-20',
    'jam' => '06:56:51',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  32 => 
  array (
    'id_presensi' => 4595,
    'nis' => 14291,
    'tanggal' => '2026-07-20',
    'jam' => '06:56:52',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  33 => 
  array (
    'id_presensi' => 4596,
    'nis' => 14760,
    'tanggal' => '2026-07-20',
    'jam' => '06:56:56',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  34 => 
  array (
    'id_presensi' => 4597,
    'nis' => 14765,
    'tanggal' => '2026-07-20',
    'jam' => '06:57:00',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  35 => 
  array (
    'id_presensi' => 4598,
    'nis' => 14784,
    'tanggal' => '2026-07-20',
    'jam' => '06:57:02',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  36 => 
  array (
    'id_presensi' => 4599,
    'nis' => 14762,
    'tanggal' => '2026-07-20',
    'jam' => '06:57:13',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  37 => 
  array (
    'id_presensi' => 4600,
    'nis' => 14694,
    'tanggal' => '2026-07-20',
    'jam' => '06:57:33',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  38 => 
  array (
    'id_presensi' => 4601,
    'nis' => 14677,
    'tanggal' => '2026-07-20',
    'jam' => '06:57:37',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  39 => 
  array (
    'id_presensi' => 4602,
    'nis' => 14726,
    'tanggal' => '2026-07-20',
    'jam' => '06:57:40',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  40 => 
  array (
    'id_presensi' => 4603,
    'nis' => 14600,
    'tanggal' => '2026-07-20',
    'jam' => '06:57:42',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  41 => 
  array (
    'id_presensi' => 4604,
    'nis' => 14751,
    'tanggal' => '2026-07-20',
    'jam' => '06:57:45',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  42 => 
  array (
    'id_presensi' => 4605,
    'nis' => 13925,
    'tanggal' => '2026-07-20',
    'jam' => '06:57:45',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  43 => 
  array (
    'id_presensi' => 4606,
    'nis' => 14512,
    'tanggal' => '2026-07-20',
    'jam' => '06:57:47',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  44 => 
  array (
    'id_presensi' => 4607,
    'nis' => 14372,
    'tanggal' => '2026-07-20',
    'jam' => '06:57:50',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  45 => 
  array (
    'id_presensi' => 4608,
    'nis' => 14238,
    'tanggal' => '2026-07-20',
    'jam' => '06:57:50',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  46 => 
  array (
    'id_presensi' => 4609,
    'nis' => 14248,
    'tanggal' => '2026-07-20',
    'jam' => '06:57:52',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  47 => 
  array (
    'id_presensi' => 4610,
    'nis' => 14296,
    'tanggal' => '2026-07-20',
    'jam' => '06:57:53',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  48 => 
  array (
    'id_presensi' => 4611,
    'nis' => 14312,
    'tanggal' => '2026-07-20',
    'jam' => '06:57:56',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  49 => 
  array (
    'id_presensi' => 4612,
    'nis' => 14603,
    'tanggal' => '2026-07-20',
    'jam' => '06:58:23',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  50 => 
  array (
    'id_presensi' => 4613,
    'nis' => 14792,
    'tanggal' => '2026-07-20',
    'jam' => '06:58:25',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  51 => 
  array (
    'id_presensi' => 4614,
    'nis' => 14323,
    'tanggal' => '2026-07-20',
    'jam' => '06:58:28',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  52 => 
  array (
    'id_presensi' => 4615,
    'nis' => 13881,
    'tanggal' => '2026-07-20',
    'jam' => '06:58:33',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  53 => 
  array (
    'id_presensi' => 4616,
    'nis' => 14802,
    'tanggal' => '2026-07-20',
    'jam' => '06:58:41',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  54 => 
  array (
    'id_presensi' => 4617,
    'nis' => 13882,
    'tanggal' => '2026-07-20',
    'jam' => '06:58:52',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  55 => 
  array (
    'id_presensi' => 4618,
    'nis' => 14325,
    'tanggal' => '2026-07-20',
    'jam' => '06:58:56',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  56 => 
  array (
    'id_presensi' => 4619,
    'nis' => 14511,
    'tanggal' => '2026-07-20',
    'jam' => '06:58:57',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  57 => 
  array (
    'id_presensi' => 4620,
    'nis' => 14443,
    'tanggal' => '2026-07-20',
    'jam' => '06:59:01',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  58 => 
  array (
    'id_presensi' => 4621,
    'nis' => 14598,
    'tanggal' => '2026-07-20',
    'jam' => '06:59:03',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  59 => 
  array (
    'id_presensi' => 4622,
    'nis' => 14579,
    'tanggal' => '2026-07-20',
    'jam' => '06:59:08',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  60 => 
  array (
    'id_presensi' => 4623,
    'nis' => 14301,
    'tanggal' => '2026-07-20',
    'jam' => '06:59:10',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  61 => 
  array (
    'id_presensi' => 4624,
    'nis' => 14300,
    'tanggal' => '2026-07-20',
    'jam' => '06:59:11',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  62 => 
  array (
    'id_presensi' => 4625,
    'nis' => 14306,
    'tanggal' => '2026-07-20',
    'jam' => '06:59:15',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  63 => 
  array (
    'id_presensi' => 4626,
    'nis' => 14308,
    'tanggal' => '2026-07-20',
    'jam' => '06:59:21',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  64 => 
  array (
    'id_presensi' => 4627,
    'nis' => 14754,
    'tanggal' => '2026-07-20',
    'jam' => '06:59:24',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  65 => 
  array (
    'id_presensi' => 4628,
    'nis' => 13890,
    'tanggal' => '2026-07-20',
    'jam' => '06:59:25',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  66 => 
  array (
    'id_presensi' => 4629,
    'nis' => 14142,
    'tanggal' => '2026-07-20',
    'jam' => '06:59:25',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  67 => 
  array (
    'id_presensi' => 4630,
    'nis' => 14376,
    'tanggal' => '2026-07-20',
    'jam' => '06:59:28',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  68 => 
  array (
    'id_presensi' => 4631,
    'nis' => 14438,
    'tanggal' => '2026-07-20',
    'jam' => '06:59:29',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  69 => 
  array (
    'id_presensi' => 4632,
    'nis' => 14169,
    'tanggal' => '2026-07-20',
    'jam' => '06:59:30',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  70 => 
  array (
    'id_presensi' => 4633,
    'nis' => 14801,
    'tanggal' => '2026-07-20',
    'jam' => '06:59:31',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  71 => 
  array (
    'id_presensi' => 4634,
    'nis' => 14446,
    'tanggal' => '2026-07-20',
    'jam' => '06:59:32',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  72 => 
  array (
    'id_presensi' => 4635,
    'nis' => 14174,
    'tanggal' => '2026-07-20',
    'jam' => '06:59:32',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  73 => 
  array (
    'id_presensi' => 4636,
    'nis' => 13886,
    'tanggal' => '2026-07-20',
    'jam' => '06:59:34',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  74 => 
  array (
    'id_presensi' => 4637,
    'nis' => 14780,
    'tanggal' => '2026-07-20',
    'jam' => '06:59:39',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  75 => 
  array (
    'id_presensi' => 4638,
    'nis' => 14328,
    'tanggal' => '2026-07-20',
    'jam' => '06:59:43',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  76 => 
  array (
    'id_presensi' => 4639,
    'nis' => 14336,
    'tanggal' => '2026-07-20',
    'jam' => '06:59:48',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  77 => 
  array (
    'id_presensi' => 4640,
    'nis' => 14791,
    'tanggal' => '2026-07-20',
    'jam' => '06:59:48',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  78 => 
  array (
    'id_presensi' => 4641,
    'nis' => 14676,
    'tanggal' => '2026-07-20',
    'jam' => '06:59:51',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  79 => 
  array (
    'id_presensi' => 4642,
    'nis' => 14799,
    'tanggal' => '2026-07-20',
    'jam' => '06:59:51',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  80 => 
  array (
    'id_presensi' => 4643,
    'nis' => 14794,
    'tanggal' => '2026-07-20',
    'jam' => '06:59:53',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  81 => 
  array (
    'id_presensi' => 4644,
    'nis' => 14700,
    'tanggal' => '2026-07-20',
    'jam' => '06:59:54',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  82 => 
  array (
    'id_presensi' => 4645,
    'nis' => 14704,
    'tanggal' => '2026-07-20',
    'jam' => '06:59:58',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  83 => 
  array (
    'id_presensi' => 4646,
    'nis' => 14683,
    'tanggal' => '2026-07-20',
    'jam' => '07:00:00',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  84 => 
  array (
    'id_presensi' => 4647,
    'nis' => 14319,
    'tanggal' => '2026-07-20',
    'jam' => '07:00:00',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  85 => 
  array (
    'id_presensi' => 4648,
    'nis' => 14686,
    'tanggal' => '2026-07-20',
    'jam' => '07:00:01',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  86 => 
  array (
    'id_presensi' => 4649,
    'nis' => 14173,
    'tanggal' => '2026-07-20',
    'jam' => '07:00:02',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  87 => 
  array (
    'id_presensi' => 4650,
    'nis' => 14160,
    'tanggal' => '2026-07-20',
    'jam' => '07:00:07',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  88 => 
  array (
    'id_presensi' => 4651,
    'nis' => 14698,
    'tanggal' => '2026-07-20',
    'jam' => '07:00:08',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  89 => 
  array (
    'id_presensi' => 4652,
    'nis' => 14164,
    'tanggal' => '2026-07-20',
    'jam' => '07:00:14',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  90 => 
  array (
    'id_presensi' => 4653,
    'nis' => 14790,
    'tanggal' => '2026-07-20',
    'jam' => '07:00:15',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  91 => 
  array (
    'id_presensi' => 4654,
    'nis' => 14161,
    'tanggal' => '2026-07-20',
    'jam' => '07:00:16',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  92 => 
  array (
    'id_presensi' => 4655,
    'nis' => 14472,
    'tanggal' => '2026-07-20',
    'jam' => '07:00:19',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  93 => 
  array (
    'id_presensi' => 4656,
    'nis' => 13987,
    'tanggal' => '2026-07-20',
    'jam' => '07:00:19',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  94 => 
  array (
    'id_presensi' => 4657,
    'nis' => 14158,
    'tanggal' => '2026-07-20',
    'jam' => '07:00:21',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  95 => 
  array (
    'id_presensi' => 4658,
    'nis' => 13870,
    'tanggal' => '2026-07-20',
    'jam' => '07:00:24',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  96 => 
  array (
    'id_presensi' => 4659,
    'nis' => 14217,
    'tanggal' => '2026-07-20',
    'jam' => '07:00:27',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  97 => 
  array (
    'id_presensi' => 4660,
    'nis' => 14330,
    'tanggal' => '2026-07-20',
    'jam' => '07:00:28',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  98 => 
  array (
    'id_presensi' => 4661,
    'nis' => 14471,
    'tanggal' => '2026-07-20',
    'jam' => '07:00:33',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  99 => 
  array (
    'id_presensi' => 4662,
    'nis' => 14332,
    'tanggal' => '2026-07-20',
    'jam' => '07:00:33',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  100 => 
  array (
    'id_presensi' => 4663,
    'nis' => 14162,
    'tanggal' => '2026-07-20',
    'jam' => '07:00:33',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  101 => 
  array (
    'id_presensi' => 4664,
    'nis' => 14755,
    'tanggal' => '2026-07-20',
    'jam' => '07:00:35',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  102 => 
  array (
    'id_presensi' => 4665,
    'nis' => 14685,
    'tanggal' => '2026-07-20',
    'jam' => '07:00:36',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  103 => 
  array (
    'id_presensi' => 4666,
    'nis' => 14716,
    'tanggal' => '2026-07-20',
    'jam' => '07:00:38',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  104 => 
  array (
    'id_presensi' => 4667,
    'nis' => 14470,
    'tanggal' => '2026-07-20',
    'jam' => '07:00:38',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  105 => 
  array (
    'id_presensi' => 4668,
    'nis' => 14457,
    'tanggal' => '2026-07-20',
    'jam' => '07:00:40',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  106 => 
  array (
    'id_presensi' => 4669,
    'nis' => 14338,
    'tanggal' => '2026-07-20',
    'jam' => '07:00:43',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  107 => 
  array (
    'id_presensi' => 4670,
    'nis' => 14305,
    'tanggal' => '2026-07-20',
    'jam' => '07:00:46',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  108 => 
  array (
    'id_presensi' => 4671,
    'nis' => 14758,
    'tanggal' => '2026-07-20',
    'jam' => '07:00:49',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  109 => 
  array (
    'id_presensi' => 4672,
    'nis' => 14494,
    'tanggal' => '2026-07-20',
    'jam' => '07:00:50',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  110 => 
  array (
    'id_presensi' => 4673,
    'nis' => 14242,
    'tanggal' => '2026-07-20',
    'jam' => '07:00:52',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  111 => 
  array (
    'id_presensi' => 4674,
    'nis' => 14503,
    'tanggal' => '2026-07-20',
    'jam' => '07:00:57',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  112 => 
  array (
    'id_presensi' => 4675,
    'nis' => 14219,
    'tanggal' => '2026-07-20',
    'jam' => '07:00:58',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  113 => 
  array (
    'id_presensi' => 4676,
    'nis' => 14465,
    'tanggal' => '2026-07-20',
    'jam' => '07:00:59',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  114 => 
  array (
    'id_presensi' => 4677,
    'nis' => 14337,
    'tanggal' => '2026-07-20',
    'jam' => '07:01:09',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  115 => 
  array (
    'id_presensi' => 4678,
    'nis' => 14299,
    'tanggal' => '2026-07-20',
    'jam' => '07:01:13',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  116 => 
  array (
    'id_presensi' => 4679,
    'nis' => 14738,
    'tanggal' => '2026-07-20',
    'jam' => '07:01:17',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  117 => 
  array (
    'id_presensi' => 4680,
    'nis' => 14313,
    'tanggal' => '2026-07-20',
    'jam' => '07:01:22',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  118 => 
  array (
    'id_presensi' => 4681,
    'nis' => 14293,
    'tanggal' => '2026-07-20',
    'jam' => '07:01:24',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  119 => 
  array (
    'id_presensi' => 4682,
    'nis' => 14156,
    'tanggal' => '2026-07-20',
    'jam' => '07:01:50',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  120 => 
  array (
    'id_presensi' => 4683,
    'nis' => 14487,
    'tanggal' => '2026-07-20',
    'jam' => '07:01:53',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  121 => 
  array (
    'id_presensi' => 4684,
    'nis' => 14148,
    'tanggal' => '2026-07-20',
    'jam' => '07:01:53',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  122 => 
  array (
    'id_presensi' => 4685,
    'nis' => 14592,
    'tanggal' => '2026-07-20',
    'jam' => '07:01:55',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  123 => 
  array (
    'id_presensi' => 4686,
    'nis' => 14289,
    'tanggal' => '2026-07-20',
    'jam' => '07:01:57',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  124 => 
  array (
    'id_presensi' => 4687,
    'nis' => 14314,
    'tanggal' => '2026-07-20',
    'jam' => '07:02:00',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  125 => 
  array (
    'id_presensi' => 4688,
    'nis' => 13878,
    'tanggal' => '2026-07-20',
    'jam' => '07:02:07',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  126 => 
  array (
    'id_presensi' => 4689,
    'nis' => 14789,
    'tanggal' => '2026-07-20',
    'jam' => '07:02:09',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  127 => 
  array (
    'id_presensi' => 4690,
    'nis' => 14369,
    'tanggal' => '2026-07-20',
    'jam' => '07:02:14',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  128 => 
  array (
    'id_presensi' => 4691,
    'nis' => 13885,
    'tanggal' => '2026-07-20',
    'jam' => '07:02:17',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  129 => 
  array (
    'id_presensi' => 4692,
    'nis' => 13862,
    'tanggal' => '2026-07-20',
    'jam' => '07:02:19',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  130 => 
  array (
    'id_presensi' => 4693,
    'nis' => 14510,
    'tanggal' => '2026-07-20',
    'jam' => '07:02:31',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  131 => 
  array (
    'id_presensi' => 4694,
    'nis' => 14367,
    'tanggal' => '2026-07-20',
    'jam' => '07:13:36',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  132 => 
  array (
    'id_presensi' => 4695,
    'nis' => 14144,
    'tanggal' => '2026-07-20',
    'jam' => '07:13:42',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  133 => 
  array (
    'id_presensi' => 4696,
    'nis' => 14172,
    'tanggal' => '2026-07-20',
    'jam' => '07:13:48',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  134 => 
  array (
    'id_presensi' => 4697,
    'nis' => 14236,
    'tanggal' => '2026-07-20',
    'jam' => '07:14:21',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  135 => 
  array (
    'id_presensi' => 4698,
    'nis' => 14246,
    'tanggal' => '2026-07-20',
    'jam' => '07:14:40',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  136 => 
  array (
    'id_presensi' => 4699,
    'nis' => 14226,
    'tanggal' => '2026-07-20',
    'jam' => '07:14:43',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  137 => 
  array (
    'id_presensi' => 4700,
    'nis' => 14237,
    'tanggal' => '2026-07-20',
    'jam' => '07:14:46',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  138 => 
  array (
    'id_presensi' => 4701,
    'nis' => 14223,
    'tanggal' => '2026-07-20',
    'jam' => '07:14:51',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  139 => 
  array (
    'id_presensi' => 4702,
    'nis' => 14244,
    'tanggal' => '2026-07-20',
    'jam' => '07:14:55',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  140 => 
  array (
    'id_presensi' => 4703,
    'nis' => 14224,
    'tanggal' => '2026-07-20',
    'jam' => '07:15:09',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  141 => 
  array (
    'id_presensi' => 4704,
    'nis' => 14220,
    'tanggal' => '2026-07-20',
    'jam' => '07:15:33',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  142 => 
  array (
    'id_presensi' => 4705,
    'nis' => 14261,
    'tanggal' => '2026-07-20',
    'jam' => '07:15:40',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  143 => 
  array (
    'id_presensi' => 4706,
    'nis' => 14232,
    'tanggal' => '2026-07-20',
    'jam' => '07:15:43',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  144 => 
  array (
    'id_presensi' => 4707,
    'nis' => 14233,
    'tanggal' => '2026-07-20',
    'jam' => '07:15:47',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  145 => 
  array (
    'id_presensi' => 4708,
    'nis' => 14482,
    'tanggal' => '2026-07-20',
    'jam' => '07:22:54',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  146 => 
  array (
    'id_presensi' => 4709,
    'nis' => 13923,
    'tanggal' => '2026-07-20',
    'jam' => '07:24:45',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  147 => 
  array (
    'id_presensi' => 4710,
    'nis' => 13914,
    'tanggal' => '2026-07-20',
    'jam' => '07:26:11',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  148 => 
  array (
    'id_presensi' => 4711,
    'nis' => 13904,
    'tanggal' => '2026-07-20',
    'jam' => '07:26:21',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  149 => 
  array (
    'id_presensi' => 4712,
    'nis' => 14216,
    'tanggal' => '2026-07-20',
    'jam' => '07:30:27',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  150 => 
  array (
    'id_presensi' => 4713,
    'nis' => 14287,
    'tanggal' => '2026-07-20',
    'jam' => '07:49:04',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  151 => 
  array (
    'id_presensi' => 4714,
    'nis' => 14304,
    'tanggal' => '2026-07-20',
    'jam' => '07:49:09',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  152 => 
  array (
    'id_presensi' => 4715,
    'nis' => 14334,
    'tanggal' => '2026-07-20',
    'jam' => '07:49:13',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  153 => 
  array (
    'id_presensi' => 4716,
    'nis' => 14684,
    'tanggal' => '2026-07-20',
    'jam' => '07:49:18',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  154 => 
  array (
    'id_presensi' => 4717,
    'nis' => 14697,
    'tanggal' => '2026-07-20',
    'jam' => '07:49:27',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  155 => 
  array (
    'id_presensi' => 4718,
    'nis' => 14464,
    'tanggal' => '2026-07-20',
    'jam' => '08:03:56',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  156 => 
  array (
    'id_presensi' => 4719,
    'nis' => 13909,
    'tanggal' => '2026-07-20',
    'jam' => '09:23:06',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  157 => 
  array (
    'id_presensi' => 4720,
    'nis' => 14146,
    'tanggal' => '2026-07-20',
    'jam' => '09:34:40',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  158 => 
  array (
    'id_presensi' => 4721,
    'nis' => 14153,
    'tanggal' => '2026-07-20',
    'jam' => '09:35:44',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  159 => 
  array (
    'id_presensi' => 4722,
    'nis' => 14154,
    'tanggal' => '2026-07-20',
    'jam' => '09:58:18',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  160 => 
  array (
    'id_presensi' => 4723,
    'nis' => 15382,
    'tanggal' => '2026-07-20',
    'jam' => '10:03:38',
    'status' => '1',
    'keterangan' => NULL,
    'file' => NULL,
  ),
  161 => 
  array (
    'id_presensi' => 4724,
    'nis' => 15383,
    'tanggal' => '2026-07-20',
    'jam' => '10:03:38',
    'status' => '1',
    'keterangan' => NULL,
    'file' => NULL,
  ),
  162 => 
  array (
    'id_presensi' => 4725,
    'nis' => 15384,
    'tanggal' => '2026-07-20',
    'jam' => '10:03:38',
    'status' => '1',
    'keterangan' => NULL,
    'file' => NULL,
  ),
  163 => 
  array (
    'id_presensi' => 4726,
    'nis' => 15385,
    'tanggal' => '2026-07-20',
    'jam' => '10:03:38',
    'status' => '1',
    'keterangan' => NULL,
    'file' => NULL,
  ),
  164 => 
  array (
    'id_presensi' => 4727,
    'nis' => 15386,
    'tanggal' => '2026-07-20',
    'jam' => '10:03:38',
    'status' => '2',
    'keterangan' => 'demam',
    'file' => 'siswa/presensi/fqpk1OYjYhLRdTenGnIxSYxKCDvV98P9vGE2A6Cq.jpg',
  ),
  165 => 
  array (
    'id_presensi' => 4728,
    'nis' => 15387,
    'tanggal' => '2026-07-20',
    'jam' => '10:03:39',
    'status' => '1',
    'keterangan' => NULL,
    'file' => NULL,
  ),
  166 => 
  array (
    'id_presensi' => 4729,
    'nis' => 15388,
    'tanggal' => '2026-07-20',
    'jam' => '10:03:39',
    'status' => '1',
    'keterangan' => NULL,
    'file' => NULL,
  ),
  167 => 
  array (
    'id_presensi' => 4730,
    'nis' => 15389,
    'tanggal' => '2026-07-20',
    'jam' => '10:03:39',
    'status' => '1',
    'keterangan' => NULL,
    'file' => NULL,
  ),
  168 => 
  array (
    'id_presensi' => 4731,
    'nis' => 15390,
    'tanggal' => '2026-07-20',
    'jam' => '10:03:39',
    'status' => '1',
    'keterangan' => NULL,
    'file' => NULL,
  ),
  169 => 
  array (
    'id_presensi' => 4732,
    'nis' => 15391,
    'tanggal' => '2026-07-20',
    'jam' => '10:03:39',
    'status' => '1',
    'keterangan' => NULL,
    'file' => NULL,
  ),
  170 => 
  array (
    'id_presensi' => 4733,
    'nis' => 15392,
    'tanggal' => '2026-07-20',
    'jam' => '10:03:39',
    'status' => '1',
    'keterangan' => NULL,
    'file' => NULL,
  ),
  171 => 
  array (
    'id_presensi' => 4734,
    'nis' => 15393,
    'tanggal' => '2026-07-20',
    'jam' => '10:03:39',
    'status' => '1',
    'keterangan' => NULL,
    'file' => NULL,
  ),
  172 => 
  array (
    'id_presensi' => 4735,
    'nis' => 15394,
    'tanggal' => '2026-07-20',
    'jam' => '10:03:39',
    'status' => '1',
    'keterangan' => NULL,
    'file' => NULL,
  ),
  173 => 
  array (
    'id_presensi' => 4736,
    'nis' => 15395,
    'tanggal' => '2026-07-20',
    'jam' => '10:03:39',
    'status' => '1',
    'keterangan' => NULL,
    'file' => NULL,
  ),
  174 => 
  array (
    'id_presensi' => 4737,
    'nis' => 15396,
    'tanggal' => '2026-07-20',
    'jam' => '10:03:39',
    'status' => '1',
    'keterangan' => NULL,
    'file' => NULL,
  ),
  175 => 
  array (
    'id_presensi' => 4738,
    'nis' => 15397,
    'tanggal' => '2026-07-20',
    'jam' => '10:03:39',
    'status' => '1',
    'keterangan' => NULL,
    'file' => NULL,
  ),
  176 => 
  array (
    'id_presensi' => 4739,
    'nis' => 15398,
    'tanggal' => '2026-07-20',
    'jam' => '10:03:39',
    'status' => '1',
    'keterangan' => NULL,
    'file' => NULL,
  ),
  177 => 
  array (
    'id_presensi' => 4740,
    'nis' => 15399,
    'tanggal' => '2026-07-20',
    'jam' => '10:03:39',
    'status' => '1',
    'keterangan' => NULL,
    'file' => NULL,
  ),
  178 => 
  array (
    'id_presensi' => 4741,
    'nis' => 15400,
    'tanggal' => '2026-07-20',
    'jam' => '10:03:39',
    'status' => '1',
    'keterangan' => NULL,
    'file' => NULL,
  ),
  179 => 
  array (
    'id_presensi' => 4742,
    'nis' => 15401,
    'tanggal' => '2026-07-20',
    'jam' => '10:03:39',
    'status' => '1',
    'keterangan' => NULL,
    'file' => NULL,
  ),
  180 => 
  array (
    'id_presensi' => 4743,
    'nis' => 15402,
    'tanggal' => '2026-07-20',
    'jam' => '10:03:39',
    'status' => '1',
    'keterangan' => NULL,
    'file' => NULL,
  ),
  181 => 
  array (
    'id_presensi' => 4744,
    'nis' => 15403,
    'tanggal' => '2026-07-20',
    'jam' => '10:03:39',
    'status' => '1',
    'keterangan' => NULL,
    'file' => NULL,
  ),
  182 => 
  array (
    'id_presensi' => 4745,
    'nis' => 15404,
    'tanggal' => '2026-07-20',
    'jam' => '10:03:39',
    'status' => '1',
    'keterangan' => NULL,
    'file' => NULL,
  ),
  183 => 
  array (
    'id_presensi' => 4746,
    'nis' => 15405,
    'tanggal' => '2026-07-20',
    'jam' => '10:03:39',
    'status' => '1',
    'keterangan' => NULL,
    'file' => NULL,
  ),
  184 => 
  array (
    'id_presensi' => 4747,
    'nis' => 15406,
    'tanggal' => '2026-07-20',
    'jam' => '10:03:39',
    'status' => '1',
    'keterangan' => NULL,
    'file' => NULL,
  ),
  185 => 
  array (
    'id_presensi' => 4748,
    'nis' => 15407,
    'tanggal' => '2026-07-20',
    'jam' => '10:03:39',
    'status' => '1',
    'keterangan' => NULL,
    'file' => NULL,
  ),
  186 => 
  array (
    'id_presensi' => 4749,
    'nis' => 15408,
    'tanggal' => '2026-07-20',
    'jam' => '10:03:39',
    'status' => '2',
    'keterangan' => 'diare',
    'file' => 'siswa/presensi/zugP6f1iuSIbr9IPpBhH5cUy0hc8omtogCCLtHCt.jpg',
  ),
  187 => 
  array (
    'id_presensi' => 4750,
    'nis' => 15409,
    'tanggal' => '2026-07-20',
    'jam' => '10:03:39',
    'status' => '1',
    'keterangan' => NULL,
    'file' => NULL,
  ),
  188 => 
  array (
    'id_presensi' => 4751,
    'nis' => 15410,
    'tanggal' => '2026-07-20',
    'jam' => '10:03:39',
    'status' => '1',
    'keterangan' => NULL,
    'file' => NULL,
  ),
  189 => 
  array (
    'id_presensi' => 4752,
    'nis' => 15411,
    'tanggal' => '2026-07-20',
    'jam' => '10:03:39',
    'status' => '1',
    'keterangan' => NULL,
    'file' => NULL,
  ),
  190 => 
  array (
    'id_presensi' => 4753,
    'nis' => 15412,
    'tanggal' => '2026-07-20',
    'jam' => '10:03:39',
    'status' => '1',
    'keterangan' => NULL,
    'file' => NULL,
  ),
  191 => 
  array (
    'id_presensi' => 4754,
    'nis' => 15413,
    'tanggal' => '2026-07-20',
    'jam' => '10:03:39',
    'status' => '1',
    'keterangan' => NULL,
    'file' => NULL,
  ),
  192 => 
  array (
    'id_presensi' => 4755,
    'nis' => 15414,
    'tanggal' => '2026-07-20',
    'jam' => '10:03:39',
    'status' => '1',
    'keterangan' => NULL,
    'file' => NULL,
  ),
  193 => 
  array (
    'id_presensi' => 4756,
    'nis' => 15415,
    'tanggal' => '2026-07-20',
    'jam' => '10:03:39',
    'status' => '1',
    'keterangan' => NULL,
    'file' => NULL,
  ),
  194 => 
  array (
    'id_presensi' => 4757,
    'nis' => 15416,
    'tanggal' => '2026-07-20',
    'jam' => '10:03:39',
    'status' => '1',
    'keterangan' => NULL,
    'file' => NULL,
  ),
  195 => 
  array (
    'id_presensi' => 4758,
    'nis' => 15417,
    'tanggal' => '2026-07-20',
    'jam' => '10:03:39',
    'status' => '1',
    'keterangan' => NULL,
    'file' => NULL,
  ),
  196 => 
  array (
    'id_presensi' => 4759,
    'nis' => 15418,
    'tanggal' => '2026-07-20',
    'jam' => '10:03:39',
    'status' => '1',
    'keterangan' => NULL,
    'file' => NULL,
  ),
  197 => 
  array (
    'id_presensi' => 4760,
    'nis' => 15419,
    'tanggal' => '2026-07-20',
    'jam' => '10:03:39',
    'status' => '1',
    'keterangan' => NULL,
    'file' => NULL,
  ),
  198 => 
  array (
    'id_presensi' => 4761,
    'nis' => 15420,
    'tanggal' => '2026-07-20',
    'jam' => '10:03:39',
    'status' => '1',
    'keterangan' => NULL,
    'file' => NULL,
  ),
  199 => 
  array (
    'id_presensi' => 4762,
    'nis' => 14490,
    'tanggal' => '2026-07-20',
    'jam' => '10:32:54',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
));

        DB::table('presensi')->insert(array (
  0 => 
  array (
    'id_presensi' => 4763,
    'nis' => 14170,
    'tanggal' => '2026-07-20',
    'jam' => '11:31:59',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  1 => 
  array (
    'id_presensi' => 4764,
    'nis' => 14150,
    'tanggal' => '2026-07-20',
    'jam' => '11:33:03',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  2 => 
  array (
    'id_presensi' => 4765,
    'nis' => 14163,
    'tanggal' => '2026-07-20',
    'jam' => '11:34:00',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  3 => 
  array (
    'id_presensi' => 4766,
    'nis' => 14168,
    'tanggal' => '2026-07-20',
    'jam' => '12:02:20',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  4 => 
  array (
    'id_presensi' => 4767,
    'nis' => 13921,
    'tanggal' => '2026-07-20',
    'jam' => '12:11:19',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  5 => 
  array (
    'id_presensi' => 4768,
    'nis' => 14673,
    'tanggal' => '2026-07-20',
    'jam' => '12:18:20',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  6 => 
  array (
    'id_presensi' => 4769,
    'nis' => 14658,
    'tanggal' => '2026-07-20',
    'jam' => '12:18:22',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  7 => 
  array (
    'id_presensi' => 4770,
    'nis' => 14657,
    'tanggal' => '2026-07-20',
    'jam' => '12:18:26',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  8 => 
  array (
    'id_presensi' => 4771,
    'nis' => 14667,
    'tanggal' => '2026-07-20',
    'jam' => '12:18:28',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  9 => 
  array (
    'id_presensi' => 4772,
    'nis' => 14648,
    'tanggal' => '2026-07-20',
    'jam' => '12:18:33',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  10 => 
  array (
    'id_presensi' => 4773,
    'nis' => 14653,
    'tanggal' => '2026-07-20',
    'jam' => '12:18:35',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  11 => 
  array (
    'id_presensi' => 4774,
    'nis' => 14669,
    'tanggal' => '2026-07-20',
    'jam' => '12:18:46',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  12 => 
  array (
    'id_presensi' => 4775,
    'nis' => 14659,
    'tanggal' => '2026-07-20',
    'jam' => '12:18:53',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  13 => 
  array (
    'id_presensi' => 4776,
    'nis' => 14649,
    'tanggal' => '2026-07-20',
    'jam' => '12:18:55',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  14 => 
  array (
    'id_presensi' => 4777,
    'nis' => 14660,
    'tanggal' => '2026-07-20',
    'jam' => '12:18:57',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  15 => 
  array (
    'id_presensi' => 4778,
    'nis' => 14646,
    'tanggal' => '2026-07-20',
    'jam' => '12:19:00',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  16 => 
  array (
    'id_presensi' => 4779,
    'nis' => 14662,
    'tanggal' => '2026-07-20',
    'jam' => '12:19:02',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  17 => 
  array (
    'id_presensi' => 4780,
    'nis' => 14671,
    'tanggal' => '2026-07-20',
    'jam' => '12:19:04',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  18 => 
  array (
    'id_presensi' => 4781,
    'nis' => 14643,
    'tanggal' => '2026-07-20',
    'jam' => '12:19:06',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  19 => 
  array (
    'id_presensi' => 4782,
    'nis' => 14647,
    'tanggal' => '2026-07-20',
    'jam' => '12:19:09',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  20 => 
  array (
    'id_presensi' => 4783,
    'nis' => 14652,
    'tanggal' => '2026-07-20',
    'jam' => '12:19:12',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  21 => 
  array (
    'id_presensi' => 4784,
    'nis' => 14651,
    'tanggal' => '2026-07-20',
    'jam' => '12:19:18',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  22 => 
  array (
    'id_presensi' => 4785,
    'nis' => 14612,
    'tanggal' => '2026-07-20',
    'jam' => '12:19:32',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  23 => 
  array (
    'id_presensi' => 4786,
    'nis' => 14634,
    'tanggal' => '2026-07-20',
    'jam' => '12:19:35',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  24 => 
  array (
    'id_presensi' => 4787,
    'nis' => 14405,
    'tanggal' => '2026-07-20',
    'jam' => '12:21:40',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  25 => 
  array (
    'id_presensi' => 4788,
    'nis' => 14391,
    'tanggal' => '2026-07-20',
    'jam' => '12:21:42',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  26 => 
  array (
    'id_presensi' => 4789,
    'nis' => 14406,
    'tanggal' => '2026-07-20',
    'jam' => '12:22:03',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  27 => 
  array (
    'id_presensi' => 4790,
    'nis' => 14416,
    'tanggal' => '2026-07-20',
    'jam' => '12:23:17',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  28 => 
  array (
    'id_presensi' => 4791,
    'nis' => 14631,
    'tanggal' => '2026-07-20',
    'jam' => '12:25:28',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  29 => 
  array (
    'id_presensi' => 4792,
    'nis' => 14644,
    'tanggal' => '2026-07-20',
    'jam' => '12:28:27',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  30 => 
  array (
    'id_presensi' => 4793,
    'nis' => 14663,
    'tanggal' => '2026-07-20',
    'jam' => '12:28:30',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  31 => 
  array (
    'id_presensi' => 4794,
    'nis' => 14650,
    'tanggal' => '2026-07-20',
    'jam' => '12:28:32',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  32 => 
  array (
    'id_presensi' => 4795,
    'nis' => 14628,
    'tanggal' => '2026-07-20',
    'jam' => '12:29:05',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  33 => 
  array (
    'id_presensi' => 4796,
    'nis' => 14617,
    'tanggal' => '2026-07-20',
    'jam' => '12:29:27',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  34 => 
  array (
    'id_presensi' => 4797,
    'nis' => 14641,
    'tanggal' => '2026-07-20',
    'jam' => '12:29:38',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  35 => 
  array (
    'id_presensi' => 4798,
    'nis' => 14635,
    'tanggal' => '2026-07-20',
    'jam' => '12:29:42',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  36 => 
  array (
    'id_presensi' => 4799,
    'nis' => 14629,
    'tanggal' => '2026-07-20',
    'jam' => '12:30:05',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  37 => 
  array (
    'id_presensi' => 4800,
    'nis' => 14618,
    'tanggal' => '2026-07-20',
    'jam' => '12:30:07',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  38 => 
  array (
    'id_presensi' => 4801,
    'nis' => 14627,
    'tanggal' => '2026-07-20',
    'jam' => '12:30:10',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  39 => 
  array (
    'id_presensi' => 4802,
    'nis' => 14621,
    'tanggal' => '2026-07-20',
    'jam' => '12:30:15',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  40 => 
  array (
    'id_presensi' => 4803,
    'nis' => 14668,
    'tanggal' => '2026-07-20',
    'jam' => '12:30:20',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  41 => 
  array (
    'id_presensi' => 4804,
    'nis' => 14625,
    'tanggal' => '2026-07-20',
    'jam' => '12:30:56',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  42 => 
  array (
    'id_presensi' => 4805,
    'nis' => 14610,
    'tanggal' => '2026-07-20',
    'jam' => '12:31:15',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  43 => 
  array (
    'id_presensi' => 4806,
    'nis' => 14636,
    'tanggal' => '2026-07-20',
    'jam' => '12:31:20',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  44 => 
  array (
    'id_presensi' => 4807,
    'nis' => 14623,
    'tanggal' => '2026-07-20',
    'jam' => '12:31:45',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  45 => 
  array (
    'id_presensi' => 4808,
    'nis' => 14624,
    'tanggal' => '2026-07-20',
    'jam' => '12:34:28',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  46 => 
  array (
    'id_presensi' => 4809,
    'nis' => 14608,
    'tanggal' => '2026-07-20',
    'jam' => '12:34:45',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  47 => 
  array (
    'id_presensi' => 4810,
    'nis' => 14666,
    'tanggal' => '2026-07-20',
    'jam' => '12:34:48',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  48 => 
  array (
    'id_presensi' => 4811,
    'nis' => 14620,
    'tanggal' => '2026-07-20',
    'jam' => '12:34:53',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  49 => 
  array (
    'id_presensi' => 4812,
    'nis' => 14626,
    'tanggal' => '2026-07-20',
    'jam' => '12:38:19',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  50 => 
  array (
    'id_presensi' => 4813,
    'nis' => 14633,
    'tanggal' => '2026-07-20',
    'jam' => '12:38:24',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  51 => 
  array (
    'id_presensi' => 4814,
    'nis' => 14467,
    'tanggal' => '2026-07-20',
    'jam' => '12:39:18',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  52 => 
  array (
    'id_presensi' => 4815,
    'nis' => 14396,
    'tanggal' => '2026-07-20',
    'jam' => '12:41:58',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  53 => 
  array (
    'id_presensi' => 4816,
    'nis' => 14415,
    'tanggal' => '2026-07-20',
    'jam' => '12:42:01',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  54 => 
  array (
    'id_presensi' => 4817,
    'nis' => 13871,
    'tanggal' => '2026-07-20',
    'jam' => '14:52:25',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  55 => 
  array (
    'id_presensi' => 4818,
    'nis' => 14639,
    'tanggal' => '2026-07-20',
    'jam' => '14:59:23',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  56 => 
  array (
    'id_presensi' => 4819,
    'nis' => 14607,
    'tanggal' => '2026-07-20',
    'jam' => '15:00:47',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  57 => 
  array (
    'id_presensi' => 4820,
    'nis' => 14664,
    'tanggal' => '2026-07-20',
    'jam' => '15:00:52',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  58 => 
  array (
    'id_presensi' => 4821,
    'nis' => 14630,
    'tanggal' => '2026-07-20',
    'jam' => '15:01:33',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  59 => 
  array (
    'id_presensi' => 4822,
    'nis' => 14394,
    'tanggal' => '2026-07-20',
    'jam' => '15:28:00',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  60 => 
  array (
    'id_presensi' => 4823,
    'nis' => 14403,
    'tanggal' => '2026-07-20',
    'jam' => '15:28:03',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  61 => 
  array (
    'id_presensi' => 4824,
    'nis' => 14414,
    'tanggal' => '2026-07-20',
    'jam' => '15:28:21',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  62 => 
  array (
    'id_presensi' => 4825,
    'nis' => 14392,
    'tanggal' => '2026-07-20',
    'jam' => '15:28:54',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  63 => 
  array (
    'id_presensi' => 4826,
    'nis' => 14379,
    'tanggal' => '2026-07-21',
    'jam' => '06:14:39',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  64 => 
  array (
    'id_presensi' => 4827,
    'nis' => 14213,
    'tanggal' => '2026-07-21',
    'jam' => '06:14:50',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  65 => 
  array (
    'id_presensi' => 4828,
    'nis' => 14479,
    'tanggal' => '2026-07-21',
    'jam' => '06:15:29',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  66 => 
  array (
    'id_presensi' => 4829,
    'nis' => 14773,
    'tanggal' => '2026-07-21',
    'jam' => '06:19:35',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  67 => 
  array (
    'id_presensi' => 4830,
    'nis' => 14774,
    'tanggal' => '2026-07-21',
    'jam' => '06:22:26',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  68 => 
  array (
    'id_presensi' => 4831,
    'nis' => 14742,
    'tanggal' => '2026-07-21',
    'jam' => '06:22:35',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  69 => 
  array (
    'id_presensi' => 4832,
    'nis' => 14744,
    'tanggal' => '2026-07-21',
    'jam' => '06:22:41',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  70 => 
  array (
    'id_presensi' => 4833,
    'nis' => 14378,
    'tanggal' => '2026-07-21',
    'jam' => '06:26:50',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  71 => 
  array (
    'id_presensi' => 4834,
    'nis' => 14382,
    'tanggal' => '2026-07-21',
    'jam' => '06:26:54',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  72 => 
  array (
    'id_presensi' => 4835,
    'nis' => 14759,
    'tanggal' => '2026-07-21',
    'jam' => '06:27:41',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  73 => 
  array (
    'id_presensi' => 4836,
    'nis' => 14758,
    'tanggal' => '2026-07-21',
    'jam' => '06:33:23',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  74 => 
  array (
    'id_presensi' => 4837,
    'nis' => 14578,
    'tanggal' => '2026-07-21',
    'jam' => '06:36:14',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  75 => 
  array (
    'id_presensi' => 4838,
    'nis' => 14594,
    'tanggal' => '2026-07-21',
    'jam' => '06:36:17',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  76 => 
  array (
    'id_presensi' => 4839,
    'nis' => 13913,
    'tanggal' => '2026-07-21',
    'jam' => '06:36:37',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  77 => 
  array (
    'id_presensi' => 4840,
    'nis' => 13891,
    'tanggal' => '2026-07-21',
    'jam' => '06:37:45',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  78 => 
  array (
    'id_presensi' => 4841,
    'nis' => 13877,
    'tanggal' => '2026-07-21',
    'jam' => '06:37:49',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  79 => 
  array (
    'id_presensi' => 4842,
    'nis' => 14340,
    'tanggal' => '2026-07-21',
    'jam' => '06:39:19',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  80 => 
  array (
    'id_presensi' => 4843,
    'nis' => 14248,
    'tanggal' => '2026-07-21',
    'jam' => '06:39:26',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  81 => 
  array (
    'id_presensi' => 4844,
    'nis' => 14247,
    'tanggal' => '2026-07-21',
    'jam' => '06:39:30',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  82 => 
  array (
    'id_presensi' => 4845,
    'nis' => 14375,
    'tanggal' => '2026-07-21',
    'jam' => '06:39:47',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  83 => 
  array (
    'id_presensi' => 4846,
    'nis' => 14717,
    'tanggal' => '2026-07-21',
    'jam' => '06:40:04',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  84 => 
  array (
    'id_presensi' => 4847,
    'nis' => 14724,
    'tanggal' => '2026-07-21',
    'jam' => '06:40:10',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  85 => 
  array (
    'id_presensi' => 4848,
    'nis' => 14321,
    'tanggal' => '2026-07-21',
    'jam' => '06:40:16',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  86 => 
  array (
    'id_presensi' => 4849,
    'nis' => 14339,
    'tanggal' => '2026-07-21',
    'jam' => '06:40:28',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  87 => 
  array (
    'id_presensi' => 4850,
    'nis' => 14693,
    'tanggal' => '2026-07-21',
    'jam' => '06:42:48',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  88 => 
  array (
    'id_presensi' => 4851,
    'nis' => 14732,
    'tanggal' => '2026-07-21',
    'jam' => '06:43:16',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  89 => 
  array (
    'id_presensi' => 4852,
    'nis' => 14508,
    'tanggal' => '2026-07-21',
    'jam' => '06:43:28',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  90 => 
  array (
    'id_presensi' => 4853,
    'nis' => 14496,
    'tanggal' => '2026-07-21',
    'jam' => '06:43:33',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  91 => 
  array (
    'id_presensi' => 4854,
    'nis' => 13875,
    'tanggal' => '2026-07-21',
    'jam' => '06:43:33',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  92 => 
  array (
    'id_presensi' => 4855,
    'nis' => 14674,
    'tanggal' => '2026-07-21',
    'jam' => '06:43:45',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  93 => 
  array (
    'id_presensi' => 4856,
    'nis' => 14691,
    'tanggal' => '2026-07-21',
    'jam' => '06:43:50',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  94 => 
  array (
    'id_presensi' => 4857,
    'nis' => 13868,
    'tanggal' => '2026-07-21',
    'jam' => '06:45:05',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  95 => 
  array (
    'id_presensi' => 4858,
    'nis' => 14718,
    'tanggal' => '2026-07-21',
    'jam' => '06:45:10',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  96 => 
  array (
    'id_presensi' => 4859,
    'nis' => 14380,
    'tanggal' => '2026-07-21',
    'jam' => '06:45:13',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  97 => 
  array (
    'id_presensi' => 4860,
    'nis' => 14749,
    'tanggal' => '2026-07-21',
    'jam' => '06:45:20',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  98 => 
  array (
    'id_presensi' => 4861,
    'nis' => 14712,
    'tanggal' => '2026-07-21',
    'jam' => '06:46:04',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  99 => 
  array (
    'id_presensi' => 4862,
    'nis' => 14737,
    'tanggal' => '2026-07-21',
    'jam' => '06:46:06',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  100 => 
  array (
    'id_presensi' => 4863,
    'nis' => 14328,
    'tanggal' => '2026-07-21',
    'jam' => '06:46:10',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  101 => 
  array (
    'id_presensi' => 4864,
    'nis' => 14697,
    'tanggal' => '2026-07-21',
    'jam' => '06:46:15',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  102 => 
  array (
    'id_presensi' => 4865,
    'nis' => 14585,
    'tanggal' => '2026-07-21',
    'jam' => '06:46:37',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  103 => 
  array (
    'id_presensi' => 4866,
    'nis' => 14604,
    'tanggal' => '2026-07-21',
    'jam' => '06:46:40',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  104 => 
  array (
    'id_presensi' => 4867,
    'nis' => 14769,
    'tanggal' => '2026-07-21',
    'jam' => '06:46:49',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  105 => 
  array (
    'id_presensi' => 4868,
    'nis' => 14386,
    'tanggal' => '2026-07-21',
    'jam' => '06:46:52',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  106 => 
  array (
    'id_presensi' => 4869,
    'nis' => 14150,
    'tanggal' => '2026-07-21',
    'jam' => '06:47:00',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  107 => 
  array (
    'id_presensi' => 4870,
    'nis' => 14385,
    'tanggal' => '2026-07-21',
    'jam' => '06:47:37',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  108 => 
  array (
    'id_presensi' => 4871,
    'nis' => 14238,
    'tanggal' => '2026-07-21',
    'jam' => '06:47:51',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  109 => 
  array (
    'id_presensi' => 4872,
    'nis' => 14701,
    'tanggal' => '2026-07-21',
    'jam' => '06:47:52',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  110 => 
  array (
    'id_presensi' => 4873,
    'nis' => 14217,
    'tanggal' => '2026-07-21',
    'jam' => '06:47:54',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  111 => 
  array (
    'id_presensi' => 4874,
    'nis' => 14434,
    'tanggal' => '2026-07-21',
    'jam' => '06:48:27',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  112 => 
  array (
    'id_presensi' => 4875,
    'nis' => 14709,
    'tanggal' => '2026-07-21',
    'jam' => '06:48:36',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  113 => 
  array (
    'id_presensi' => 4876,
    'nis' => 14715,
    'tanggal' => '2026-07-21',
    'jam' => '06:48:41',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  114 => 
  array (
    'id_presensi' => 4877,
    'nis' => 13966,
    'tanggal' => '2026-07-21',
    'jam' => '06:48:43',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  115 => 
  array (
    'id_presensi' => 4878,
    'nis' => 14290,
    'tanggal' => '2026-07-21',
    'jam' => '06:48:55',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  116 => 
  array (
    'id_presensi' => 4879,
    'nis' => 14302,
    'tanggal' => '2026-07-21',
    'jam' => '06:49:01',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  117 => 
  array (
    'id_presensi' => 4880,
    'nis' => 13906,
    'tanggal' => '2026-07-21',
    'jam' => '06:49:17',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  118 => 
  array (
    'id_presensi' => 4881,
    'nis' => 14002,
    'tanggal' => '2026-07-21',
    'jam' => '06:49:20',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  119 => 
  array (
    'id_presensi' => 4882,
    'nis' => 14752,
    'tanggal' => '2026-07-21',
    'jam' => '06:49:27',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  120 => 
  array (
    'id_presensi' => 4883,
    'nis' => 14456,
    'tanggal' => '2026-07-21',
    'jam' => '06:49:32',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  121 => 
  array (
    'id_presensi' => 4884,
    'nis' => 14469,
    'tanggal' => '2026-07-21',
    'jam' => '06:49:35',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  122 => 
  array (
    'id_presensi' => 4885,
    'nis' => 14588,
    'tanggal' => '2026-07-21',
    'jam' => '06:49:35',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  123 => 
  array (
    'id_presensi' => 4886,
    'nis' => 14593,
    'tanggal' => '2026-07-21',
    'jam' => '06:49:41',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  124 => 
  array (
    'id_presensi' => 4887,
    'nis' => 13874,
    'tanggal' => '2026-07-21',
    'jam' => '06:49:48',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  125 => 
  array (
    'id_presensi' => 4888,
    'nis' => 14331,
    'tanggal' => '2026-07-21',
    'jam' => '06:49:53',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  126 => 
  array (
    'id_presensi' => 4889,
    'nis' => 14711,
    'tanggal' => '2026-07-21',
    'jam' => '06:50:00',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  127 => 
  array (
    'id_presensi' => 4890,
    'nis' => 13862,
    'tanggal' => '2026-07-21',
    'jam' => '06:50:39',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  128 => 
  array (
    'id_presensi' => 4891,
    'nis' => 14511,
    'tanggal' => '2026-07-21',
    'jam' => '06:51:01',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  129 => 
  array (
    'id_presensi' => 4892,
    'nis' => 14507,
    'tanggal' => '2026-07-21',
    'jam' => '06:51:04',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  130 => 
  array (
    'id_presensi' => 4893,
    'nis' => 14336,
    'tanggal' => '2026-07-21',
    'jam' => '06:51:14',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  131 => 
  array (
    'id_presensi' => 4894,
    'nis' => 14318,
    'tanggal' => '2026-07-21',
    'jam' => '06:51:15',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  132 => 
  array (
    'id_presensi' => 4895,
    'nis' => 14435,
    'tanggal' => '2026-07-21',
    'jam' => '06:51:22',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  133 => 
  array (
    'id_presensi' => 4896,
    'nis' => 14437,
    'tanggal' => '2026-07-21',
    'jam' => '06:51:45',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  134 => 
  array (
    'id_presensi' => 4897,
    'nis' => 14805,
    'tanggal' => '2026-07-21',
    'jam' => '06:52:07',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  135 => 
  array (
    'id_presensi' => 4898,
    'nis' => 14695,
    'tanggal' => '2026-07-21',
    'jam' => '06:52:11',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  136 => 
  array (
    'id_presensi' => 4899,
    'nis' => 14770,
    'tanggal' => '2026-07-21',
    'jam' => '06:52:15',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  137 => 
  array (
    'id_presensi' => 4900,
    'nis' => 14705,
    'tanggal' => '2026-07-21',
    'jam' => '06:52:17',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  138 => 
  array (
    'id_presensi' => 4901,
    'nis' => 14682,
    'tanggal' => '2026-07-21',
    'jam' => '06:52:19',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  139 => 
  array (
    'id_presensi' => 4902,
    'nis' => 14034,
    'tanggal' => '2026-07-21',
    'jam' => '06:52:20',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  140 => 
  array (
    'id_presensi' => 4903,
    'nis' => 14023,
    'tanggal' => '2026-07-21',
    'jam' => '06:52:23',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  141 => 
  array (
    'id_presensi' => 4904,
    'nis' => 14492,
    'tanggal' => '2026-07-21',
    'jam' => '06:52:28',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  142 => 
  array (
    'id_presensi' => 4905,
    'nis' => 14169,
    'tanggal' => '2026-07-21',
    'jam' => '06:52:33',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  143 => 
  array (
    'id_presensi' => 4906,
    'nis' => 14235,
    'tanggal' => '2026-07-21',
    'jam' => '06:52:35',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  144 => 
  array (
    'id_presensi' => 4907,
    'nis' => 14239,
    'tanggal' => '2026-07-21',
    'jam' => '06:52:40',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  145 => 
  array (
    'id_presensi' => 4908,
    'nis' => 14721,
    'tanggal' => '2026-07-21',
    'jam' => '06:52:41',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  146 => 
  array (
    'id_presensi' => 4909,
    'nis' => 14173,
    'tanggal' => '2026-07-21',
    'jam' => '06:52:43',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  147 => 
  array (
    'id_presensi' => 4910,
    'nis' => 14734,
    'tanggal' => '2026-07-21',
    'jam' => '06:52:45',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  148 => 
  array (
    'id_presensi' => 4911,
    'nis' => 14164,
    'tanggal' => '2026-07-21',
    'jam' => '06:52:46',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  149 => 
  array (
    'id_presensi' => 4912,
    'nis' => 14463,
    'tanggal' => '2026-07-21',
    'jam' => '06:52:50',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  150 => 
  array (
    'id_presensi' => 4913,
    'nis' => 14778,
    'tanggal' => '2026-07-21',
    'jam' => '06:52:51',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  151 => 
  array (
    'id_presensi' => 4914,
    'nis' => 14333,
    'tanggal' => '2026-07-21',
    'jam' => '06:52:54',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  152 => 
  array (
    'id_presensi' => 4915,
    'nis' => 14461,
    'tanggal' => '2026-07-21',
    'jam' => '06:52:54',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  153 => 
  array (
    'id_presensi' => 4916,
    'nis' => 14579,
    'tanggal' => '2026-07-21',
    'jam' => '06:53:01',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  154 => 
  array (
    'id_presensi' => 4917,
    'nis' => 14581,
    'tanggal' => '2026-07-21',
    'jam' => '06:53:03',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  155 => 
  array (
    'id_presensi' => 4918,
    'nis' => 14582,
    'tanggal' => '2026-07-21',
    'jam' => '06:53:05',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  156 => 
  array (
    'id_presensi' => 4919,
    'nis' => 14689,
    'tanggal' => '2026-07-21',
    'jam' => '06:53:10',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  157 => 
  array (
    'id_presensi' => 4920,
    'nis' => 14688,
    'tanggal' => '2026-07-21',
    'jam' => '06:53:12',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  158 => 
  array (
    'id_presensi' => 4921,
    'nis' => 14466,
    'tanggal' => '2026-07-21',
    'jam' => '06:53:12',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  159 => 
  array (
    'id_presensi' => 4922,
    'nis' => 14454,
    'tanggal' => '2026-07-21',
    'jam' => '06:53:15',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  160 => 
  array (
    'id_presensi' => 4923,
    'nis' => 13876,
    'tanggal' => '2026-07-21',
    'jam' => '06:53:22',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  161 => 
  array (
    'id_presensi' => 4924,
    'nis' => 14600,
    'tanggal' => '2026-07-21',
    'jam' => '06:53:24',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  162 => 
  array (
    'id_presensi' => 4925,
    'nis' => 14424,
    'tanggal' => '2026-07-21',
    'jam' => '06:53:25',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  163 => 
  array (
    'id_presensi' => 4926,
    'nis' => 14420,
    'tanggal' => '2026-07-21',
    'jam' => '06:53:28',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  164 => 
  array (
    'id_presensi' => 4927,
    'nis' => 14710,
    'tanggal' => '2026-07-21',
    'jam' => '06:53:29',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  165 => 
  array (
    'id_presensi' => 4928,
    'nis' => 13866,
    'tanggal' => '2026-07-21',
    'jam' => '06:53:31',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  166 => 
  array (
    'id_presensi' => 4929,
    'nis' => 14425,
    'tanggal' => '2026-07-21',
    'jam' => '06:53:34',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  167 => 
  array (
    'id_presensi' => 4930,
    'nis' => 13864,
    'tanggal' => '2026-07-21',
    'jam' => '06:53:34',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  168 => 
  array (
    'id_presensi' => 4931,
    'nis' => 14431,
    'tanggal' => '2026-07-21',
    'jam' => '06:53:37',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  169 => 
  array (
    'id_presensi' => 4932,
    'nis' => 14011,
    'tanggal' => '2026-07-21',
    'jam' => '06:53:39',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  170 => 
  array (
    'id_presensi' => 4933,
    'nis' => 14428,
    'tanggal' => '2026-07-21',
    'jam' => '06:53:40',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  171 => 
  array (
    'id_presensi' => 4934,
    'nis' => 14491,
    'tanggal' => '2026-07-21',
    'jam' => '06:53:42',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  172 => 
  array (
    'id_presensi' => 4935,
    'nis' => 13978,
    'tanggal' => '2026-07-21',
    'jam' => '06:53:42',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  173 => 
  array (
    'id_presensi' => 4936,
    'nis' => 13973,
    'tanggal' => '2026-07-21',
    'jam' => '06:53:44',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  174 => 
  array (
    'id_presensi' => 4937,
    'nis' => 14215,
    'tanggal' => '2026-07-21',
    'jam' => '06:53:45',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  175 => 
  array (
    'id_presensi' => 4938,
    'nis' => 13971,
    'tanggal' => '2026-07-21',
    'jam' => '06:53:47',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  176 => 
  array (
    'id_presensi' => 4939,
    'nis' => 14294,
    'tanggal' => '2026-07-21',
    'jam' => '06:53:54',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  177 => 
  array (
    'id_presensi' => 4940,
    'nis' => 14223,
    'tanggal' => '2026-07-21',
    'jam' => '06:53:56',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  178 => 
  array (
    'id_presensi' => 4941,
    'nis' => 14297,
    'tanggal' => '2026-07-21',
    'jam' => '06:53:57',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  179 => 
  array (
    'id_presensi' => 4942,
    'nis' => 14236,
    'tanggal' => '2026-07-21',
    'jam' => '06:53:58',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  180 => 
  array (
    'id_presensi' => 4943,
    'nis' => 14595,
    'tanggal' => '2026-07-21',
    'jam' => '06:54:01',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  181 => 
  array (
    'id_presensi' => 4944,
    'nis' => 14032,
    'tanggal' => '2026-07-21',
    'jam' => '06:54:04',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  182 => 
  array (
    'id_presensi' => 4945,
    'nis' => 14305,
    'tanggal' => '2026-07-21',
    'jam' => '06:54:06',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  183 => 
  array (
    'id_presensi' => 4946,
    'nis' => 14713,
    'tanggal' => '2026-07-21',
    'jam' => '06:54:10',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  184 => 
  array (
    'id_presensi' => 4947,
    'nis' => 14730,
    'tanggal' => '2026-07-21',
    'jam' => '06:54:13',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  185 => 
  array (
    'id_presensi' => 4948,
    'nis' => 14427,
    'tanggal' => '2026-07-21',
    'jam' => '06:54:17',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  186 => 
  array (
    'id_presensi' => 4949,
    'nis' => 14445,
    'tanggal' => '2026-07-21',
    'jam' => '06:54:17',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  187 => 
  array (
    'id_presensi' => 4950,
    'nis' => 14728,
    'tanggal' => '2026-07-21',
    'jam' => '06:54:19',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  188 => 
  array (
    'id_presensi' => 4951,
    'nis' => 14433,
    'tanggal' => '2026-07-21',
    'jam' => '06:54:21',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  189 => 
  array (
    'id_presensi' => 4952,
    'nis' => 14442,
    'tanggal' => '2026-07-21',
    'jam' => '06:54:21',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  190 => 
  array (
    'id_presensi' => 4953,
    'nis' => 14439,
    'tanggal' => '2026-07-21',
    'jam' => '06:54:23',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  191 => 
  array (
    'id_presensi' => 4954,
    'nis' => 14292,
    'tanggal' => '2026-07-21',
    'jam' => '06:54:26',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  192 => 
  array (
    'id_presensi' => 4955,
    'nis' => 14485,
    'tanggal' => '2026-07-21',
    'jam' => '06:54:26',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  193 => 
  array (
    'id_presensi' => 4956,
    'nis' => 14486,
    'tanggal' => '2026-07-21',
    'jam' => '06:54:28',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  194 => 
  array (
    'id_presensi' => 4957,
    'nis' => 14288,
    'tanggal' => '2026-07-21',
    'jam' => '06:54:31',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  195 => 
  array (
    'id_presensi' => 4958,
    'nis' => 14298,
    'tanggal' => '2026-07-21',
    'jam' => '06:54:33',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  196 => 
  array (
    'id_presensi' => 4959,
    'nis' => 14679,
    'tanggal' => '2026-07-21',
    'jam' => '06:54:37',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  197 => 
  array (
    'id_presensi' => 4960,
    'nis' => 14736,
    'tanggal' => '2026-07-21',
    'jam' => '06:54:38',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  198 => 
  array (
    'id_presensi' => 4961,
    'nis' => 14490,
    'tanggal' => '2026-07-21',
    'jam' => '06:54:40',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  199 => 
  array (
    'id_presensi' => 4962,
    'nis' => 14722,
    'tanggal' => '2026-07-21',
    'jam' => '06:54:41',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
));

        DB::table('presensi')->insert(array (
  0 => 
  array (
    'id_presensi' => 4963,
    'nis' => 14418,
    'tanggal' => '2026-07-21',
    'jam' => '06:54:43',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  1 => 
  array (
    'id_presensi' => 4964,
    'nis' => 14723,
    'tanggal' => '2026-07-21',
    'jam' => '06:54:44',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  2 => 
  array (
    'id_presensi' => 4965,
    'nis' => 14800,
    'tanggal' => '2026-07-21',
    'jam' => '06:54:46',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  3 => 
  array (
    'id_presensi' => 4966,
    'nis' => 14803,
    'tanggal' => '2026-07-21',
    'jam' => '06:54:48',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  4 => 
  array (
    'id_presensi' => 4967,
    'nis' => 13968,
    'tanggal' => '2026-07-21',
    'jam' => '06:54:48',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  5 => 
  array (
    'id_presensi' => 4968,
    'nis' => 13974,
    'tanggal' => '2026-07-21',
    'jam' => '06:54:50',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  6 => 
  array (
    'id_presensi' => 4969,
    'nis' => 14782,
    'tanggal' => '2026-07-21',
    'jam' => '06:54:53',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  7 => 
  array (
    'id_presensi' => 4970,
    'nis' => 14785,
    'tanggal' => '2026-07-21',
    'jam' => '06:54:53',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  8 => 
  array (
    'id_presensi' => 4971,
    'nis' => 13897,
    'tanggal' => '2026-07-21',
    'jam' => '06:54:54',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  9 => 
  array (
    'id_presensi' => 4972,
    'nis' => 14735,
    'tanggal' => '2026-07-21',
    'jam' => '06:54:57',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  10 => 
  array (
    'id_presensi' => 4973,
    'nis' => 14798,
    'tanggal' => '2026-07-21',
    'jam' => '06:54:57',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  11 => 
  array (
    'id_presensi' => 4974,
    'nis' => 14026,
    'tanggal' => '2026-07-21',
    'jam' => '06:54:57',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  12 => 
  array (
    'id_presensi' => 4975,
    'nis' => 14022,
    'tanggal' => '2026-07-21',
    'jam' => '06:54:59',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  13 => 
  array (
    'id_presensi' => 4976,
    'nis' => 13992,
    'tanggal' => '2026-07-21',
    'jam' => '06:55:00',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  14 => 
  array (
    'id_presensi' => 4977,
    'nis' => 14776,
    'tanggal' => '2026-07-21',
    'jam' => '06:55:00',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  15 => 
  array (
    'id_presensi' => 4978,
    'nis' => 14787,
    'tanggal' => '2026-07-21',
    'jam' => '06:55:03',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  16 => 
  array (
    'id_presensi' => 4979,
    'nis' => 14788,
    'tanggal' => '2026-07-21',
    'jam' => '06:55:05',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  17 => 
  array (
    'id_presensi' => 4980,
    'nis' => 14779,
    'tanggal' => '2026-07-21',
    'jam' => '06:55:07',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  18 => 
  array (
    'id_presensi' => 4981,
    'nis' => 14707,
    'tanggal' => '2026-07-21',
    'jam' => '06:55:08',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  19 => 
  array (
    'id_presensi' => 4982,
    'nis' => 14796,
    'tanggal' => '2026-07-21',
    'jam' => '06:55:09',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  20 => 
  array (
    'id_presensi' => 4983,
    'nis' => 13980,
    'tanggal' => '2026-07-21',
    'jam' => '06:55:11',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  21 => 
  array (
    'id_presensi' => 4984,
    'nis' => 14793,
    'tanggal' => '2026-07-21',
    'jam' => '06:55:13',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  22 => 
  array (
    'id_presensi' => 4985,
    'nis' => 14767,
    'tanggal' => '2026-07-21',
    'jam' => '06:55:13',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  23 => 
  array (
    'id_presensi' => 4986,
    'nis' => 14754,
    'tanggal' => '2026-07-21',
    'jam' => '06:55:15',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  24 => 
  array (
    'id_presensi' => 4987,
    'nis' => 14760,
    'tanggal' => '2026-07-21',
    'jam' => '06:55:20',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  25 => 
  array (
    'id_presensi' => 4988,
    'nis' => 14308,
    'tanggal' => '2026-07-21',
    'jam' => '06:55:20',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  26 => 
  array (
    'id_presensi' => 4989,
    'nis' => 14753,
    'tanggal' => '2026-07-21',
    'jam' => '06:55:23',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  27 => 
  array (
    'id_presensi' => 4990,
    'nis' => 14784,
    'tanggal' => '2026-07-21',
    'jam' => '06:55:24',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  28 => 
  array (
    'id_presensi' => 4991,
    'nis' => 14301,
    'tanggal' => '2026-07-21',
    'jam' => '06:55:26',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  29 => 
  array (
    'id_presensi' => 4992,
    'nis' => 14745,
    'tanggal' => '2026-07-21',
    'jam' => '06:55:29',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  30 => 
  array (
    'id_presensi' => 4993,
    'nis' => 14756,
    'tanggal' => '2026-07-21',
    'jam' => '06:55:32',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  31 => 
  array (
    'id_presensi' => 4994,
    'nis' => 14242,
    'tanggal' => '2026-07-21',
    'jam' => '06:55:35',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  32 => 
  array (
    'id_presensi' => 4995,
    'nis' => 14214,
    'tanggal' => '2026-07-21',
    'jam' => '06:55:36',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  33 => 
  array (
    'id_presensi' => 4996,
    'nis' => 14231,
    'tanggal' => '2026-07-21',
    'jam' => '06:55:37',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  34 => 
  array (
    'id_presensi' => 4997,
    'nis' => 14338,
    'tanggal' => '2026-07-21',
    'jam' => '06:55:38',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  35 => 
  array (
    'id_presensi' => 4998,
    'nis' => 14699,
    'tanggal' => '2026-07-21',
    'jam' => '06:55:40',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  36 => 
  array (
    'id_presensi' => 4999,
    'nis' => 14261,
    'tanggal' => '2026-07-21',
    'jam' => '06:55:41',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  37 => 
  array (
    'id_presensi' => 5000,
    'nis' => 14601,
    'tanggal' => '2026-07-21',
    'jam' => '06:55:41',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  38 => 
  array (
    'id_presensi' => 5001,
    'nis' => 14675,
    'tanggal' => '2026-07-21',
    'jam' => '06:55:42',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  39 => 
  array (
    'id_presensi' => 5002,
    'nis' => 13879,
    'tanggal' => '2026-07-21',
    'jam' => '06:55:44',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  40 => 
  array (
    'id_presensi' => 5003,
    'nis' => 14509,
    'tanggal' => '2026-07-21',
    'jam' => '06:55:49',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  41 => 
  array (
    'id_presensi' => 5004,
    'nis' => 13984,
    'tanggal' => '2026-07-21',
    'jam' => '06:55:50',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  42 => 
  array (
    'id_presensi' => 5005,
    'nis' => 14131,
    'tanggal' => '2026-07-21',
    'jam' => '06:55:52',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  43 => 
  array (
    'id_presensi' => 5006,
    'nis' => 13997,
    'tanggal' => '2026-07-21',
    'jam' => '06:55:52',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  44 => 
  array (
    'id_presensi' => 5007,
    'nis' => 14777,
    'tanggal' => '2026-07-21',
    'jam' => '06:55:55',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  45 => 
  array (
    'id_presensi' => 5008,
    'nis' => 14696,
    'tanggal' => '2026-07-21',
    'jam' => '06:55:56',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  46 => 
  array (
    'id_presensi' => 5009,
    'nis' => 14430,
    'tanggal' => '2026-07-21',
    'jam' => '06:55:59',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  47 => 
  array (
    'id_presensi' => 5010,
    'nis' => 14441,
    'tanggal' => '2026-07-21',
    'jam' => '06:56:01',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  48 => 
  array (
    'id_presensi' => 5011,
    'nis' => 14483,
    'tanggal' => '2026-07-21',
    'jam' => '06:56:04',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  49 => 
  array (
    'id_presensi' => 5012,
    'nis' => 14444,
    'tanggal' => '2026-07-21',
    'jam' => '06:56:04',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  50 => 
  array (
    'id_presensi' => 5013,
    'nis' => 14311,
    'tanggal' => '2026-07-21',
    'jam' => '06:56:07',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  51 => 
  array (
    'id_presensi' => 5014,
    'nis' => 14327,
    'tanggal' => '2026-07-21',
    'jam' => '06:56:08',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  52 => 
  array (
    'id_presensi' => 5015,
    'nis' => 14303,
    'tanggal' => '2026-07-21',
    'jam' => '06:56:10',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  53 => 
  array (
    'id_presensi' => 5016,
    'nis' => 14330,
    'tanggal' => '2026-07-21',
    'jam' => '06:56:10',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  54 => 
  array (
    'id_presensi' => 5017,
    'nis' => 14013,
    'tanggal' => '2026-07-21',
    'jam' => '06:56:11',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  55 => 
  array (
    'id_presensi' => 5018,
    'nis' => 14474,
    'tanggal' => '2026-07-21',
    'jam' => '06:56:13',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  56 => 
  array (
    'id_presensi' => 5019,
    'nis' => 14429,
    'tanggal' => '2026-07-21',
    'jam' => '06:56:14',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  57 => 
  array (
    'id_presensi' => 5020,
    'nis' => 14329,
    'tanggal' => '2026-07-21',
    'jam' => '06:56:14',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  58 => 
  array (
    'id_presensi' => 5021,
    'nis' => 14748,
    'tanggal' => '2026-07-21',
    'jam' => '06:56:16',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  59 => 
  array (
    'id_presensi' => 5022,
    'nis' => 14423,
    'tanggal' => '2026-07-21',
    'jam' => '06:56:18',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  60 => 
  array (
    'id_presensi' => 5023,
    'nis' => 14300,
    'tanggal' => '2026-07-21',
    'jam' => '06:56:19',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  61 => 
  array (
    'id_presensi' => 5024,
    'nis' => 14319,
    'tanggal' => '2026-07-21',
    'jam' => '06:56:20',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  62 => 
  array (
    'id_presensi' => 5025,
    'nis' => 14706,
    'tanggal' => '2026-07-21',
    'jam' => '06:56:21',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  63 => 
  array (
    'id_presensi' => 5026,
    'nis' => 14372,
    'tanggal' => '2026-07-21',
    'jam' => '06:56:23',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  64 => 
  array (
    'id_presensi' => 5027,
    'nis' => 14004,
    'tanggal' => '2026-07-21',
    'jam' => '06:56:25',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  65 => 
  array (
    'id_presensi' => 5028,
    'nis' => 14295,
    'tanggal' => '2026-07-21',
    'jam' => '06:56:26',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  66 => 
  array (
    'id_presensi' => 5029,
    'nis' => 14802,
    'tanggal' => '2026-07-21',
    'jam' => '06:56:27',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  67 => 
  array (
    'id_presensi' => 5030,
    'nis' => 14797,
    'tanggal' => '2026-07-21',
    'jam' => '06:56:28',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  68 => 
  array (
    'id_presensi' => 5031,
    'nis' => 14020,
    'tanggal' => '2026-07-21',
    'jam' => '06:56:31',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  69 => 
  array (
    'id_presensi' => 5032,
    'nis' => 13888,
    'tanggal' => '2026-07-21',
    'jam' => '06:56:31',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  70 => 
  array (
    'id_presensi' => 5033,
    'nis' => 14726,
    'tanggal' => '2026-07-21',
    'jam' => '06:56:34',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  71 => 
  array (
    'id_presensi' => 5034,
    'nis' => 13981,
    'tanggal' => '2026-07-21',
    'jam' => '06:56:36',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  72 => 
  array (
    'id_presensi' => 5035,
    'nis' => 14326,
    'tanggal' => '2026-07-21',
    'jam' => '06:56:41',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  73 => 
  array (
    'id_presensi' => 5036,
    'nis' => 13884,
    'tanggal' => '2026-07-21',
    'jam' => '06:56:43',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  74 => 
  array (
    'id_presensi' => 5037,
    'nis' => 14462,
    'tanggal' => '2026-07-21',
    'jam' => '06:56:48',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  75 => 
  array (
    'id_presensi' => 5038,
    'nis' => 14751,
    'tanggal' => '2026-07-21',
    'jam' => '06:56:48',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  76 => 
  array (
    'id_presensi' => 5039,
    'nis' => 14772,
    'tanggal' => '2026-07-21',
    'jam' => '06:56:50',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  77 => 
  array (
    'id_presensi' => 5040,
    'nis' => 14786,
    'tanggal' => '2026-07-21',
    'jam' => '06:56:52',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  78 => 
  array (
    'id_presensi' => 5041,
    'nis' => 13983,
    'tanggal' => '2026-07-21',
    'jam' => '06:56:52',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  79 => 
  array (
    'id_presensi' => 5042,
    'nis' => 14170,
    'tanggal' => '2026-07-21',
    'jam' => '06:56:54',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  80 => 
  array (
    'id_presensi' => 5043,
    'nis' => 14794,
    'tanggal' => '2026-07-21',
    'jam' => '06:56:54',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  81 => 
  array (
    'id_presensi' => 5044,
    'nis' => 13976,
    'tanggal' => '2026-07-21',
    'jam' => '06:56:54',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  82 => 
  array (
    'id_presensi' => 5045,
    'nis' => 14603,
    'tanggal' => '2026-07-21',
    'jam' => '06:56:59',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  83 => 
  array (
    'id_presensi' => 5046,
    'nis' => 14584,
    'tanggal' => '2026-07-21',
    'jam' => '06:57:03',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  84 => 
  array (
    'id_presensi' => 5047,
    'nis' => 14596,
    'tanggal' => '2026-07-21',
    'jam' => '06:57:10',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  85 => 
  array (
    'id_presensi' => 5048,
    'nis' => 14598,
    'tanggal' => '2026-07-21',
    'jam' => '06:57:14',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  86 => 
  array (
    'id_presensi' => 5049,
    'nis' => 14293,
    'tanggal' => '2026-07-21',
    'jam' => '06:57:20',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  87 => 
  array (
    'id_presensi' => 5050,
    'nis' => 14168,
    'tanggal' => '2026-07-21',
    'jam' => '06:57:22',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  88 => 
  array (
    'id_presensi' => 5051,
    'nis' => 14607,
    'tanggal' => '2026-07-21',
    'jam' => '06:57:23',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  89 => 
  array (
    'id_presensi' => 5052,
    'nis' => 14142,
    'tanggal' => '2026-07-21',
    'jam' => '06:57:24',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  90 => 
  array (
    'id_presensi' => 5053,
    'nis' => 14720,
    'tanggal' => '2026-07-21',
    'jam' => '06:57:26',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  91 => 
  array (
    'id_presensi' => 5054,
    'nis' => 14725,
    'tanggal' => '2026-07-21',
    'jam' => '06:57:28',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  92 => 
  array (
    'id_presensi' => 5055,
    'nis' => 14289,
    'tanggal' => '2026-07-21',
    'jam' => '06:57:35',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  93 => 
  array (
    'id_presensi' => 5056,
    'nis' => 14309,
    'tanggal' => '2026-07-21',
    'jam' => '06:57:38',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  94 => 
  array (
    'id_presensi' => 5057,
    'nis' => 14493,
    'tanggal' => '2026-07-21',
    'jam' => '06:57:44',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  95 => 
  array (
    'id_presensi' => 5058,
    'nis' => 14244,
    'tanggal' => '2026-07-21',
    'jam' => '06:57:45',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  96 => 
  array (
    'id_presensi' => 5059,
    'nis' => 14335,
    'tanggal' => '2026-07-21',
    'jam' => '06:57:48',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  97 => 
  array (
    'id_presensi' => 5060,
    'nis' => 14332,
    'tanggal' => '2026-07-21',
    'jam' => '06:57:50',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  98 => 
  array (
    'id_presensi' => 5061,
    'nis' => 14225,
    'tanggal' => '2026-07-21',
    'jam' => '06:58:13',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  99 => 
  array (
    'id_presensi' => 5062,
    'nis' => 14220,
    'tanggal' => '2026-07-21',
    'jam' => '06:58:17',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  100 => 
  array (
    'id_presensi' => 5063,
    'nis' => 14287,
    'tanggal' => '2026-07-21',
    'jam' => '06:58:18',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  101 => 
  array (
    'id_presensi' => 5064,
    'nis' => 13886,
    'tanggal' => '2026-07-21',
    'jam' => '06:58:20',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  102 => 
  array (
    'id_presensi' => 5065,
    'nis' => 14443,
    'tanggal' => '2026-07-21',
    'jam' => '06:58:21',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  103 => 
  array (
    'id_presensi' => 5066,
    'nis' => 14233,
    'tanggal' => '2026-07-21',
    'jam' => '06:58:23',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  104 => 
  array (
    'id_presensi' => 5067,
    'nis' => 13881,
    'tanggal' => '2026-07-21',
    'jam' => '06:58:24',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  105 => 
  array (
    'id_presensi' => 5068,
    'nis' => 14502,
    'tanggal' => '2026-07-21',
    'jam' => '06:58:26',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  106 => 
  array (
    'id_presensi' => 5069,
    'nis' => 14498,
    'tanggal' => '2026-07-21',
    'jam' => '06:58:28',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  107 => 
  array (
    'id_presensi' => 5070,
    'nis' => 14589,
    'tanggal' => '2026-07-21',
    'jam' => '06:58:31',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  108 => 
  array (
    'id_presensi' => 5071,
    'nis' => 14323,
    'tanggal' => '2026-07-21',
    'jam' => '06:58:35',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  109 => 
  array (
    'id_presensi' => 5072,
    'nis' => 13999,
    'tanggal' => '2026-07-21',
    'jam' => '06:58:37',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  110 => 
  array (
    'id_presensi' => 5073,
    'nis' => 14606,
    'tanggal' => '2026-07-21',
    'jam' => '06:58:40',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  111 => 
  array (
    'id_presensi' => 5074,
    'nis' => 14021,
    'tanggal' => '2026-07-21',
    'jam' => '06:58:41',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  112 => 
  array (
    'id_presensi' => 5075,
    'nis' => 14587,
    'tanggal' => '2026-07-21',
    'jam' => '06:58:45',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  113 => 
  array (
    'id_presensi' => 5076,
    'nis' => 14232,
    'tanggal' => '2026-07-21',
    'jam' => '06:58:47',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  114 => 
  array (
    'id_presensi' => 5077,
    'nis' => 14801,
    'tanggal' => '2026-07-21',
    'jam' => '06:58:48',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  115 => 
  array (
    'id_presensi' => 5078,
    'nis' => 14789,
    'tanggal' => '2026-07-21',
    'jam' => '06:58:50',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  116 => 
  array (
    'id_presensi' => 5079,
    'nis' => 14599,
    'tanggal' => '2026-07-21',
    'jam' => '06:58:54',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  117 => 
  array (
    'id_presensi' => 5080,
    'nis' => 14512,
    'tanggal' => '2026-07-21',
    'jam' => '06:58:57',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  118 => 
  array (
    'id_presensi' => 5081,
    'nis' => 13987,
    'tanggal' => '2026-07-21',
    'jam' => '06:58:58',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  119 => 
  array (
    'id_presensi' => 5082,
    'nis' => 14686,
    'tanggal' => '2026-07-21',
    'jam' => '06:59:01',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  120 => 
  array (
    'id_presensi' => 5083,
    'nis' => 13991,
    'tanggal' => '2026-07-21',
    'jam' => '06:59:01',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  121 => 
  array (
    'id_presensi' => 5084,
    'nis' => 13869,
    'tanggal' => '2026-07-21',
    'jam' => '06:59:03',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  122 => 
  array (
    'id_presensi' => 5085,
    'nis' => 14680,
    'tanggal' => '2026-07-21',
    'jam' => '06:59:04',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  123 => 
  array (
    'id_presensi' => 5086,
    'nis' => 14161,
    'tanggal' => '2026-07-21',
    'jam' => '06:59:06',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  124 => 
  array (
    'id_presensi' => 5087,
    'nis' => 13996,
    'tanggal' => '2026-07-21',
    'jam' => '06:59:07',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  125 => 
  array (
    'id_presensi' => 5088,
    'nis' => 14174,
    'tanggal' => '2026-07-21',
    'jam' => '06:59:09',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  126 => 
  array (
    'id_presensi' => 5089,
    'nis' => 13989,
    'tanggal' => '2026-07-21',
    'jam' => '06:59:10',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  127 => 
  array (
    'id_presensi' => 5090,
    'nis' => 14167,
    'tanggal' => '2026-07-21',
    'jam' => '06:59:11',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  128 => 
  array (
    'id_presensi' => 5091,
    'nis' => 14694,
    'tanggal' => '2026-07-21',
    'jam' => '06:59:12',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  129 => 
  array (
    'id_presensi' => 5092,
    'nis' => 14677,
    'tanggal' => '2026-07-21',
    'jam' => '06:59:14',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  130 => 
  array (
    'id_presensi' => 5093,
    'nis' => 14306,
    'tanggal' => '2026-07-21',
    'jam' => '06:59:15',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  131 => 
  array (
    'id_presensi' => 5094,
    'nis' => 14768,
    'tanggal' => '2026-07-21',
    'jam' => '06:59:17',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  132 => 
  array (
    'id_presensi' => 5095,
    'nis' => 14716,
    'tanggal' => '2026-07-21',
    'jam' => '06:59:17',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  133 => 
  array (
    'id_presensi' => 5096,
    'nis' => 14446,
    'tanggal' => '2026-07-21',
    'jam' => '06:59:20',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  134 => 
  array (
    'id_presensi' => 5097,
    'nis' => 14291,
    'tanggal' => '2026-07-21',
    'jam' => '06:59:22',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  135 => 
  array (
    'id_presensi' => 5098,
    'nis' => 14438,
    'tanggal' => '2026-07-21',
    'jam' => '06:59:23',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  136 => 
  array (
    'id_presensi' => 5099,
    'nis' => 14018,
    'tanggal' => '2026-07-21',
    'jam' => '06:59:26',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  137 => 
  array (
    'id_presensi' => 5100,
    'nis' => 14495,
    'tanggal' => '2026-07-21',
    'jam' => '06:59:29',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  138 => 
  array (
    'id_presensi' => 5101,
    'nis' => 14804,
    'tanggal' => '2026-07-21',
    'jam' => '06:59:35',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  139 => 
  array (
    'id_presensi' => 5102,
    'nis' => 14015,
    'tanggal' => '2026-07-21',
    'jam' => '06:59:35',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  140 => 
  array (
    'id_presensi' => 5103,
    'nis' => 14783,
    'tanggal' => '2026-07-21',
    'jam' => '06:59:37',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  141 => 
  array (
    'id_presensi' => 5104,
    'nis' => 14160,
    'tanggal' => '2026-07-21',
    'jam' => '06:59:52',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  142 => 
  array (
    'id_presensi' => 5105,
    'nis' => 14795,
    'tanggal' => '2026-07-21',
    'jam' => '06:59:58',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  143 => 
  array (
    'id_presensi' => 5106,
    'nis' => 14005,
    'tanggal' => '2026-07-21',
    'jam' => '07:00:04',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  144 => 
  array (
    'id_presensi' => 5107,
    'nis' => 14012,
    'tanggal' => '2026-07-21',
    'jam' => '07:00:06',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  145 => 
  array (
    'id_presensi' => 5108,
    'nis' => 14685,
    'tanggal' => '2026-07-21',
    'jam' => '07:00:08',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  146 => 
  array (
    'id_presensi' => 5109,
    'nis' => 14698,
    'tanggal' => '2026-07-21',
    'jam' => '07:00:11',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  147 => 
  array (
    'id_presensi' => 5110,
    'nis' => 14320,
    'tanggal' => '2026-07-21',
    'jam' => '07:00:18',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  148 => 
  array (
    'id_presensi' => 5111,
    'nis' => 14006,
    'tanggal' => '2026-07-21',
    'jam' => '07:00:19',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  149 => 
  array (
    'id_presensi' => 5112,
    'nis' => 14312,
    'tanggal' => '2026-07-21',
    'jam' => '07:00:21',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  150 => 
  array (
    'id_presensi' => 5113,
    'nis' => 14324,
    'tanggal' => '2026-07-21',
    'jam' => '07:00:23',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  151 => 
  array (
    'id_presensi' => 5114,
    'nis' => 14478,
    'tanggal' => '2026-07-21',
    'jam' => '07:00:24',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  152 => 
  array (
    'id_presensi' => 5115,
    'nis' => 14296,
    'tanggal' => '2026-07-21',
    'jam' => '07:00:25',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  153 => 
  array (
    'id_presensi' => 5116,
    'nis' => 14313,
    'tanggal' => '2026-07-21',
    'jam' => '07:00:26',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  154 => 
  array (
    'id_presensi' => 5117,
    'nis' => 14224,
    'tanggal' => '2026-07-21',
    'jam' => '07:00:29',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  155 => 
  array (
    'id_presensi' => 5118,
    'nis' => 14030,
    'tanggal' => '2026-07-21',
    'jam' => '07:00:31',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  156 => 
  array (
    'id_presensi' => 5119,
    'nis' => 14009,
    'tanggal' => '2026-07-21',
    'jam' => '07:00:33',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  157 => 
  array (
    'id_presensi' => 5120,
    'nis' => 14799,
    'tanggal' => '2026-07-21',
    'jam' => '07:00:38',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  158 => 
  array (
    'id_presensi' => 5121,
    'nis' => 14033,
    'tanggal' => '2026-07-21',
    'jam' => '07:00:41',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  159 => 
  array (
    'id_presensi' => 5122,
    'nis' => 14791,
    'tanggal' => '2026-07-21',
    'jam' => '07:00:45',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  160 => 
  array (
    'id_presensi' => 5123,
    'nis' => 14448,
    'tanggal' => '2026-07-21',
    'jam' => '07:00:47',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  161 => 
  array (
    'id_presensi' => 5124,
    'nis' => 14733,
    'tanggal' => '2026-07-21',
    'jam' => '07:00:52',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  162 => 
  array (
    'id_presensi' => 5125,
    'nis' => 14790,
    'tanggal' => '2026-07-21',
    'jam' => '07:00:53',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  163 => 
  array (
    'id_presensi' => 5126,
    'nis' => 14314,
    'tanggal' => '2026-07-21',
    'jam' => '07:00:54',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  164 => 
  array (
    'id_presensi' => 5127,
    'nis' => 14014,
    'tanggal' => '2026-07-21',
    'jam' => '07:00:55',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  165 => 
  array (
    'id_presensi' => 5128,
    'nis' => 14432,
    'tanggal' => '2026-07-21',
    'jam' => '07:00:55',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  166 => 
  array (
    'id_presensi' => 5129,
    'nis' => 14792,
    'tanggal' => '2026-07-21',
    'jam' => '07:00:57',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  167 => 
  array (
    'id_presensi' => 5130,
    'nis' => 14780,
    'tanggal' => '2026-07-21',
    'jam' => '07:01:07',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  168 => 
  array (
    'id_presensi' => 5131,
    'nis' => 14503,
    'tanggal' => '2026-07-21',
    'jam' => '07:01:18',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  169 => 
  array (
    'id_presensi' => 5132,
    'nis' => 14436,
    'tanggal' => '2026-07-21',
    'jam' => '07:01:21',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  170 => 
  array (
    'id_presensi' => 5133,
    'nis' => 14369,
    'tanggal' => '2026-07-21',
    'jam' => '07:01:24',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  171 => 
  array (
    'id_presensi' => 5134,
    'nis' => 13880,
    'tanggal' => '2026-07-21',
    'jam' => '07:01:25',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  172 => 
  array (
    'id_presensi' => 5135,
    'nis' => 13885,
    'tanggal' => '2026-07-21',
    'jam' => '07:01:26',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  173 => 
  array (
    'id_presensi' => 5136,
    'nis' => 13977,
    'tanggal' => '2026-07-21',
    'jam' => '07:01:30',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  174 => 
  array (
    'id_presensi' => 5137,
    'nis' => 13878,
    'tanggal' => '2026-07-21',
    'jam' => '07:01:31',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  175 => 
  array (
    'id_presensi' => 5138,
    'nis' => 14025,
    'tanggal' => '2026-07-21',
    'jam' => '07:01:33',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  176 => 
  array (
    'id_presensi' => 5139,
    'nis' => 13889,
    'tanggal' => '2026-07-21',
    'jam' => '07:01:33',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  177 => 
  array (
    'id_presensi' => 5140,
    'nis' => 13925,
    'tanggal' => '2026-07-21',
    'jam' => '07:01:34',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  178 => 
  array (
    'id_presensi' => 5141,
    'nis' => 13972,
    'tanggal' => '2026-07-21',
    'jam' => '07:01:36',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  179 => 
  array (
    'id_presensi' => 5142,
    'nis' => 13909,
    'tanggal' => '2026-07-21',
    'jam' => '07:01:36',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  180 => 
  array (
    'id_presensi' => 5143,
    'nis' => 13900,
    'tanggal' => '2026-07-21',
    'jam' => '07:01:41',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  181 => 
  array (
    'id_presensi' => 5144,
    'nis' => 13892,
    'tanggal' => '2026-07-21',
    'jam' => '07:01:42',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  182 => 
  array (
    'id_presensi' => 5145,
    'nis' => 13907,
    'tanggal' => '2026-07-21',
    'jam' => '07:01:43',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  183 => 
  array (
    'id_presensi' => 5146,
    'nis' => 14727,
    'tanggal' => '2026-07-21',
    'jam' => '07:01:45',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  184 => 
  array (
    'id_presensi' => 5147,
    'nis' => 14367,
    'tanggal' => '2026-07-21',
    'jam' => '07:01:47',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  185 => 
  array (
    'id_presensi' => 5148,
    'nis' => 13922,
    'tanggal' => '2026-07-21',
    'jam' => '07:01:48',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  186 => 
  array (
    'id_presensi' => 5149,
    'nis' => 14237,
    'tanggal' => '2026-07-21',
    'jam' => '07:01:51',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  187 => 
  array (
    'id_presensi' => 5150,
    'nis' => 13901,
    'tanggal' => '2026-07-21',
    'jam' => '07:01:52',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  188 => 
  array (
    'id_presensi' => 5151,
    'nis' => 13967,
    'tanggal' => '2026-07-21',
    'jam' => '07:01:54',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  189 => 
  array (
    'id_presensi' => 5152,
    'nis' => 14246,
    'tanggal' => '2026-07-21',
    'jam' => '07:01:54',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  190 => 
  array (
    'id_presensi' => 5153,
    'nis' => 13914,
    'tanggal' => '2026-07-21',
    'jam' => '07:01:54',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  191 => 
  array (
    'id_presensi' => 5154,
    'nis' => 13995,
    'tanggal' => '2026-07-21',
    'jam' => '07:01:56',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  192 => 
  array (
    'id_presensi' => 5155,
    'nis' => 14226,
    'tanggal' => '2026-07-21',
    'jam' => '07:01:56',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  193 => 
  array (
    'id_presensi' => 5156,
    'nis' => 13918,
    'tanggal' => '2026-07-21',
    'jam' => '07:01:57',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  194 => 
  array (
    'id_presensi' => 5157,
    'nis' => 14510,
    'tanggal' => '2026-07-21',
    'jam' => '07:01:59',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  195 => 
  array (
    'id_presensi' => 5158,
    'nis' => 13890,
    'tanggal' => '2026-07-21',
    'jam' => '07:01:59',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  196 => 
  array (
    'id_presensi' => 5159,
    'nis' => 13911,
    'tanggal' => '2026-07-21',
    'jam' => '07:01:59',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  197 => 
  array (
    'id_presensi' => 5160,
    'nis' => 13870,
    'tanggal' => '2026-07-21',
    'jam' => '07:02:01',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  198 => 
  array (
    'id_presensi' => 5161,
    'nis' => 13904,
    'tanggal' => '2026-07-21',
    'jam' => '07:02:02',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  199 => 
  array (
    'id_presensi' => 5162,
    'nis' => 14714,
    'tanggal' => '2026-07-21',
    'jam' => '07:02:03',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
));

        DB::table('presensi')->insert(array (
  0 => 
  array (
    'id_presensi' => 5163,
    'nis' => 13871,
    'tanggal' => '2026-07-21',
    'jam' => '07:02:06',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  1 => 
  array (
    'id_presensi' => 5164,
    'nis' => 14580,
    'tanggal' => '2026-07-21',
    'jam' => '07:02:08',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  2 => 
  array (
    'id_presensi' => 5165,
    'nis' => 14704,
    'tanggal' => '2026-07-21',
    'jam' => '07:02:09',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  3 => 
  array (
    'id_presensi' => 5166,
    'nis' => 14337,
    'tanggal' => '2026-07-21',
    'jam' => '07:02:10',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  4 => 
  array (
    'id_presensi' => 5167,
    'nis' => 14746,
    'tanggal' => '2026-07-21',
    'jam' => '07:02:11',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  5 => 
  array (
    'id_presensi' => 5168,
    'nis' => 14683,
    'tanggal' => '2026-07-21',
    'jam' => '07:02:11',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  6 => 
  array (
    'id_presensi' => 5169,
    'nis' => 13903,
    'tanggal' => '2026-07-21',
    'jam' => '07:02:11',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  7 => 
  array (
    'id_presensi' => 5170,
    'nis' => 14764,
    'tanggal' => '2026-07-21',
    'jam' => '07:02:13',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  8 => 
  array (
    'id_presensi' => 5171,
    'nis' => 14299,
    'tanggal' => '2026-07-21',
    'jam' => '07:02:13',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  9 => 
  array (
    'id_presensi' => 5172,
    'nis' => 13898,
    'tanggal' => '2026-07-21',
    'jam' => '07:02:13',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  10 => 
  array (
    'id_presensi' => 5173,
    'nis' => 14687,
    'tanggal' => '2026-07-21',
    'jam' => '07:02:14',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  11 => 
  array (
    'id_presensi' => 5174,
    'nis' => 13905,
    'tanggal' => '2026-07-21',
    'jam' => '07:02:15',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  12 => 
  array (
    'id_presensi' => 5175,
    'nis' => 14230,
    'tanggal' => '2026-07-21',
    'jam' => '07:02:16',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  13 => 
  array (
    'id_presensi' => 5176,
    'nis' => 14690,
    'tanggal' => '2026-07-21',
    'jam' => '07:02:16',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  14 => 
  array (
    'id_presensi' => 5177,
    'nis' => 14007,
    'tanggal' => '2026-07-21',
    'jam' => '07:02:19',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  15 => 
  array (
    'id_presensi' => 5178,
    'nis' => 13895,
    'tanggal' => '2026-07-21',
    'jam' => '07:02:22',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  16 => 
  array (
    'id_presensi' => 5179,
    'nis' => 13915,
    'tanggal' => '2026-07-21',
    'jam' => '07:02:24',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  17 => 
  array (
    'id_presensi' => 5180,
    'nis' => 14376,
    'tanggal' => '2026-07-21',
    'jam' => '07:02:25',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  18 => 
  array (
    'id_presensi' => 5181,
    'nis' => 14602,
    'tanggal' => '2026-07-21',
    'jam' => '07:02:26',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  19 => 
  array (
    'id_presensi' => 5182,
    'nis' => 13923,
    'tanggal' => '2026-07-21',
    'jam' => '07:02:27',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  20 => 
  array (
    'id_presensi' => 5183,
    'nis' => 13910,
    'tanggal' => '2026-07-21',
    'jam' => '07:02:29',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  21 => 
  array (
    'id_presensi' => 5184,
    'nis' => 14031,
    'tanggal' => '2026-07-21',
    'jam' => '07:02:32',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  22 => 
  array (
    'id_presensi' => 5185,
    'nis' => 13929,
    'tanggal' => '2026-07-21',
    'jam' => '07:02:32',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  23 => 
  array (
    'id_presensi' => 5186,
    'nis' => 14676,
    'tanggal' => '2026-07-21',
    'jam' => '07:02:34',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  24 => 
  array (
    'id_presensi' => 5187,
    'nis' => 14440,
    'tanggal' => '2026-07-21',
    'jam' => '07:02:35',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  25 => 
  array (
    'id_presensi' => 5188,
    'nis' => 13924,
    'tanggal' => '2026-07-21',
    'jam' => '07:02:38',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  26 => 
  array (
    'id_presensi' => 5189,
    'nis' => 14487,
    'tanggal' => '2026-07-21',
    'jam' => '07:02:39',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  27 => 
  array (
    'id_presensi' => 5190,
    'nis' => 14700,
    'tanggal' => '2026-07-21',
    'jam' => '07:02:39',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  28 => 
  array (
    'id_presensi' => 5191,
    'nis' => 14592,
    'tanggal' => '2026-07-21',
    'jam' => '07:02:42',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  29 => 
  array (
    'id_presensi' => 5192,
    'nis' => 13896,
    'tanggal' => '2026-07-21',
    'jam' => '07:02:46',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  30 => 
  array (
    'id_presensi' => 5193,
    'nis' => 14763,
    'tanggal' => '2026-07-21',
    'jam' => '07:11:54',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  31 => 
  array (
    'id_presensi' => 5194,
    'nis' => 14322,
    'tanggal' => '2026-07-21',
    'jam' => '07:11:56',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  32 => 
  array (
    'id_presensi' => 5195,
    'nis' => 14771,
    'tanggal' => '2026-07-21',
    'jam' => '07:11:59',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  33 => 
  array (
    'id_presensi' => 5196,
    'nis' => 14143,
    'tanggal' => '2026-07-21',
    'jam' => '07:16:09',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  34 => 
  array (
    'id_presensi' => 5197,
    'nis' => 14172,
    'tanggal' => '2026-07-21',
    'jam' => '07:16:21',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  35 => 
  array (
    'id_presensi' => 5198,
    'nis' => 14153,
    'tanggal' => '2026-07-21',
    'jam' => '07:16:57',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  36 => 
  array (
    'id_presensi' => 5199,
    'nis' => 14163,
    'tanggal' => '2026-07-21',
    'jam' => '07:17:03',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  37 => 
  array (
    'id_presensi' => 5200,
    'nis' => 14148,
    'tanggal' => '2026-07-21',
    'jam' => '07:17:05',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  38 => 
  array (
    'id_presensi' => 5201,
    'nis' => 14158,
    'tanggal' => '2026-07-21',
    'jam' => '07:17:07',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  39 => 
  array (
    'id_presensi' => 5202,
    'nis' => 14152,
    'tanggal' => '2026-07-21',
    'jam' => '07:17:14',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  40 => 
  array (
    'id_presensi' => 5203,
    'nis' => 14157,
    'tanggal' => '2026-07-21',
    'jam' => '07:17:16',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  41 => 
  array (
    'id_presensi' => 5204,
    'nis' => 13908,
    'tanggal' => '2026-07-21',
    'jam' => '07:25:10',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  42 => 
  array (
    'id_presensi' => 5205,
    'nis' => 13899,
    'tanggal' => '2026-07-21',
    'jam' => '07:25:12',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  43 => 
  array (
    'id_presensi' => 5206,
    'nis' => 13894,
    'tanggal' => '2026-07-21',
    'jam' => '07:29:55',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  44 => 
  array (
    'id_presensi' => 5207,
    'nis' => 13927,
    'tanggal' => '2026-07-21',
    'jam' => '07:30:09',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  45 => 
  array (
    'id_presensi' => 5208,
    'nis' => 13921,
    'tanggal' => '2026-07-21',
    'jam' => '07:32:15',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  46 => 
  array (
    'id_presensi' => 5209,
    'nis' => 14464,
    'tanggal' => '2026-07-21',
    'jam' => '07:36:27',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  47 => 
  array (
    'id_presensi' => 5210,
    'nis' => 14455,
    'tanggal' => '2026-07-21',
    'jam' => '07:36:38',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  48 => 
  array (
    'id_presensi' => 5211,
    'nis' => 14477,
    'tanggal' => '2026-07-21',
    'jam' => '07:36:41',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  49 => 
  array (
    'id_presensi' => 5212,
    'nis' => 14473,
    'tanggal' => '2026-07-21',
    'jam' => '07:37:12',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  50 => 
  array (
    'id_presensi' => 5213,
    'nis' => 14304,
    'tanggal' => '2026-07-21',
    'jam' => '07:44:38',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  51 => 
  array (
    'id_presensi' => 5214,
    'nis' => 14334,
    'tanggal' => '2026-07-21',
    'jam' => '07:44:41',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  52 => 
  array (
    'id_presensi' => 5215,
    'nis' => 14145,
    'tanggal' => '2026-07-21',
    'jam' => '10:00:30',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  53 => 
  array (
    'id_presensi' => 5216,
    'nis' => 13916,
    'tanggal' => '2026-07-21',
    'jam' => '10:29:35',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  54 => 
  array (
    'id_presensi' => 5217,
    'nis' => 14144,
    'tanggal' => '2026-07-21',
    'jam' => '11:35:14',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  55 => 
  array (
    'id_presensi' => 5218,
    'nis' => 14633,
    'tanggal' => '2026-07-21',
    'jam' => '12:12:40',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  56 => 
  array (
    'id_presensi' => 5219,
    'nis' => 14628,
    'tanggal' => '2026-07-21',
    'jam' => '12:13:55',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  57 => 
  array (
    'id_presensi' => 5220,
    'nis' => 14635,
    'tanggal' => '2026-07-21',
    'jam' => '12:15:00',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  58 => 
  array (
    'id_presensi' => 5221,
    'nis' => 14673,
    'tanggal' => '2026-07-21',
    'jam' => '12:16:04',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  59 => 
  array (
    'id_presensi' => 5222,
    'nis' => 14634,
    'tanggal' => '2026-07-21',
    'jam' => '12:16:09',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  60 => 
  array (
    'id_presensi' => 5223,
    'nis' => 14657,
    'tanggal' => '2026-07-21',
    'jam' => '12:16:11',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  61 => 
  array (
    'id_presensi' => 5224,
    'nis' => 14642,
    'tanggal' => '2026-07-21',
    'jam' => '12:16:21',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  62 => 
  array (
    'id_presensi' => 5225,
    'nis' => 14647,
    'tanggal' => '2026-07-21',
    'jam' => '12:16:25',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  63 => 
  array (
    'id_presensi' => 5226,
    'nis' => 14641,
    'tanggal' => '2026-07-21',
    'jam' => '12:16:28',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  64 => 
  array (
    'id_presensi' => 5227,
    'nis' => 14659,
    'tanggal' => '2026-07-21',
    'jam' => '12:16:30',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  65 => 
  array (
    'id_presensi' => 5228,
    'nis' => 14625,
    'tanggal' => '2026-07-21',
    'jam' => '12:16:33',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  66 => 
  array (
    'id_presensi' => 5229,
    'nis' => 14615,
    'tanggal' => '2026-07-21',
    'jam' => '12:16:38',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  67 => 
  array (
    'id_presensi' => 5230,
    'nis' => 14658,
    'tanggal' => '2026-07-21',
    'jam' => '12:16:40',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  68 => 
  array (
    'id_presensi' => 5231,
    'nis' => 14660,
    'tanggal' => '2026-07-21',
    'jam' => '12:16:43',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  69 => 
  array (
    'id_presensi' => 5232,
    'nis' => 14611,
    'tanggal' => '2026-07-21',
    'jam' => '12:16:46',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  70 => 
  array (
    'id_presensi' => 5233,
    'nis' => 14639,
    'tanggal' => '2026-07-21',
    'jam' => '12:16:48',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  71 => 
  array (
    'id_presensi' => 5234,
    'nis' => 14629,
    'tanggal' => '2026-07-21',
    'jam' => '12:16:51',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  72 => 
  array (
    'id_presensi' => 5235,
    'nis' => 14631,
    'tanggal' => '2026-07-21',
    'jam' => '12:18:03',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  73 => 
  array (
    'id_presensi' => 5236,
    'nis' => 14612,
    'tanggal' => '2026-07-21',
    'jam' => '12:20:26',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  74 => 
  array (
    'id_presensi' => 5237,
    'nis' => 14621,
    'tanggal' => '2026-07-21',
    'jam' => '12:22:33',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  75 => 
  array (
    'id_presensi' => 5238,
    'nis' => 14623,
    'tanggal' => '2026-07-21',
    'jam' => '12:22:42',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  76 => 
  array (
    'id_presensi' => 5239,
    'nis' => 14614,
    'tanggal' => '2026-07-21',
    'jam' => '12:23:02',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  77 => 
  array (
    'id_presensi' => 5240,
    'nis' => 14620,
    'tanggal' => '2026-07-21',
    'jam' => '12:23:13',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  78 => 
  array (
    'id_presensi' => 5241,
    'nis' => 14630,
    'tanggal' => '2026-07-21',
    'jam' => '12:23:15',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  79 => 
  array (
    'id_presensi' => 5242,
    'nis' => 14644,
    'tanggal' => '2026-07-21',
    'jam' => '12:23:36',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  80 => 
  array (
    'id_presensi' => 5243,
    'nis' => 14667,
    'tanggal' => '2026-07-21',
    'jam' => '12:23:38',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  81 => 
  array (
    'id_presensi' => 5244,
    'nis' => 14653,
    'tanggal' => '2026-07-21',
    'jam' => '12:24:47',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  82 => 
  array (
    'id_presensi' => 5245,
    'nis' => 14646,
    'tanggal' => '2026-07-21',
    'jam' => '12:24:49',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  83 => 
  array (
    'id_presensi' => 5246,
    'nis' => 14664,
    'tanggal' => '2026-07-21',
    'jam' => '12:24:55',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  84 => 
  array (
    'id_presensi' => 5247,
    'nis' => 14643,
    'tanggal' => '2026-07-21',
    'jam' => '12:24:58',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  85 => 
  array (
    'id_presensi' => 5248,
    'nis' => 14627,
    'tanggal' => '2026-07-21',
    'jam' => '12:25:07',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  86 => 
  array (
    'id_presensi' => 5249,
    'nis' => 14666,
    'tanggal' => '2026-07-21',
    'jam' => '12:25:13',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  87 => 
  array (
    'id_presensi' => 5250,
    'nis' => 14656,
    'tanggal' => '2026-07-21',
    'jam' => '12:25:20',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  88 => 
  array (
    'id_presensi' => 5251,
    'nis' => 14668,
    'tanggal' => '2026-07-21',
    'jam' => '12:25:31',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  89 => 
  array (
    'id_presensi' => 5252,
    'nis' => 14669,
    'tanggal' => '2026-07-21',
    'jam' => '12:25:38',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  90 => 
  array (
    'id_presensi' => 5253,
    'nis' => 14608,
    'tanggal' => '2026-07-21',
    'jam' => '12:26:17',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  91 => 
  array (
    'id_presensi' => 5254,
    'nis' => 14671,
    'tanggal' => '2026-07-21',
    'jam' => '12:28:11',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  92 => 
  array (
    'id_presensi' => 5255,
    'nis' => 14662,
    'tanggal' => '2026-07-21',
    'jam' => '12:28:14',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  93 => 
  array (
    'id_presensi' => 5256,
    'nis' => 14663,
    'tanggal' => '2026-07-21',
    'jam' => '12:29:15',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  94 => 
  array (
    'id_presensi' => 5257,
    'nis' => 14626,
    'tanggal' => '2026-07-21',
    'jam' => '12:29:24',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  95 => 
  array (
    'id_presensi' => 5258,
    'nis' => 14651,
    'tanggal' => '2026-07-21',
    'jam' => '12:30:11',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  96 => 
  array (
    'id_presensi' => 5259,
    'nis' => 14610,
    'tanggal' => '2026-07-21',
    'jam' => '12:30:18',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  97 => 
  array (
    'id_presensi' => 5260,
    'nis' => 14624,
    'tanggal' => '2026-07-21',
    'jam' => '12:30:29',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  98 => 
  array (
    'id_presensi' => 5261,
    'nis' => 14652,
    'tanggal' => '2026-07-21',
    'jam' => '12:30:37',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  99 => 
  array (
    'id_presensi' => 5262,
    'nis' => 14618,
    'tanggal' => '2026-07-21',
    'jam' => '12:30:42',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  100 => 
  array (
    'id_presensi' => 5263,
    'nis' => 14405,
    'tanggal' => '2026-07-21',
    'jam' => '12:34:31',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  101 => 
  array (
    'id_presensi' => 5264,
    'nis' => 14636,
    'tanggal' => '2026-07-21',
    'jam' => '12:34:36',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  102 => 
  array (
    'id_presensi' => 5265,
    'nis' => 14648,
    'tanggal' => '2026-07-21',
    'jam' => '14:50:00',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  103 => 
  array (
    'id_presensi' => 5266,
    'nis' => 14414,
    'tanggal' => '2026-07-21',
    'jam' => '14:50:20',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  104 => 
  array (
    'id_presensi' => 5267,
    'nis' => 14650,
    'tanggal' => '2026-07-21',
    'jam' => '14:57:40',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  105 => 
  array (
    'id_presensi' => 5268,
    'nis' => 14413,
    'tanggal' => '2026-07-21',
    'jam' => '15:26:40',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  106 => 
  array (
    'id_presensi' => 5269,
    'nis' => 14395,
    'tanggal' => '2026-07-21',
    'jam' => '15:26:49',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  107 => 
  array (
    'id_presensi' => 5270,
    'nis' => 14392,
    'tanggal' => '2026-07-21',
    'jam' => '15:27:27',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  108 => 
  array (
    'id_presensi' => 5271,
    'nis' => 14396,
    'tanggal' => '2026-07-21',
    'jam' => '15:27:39',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  109 => 
  array (
    'id_presensi' => 5272,
    'nis' => 14400,
    'tanggal' => '2026-07-21',
    'jam' => '15:27:57',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  110 => 
  array (
    'id_presensi' => 5273,
    'nis' => 14401,
    'tanggal' => '2026-07-21',
    'jam' => '15:28:27',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  111 => 
  array (
    'id_presensi' => 5274,
    'nis' => 14391,
    'tanggal' => '2026-07-21',
    'jam' => '15:28:32',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  112 => 
  array (
    'id_presensi' => 5275,
    'nis' => 14213,
    'tanggal' => '2026-07-22',
    'jam' => '06:13:41',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  113 => 
  array (
    'id_presensi' => 5276,
    'nis' => 14774,
    'tanggal' => '2026-07-22',
    'jam' => '06:20:43',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  114 => 
  array (
    'id_presensi' => 5277,
    'nis' => 14742,
    'tanggal' => '2026-07-22',
    'jam' => '06:20:58',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  115 => 
  array (
    'id_presensi' => 5278,
    'nis' => 14378,
    'tanggal' => '2026-07-22',
    'jam' => '06:27:33',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  116 => 
  array (
    'id_presensi' => 5279,
    'nis' => 14382,
    'tanggal' => '2026-07-22',
    'jam' => '06:27:33',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  117 => 
  array (
    'id_presensi' => 5280,
    'nis' => 14379,
    'tanggal' => '2026-07-22',
    'jam' => '06:29:03',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  118 => 
  array (
    'id_presensi' => 5281,
    'nis' => 14759,
    'tanggal' => '2026-07-22',
    'jam' => '06:32:04',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  119 => 
  array (
    'id_presensi' => 5282,
    'nis' => 14744,
    'tanggal' => '2026-07-22',
    'jam' => '06:32:12',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  120 => 
  array (
    'id_presensi' => 5283,
    'nis' => 14741,
    'tanggal' => '2026-07-22',
    'jam' => '06:32:54',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  121 => 
  array (
    'id_presensi' => 5284,
    'nis' => 13868,
    'tanggal' => '2026-07-22',
    'jam' => '06:35:47',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  122 => 
  array (
    'id_presensi' => 5285,
    'nis' => 14381,
    'tanggal' => '2026-07-22',
    'jam' => '06:37:05',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  123 => 
  array (
    'id_presensi' => 5286,
    'nis' => 13891,
    'tanggal' => '2026-07-22',
    'jam' => '06:37:23',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  124 => 
  array (
    'id_presensi' => 5287,
    'nis' => 14340,
    'tanggal' => '2026-07-22',
    'jam' => '06:37:30',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  125 => 
  array (
    'id_presensi' => 5288,
    'nis' => 14757,
    'tanggal' => '2026-07-22',
    'jam' => '06:37:40',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  126 => 
  array (
    'id_presensi' => 5289,
    'nis' => 14380,
    'tanggal' => '2026-07-22',
    'jam' => '06:38:23',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  127 => 
  array (
    'id_presensi' => 5290,
    'nis' => 14773,
    'tanggal' => '2026-07-22',
    'jam' => '06:38:29',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  128 => 
  array (
    'id_presensi' => 5291,
    'nis' => 14321,
    'tanggal' => '2026-07-22',
    'jam' => '06:39:32',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  129 => 
  array (
    'id_presensi' => 5292,
    'nis' => 14674,
    'tanggal' => '2026-07-22',
    'jam' => '06:40:08',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  130 => 
  array (
    'id_presensi' => 5293,
    'nis' => 14749,
    'tanggal' => '2026-07-22',
    'jam' => '06:40:17',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  131 => 
  array (
    'id_presensi' => 5294,
    'nis' => 14375,
    'tanggal' => '2026-07-22',
    'jam' => '06:40:35',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  132 => 
  array (
    'id_presensi' => 5295,
    'nis' => 14753,
    'tanggal' => '2026-07-22',
    'jam' => '06:41:22',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  133 => 
  array (
    'id_presensi' => 5296,
    'nis' => 14248,
    'tanggal' => '2026-07-22',
    'jam' => '06:42:42',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  134 => 
  array (
    'id_presensi' => 5297,
    'nis' => 14247,
    'tanggal' => '2026-07-22',
    'jam' => '06:42:45',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  135 => 
  array (
    'id_presensi' => 5298,
    'nis' => 14734,
    'tanggal' => '2026-07-22',
    'jam' => '06:43:19',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  136 => 
  array (
    'id_presensi' => 5299,
    'nis' => 14771,
    'tanggal' => '2026-07-22',
    'jam' => '06:43:20',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  137 => 
  array (
    'id_presensi' => 5300,
    'nis' => 14737,
    'tanggal' => '2026-07-22',
    'jam' => '06:43:24',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  138 => 
  array (
    'id_presensi' => 5301,
    'nis' => 14793,
    'tanggal' => '2026-07-22',
    'jam' => '06:43:27',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  139 => 
  array (
    'id_presensi' => 5302,
    'nis' => 14724,
    'tanggal' => '2026-07-22',
    'jam' => '06:43:30',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  140 => 
  array (
    'id_presensi' => 5303,
    'nis' => 14775,
    'tanggal' => '2026-07-22',
    'jam' => '06:43:35',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  141 => 
  array (
    'id_presensi' => 5304,
    'nis' => 14594,
    'tanggal' => '2026-07-22',
    'jam' => '06:44:25',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  142 => 
  array (
    'id_presensi' => 5305,
    'nis' => 14508,
    'tanggal' => '2026-07-22',
    'jam' => '06:45:16',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  143 => 
  array (
    'id_presensi' => 5306,
    'nis' => 14763,
    'tanggal' => '2026-07-22',
    'jam' => '06:46:01',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  144 => 
  array (
    'id_presensi' => 5307,
    'nis' => 14335,
    'tanggal' => '2026-07-22',
    'jam' => '06:46:07',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  145 => 
  array (
    'id_presensi' => 5308,
    'nis' => 13884,
    'tanggal' => '2026-07-22',
    'jam' => '06:46:21',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  146 => 
  array (
    'id_presensi' => 5309,
    'nis' => 13906,
    'tanggal' => '2026-07-22',
    'jam' => '06:46:46',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  147 => 
  array (
    'id_presensi' => 5310,
    'nis' => 13908,
    'tanggal' => '2026-07-22',
    'jam' => '06:46:52',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  148 => 
  array (
    'id_presensi' => 5311,
    'nis' => 14691,
    'tanggal' => '2026-07-22',
    'jam' => '06:47:21',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  149 => 
  array (
    'id_presensi' => 5312,
    'nis' => 13877,
    'tanggal' => '2026-07-22',
    'jam' => '06:47:30',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  150 => 
  array (
    'id_presensi' => 5313,
    'nis' => 13875,
    'tanggal' => '2026-07-22',
    'jam' => '06:47:42',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  151 => 
  array (
    'id_presensi' => 5314,
    'nis' => 14683,
    'tanggal' => '2026-07-22',
    'jam' => '06:47:52',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  152 => 
  array (
    'id_presensi' => 5315,
    'nis' => 14701,
    'tanggal' => '2026-07-22',
    'jam' => '06:47:55',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  153 => 
  array (
    'id_presensi' => 5316,
    'nis' => 13874,
    'tanggal' => '2026-07-22',
    'jam' => '06:47:58',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  154 => 
  array (
    'id_presensi' => 5317,
    'nis' => 14331,
    'tanggal' => '2026-07-22',
    'jam' => '06:48:21',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  155 => 
  array (
    'id_presensi' => 5318,
    'nis' => 14601,
    'tanggal' => '2026-07-22',
    'jam' => '06:48:23',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  156 => 
  array (
    'id_presensi' => 5319,
    'nis' => 14578,
    'tanggal' => '2026-07-22',
    'jam' => '06:48:25',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  157 => 
  array (
    'id_presensi' => 5320,
    'nis' => 14779,
    'tanggal' => '2026-07-22',
    'jam' => '06:48:31',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  158 => 
  array (
    'id_presensi' => 5321,
    'nis' => 14745,
    'tanggal' => '2026-07-22',
    'jam' => '06:48:33',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  159 => 
  array (
    'id_presensi' => 5322,
    'nis' => 14328,
    'tanggal' => '2026-07-22',
    'jam' => '06:48:40',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  160 => 
  array (
    'id_presensi' => 5323,
    'nis' => 14131,
    'tanggal' => '2026-07-22',
    'jam' => '06:48:49',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  161 => 
  array (
    'id_presensi' => 5324,
    'nis' => 14721,
    'tanggal' => '2026-07-22',
    'jam' => '06:48:52',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  162 => 
  array (
    'id_presensi' => 5325,
    'nis' => 14604,
    'tanggal' => '2026-07-22',
    'jam' => '06:48:53',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  163 => 
  array (
    'id_presensi' => 5326,
    'nis' => 14728,
    'tanggal' => '2026-07-22',
    'jam' => '06:48:58',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  164 => 
  array (
    'id_presensi' => 5327,
    'nis' => 14723,
    'tanggal' => '2026-07-22',
    'jam' => '06:49:05',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  165 => 
  array (
    'id_presensi' => 5328,
    'nis' => 14715,
    'tanggal' => '2026-07-22',
    'jam' => '06:49:09',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  166 => 
  array (
    'id_presensi' => 5329,
    'nis' => 14236,
    'tanggal' => '2026-07-22',
    'jam' => '06:49:26',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  167 => 
  array (
    'id_presensi' => 5330,
    'nis' => 14223,
    'tanggal' => '2026-07-22',
    'jam' => '06:49:29',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  168 => 
  array (
    'id_presensi' => 5331,
    'nis' => 14588,
    'tanggal' => '2026-07-22',
    'jam' => '06:49:37',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  169 => 
  array (
    'id_presensi' => 5332,
    'nis' => 14294,
    'tanggal' => '2026-07-22',
    'jam' => '06:49:39',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  170 => 
  array (
    'id_presensi' => 5333,
    'nis' => 14607,
    'tanggal' => '2026-07-22',
    'jam' => '06:49:40',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  171 => 
  array (
    'id_presensi' => 5334,
    'nis' => 14372,
    'tanggal' => '2026-07-22',
    'jam' => '06:49:42',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  172 => 
  array (
    'id_presensi' => 5335,
    'nis' => 14593,
    'tanggal' => '2026-07-22',
    'jam' => '06:49:45',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  173 => 
  array (
    'id_presensi' => 5336,
    'nis' => 14298,
    'tanggal' => '2026-07-22',
    'jam' => '06:49:47',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  174 => 
  array (
    'id_presensi' => 5337,
    'nis' => 14599,
    'tanggal' => '2026-07-22',
    'jam' => '06:49:48',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  175 => 
  array (
    'id_presensi' => 5338,
    'nis' => 14302,
    'tanggal' => '2026-07-22',
    'jam' => '06:50:20',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  176 => 
  array (
    'id_presensi' => 5339,
    'nis' => 14680,
    'tanggal' => '2026-07-22',
    'jam' => '06:50:23',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  177 => 
  array (
    'id_presensi' => 5340,
    'nis' => 14290,
    'tanggal' => '2026-07-22',
    'jam' => '06:50:23',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  178 => 
  array (
    'id_presensi' => 5341,
    'nis' => 14295,
    'tanggal' => '2026-07-22',
    'jam' => '06:50:26',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  179 => 
  array (
    'id_presensi' => 5342,
    'nis' => 14752,
    'tanggal' => '2026-07-22',
    'jam' => '06:50:35',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  180 => 
  array (
    'id_presensi' => 5343,
    'nis' => 14491,
    'tanggal' => '2026-07-22',
    'jam' => '06:50:45',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  181 => 
  array (
    'id_presensi' => 5344,
    'nis' => 14585,
    'tanggal' => '2026-07-22',
    'jam' => '06:50:51',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  182 => 
  array (
    'id_presensi' => 5345,
    'nis' => 13866,
    'tanggal' => '2026-07-22',
    'jam' => '06:51:10',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  183 => 
  array (
    'id_presensi' => 5346,
    'nis' => 14756,
    'tanggal' => '2026-07-22',
    'jam' => '06:51:19',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  184 => 
  array (
    'id_presensi' => 5347,
    'nis' => 14297,
    'tanggal' => '2026-07-22',
    'jam' => '06:51:29',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  185 => 
  array (
    'id_presensi' => 5348,
    'nis' => 14709,
    'tanggal' => '2026-07-22',
    'jam' => '06:51:36',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  186 => 
  array (
    'id_presensi' => 5349,
    'nis' => 13980,
    'tanggal' => '2026-07-22',
    'jam' => '06:51:54',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  187 => 
  array (
    'id_presensi' => 5350,
    'nis' => 13971,
    'tanggal' => '2026-07-22',
    'jam' => '06:51:56',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  188 => 
  array (
    'id_presensi' => 5351,
    'nis' => 13993,
    'tanggal' => '2026-07-22',
    'jam' => '06:51:58',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  189 => 
  array (
    'id_presensi' => 5352,
    'nis' => 14002,
    'tanggal' => '2026-07-22',
    'jam' => '06:52:00',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  190 => 
  array (
    'id_presensi' => 5353,
    'nis' => 14034,
    'tanggal' => '2026-07-22',
    'jam' => '06:52:02',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  191 => 
  array (
    'id_presensi' => 5354,
    'nis' => 13929,
    'tanggal' => '2026-07-22',
    'jam' => '06:52:03',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  192 => 
  array (
    'id_presensi' => 5355,
    'nis' => 14689,
    'tanggal' => '2026-07-22',
    'jam' => '06:52:09',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  193 => 
  array (
    'id_presensi' => 5356,
    'nis' => 13973,
    'tanggal' => '2026-07-22',
    'jam' => '06:52:09',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  194 => 
  array (
    'id_presensi' => 5357,
    'nis' => 14688,
    'tanggal' => '2026-07-22',
    'jam' => '06:52:14',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  195 => 
  array (
    'id_presensi' => 5358,
    'nis' => 14219,
    'tanggal' => '2026-07-22',
    'jam' => '06:52:14',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  196 => 
  array (
    'id_presensi' => 5359,
    'nis' => 14216,
    'tanggal' => '2026-07-22',
    'jam' => '06:52:19',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  197 => 
  array (
    'id_presensi' => 5360,
    'nis' => 14479,
    'tanggal' => '2026-07-22',
    'jam' => '06:52:24',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  198 => 
  array (
    'id_presensi' => 5361,
    'nis' => 14596,
    'tanggal' => '2026-07-22',
    'jam' => '06:52:29',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  199 => 
  array (
    'id_presensi' => 5362,
    'nis' => 14434,
    'tanggal' => '2026-07-22',
    'jam' => '06:52:31',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
));

        DB::table('presensi')->insert(array (
  0 => 
  array (
    'id_presensi' => 5363,
    'nis' => 14747,
    'tanggal' => '2026-07-22',
    'jam' => '06:52:32',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  1 => 
  array (
    'id_presensi' => 5364,
    'nis' => 14447,
    'tanggal' => '2026-07-22',
    'jam' => '06:52:33',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  2 => 
  array (
    'id_presensi' => 5365,
    'nis' => 14705,
    'tanggal' => '2026-07-22',
    'jam' => '06:52:36',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  3 => 
  array (
    'id_presensi' => 5366,
    'nis' => 14676,
    'tanggal' => '2026-07-22',
    'jam' => '06:52:39',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  4 => 
  array (
    'id_presensi' => 5367,
    'nis' => 14333,
    'tanggal' => '2026-07-22',
    'jam' => '06:52:44',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  5 => 
  array (
    'id_presensi' => 5368,
    'nis' => 13968,
    'tanggal' => '2026-07-22',
    'jam' => '06:52:50',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  6 => 
  array (
    'id_presensi' => 5369,
    'nis' => 14778,
    'tanggal' => '2026-07-22',
    'jam' => '06:53:07',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  7 => 
  array (
    'id_presensi' => 5370,
    'nis' => 14435,
    'tanggal' => '2026-07-22',
    'jam' => '06:53:13',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  8 => 
  array (
    'id_presensi' => 5371,
    'nis' => 13978,
    'tanggal' => '2026-07-22',
    'jam' => '06:53:17',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  9 => 
  array (
    'id_presensi' => 5372,
    'nis' => 14305,
    'tanggal' => '2026-07-22',
    'jam' => '06:53:20',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  10 => 
  array (
    'id_presensi' => 5373,
    'nis' => 14712,
    'tanggal' => '2026-07-22',
    'jam' => '06:53:23',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  11 => 
  array (
    'id_presensi' => 5374,
    'nis' => 14711,
    'tanggal' => '2026-07-22',
    'jam' => '06:53:25',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  12 => 
  array (
    'id_presensi' => 5375,
    'nis' => 13869,
    'tanggal' => '2026-07-22',
    'jam' => '06:53:33',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  13 => 
  array (
    'id_presensi' => 5376,
    'nis' => 14730,
    'tanggal' => '2026-07-22',
    'jam' => '06:53:38',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  14 => 
  array (
    'id_presensi' => 5377,
    'nis' => 14454,
    'tanggal' => '2026-07-22',
    'jam' => '06:53:44',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  15 => 
  array (
    'id_presensi' => 5378,
    'nis' => 14456,
    'tanggal' => '2026-07-22',
    'jam' => '06:53:47',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  16 => 
  array (
    'id_presensi' => 5379,
    'nis' => 14465,
    'tanggal' => '2026-07-22',
    'jam' => '06:53:50',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  17 => 
  array (
    'id_presensi' => 5380,
    'nis' => 14292,
    'tanggal' => '2026-07-22',
    'jam' => '06:53:51',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  18 => 
  array (
    'id_presensi' => 5381,
    'nis' => 14316,
    'tanggal' => '2026-07-22',
    'jam' => '06:53:52',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  19 => 
  array (
    'id_presensi' => 5382,
    'nis' => 14286,
    'tanggal' => '2026-07-22',
    'jam' => '06:53:53',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  20 => 
  array (
    'id_presensi' => 5383,
    'nis' => 14437,
    'tanggal' => '2026-07-22',
    'jam' => '06:53:55',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  21 => 
  array (
    'id_presensi' => 5384,
    'nis' => 14336,
    'tanggal' => '2026-07-22',
    'jam' => '06:53:56',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  22 => 
  array (
    'id_presensi' => 5385,
    'nis' => 14787,
    'tanggal' => '2026-07-22',
    'jam' => '06:53:57',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  23 => 
  array (
    'id_presensi' => 5386,
    'nis' => 14696,
    'tanggal' => '2026-07-22',
    'jam' => '06:53:59',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  24 => 
  array (
    'id_presensi' => 5387,
    'nis' => 14796,
    'tanggal' => '2026-07-22',
    'jam' => '06:54:00',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  25 => 
  array (
    'id_presensi' => 5388,
    'nis' => 13927,
    'tanggal' => '2026-07-22',
    'jam' => '06:54:02',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  26 => 
  array (
    'id_presensi' => 5389,
    'nis' => 14784,
    'tanggal' => '2026-07-22',
    'jam' => '06:54:03',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  27 => 
  array (
    'id_presensi' => 5390,
    'nis' => 14788,
    'tanggal' => '2026-07-22',
    'jam' => '06:54:06',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  28 => 
  array (
    'id_presensi' => 5391,
    'nis' => 14679,
    'tanggal' => '2026-07-22',
    'jam' => '06:54:08',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  29 => 
  array (
    'id_presensi' => 5392,
    'nis' => 13900,
    'tanggal' => '2026-07-22',
    'jam' => '06:54:10',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  30 => 
  array (
    'id_presensi' => 5393,
    'nis' => 13902,
    'tanggal' => '2026-07-22',
    'jam' => '06:54:15',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  31 => 
  array (
    'id_presensi' => 5394,
    'nis' => 14327,
    'tanggal' => '2026-07-22',
    'jam' => '06:54:19',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  32 => 
  array (
    'id_presensi' => 5395,
    'nis' => 14318,
    'tanggal' => '2026-07-22',
    'jam' => '06:54:21',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  33 => 
  array (
    'id_presensi' => 5396,
    'nis' => 14329,
    'tanggal' => '2026-07-22',
    'jam' => '06:54:24',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  34 => 
  array (
    'id_presensi' => 5397,
    'nis' => 14424,
    'tanggal' => '2026-07-22',
    'jam' => '06:54:26',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  35 => 
  array (
    'id_presensi' => 5398,
    'nis' => 14339,
    'tanggal' => '2026-07-22',
    'jam' => '06:54:32',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  36 => 
  array (
    'id_presensi' => 5399,
    'nis' => 14287,
    'tanggal' => '2026-07-22',
    'jam' => '06:54:34',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  37 => 
  array (
    'id_presensi' => 5400,
    'nis' => 14423,
    'tanggal' => '2026-07-22',
    'jam' => '06:54:37',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  38 => 
  array (
    'id_presensi' => 5401,
    'nis' => 14238,
    'tanggal' => '2026-07-22',
    'jam' => '06:54:37',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  39 => 
  array (
    'id_presensi' => 5402,
    'nis' => 14217,
    'tanggal' => '2026-07-22',
    'jam' => '06:54:39',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  40 => 
  array (
    'id_presensi' => 5403,
    'nis' => 13913,
    'tanggal' => '2026-07-22',
    'jam' => '06:54:42',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  41 => 
  array (
    'id_presensi' => 5404,
    'nis' => 14296,
    'tanggal' => '2026-07-22',
    'jam' => '06:54:42',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  42 => 
  array (
    'id_presensi' => 5405,
    'nis' => 14428,
    'tanggal' => '2026-07-22',
    'jam' => '06:54:43',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  43 => 
  array (
    'id_presensi' => 5406,
    'nis' => 14802,
    'tanggal' => '2026-07-22',
    'jam' => '06:54:44',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  44 => 
  array (
    'id_presensi' => 5407,
    'nis' => 14430,
    'tanggal' => '2026-07-22',
    'jam' => '06:54:47',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  45 => 
  array (
    'id_presensi' => 5408,
    'nis' => 13990,
    'tanggal' => '2026-07-22',
    'jam' => '06:54:48',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  46 => 
  array (
    'id_presensi' => 5409,
    'nis' => 14032,
    'tanggal' => '2026-07-22',
    'jam' => '06:54:49',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  47 => 
  array (
    'id_presensi' => 5410,
    'nis' => 14431,
    'tanggal' => '2026-07-22',
    'jam' => '06:54:52',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  48 => 
  array (
    'id_presensi' => 5411,
    'nis' => 14495,
    'tanggal' => '2026-07-22',
    'jam' => '06:54:54',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  49 => 
  array (
    'id_presensi' => 5412,
    'nis' => 14291,
    'tanggal' => '2026-07-22',
    'jam' => '06:54:56',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  50 => 
  array (
    'id_presensi' => 5413,
    'nis' => 14009,
    'tanggal' => '2026-07-22',
    'jam' => '06:54:59',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  51 => 
  array (
    'id_presensi' => 5414,
    'nis' => 13974,
    'tanggal' => '2026-07-22',
    'jam' => '06:55:04',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  52 => 
  array (
    'id_presensi' => 5415,
    'nis' => 13911,
    'tanggal' => '2026-07-22',
    'jam' => '06:55:05',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  53 => 
  array (
    'id_presensi' => 5416,
    'nis' => 14301,
    'tanggal' => '2026-07-22',
    'jam' => '06:55:06',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  54 => 
  array (
    'id_presensi' => 5417,
    'nis' => 14288,
    'tanggal' => '2026-07-22',
    'jam' => '06:55:08',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  55 => 
  array (
    'id_presensi' => 5418,
    'nis' => 14224,
    'tanggal' => '2026-07-22',
    'jam' => '06:55:08',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  56 => 
  array (
    'id_presensi' => 5419,
    'nis' => 14308,
    'tanggal' => '2026-07-22',
    'jam' => '06:55:10',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  57 => 
  array (
    'id_presensi' => 5420,
    'nis' => 14502,
    'tanggal' => '2026-07-22',
    'jam' => '06:55:13',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  58 => 
  array (
    'id_presensi' => 5421,
    'nis' => 13898,
    'tanggal' => '2026-07-22',
    'jam' => '06:55:17',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  59 => 
  array (
    'id_presensi' => 5422,
    'nis' => 14492,
    'tanggal' => '2026-07-22',
    'jam' => '06:55:18',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  60 => 
  array (
    'id_presensi' => 5423,
    'nis' => 14755,
    'tanggal' => '2026-07-22',
    'jam' => '06:55:19',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  61 => 
  array (
    'id_presensi' => 5424,
    'nis' => 14786,
    'tanggal' => '2026-07-22',
    'jam' => '06:55:21',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  62 => 
  array (
    'id_presensi' => 5425,
    'nis' => 14760,
    'tanggal' => '2026-07-22',
    'jam' => '06:55:21',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  63 => 
  array (
    'id_presensi' => 5426,
    'nis' => 14782,
    'tanggal' => '2026-07-22',
    'jam' => '06:55:22',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  64 => 
  array (
    'id_presensi' => 5427,
    'nis' => 14777,
    'tanggal' => '2026-07-22',
    'jam' => '06:55:24',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  65 => 
  array (
    'id_presensi' => 5428,
    'nis' => 14794,
    'tanggal' => '2026-07-22',
    'jam' => '06:55:24',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  66 => 
  array (
    'id_presensi' => 5429,
    'nis' => 14445,
    'tanggal' => '2026-07-22',
    'jam' => '06:55:26',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  67 => 
  array (
    'id_presensi' => 5430,
    'nis' => 14804,
    'tanggal' => '2026-07-22',
    'jam' => '06:55:26',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  68 => 
  array (
    'id_presensi' => 5431,
    'nis' => 14803,
    'tanggal' => '2026-07-22',
    'jam' => '06:55:29',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  69 => 
  array (
    'id_presensi' => 5432,
    'nis' => 14785,
    'tanggal' => '2026-07-22',
    'jam' => '06:55:30',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  70 => 
  array (
    'id_presensi' => 5433,
    'nis' => 14419,
    'tanggal' => '2026-07-22',
    'jam' => '06:55:32',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  71 => 
  array (
    'id_presensi' => 5434,
    'nis' => 14433,
    'tanggal' => '2026-07-22',
    'jam' => '06:55:35',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  72 => 
  array (
    'id_presensi' => 5435,
    'nis' => 14439,
    'tanggal' => '2026-07-22',
    'jam' => '06:55:35',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  73 => 
  array (
    'id_presensi' => 5436,
    'nis' => 14425,
    'tanggal' => '2026-07-22',
    'jam' => '06:55:37',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  74 => 
  array (
    'id_presensi' => 5437,
    'nis' => 14332,
    'tanggal' => '2026-07-22',
    'jam' => '06:55:40',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  75 => 
  array (
    'id_presensi' => 5438,
    'nis' => 14800,
    'tanggal' => '2026-07-22',
    'jam' => '06:55:40',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  76 => 
  array (
    'id_presensi' => 5439,
    'nis' => 14798,
    'tanggal' => '2026-07-22',
    'jam' => '06:55:43',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  77 => 
  array (
    'id_presensi' => 5440,
    'nis' => 13901,
    'tanggal' => '2026-07-22',
    'jam' => '06:55:43',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  78 => 
  array (
    'id_presensi' => 5441,
    'nis' => 14587,
    'tanggal' => '2026-07-22',
    'jam' => '06:55:43',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  79 => 
  array (
    'id_presensi' => 5442,
    'nis' => 14427,
    'tanggal' => '2026-07-22',
    'jam' => '06:55:44',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  80 => 
  array (
    'id_presensi' => 5443,
    'nis' => 13904,
    'tanggal' => '2026-07-22',
    'jam' => '06:55:45',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  81 => 
  array (
    'id_presensi' => 5444,
    'nis' => 14461,
    'tanggal' => '2026-07-22',
    'jam' => '06:55:47',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  82 => 
  array (
    'id_presensi' => 5445,
    'nis' => 14706,
    'tanggal' => '2026-07-22',
    'jam' => '06:55:48',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  83 => 
  array (
    'id_presensi' => 5446,
    'nis' => 14600,
    'tanggal' => '2026-07-22',
    'jam' => '06:55:49',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  84 => 
  array (
    'id_presensi' => 5447,
    'nis' => 14707,
    'tanggal' => '2026-07-22',
    'jam' => '06:55:51',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  85 => 
  array (
    'id_presensi' => 5448,
    'nis' => 14579,
    'tanggal' => '2026-07-22',
    'jam' => '06:55:53',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  86 => 
  array (
    'id_presensi' => 5449,
    'nis' => 14735,
    'tanggal' => '2026-07-22',
    'jam' => '06:55:55',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  87 => 
  array (
    'id_presensi' => 5450,
    'nis' => 14496,
    'tanggal' => '2026-07-22',
    'jam' => '06:55:58',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  88 => 
  array (
    'id_presensi' => 5451,
    'nis' => 14772,
    'tanggal' => '2026-07-22',
    'jam' => '06:55:59',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  89 => 
  array (
    'id_presensi' => 5452,
    'nis' => 14338,
    'tanggal' => '2026-07-22',
    'jam' => '06:56:04',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  90 => 
  array (
    'id_presensi' => 5453,
    'nis' => 14690,
    'tanggal' => '2026-07-22',
    'jam' => '06:56:06',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  91 => 
  array (
    'id_presensi' => 5454,
    'nis' => 14228,
    'tanggal' => '2026-07-22',
    'jam' => '06:56:08',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  92 => 
  array (
    'id_presensi' => 5455,
    'nis' => 14026,
    'tanggal' => '2026-07-22',
    'jam' => '06:56:08',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  93 => 
  array (
    'id_presensi' => 5456,
    'nis' => 14698,
    'tanggal' => '2026-07-22',
    'jam' => '06:56:10',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  94 => 
  array (
    'id_presensi' => 5457,
    'nis' => 14022,
    'tanggal' => '2026-07-22',
    'jam' => '06:56:10',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  95 => 
  array (
    'id_presensi' => 5458,
    'nis' => 14795,
    'tanggal' => '2026-07-22',
    'jam' => '06:56:12',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  96 => 
  array (
    'id_presensi' => 5459,
    'nis' => 14725,
    'tanggal' => '2026-07-22',
    'jam' => '06:56:13',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  97 => 
  array (
    'id_presensi' => 5460,
    'nis' => 13996,
    'tanggal' => '2026-07-22',
    'jam' => '06:56:15',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  98 => 
  array (
    'id_presensi' => 5461,
    'nis' => 13999,
    'tanggal' => '2026-07-22',
    'jam' => '06:56:17',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  99 => 
  array (
    'id_presensi' => 5462,
    'nis' => 14021,
    'tanggal' => '2026-07-22',
    'jam' => '06:56:19',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  100 => 
  array (
    'id_presensi' => 5463,
    'nis' => 14011,
    'tanggal' => '2026-07-22',
    'jam' => '06:56:21',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  101 => 
  array (
    'id_presensi' => 5464,
    'nis' => 14006,
    'tanggal' => '2026-07-22',
    'jam' => '06:56:25',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  102 => 
  array (
    'id_presensi' => 5465,
    'nis' => 14029,
    'tanggal' => '2026-07-22',
    'jam' => '06:56:27',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  103 => 
  array (
    'id_presensi' => 5466,
    'nis' => 13883,
    'tanggal' => '2026-07-22',
    'jam' => '06:56:27',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  104 => 
  array (
    'id_presensi' => 5467,
    'nis' => 14758,
    'tanggal' => '2026-07-22',
    'jam' => '06:56:30',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  105 => 
  array (
    'id_presensi' => 5468,
    'nis' => 14020,
    'tanggal' => '2026-07-22',
    'jam' => '06:56:30',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  106 => 
  array (
    'id_presensi' => 5469,
    'nis' => 14768,
    'tanggal' => '2026-07-22',
    'jam' => '06:56:31',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  107 => 
  array (
    'id_presensi' => 5470,
    'nis' => 14016,
    'tanggal' => '2026-07-22',
    'jam' => '06:56:36',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  108 => 
  array (
    'id_presensi' => 5471,
    'nis' => 13876,
    'tanggal' => '2026-07-22',
    'jam' => '06:56:39',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  109 => 
  array (
    'id_presensi' => 5472,
    'nis' => 14512,
    'tanggal' => '2026-07-22',
    'jam' => '06:56:39',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  110 => 
  array (
    'id_presensi' => 5473,
    'nis' => 14695,
    'tanggal' => '2026-07-22',
    'jam' => '06:56:44',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  111 => 
  array (
    'id_presensi' => 5474,
    'nis' => 13977,
    'tanggal' => '2026-07-22',
    'jam' => '06:56:44',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  112 => 
  array (
    'id_presensi' => 5475,
    'nis' => 14306,
    'tanggal' => '2026-07-22',
    'jam' => '06:56:48',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  113 => 
  array (
    'id_presensi' => 5476,
    'nis' => 14595,
    'tanggal' => '2026-07-22',
    'jam' => '06:56:50',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  114 => 
  array (
    'id_presensi' => 5477,
    'nis' => 14448,
    'tanggal' => '2026-07-22',
    'jam' => '06:56:52',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  115 => 
  array (
    'id_presensi' => 5478,
    'nis' => 14300,
    'tanggal' => '2026-07-22',
    'jam' => '06:56:54',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  116 => 
  array (
    'id_presensi' => 5479,
    'nis' => 13881,
    'tanggal' => '2026-07-22',
    'jam' => '06:57:01',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  117 => 
  array (
    'id_presensi' => 5480,
    'nis' => 14589,
    'tanggal' => '2026-07-22',
    'jam' => '06:57:05',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  118 => 
  array (
    'id_presensi' => 5481,
    'nis' => 14738,
    'tanggal' => '2026-07-22',
    'jam' => '06:57:07',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  119 => 
  array (
    'id_presensi' => 5482,
    'nis' => 14704,
    'tanggal' => '2026-07-22',
    'jam' => '06:57:08',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  120 => 
  array (
    'id_presensi' => 5483,
    'nis' => 14603,
    'tanggal' => '2026-07-22',
    'jam' => '06:57:11',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  121 => 
  array (
    'id_presensi' => 5484,
    'nis' => 13925,
    'tanggal' => '2026-07-22',
    'jam' => '06:57:11',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  122 => 
  array (
    'id_presensi' => 5485,
    'nis' => 14694,
    'tanggal' => '2026-07-22',
    'jam' => '06:57:11',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  123 => 
  array (
    'id_presensi' => 5486,
    'nis' => 14142,
    'tanggal' => '2026-07-22',
    'jam' => '06:57:12',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  124 => 
  array (
    'id_presensi' => 5487,
    'nis' => 14584,
    'tanggal' => '2026-07-22',
    'jam' => '06:57:14',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  125 => 
  array (
    'id_presensi' => 5488,
    'nis' => 14376,
    'tanggal' => '2026-07-22',
    'jam' => '06:57:14',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  126 => 
  array (
    'id_presensi' => 5489,
    'nis' => 14313,
    'tanggal' => '2026-07-22',
    'jam' => '06:57:21',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  127 => 
  array (
    'id_presensi' => 5490,
    'nis' => 14751,
    'tanggal' => '2026-07-22',
    'jam' => '06:57:22',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  128 => 
  array (
    'id_presensi' => 5491,
    'nis' => 13922,
    'tanggal' => '2026-07-22',
    'jam' => '06:57:24',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  129 => 
  array (
    'id_presensi' => 5492,
    'nis' => 14478,
    'tanggal' => '2026-07-22',
    'jam' => '06:57:27',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  130 => 
  array (
    'id_presensi' => 5493,
    'nis' => 14311,
    'tanggal' => '2026-07-22',
    'jam' => '06:57:33',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  131 => 
  array (
    'id_presensi' => 5494,
    'nis' => 13981,
    'tanggal' => '2026-07-22',
    'jam' => '06:57:34',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  132 => 
  array (
    'id_presensi' => 5495,
    'nis' => 14303,
    'tanggal' => '2026-07-22',
    'jam' => '06:57:42',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  133 => 
  array (
    'id_presensi' => 5496,
    'nis' => 14677,
    'tanggal' => '2026-07-22',
    'jam' => '06:57:46',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  134 => 
  array (
    'id_presensi' => 5497,
    'nis' => 14797,
    'tanggal' => '2026-07-22',
    'jam' => '06:57:49',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  135 => 
  array (
    'id_presensi' => 5498,
    'nis' => 14452,
    'tanggal' => '2026-07-22',
    'jam' => '06:57:49',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  136 => 
  array (
    'id_presensi' => 5499,
    'nis' => 14606,
    'tanggal' => '2026-07-22',
    'jam' => '06:57:51',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  137 => 
  array (
    'id_presensi' => 5500,
    'nis' => 14583,
    'tanggal' => '2026-07-22',
    'jam' => '06:57:58',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  138 => 
  array (
    'id_presensi' => 5501,
    'nis' => 14474,
    'tanggal' => '2026-07-22',
    'jam' => '06:58:00',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  139 => 
  array (
    'id_presensi' => 5502,
    'nis' => 13915,
    'tanggal' => '2026-07-22',
    'jam' => '06:58:00',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  140 => 
  array (
    'id_presensi' => 5503,
    'nis' => 14033,
    'tanggal' => '2026-07-22',
    'jam' => '06:58:03',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  141 => 
  array (
    'id_presensi' => 5504,
    'nis' => 14466,
    'tanggal' => '2026-07-22',
    'jam' => '06:58:04',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  142 => 
  array (
    'id_presensi' => 5505,
    'nis' => 14418,
    'tanggal' => '2026-07-22',
    'jam' => '06:58:06',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  143 => 
  array (
    'id_presensi' => 5506,
    'nis' => 13997,
    'tanggal' => '2026-07-22',
    'jam' => '06:58:09',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  144 => 
  array (
    'id_presensi' => 5507,
    'nis' => 13895,
    'tanggal' => '2026-07-22',
    'jam' => '06:58:09',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  145 => 
  array (
    'id_presensi' => 5508,
    'nis' => 14722,
    'tanggal' => '2026-07-22',
    'jam' => '06:58:09',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  146 => 
  array (
    'id_presensi' => 5509,
    'nis' => 14462,
    'tanggal' => '2026-07-22',
    'jam' => '06:58:10',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  147 => 
  array (
    'id_presensi' => 5510,
    'nis' => 14330,
    'tanggal' => '2026-07-22',
    'jam' => '06:58:12',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  148 => 
  array (
    'id_presensi' => 5511,
    'nis' => 14710,
    'tanggal' => '2026-07-22',
    'jam' => '06:58:14',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  149 => 
  array (
    'id_presensi' => 5512,
    'nis' => 14450,
    'tanggal' => '2026-07-22',
    'jam' => '06:58:14',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  150 => 
  array (
    'id_presensi' => 5513,
    'nis' => 14220,
    'tanggal' => '2026-07-22',
    'jam' => '06:58:14',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  151 => 
  array (
    'id_presensi' => 5514,
    'nis' => 13918,
    'tanggal' => '2026-07-22',
    'jam' => '06:58:16',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  152 => 
  array (
    'id_presensi' => 5515,
    'nis' => 14233,
    'tanggal' => '2026-07-22',
    'jam' => '06:58:17',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  153 => 
  array (
    'id_presensi' => 5516,
    'nis' => 14726,
    'tanggal' => '2026-07-22',
    'jam' => '06:58:18',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  154 => 
  array (
    'id_presensi' => 5517,
    'nis' => 14509,
    'tanggal' => '2026-07-22',
    'jam' => '06:58:27',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  155 => 
  array (
    'id_presensi' => 5518,
    'nis' => 13867,
    'tanggal' => '2026-07-22',
    'jam' => '06:58:28',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  156 => 
  array (
    'id_presensi' => 5519,
    'nis' => 14025,
    'tanggal' => '2026-07-22',
    'jam' => '06:58:29',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  157 => 
  array (
    'id_presensi' => 5520,
    'nis' => 14511,
    'tanggal' => '2026-07-22',
    'jam' => '06:58:30',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  158 => 
  array (
    'id_presensi' => 5521,
    'nis' => 13992,
    'tanggal' => '2026-07-22',
    'jam' => '06:58:32',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  159 => 
  array (
    'id_presensi' => 5522,
    'nis' => 13889,
    'tanggal' => '2026-07-22',
    'jam' => '06:58:33',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  160 => 
  array (
    'id_presensi' => 5523,
    'nis' => 14161,
    'tanggal' => '2026-07-22',
    'jam' => '06:58:42',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  161 => 
  array (
    'id_presensi' => 5524,
    'nis' => 14004,
    'tanggal' => '2026-07-22',
    'jam' => '06:58:43',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  162 => 
  array (
    'id_presensi' => 5525,
    'nis' => 14231,
    'tanggal' => '2026-07-22',
    'jam' => '06:58:44',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  163 => 
  array (
    'id_presensi' => 5526,
    'nis' => 14158,
    'tanggal' => '2026-07-22',
    'jam' => '06:58:44',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  164 => 
  array (
    'id_presensi' => 5527,
    'nis' => 14014,
    'tanggal' => '2026-07-22',
    'jam' => '06:58:45',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  165 => 
  array (
    'id_presensi' => 5528,
    'nis' => 14261,
    'tanggal' => '2026-07-22',
    'jam' => '06:58:47',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  166 => 
  array (
    'id_presensi' => 5529,
    'nis' => 14727,
    'tanggal' => '2026-07-22',
    'jam' => '06:58:50',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  167 => 
  array (
    'id_presensi' => 5530,
    'nis' => 14144,
    'tanggal' => '2026-07-22',
    'jam' => '06:58:50',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  168 => 
  array (
    'id_presensi' => 5531,
    'nis' => 14493,
    'tanggal' => '2026-07-22',
    'jam' => '06:58:51',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  169 => 
  array (
    'id_presensi' => 5532,
    'nis' => 14240,
    'tanggal' => '2026-07-22',
    'jam' => '06:58:52',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  170 => 
  array (
    'id_presensi' => 5533,
    'nis' => 14754,
    'tanggal' => '2026-07-22',
    'jam' => '06:58:55',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  171 => 
  array (
    'id_presensi' => 5534,
    'nis' => 14367,
    'tanggal' => '2026-07-22',
    'jam' => '06:59:02',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  172 => 
  array (
    'id_presensi' => 5535,
    'nis' => 14801,
    'tanggal' => '2026-07-22',
    'jam' => '06:59:03',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  173 => 
  array (
    'id_presensi' => 5536,
    'nis' => 14699,
    'tanggal' => '2026-07-22',
    'jam' => '06:59:04',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  174 => 
  array (
    'id_presensi' => 5537,
    'nis' => 14675,
    'tanggal' => '2026-07-22',
    'jam' => '06:59:07',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  175 => 
  array (
    'id_presensi' => 5538,
    'nis' => 14799,
    'tanggal' => '2026-07-22',
    'jam' => '06:59:08',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  176 => 
  array (
    'id_presensi' => 5539,
    'nis' => 14685,
    'tanggal' => '2026-07-22',
    'jam' => '06:59:10',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  177 => 
  array (
    'id_presensi' => 5540,
    'nis' => 14242,
    'tanggal' => '2026-07-22',
    'jam' => '06:59:10',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  178 => 
  array (
    'id_presensi' => 5541,
    'nis' => 14235,
    'tanggal' => '2026-07-22',
    'jam' => '06:59:12',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  179 => 
  array (
    'id_presensi' => 5542,
    'nis' => 13886,
    'tanggal' => '2026-07-22',
    'jam' => '06:59:13',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  180 => 
  array (
    'id_presensi' => 5543,
    'nis' => 14592,
    'tanggal' => '2026-07-22',
    'jam' => '06:59:15',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  181 => 
  array (
    'id_presensi' => 5544,
    'nis' => 14510,
    'tanggal' => '2026-07-22',
    'jam' => '06:59:17',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  182 => 
  array (
    'id_presensi' => 5545,
    'nis' => 14792,
    'tanggal' => '2026-07-22',
    'jam' => '06:59:18',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  183 => 
  array (
    'id_presensi' => 5546,
    'nis' => 14446,
    'tanggal' => '2026-07-22',
    'jam' => '06:59:20',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  184 => 
  array (
    'id_presensi' => 5547,
    'nis' => 14319,
    'tanggal' => '2026-07-22',
    'jam' => '06:59:20',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  185 => 
  array (
    'id_presensi' => 5548,
    'nis' => 14791,
    'tanggal' => '2026-07-22',
    'jam' => '06:59:22',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  186 => 
  array (
    'id_presensi' => 5549,
    'nis' => 14776,
    'tanggal' => '2026-07-22',
    'jam' => '06:59:24',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  187 => 
  array (
    'id_presensi' => 5550,
    'nis' => 14602,
    'tanggal' => '2026-07-22',
    'jam' => '06:59:25',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  188 => 
  array (
    'id_presensi' => 5551,
    'nis' => 14769,
    'tanggal' => '2026-07-22',
    'jam' => '06:59:25',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  189 => 
  array (
    'id_presensi' => 5552,
    'nis' => 14438,
    'tanggal' => '2026-07-22',
    'jam' => '06:59:29',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  190 => 
  array (
    'id_presensi' => 5553,
    'nis' => 14598,
    'tanggal' => '2026-07-22',
    'jam' => '06:59:30',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  191 => 
  array (
    'id_presensi' => 5554,
    'nis' => 14746,
    'tanggal' => '2026-07-22',
    'jam' => '06:59:31',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  192 => 
  array (
    'id_presensi' => 5555,
    'nis' => 14468,
    'tanggal' => '2026-07-22',
    'jam' => '06:59:32',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  193 => 
  array (
    'id_presensi' => 5556,
    'nis' => 14764,
    'tanggal' => '2026-07-22',
    'jam' => '06:59:33',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  194 => 
  array (
    'id_presensi' => 5557,
    'nis' => 13887,
    'tanggal' => '2026-07-22',
    'jam' => '06:59:35',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  195 => 
  array (
    'id_presensi' => 5558,
    'nis' => 14472,
    'tanggal' => '2026-07-22',
    'jam' => '06:59:35',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  196 => 
  array (
    'id_presensi' => 5559,
    'nis' => 14214,
    'tanggal' => '2026-07-22',
    'jam' => '06:59:42',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  197 => 
  array (
    'id_presensi' => 5560,
    'nis' => 14432,
    'tanggal' => '2026-07-22',
    'jam' => '06:59:47',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  198 => 
  array (
    'id_presensi' => 5561,
    'nis' => 14780,
    'tanggal' => '2026-07-22',
    'jam' => '06:59:49',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  199 => 
  array (
    'id_presensi' => 5562,
    'nis' => 14503,
    'tanggal' => '2026-07-22',
    'jam' => '06:59:58',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
));

        DB::table('presensi')->insert(array (
  0 => 
  array (
    'id_presensi' => 5563,
    'nis' => 13871,
    'tanggal' => '2026-07-22',
    'jam' => '07:00:00',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  1 => 
  array (
    'id_presensi' => 5564,
    'nis' => 14443,
    'tanggal' => '2026-07-22',
    'jam' => '07:00:03',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  2 => 
  array (
    'id_presensi' => 5565,
    'nis' => 14324,
    'tanggal' => '2026-07-22',
    'jam' => '07:00:04',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  3 => 
  array (
    'id_presensi' => 5566,
    'nis' => 14748,
    'tanggal' => '2026-07-22',
    'jam' => '07:00:07',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  4 => 
  array (
    'id_presensi' => 5567,
    'nis' => 14494,
    'tanggal' => '2026-07-22',
    'jam' => '07:00:09',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  5 => 
  array (
    'id_presensi' => 5568,
    'nis' => 14030,
    'tanggal' => '2026-07-22',
    'jam' => '07:00:12',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  6 => 
  array (
    'id_presensi' => 5569,
    'nis' => 14005,
    'tanggal' => '2026-07-22',
    'jam' => '07:00:14',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  7 => 
  array (
    'id_presensi' => 5570,
    'nis' => 13983,
    'tanggal' => '2026-07-22',
    'jam' => '07:00:14',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  8 => 
  array (
    'id_presensi' => 5571,
    'nis' => 13870,
    'tanggal' => '2026-07-22',
    'jam' => '07:00:19',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  9 => 
  array (
    'id_presensi' => 5572,
    'nis' => 13976,
    'tanggal' => '2026-07-22',
    'jam' => '07:00:26',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  10 => 
  array (
    'id_presensi' => 5573,
    'nis' => 14469,
    'tanggal' => '2026-07-22',
    'jam' => '07:00:28',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  11 => 
  array (
    'id_presensi' => 5574,
    'nis' => 14337,
    'tanggal' => '2026-07-22',
    'jam' => '07:00:31',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  12 => 
  array (
    'id_presensi' => 5575,
    'nis' => 13967,
    'tanggal' => '2026-07-22',
    'jam' => '07:00:37',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  13 => 
  array (
    'id_presensi' => 5576,
    'nis' => 13984,
    'tanggal' => '2026-07-22',
    'jam' => '07:00:41',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  14 => 
  array (
    'id_presensi' => 5577,
    'nis' => 14320,
    'tanggal' => '2026-07-22',
    'jam' => '07:00:42',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  15 => 
  array (
    'id_presensi' => 5578,
    'nis' => 14326,
    'tanggal' => '2026-07-22',
    'jam' => '07:01:04',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  16 => 
  array (
    'id_presensi' => 5579,
    'nis' => 14314,
    'tanggal' => '2026-07-22',
    'jam' => '07:01:09',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  17 => 
  array (
    'id_presensi' => 5580,
    'nis' => 14482,
    'tanggal' => '2026-07-22',
    'jam' => '07:01:11',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  18 => 
  array (
    'id_presensi' => 5581,
    'nis' => 14018,
    'tanggal' => '2026-07-22',
    'jam' => '07:01:13',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  19 => 
  array (
    'id_presensi' => 5582,
    'nis' => 14440,
    'tanggal' => '2026-07-22',
    'jam' => '07:01:16',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  20 => 
  array (
    'id_presensi' => 5583,
    'nis' => 14007,
    'tanggal' => '2026-07-22',
    'jam' => '07:01:22',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  21 => 
  array (
    'id_presensi' => 5584,
    'nis' => 14017,
    'tanggal' => '2026-07-22',
    'jam' => '07:01:25',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  22 => 
  array (
    'id_presensi' => 5585,
    'nis' => 14232,
    'tanggal' => '2026-07-22',
    'jam' => '07:01:29',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  23 => 
  array (
    'id_presensi' => 5586,
    'nis' => 14237,
    'tanggal' => '2026-07-22',
    'jam' => '07:01:31',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  24 => 
  array (
    'id_presensi' => 5587,
    'nis' => 14471,
    'tanggal' => '2026-07-22',
    'jam' => '07:01:33',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  25 => 
  array (
    'id_presensi' => 5588,
    'nis' => 14733,
    'tanggal' => '2026-07-22',
    'jam' => '07:01:46',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  26 => 
  array (
    'id_presensi' => 5589,
    'nis' => 14323,
    'tanggal' => '2026-07-22',
    'jam' => '07:01:52',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  27 => 
  array (
    'id_presensi' => 5590,
    'nis' => 14246,
    'tanggal' => '2026-07-22',
    'jam' => '07:01:53',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  28 => 
  array (
    'id_presensi' => 5591,
    'nis' => 14484,
    'tanggal' => '2026-07-22',
    'jam' => '07:01:54',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  29 => 
  array (
    'id_presensi' => 5592,
    'nis' => 14230,
    'tanggal' => '2026-07-22',
    'jam' => '07:01:59',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  30 => 
  array (
    'id_presensi' => 5593,
    'nis' => 14299,
    'tanggal' => '2026-07-22',
    'jam' => '07:02:05',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  31 => 
  array (
    'id_presensi' => 5594,
    'nis' => 13890,
    'tanggal' => '2026-07-22',
    'jam' => '07:02:08',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  32 => 
  array (
    'id_presensi' => 5595,
    'nis' => 14369,
    'tanggal' => '2026-07-22',
    'jam' => '07:02:14',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  33 => 
  array (
    'id_presensi' => 5596,
    'nis' => 14470,
    'tanggal' => '2026-07-22',
    'jam' => '07:02:16',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  34 => 
  array (
    'id_presensi' => 5597,
    'nis' => 13995,
    'tanggal' => '2026-07-22',
    'jam' => '07:02:26',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  35 => 
  array (
    'id_presensi' => 5598,
    'nis' => 14143,
    'tanggal' => '2026-07-22',
    'jam' => '07:02:31',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  36 => 
  array (
    'id_presensi' => 5599,
    'nis' => 14174,
    'tanggal' => '2026-07-22',
    'jam' => '07:02:33',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  37 => 
  array (
    'id_presensi' => 5600,
    'nis' => 13991,
    'tanggal' => '2026-07-22',
    'jam' => '07:02:40',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  38 => 
  array (
    'id_presensi' => 5601,
    'nis' => 13989,
    'tanggal' => '2026-07-22',
    'jam' => '07:02:45',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  39 => 
  array (
    'id_presensi' => 5602,
    'nis' => 13998,
    'tanggal' => '2026-07-22',
    'jam' => '07:02:52',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  40 => 
  array (
    'id_presensi' => 5603,
    'nis' => 14687,
    'tanggal' => '2026-07-22',
    'jam' => '07:04:45',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  41 => 
  array (
    'id_presensi' => 5604,
    'nis' => 14684,
    'tanggal' => '2026-07-22',
    'jam' => '07:04:49',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  42 => 
  array (
    'id_presensi' => 5605,
    'nis' => 15382,
    'tanggal' => '2026-07-22',
    'jam' => NULL,
    'status' => 'Hadir',
    'keterangan' => 'Input manual',
    'file' => NULL,
  ),
  43 => 
  array (
    'id_presensi' => 5606,
    'nis' => 15383,
    'tanggal' => '2026-07-22',
    'jam' => NULL,
    'status' => 'Hadir',
    'keterangan' => 'Input manual',
    'file' => NULL,
  ),
  44 => 
  array (
    'id_presensi' => 5607,
    'nis' => 15384,
    'tanggal' => '2026-07-22',
    'jam' => NULL,
    'status' => 'Hadir',
    'keterangan' => 'Input manual',
    'file' => NULL,
  ),
  45 => 
  array (
    'id_presensi' => 5608,
    'nis' => 15385,
    'tanggal' => '2026-07-22',
    'jam' => NULL,
    'status' => 'Hadir',
    'keterangan' => 'Input manual',
    'file' => NULL,
  ),
  46 => 
  array (
    'id_presensi' => 5609,
    'nis' => 15386,
    'tanggal' => '2026-07-22',
    'jam' => NULL,
    'status' => 'Hadir',
    'keterangan' => 'Input manual',
    'file' => NULL,
  ),
  47 => 
  array (
    'id_presensi' => 5610,
    'nis' => 15387,
    'tanggal' => '2026-07-22',
    'jam' => NULL,
    'status' => 'Hadir',
    'keterangan' => 'Input manual',
    'file' => NULL,
  ),
  48 => 
  array (
    'id_presensi' => 5611,
    'nis' => 15388,
    'tanggal' => '2026-07-22',
    'jam' => NULL,
    'status' => 'Hadir',
    'keterangan' => 'Input manual',
    'file' => NULL,
  ),
  49 => 
  array (
    'id_presensi' => 5612,
    'nis' => 15389,
    'tanggal' => '2026-07-22',
    'jam' => NULL,
    'status' => 'Hadir',
    'keterangan' => 'Input manual',
    'file' => NULL,
  ),
  50 => 
  array (
    'id_presensi' => 5613,
    'nis' => 15390,
    'tanggal' => '2026-07-22',
    'jam' => NULL,
    'status' => 'Hadir',
    'keterangan' => 'Input manual',
    'file' => NULL,
  ),
  51 => 
  array (
    'id_presensi' => 5614,
    'nis' => 15391,
    'tanggal' => '2026-07-22',
    'jam' => NULL,
    'status' => 'Hadir',
    'keterangan' => 'Input manual',
    'file' => NULL,
  ),
  52 => 
  array (
    'id_presensi' => 5615,
    'nis' => 15392,
    'tanggal' => '2026-07-22',
    'jam' => NULL,
    'status' => 'Hadir',
    'keterangan' => 'Input manual',
    'file' => NULL,
  ),
  53 => 
  array (
    'id_presensi' => 5616,
    'nis' => 15393,
    'tanggal' => '2026-07-22',
    'jam' => NULL,
    'status' => 'Hadir',
    'keterangan' => 'Input manual',
    'file' => NULL,
  ),
  54 => 
  array (
    'id_presensi' => 5617,
    'nis' => 15394,
    'tanggal' => '2026-07-22',
    'jam' => NULL,
    'status' => 'Hadir',
    'keterangan' => 'Input manual',
    'file' => NULL,
  ),
  55 => 
  array (
    'id_presensi' => 5618,
    'nis' => 15395,
    'tanggal' => '2026-07-22',
    'jam' => NULL,
    'status' => 'Hadir',
    'keterangan' => 'Input manual',
    'file' => NULL,
  ),
  56 => 
  array (
    'id_presensi' => 5619,
    'nis' => 15396,
    'tanggal' => '2026-07-22',
    'jam' => NULL,
    'status' => 'Hadir',
    'keterangan' => 'Input manual',
    'file' => NULL,
  ),
  57 => 
  array (
    'id_presensi' => 5620,
    'nis' => 15397,
    'tanggal' => '2026-07-22',
    'jam' => NULL,
    'status' => 'Hadir',
    'keterangan' => 'Input manual',
    'file' => NULL,
  ),
  58 => 
  array (
    'id_presensi' => 5621,
    'nis' => 15398,
    'tanggal' => '2026-07-22',
    'jam' => NULL,
    'status' => 'Hadir',
    'keterangan' => 'Input manual',
    'file' => NULL,
  ),
  59 => 
  array (
    'id_presensi' => 5622,
    'nis' => 15399,
    'tanggal' => '2026-07-22',
    'jam' => NULL,
    'status' => 'Hadir',
    'keterangan' => 'Input manual',
    'file' => NULL,
  ),
  60 => 
  array (
    'id_presensi' => 5623,
    'nis' => 15400,
    'tanggal' => '2026-07-22',
    'jam' => NULL,
    'status' => 'Hadir',
    'keterangan' => 'Input manual',
    'file' => NULL,
  ),
  61 => 
  array (
    'id_presensi' => 5624,
    'nis' => 15401,
    'tanggal' => '2026-07-22',
    'jam' => NULL,
    'status' => 'Hadir',
    'keterangan' => 'Input manual',
    'file' => NULL,
  ),
  62 => 
  array (
    'id_presensi' => 5625,
    'nis' => 15402,
    'tanggal' => '2026-07-22',
    'jam' => NULL,
    'status' => 'Hadir',
    'keterangan' => 'Input manual',
    'file' => NULL,
  ),
  63 => 
  array (
    'id_presensi' => 5626,
    'nis' => 15403,
    'tanggal' => '2026-07-22',
    'jam' => NULL,
    'status' => 'Hadir',
    'keterangan' => 'Input manual',
    'file' => NULL,
  ),
  64 => 
  array (
    'id_presensi' => 5627,
    'nis' => 15404,
    'tanggal' => '2026-07-22',
    'jam' => NULL,
    'status' => 'Hadir',
    'keterangan' => 'Input manual',
    'file' => NULL,
  ),
  65 => 
  array (
    'id_presensi' => 5628,
    'nis' => 15405,
    'tanggal' => '2026-07-22',
    'jam' => NULL,
    'status' => 'Hadir',
    'keterangan' => 'Input manual',
    'file' => NULL,
  ),
  66 => 
  array (
    'id_presensi' => 5629,
    'nis' => 15406,
    'tanggal' => '2026-07-22',
    'jam' => NULL,
    'status' => 'Hadir',
    'keterangan' => 'Input manual',
    'file' => NULL,
  ),
  67 => 
  array (
    'id_presensi' => 5630,
    'nis' => 15407,
    'tanggal' => '2026-07-22',
    'jam' => NULL,
    'status' => 'Hadir',
    'keterangan' => 'Input manual',
    'file' => NULL,
  ),
  68 => 
  array (
    'id_presensi' => 5631,
    'nis' => 15408,
    'tanggal' => '2026-07-22',
    'jam' => NULL,
    'status' => 'Hadir',
    'keterangan' => 'Input manual',
    'file' => NULL,
  ),
  69 => 
  array (
    'id_presensi' => 5632,
    'nis' => 15409,
    'tanggal' => '2026-07-22',
    'jam' => NULL,
    'status' => 'Hadir',
    'keterangan' => 'Input manual',
    'file' => NULL,
  ),
  70 => 
  array (
    'id_presensi' => 5633,
    'nis' => 15410,
    'tanggal' => '2026-07-22',
    'jam' => NULL,
    'status' => 'Hadir',
    'keterangan' => 'Input manual',
    'file' => NULL,
  ),
  71 => 
  array (
    'id_presensi' => 5634,
    'nis' => 15411,
    'tanggal' => '2026-07-22',
    'jam' => NULL,
    'status' => 'Hadir',
    'keterangan' => 'Input manual',
    'file' => NULL,
  ),
  72 => 
  array (
    'id_presensi' => 5635,
    'nis' => 15412,
    'tanggal' => '2026-07-22',
    'jam' => NULL,
    'status' => 'Hadir',
    'keterangan' => 'Input manual',
    'file' => NULL,
  ),
  73 => 
  array (
    'id_presensi' => 5636,
    'nis' => 15413,
    'tanggal' => '2026-07-22',
    'jam' => NULL,
    'status' => 'Sakit',
    'keterangan' => 'Input manual',
    'file' => NULL,
  ),
  74 => 
  array (
    'id_presensi' => 5637,
    'nis' => 15414,
    'tanggal' => '2026-07-22',
    'jam' => NULL,
    'status' => 'Hadir',
    'keterangan' => 'Input manual',
    'file' => NULL,
  ),
  75 => 
  array (
    'id_presensi' => 5638,
    'nis' => 15415,
    'tanggal' => '2026-07-22',
    'jam' => NULL,
    'status' => 'Hadir',
    'keterangan' => 'Input manual',
    'file' => NULL,
  ),
  76 => 
  array (
    'id_presensi' => 5639,
    'nis' => 15416,
    'tanggal' => '2026-07-22',
    'jam' => NULL,
    'status' => 'Hadir',
    'keterangan' => 'Input manual',
    'file' => NULL,
  ),
  77 => 
  array (
    'id_presensi' => 5640,
    'nis' => 15417,
    'tanggal' => '2026-07-22',
    'jam' => NULL,
    'status' => 'Hadir',
    'keterangan' => 'Input manual',
    'file' => NULL,
  ),
  78 => 
  array (
    'id_presensi' => 5641,
    'nis' => 15418,
    'tanggal' => '2026-07-22',
    'jam' => NULL,
    'status' => 'Hadir',
    'keterangan' => 'Input manual',
    'file' => NULL,
  ),
  79 => 
  array (
    'id_presensi' => 5642,
    'nis' => 15419,
    'tanggal' => '2026-07-22',
    'jam' => NULL,
    'status' => 'Sakit',
    'keterangan' => 'Input manual',
    'file' => NULL,
  ),
  80 => 
  array (
    'id_presensi' => 5643,
    'nis' => 15420,
    'tanggal' => '2026-07-22',
    'jam' => NULL,
    'status' => 'Hadir',
    'keterangan' => 'Input manual',
    'file' => NULL,
  ),
  81 => 
  array (
    'id_presensi' => 5644,
    'nis' => 14148,
    'tanggal' => '2026-07-22',
    'jam' => '07:17:04',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  82 => 
  array (
    'id_presensi' => 5645,
    'nis' => 14173,
    'tanggal' => '2026-07-22',
    'jam' => '07:17:34',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  83 => 
  array (
    'id_presensi' => 5646,
    'nis' => 14167,
    'tanggal' => '2026-07-22',
    'jam' => '07:17:37',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  84 => 
  array (
    'id_presensi' => 5647,
    'nis' => 14170,
    'tanggal' => '2026-07-22',
    'jam' => '07:17:50',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  85 => 
  array (
    'id_presensi' => 5648,
    'nis' => 14153,
    'tanggal' => '2026-07-22',
    'jam' => '07:17:56',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  86 => 
  array (
    'id_presensi' => 5649,
    'nis' => 14152,
    'tanggal' => '2026-07-22',
    'jam' => '07:20:27',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  87 => 
  array (
    'id_presensi' => 5650,
    'nis' => 14157,
    'tanggal' => '2026-07-22',
    'jam' => '07:20:33',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  88 => 
  array (
    'id_presensi' => 5651,
    'nis' => 14155,
    'tanggal' => '2026-07-22',
    'jam' => '07:20:38',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  89 => 
  array (
    'id_presensi' => 5652,
    'nis' => 13909,
    'tanggal' => '2026-07-22',
    'jam' => '07:21:58',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  90 => 
  array (
    'id_presensi' => 5653,
    'nis' => 13903,
    'tanggal' => '2026-07-22',
    'jam' => '07:22:21',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  91 => 
  array (
    'id_presensi' => 5654,
    'nis' => 13894,
    'tanggal' => '2026-07-22',
    'jam' => '07:22:57',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  92 => 
  array (
    'id_presensi' => 5655,
    'nis' => 13905,
    'tanggal' => '2026-07-22',
    'jam' => '07:23:20',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  93 => 
  array (
    'id_presensi' => 5656,
    'nis' => 13897,
    'tanggal' => '2026-07-22',
    'jam' => '07:54:29',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  94 => 
  array (
    'id_presensi' => 5657,
    'nis' => 13910,
    'tanggal' => '2026-07-22',
    'jam' => '08:16:52',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  95 => 
  array (
    'id_presensi' => 5658,
    'nis' => 13893,
    'tanggal' => '2026-07-22',
    'jam' => '08:17:00',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  96 => 
  array (
    'id_presensi' => 5659,
    'nis' => 14160,
    'tanggal' => '2026-07-22',
    'jam' => '09:51:04',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  97 => 
  array (
    'id_presensi' => 5660,
    'nis' => 14464,
    'tanggal' => '2026-07-22',
    'jam' => '11:15:30',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  98 => 
  array (
    'id_presensi' => 5661,
    'nis' => 14467,
    'tanggal' => '2026-07-22',
    'jam' => '11:15:42',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  99 => 
  array (
    'id_presensi' => 5662,
    'nis' => 14164,
    'tanggal' => '2026-07-22',
    'jam' => '11:32:42',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  100 => 
  array (
    'id_presensi' => 5663,
    'nis' => 14001,
    'tanggal' => '2026-07-22',
    'jam' => '11:35:01',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  101 => 
  array (
    'id_presensi' => 5664,
    'nis' => 13924,
    'tanggal' => '2026-07-22',
    'jam' => '11:43:45',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  102 => 
  array (
    'id_presensi' => 5665,
    'nis' => 14486,
    'tanggal' => '2026-07-22',
    'jam' => '11:44:28',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  103 => 
  array (
    'id_presensi' => 5666,
    'nis' => 14612,
    'tanggal' => '2026-07-22',
    'jam' => '12:19:51',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  104 => 
  array (
    'id_presensi' => 5667,
    'nis' => 14634,
    'tanggal' => '2026-07-22',
    'jam' => '12:19:54',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  105 => 
  array (
    'id_presensi' => 5668,
    'nis' => 14641,
    'tanggal' => '2026-07-22',
    'jam' => '12:19:58',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  106 => 
  array (
    'id_presensi' => 5669,
    'nis' => 14622,
    'tanggal' => '2026-07-22',
    'jam' => '12:20:00',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  107 => 
  array (
    'id_presensi' => 5670,
    'nis' => 14610,
    'tanggal' => '2026-07-22',
    'jam' => '12:20:12',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  108 => 
  array (
    'id_presensi' => 5671,
    'nis' => 14624,
    'tanggal' => '2026-07-22',
    'jam' => '12:20:22',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  109 => 
  array (
    'id_presensi' => 5672,
    'nis' => 14636,
    'tanggal' => '2026-07-22',
    'jam' => '12:20:25',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  110 => 
  array (
    'id_presensi' => 5673,
    'nis' => 14611,
    'tanggal' => '2026-07-22',
    'jam' => '12:20:29',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  111 => 
  array (
    'id_presensi' => 5674,
    'nis' => 14615,
    'tanggal' => '2026-07-22',
    'jam' => '12:20:35',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  112 => 
  array (
    'id_presensi' => 5675,
    'nis' => 14642,
    'tanggal' => '2026-07-22',
    'jam' => '12:20:38',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  113 => 
  array (
    'id_presensi' => 5676,
    'nis' => 14659,
    'tanggal' => '2026-07-22',
    'jam' => '12:20:42',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  114 => 
  array (
    'id_presensi' => 5677,
    'nis' => 14669,
    'tanggal' => '2026-07-22',
    'jam' => '12:20:48',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  115 => 
  array (
    'id_presensi' => 5678,
    'nis' => 14657,
    'tanggal' => '2026-07-22',
    'jam' => '12:20:51',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  116 => 
  array (
    'id_presensi' => 5679,
    'nis' => 14646,
    'tanggal' => '2026-07-22',
    'jam' => '12:20:58',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  117 => 
  array (
    'id_presensi' => 5680,
    'nis' => 14662,
    'tanggal' => '2026-07-22',
    'jam' => '12:21:00',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  118 => 
  array (
    'id_presensi' => 5681,
    'nis' => 14647,
    'tanggal' => '2026-07-22',
    'jam' => '12:21:02',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  119 => 
  array (
    'id_presensi' => 5682,
    'nis' => 14658,
    'tanggal' => '2026-07-22',
    'jam' => '12:21:09',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  120 => 
  array (
    'id_presensi' => 5683,
    'nis' => 14660,
    'tanggal' => '2026-07-22',
    'jam' => '12:21:12',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  121 => 
  array (
    'id_presensi' => 5684,
    'nis' => 14673,
    'tanggal' => '2026-07-22',
    'jam' => '12:21:18',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  122 => 
  array (
    'id_presensi' => 5685,
    'nis' => 14671,
    'tanggal' => '2026-07-22',
    'jam' => '12:21:20',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  123 => 
  array (
    'id_presensi' => 5686,
    'nis' => 14620,
    'tanggal' => '2026-07-22',
    'jam' => '12:21:27',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  124 => 
  array (
    'id_presensi' => 5687,
    'nis' => 14633,
    'tanggal' => '2026-07-22',
    'jam' => '12:21:31',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  125 => 
  array (
    'id_presensi' => 5688,
    'nis' => 14639,
    'tanggal' => '2026-07-22',
    'jam' => '12:21:35',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  126 => 
  array (
    'id_presensi' => 5689,
    'nis' => 14635,
    'tanggal' => '2026-07-22',
    'jam' => '12:21:50',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  127 => 
  array (
    'id_presensi' => 5690,
    'nis' => 14627,
    'tanggal' => '2026-07-22',
    'jam' => '12:22:04',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  128 => 
  array (
    'id_presensi' => 5691,
    'nis' => 14626,
    'tanggal' => '2026-07-22',
    'jam' => '12:22:13',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  129 => 
  array (
    'id_presensi' => 5692,
    'nis' => 14644,
    'tanggal' => '2026-07-22',
    'jam' => '12:23:34',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  130 => 
  array (
    'id_presensi' => 5693,
    'nis' => 14608,
    'tanggal' => '2026-07-22',
    'jam' => '12:24:50',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  131 => 
  array (
    'id_presensi' => 5694,
    'nis' => 14628,
    'tanggal' => '2026-07-22',
    'jam' => '12:25:36',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  132 => 
  array (
    'id_presensi' => 5695,
    'nis' => 14630,
    'tanggal' => '2026-07-22',
    'jam' => '12:27:51',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  133 => 
  array (
    'id_presensi' => 5696,
    'nis' => 14667,
    'tanggal' => '2026-07-22',
    'jam' => '12:29:15',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  134 => 
  array (
    'id_presensi' => 5697,
    'nis' => 14668,
    'tanggal' => '2026-07-22',
    'jam' => '12:29:18',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  135 => 
  array (
    'id_presensi' => 5698,
    'nis' => 14648,
    'tanggal' => '2026-07-22',
    'jam' => '12:29:22',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  136 => 
  array (
    'id_presensi' => 5699,
    'nis' => 14649,
    'tanggal' => '2026-07-22',
    'jam' => '12:30:15',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  137 => 
  array (
    'id_presensi' => 5700,
    'nis' => 14629,
    'tanggal' => '2026-07-22',
    'jam' => '12:30:35',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  138 => 
  array (
    'id_presensi' => 5701,
    'nis' => 14625,
    'tanggal' => '2026-07-22',
    'jam' => '12:31:10',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  139 => 
  array (
    'id_presensi' => 5702,
    'nis' => 14396,
    'tanggal' => '2026-07-22',
    'jam' => '12:33:29',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  140 => 
  array (
    'id_presensi' => 5703,
    'nis' => 14623,
    'tanggal' => '2026-07-22',
    'jam' => '12:33:35',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  141 => 
  array (
    'id_presensi' => 5704,
    'nis' => 14405,
    'tanggal' => '2026-07-22',
    'jam' => '12:33:38',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  142 => 
  array (
    'id_presensi' => 5705,
    'nis' => 14631,
    'tanggal' => '2026-07-22',
    'jam' => '12:33:38',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  143 => 
  array (
    'id_presensi' => 5706,
    'nis' => 14652,
    'tanggal' => '2026-07-22',
    'jam' => '12:33:43',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  144 => 
  array (
    'id_presensi' => 5707,
    'nis' => 14651,
    'tanggal' => '2026-07-22',
    'jam' => '12:33:51',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  145 => 
  array (
    'id_presensi' => 5708,
    'nis' => 14663,
    'tanggal' => '2026-07-22',
    'jam' => '12:34:02',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  146 => 
  array (
    'id_presensi' => 5709,
    'nis' => 14666,
    'tanggal' => '2026-07-22',
    'jam' => '12:34:19',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  147 => 
  array (
    'id_presensi' => 5710,
    'nis' => 14664,
    'tanggal' => '2026-07-22',
    'jam' => '12:38:33',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  148 => 
  array (
    'id_presensi' => 5711,
    'nis' => 14643,
    'tanggal' => '2026-07-22',
    'jam' => '12:38:38',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  149 => 
  array (
    'id_presensi' => 5712,
    'nis' => 14400,
    'tanggal' => '2026-07-22',
    'jam' => '12:44:24',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  150 => 
  array (
    'id_presensi' => 5713,
    'nis' => 14476,
    'tanggal' => '2026-07-22',
    'jam' => '13:32:38',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  151 => 
  array (
    'id_presensi' => 5714,
    'nis' => 14293,
    'tanggal' => '2026-07-22',
    'jam' => '14:30:51',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  152 => 
  array (
    'id_presensi' => 5715,
    'nis' => 14312,
    'tanggal' => '2026-07-22',
    'jam' => '14:32:20',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  153 => 
  array (
    'id_presensi' => 5716,
    'nis' => 14414,
    'tanggal' => '2026-07-22',
    'jam' => '15:11:19',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  154 => 
  array (
    'id_presensi' => 5717,
    'nis' => 14653,
    'tanggal' => '2026-07-22',
    'jam' => '15:14:41',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  155 => 
  array (
    'id_presensi' => 5718,
    'nis' => 14650,
    'tanggal' => '2026-07-22',
    'jam' => '15:16:01',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  156 => 
  array (
    'id_presensi' => 5719,
    'nis' => 14304,
    'tanggal' => '2026-07-22',
    'jam' => '15:20:53',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  157 => 
  array (
    'id_presensi' => 5720,
    'nis' => 14394,
    'tanggal' => '2026-07-22',
    'jam' => '15:26:55',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  158 => 
  array (
    'id_presensi' => 5721,
    'nis' => 14412,
    'tanggal' => '2026-07-22',
    'jam' => '15:27:00',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  159 => 
  array (
    'id_presensi' => 5722,
    'nis' => 14392,
    'tanggal' => '2026-07-22',
    'jam' => '15:27:23',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  160 => 
  array (
    'id_presensi' => 5723,
    'nis' => 14391,
    'tanggal' => '2026-07-22',
    'jam' => '15:28:00',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  161 => 
  array (
    'id_presensi' => 5724,
    'nis' => 14614,
    'tanggal' => '2026-07-22',
    'jam' => '15:32:43',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  162 => 
  array (
    'id_presensi' => 5725,
    'nis' => 14618,
    'tanggal' => '2026-07-22',
    'jam' => '15:53:41',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  163 => 
  array (
    'id_presensi' => 5726,
    'nis' => 14406,
    'tanggal' => '2026-07-22',
    'jam' => '15:59:55',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  164 => 
  array (
    'id_presensi' => 5727,
    'nis' => 14773,
    'tanggal' => '2026-07-23',
    'jam' => '06:08:45',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  165 => 
  array (
    'id_presensi' => 5728,
    'nis' => 14213,
    'tanggal' => '2026-07-23',
    'jam' => '06:15:10',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  166 => 
  array (
    'id_presensi' => 5729,
    'nis' => 14774,
    'tanggal' => '2026-07-23',
    'jam' => '06:20:14',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  167 => 
  array (
    'id_presensi' => 5730,
    'nis' => 14379,
    'tanggal' => '2026-07-23',
    'jam' => '06:22:43',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  168 => 
  array (
    'id_presensi' => 5731,
    'nis' => 14742,
    'tanggal' => '2026-07-23',
    'jam' => '06:22:58',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  169 => 
  array (
    'id_presensi' => 5732,
    'nis' => 14741,
    'tanggal' => '2026-07-23',
    'jam' => '06:30:17',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  170 => 
  array (
    'id_presensi' => 5733,
    'nis' => 13866,
    'tanggal' => '2026-07-23',
    'jam' => '06:31:34',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  171 => 
  array (
    'id_presensi' => 5734,
    'nis' => 14759,
    'tanggal' => '2026-07-23',
    'jam' => '06:31:36',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  172 => 
  array (
    'id_presensi' => 5735,
    'nis' => 13868,
    'tanggal' => '2026-07-23',
    'jam' => '06:35:07',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  173 => 
  array (
    'id_presensi' => 5736,
    'nis' => 14378,
    'tanggal' => '2026-07-23',
    'jam' => '06:35:19',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  174 => 
  array (
    'id_presensi' => 5737,
    'nis' => 14382,
    'tanggal' => '2026-07-23',
    'jam' => '06:35:22',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  175 => 
  array (
    'id_presensi' => 5738,
    'nis' => 14162,
    'tanggal' => '2026-07-23',
    'jam' => '06:35:45',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  176 => 
  array (
    'id_presensi' => 5739,
    'nis' => 14380,
    'tanggal' => '2026-07-23',
    'jam' => '06:36:52',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  177 => 
  array (
    'id_presensi' => 5740,
    'nis' => 14744,
    'tanggal' => '2026-07-23',
    'jam' => '06:38:17',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  178 => 
  array (
    'id_presensi' => 5741,
    'nis' => 14745,
    'tanggal' => '2026-07-23',
    'jam' => '06:38:20',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  179 => 
  array (
    'id_presensi' => 5742,
    'nis' => 14340,
    'tanggal' => '2026-07-23',
    'jam' => '06:38:24',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  180 => 
  array (
    'id_presensi' => 5743,
    'nis' => 14247,
    'tanggal' => '2026-07-23',
    'jam' => '06:40:10',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  181 => 
  array (
    'id_presensi' => 5744,
    'nis' => 14248,
    'tanggal' => '2026-07-23',
    'jam' => '06:40:12',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  182 => 
  array (
    'id_presensi' => 5745,
    'nis' => 14723,
    'tanggal' => '2026-07-23',
    'jam' => '06:41:19',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  183 => 
  array (
    'id_presensi' => 5746,
    'nis' => 14737,
    'tanggal' => '2026-07-23',
    'jam' => '06:41:21',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  184 => 
  array (
    'id_presensi' => 5747,
    'nis' => 14724,
    'tanggal' => '2026-07-23',
    'jam' => '06:41:25',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  185 => 
  array (
    'id_presensi' => 5748,
    'nis' => 14322,
    'tanggal' => '2026-07-23',
    'jam' => '06:41:28',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  186 => 
  array (
    'id_presensi' => 5749,
    'nis' => 13882,
    'tanggal' => '2026-07-23',
    'jam' => '06:41:35',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  187 => 
  array (
    'id_presensi' => 5750,
    'nis' => 14144,
    'tanggal' => '2026-07-23',
    'jam' => '06:41:40',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  188 => 
  array (
    'id_presensi' => 5751,
    'nis' => 14434,
    'tanggal' => '2026-07-23',
    'jam' => '06:42:24',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  189 => 
  array (
    'id_presensi' => 5752,
    'nis' => 14674,
    'tanggal' => '2026-07-23',
    'jam' => '06:42:32',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  190 => 
  array (
    'id_presensi' => 5753,
    'nis' => 14222,
    'tanggal' => '2026-07-23',
    'jam' => '06:42:37',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  191 => 
  array (
    'id_presensi' => 5754,
    'nis' => 14502,
    'tanggal' => '2026-07-23',
    'jam' => '06:42:51',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  192 => 
  array (
    'id_presensi' => 5755,
    'nis' => 13906,
    'tanggal' => '2026-07-23',
    'jam' => '06:43:36',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  193 => 
  array (
    'id_presensi' => 5756,
    'nis' => 14691,
    'tanggal' => '2026-07-23',
    'jam' => '06:44:35',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  194 => 
  array (
    'id_presensi' => 5757,
    'nis' => 14386,
    'tanggal' => '2026-07-23',
    'jam' => '06:45:04',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  195 => 
  array (
    'id_presensi' => 5758,
    'nis' => 14581,
    'tanggal' => '2026-07-23',
    'jam' => '06:45:07',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  196 => 
  array (
    'id_presensi' => 5759,
    'nis' => 14751,
    'tanggal' => '2026-07-23',
    'jam' => '06:45:12',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  197 => 
  array (
    'id_presensi' => 5760,
    'nis' => 14770,
    'tanggal' => '2026-07-23',
    'jam' => '06:45:15',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  198 => 
  array (
    'id_presensi' => 5761,
    'nis' => 13877,
    'tanggal' => '2026-07-23',
    'jam' => '06:45:41',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  199 => 
  array (
    'id_presensi' => 5762,
    'nis' => 13875,
    'tanggal' => '2026-07-23',
    'jam' => '06:45:45',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
));

        DB::table('presensi')->insert(array (
  0 => 
  array (
    'id_presensi' => 5763,
    'nis' => 14339,
    'tanggal' => '2026-07-23',
    'jam' => '06:45:51',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  1 => 
  array (
    'id_presensi' => 5764,
    'nis' => 13915,
    'tanggal' => '2026-07-23',
    'jam' => '06:46:05',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  2 => 
  array (
    'id_presensi' => 5765,
    'nis' => 14321,
    'tanggal' => '2026-07-23',
    'jam' => '06:46:33',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  3 => 
  array (
    'id_presensi' => 5766,
    'nis' => 14317,
    'tanggal' => '2026-07-23',
    'jam' => '06:46:54',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  4 => 
  array (
    'id_presensi' => 5767,
    'nis' => 13874,
    'tanggal' => '2026-07-23',
    'jam' => '06:46:58',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  5 => 
  array (
    'id_presensi' => 5768,
    'nis' => 14295,
    'tanggal' => '2026-07-23',
    'jam' => '06:47:20',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  6 => 
  array (
    'id_presensi' => 5769,
    'nis' => 14766,
    'tanggal' => '2026-07-23',
    'jam' => '06:47:23',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  7 => 
  array (
    'id_presensi' => 5770,
    'nis' => 14302,
    'tanggal' => '2026-07-23',
    'jam' => '06:47:23',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  8 => 
  array (
    'id_presensi' => 5771,
    'nis' => 14385,
    'tanggal' => '2026-07-23',
    'jam' => '06:47:29',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  9 => 
  array (
    'id_presensi' => 5772,
    'nis' => 14771,
    'tanggal' => '2026-07-23',
    'jam' => '06:47:30',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  10 => 
  array (
    'id_presensi' => 5773,
    'nis' => 14294,
    'tanggal' => '2026-07-23',
    'jam' => '06:47:36',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  11 => 
  array (
    'id_presensi' => 5774,
    'nis' => 14288,
    'tanggal' => '2026-07-23',
    'jam' => '06:47:39',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  12 => 
  array (
    'id_presensi' => 5775,
    'nis' => 14508,
    'tanggal' => '2026-07-23',
    'jam' => '06:48:06',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  13 => 
  array (
    'id_presensi' => 5776,
    'nis' => 14578,
    'tanggal' => '2026-07-23',
    'jam' => '06:48:11',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  14 => 
  array (
    'id_presensi' => 5777,
    'nis' => 14585,
    'tanggal' => '2026-07-23',
    'jam' => '06:48:14',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  15 => 
  array (
    'id_presensi' => 5778,
    'nis' => 14587,
    'tanggal' => '2026-07-23',
    'jam' => '06:48:15',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  16 => 
  array (
    'id_presensi' => 5779,
    'nis' => 14604,
    'tanggal' => '2026-07-23',
    'jam' => '06:48:20',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  17 => 
  array (
    'id_presensi' => 5780,
    'nis' => 14486,
    'tanggal' => '2026-07-23',
    'jam' => '06:48:21',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  18 => 
  array (
    'id_presensi' => 5781,
    'nis' => 13929,
    'tanggal' => '2026-07-23',
    'jam' => '06:48:32',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  19 => 
  array (
    'id_presensi' => 5782,
    'nis' => 13922,
    'tanggal' => '2026-07-23',
    'jam' => '06:48:50',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  20 => 
  array (
    'id_presensi' => 5783,
    'nis' => 14431,
    'tanggal' => '2026-07-23',
    'jam' => '06:48:51',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  21 => 
  array (
    'id_presensi' => 5784,
    'nis' => 14430,
    'tanggal' => '2026-07-23',
    'jam' => '06:48:57',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  22 => 
  array (
    'id_presensi' => 5785,
    'nis' => 14428,
    'tanggal' => '2026-07-23',
    'jam' => '06:48:59',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  23 => 
  array (
    'id_presensi' => 5786,
    'nis' => 14435,
    'tanggal' => '2026-07-23',
    'jam' => '06:49:02',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  24 => 
  array (
    'id_presensi' => 5787,
    'nis' => 14805,
    'tanggal' => '2026-07-23',
    'jam' => '06:49:20',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  25 => 
  array (
    'id_presensi' => 5788,
    'nis' => 14769,
    'tanggal' => '2026-07-23',
    'jam' => '06:49:46',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  26 => 
  array (
    'id_presensi' => 5789,
    'nis' => 13884,
    'tanggal' => '2026-07-23',
    'jam' => '06:49:53',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  27 => 
  array (
    'id_presensi' => 5790,
    'nis' => 14682,
    'tanggal' => '2026-07-23',
    'jam' => '06:49:54',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  28 => 
  array (
    'id_presensi' => 5791,
    'nis' => 14582,
    'tanggal' => '2026-07-23',
    'jam' => '06:50:01',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  29 => 
  array (
    'id_presensi' => 5792,
    'nis' => 14703,
    'tanggal' => '2026-07-23',
    'jam' => '06:50:02',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  30 => 
  array (
    'id_presensi' => 5793,
    'nis' => 14593,
    'tanggal' => '2026-07-23',
    'jam' => '06:50:04',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  31 => 
  array (
    'id_presensi' => 5794,
    'nis' => 14290,
    'tanggal' => '2026-07-23',
    'jam' => '06:50:12',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  32 => 
  array (
    'id_presensi' => 5795,
    'nis' => 14217,
    'tanggal' => '2026-07-23',
    'jam' => '06:50:16',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  33 => 
  array (
    'id_presensi' => 5796,
    'nis' => 13999,
    'tanggal' => '2026-07-23',
    'jam' => '06:50:20',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  34 => 
  array (
    'id_presensi' => 5797,
    'nis' => 14238,
    'tanggal' => '2026-07-23',
    'jam' => '06:50:23',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  35 => 
  array (
    'id_presensi' => 5798,
    'nis' => 14215,
    'tanggal' => '2026-07-23',
    'jam' => '06:50:26',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  36 => 
  array (
    'id_presensi' => 5799,
    'nis' => 14029,
    'tanggal' => '2026-07-23',
    'jam' => '06:50:28',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  37 => 
  array (
    'id_presensi' => 5800,
    'nis' => 14331,
    'tanggal' => '2026-07-23',
    'jam' => '06:50:28',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  38 => 
  array (
    'id_presensi' => 5801,
    'nis' => 14701,
    'tanggal' => '2026-07-23',
    'jam' => '06:50:35',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  39 => 
  array (
    'id_presensi' => 5802,
    'nis' => 14734,
    'tanggal' => '2026-07-23',
    'jam' => '06:51:03',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  40 => 
  array (
    'id_presensi' => 5803,
    'nis' => 14601,
    'tanggal' => '2026-07-23',
    'jam' => '06:51:07',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  41 => 
  array (
    'id_presensi' => 5804,
    'nis' => 14131,
    'tanggal' => '2026-07-23',
    'jam' => '06:51:09',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  42 => 
  array (
    'id_presensi' => 5805,
    'nis' => 14715,
    'tanggal' => '2026-07-23',
    'jam' => '06:51:10',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  43 => 
  array (
    'id_presensi' => 5806,
    'nis' => 14596,
    'tanggal' => '2026-07-23',
    'jam' => '06:51:11',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  44 => 
  array (
    'id_presensi' => 5807,
    'nis' => 14711,
    'tanggal' => '2026-07-23',
    'jam' => '06:51:12',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  45 => 
  array (
    'id_presensi' => 5808,
    'nis' => 14588,
    'tanggal' => '2026-07-23',
    'jam' => '06:51:18',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  46 => 
  array (
    'id_presensi' => 5809,
    'nis' => 14728,
    'tanggal' => '2026-07-23',
    'jam' => '06:51:40',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  47 => 
  array (
    'id_presensi' => 5810,
    'nis' => 14492,
    'tanggal' => '2026-07-23',
    'jam' => '06:51:47',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  48 => 
  array (
    'id_presensi' => 5811,
    'nis' => 14688,
    'tanggal' => '2026-07-23',
    'jam' => '06:52:10',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  49 => 
  array (
    'id_presensi' => 5812,
    'nis' => 14292,
    'tanggal' => '2026-07-23',
    'jam' => '06:52:10',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  50 => 
  array (
    'id_presensi' => 5813,
    'nis' => 14758,
    'tanggal' => '2026-07-23',
    'jam' => '06:52:12',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  51 => 
  array (
    'id_presensi' => 5814,
    'nis' => 14689,
    'tanggal' => '2026-07-23',
    'jam' => '06:52:13',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  52 => 
  array (
    'id_presensi' => 5815,
    'nis' => 14787,
    'tanggal' => '2026-07-23',
    'jam' => '06:52:16',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  53 => 
  array (
    'id_presensi' => 5816,
    'nis' => 14775,
    'tanggal' => '2026-07-23',
    'jam' => '06:52:19',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  54 => 
  array (
    'id_presensi' => 5817,
    'nis' => 14796,
    'tanggal' => '2026-07-23',
    'jam' => '06:52:22',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  55 => 
  array (
    'id_presensi' => 5818,
    'nis' => 14219,
    'tanggal' => '2026-07-23',
    'jam' => '06:52:24',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  56 => 
  array (
    'id_presensi' => 5819,
    'nis' => 14779,
    'tanggal' => '2026-07-23',
    'jam' => '06:52:25',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  57 => 
  array (
    'id_presensi' => 5820,
    'nis' => 14328,
    'tanggal' => '2026-07-23',
    'jam' => '06:52:28',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  58 => 
  array (
    'id_presensi' => 5821,
    'nis' => 14336,
    'tanggal' => '2026-07-23',
    'jam' => '06:52:33',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  59 => 
  array (
    'id_presensi' => 5822,
    'nis' => 14793,
    'tanggal' => '2026-07-23',
    'jam' => '06:52:38',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  60 => 
  array (
    'id_presensi' => 5823,
    'nis' => 14784,
    'tanggal' => '2026-07-23',
    'jam' => '06:52:41',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  61 => 
  array (
    'id_presensi' => 5824,
    'nis' => 13872,
    'tanggal' => '2026-07-23',
    'jam' => '06:52:42',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  62 => 
  array (
    'id_presensi' => 5825,
    'nis' => 14372,
    'tanggal' => '2026-07-23',
    'jam' => '06:52:51',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  63 => 
  array (
    'id_presensi' => 5826,
    'nis' => 13968,
    'tanggal' => '2026-07-23',
    'jam' => '06:52:59',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  64 => 
  array (
    'id_presensi' => 5827,
    'nis' => 13993,
    'tanggal' => '2026-07-23',
    'jam' => '06:53:05',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  65 => 
  array (
    'id_presensi' => 5828,
    'nis' => 14722,
    'tanggal' => '2026-07-23',
    'jam' => '06:53:06',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  66 => 
  array (
    'id_presensi' => 5829,
    'nis' => 13990,
    'tanggal' => '2026-07-23',
    'jam' => '06:53:07',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  67 => 
  array (
    'id_presensi' => 5830,
    'nis' => 13977,
    'tanggal' => '2026-07-23',
    'jam' => '06:53:09',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  68 => 
  array (
    'id_presensi' => 5831,
    'nis' => 14736,
    'tanggal' => '2026-07-23',
    'jam' => '06:53:10',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  69 => 
  array (
    'id_presensi' => 5832,
    'nis' => 14696,
    'tanggal' => '2026-07-23',
    'jam' => '06:53:14',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  70 => 
  array (
    'id_presensi' => 5833,
    'nis' => 14602,
    'tanggal' => '2026-07-23',
    'jam' => '06:53:18',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  71 => 
  array (
    'id_presensi' => 5834,
    'nis' => 14298,
    'tanggal' => '2026-07-23',
    'jam' => '06:53:25',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  72 => 
  array (
    'id_presensi' => 5835,
    'nis' => 14456,
    'tanggal' => '2026-07-23',
    'jam' => '06:53:26',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  73 => 
  array (
    'id_presensi' => 5836,
    'nis' => 14679,
    'tanggal' => '2026-07-23',
    'jam' => '06:53:28',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  74 => 
  array (
    'id_presensi' => 5837,
    'nis' => 14297,
    'tanggal' => '2026-07-23',
    'jam' => '06:53:28',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  75 => 
  array (
    'id_presensi' => 5838,
    'nis' => 14454,
    'tanggal' => '2026-07-23',
    'jam' => '06:53:30',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  76 => 
  array (
    'id_presensi' => 5839,
    'nis' => 13992,
    'tanggal' => '2026-07-23',
    'jam' => '06:53:32',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  77 => 
  array (
    'id_presensi' => 5840,
    'nis' => 13971,
    'tanggal' => '2026-07-23',
    'jam' => '06:53:33',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  78 => 
  array (
    'id_presensi' => 5841,
    'nis' => 14469,
    'tanggal' => '2026-07-23',
    'jam' => '06:53:34',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  79 => 
  array (
    'id_presensi' => 5842,
    'nis' => 13980,
    'tanggal' => '2026-07-23',
    'jam' => '06:53:37',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  80 => 
  array (
    'id_presensi' => 5843,
    'nis' => 14000,
    'tanggal' => '2026-07-23',
    'jam' => '06:53:40',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  81 => 
  array (
    'id_presensi' => 5844,
    'nis' => 14032,
    'tanggal' => '2026-07-23',
    'jam' => '06:53:41',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  82 => 
  array (
    'id_presensi' => 5845,
    'nis' => 14034,
    'tanggal' => '2026-07-23',
    'jam' => '06:53:44',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  83 => 
  array (
    'id_presensi' => 5846,
    'nis' => 14318,
    'tanggal' => '2026-07-23',
    'jam' => '06:53:45',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  84 => 
  array (
    'id_presensi' => 5847,
    'nis' => 14003,
    'tanggal' => '2026-07-23',
    'jam' => '06:53:46',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  85 => 
  array (
    'id_presensi' => 5848,
    'nis' => 14023,
    'tanggal' => '2026-07-23',
    'jam' => '06:53:48',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  86 => 
  array (
    'id_presensi' => 5849,
    'nis' => 14485,
    'tanggal' => '2026-07-23',
    'jam' => '06:53:48',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  87 => 
  array (
    'id_presensi' => 5850,
    'nis' => 14335,
    'tanggal' => '2026-07-23',
    'jam' => '06:53:52',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  88 => 
  array (
    'id_presensi' => 5851,
    'nis' => 14713,
    'tanggal' => '2026-07-23',
    'jam' => '06:54:12',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  89 => 
  array (
    'id_presensi' => 5852,
    'nis' => 14718,
    'tanggal' => '2026-07-23',
    'jam' => '06:54:15',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  90 => 
  array (
    'id_presensi' => 5853,
    'nis' => 13888,
    'tanggal' => '2026-07-23',
    'jam' => '06:54:18',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  91 => 
  array (
    'id_presensi' => 5854,
    'nis' => 14730,
    'tanggal' => '2026-07-23',
    'jam' => '06:54:18',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  92 => 
  array (
    'id_presensi' => 5855,
    'nis' => 14223,
    'tanggal' => '2026-07-23',
    'jam' => '06:54:22',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  93 => 
  array (
    'id_presensi' => 5856,
    'nis' => 14720,
    'tanggal' => '2026-07-23',
    'jam' => '06:54:24',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  94 => 
  array (
    'id_presensi' => 5857,
    'nis' => 14461,
    'tanggal' => '2026-07-23',
    'jam' => '06:54:24',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  95 => 
  array (
    'id_presensi' => 5858,
    'nis' => 14450,
    'tanggal' => '2026-07-23',
    'jam' => '06:54:26',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  96 => 
  array (
    'id_presensi' => 5859,
    'nis' => 14755,
    'tanggal' => '2026-07-23',
    'jam' => '06:54:27',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  97 => 
  array (
    'id_presensi' => 5860,
    'nis' => 14236,
    'tanggal' => '2026-07-23',
    'jam' => '06:54:31',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  98 => 
  array (
    'id_presensi' => 5861,
    'nis' => 14507,
    'tanggal' => '2026-07-23',
    'jam' => '06:54:31',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  99 => 
  array (
    'id_presensi' => 5862,
    'nis' => 14303,
    'tanggal' => '2026-07-23',
    'jam' => '06:54:33',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  100 => 
  array (
    'id_presensi' => 5863,
    'nis' => 14483,
    'tanggal' => '2026-07-23',
    'jam' => '06:54:33',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  101 => 
  array (
    'id_presensi' => 5864,
    'nis' => 13876,
    'tanggal' => '2026-07-23',
    'jam' => '06:54:40',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  102 => 
  array (
    'id_presensi' => 5865,
    'nis' => 14002,
    'tanggal' => '2026-07-23',
    'jam' => '06:54:47',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  103 => 
  array (
    'id_presensi' => 5866,
    'nis' => 14231,
    'tanggal' => '2026-07-23',
    'jam' => '06:55:04',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  104 => 
  array (
    'id_presensi' => 5867,
    'nis' => 14228,
    'tanggal' => '2026-07-23',
    'jam' => '06:55:04',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  105 => 
  array (
    'id_presensi' => 5868,
    'nis' => 14712,
    'tanggal' => '2026-07-23',
    'jam' => '06:55:08',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  106 => 
  array (
    'id_presensi' => 5869,
    'nis' => 14753,
    'tanggal' => '2026-07-23',
    'jam' => '06:55:11',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  107 => 
  array (
    'id_presensi' => 5870,
    'nis' => 14747,
    'tanggal' => '2026-07-23',
    'jam' => '06:55:15',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  108 => 
  array (
    'id_presensi' => 5871,
    'nis' => 14706,
    'tanggal' => '2026-07-23',
    'jam' => '06:55:17',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  109 => 
  array (
    'id_presensi' => 5872,
    'nis' => 14496,
    'tanggal' => '2026-07-23',
    'jam' => '06:55:20',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  110 => 
  array (
    'id_presensi' => 5873,
    'nis' => 14324,
    'tanggal' => '2026-07-23',
    'jam' => '06:55:22',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  111 => 
  array (
    'id_presensi' => 5874,
    'nis' => 14495,
    'tanggal' => '2026-07-23',
    'jam' => '06:55:25',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  112 => 
  array (
    'id_presensi' => 5875,
    'nis' => 14778,
    'tanggal' => '2026-07-23',
    'jam' => '06:55:28',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  113 => 
  array (
    'id_presensi' => 5876,
    'nis' => 14680,
    'tanggal' => '2026-07-23',
    'jam' => '06:55:32',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  114 => 
  array (
    'id_presensi' => 5877,
    'nis' => 14320,
    'tanggal' => '2026-07-23',
    'jam' => '06:55:34',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  115 => 
  array (
    'id_presensi' => 5878,
    'nis' => 14600,
    'tanggal' => '2026-07-23',
    'jam' => '06:55:38',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  116 => 
  array (
    'id_presensi' => 5879,
    'nis' => 14293,
    'tanggal' => '2026-07-23',
    'jam' => '06:55:43',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  117 => 
  array (
    'id_presensi' => 5880,
    'nis' => 14306,
    'tanggal' => '2026-07-23',
    'jam' => '06:55:46',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  118 => 
  array (
    'id_presensi' => 5881,
    'nis' => 14710,
    'tanggal' => '2026-07-23',
    'jam' => '06:55:48',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  119 => 
  array (
    'id_presensi' => 5882,
    'nis' => 14451,
    'tanggal' => '2026-07-23',
    'jam' => '06:55:49',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  120 => 
  array (
    'id_presensi' => 5883,
    'nis' => 14725,
    'tanggal' => '2026-07-23',
    'jam' => '06:55:51',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  121 => 
  array (
    'id_presensi' => 5884,
    'nis' => 14760,
    'tanggal' => '2026-07-23',
    'jam' => '06:55:52',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  122 => 
  array (
    'id_presensi' => 5885,
    'nis' => 14511,
    'tanggal' => '2026-07-23',
    'jam' => '06:55:55',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  123 => 
  array (
    'id_presensi' => 5886,
    'nis' => 14599,
    'tanggal' => '2026-07-23',
    'jam' => '06:55:58',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  124 => 
  array (
    'id_presensi' => 5887,
    'nis' => 14595,
    'tanggal' => '2026-07-23',
    'jam' => '06:56:00',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  125 => 
  array (
    'id_presensi' => 5888,
    'nis' => 14768,
    'tanggal' => '2026-07-23',
    'jam' => '06:56:02',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  126 => 
  array (
    'id_presensi' => 5889,
    'nis' => 14589,
    'tanggal' => '2026-07-23',
    'jam' => '06:56:03',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  127 => 
  array (
    'id_presensi' => 5890,
    'nis' => 13997,
    'tanggal' => '2026-07-23',
    'jam' => '06:56:06',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  128 => 
  array (
    'id_presensi' => 5891,
    'nis' => 14291,
    'tanggal' => '2026-07-23',
    'jam' => '06:56:07',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  129 => 
  array (
    'id_presensi' => 5892,
    'nis' => 14491,
    'tanggal' => '2026-07-23',
    'jam' => '06:56:08',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  130 => 
  array (
    'id_presensi' => 5893,
    'nis' => 13978,
    'tanggal' => '2026-07-23',
    'jam' => '06:56:20',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  131 => 
  array (
    'id_presensi' => 5894,
    'nis' => 14330,
    'tanggal' => '2026-07-23',
    'jam' => '06:56:23',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  132 => 
  array (
    'id_presensi' => 5895,
    'nis' => 13883,
    'tanggal' => '2026-07-23',
    'jam' => '06:56:25',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  133 => 
  array (
    'id_presensi' => 5896,
    'nis' => 14305,
    'tanggal' => '2026-07-23',
    'jam' => '06:56:27',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  134 => 
  array (
    'id_presensi' => 5897,
    'nis' => 14333,
    'tanggal' => '2026-07-23',
    'jam' => '06:56:30',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  135 => 
  array (
    'id_presensi' => 5898,
    'nis' => 14327,
    'tanggal' => '2026-07-23',
    'jam' => '06:56:31',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  136 => 
  array (
    'id_presensi' => 5899,
    'nis' => 14802,
    'tanggal' => '2026-07-23',
    'jam' => '06:56:32',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  137 => 
  array (
    'id_presensi' => 5900,
    'nis' => 14332,
    'tanggal' => '2026-07-23',
    'jam' => '06:56:33',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  138 => 
  array (
    'id_presensi' => 5901,
    'nis' => 14329,
    'tanggal' => '2026-07-23',
    'jam' => '06:56:37',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  139 => 
  array (
    'id_presensi' => 5902,
    'nis' => 14785,
    'tanggal' => '2026-07-23',
    'jam' => '06:56:38',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  140 => 
  array (
    'id_presensi' => 5903,
    'nis' => 14786,
    'tanggal' => '2026-07-23',
    'jam' => '06:56:40',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  141 => 
  array (
    'id_presensi' => 5904,
    'nis' => 13925,
    'tanggal' => '2026-07-23',
    'jam' => '06:56:42',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  142 => 
  array (
    'id_presensi' => 5905,
    'nis' => 14011,
    'tanggal' => '2026-07-23',
    'jam' => '06:56:43',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  143 => 
  array (
    'id_presensi' => 5906,
    'nis' => 14244,
    'tanggal' => '2026-07-23',
    'jam' => '06:56:46',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  144 => 
  array (
    'id_presensi' => 5907,
    'nis' => 13897,
    'tanggal' => '2026-07-23',
    'jam' => '06:56:46',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  145 => 
  array (
    'id_presensi' => 5908,
    'nis' => 14791,
    'tanggal' => '2026-07-23',
    'jam' => '06:56:47',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  146 => 
  array (
    'id_presensi' => 5909,
    'nis' => 14777,
    'tanggal' => '2026-07-23',
    'jam' => '06:56:51',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  147 => 
  array (
    'id_presensi' => 5910,
    'nis' => 13904,
    'tanggal' => '2026-07-23',
    'jam' => '06:56:51',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  148 => 
  array (
    'id_presensi' => 5911,
    'nis' => 14800,
    'tanggal' => '2026-07-23',
    'jam' => '06:56:51',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  149 => 
  array (
    'id_presensi' => 5912,
    'nis' => 14735,
    'tanggal' => '2026-07-23',
    'jam' => '06:56:52',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  150 => 
  array (
    'id_presensi' => 5913,
    'nis' => 14707,
    'tanggal' => '2026-07-23',
    'jam' => '06:56:55',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  151 => 
  array (
    'id_presensi' => 5914,
    'nis' => 13900,
    'tanggal' => '2026-07-23',
    'jam' => '06:56:57',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  152 => 
  array (
    'id_presensi' => 5915,
    'nis' => 14797,
    'tanggal' => '2026-07-23',
    'jam' => '06:56:57',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  153 => 
  array (
    'id_presensi' => 5916,
    'nis' => 14804,
    'tanggal' => '2026-07-23',
    'jam' => '06:56:59',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  154 => 
  array (
    'id_presensi' => 5917,
    'nis' => 14006,
    'tanggal' => '2026-07-23',
    'jam' => '06:57:01',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  155 => 
  array (
    'id_presensi' => 5918,
    'nis' => 14782,
    'tanggal' => '2026-07-23',
    'jam' => '06:57:01',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  156 => 
  array (
    'id_presensi' => 5919,
    'nis' => 14607,
    'tanggal' => '2026-07-23',
    'jam' => '06:57:01',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  157 => 
  array (
    'id_presensi' => 5920,
    'nis' => 14798,
    'tanggal' => '2026-07-23',
    'jam' => '06:57:04',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  158 => 
  array (
    'id_presensi' => 5921,
    'nis' => 14801,
    'tanggal' => '2026-07-23',
    'jam' => '06:57:04',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  159 => 
  array (
    'id_presensi' => 5922,
    'nis' => 14794,
    'tanggal' => '2026-07-23',
    'jam' => '06:57:06',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  160 => 
  array (
    'id_presensi' => 5923,
    'nis' => 14803,
    'tanggal' => '2026-07-23',
    'jam' => '06:57:06',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  161 => 
  array (
    'id_presensi' => 5924,
    'nis' => 13974,
    'tanggal' => '2026-07-23',
    'jam' => '06:57:12',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  162 => 
  array (
    'id_presensi' => 5925,
    'nis' => 13873,
    'tanggal' => '2026-07-23',
    'jam' => '06:57:13',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  163 => 
  array (
    'id_presensi' => 5926,
    'nis' => 13879,
    'tanggal' => '2026-07-23',
    'jam' => '06:57:16',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  164 => 
  array (
    'id_presensi' => 5927,
    'nis' => 13986,
    'tanggal' => '2026-07-23',
    'jam' => '06:57:18',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  165 => 
  array (
    'id_presensi' => 5928,
    'nis' => 13881,
    'tanggal' => '2026-07-23',
    'jam' => '06:57:19',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  166 => 
  array (
    'id_presensi' => 5929,
    'nis' => 13864,
    'tanggal' => '2026-07-23',
    'jam' => '06:57:21',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  167 => 
  array (
    'id_presensi' => 5930,
    'nis' => 13987,
    'tanggal' => '2026-07-23',
    'jam' => '06:57:22',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  168 => 
  array (
    'id_presensi' => 5931,
    'nis' => 13869,
    'tanggal' => '2026-07-23',
    'jam' => '06:57:24',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  169 => 
  array (
    'id_presensi' => 5932,
    'nis' => 14289,
    'tanggal' => '2026-07-23',
    'jam' => '06:57:26',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  170 => 
  array (
    'id_presensi' => 5933,
    'nis' => 14325,
    'tanggal' => '2026-07-23',
    'jam' => '06:57:28',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  171 => 
  array (
    'id_presensi' => 5934,
    'nis' => 14432,
    'tanggal' => '2026-07-23',
    'jam' => '06:57:30',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  172 => 
  array (
    'id_presensi' => 5935,
    'nis' => 14312,
    'tanggal' => '2026-07-23',
    'jam' => '06:57:31',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  173 => 
  array (
    'id_presensi' => 5936,
    'nis' => 14338,
    'tanggal' => '2026-07-23',
    'jam' => '06:57:34',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  174 => 
  array (
    'id_presensi' => 5937,
    'nis' => 14296,
    'tanggal' => '2026-07-23',
    'jam' => '06:57:37',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  175 => 
  array (
    'id_presensi' => 5938,
    'nis' => 14016,
    'tanggal' => '2026-07-23',
    'jam' => '06:57:39',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  176 => 
  array (
    'id_presensi' => 5939,
    'nis' => 14423,
    'tanggal' => '2026-07-23',
    'jam' => '06:57:42',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  177 => 
  array (
    'id_presensi' => 5940,
    'nis' => 14316,
    'tanggal' => '2026-07-23',
    'jam' => '06:57:43',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  178 => 
  array (
    'id_presensi' => 5941,
    'nis' => 14445,
    'tanggal' => '2026-07-23',
    'jam' => '06:57:44',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  179 => 
  array (
    'id_presensi' => 5942,
    'nis' => 14442,
    'tanggal' => '2026-07-23',
    'jam' => '06:57:47',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  180 => 
  array (
    'id_presensi' => 5943,
    'nis' => 14462,
    'tanggal' => '2026-07-23',
    'jam' => '06:57:47',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  181 => 
  array (
    'id_presensi' => 5944,
    'nis' => 14433,
    'tanggal' => '2026-07-23',
    'jam' => '06:57:47',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  182 => 
  array (
    'id_presensi' => 5945,
    'nis' => 14447,
    'tanggal' => '2026-07-23',
    'jam' => '06:57:49',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  183 => 
  array (
    'id_presensi' => 5946,
    'nis' => 14420,
    'tanggal' => '2026-07-23',
    'jam' => '06:57:50',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  184 => 
  array (
    'id_presensi' => 5947,
    'nis' => 14474,
    'tanggal' => '2026-07-23',
    'jam' => '06:57:53',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  185 => 
  array (
    'id_presensi' => 5948,
    'nis' => 14429,
    'tanggal' => '2026-07-23',
    'jam' => '06:57:53',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  186 => 
  array (
    'id_presensi' => 5949,
    'nis' => 14419,
    'tanggal' => '2026-07-23',
    'jam' => '06:57:54',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  187 => 
  array (
    'id_presensi' => 5950,
    'nis' => 14452,
    'tanggal' => '2026-07-23',
    'jam' => '06:57:55',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  188 => 
  array (
    'id_presensi' => 5951,
    'nis' => 14425,
    'tanggal' => '2026-07-23',
    'jam' => '06:57:55',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  189 => 
  array (
    'id_presensi' => 5952,
    'nis' => 14422,
    'tanggal' => '2026-07-23',
    'jam' => '06:57:57',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  190 => 
  array (
    'id_presensi' => 5953,
    'nis' => 13913,
    'tanggal' => '2026-07-23',
    'jam' => '06:57:57',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  191 => 
  array (
    'id_presensi' => 5954,
    'nis' => 14424,
    'tanggal' => '2026-07-23',
    'jam' => '06:57:58',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  192 => 
  array (
    'id_presensi' => 5955,
    'nis' => 14465,
    'tanggal' => '2026-07-23',
    'jam' => '06:57:58',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  193 => 
  array (
    'id_presensi' => 5956,
    'nis' => 14440,
    'tanggal' => '2026-07-23',
    'jam' => '06:57:59',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  194 => 
  array (
    'id_presensi' => 5957,
    'nis' => 14441,
    'tanggal' => '2026-07-23',
    'jam' => '06:58:01',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  195 => 
  array (
    'id_presensi' => 5958,
    'nis' => 14446,
    'tanggal' => '2026-07-23',
    'jam' => '06:58:02',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  196 => 
  array (
    'id_presensi' => 5959,
    'nis' => 14226,
    'tanggal' => '2026-07-23',
    'jam' => '06:58:04',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  197 => 
  array (
    'id_presensi' => 5960,
    'nis' => 14795,
    'tanggal' => '2026-07-23',
    'jam' => '06:58:12',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  198 => 
  array (
    'id_presensi' => 5961,
    'nis' => 14772,
    'tanggal' => '2026-07-23',
    'jam' => '06:58:12',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  199 => 
  array (
    'id_presensi' => 5962,
    'nis' => 13984,
    'tanggal' => '2026-07-23',
    'jam' => '06:58:15',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
));

        DB::table('presensi')->insert(array (
  0 => 
  array (
    'id_presensi' => 5963,
    'nis' => 13983,
    'tanggal' => '2026-07-23',
    'jam' => '06:58:17',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  1 => 
  array (
    'id_presensi' => 5964,
    'nis' => 14013,
    'tanggal' => '2026-07-23',
    'jam' => '06:58:18',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  2 => 
  array (
    'id_presensi' => 5965,
    'nis' => 13976,
    'tanggal' => '2026-07-23',
    'jam' => '06:58:22',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  3 => 
  array (
    'id_presensi' => 5966,
    'nis' => 14004,
    'tanggal' => '2026-07-23',
    'jam' => '06:58:25',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  4 => 
  array (
    'id_presensi' => 5967,
    'nis' => 14018,
    'tanggal' => '2026-07-23',
    'jam' => '06:58:29',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  5 => 
  array (
    'id_presensi' => 5968,
    'nis' => 13998,
    'tanggal' => '2026-07-23',
    'jam' => '06:58:43',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  6 => 
  array (
    'id_presensi' => 5969,
    'nis' => 13981,
    'tanggal' => '2026-07-23',
    'jam' => '06:58:45',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  7 => 
  array (
    'id_presensi' => 5970,
    'nis' => 13996,
    'tanggal' => '2026-07-23',
    'jam' => '06:58:48',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  8 => 
  array (
    'id_presensi' => 5971,
    'nis' => 14754,
    'tanggal' => '2026-07-23',
    'jam' => '06:58:52',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  9 => 
  array (
    'id_presensi' => 5972,
    'nis' => 14313,
    'tanggal' => '2026-07-23',
    'jam' => '06:58:59',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  10 => 
  array (
    'id_presensi' => 5973,
    'nis' => 13991,
    'tanggal' => '2026-07-23',
    'jam' => '06:59:04',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  11 => 
  array (
    'id_presensi' => 5974,
    'nis' => 14030,
    'tanggal' => '2026-07-23',
    'jam' => '06:59:05',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  12 => 
  array (
    'id_presensi' => 5975,
    'nis' => 14756,
    'tanggal' => '2026-07-23',
    'jam' => '06:59:10',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  13 => 
  array (
    'id_presensi' => 5976,
    'nis' => 14314,
    'tanggal' => '2026-07-23',
    'jam' => '06:59:14',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  14 => 
  array (
    'id_presensi' => 5977,
    'nis' => 14792,
    'tanggal' => '2026-07-23',
    'jam' => '06:59:15',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  15 => 
  array (
    'id_presensi' => 5978,
    'nis' => 14580,
    'tanggal' => '2026-07-23',
    'jam' => '06:59:17',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  16 => 
  array (
    'id_presensi' => 5979,
    'nis' => 14020,
    'tanggal' => '2026-07-23',
    'jam' => '06:59:17',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  17 => 
  array (
    'id_presensi' => 5980,
    'nis' => 14300,
    'tanggal' => '2026-07-23',
    'jam' => '06:59:17',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  18 => 
  array (
    'id_presensi' => 5981,
    'nis' => 14427,
    'tanggal' => '2026-07-23',
    'jam' => '06:59:22',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  19 => 
  array (
    'id_presensi' => 5982,
    'nis' => 14776,
    'tanggal' => '2026-07-23',
    'jam' => '06:59:22',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  20 => 
  array (
    'id_presensi' => 5983,
    'nis' => 14319,
    'tanggal' => '2026-07-23',
    'jam' => '06:59:24',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  21 => 
  array (
    'id_presensi' => 5984,
    'nis' => 14790,
    'tanggal' => '2026-07-23',
    'jam' => '06:59:25',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  22 => 
  array (
    'id_presensi' => 5985,
    'nis' => 14764,
    'tanggal' => '2026-07-23',
    'jam' => '06:59:26',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  23 => 
  array (
    'id_presensi' => 5986,
    'nis' => 14705,
    'tanggal' => '2026-07-23',
    'jam' => '06:59:31',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  24 => 
  array (
    'id_presensi' => 5987,
    'nis' => 14494,
    'tanggal' => '2026-07-23',
    'jam' => '06:59:36',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  25 => 
  array (
    'id_presensi' => 5988,
    'nis' => 14677,
    'tanggal' => '2026-07-23',
    'jam' => '06:59:38',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  26 => 
  array (
    'id_presensi' => 5989,
    'nis' => 14584,
    'tanggal' => '2026-07-23',
    'jam' => '06:59:39',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  27 => 
  array (
    'id_presensi' => 5990,
    'nis' => 14690,
    'tanggal' => '2026-07-23',
    'jam' => '06:59:40',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  28 => 
  array (
    'id_presensi' => 5991,
    'nis' => 14484,
    'tanggal' => '2026-07-23',
    'jam' => '06:59:41',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  29 => 
  array (
    'id_presensi' => 5992,
    'nis' => 14510,
    'tanggal' => '2026-07-23',
    'jam' => '06:59:42',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  30 => 
  array (
    'id_presensi' => 5993,
    'nis' => 14478,
    'tanggal' => '2026-07-23',
    'jam' => '06:59:45',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  31 => 
  array (
    'id_presensi' => 5994,
    'nis' => 14170,
    'tanggal' => '2026-07-23',
    'jam' => '06:59:45',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  32 => 
  array (
    'id_presensi' => 5995,
    'nis' => 14698,
    'tanggal' => '2026-07-23',
    'jam' => '06:59:56',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  33 => 
  array (
    'id_presensi' => 5996,
    'nis' => 14235,
    'tanggal' => '2026-07-23',
    'jam' => '06:59:57',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  34 => 
  array (
    'id_presensi' => 5997,
    'nis' => 14686,
    'tanggal' => '2026-07-23',
    'jam' => '06:59:58',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  35 => 
  array (
    'id_presensi' => 5998,
    'nis' => 14367,
    'tanggal' => '2026-07-23',
    'jam' => '07:00:02',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  36 => 
  array (
    'id_presensi' => 5999,
    'nis' => 14326,
    'tanggal' => '2026-07-23',
    'jam' => '07:00:02',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  37 => 
  array (
    'id_presensi' => 6000,
    'nis' => 14311,
    'tanggal' => '2026-07-23',
    'jam' => '07:00:04',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  38 => 
  array (
    'id_presensi' => 6001,
    'nis' => 14237,
    'tanggal' => '2026-07-23',
    'jam' => '07:00:06',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  39 => 
  array (
    'id_presensi' => 6002,
    'nis' => 14606,
    'tanggal' => '2026-07-23',
    'jam' => '07:00:06',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  40 => 
  array (
    'id_presensi' => 6003,
    'nis' => 14463,
    'tanggal' => '2026-07-23',
    'jam' => '07:00:07',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  41 => 
  array (
    'id_presensi' => 6004,
    'nis' => 14164,
    'tanggal' => '2026-07-23',
    'jam' => '07:00:07',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  42 => 
  array (
    'id_presensi' => 6005,
    'nis' => 14448,
    'tanggal' => '2026-07-23',
    'jam' => '07:00:09',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  43 => 
  array (
    'id_presensi' => 6006,
    'nis' => 14174,
    'tanggal' => '2026-07-23',
    'jam' => '07:00:09',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  44 => 
  array (
    'id_presensi' => 6007,
    'nis' => 14464,
    'tanggal' => '2026-07-23',
    'jam' => '07:00:10',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  45 => 
  array (
    'id_presensi' => 6008,
    'nis' => 14142,
    'tanggal' => '2026-07-23',
    'jam' => '07:00:11',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  46 => 
  array (
    'id_presensi' => 6009,
    'nis' => 14579,
    'tanggal' => '2026-07-23',
    'jam' => '07:00:12',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  47 => 
  array (
    'id_presensi' => 6010,
    'nis' => 14439,
    'tanggal' => '2026-07-23',
    'jam' => '07:00:13',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  48 => 
  array (
    'id_presensi' => 6011,
    'nis' => 14598,
    'tanggal' => '2026-07-23',
    'jam' => '07:00:15',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  49 => 
  array (
    'id_presensi' => 6012,
    'nis' => 14603,
    'tanggal' => '2026-07-23',
    'jam' => '07:00:17',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  50 => 
  array (
    'id_presensi' => 6013,
    'nis' => 14158,
    'tanggal' => '2026-07-23',
    'jam' => '07:00:18',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  51 => 
  array (
    'id_presensi' => 6014,
    'nis' => 14337,
    'tanggal' => '2026-07-23',
    'jam' => '07:00:19',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  52 => 
  array (
    'id_presensi' => 6015,
    'nis' => 14752,
    'tanggal' => '2026-07-23',
    'jam' => '07:00:20',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  53 => 
  array (
    'id_presensi' => 6016,
    'nis' => 14487,
    'tanggal' => '2026-07-23',
    'jam' => '07:00:20',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  54 => 
  array (
    'id_presensi' => 6017,
    'nis' => 14161,
    'tanggal' => '2026-07-23',
    'jam' => '07:00:21',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  55 => 
  array (
    'id_presensi' => 6018,
    'nis' => 14592,
    'tanggal' => '2026-07-23',
    'jam' => '07:00:23',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  56 => 
  array (
    'id_presensi' => 6019,
    'nis' => 14287,
    'tanggal' => '2026-07-23',
    'jam' => '07:00:23',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  57 => 
  array (
    'id_presensi' => 6020,
    'nis' => 14015,
    'tanggal' => '2026-07-23',
    'jam' => '07:00:26',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  58 => 
  array (
    'id_presensi' => 6021,
    'nis' => 13989,
    'tanggal' => '2026-07-23',
    'jam' => '07:00:29',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  59 => 
  array (
    'id_presensi' => 6022,
    'nis' => 13908,
    'tanggal' => '2026-07-23',
    'jam' => '07:00:30',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  60 => 
  array (
    'id_presensi' => 6023,
    'nis' => 14683,
    'tanggal' => '2026-07-23',
    'jam' => '07:00:31',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  61 => 
  array (
    'id_presensi' => 6024,
    'nis' => 13970,
    'tanggal' => '2026-07-23',
    'jam' => '07:00:34',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  62 => 
  array (
    'id_presensi' => 6025,
    'nis' => 14704,
    'tanggal' => '2026-07-23',
    'jam' => '07:00:34',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  63 => 
  array (
    'id_presensi' => 6026,
    'nis' => 14738,
    'tanggal' => '2026-07-23',
    'jam' => '07:00:37',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  64 => 
  array (
    'id_presensi' => 6027,
    'nis' => 14512,
    'tanggal' => '2026-07-23',
    'jam' => '07:00:38',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  65 => 
  array (
    'id_presensi' => 6028,
    'nis' => 14727,
    'tanggal' => '2026-07-23',
    'jam' => '07:00:41',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  66 => 
  array (
    'id_presensi' => 6029,
    'nis' => 14716,
    'tanggal' => '2026-07-23',
    'jam' => '07:00:41',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  67 => 
  array (
    'id_presensi' => 6030,
    'nis' => 14033,
    'tanggal' => '2026-07-23',
    'jam' => '07:00:45',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  68 => 
  array (
    'id_presensi' => 6031,
    'nis' => 14304,
    'tanggal' => '2026-07-23',
    'jam' => '07:00:46',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  69 => 
  array (
    'id_presensi' => 6032,
    'nis' => 14748,
    'tanggal' => '2026-07-23',
    'jam' => '07:00:47',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  70 => 
  array (
    'id_presensi' => 6033,
    'nis' => 14308,
    'tanggal' => '2026-07-23',
    'jam' => '07:00:51',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  71 => 
  array (
    'id_presensi' => 6034,
    'nis' => 14309,
    'tanggal' => '2026-07-23',
    'jam' => '07:00:52',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  72 => 
  array (
    'id_presensi' => 6035,
    'nis' => 14334,
    'tanggal' => '2026-07-23',
    'jam' => '07:00:55',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  73 => 
  array (
    'id_presensi' => 6036,
    'nis' => 14301,
    'tanggal' => '2026-07-23',
    'jam' => '07:01:01',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  74 => 
  array (
    'id_presensi' => 6037,
    'nis' => 13918,
    'tanggal' => '2026-07-23',
    'jam' => '07:01:02',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  75 => 
  array (
    'id_presensi' => 6038,
    'nis' => 14323,
    'tanggal' => '2026-07-23',
    'jam' => '07:01:03',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  76 => 
  array (
    'id_presensi' => 6039,
    'nis' => 13901,
    'tanggal' => '2026-07-23',
    'jam' => '07:01:10',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  77 => 
  array (
    'id_presensi' => 6040,
    'nis' => 14789,
    'tanggal' => '2026-07-23',
    'jam' => '07:01:16',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  78 => 
  array (
    'id_presensi' => 6041,
    'nis' => 14783,
    'tanggal' => '2026-07-23',
    'jam' => '07:01:19',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  79 => 
  array (
    'id_presensi' => 6042,
    'nis' => 13894,
    'tanggal' => '2026-07-23',
    'jam' => '07:01:20',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  80 => 
  array (
    'id_presensi' => 6043,
    'nis' => 14239,
    'tanggal' => '2026-07-23',
    'jam' => '07:01:22',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  81 => 
  array (
    'id_presensi' => 6044,
    'nis' => 13886,
    'tanggal' => '2026-07-23',
    'jam' => '07:01:22',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  82 => 
  array (
    'id_presensi' => 6045,
    'nis' => 13871,
    'tanggal' => '2026-07-23',
    'jam' => '07:01:24',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  83 => 
  array (
    'id_presensi' => 6046,
    'nis' => 13905,
    'tanggal' => '2026-07-23',
    'jam' => '07:01:27',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  84 => 
  array (
    'id_presensi' => 6047,
    'nis' => 14005,
    'tanggal' => '2026-07-23',
    'jam' => '07:01:28',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  85 => 
  array (
    'id_presensi' => 6048,
    'nis' => 14017,
    'tanggal' => '2026-07-23',
    'jam' => '07:01:33',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  86 => 
  array (
    'id_presensi' => 6049,
    'nis' => 13923,
    'tanggal' => '2026-07-23',
    'jam' => '07:01:33',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  87 => 
  array (
    'id_presensi' => 6050,
    'nis' => 14009,
    'tanggal' => '2026-07-23',
    'jam' => '07:01:36',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  88 => 
  array (
    'id_presensi' => 6051,
    'nis' => 14021,
    'tanggal' => '2026-07-23',
    'jam' => '07:01:42',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  89 => 
  array (
    'id_presensi' => 6052,
    'nis' => 14031,
    'tanggal' => '2026-07-23',
    'jam' => '07:01:44',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  90 => 
  array (
    'id_presensi' => 6053,
    'nis' => 14007,
    'tanggal' => '2026-07-23',
    'jam' => '07:01:45',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  91 => 
  array (
    'id_presensi' => 6054,
    'nis' => 14699,
    'tanggal' => '2026-07-23',
    'jam' => '07:01:50',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  92 => 
  array (
    'id_presensi' => 6055,
    'nis' => 13995,
    'tanggal' => '2026-07-23',
    'jam' => '07:01:51',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  93 => 
  array (
    'id_presensi' => 6056,
    'nis' => 14675,
    'tanggal' => '2026-07-23',
    'jam' => '07:01:53',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  94 => 
  array (
    'id_presensi' => 6057,
    'nis' => 13967,
    'tanggal' => '2026-07-23',
    'jam' => '07:01:56',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  95 => 
  array (
    'id_presensi' => 6058,
    'nis' => 13887,
    'tanggal' => '2026-07-23',
    'jam' => '07:01:59',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  96 => 
  array (
    'id_presensi' => 6059,
    'nis' => 13880,
    'tanggal' => '2026-07-23',
    'jam' => '07:02:00',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  97 => 
  array (
    'id_presensi' => 6060,
    'nis' => 13867,
    'tanggal' => '2026-07-23',
    'jam' => '07:02:03',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  98 => 
  array (
    'id_presensi' => 6061,
    'nis' => 13889,
    'tanggal' => '2026-07-23',
    'jam' => '07:02:05',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  99 => 
  array (
    'id_presensi' => 6062,
    'nis' => 13870,
    'tanggal' => '2026-07-23',
    'jam' => '07:02:07',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  100 => 
  array (
    'id_presensi' => 6063,
    'nis' => 14230,
    'tanggal' => '2026-07-23',
    'jam' => '07:02:10',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  101 => 
  array (
    'id_presensi' => 6064,
    'nis' => 13878,
    'tanggal' => '2026-07-23',
    'jam' => '07:02:10',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  102 => 
  array (
    'id_presensi' => 6065,
    'nis' => 14232,
    'tanggal' => '2026-07-23',
    'jam' => '07:02:12',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  103 => 
  array (
    'id_presensi' => 6066,
    'nis' => 14457,
    'tanggal' => '2026-07-23',
    'jam' => '07:02:16',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  104 => 
  array (
    'id_presensi' => 6067,
    'nis' => 14733,
    'tanggal' => '2026-07-23',
    'jam' => '07:02:19',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  105 => 
  array (
    'id_presensi' => 6068,
    'nis' => 13885,
    'tanggal' => '2026-07-23',
    'jam' => '07:02:19',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  106 => 
  array (
    'id_presensi' => 6069,
    'nis' => 14214,
    'tanggal' => '2026-07-23',
    'jam' => '07:02:22',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  107 => 
  array (
    'id_presensi' => 6070,
    'nis' => 13862,
    'tanggal' => '2026-07-23',
    'jam' => '07:02:23',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  108 => 
  array (
    'id_presensi' => 6071,
    'nis' => 14224,
    'tanggal' => '2026-07-23',
    'jam' => '07:02:24',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  109 => 
  array (
    'id_presensi' => 6072,
    'nis' => 14246,
    'tanggal' => '2026-07-23',
    'jam' => '07:02:27',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  110 => 
  array (
    'id_presensi' => 6073,
    'nis' => 14714,
    'tanggal' => '2026-07-23',
    'jam' => '07:02:28',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  111 => 
  array (
    'id_presensi' => 6074,
    'nis' => 14369,
    'tanggal' => '2026-07-23',
    'jam' => '07:02:28',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  112 => 
  array (
    'id_presensi' => 6075,
    'nis' => 14299,
    'tanggal' => '2026-07-23',
    'jam' => '07:02:37',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  113 => 
  array (
    'id_presensi' => 6076,
    'nis' => 14261,
    'tanggal' => '2026-07-23',
    'jam' => '07:02:38',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  114 => 
  array (
    'id_presensi' => 6077,
    'nis' => 13890,
    'tanggal' => '2026-07-23',
    'jam' => '07:02:38',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  115 => 
  array (
    'id_presensi' => 6078,
    'nis' => 14426,
    'tanggal' => '2026-07-23',
    'jam' => '07:02:41',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  116 => 
  array (
    'id_presensi' => 6079,
    'nis' => 14376,
    'tanggal' => '2026-07-23',
    'jam' => '07:02:41',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  117 => 
  array (
    'id_presensi' => 6080,
    'nis' => 14436,
    'tanggal' => '2026-07-23',
    'jam' => '07:02:42',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  118 => 
  array (
    'id_presensi' => 6081,
    'nis' => 14687,
    'tanggal' => '2026-07-23',
    'jam' => '07:02:47',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  119 => 
  array (
    'id_presensi' => 6082,
    'nis' => 14014,
    'tanggal' => '2026-07-23',
    'jam' => '07:02:47',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  120 => 
  array (
    'id_presensi' => 6083,
    'nis' => 14684,
    'tanggal' => '2026-07-23',
    'jam' => '07:02:51',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  121 => 
  array (
    'id_presensi' => 6084,
    'nis' => 14700,
    'tanggal' => '2026-07-23',
    'jam' => '07:02:52',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  122 => 
  array (
    'id_presensi' => 6085,
    'nis' => 14676,
    'tanggal' => '2026-07-23',
    'jam' => '07:02:54',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  123 => 
  array (
    'id_presensi' => 6086,
    'nis' => 14503,
    'tanggal' => '2026-07-23',
    'jam' => '07:03:19',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  124 => 
  array (
    'id_presensi' => 6087,
    'nis' => 14482,
    'tanggal' => '2026-07-23',
    'jam' => '07:03:22',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  125 => 
  array (
    'id_presensi' => 6088,
    'nis' => 14780,
    'tanggal' => '2026-07-23',
    'jam' => '07:08:44',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  126 => 
  array (
    'id_presensi' => 6089,
    'nis' => 14763,
    'tanggal' => '2026-07-23',
    'jam' => '07:13:21',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  127 => 
  array (
    'id_presensi' => 6090,
    'nis' => 14479,
    'tanggal' => '2026-07-23',
    'jam' => '07:13:24',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  128 => 
  array (
    'id_presensi' => 6091,
    'nis' => 14594,
    'tanggal' => '2026-07-23',
    'jam' => '07:13:30',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  129 => 
  array (
    'id_presensi' => 6092,
    'nis' => 14717,
    'tanggal' => '2026-07-23',
    'jam' => '07:13:44',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  130 => 
  array (
    'id_presensi' => 6093,
    'nis' => 14767,
    'tanggal' => '2026-07-23',
    'jam' => '07:13:49',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  131 => 
  array (
    'id_presensi' => 6094,
    'nis' => 14138,
    'tanggal' => '2026-07-23',
    'jam' => '07:19:39',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  132 => 
  array (
    'id_presensi' => 6095,
    'nis' => 14153,
    'tanggal' => '2026-07-23',
    'jam' => '07:20:33',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  133 => 
  array (
    'id_presensi' => 6096,
    'nis' => 13911,
    'tanggal' => '2026-07-23',
    'jam' => '07:22:03',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  134 => 
  array (
    'id_presensi' => 6097,
    'nis' => 14155,
    'tanggal' => '2026-07-23',
    'jam' => '07:22:12',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  135 => 
  array (
    'id_presensi' => 6098,
    'nis' => 13924,
    'tanggal' => '2026-07-23',
    'jam' => '07:22:19',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  136 => 
  array (
    'id_presensi' => 6099,
    'nis' => 14143,
    'tanggal' => '2026-07-23',
    'jam' => '07:22:22',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  137 => 
  array (
    'id_presensi' => 6100,
    'nis' => 14152,
    'tanggal' => '2026-07-23',
    'jam' => '07:22:26',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  138 => 
  array (
    'id_presensi' => 6101,
    'nis' => 14173,
    'tanggal' => '2026-07-23',
    'jam' => '07:22:28',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  139 => 
  array (
    'id_presensi' => 6102,
    'nis' => 14157,
    'tanggal' => '2026-07-23',
    'jam' => '07:22:31',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  140 => 
  array (
    'id_presensi' => 6103,
    'nis' => 13896,
    'tanggal' => '2026-07-23',
    'jam' => '07:22:34',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  141 => 
  array (
    'id_presensi' => 6104,
    'nis' => 13927,
    'tanggal' => '2026-07-23',
    'jam' => '07:23:45',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  142 => 
  array (
    'id_presensi' => 6105,
    'nis' => 14488,
    'tanggal' => '2026-07-23',
    'jam' => '07:37:53',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
  143 => 
  array (
    'id_presensi' => 6106,
    'nis' => 13966,
    'tanggal' => '2026-07-23',
    'jam' => '07:46:33',
    'status' => '1',
    'keterangan' => 'Mesin finger – otomatis',
    'file' => NULL,
  ),
));


        Schema::enableForeignKeyConstraints();
    }
}