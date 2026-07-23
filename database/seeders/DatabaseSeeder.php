<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Seed user_smartschool (Staff & Admin users dengan 6 role)
        $this->call(UserSmartschoolSeeder::class);

        // 2. Seed Jurusan
        DB::table('jurusan')->insert([
            ['id_jurusan' => 1, 'kode_jurusan' => 'RPL', 'nama_jurusan' => 'Rekayasa Perangkat Lunak', 'status' => 'aktif'],
            ['id_jurusan' => 2, 'kode_jurusan' => 'TKJ', 'nama_jurusan' => 'Teknik Komputer & Jaringan', 'status' => 'aktif'],
        ]);

        // 3. Seed Guru
        DB::table('guru')->insert([
            ['id_guru' => 1, 'no_id' => 1001, 'nama_guru' => 'Budi Santoso, S.Pd.', 'jenkel' => 'L', 'guru_bk' => 'tidak', 'status' => 'aktif', 'password' => Hash::make('123456')],
            ['id_guru' => 2, 'no_id' => 1002, 'nama_guru' => 'Sri Wahyuni, M.Pd.', 'jenkel' => 'P', 'guru_bk' => 'ya', 'status' => 'aktif', 'password' => Hash::make('123456')],
        ]);

        // 4. Seed Kelas
        DB::table('kelas')->insert([
            ['id_kelas' => 1, 'tahun_masuk' => '2024', 'tingkat' => 10, 'id_jurusan' => 1, 'rombel' => 'RPL 1', 'walikelas' => 1, 'status' => 'aktif'],
            ['id_kelas' => 2, 'tahun_masuk' => '2023', 'tingkat' => 11, 'id_jurusan' => 1, 'rombel' => 'RPL 2', 'walikelas' => 2, 'status' => 'aktif'],
        ]);

        // 5. Seed user_siswa
        DB::table('user_siswa')->insert([
            [
                'nis' => 2026001,
                'password' => '7c4a8d09ca3762af61e59520943dc26494f8941b', // SHA1 of '123456'
                'password_wali' => '7c4a8d09ca3762af61e59520943dc26494f8941b',
                'id_kelas' => 1,
                'nama_siswa' => 'Ahmad Fauzi',
                'jenkel' => 'L',
                'tempat_lahir' => 'Sleman',
                'tgl_lahir' => '2010-05-12',
                'kelengkapan' => 1,
                'status' => 'aktif',
            ],
            [
                'nis' => 2026002,
                'password' => Hash::make('123456'), // Bcrypt of '123456'
                'password_wali' => '7c4a8d09ca3762af61e59520943dc26494f8941b',
                'id_kelas' => 2,
                'nama_siswa' => 'Rara Amalia',
                'jenkel' => 'P',
                'tempat_lahir' => 'Yogyakarta',
                'tgl_lahir' => '2009-08-22',
                'kelengkapan' => 1,
                'status' => 'aktif',
            ],
        ]);

        // 6. Seed Presensi (Attendance for today and yesterday)
        $today = Carbon::today()->toDateString();
        $yesterday = Carbon::yesterday()->toDateString();

        DB::table('presensi')->insert([
            [
                'id_presensi' => 1,
                'nis' => 2026001,
                'tanggal' => $today,
                'jam' => '07:05:00',
                'status' => 1,
                'keterangan' => 'Tepat waktu',
                'file' => null,
            ],
            [
                'id_presensi' => 2,
                'nis' => 2026002,
                'tanggal' => $today,
                'jam' => '07:20:00',
                'status' => 3,
                'keterangan' => 'Acara keluarga',
                'file' => 'surat_izin.pdf',
            ],
            [
                'id_presensi' => 3,
                'nis' => 2026001,
                'tanggal' => $yesterday,
                'jam' => '06:58:00',
                'status' => 1,
                'keterangan' => 'Tepat waktu',
                'file' => null,
            ],
        ]);

        // 7. Seed Kunjungan UKS
        DB::table('kunjungan_uks')->insert([
            [
                'id_kunjungan' => 1,
                'nis' => 2026001,
                'tanggal' => $today,
                'jam' => '10:15:00',
                'keluhan' => 'Pusing dan lemas',
                'diagnosa' => 'Dehidrasi / Kurang Istirahat',
                'tindakan' => 'Diberikan minum air hangat dan istirahat 30 menit',
            ],
        ]);

        // 8. Seed Sekolah (School branding details)
        DB::table('sekolah')->insert([
            [
                'id_sekolah' => 1,
                'npsn' => 12345678,
                'nama_sekolah' => 'SMK Muhammadiyah 1 Yogyakarta',
                'kepala_sekolah' => 'Drs. H. Herynugroho, M.Pd.',
                'nip' => '196504211993031',
                'status' => 'swasta',
                'alamat_sekolah' => 'Jl. Kenari No. 4, Yogyakarta',
                'logo' => 'sekolah/logo/30misSNHqzwsnIqGsWIimqX4P7AeqPdWgm6pnoCg.jpg',
                'kop' => null,
                'ijin' => 'ya',
                'sync_otomatis' => 1,
            ]
        ]);

        // 9. Seed Data Mesin Finger
        DB::table('data_mesin')->insert([
            ['nama_mesin' => 'Unit 1 (Mesin 1)', 'sn' => 'NJF7243603633', 'password' => 'solution', 'data' => null, 'last_update' => null],
            ['nama_mesin' => 'Unit 1 (Mesin 2)', 'sn' => 'BWXP233560696', 'password' => 'solution', 'data' => null, 'last_update' => null],
            ['nama_mesin' => 'Unit 1 (Mesin 3)', 'sn' => 'NJF7243603906', 'password' => 'solution', 'data' => null, 'last_update' => null],
            ['nama_mesin' => 'Unit 1 (Mesin 4)', 'sn' => 'NJF7243600804', 'password' => 'solution', 'data' => null, 'last_update' => null],
            ['nama_mesin' => 'Unit 2 (Mesin 1)', 'sn' => 'NJF7243600115', 'password' => 'solution', 'data' => null, 'last_update' => null],
            ['nama_mesin' => 'Unit 3 (Mesin 1)', 'sn' => 'NJF7243603629', 'password' => 'solution', 'data' => null, 'last_update' => null],
            ['nama_mesin' => 'Unit 4 (Mesin 1)', 'sn' => 'NJF7243601217', 'password' => 'solution', 'data' => null, 'last_update' => null],
        ]);

        // 10. Seed BTAQ Master Data
        $this->call(BtaqMasterSeeder::class);
    }
}
