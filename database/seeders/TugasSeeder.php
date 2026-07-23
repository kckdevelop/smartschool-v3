<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class TugasSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Schema::disableForeignKeyConstraints();

        // Table: tugas (24 rows)
        DB::table('tugas')->truncate();
        DB::table('tugas')->insert(array (
  0 => 
  array (
    'id_tugas' => 3,
    'tanggal' => '2026-06-29',
    'id_guru' => 135,
    'judul_tugas' => 'Resume Materi',
    'id_kelas' => 38,
    'deskripsi' => 'Kerjakan tugas ini dengan baik dan benar. Deadline sesuai tanggal yang tertera.',
    'lampiran' => NULL,
    'status' => 'aktif',
  ),
  1 => 
  array (
    'id_tugas' => 4,
    'tanggal' => '2026-06-07',
    'id_guru' => 135,
    'judul_tugas' => 'Tugas Kelompok',
    'id_kelas' => 38,
    'deskripsi' => 'Kerjakan tugas ini dengan baik dan benar. Deadline sesuai tanggal yang tertera.',
    'lampiran' => NULL,
    'status' => 'aktif',
  ),
  2 => 
  array (
    'id_tugas' => 5,
    'tanggal' => '2026-06-30',
    'id_guru' => 104,
    'judul_tugas' => 'Esai Argumentatif',
    'id_kelas' => 38,
    'deskripsi' => 'Kerjakan tugas ini dengan baik dan benar. Deadline sesuai tanggal yang tertera.',
    'lampiran' => NULL,
    'status' => 'aktif',
  ),
  3 => 
  array (
    'id_tugas' => 6,
    'tanggal' => '2026-06-18',
    'id_guru' => 144,
    'judul_tugas' => 'Praktikum Lab',
    'id_kelas' => 39,
    'deskripsi' => 'Kerjakan tugas ini dengan baik dan benar. Deadline sesuai tanggal yang tertera.',
    'lampiran' => NULL,
    'status' => 'aktif',
  ),
  4 => 
  array (
    'id_tugas' => 7,
    'tanggal' => '2026-06-20',
    'id_guru' => 137,
    'judul_tugas' => 'Laporan Kegiatan',
    'id_kelas' => 39,
    'deskripsi' => 'Kerjakan tugas ini dengan baik dan benar. Deadline sesuai tanggal yang tertera.',
    'lampiran' => NULL,
    'status' => 'aktif',
  ),
  5 => 
  array (
    'id_tugas' => 8,
    'tanggal' => '2026-06-09',
    'id_guru' => 147,
    'judul_tugas' => 'Tugas Kelompok',
    'id_kelas' => 39,
    'deskripsi' => 'Kerjakan tugas ini dengan baik dan benar. Deadline sesuai tanggal yang tertera.',
    'lampiran' => NULL,
    'status' => 'aktif',
  ),
  6 => 
  array (
    'id_tugas' => 9,
    'tanggal' => '2026-06-09',
    'id_guru' => 174,
    'judul_tugas' => 'Resume Materi',
    'id_kelas' => 40,
    'deskripsi' => 'Kerjakan tugas ini dengan baik dan benar. Deadline sesuai tanggal yang tertera.',
    'lampiran' => NULL,
    'status' => 'aktif',
  ),
  7 => 
  array (
    'id_tugas' => 10,
    'tanggal' => '2026-06-08',
    'id_guru' => 151,
    'judul_tugas' => 'Praktikum Lab',
    'id_kelas' => 40,
    'deskripsi' => 'Kerjakan tugas ini dengan baik dan benar. Deadline sesuai tanggal yang tertera.',
    'lampiran' => NULL,
    'status' => 'aktif',
  ),
  8 => 
  array (
    'id_tugas' => 11,
    'tanggal' => '2026-06-25',
    'id_guru' => 121,
    'judul_tugas' => 'Soal Ulangan Harian',
    'id_kelas' => 40,
    'deskripsi' => 'Kerjakan tugas ini dengan baik dan benar. Deadline sesuai tanggal yang tertera.',
    'lampiran' => NULL,
    'status' => 'aktif',
  ),
  9 => 
  array (
    'id_tugas' => 12,
    'tanggal' => '2026-06-18',
    'id_guru' => 172,
    'judul_tugas' => 'Presentasi Hasil Observasi',
    'id_kelas' => 41,
    'deskripsi' => 'Kerjakan tugas ini dengan baik dan benar. Deadline sesuai tanggal yang tertera.',
    'lampiran' => NULL,
    'status' => 'aktif',
  ),
  10 => 
  array (
    'id_tugas' => 13,
    'tanggal' => '2026-06-12',
    'id_guru' => 148,
    'judul_tugas' => 'Soal Ulangan Harian',
    'id_kelas' => 41,
    'deskripsi' => 'Kerjakan tugas ini dengan baik dan benar. Deadline sesuai tanggal yang tertera.',
    'lampiran' => NULL,
    'status' => 'aktif',
  ),
  11 => 
  array (
    'id_tugas' => 14,
    'tanggal' => '2026-06-27',
    'id_guru' => 122,
    'judul_tugas' => 'Resume Materi',
    'id_kelas' => 41,
    'deskripsi' => 'Kerjakan tugas ini dengan baik dan benar. Deadline sesuai tanggal yang tertera.',
    'lampiran' => NULL,
    'status' => 'aktif',
  ),
  12 => 
  array (
    'id_tugas' => 15,
    'tanggal' => '2026-06-15',
    'id_guru' => 122,
    'judul_tugas' => 'Proyek Akhir Semester',
    'id_kelas' => 42,
    'deskripsi' => 'Kerjakan tugas ini dengan baik dan benar. Deadline sesuai tanggal yang tertera.',
    'lampiran' => NULL,
    'status' => 'aktif',
  ),
  13 => 
  array (
    'id_tugas' => 16,
    'tanggal' => '2026-06-08',
    'id_guru' => 130,
    'judul_tugas' => 'Proyek Akhir Semester',
    'id_kelas' => 42,
    'deskripsi' => 'Kerjakan tugas ini dengan baik dan benar. Deadline sesuai tanggal yang tertera.',
    'lampiran' => NULL,
    'status' => 'aktif',
  ),
  14 => 
  array (
    'id_tugas' => 17,
    'tanggal' => '2026-06-21',
    'id_guru' => 152,
    'judul_tugas' => 'Tugas Kelompok',
    'id_kelas' => 42,
    'deskripsi' => 'Kerjakan tugas ini dengan baik dan benar. Deadline sesuai tanggal yang tertera.',
    'lampiran' => NULL,
    'status' => 'aktif',
  ),
  15 => 
  array (
    'id_tugas' => 18,
    'tanggal' => '2026-06-13',
    'id_guru' => 125,
    'judul_tugas' => 'Tugas Kelompok',
    'id_kelas' => 43,
    'deskripsi' => 'Kerjakan tugas ini dengan baik dan benar. Deadline sesuai tanggal yang tertera.',
    'lampiran' => NULL,
    'status' => 'aktif',
  ),
  16 => 
  array (
    'id_tugas' => 19,
    'tanggal' => '2026-06-23',
    'id_guru' => 154,
    'judul_tugas' => 'Tugas Kelompok',
    'id_kelas' => 43,
    'deskripsi' => 'Kerjakan tugas ini dengan baik dan benar. Deadline sesuai tanggal yang tertera.',
    'lampiran' => NULL,
    'status' => 'aktif',
  ),
  17 => 
  array (
    'id_tugas' => 20,
    'tanggal' => '2026-06-08',
    'id_guru' => 115,
    'judul_tugas' => 'Presentasi Hasil Observasi',
    'id_kelas' => 43,
    'deskripsi' => 'Kerjakan tugas ini dengan baik dan benar. Deadline sesuai tanggal yang tertera.',
    'lampiran' => NULL,
    'status' => 'aktif',
  ),
  18 => 
  array (
    'id_tugas' => 21,
    'tanggal' => '2026-06-06',
    'id_guru' => 162,
    'judul_tugas' => 'Laporan Kegiatan',
    'id_kelas' => 44,
    'deskripsi' => 'Kerjakan tugas ini dengan baik dan benar. Deadline sesuai tanggal yang tertera.',
    'lampiran' => NULL,
    'status' => 'aktif',
  ),
  19 => 
  array (
    'id_tugas' => 22,
    'tanggal' => '2026-06-08',
    'id_guru' => 146,
    'judul_tugas' => 'Laporan Kegiatan',
    'id_kelas' => 44,
    'deskripsi' => 'Kerjakan tugas ini dengan baik dan benar. Deadline sesuai tanggal yang tertera.',
    'lampiran' => NULL,
    'status' => 'aktif',
  ),
  20 => 
  array (
    'id_tugas' => 23,
    'tanggal' => '2026-06-21',
    'id_guru' => 118,
    'judul_tugas' => 'Praktikum Lab',
    'id_kelas' => 44,
    'deskripsi' => 'Kerjakan tugas ini dengan baik dan benar. Deadline sesuai tanggal yang tertera.',
    'lampiran' => NULL,
    'status' => 'aktif',
  ),
  21 => 
  array (
    'id_tugas' => 24,
    'tanggal' => '2026-06-07',
    'id_guru' => 151,
    'judul_tugas' => 'Proyek Akhir Semester',
    'id_kelas' => 45,
    'deskripsi' => 'Kerjakan tugas ini dengan baik dan benar. Deadline sesuai tanggal yang tertera.',
    'lampiran' => NULL,
    'status' => 'aktif',
  ),
  22 => 
  array (
    'id_tugas' => 25,
    'tanggal' => '2026-06-16',
    'id_guru' => 174,
    'judul_tugas' => 'Praktikum Lab',
    'id_kelas' => 45,
    'deskripsi' => 'Kerjakan tugas ini dengan baik dan benar. Deadline sesuai tanggal yang tertera.',
    'lampiran' => NULL,
    'status' => 'aktif',
  ),
  23 => 
  array (
    'id_tugas' => 26,
    'tanggal' => '2026-06-11',
    'id_guru' => 171,
    'judul_tugas' => 'Laporan Kegiatan',
    'id_kelas' => 45,
    'deskripsi' => 'Kerjakan tugas ini dengan baik dan benar. Deadline sesuai tanggal yang tertera.',
    'lampiran' => NULL,
    'status' => 'aktif',
  ),
));


        Schema::enableForeignKeyConstraints();
    }
}