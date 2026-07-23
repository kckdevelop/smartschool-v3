<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class BkDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Schema::disableForeignKeyConstraints();

        // Table: bimbingan_konseling (1 rows)
        DB::table('bimbingan_konseling')->truncate();
        DB::table('bimbingan_konseling')->insert(array (
  0 => 
  array (
    'id_bk' => 1,
    'tanggal' => '2026-07-20',
    'nis' => 14175,
    'jenis_masalah' => 'Tidak bisa konsentrasi',
    'uraian' => 'Siswa ini tidak bisa konsentrasi saat praktikum',
    'tindak_lanjut' => 'Disarankan memakai headset',
    'status' => 'proses',
    'id_guru' => 111,
  ),
));

        // Table: buku_kasus (1 rows)
        DB::table('buku_kasus')->truncate();
        DB::table('buku_kasus')->insert(array (
  0 => 
  array (
    'id_kasus' => 1,
    'tanggal' => '2026-07-20',
    'nis' => '14294',
    'judul_kasus' => 'Make up',
    'uraian_kasus' => 'Pakai make up berlebihan',
    'tindak_lanjut' => 'Hapus make up',
    'status' => 'selesai',
    'id_guru' => 111,
    'created_at' => '2026-07-20 16:21:24',
    'updated_at' => '2026-07-20 16:21:24',
  ),
));

        // Table: home_visit (2 rows)
        DB::table('home_visit')->truncate();
        DB::table('home_visit')->insert(array (
  0 => 
  array (
    'id_home_visit' => 1,
    'tanggal_visit' => '2026-07-20',
    'nis' => '14766',
    'alamat' => 'Dusun kedungjati no 3. Imogiri',
    'tujuan_kunjungan' => 'Siswa sering tidak berangkat sekolah',
    'hasil_kunjungan' => 'Siswa bersedia berangkat dengan tertib',
    'tindak_lanjut' => 'Berangkat tertib dan tidak membolos',
    'status' => 'selesai',
    'id_guru' => 111,
    'foto_bukti' => NULL,
    'created_at' => '2026-07-20 17:08:33',
    'updated_at' => '2026-07-20 17:34:43',
  ),
  1 => 
  array (
    'id_home_visit' => 2,
    'tanggal_visit' => '2026-07-20',
    'nis' => '14175',
    'alamat' => 'Hjbjvjbh hjbjhj',
    'tujuan_kunjungan' => 'Bjj h jbuvuvb u.',
    'hasil_kunjungan' => 'K j ibubknbibibbi',
    'tindak_lanjut' => 'J uvvybibibbknini',
    'status' => 'selesai',
    'id_guru' => 111,
    'foto_bukti' => 'home-visit/gamqXGmOkt6wtUJoZPoZ3FvrWDbmF6na9DaAeGzP.jpg',
    'created_at' => '2026-07-20 17:20:50',
    'updated_at' => '2026-07-20 17:34:50',
  ),
));

        // Table: panggil_ortu (16 rows)
        DB::table('panggil_ortu')->truncate();
        DB::table('panggil_ortu')->insert(array (
  0 => 
  array (
    'id_panggil' => 4,
    'no_surat' => 'PS/003/VII/2026',
    'tanggal_panggil' => '2026-06-17',
    'waktu_pertemuan' => '09:00:00',
    'lokasi_pertemuan' => 'Ruang Bimbingan Konseling (BK)',
    'nis' => '14225',
    'nama_ortu' => 'Ilham Firmansyah',
    'no_hp_ortu' => '081243842183',
    'jenis_panggilan' => 'panggilan_biasa',
    'alasan_panggil' => 'Pelanggaran berulang tata tertib sekolah',
    'hasil_pertemuan' => NULL,
    'bukti_pertemuan' => NULL,
    'surat_pernyataan' => NULL,
    'status' => 'belum_hadir',
    'id_guru' => 105,
    'created_at' => '2026-05-11 04:17:55',
    'updated_at' => '2026-07-05 04:17:55',
  ),
  1 => 
  array (
    'id_panggil' => 5,
    'no_surat' => 'PS/004/VII/2026',
    'tanggal_panggil' => '2026-04-09',
    'waktu_pertemuan' => '14:00:00',
    'lokasi_pertemuan' => 'Ruang Bimbingan Konseling (BK)',
    'nis' => '14649',
    'nama_ortu' => 'Rian Setiawan',
    'no_hp_ortu' => '081272635752',
    'jenis_panggilan' => 'panggilan_biasa',
    'alasan_panggil' => 'Perkelahian di lingkungan sekolah',
    'hasil_pertemuan' => NULL,
    'bukti_pertemuan' => NULL,
    'surat_pernyataan' => NULL,
    'status' => 'belum_hadir',
    'id_guru' => 105,
    'created_at' => '2026-06-08 04:17:55',
    'updated_at' => '2026-07-05 04:17:55',
  ),
  2 => 
  array (
    'id_panggil' => 7,
    'no_surat' => 'PS/006/VII/2026',
    'tanggal_panggil' => '2026-04-17',
    'waktu_pertemuan' => '11:00:00',
    'lokasi_pertemuan' => 'Ruang Bimbingan Konseling (BK)',
    'nis' => '14619',
    'nama_ortu' => 'Fajar Hidayat',
    'no_hp_ortu' => '081245288118',
    'jenis_panggilan' => 'panggilan_biasa',
    'alasan_panggil' => 'Ketidakhadiran melebihi batas',
    'hasil_pertemuan' => NULL,
    'bukti_pertemuan' => NULL,
    'surat_pernyataan' => NULL,
    'status' => 'belum_hadir',
    'id_guru' => 105,
    'created_at' => '2026-05-24 04:17:55',
    'updated_at' => '2026-07-05 04:17:55',
  ),
  3 => 
  array (
    'id_panggil' => 8,
    'no_surat' => 'PS/007/VII/2026',
    'tanggal_panggil' => '2026-04-12',
    'waktu_pertemuan' => '10:00:00',
    'lokasi_pertemuan' => 'Ruang Bimbingan Konseling (BK)',
    'nis' => '14330',
    'nama_ortu' => 'Hadi Lestari',
    'no_hp_ortu' => '081263058239',
    'jenis_panggilan' => 'sp_1',
    'alasan_panggil' => 'Penurunan drastis nilai akademik',
    'hasil_pertemuan' => NULL,
    'bukti_pertemuan' => NULL,
    'surat_pernyataan' => NULL,
    'status' => 'belum_hadir',
    'id_guru' => 105,
    'created_at' => '2026-06-22 04:17:55',
    'updated_at' => '2026-07-05 04:17:55',
  ),
  4 => 
  array (
    'id_panggil' => 9,
    'no_surat' => 'PS/008/VII/2026',
    'tanggal_panggil' => '2026-06-13',
    'waktu_pertemuan' => '14:00:00',
    'lokasi_pertemuan' => 'Ruang Bimbingan Konseling (BK)',
    'nis' => '14423',
    'nama_ortu' => 'Danu Santoso',
    'no_hp_ortu' => '081289227196',
    'jenis_panggilan' => 'sp_1',
    'alasan_panggil' => 'Perkelahian di lingkungan sekolah',
    'hasil_pertemuan' => NULL,
    'bukti_pertemuan' => NULL,
    'surat_pernyataan' => NULL,
    'status' => 'belum_hadir',
    'id_guru' => 105,
    'created_at' => '2026-05-23 04:17:55',
    'updated_at' => '2026-07-05 04:17:55',
  ),
  5 => 
  array (
    'id_panggil' => 10,
    'no_surat' => 'PS/009/VII/2026',
    'tanggal_panggil' => '2026-06-05',
    'waktu_pertemuan' => '12:00:00',
    'lokasi_pertemuan' => 'Ruang Bimbingan Konseling (BK)',
    'nis' => '14431',
    'nama_ortu' => 'Prabowo Hidayat',
    'no_hp_ortu' => '081242723686',
    'jenis_panggilan' => 'panggilan_biasa',
    'alasan_panggil' => 'Pelanggaran berulang tata tertib sekolah',
    'hasil_pertemuan' => NULL,
    'bukti_pertemuan' => NULL,
    'surat_pernyataan' => NULL,
    'status' => 'belum_hadir',
    'id_guru' => 105,
    'created_at' => '2026-06-24 04:17:55',
    'updated_at' => '2026-07-05 04:17:55',
  ),
  6 => 
  array (
    'id_panggil' => 12,
    'no_surat' => 'PS/011/VII/2026',
    'tanggal_panggil' => '2026-05-11',
    'waktu_pertemuan' => '11:00:00',
    'lokasi_pertemuan' => 'Ruang Bimbingan Konseling (BK)',
    'nis' => '14284',
    'nama_ortu' => 'Kurnia Putra',
    'no_hp_ortu' => '081281890810',
    'jenis_panggilan' => 'sp_1',
    'alasan_panggil' => 'Pelanggaran berulang tata tertib sekolah',
    'hasil_pertemuan' => NULL,
    'bukti_pertemuan' => NULL,
    'surat_pernyataan' => NULL,
    'status' => 'belum_hadir',
    'id_guru' => 105,
    'created_at' => '2026-04-07 04:17:55',
    'updated_at' => '2026-07-05 04:17:55',
  ),
  7 => 
  array (
    'id_panggil' => 13,
    'no_surat' => 'PS/012/VII/2026',
    'tanggal_panggil' => '2026-06-15',
    'waktu_pertemuan' => '13:00:00',
    'lokasi_pertemuan' => 'Ruang Bimbingan Konseling (BK)',
    'nis' => '14685',
    'nama_ortu' => 'Chandra Pratama',
    'no_hp_ortu' => '081292145615',
    'jenis_panggilan' => 'sp_2',
    'alasan_panggil' => 'Perkelahian di lingkungan sekolah',
    'hasil_pertemuan' => NULL,
    'bukti_pertemuan' => NULL,
    'surat_pernyataan' => NULL,
    'status' => 'belum_hadir',
    'id_guru' => 105,
    'created_at' => '2026-05-03 04:17:55',
    'updated_at' => '2026-07-05 04:17:55',
  ),
  8 => 
  array (
    'id_panggil' => 14,
    'no_surat' => 'PS/013/VII/2026',
    'tanggal_panggil' => '2026-05-05',
    'waktu_pertemuan' => '13:00:00',
    'lokasi_pertemuan' => 'Ruang Bimbingan Konseling (BK)',
    'nis' => '14224',
    'nama_ortu' => 'Setyo Saputra',
    'no_hp_ortu' => '081250584826',
    'jenis_panggilan' => 'panggilan_biasa',
    'alasan_panggil' => 'Ketidakhadiran melebihi batas',
    'hasil_pertemuan' => NULL,
    'bukti_pertemuan' => NULL,
    'surat_pernyataan' => NULL,
    'status' => 'belum_hadir',
    'id_guru' => 105,
    'created_at' => '2026-06-12 04:17:55',
    'updated_at' => '2026-07-05 04:17:55',
  ),
  9 => 
  array (
    'id_panggil' => 16,
    'no_surat' => 'PS/015/VII/2026',
    'tanggal_panggil' => '2026-06-29',
    'waktu_pertemuan' => '13:00:00',
    'lokasi_pertemuan' => 'Ruang Bimbingan Konseling (BK)',
    'nis' => '13989',
    'nama_ortu' => 'Bagas Firmansyah',
    'no_hp_ortu' => '081217151194',
    'jenis_panggilan' => 'panggilan_biasa',
    'alasan_panggil' => 'Penurunan drastis nilai akademik',
    'hasil_pertemuan' => NULL,
    'bukti_pertemuan' => NULL,
    'surat_pernyataan' => NULL,
    'status' => 'belum_hadir',
    'id_guru' => 105,
    'created_at' => '2026-05-08 04:17:55',
    'updated_at' => '2026-07-05 04:17:55',
  ),
  10 => 
  array (
    'id_panggil' => 17,
    'no_surat' => 'PS/016/VII/2026',
    'tanggal_panggil' => '2026-05-20',
    'waktu_pertemuan' => '11:00:00',
    'lokasi_pertemuan' => 'Ruang Bimbingan Konseling (BK)',
    'nis' => '14655',
    'nama_ortu' => 'Fajar Putra',
    'no_hp_ortu' => '081288715924',
    'jenis_panggilan' => 'sp_1',
    'alasan_panggil' => 'Laporan bullying',
    'hasil_pertemuan' => NULL,
    'bukti_pertemuan' => NULL,
    'surat_pernyataan' => NULL,
    'status' => 'belum_hadir',
    'id_guru' => 105,
    'created_at' => '2026-06-07 04:17:55',
    'updated_at' => '2026-07-05 04:17:55',
  ),
  11 => 
  array (
    'id_panggil' => 18,
    'no_surat' => 'PS/017/VII/2026',
    'tanggal_panggil' => '2026-04-13',
    'waktu_pertemuan' => '10:00:00',
    'lokasi_pertemuan' => 'Ruang Bimbingan Konseling (BK)',
    'nis' => '14508',
    'nama_ortu' => 'Lukman Nugraha',
    'no_hp_ortu' => '081220065826',
    'jenis_panggilan' => 'sp_2',
    'alasan_panggil' => 'Perkelahian di lingkungan sekolah',
    'hasil_pertemuan' => NULL,
    'bukti_pertemuan' => NULL,
    'surat_pernyataan' => NULL,
    'status' => 'belum_hadir',
    'id_guru' => 105,
    'created_at' => '2026-06-07 04:17:55',
    'updated_at' => '2026-07-05 04:17:55',
  ),
  12 => 
  array (
    'id_panggil' => 19,
    'no_surat' => 'PS/018/VII/2026',
    'tanggal_panggil' => '2026-06-20',
    'waktu_pertemuan' => '09:00:00',
    'lokasi_pertemuan' => 'Ruang Bimbingan Konseling (BK)',
    'nis' => '14488',
    'nama_ortu' => 'Joko Pratama',
    'no_hp_ortu' => '081262149524',
    'jenis_panggilan' => 'sp_2',
    'alasan_panggil' => 'Perkelahian di lingkungan sekolah',
    'hasil_pertemuan' => NULL,
    'bukti_pertemuan' => NULL,
    'surat_pernyataan' => NULL,
    'status' => 'belum_hadir',
    'id_guru' => 105,
    'created_at' => '2026-06-19 04:17:55',
    'updated_at' => '2026-07-05 04:17:55',
  ),
  13 => 
  array (
    'id_panggil' => 20,
    'no_surat' => 'PS/019/VII/2026',
    'tanggal_panggil' => '2026-05-08',
    'waktu_pertemuan' => '12:00:00',
    'lokasi_pertemuan' => 'Ruang Bimbingan Konseling (BK)',
    'nis' => '14329',
    'nama_ortu' => 'Rian Lestari',
    'no_hp_ortu' => '081214789394',
    'jenis_panggilan' => 'sp_2',
    'alasan_panggil' => 'Laporan bullying',
    'hasil_pertemuan' => NULL,
    'bukti_pertemuan' => NULL,
    'surat_pernyataan' => NULL,
    'status' => 'belum_hadir',
    'id_guru' => 105,
    'created_at' => '2026-05-28 04:17:55',
    'updated_at' => '2026-07-05 04:17:55',
  ),
  14 => 
  array (
    'id_panggil' => 21,
    'no_surat' => 'PS/020/VII/2026',
    'tanggal_panggil' => '2026-04-18',
    'waktu_pertemuan' => '14:00:00',
    'lokasi_pertemuan' => 'Ruang Bimbingan Konseling (BK)',
    'nis' => '14178',
    'nama_ortu' => 'Ilham Nugraha',
    'no_hp_ortu' => '081224628522',
    'jenis_panggilan' => 'sp_2',
    'alasan_panggil' => 'Pelanggaran berulang tata tertib sekolah',
    'hasil_pertemuan' => NULL,
    'bukti_pertemuan' => NULL,
    'surat_pernyataan' => NULL,
    'status' => 'belum_hadir',
    'id_guru' => 105,
    'created_at' => '2026-04-06 04:17:55',
    'updated_at' => '2026-07-05 04:17:55',
  ),
  15 => 
  array (
    'id_panggil' => 22,
    'no_surat' => NULL,
    'tanggal_panggil' => '2026-07-18',
    'waktu_pertemuan' => '08:00:00',
    'lokasi_pertemuan' => 'Ruang Bimbingan Konseling (BK)',
    'nis' => '15385',
    'nama_ortu' => 'EDDY PRAYITNO',
    'no_hp_ortu' => '085725653663',
    'jenis_panggilan' => 'panggilan_biasa',
    'alasan_panggil' => 'Keterlambatan masuk sekolah yang berulang dan melebihi batas toleransi.',
    'hasil_pertemuan' => NULL,
    'bukti_pertemuan' => 'pemanggilan/bukti/PgqXXFJDcFMzK4yJCwPODdOF3Afmp1JKch0unh1u.jpg',
    'surat_pernyataan' => 'pemanggilan/surat/KoTytLSSoNORNrTc06G2FlkSWr48wWxdGQuuvFMD.jpg',
    'status' => 'sudah_hadir',
    'id_guru' => 1,
    'created_at' => '2026-07-20 09:27:52',
    'updated_at' => '2026-07-22 08:30:40',
  ),
));

        // Table: riwayat_kesehatan (1 rows)
        DB::table('riwayat_kesehatan')->truncate();
        DB::table('riwayat_kesehatan')->insert(array (
  0 => 
  array (
    'id_riwayat_kesehatan' => 1,
    'nis' => 13862,
    'tanggal' => '2026-07-21',
    'tinggi_badan' => 156,
    'berat_badan' => 48,
    'golongan_darah' => 'B',
    'penyakit_bawaan' => '-',
    'alergi' => 'dingin',
    'riwayat_penyakit' => 'asma',
    'catatan_khusus' => NULL,
    'created_at' => '2026-07-21 13:55:27',
    'updated_at' => '2026-07-21 13:55:27',
  ),
));


        Schema::enableForeignKeyConstraints();
    }
}