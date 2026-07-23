<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PklSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Seed pkl_nomor_surat
        DB::table('pkl_nomor_surat')->insertOrIgnore([
            [
                'jenis_surat' => 'permohonan',
                'format_nomor' => '{NO}/PM/PKL/SMKM1/{BULAN-ROMAWI}/{TAHUN}',
                'prefix' => null,
                'counter_terakhir' => 0,
                'tahun_reset' => date('Y'),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'jenis_surat' => 'penempatan',
                'format_nomor' => '{NO}/PP/PKL/SMKM1/{BULAN-ROMAWI}/{TAHUN}',
                'prefix' => null,
                'counter_terakhir' => 0,
                'tahun_reset' => date('Y'),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'jenis_surat' => 'penarikan',
                'format_nomor' => '{NO}/PT/PKL/SMKM1/{BULAN-ROMAWI}/{TAHUN}',
                'prefix' => null,
                'counter_terakhir' => 0,
                'tahun_reset' => date('Y'),
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        // 2. Seed pkl_dudi (Mitra Industri)
        DB::table('pkl_dudi')->insertOrIgnore([
            [
                'id_dudi' => 1,
                'nama_dudi' => 'PT Solusi Teknologi Nusantara',
                'bidang_usaha' => 'Teknologi Informasi & Software House',
                'alamat' => 'Jl. Kaliurang KM 10, Sinduharjo, Ngaglik',
                'kota' => 'Sleman',
                'no_telepon' => '0274-123456',
                'email' => 'hrd@solusitekno.id',
                'nama_pic' => 'Budi Prasetyo, S.Kom.',
                'jabatan_pic' => 'HR Manager',
                'no_hp_pic' => '081234567890',
                'kuota_siswa' => 5,
                'status' => 'aktif',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_dudi' => 2,
                'nama_dudi' => 'Bengkel Agung Motor Utama',
                'bidang_usaha' => 'Otomotif & Servis Motor',
                'alamat' => 'Jl. Magelang KM 5.5, Karangwaru',
                'kota' => 'Yogyakarta',
                'no_telepon' => '0274-654321',
                'email' => 'info@agungmotor.com',
                'nama_pic' => 'Agus Budiman',
                'jabatan_pic' => 'Kepala Bengkel',
                'no_hp_pic' => '089876543210',
                'kuota_siswa' => 3,
                'status' => 'aktif',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_dudi' => 3,
                'nama_dudi' => 'Nusa Computindo Utama',
                'bidang_usaha' => 'Jaringan Komputer & IT Support',
                'alamat' => 'Jl. Gejayan No. 12, Caturtunggal',
                'kota' => 'Sleman',
                'no_telepon' => '0274-789012',
                'email' => 'nusa.computer@gmail.com',
                'nama_pic' => 'Sri Astuti',
                'jabatan_pic' => 'Supervisor IT',
                'no_hp_pic' => '085223344556',
                'kuota_siswa' => 4,
                'status' => 'aktif',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        // 3. Seed pkl_gelombang
        DB::table('pkl_gelombang')->insertOrIgnore([
            [
                'id_gelombang' => 1,
                'nama_gelombang' => 'PKL Gelombang I (RPL & TKJ) 2026',
                'tahun_ajaran' => '2025/2026',
                'tanggal_mulai' => '2026-07-01',
                'tanggal_selesai' => '2026-09-30',
                'status' => 'aktif',
                'keterangan' => 'Pelaksanaan PKL semester ganjil untuk kompetensi keahlian RPL dan TKJ.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_gelombang' => 2,
                'nama_gelombang' => 'PKL Gelombang II 2026',
                'tahun_ajaran' => '2025/2026',
                'tanggal_mulai' => '2026-10-01',
                'tanggal_selesai' => '2026-12-31',
                'status' => 'draft',
                'keterangan' => 'Periode PKL cadangan.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        // 4. Mapping kelas ke gelombang (Kelas RPL 2 -> ID 2, RPL 1 -> ID 1)
        DB::table('pkl_kelas_gelombang')->insertOrIgnore([
            [
                'id' => 1,
                'id_gelombang' => 1,
                'id_kelas' => 2, // 11 RPL 2
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 2,
                'id_gelombang' => 1,
                'id_kelas' => 1, // 10 RPL 1
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
