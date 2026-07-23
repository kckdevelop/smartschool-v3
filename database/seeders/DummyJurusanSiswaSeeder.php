<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DummyJurusanSiswaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $majorsInfo = [
            'RPL' => ['nama' => 'Rekayasa Perangkat Lunak'],
            'TSM' => ['nama' => 'Teknik Sepeda Motor'],
            'TPM' => ['nama' => 'Teknik Pemesinan'],
            'TAV' => ['nama' => 'Teknik Audio Video'],
            'TKR' => ['nama' => 'Teknik Kendaraan Ringan'],
        ];

        // 1. Insert or get majors
        $majorIds = [];
        foreach ($majorsInfo as $code => $info) {
            $existing = DB::table('jurusan')->where('kode_jurusan', $code)->first();
            if ($existing) {
                $majorIds[$code] = $existing->id_jurusan;
            } else {
                $id = DB::table('jurusan')->insertGetId([
                    'kode_jurusan' => $code,
                    'nama_jurusan' => $info['nama'],
                    'status' => 'aktif',
                ]);
                $majorIds[$code] = $id;
            }
        }

        // Indonesian names list for dummy data
        $firstNamesL = ['Ahmad', 'Budi', 'Chandra', 'Dedi', 'Eko', 'Fajar', 'Guntur', 'Hadi', 'Iwan', 'Joko', 'Kurniawan', 'Lukman', 'Mulyono', 'Nugroho', 'Oki', 'Prabowo', 'Rian', 'Setyo', 'Taufik', 'Umar', 'Wahyu', 'Yanto', 'Zulkifli'];
        $firstNamesP = ['Ani', 'Citra', 'Dewi', 'Endah', 'Fitri', 'Gita', 'Hana', 'Indah', 'Kartika', 'Laras', 'Mega', 'Novi', 'Olga', 'Putri', 'Rina', 'Siti', 'Tari', 'Utami', 'Wulan', 'Yuni', 'Zahra', 'Lia', 'Rara'];
        $lastNames = ['Santoso', 'Wibowo', 'Pratama', 'Hidayat', 'Saputra', 'Kusuma', 'Sari', 'Lestari', 'Putra', 'Setiawan', 'Nugraha', 'Wijaya', 'Siregar', 'Lubis', 'Harahap', 'Nasution', 'Ginting', 'Sembiring'];

        $places = ['Yogyakarta', 'Sleman', 'Bantul', 'Kulon Progo', 'Gunungkidul', 'Surakarta', 'Klaten', 'Magelang', 'Semarang', 'Jakarta'];
        $religions = ['Islam', 'Kristen', 'Katolik', 'Hindu', 'Buddha', 'Konghucu'];

        $startNis = 2026100;

        // Prepare bulk insert arrays
        $studentsToInsert = [];
        $detailsToInsert = [];

        foreach ($majorIds as $code => $majorId) {
            // Create 2 rombels per major
            for ($rombelNum = 1; $rombelNum <= 2; $rombelNum++) {
                $rombelName = $code . ' ' . $rombelNum;
                
                // Check if class exists
                $existingClass = DB::table('kelas')
                    ->where('id_jurusan', $majorId)
                    ->where('rombel', $rombelName)
                    ->where('tingkat', 10)
                    ->first();

                if ($existingClass) {
                    $classId = $existingClass->id_kelas;
                } else {
                    $classId = DB::table('kelas')->insertGetId([
                        'tahun_masuk' => '2026',
                        'tingkat' => 10,
                        'id_jurusan' => $majorId,
                        'rombel' => $rombelName,
                        'walikelas' => null,
                        'status' => 'aktif',
                    ]);
                }

                // Generate 30 students for this rombel
                for ($studentNum = 1; $studentNum <= 30; $studentNum++) {
                    $nis = $startNis++;
                    
                    // Make sure Nis is unique
                    while (DB::table('user_siswa')->where('nis', $nis)->exists()) {
                        $nis = $startNis++;
                    }

                    $jenkel = rand(0, 1) ? 'L' : 'P';
                    $firstName = $jenkel == 'L' 
                        ? $firstNamesL[array_rand($firstNamesL)] 
                        : $firstNamesP[array_rand($firstNamesP)];
                    $lastName = $lastNames[array_rand($lastNames)];
                    $name = $firstName . ' ' . $lastName;

                    $birthPlace = $places[array_rand($places)];
                    $birthDate = Carbon::create(2010, rand(1, 12), rand(1, 28))->toDateString();

                    $studentsToInsert[] = [
                        'nis' => $nis,
                        'password' => '7c4a8d09ca3762af61e59520943dc26494f8941b', // SHA1 of '123456'
                        'password_wali' => '7c4a8d09ca3762af61e59520943dc26494f8941b',
                        'id_kelas' => $classId,
                        'nama_siswa' => $name,
                        'jenkel' => $jenkel,
                        'tempat_lahir' => $birthPlace,
                        'tgl_lahir' => $birthDate,
                        'kelengkapan' => 0,
                        'status' => 'aktif',
                    ];

                    $detailsToInsert[] = [
                        'nis' => $nis,
                        'alamat' => 'Jl. Raya No. ' . rand(1, 100) . ', ' . $birthPlace,
                        'agama' => $religions[array_rand($religions)],
                        'golongan_darah' => ['A', 'B', 'AB', 'O'][rand(0, 3)],
                        'nama_ayah' => $firstNamesL[array_rand($firstNamesL)] . ' ' . $lastNames[array_rand($lastNames)],
                        'pekerjaan_ayah' => ['Wiraswasta', 'PNS', 'Karyawan Swasta', 'Buruh', 'Guru'][rand(0, 4)],
                        'no_telp_ayah' => '08' . rand(1000000000, 9999999999),
                        'nama_ibu' => $firstNamesP[array_rand($firstNamesP)] . ' ' . $lastNames[array_rand($lastNames)],
                        'pekerjaan_ibu' => ['Ibu Rumah Tangga', 'Karyawan Swasta', 'PNS', 'Wiraswasta'][rand(0, 3)],
                        'no_telp_ibu' => '08' . rand(1000000000, 9999999999),
                        'nama_wali' => null,
                        'pekerjaan_wali' => null,
                        'no_telp_wali' => null,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                }
            }
        }

        // Bulk insert in chunks to prevent SQL limitations
        foreach (array_chunk($studentsToInsert, 50) as $chunk) {
            DB::table('user_siswa')->insert($chunk);
        }

        foreach (array_chunk($detailsToInsert, 50) as $chunk) {
            DB::table('detail_siswa')->insert($chunk);
        }
    }
}
