<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class KelasSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Schema::disableForeignKeyConstraints();

        // Table: kelas (43 rows)
        DB::table('kelas')->truncate();
        $kelasData = array (
  0 => 
  array (
    'id_kelas' => 2,
    'tahun_masuk' => '2023',
    'tingkat' => 13,
    'id_jurusan' => 1,
    'rombel' => 'RPL 2',
    'walikelas' => NULL,
    'status' => 'tidak',
  ),
  1 => 
  array (
    'id_kelas' => 38,
    'tahun_masuk' => '2025',
    'tingkat' => 11,
    'id_jurusan' => 5,
    'rombel' => 'TAV 1',
    'walikelas' => 181,
    'status' => 'aktif',
  ),
  2 => 
  array (
    'id_kelas' => 39,
    'tahun_masuk' => '2025',
    'tingkat' => 11,
    'id_jurusan' => 4,
    'rombel' => 'TPM 1',
    'walikelas' => 157,
    'status' => 'aktif',
  ),
  3 => 
  array (
    'id_kelas' => 40,
    'tahun_masuk' => '2025',
    'tingkat' => 11,
    'id_jurusan' => 4,
    'rombel' => 'TPM 2',
    'walikelas' => 133,
    'status' => 'aktif',
  ),
  4 => 
  array (
    'id_kelas' => 41,
    'tahun_masuk' => '2025',
    'tingkat' => 11,
    'id_jurusan' => 6,
    'rombel' => 'TKR 1',
    'walikelas' => 162,
    'status' => 'aktif',
  ),
  5 => 
  array (
    'id_kelas' => 42,
    'tahun_masuk' => '2025',
    'tingkat' => 11,
    'id_jurusan' => 6,
    'rombel' => 'TKR 2',
    'walikelas' => 177,
    'status' => 'aktif',
  ),
  6 => 
  array (
    'id_kelas' => 43,
    'tahun_masuk' => '2025',
    'tingkat' => 11,
    'id_jurusan' => 6,
    'rombel' => 'TKR 3',
    'walikelas' => 156,
    'status' => 'aktif',
  ),
  7 => 
  array (
    'id_kelas' => 44,
    'tahun_masuk' => '2025',
    'tingkat' => 11,
    'id_jurusan' => 6,
    'rombel' => 'TKR 4',
    'walikelas' => 165,
    'status' => 'aktif',
  ),
  8 => 
  array (
    'id_kelas' => 45,
    'tahun_masuk' => '2025',
    'tingkat' => 11,
    'id_jurusan' => 6,
    'rombel' => 'TKR 5',
    'walikelas' => 126,
    'status' => 'aktif',
  ),
  9 => 
  array (
    'id_kelas' => 46,
    'tahun_masuk' => '2025',
    'tingkat' => 11,
    'id_jurusan' => 3,
    'rombel' => 'TSM 1',
    'walikelas' => 176,
    'status' => 'aktif',
  ),
  10 => 
  array (
    'id_kelas' => 47,
    'tahun_masuk' => '2025',
    'tingkat' => 11,
    'id_jurusan' => 3,
    'rombel' => 'TSM 2',
    'walikelas' => 183,
    'status' => 'aktif',
  ),
  11 => 
  array (
    'id_kelas' => 48,
    'tahun_masuk' => '2025',
    'tingkat' => 11,
    'id_jurusan' => 3,
    'rombel' => 'TSM 3',
    'walikelas' => 172,
    'status' => 'aktif',
  ),
  12 => 
  array (
    'id_kelas' => 49,
    'tahun_masuk' => '2025',
    'tingkat' => 11,
    'id_jurusan' => 3,
    'rombel' => 'TSM 4',
    'walikelas' => 160,
    'status' => 'aktif',
  ),
  13 => 
  array (
    'id_kelas' => 50,
    'tahun_masuk' => '2025',
    'tingkat' => 11,
    'id_jurusan' => 1,
    'rombel' => 'RPL 1',
    'walikelas' => 146,
    'status' => 'aktif',
  ),
  14 => 
  array (
    'id_kelas' => 51,
    'tahun_masuk' => '2025',
    'tingkat' => 11,
    'id_jurusan' => 1,
    'rombel' => 'RPL 2',
    'walikelas' => 113,
    'status' => 'aktif',
  ),
  15 => 
  array (
    'id_kelas' => 52,
    'tahun_masuk' => '2024',
    'tingkat' => 12,
    'id_jurusan' => 5,
    'rombel' => 'TAV 1',
    'walikelas' => 103,
    'status' => 'aktif',
  ),
  16 => 
  array (
    'id_kelas' => 53,
    'tahun_masuk' => '2024',
    'tingkat' => 12,
    'id_jurusan' => 4,
    'rombel' => 'TPM 1',
    'walikelas' => 144,
    'status' => 'aktif',
  ),
  17 => 
  array (
    'id_kelas' => 54,
    'tahun_masuk' => '2024',
    'tingkat' => 12,
    'id_jurusan' => 4,
    'rombel' => 'TPM 2',
    'walikelas' => 128,
    'status' => 'aktif',
  ),
  18 => 
  array (
    'id_kelas' => 55,
    'tahun_masuk' => '2024',
    'tingkat' => 12,
    'id_jurusan' => 6,
    'rombel' => 'TKRO 1',
    'walikelas' => 152,
    'status' => 'aktif',
  ),
  19 => 
  array (
    'id_kelas' => 56,
    'tahun_masuk' => '2024',
    'tingkat' => 12,
    'id_jurusan' => 6,
    'rombel' => 'TKRO 2',
    'walikelas' => 112,
    'status' => 'aktif',
  ),
  20 => 
  array (
    'id_kelas' => 57,
    'tahun_masuk' => '2024',
    'tingkat' => 12,
    'id_jurusan' => 6,
    'rombel' => 'TKRO 3',
    'walikelas' => 182,
    'status' => 'aktif',
  ),
  21 => 
  array (
    'id_kelas' => 58,
    'tahun_masuk' => '2024',
    'tingkat' => 12,
    'id_jurusan' => 6,
    'rombel' => 'TKRO 4',
    'walikelas' => 148,
    'status' => 'aktif',
  ),
  22 => 
  array (
    'id_kelas' => 59,
    'tahun_masuk' => '2024',
    'tingkat' => 12,
    'id_jurusan' => 6,
    'rombel' => 'TKRO 5',
    'walikelas' => 159,
    'status' => 'aktif',
  ),
  23 => 
  array (
    'id_kelas' => 60,
    'tahun_masuk' => '2024',
    'tingkat' => 12,
    'id_jurusan' => 3,
    'rombel' => 'TBSM 1',
    'walikelas' => 136,
    'status' => 'aktif',
  ),
  24 => 
  array (
    'id_kelas' => 61,
    'tahun_masuk' => '2024',
    'tingkat' => 12,
    'id_jurusan' => 3,
    'rombel' => 'TBSM 2',
    'walikelas' => 169,
    'status' => 'aktif',
  ),
  25 => 
  array (
    'id_kelas' => 62,
    'tahun_masuk' => '2024',
    'tingkat' => 12,
    'id_jurusan' => 3,
    'rombel' => 'TBSM 3',
    'walikelas' => 164,
    'status' => 'aktif',
  ),
  26 => 
  array (
    'id_kelas' => 63,
    'tahun_masuk' => '2024',
    'tingkat' => 12,
    'id_jurusan' => 3,
    'rombel' => 'TBSM 4',
    'walikelas' => 175,
    'status' => 'aktif',
  ),
  27 => 
  array (
    'id_kelas' => 64,
    'tahun_masuk' => '2024',
    'tingkat' => 12,
    'id_jurusan' => 1,
    'rombel' => 'RPL 1',
    'walikelas' => 102,
    'status' => 'aktif',
  ),
  28 => 
  array (
    'id_kelas' => 65,
    'tahun_masuk' => '2024',
    'tingkat' => 12,
    'id_jurusan' => 1,
    'rombel' => 'RPL 2',
    'walikelas' => 104,
    'status' => 'aktif',
  ),
  29 => 
  array (
    'id_kelas' => 66,
    'tahun_masuk' => '2024',
    'tingkat' => 12,
    'id_jurusan' => 1,
    'rombel' => 'RPL 3',
    'walikelas' => 186,
    'status' => 'aktif',
  ),
  30 => 
  array (
    'id_kelas' => 67,
    'tahun_masuk' => '2026',
    'tingkat' => 10,
    'id_jurusan' => 5,
    'rombel' => 'TAV',
    'walikelas' => 125,
    'status' => 'aktif',
  ),
  31 => 
  array (
    'id_kelas' => 68,
    'tahun_masuk' => '2026',
    'tingkat' => 10,
    'id_jurusan' => 4,
    'rombel' => 'TPM 1',
    'walikelas' => 115,
    'status' => 'aktif',
  ),
  32 => 
  array (
    'id_kelas' => 69,
    'tahun_masuk' => '2026',
    'tingkat' => 10,
    'id_jurusan' => 4,
    'rombel' => 'TPM 2',
    'walikelas' => 137,
    'status' => 'aktif',
  ),
  33 => 
  array (
    'id_kelas' => 70,
    'tahun_masuk' => '2026',
    'tingkat' => 10,
    'id_jurusan' => 6,
    'rombel' => 'TKR 1',
    'walikelas' => 108,
    'status' => 'aktif',
  ),
  34 => 
  array (
    'id_kelas' => 71,
    'tahun_masuk' => '2026',
    'tingkat' => 10,
    'id_jurusan' => 6,
    'rombel' => 'TKR 2',
    'walikelas' => 184,
    'status' => 'aktif',
  ),
  35 => 
  array (
    'id_kelas' => 72,
    'tahun_masuk' => '2026',
    'tingkat' => 10,
    'id_jurusan' => 6,
    'rombel' => 'TKR 3',
    'walikelas' => 138,
    'status' => 'aktif',
  ),
  36 => 
  array (
    'id_kelas' => 73,
    'tahun_masuk' => '2026',
    'tingkat' => 10,
    'id_jurusan' => 6,
    'rombel' => 'TKR 4',
    'walikelas' => 135,
    'status' => 'aktif',
  ),
  37 => 
  array (
    'id_kelas' => 74,
    'tahun_masuk' => '2026',
    'tingkat' => 10,
    'id_jurusan' => 6,
    'rombel' => 'TKR 5',
    'walikelas' => 130,
    'status' => 'aktif',
  ),
  38 => 
  array (
    'id_kelas' => 75,
    'tahun_masuk' => '2026',
    'tingkat' => 10,
    'id_jurusan' => 3,
    'rombel' => 'TSM 1',
    'walikelas' => 122,
    'status' => 'aktif',
  ),
  39 => 
  array (
    'id_kelas' => 76,
    'tahun_masuk' => '2026',
    'tingkat' => 10,
    'id_jurusan' => 3,
    'rombel' => 'TSM 2',
    'walikelas' => 123,
    'status' => 'aktif',
  ),
  40 => 
  array (
    'id_kelas' => 77,
    'tahun_masuk' => '2026',
    'tingkat' => 10,
    'id_jurusan' => 3,
    'rombel' => 'TSM 3',
    'walikelas' => 145,
    'status' => 'aktif',
  ),
  41 => 
  array (
    'id_kelas' => 78,
    'tahun_masuk' => '2026',
    'tingkat' => 10,
    'id_jurusan' => 1,
    'rombel' => 'RPL 1',
    'walikelas' => 167,
    'status' => 'aktif',
  ),
  42 => 
  array (
    'id_kelas' => 79,
    'tahun_masuk' => '2026',
    'tingkat' => 10,
    'id_jurusan' => 1,
    'rombel' => 'RPL 2',
    'walikelas' => 101,
    'status' => 'aktif',
  ),
);

        foreach (array_chunk($kelasData, 200) as $chunk) {
            DB::table('kelas')->insert($chunk);
        }

        Schema::enableForeignKeyConstraints();
    }
}