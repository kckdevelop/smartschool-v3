<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class GuruMapelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // ─── Tambahkan data Guru (mulai dari id_guru 3, karena 1 & 2 sudah ada) ───
        $password = Hash::make('123456');

        $guruTambahan = [
            ['id_guru' => 3,  'no_id' => 1003, 'nama_guru' => 'Ahmad Rifa\'i, S.T.',          'guru_bk' => 'tidak', 'guru_ismuba' => 'tidak', 'status' => 'aktif', 'password' => $password],
            ['id_guru' => 4,  'no_id' => 1004, 'nama_guru' => 'Nurul Hidayah, S.Pd.',          'guru_bk' => 'tidak', 'guru_ismuba' => 'ya',    'status' => 'aktif', 'password' => $password],
            ['id_guru' => 5,  'no_id' => 1005, 'nama_guru' => 'Drs. Haryono, M.M.',            'guru_bk' => 'tidak', 'guru_ismuba' => 'tidak', 'status' => 'aktif', 'password' => $password],
            ['id_guru' => 6,  'no_id' => 1006, 'nama_guru' => 'Fitria Kusuma Dewi, S.Pd.',     'guru_bk' => 'tidak', 'guru_ismuba' => 'tidak', 'status' => 'aktif', 'password' => $password],
            ['id_guru' => 7,  'no_id' => 1007, 'nama_guru' => 'Bambang Suharto, S.T.',         'guru_bk' => 'tidak', 'guru_ismuba' => 'tidak', 'status' => 'aktif', 'password' => $password],
            ['id_guru' => 8,  'no_id' => 1008, 'nama_guru' => 'Siti Rahmawati, M.Pd.',         'guru_bk' => 'tidak', 'guru_ismuba' => 'ya',    'status' => 'aktif', 'password' => $password],
            ['id_guru' => 9,  'no_id' => 1009, 'nama_guru' => 'Dwi Prasetyo, S.Kom.',          'guru_bk' => 'tidak', 'guru_ismuba' => 'tidak', 'status' => 'aktif', 'password' => $password],
            ['id_guru' => 10, 'no_id' => 1010, 'nama_guru' => 'Rina Agustina, S.Pd.',          'guru_bk' => 'tidak', 'guru_ismuba' => 'tidak', 'status' => 'aktif', 'password' => $password],
            ['id_guru' => 11, 'no_id' => 1011, 'nama_guru' => 'Ir. Wahyu Prasetya, M.T.',      'guru_bk' => 'tidak', 'guru_ismuba' => 'tidak', 'status' => 'aktif', 'password' => $password],
            ['id_guru' => 12, 'no_id' => 1012, 'nama_guru' => 'Endang Sulistyowati, S.Pd.',    'guru_bk' => 'ya',    'guru_ismuba' => 'tidak', 'status' => 'aktif', 'password' => $password],
            ['id_guru' => 13, 'no_id' => 1013, 'nama_guru' => 'Fajar Nugroho, S.Kom., M.T.', 'guru_bk' => 'tidak', 'guru_ismuba' => 'tidak', 'status' => 'aktif', 'password' => $password],
            ['id_guru' => 14, 'no_id' => 1014, 'nama_guru' => 'Indah Permatasari, S.Pd.',      'guru_bk' => 'tidak', 'guru_ismuba' => 'ya',    'status' => 'aktif', 'password' => $password],
            ['id_guru' => 15, 'no_id' => 1015, 'nama_guru' => 'Mugiyanto, S.T., M.M.',        'guru_bk' => 'tidak', 'guru_ismuba' => 'tidak', 'status' => 'aktif', 'password' => $password],
            ['id_guru' => 16, 'no_id' => 1016, 'nama_guru' => 'Lestari Nur Wulandari, S.Pd.', 'guru_bk' => 'tidak', 'guru_ismuba' => 'tidak', 'status' => 'aktif', 'password' => $password],
            ['id_guru' => 17, 'no_id' => 1017, 'nama_guru' => 'Raden Sigit Wibowo, S.Pd.',    'guru_bk' => 'tidak', 'guru_ismuba' => 'tidak', 'status' => 'aktif', 'password' => $password],
            ['id_guru' => 18, 'no_id' => 1018, 'nama_guru' => 'Anik Setyowati, M.Pd.',        'guru_bk' => 'tidak', 'guru_ismuba' => 'ya',    'status' => 'aktif', 'password' => $password],
            ['id_guru' => 19, 'no_id' => 1019, 'nama_guru' => 'Hendra Kusuma, S.Pd., M.T.',  'guru_bk' => 'tidak', 'guru_ismuba' => 'tidak', 'status' => 'aktif', 'password' => $password],
            ['id_guru' => 20, 'no_id' => 1020, 'nama_guru' => 'Tri Wahyu Ningsih, S.Pd.',     'guru_bk' => 'ya',    'guru_ismuba' => 'tidak', 'status' => 'aktif', 'password' => $password],
        ];

        // Update yang sudah ada (tambahkan guru_ismuba jika kolom ada tapi kosong)
        DB::table('guru')->where('id_guru', 1)->update(['guru_ismuba' => 'tidak']);
        DB::table('guru')->where('id_guru', 2)->update(['guru_ismuba' => 'tidak']);

        // Insert guru tambahan (skip jika sudah ada)
        foreach ($guruTambahan as $guru) {
            if (!DB::table('guru')->where('id_guru', $guru['id_guru'])->exists()) {
                DB::table('guru')->insert($guru);
            }
        }

        echo "  ✓ " . DB::table('guru')->count() . " data guru berhasil di-seed.\n";

        // ─── Seed Mata Pelajaran ───
        $mapelData = [
            // Mata Pelajaran Umum (Kelompok A)
            ['kode_mapel' => 'PAI',   'nama_mapel' => 'Pendidikan Agama Islam & Budi Pekerti'],
            ['kode_mapel' => 'PPKn',  'nama_mapel' => 'Pendidikan Pancasila & Kewarganegaraan'],
            ['kode_mapel' => 'BIN',   'nama_mapel' => 'Bahasa Indonesia'],
            ['kode_mapel' => 'MTK',   'nama_mapel' => 'Matematika'],
            ['kode_mapel' => 'SEJ',   'nama_mapel' => 'Sejarah Indonesia'],
            ['kode_mapel' => 'BING',  'nama_mapel' => 'Bahasa Inggris'],

            // Mata Pelajaran Umum (Kelompok B)
            ['kode_mapel' => 'SBD',   'nama_mapel' => 'Seni Budaya & Prakarya'],
            ['kode_mapel' => 'PJOK',  'nama_mapel' => 'PJOK (Pendidikan Jasmani, Olahraga, dan Kesehatan)'],

            // Mata Pelajaran Ismuba (Muhammadiyah)
            ['kode_mapel' => 'ISMB',  'nama_mapel' => 'Al-Islam & Kemuhammadiyahan'],
            ['kode_mapel' => 'BARAB', 'nama_mapel' => 'Bahasa Arab'],

            // Mata Pelajaran Kejuruan RPL
            ['kode_mapel' => 'SDTE',  'nama_mapel' => 'Simulasi & Komunikasi Digital'],
            ['kode_mapel' => 'DPIB',  'nama_mapel' => 'Dasar Program Keahlian (Pemrograman Dasar)'],
            ['kode_mapel' => 'PEMWEB','nama_mapel' => 'Pemrograman Web & Perangkat Bergerak'],
            ['kode_mapel' => 'BASIS', 'nama_mapel' => 'Basis Data'],
            ['kode_mapel' => 'JARKOM','nama_mapel' => 'Sistem Operasi Jaringan'],
            ['kode_mapel' => 'RPL',   'nama_mapel' => 'Rekayasa Perangkat Lunak'],
            ['kode_mapel' => 'PKK',   'nama_mapel' => 'Produk Kreatif & Kewirausahaan'],
            ['kode_mapel' => 'BK',    'nama_mapel' => 'Bimbingan Konseling'],
        ];

        // Kosongkan dulu lalu insert ulang supaya tidak duplikat
        DB::table('mapel')->truncate();
        DB::table('mapel')->insert($mapelData);

        echo "  ✓ " . DB::table('mapel')->count() . " data mata pelajaran berhasil di-seed.\n";
    }
}
