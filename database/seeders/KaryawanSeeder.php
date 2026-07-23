<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class KaryawanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Schema::disableForeignKeyConstraints();

        // Table: karyawan (33 rows)
        DB::table('karyawan')->truncate();
        $karyawanData = array (
  0 => 
  array (
    'id_karyawan' => 1,
    'no_id' => 1061885,
    'nama_karyawan' => 'AMON KURNIAWAN',
    'jenkel' => 'L',
    'status' => 'aktif',
    'petugas_uks' => 'tidak',
    'foto' => NULL,
    'password' => '$2y$12$SpGRPugo0iZ1kTDM6HiM/OPJySDuBUtNPsztXH5gDO.tNDBjD1UqS',
  ),
  1 => 
  array (
    'id_karyawan' => 2,
    'no_id' => 1045528,
    'nama_karyawan' => 'ANDI PRASETYA',
    'jenkel' => 'L',
    'status' => 'aktif',
    'petugas_uks' => 'tidak',
    'foto' => NULL,
    'password' => 'cbfdac6008f9cab4083784cbd1874f76618d2a97',
  ),
  2 => 
  array (
    'id_karyawan' => 3,
    'no_id' => 930420,
    'nama_karyawan' => 'BIBIT WIJISIH, A.Md',
    'jenkel' => 'L',
    'status' => 'aktif',
    'petugas_uks' => 'tidak',
    'foto' => NULL,
    'password' => '$2y$12$SpGRPugo0iZ1kTDM6HiM/OPJySDuBUtNPsztXH5gDO.tNDBjD1UqS',
  ),
  3 => 
  array (
    'id_karyawan' => 4,
    'no_id' => 811933,
    'nama_karyawan' => 'BUWANG TRIYONO',
    'jenkel' => 'L',
    'status' => 'aktif',
    'petugas_uks' => 'tidak',
    'foto' => NULL,
    'password' => '$2y$12$SpGRPugo0iZ1kTDM6HiM/OPJySDuBUtNPsztXH5gDO.tNDBjD1UqS',
  ),
  4 => 
  array (
    'id_karyawan' => 5,
    'no_id' => 1151929,
    'nama_karyawan' => 'EDWIN ARRIVIAN HUDA',
    'jenkel' => 'L',
    'status' => 'aktif',
    'petugas_uks' => 'tidak',
    'foto' => NULL,
    'password' => '$2y$12$SpGRPugo0iZ1kTDM6HiM/OPJySDuBUtNPsztXH5gDO.tNDBjD1UqS',
  ),
  5 => 
  array (
    'id_karyawan' => 6,
    'no_id' => 1141248,
    'nama_karyawan' => 'HARYONO',
    'jenkel' => 'L',
    'status' => 'aktif',
    'petugas_uks' => 'tidak',
    'foto' => NULL,
    'password' => '$2y$12$SpGRPugo0iZ1kTDM6HiM/OPJySDuBUtNPsztXH5gDO.tNDBjD1UqS',
  ),
  6 => 
  array (
    'id_karyawan' => 7,
    'no_id' => 1088677,
    'nama_karyawan' => 'ICHWAN SUKMAHADI, A.Md',
    'jenkel' => 'L',
    'status' => 'aktif',
    'petugas_uks' => 'tidak',
    'foto' => NULL,
    'password' => '$2y$12$SpGRPugo0iZ1kTDM6HiM/OPJySDuBUtNPsztXH5gDO.tNDBjD1UqS',
  ),
  7 => 
  array (
    'id_karyawan' => 8,
    'no_id' => 111408,
    'nama_karyawan' => 'IHSAN ARIS NIRWANA',
    'jenkel' => 'L',
    'status' => 'aktif',
    'petugas_uks' => 'tidak',
    'foto' => NULL,
    'password' => '$2y$12$SpGRPugo0iZ1kTDM6HiM/OPJySDuBUtNPsztXH5gDO.tNDBjD1UqS',
  ),
  8 => 
  array (
    'id_karyawan' => 9,
    'no_id' => 1573175,
    'nama_karyawan' => 'IRVAN DANI PRASETYA, AMD',
    'jenkel' => 'L',
    'status' => 'aktif',
    'petugas_uks' => 'tidak',
    'foto' => NULL,
    'password' => '$2y$12$SpGRPugo0iZ1kTDM6HiM/OPJySDuBUtNPsztXH5gDO.tNDBjD1UqS',
  ),
  9 => 
  array (
    'id_karyawan' => 10,
    'no_id' => 1102684,
    'nama_karyawan' => 'IRWAN DWI HARTANTA',
    'jenkel' => 'L',
    'status' => 'aktif',
    'petugas_uks' => 'tidak',
    'foto' => NULL,
    'password' => '$2y$12$SpGRPugo0iZ1kTDM6HiM/OPJySDuBUtNPsztXH5gDO.tNDBjD1UqS',
  ),
  10 => 
  array (
    'id_karyawan' => 11,
    'no_id' => 1142330,
    'nama_karyawan' => 'ISDIYANTO',
    'jenkel' => 'L',
    'status' => 'aktif',
    'petugas_uks' => 'tidak',
    'foto' => NULL,
    'password' => '$2y$12$SpGRPugo0iZ1kTDM6HiM/OPJySDuBUtNPsztXH5gDO.tNDBjD1UqS',
  ),
  11 => 
  array (
    'id_karyawan' => 12,
    'no_id' => 1045526,
    'nama_karyawan' => 'JOKO WINTOLO',
    'jenkel' => 'L',
    'status' => 'aktif',
    'petugas_uks' => 'tidak',
    'foto' => NULL,
    'password' => '$2y$12$SpGRPugo0iZ1kTDM6HiM/OPJySDuBUtNPsztXH5gDO.tNDBjD1UqS',
  ),
  12 => 
  array (
    'id_karyawan' => 13,
    'no_id' => 1200136,
    'nama_karyawan' => 'MUHAMMAD IHSANUDDIN, S.Pd',
    'jenkel' => 'L',
    'status' => 'aktif',
    'petugas_uks' => 'tidak',
    'foto' => NULL,
    'password' => '$2y$12$SpGRPugo0iZ1kTDM6HiM/OPJySDuBUtNPsztXH5gDO.tNDBjD1UqS',
  ),
  13 => 
  array (
    'id_karyawan' => 14,
    'no_id' => 1102102,
    'nama_karyawan' => 'NUGROHO',
    'jenkel' => 'L',
    'status' => 'aktif',
    'petugas_uks' => 'tidak',
    'foto' => NULL,
    'password' => '$2y$12$SpGRPugo0iZ1kTDM6HiM/OPJySDuBUtNPsztXH5gDO.tNDBjD1UqS',
  ),
  14 => 
  array (
    'id_karyawan' => 15,
    'no_id' => 1018622,
    'nama_karyawan' => 'NURYADI',
    'jenkel' => 'L',
    'status' => 'aktif',
    'petugas_uks' => 'tidak',
    'foto' => NULL,
    'password' => '$2y$12$SpGRPugo0iZ1kTDM6HiM/OPJySDuBUtNPsztXH5gDO.tNDBjD1UqS',
  ),
  15 => 
  array (
    'id_karyawan' => 16,
    'no_id' => 1045532,
    'nama_karyawan' => 'PARYANTO',
    'jenkel' => 'L',
    'status' => 'aktif',
    'petugas_uks' => 'tidak',
    'foto' => NULL,
    'password' => '$2y$12$SpGRPugo0iZ1kTDM6HiM/OPJySDuBUtNPsztXH5gDO.tNDBjD1UqS',
  ),
  16 => 
  array (
    'id_karyawan' => 17,
    'no_id' => 1553943,
    'nama_karyawan' => 'PURWANTO',
    'jenkel' => 'L',
    'status' => 'aktif',
    'petugas_uks' => 'tidak',
    'foto' => NULL,
    'password' => '$2y$12$SpGRPugo0iZ1kTDM6HiM/OPJySDuBUtNPsztXH5gDO.tNDBjD1UqS',
  ),
  17 => 
  array (
    'id_karyawan' => 18,
    'no_id' => 1136921,
    'nama_karyawan' => 'RIDWAN NUGROHO',
    'jenkel' => 'L',
    'status' => 'aktif',
    'petugas_uks' => 'tidak',
    'foto' => NULL,
    'password' => '$2y$12$SpGRPugo0iZ1kTDM6HiM/OPJySDuBUtNPsztXH5gDO.tNDBjD1UqS',
  ),
  18 => 
  array (
    'id_karyawan' => 19,
    'no_id' => 1338295,
    'nama_karyawan' => 'RIZKI LIA ANNISAATUN ISNAINI',
    'jenkel' => 'L',
    'status' => 'aktif',
    'petugas_uks' => 'ya',
    'foto' => NULL,
    'password' => '$2y$12$SpGRPugo0iZ1kTDM6HiM/OPJySDuBUtNPsztXH5gDO.tNDBjD1UqS',
  ),
  19 => 
  array (
    'id_karyawan' => 20,
    'no_id' => 911175,
    'nama_karyawan' => 'ROHMAD SUNADI',
    'jenkel' => 'L',
    'status' => 'aktif',
    'petugas_uks' => 'tidak',
    'foto' => NULL,
    'password' => '$2y$12$SpGRPugo0iZ1kTDM6HiM/OPJySDuBUtNPsztXH5gDO.tNDBjD1UqS',
  ),
  20 => 
  array (
    'id_karyawan' => 21,
    'no_id' => 1343054,
    'nama_karyawan' => 'SAIFUL RAHARJA',
    'jenkel' => 'L',
    'status' => 'aktif',
    'petugas_uks' => 'tidak',
    'foto' => NULL,
    'password' => '$2y$12$SpGRPugo0iZ1kTDM6HiM/OPJySDuBUtNPsztXH5gDO.tNDBjD1UqS',
  ),
  21 => 
  array (
    'id_karyawan' => 22,
    'no_id' => 1065600,
    'nama_karyawan' => 'SAMSUDIN',
    'jenkel' => 'L',
    'status' => 'aktif',
    'petugas_uks' => 'tidak',
    'foto' => NULL,
    'password' => '$2y$12$SpGRPugo0iZ1kTDM6HiM/OPJySDuBUtNPsztXH5gDO.tNDBjD1UqS',
  ),
  22 => 
  array (
    'id_karyawan' => 23,
    'no_id' => 1113076,
    'nama_karyawan' => 'SARIONO',
    'jenkel' => 'L',
    'status' => 'aktif',
    'petugas_uks' => 'tidak',
    'foto' => NULL,
    'password' => '$2y$12$SpGRPugo0iZ1kTDM6HiM/OPJySDuBUtNPsztXH5gDO.tNDBjD1UqS',
  ),
  23 => 
  array (
    'id_karyawan' => 24,
    'no_id' => 1216191,
    'nama_karyawan' => 'SARMIYANTO',
    'jenkel' => 'L',
    'status' => 'aktif',
    'petugas_uks' => 'tidak',
    'foto' => NULL,
    'password' => '$2y$12$SpGRPugo0iZ1kTDM6HiM/OPJySDuBUtNPsztXH5gDO.tNDBjD1UqS',
  ),
  24 => 
  array (
    'id_karyawan' => 25,
    'no_id' => 1050144,
    'nama_karyawan' => 'SIGIT RIYADI',
    'jenkel' => 'L',
    'status' => 'aktif',
    'petugas_uks' => 'tidak',
    'foto' => NULL,
    'password' => '$2y$12$SpGRPugo0iZ1kTDM6HiM/OPJySDuBUtNPsztXH5gDO.tNDBjD1UqS',
  ),
  25 => 
  array (
    'id_karyawan' => 26,
    'no_id' => 952743,
    'nama_karyawan' => 'SRI MURWATI',
    'jenkel' => 'L',
    'status' => 'aktif',
    'petugas_uks' => 'tidak',
    'foto' => NULL,
    'password' => '$2y$12$SpGRPugo0iZ1kTDM6HiM/OPJySDuBUtNPsztXH5gDO.tNDBjD1UqS',
  ),
  26 => 
  array (
    'id_karyawan' => 27,
    'no_id' => 1102290,
    'nama_karyawan' => 'SUBANDI',
    'jenkel' => 'L',
    'status' => 'aktif',
    'petugas_uks' => 'tidak',
    'foto' => NULL,
    'password' => '$2y$12$SpGRPugo0iZ1kTDM6HiM/OPJySDuBUtNPsztXH5gDO.tNDBjD1UqS',
  ),
  27 => 
  array (
    'id_karyawan' => 28,
    'no_id' => 1285345,
    'nama_karyawan' => 'SUPRIYADI',
    'jenkel' => 'L',
    'status' => 'aktif',
    'petugas_uks' => 'tidak',
    'foto' => NULL,
    'password' => '$2y$12$SpGRPugo0iZ1kTDM6HiM/OPJySDuBUtNPsztXH5gDO.tNDBjD1UqS',
  ),
  28 => 
  array (
    'id_karyawan' => 29,
    'no_id' => 952744,
    'nama_karyawan' => 'SURATMAN',
    'jenkel' => 'L',
    'status' => 'aktif',
    'petugas_uks' => 'tidak',
    'foto' => NULL,
    'password' => '$2y$12$SpGRPugo0iZ1kTDM6HiM/OPJySDuBUtNPsztXH5gDO.tNDBjD1UqS',
  ),
  29 => 
  array (
    'id_karyawan' => 30,
    'no_id' => 952765,
    'nama_karyawan' => 'SUTARNO WIDODO',
    'jenkel' => 'L',
    'status' => 'aktif',
    'petugas_uks' => 'tidak',
    'foto' => NULL,
    'password' => '$2y$12$SpGRPugo0iZ1kTDM6HiM/OPJySDuBUtNPsztXH5gDO.tNDBjD1UqS',
  ),
  30 => 
  array (
    'id_karyawan' => 31,
    'no_id' => 952745,
    'nama_karyawan' => 'SUYADI',
    'jenkel' => 'L',
    'status' => 'aktif',
    'petugas_uks' => 'tidak',
    'foto' => NULL,
    'password' => '$2y$12$SpGRPugo0iZ1kTDM6HiM/OPJySDuBUtNPsztXH5gDO.tNDBjD1UqS',
  ),
  31 => 
  array (
    'id_karyawan' => 32,
    'no_id' => 1095611,
    'nama_karyawan' => 'WIHDATUL UMMAH, A.Md',
    'jenkel' => 'L',
    'status' => 'aktif',
    'petugas_uks' => 'tidak',
    'foto' => NULL,
    'password' => '$2y$12$SpGRPugo0iZ1kTDM6HiM/OPJySDuBUtNPsztXH5gDO.tNDBjD1UqS',
  ),
  32 => 
  array (
    'id_karyawan' => 33,
    'no_id' => 1701106,
    'nama_karyawan' => 'ZULKARNAIN NUR FAJAR, S.Pd',
    'jenkel' => 'L',
    'status' => 'aktif',
    'petugas_uks' => 'tidak',
    'foto' => NULL,
    'password' => '$2y$12$SpGRPugo0iZ1kTDM6HiM/OPJySDuBUtNPsztXH5gDO.tNDBjD1UqS',
  ),
);

        foreach (array_chunk($karyawanData, 200) as $chunk) {
            DB::table('karyawan')->insert($chunk);
        }

        Schema::enableForeignKeyConstraints();
    }
}