<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class IsmubaDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Schema::disableForeignKeyConstraints();

        // Table: btaq (10 rows)
        DB::table('btaq')->truncate();
        DB::table('btaq')->insert(array (
  0 => 
  array (
    'id_btaq' => 6,
    'tanggal' => '2026-07-13',
    'nis' => 14741,
    'id_kelas' => 50,
    'level' => 'Iqro',
    'awal' => '1',
    'akhir' => '1',
    'id_guru' => 105,
  ),
  1 => 
  array (
    'id_btaq' => 7,
    'tanggal' => '2026-07-14',
    'nis' => 14741,
    'id_kelas' => 50,
    'level' => 'Iqro',
    'awal' => '2',
    'akhir' => '2',
    'id_guru' => 105,
  ),
  2 => 
  array (
    'id_btaq' => 8,
    'tanggal' => '2026-07-13',
    'nis' => 14742,
    'id_kelas' => 50,
    'level' => 'Iqro',
    'awal' => '11',
    'akhir' => '11',
    'id_guru' => 105,
  ),
  3 => 
  array (
    'id_btaq' => 9,
    'tanggal' => '2026-07-17',
    'nis' => 14775,
    'id_kelas' => 51,
    'level' => 'Iqro',
    'awal' => '2',
    'akhir' => '2',
    'id_guru' => 104,
  ),
  4 => 
  array (
    'id_btaq' => 10,
    'tanggal' => '2026-07-13',
    'nis' => 14742,
    'id_kelas' => 50,
    'level' => 'Iqro',
    'awal' => '13',
    'akhir' => '13',
    'id_guru' => 111,
  ),
  5 => 
  array (
    'id_btaq' => 11,
    'tanggal' => '2026-07-14',
    'nis' => 14742,
    'id_kelas' => 50,
    'level' => 'Iqro',
    'awal' => '14',
    'akhir' => '14',
    'id_guru' => 111,
  ),
  6 => 
  array (
    'id_btaq' => 13,
    'tanggal' => '2026-07-15',
    'nis' => 14741,
    'id_kelas' => 50,
    'level' => 'Iqro',
    'awal' => '2',
    'akhir' => '2',
    'id_guru' => 111,
  ),
  7 => 
  array (
    'id_btaq' => 14,
    'tanggal' => '2026-07-13',
    'nis' => 13862,
    'id_kelas' => 52,
    'level' => 'Iqro',
    'awal' => '1',
    'akhir' => '1',
    'id_guru' => 101,
  ),
  8 => 
  array (
    'id_btaq' => 15,
    'tanggal' => '2026-07-15',
    'nis' => 13862,
    'id_kelas' => 52,
    'level' => 'Iqro',
    'awal' => '2',
    'akhir' => '2',
    'id_guru' => 101,
  ),
  9 => 
  array (
    'id_btaq' => 16,
    'tanggal' => '2026-07-16',
    'nis' => 13862,
    'id_kelas' => 52,
    'level' => 'Iqro',
    'awal' => '14',
    'akhir' => '14',
    'id_guru' => 101,
  ),
));

        // Table: tadarus (2 rows)
        DB::table('tadarus')->truncate();
        DB::table('tadarus')->insert(array (
  0 => 
  array (
    'id_tadarus' => 3,
    'tanggal' => '2026-07-17',
    'id_kelas' => 50,
    'awal_surat' => 'Al-Fatihah',
    'awal_ayat' => 1,
    'akhir_surat' => 'Al-Baqarah',
    'akhir_ayat' => 17,
    'id_guru' => 101,
  ),
  1 => 
  array (
    'id_tadarus' => 4,
    'tanggal' => '2026-07-17',
    'id_kelas' => 78,
    'awal_surat' => 'Al-Baqarah',
    'awal_ayat' => 8,
    'akhir_surat' => 'Al-Baqarah',
    'akhir_ayat' => 18,
    'id_guru' => 101,
  ),
));

        // Table: jadwal_pengajian (1 rows)
        DB::table('jadwal_pengajian')->truncate();
        DB::table('jadwal_pengajian')->insert(array (
  0 => 
  array (
    'id_jadwal' => 11,
    'nama_kegiatan' => 'Pengajian Rutin',
    'tanggal' => '2026-07-20',
    'jam_mulai' => '08:00:00',
    'jam_selesai' => '12:00:00',
    'tempat' => 'Bpk Edwin Arrivian Huda',
    'lokasi_gmaps' => 'https://www.google.com/maps/place/Kedai+sawah+kopi+%26+resto/@-7.8271782,110.4915203,20.15z/data=!4m14!1m7!3m6!1s0x2e7a510832e59df3:0x7df19aeb5a6c0b65!2sKedai+sawah+kopi+%26+resto!8m2!3d-7.8272963!4d110.4915574!16s%2Fg%2F11lh2w4myp!3m5!1s0x2e7a510832e59df3:0x7df19aeb5a6c0b65!8m2!3d-7.8272963!4d110.4915574!16s%2Fg%2F11lh2w4myp?hl=id-ID&entry=ttu&g_ep=EgoyMDI2MDcxNS4wIKXMDSoASAFQAw%3D%3D',
    'latitude' => '-7.82717820',
    'longitude' => '110.49152030',
    'radius_meter' => 500,
    'keterangan' => NULL,
    'created_at' => '2026-07-20 11:05:30',
    'updated_at' => '2026-07-20 11:13:12',
  ),
));


        Schema::enableForeignKeyConstraints();
    }
}