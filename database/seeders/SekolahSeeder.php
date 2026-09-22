<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class SekolahSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Schema::disableForeignKeyConstraints();

        // Table: sekolah (1 rows)
        DB::table('sekolah')->truncate();
        DB::table('sekolah')->insert(array (
  0 => 
  array (
    'id_sekolah' => 1,
    'npsn' => 20400383,
    'nama_sekolah' => 'SMK Muhammadiyah 1 Bantul',
    'kepala_sekolah' => 'Harimawan,S.Pd.T.,M.S.I.',
    'nbm' => '907793',
    'status' => 'swasta',
    'alamat_sekolah' => 'Jalan Parangtritis KM 12, Manding, Trirenggo, Kecamatan Bantul, Kabupaten Bantul, Daerah Istimewa Yogyakarta',
    'kota' => 'Bantul',
    'logo' => 'sekolah/logo/30misSNHqzwsnIqGsWIimqX4P7AeqPdWgm6pnoCg.jpg',
    'kop' => 'sekolah/kop/iw9npWeFe2nUCldMb99hwbKbjQwJjDsDoGVqsjUD.png',
    'ttd_kepala_sekolah' => 'sekolah/ttd/g1VDKin8uoCl4xpDmhk1ur9j7MLRbVHMepd9rAX6.png',
    'jadwal_aktif' => 'normal',
    'ijin' => 'ya',
    'edit_detail_siswa' => 1,
    'sync_otomatis' => 0,
    'sync_interval' => '30',
    'sync_time' => '00:00',
    'llm_provider' => 'custom',
    'llm_api_key' => 'sk-8e3b65f406c3bd98-a0ejmb-62ab1e83',
    'llm_model' => 'Assistant-smart',
    'groq_key' => 'sk-8e3b65f406c3bd98-a0ejmb-62ab1e83',
    'groq_status' => 'aktif',
    'groq_model' => 'Assistant-smart',
    'groq_quota' => 9999,
    'gemini_key' => 'sk-8e3b65f406c3bd98-a0ejmb-62ab1e83',
    'gemini_status' => 'aktif',
    'gemini_model' => 'Assistant-smart',
    'gemini_quota' => 9999,
    'wa_token' => 'hwoCLXmzdXreEukcgApW',
    'wa_status' => 'aktif',
  ),
));

        Schema::enableForeignKeyConstraints();
    }
}