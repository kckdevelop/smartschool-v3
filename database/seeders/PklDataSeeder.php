<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class PklDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Schema::disableForeignKeyConstraints();

        // Table: pkl_dudi (10 rows)
        DB::table('pkl_dudi')->truncate();
        DB::table('pkl_dudi')->insert(array (
  0 => 
  array (
    'id_dudi' => 14,
    'id_jurusan' => 1,
    'nama_dudi' => 'Percetakaan Aliansi',
    'bidang_usaha' => 'Percetakaan',
    'alamat' => 'Jl. Imogiri Barat No.10, Ngentak, Timbulharjo, Kec. Sewon, Kabupaten Bantul, Daerah Istimewa Yogyakarta 55187',
    'kota' => 'Bantul',
    'kecamatan' => 'Sewon',
    'kabupaten' => 'Bantul',
    'no_telepon' => '89619920222',
    'email' => NULL,
    'nama_pic' => NULL,
    'jabatan_pic' => NULL,
    'no_hp_pic' => NULL,
    'kuota_siswa' => 5,
    'status' => 'aktif',
    'created_at' => '2026-07-23 10:34:27',
    'updated_at' => '2026-07-23 10:34:27',
  ),
  1 => 
  array (
    'id_dudi' => 15,
    'id_jurusan' => 1,
    'nama_dudi' => 'Percetakan Mas Eko Cetak',
    'bidang_usaha' => 'Percetakaan',
    'alamat' => 'Jl. Imogiri Barat No.km.7, Semail, Bangunharjo, Kec. Sewon, Kabupaten Bantul, Daerah Istimewa Yogyakarta 55188',
    'kota' => 'Bantul',
    'kecamatan' => 'Sewon',
    'kabupaten' => 'Bantul',
    'no_telepon' => '8882848088',
    'email' => NULL,
    'nama_pic' => NULL,
    'jabatan_pic' => NULL,
    'no_hp_pic' => NULL,
    'kuota_siswa' => 5,
    'status' => 'aktif',
    'created_at' => '2026-07-23 10:34:27',
    'updated_at' => '2026-07-23 10:34:27',
  ),
  2 => 
  array (
    'id_dudi' => 16,
    'id_jurusan' => 1,
    'nama_dudi' => 'Central Print',
    'bidang_usaha' => 'Percetakaan',
    'alamat' => 'Jalan Kolonel Sugiyono no 68 Brontokusuman, Kecamatan Mergangsan',
    'kota' => 'Kota Yogyakarta',
    'kecamatan' => 'Mergangsang',
    'kabupaten' => 'Kota Yogyakarta',
    'no_telepon' => NULL,
    'email' => NULL,
    'nama_pic' => NULL,
    'jabatan_pic' => NULL,
    'no_hp_pic' => NULL,
    'kuota_siswa' => 5,
    'status' => 'aktif',
    'created_at' => '2026-07-23 10:34:27',
    'updated_at' => '2026-07-23 10:34:27',
  ),
  3 => 
  array (
    'id_dudi' => 17,
    'id_jurusan' => 1,
    'nama_dudi' => 'Pengadilan Agama Bantul',
    'bidang_usaha' => 'umum',
    'alamat' => 'Jl. Urip Sumoharjo No.8, Bejen, Bantul, Kec. Bantul, Kabupaten Bantul, Daerah Istimewa Yogyakarta 55711',
    'kota' => 'Bantul',
    'kecamatan' => 'Bantul',
    'kabupaten' => 'Bantul',
    'no_telepon' => '(0274) 367423',
    'email' => NULL,
    'nama_pic' => NULL,
    'jabatan_pic' => NULL,
    'no_hp_pic' => NULL,
    'kuota_siswa' => 5,
    'status' => 'aktif',
    'created_at' => '2026-07-23 10:34:27',
    'updated_at' => '2026-07-23 10:34:27',
  ),
  4 => 
  array (
    'id_dudi' => 18,
    'id_jurusan' => 1,
    'nama_dudi' => 'Tercode Indonesia',
    'bidang_usaha' => 'Software',
    'alamat' => 'Jl. Raya Janti No.223, RT.07/RW.20, Modalan, Banguntapan, Kec. Banguntapan, Kabupaten Bantul, Daerah Istimewa Yogyakarta 55198',
    'kota' => 'Bantul',
    'kecamatan' => 'Banguntapan',
    'kabupaten' => 'Bantul',
    'no_telepon' => '87818310410',
    'email' => NULL,
    'nama_pic' => NULL,
    'jabatan_pic' => NULL,
    'no_hp_pic' => NULL,
    'kuota_siswa' => 5,
    'status' => 'aktif',
    'created_at' => '2026-07-23 10:34:27',
    'updated_at' => '2026-07-23 10:34:27',
  ),
  5 => 
  array (
    'id_dudi' => 19,
    'id_jurusan' => 1,
    'nama_dudi' => 'Siap Cetak Panembahan Bantul',
    'bidang_usaha' => 'Percetakaan',
    'alamat' => 'Jl. Dr. Wahidin Sudiro Husodo, Area Sawah, Trirenggo, Kec. Bantul, Kabupaten Bantul, Daerah Istimewa Yogyakarta 55714',
    'kota' => 'Bantul',
    'kecamatan' => 'Bantul',
    'kabupaten' => 'Bantul',
    'no_telepon' => '(0274) 2258040',
    'email' => NULL,
    'nama_pic' => NULL,
    'jabatan_pic' => NULL,
    'no_hp_pic' => NULL,
    'kuota_siswa' => 5,
    'status' => 'aktif',
    'created_at' => '2026-07-23 10:34:27',
    'updated_at' => '2026-07-23 10:34:27',
  ),
  6 => 
  array (
    'id_dudi' => 20,
    'id_jurusan' => 1,
    'nama_dudi' => 'IndoMarching & IndoDrumband',
    'bidang_usaha' => 'umum',
    'alamat' => 'JL. Manding Imogiri km 0.7 RT.06 Keyongankidul, Dukuh, Sabdodadi, Kec. Bantul, Kabupaten Bantul, Daerah Istimewa Yogyakarta 55715',
    'kota' => 'Bantul',
    'kecamatan' => 'Bantul',
    'kabupaten' => 'Bantul',
    'no_telepon' => '87738832588',
    'email' => NULL,
    'nama_pic' => NULL,
    'jabatan_pic' => NULL,
    'no_hp_pic' => NULL,
    'kuota_siswa' => 5,
    'status' => 'aktif',
    'created_at' => '2026-07-23 10:34:27',
    'updated_at' => '2026-07-23 10:34:27',
  ),
  7 => 
  array (
    'id_dudi' => 21,
    'id_jurusan' => 1,
    'nama_dudi' => 'Digivasi.id',
    'bidang_usaha' => 'Percetakaan',
    'alamat' => 'Gg. Werkudara No.260A, Jotawang, Bangunharjo, Kec. Sewon, Kabupaten Bantul, Daerah Istimewa Yogyakarta 55188',
    'kota' => 'Bantul',
    'kecamatan' => 'Sewon',
    'kabupaten' => 'Bantul',
    'no_telepon' => '85122932021',
    'email' => NULL,
    'nama_pic' => NULL,
    'jabatan_pic' => NULL,
    'no_hp_pic' => NULL,
    'kuota_siswa' => 5,
    'status' => 'aktif',
    'created_at' => '2026-07-23 10:34:27',
    'updated_at' => '2026-07-23 10:34:27',
  ),
  8 => 
  array (
    'id_dudi' => 22,
    'id_jurusan' => 1,
    'nama_dudi' => 'PT INDO TECHNO MEDIC',
    'bidang_usaha' => 'umum',
    'alamat' => 'Jl. Ringroad Selatan, Menayu Lor, Tirtonirmolo, Kec. Kasihan, Kabupaten Bantul, Daerah Istimewa Yogyakarta 55184',
    'kota' => 'Bantul',
    'kecamatan' => 'Kasihan',
    'kabupaten' => 'Bantul',
    'no_telepon' => '895403388880',
    'email' => NULL,
    'nama_pic' => NULL,
    'jabatan_pic' => NULL,
    'no_hp_pic' => NULL,
    'kuota_siswa' => 5,
    'status' => 'aktif',
    'created_at' => '2026-07-23 10:34:27',
    'updated_at' => '2026-07-23 10:34:27',
  ),
  9 => 
  array (
    'id_dudi' => 23,
    'id_jurusan' => 1,
    'nama_dudi' => 'Global Intermedia',
    'bidang_usaha' => 'Software',
    'alamat' => 'Jl. Taman Siswa No.125, Wirogunan, Kec. Mergangsan, Kota Yogyakarta, Daerah Istimewa Yogyakarta 55151',
    'kota' => 'Kota Yogyakarta',
    'kecamatan' => 'Mergangsang',
    'kabupaten' => 'Kota Yogyakarta',
    'no_telepon' => '(0274) 382238',
    'email' => NULL,
    'nama_pic' => NULL,
    'jabatan_pic' => NULL,
    'no_hp_pic' => NULL,
    'kuota_siswa' => 5,
    'status' => 'aktif',
    'created_at' => '2026-07-23 10:34:27',
    'updated_at' => '2026-07-23 10:34:27',
  ),
));

        // Table: pkl_gelombang (1 rows)
        DB::table('pkl_gelombang')->truncate();
        DB::table('pkl_gelombang')->insert(array (
  0 => 
  array (
    'id_gelombang' => 5,
    'nama_gelombang' => 'GELOMBANG 1',
    'tahun_ajaran' => '2026/2027',
    'tanggal_mulai' => '2026-12-31',
    'tanggal_selesai' => '2027-05-30',
    'status' => 'aktif',
    'keterangan' => NULL,
    'created_at' => '2026-07-23 09:27:07',
    'updated_at' => '2026-07-23 09:27:14',
  ),
));

        // Table: pkl_kelas_gelombang (15 rows)
        DB::table('pkl_kelas_gelombang')->truncate();
        DB::table('pkl_kelas_gelombang')->insert(array (
  0 => 
  array (
    'id' => 23,
    'id_gelombang' => 5,
    'id_kelas' => 64,
    'created_at' => '2026-07-23 09:27:14',
    'updated_at' => '2026-07-23 09:27:14',
  ),
  1 => 
  array (
    'id' => 24,
    'id_gelombang' => 5,
    'id_kelas' => 65,
    'created_at' => '2026-07-23 09:27:14',
    'updated_at' => '2026-07-23 09:27:14',
  ),
  2 => 
  array (
    'id' => 25,
    'id_gelombang' => 5,
    'id_kelas' => 66,
    'created_at' => '2026-07-23 09:27:14',
    'updated_at' => '2026-07-23 09:27:14',
  ),
  3 => 
  array (
    'id' => 26,
    'id_gelombang' => 5,
    'id_kelas' => 52,
    'created_at' => '2026-07-23 09:27:14',
    'updated_at' => '2026-07-23 09:27:14',
  ),
  4 => 
  array (
    'id' => 27,
    'id_gelombang' => 5,
    'id_kelas' => 60,
    'created_at' => '2026-07-23 09:27:14',
    'updated_at' => '2026-07-23 09:27:14',
  ),
  5 => 
  array (
    'id' => 28,
    'id_gelombang' => 5,
    'id_kelas' => 61,
    'created_at' => '2026-07-23 09:27:14',
    'updated_at' => '2026-07-23 09:27:14',
  ),
  6 => 
  array (
    'id' => 29,
    'id_gelombang' => 5,
    'id_kelas' => 62,
    'created_at' => '2026-07-23 09:27:14',
    'updated_at' => '2026-07-23 09:27:14',
  ),
  7 => 
  array (
    'id' => 30,
    'id_gelombang' => 5,
    'id_kelas' => 63,
    'created_at' => '2026-07-23 09:27:14',
    'updated_at' => '2026-07-23 09:27:14',
  ),
  8 => 
  array (
    'id' => 31,
    'id_gelombang' => 5,
    'id_kelas' => 55,
    'created_at' => '2026-07-23 09:27:14',
    'updated_at' => '2026-07-23 09:27:14',
  ),
  9 => 
  array (
    'id' => 32,
    'id_gelombang' => 5,
    'id_kelas' => 56,
    'created_at' => '2026-07-23 09:27:14',
    'updated_at' => '2026-07-23 09:27:14',
  ),
  10 => 
  array (
    'id' => 33,
    'id_gelombang' => 5,
    'id_kelas' => 57,
    'created_at' => '2026-07-23 09:27:14',
    'updated_at' => '2026-07-23 09:27:14',
  ),
  11 => 
  array (
    'id' => 34,
    'id_gelombang' => 5,
    'id_kelas' => 58,
    'created_at' => '2026-07-23 09:27:14',
    'updated_at' => '2026-07-23 09:27:14',
  ),
  12 => 
  array (
    'id' => 35,
    'id_gelombang' => 5,
    'id_kelas' => 59,
    'created_at' => '2026-07-23 09:27:14',
    'updated_at' => '2026-07-23 09:27:14',
  ),
  13 => 
  array (
    'id' => 36,
    'id_gelombang' => 5,
    'id_kelas' => 53,
    'created_at' => '2026-07-23 09:27:14',
    'updated_at' => '2026-07-23 09:27:14',
  ),
  14 => 
  array (
    'id' => 37,
    'id_gelombang' => 5,
    'id_kelas' => 54,
    'created_at' => '2026-07-23 09:27:14',
    'updated_at' => '2026-07-23 09:27:14',
  ),
));

        // Table: pkl_nomor_surat (3 rows)
        DB::table('pkl_nomor_surat')->truncate();
        DB::table('pkl_nomor_surat')->insert(array (
  0 => 
  array (
    'id' => 1,
    'jenis_surat' => 'permohonan',
    'format_nomor' => '88/III.4.AU/F/A/2026',
    'prefix' => NULL,
    'counter_terakhir' => 3,
    'tahun_reset' => '2026',
    'created_at' => '2026-06-27 11:20:39',
    'updated_at' => '2026-07-23 11:32:32',
  ),
  1 => 
  array (
    'id' => 2,
    'jenis_surat' => 'penempatan',
    'format_nomor' => '88/III.4.AU/F/2026',
    'prefix' => NULL,
    'counter_terakhir' => 0,
    'tahun_reset' => '2026',
    'created_at' => '2026-06-27 11:20:39',
    'updated_at' => '2026-07-23 11:06:25',
  ),
  2 => 
  array (
    'id' => 3,
    'jenis_surat' => 'penarikan',
    'format_nomor' => '88/III.4.AU/F/2026',
    'prefix' => NULL,
    'counter_terakhir' => 0,
    'tahun_reset' => '2026',
    'created_at' => '2026-06-27 11:20:39',
    'updated_at' => '2026-07-23 11:06:32',
  ),
));

        // Table: pkl_pembimbing (2 rows)
        DB::table('pkl_pembimbing')->truncate();
        DB::table('pkl_pembimbing')->insert(array (
  0 => 
  array (
    'id_pembimbing' => 14,
    'id_gelombang' => 5,
    'id_guru' => 103,
    'id_dudi' => 16,
    'created_at' => '2026-07-23 10:59:54',
    'updated_at' => '2026-07-23 10:59:54',
  ),
  1 => 
  array (
    'id_pembimbing' => 15,
    'id_gelombang' => 5,
    'id_guru' => 103,
    'id_dudi' => 21,
    'created_at' => '2026-07-23 11:00:05',
    'updated_at' => '2026-07-23 11:00:05',
  ),
));

        // Table: pkl_penempatan (3 rows)
        DB::table('pkl_penempatan')->truncate();
        DB::table('pkl_penempatan')->insert(array (
  0 => 
  array (
    'id_penempatan' => 5,
    'id_gelombang' => 5,
    'id_dudi' => 16,
    'nis' => '14286',
    'id_pembimbing' => 14,
    'tanggal_masuk' => '2027-01-01',
    'tanggal_keluar' => '2027-05-31',
    'status' => 'aktif',
    'keterangan' => NULL,
    'created_at' => '2026-07-23 10:52:47',
    'updated_at' => '2026-07-23 10:59:54',
  ),
  1 => 
  array (
    'id_penempatan' => 6,
    'id_gelombang' => 5,
    'id_dudi' => 16,
    'nis' => '14341',
    'id_pembimbing' => 14,
    'tanggal_masuk' => '2027-01-01',
    'tanggal_keluar' => '2027-05-31',
    'status' => 'aktif',
    'keterangan' => NULL,
    'created_at' => '2026-07-23 10:52:47',
    'updated_at' => '2026-07-23 10:59:54',
  ),
  2 => 
  array (
    'id_penempatan' => 7,
    'id_gelombang' => 5,
    'id_dudi' => 16,
    'nis' => '14315',
    'id_pembimbing' => 14,
    'tanggal_masuk' => '2027-01-01',
    'tanggal_keluar' => '2027-05-31',
    'status' => 'aktif',
    'keterangan' => NULL,
    'created_at' => '2026-07-23 10:52:47',
    'updated_at' => '2026-07-23 10:59:54',
  ),
));

        // Table: pkl_persuratan (4 rows)
        DB::table('pkl_persuratan')->truncate();
        DB::table('pkl_persuratan')->insert(array (
  0 => 
  array (
    'id_surat' => 6,
    'nomor_surat' => '001//III.4.AU/F/B/2026',
    'jenis_surat' => 'penempatan',
    'id_gelombang' => 5,
    'id_dudi' => 2,
    'tanggal_surat' => '2026-07-23',
    'hal' => 'Surat Pengantar Penempatan Siswa PKL',
    'daftar_siswa' => NULL,
    'file_pdf' => NULL,
    'dicetak_oleh' => 1,
    'created_at' => '2026-07-23 09:40:38',
    'updated_at' => '2026-07-23 09:40:38',
  ),
  1 => 
  array (
    'id_surat' => 7,
    'nomor_surat' => '001/III.4.AU/F/C/2026',
    'jenis_surat' => 'penarikan',
    'id_gelombang' => 5,
    'id_dudi' => 2,
    'tanggal_surat' => '2026-07-23',
    'hal' => 'Penarikan Siswa Praktik Kerja Lapangan',
    'daftar_siswa' => NULL,
    'file_pdf' => NULL,
    'dicetak_oleh' => 1,
    'created_at' => '2026-07-23 09:46:17',
    'updated_at' => '2026-07-23 09:46:17',
  ),
  2 => 
  array (
    'id_surat' => 8,
    'nomor_surat' => '002/III.4.AU/F/C/2026',
    'jenis_surat' => 'penarikan',
    'id_gelombang' => 5,
    'id_dudi' => 2,
    'tanggal_surat' => '2026-07-23',
    'hal' => 'Penarikan Siswa Praktik Kerja Lapangan',
    'daftar_siswa' => NULL,
    'file_pdf' => NULL,
    'dicetak_oleh' => 1,
    'created_at' => '2026-07-23 09:48:30',
    'updated_at' => '2026-07-23 09:48:30',
  ),
  3 => 
  array (
    'id_surat' => 11,
    'nomor_surat' => '88/III.4.AU/F/A/2026',
    'jenis_surat' => 'permohonan',
    'id_gelombang' => 5,
    'id_dudi' => 14,
    'tanggal_surat' => '2026-07-23',
    'hal' => 'Permohonan Praktik Kerja Lapangan (PKL)',
    'daftar_siswa' => '[{"nis":"14286","nama_siswa":"ADE SUKMA RISTY ALDI","nama_kelas":"12 RPL 1","keahlian":"Rekayasa Perangkat Lunak"},{"nis":"14287","nama_siswa":"AINUGRAH AFFIF FEBRYANO","nama_kelas":"12 RPL 1","keahlian":"Rekayasa Perangkat Lunak"}]',
    'file_pdf' => NULL,
    'dicetak_oleh' => 1,
    'created_at' => '2026-07-23 11:32:32',
    'updated_at' => '2026-07-23 11:32:32',
  ),
));


        Schema::enableForeignKeyConstraints();
    }
}