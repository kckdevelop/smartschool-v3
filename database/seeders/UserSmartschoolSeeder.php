<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class UserSmartschoolSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Schema::disableForeignKeyConstraints();

        // Table: user_smartschool (8 rows)
        DB::table('user_smartschool')->truncate();
        DB::table('user_smartschool')->insert(array (
  0 => 
  array (
    'id_user' => 1,
    'username' => 'admin',
    'password' => '$2y$12$IUSZuDFuJe5SIPBDItdbVe00MxSMggNlL6q95DOGZ2wP.yjY4Qsy6',
    'nama_lengkap' => 'Admin Data & Kurikulum',
    'level' => 'admin_kurikulum',
  ),
  1 => 
  array (
    'id_user' => 2,
    'username' => 'uks_user',
    'password' => 'cbfdac6008f9cab4083784cbd1874f76618d2a97',
    'nama_lengkap' => 'UKS Staff Officer',
    'level' => 'uks',
  ),
  2 => 
  array (
    'id_user' => 3,
    'username' => 'bk_user',
    'password' => 'cbfdac6008f9cab4083784cbd1874f76618d2a97',
    'nama_lengkap' => 'BK Advisor',
    'level' => 'bk',
  ),
  3 => 
  array (
    'id_user' => 4,
    'username' => 'superadmin',
    'password' => '$2y$12$N.jDmViA.nEk8gjGrgjanOroVc5OqFq64rxpqbhRXem97QEBbm.Te',
    'nama_lengkap' => 'Super Administrator',
    'level' => 'super_admin',
  ),
  4 => 
  array (
    'id_user' => 5,
    'username' => 'guru_bk',
    'password' => '$2y$12$VCTc8T5DWSNFEnNSBz5Hl.tRlm3G.7sN1rYDm4p8buYImqfPBM996',
    'nama_lengkap' => 'Guru BK / Konselor',
    'level' => 'guru_bk',
  ),
  5 => 
  array (
    'id_user' => 6,
    'username' => 'petugas_uks',
    'password' => '$2y$12$RKxN/kH1x6lUM/LwLqBJ6uAex0MpJJNHXOW6EAwpLiisTTjoOnfve',
    'nama_lengkap' => 'Petugas UKS',
    'level' => 'petugas_uks',
  ),
  6 => 
  array (
    'id_user' => 7,
    'username' => 'admin_ismuba',
    'password' => '$2y$12$PuLK3xU5d1.TusxjJTCgmO8nDFGOOcPKg3vb2Q42G0VT9WJ8Wtm7u',
    'nama_lengkap' => 'Admin ISMUBA',
    'level' => 'admin_ismuba',
  ),
  7 => 
  array (
    'id_user' => 8,
    'username' => 'admin_pkl',
    'password' => '$2y$12$NW2jOb0GEdyR3prBnEwmAOjSicnHXCGIdU1Rv9WZqnVqgGZSkrT8C',
    'nama_lengkap' => 'Admin PKL',
    'level' => 'admin_pkl',
  ),
));

        // Table: users (2 rows)
        DB::table('users')->truncate();
        DB::table('users')->insert(array (
  0 => 
  array (
    'id' => 1,
    'name' => 'AFIFAN PUTRA MUKLISHAN',
    'email' => '13862@siswa.local',
    'email_verified_at' => NULL,
    'password' => '$2y$12$hGwJf/IUrevBGOPyxratDuDMknifAzp7usrK/N15oEI1fTMMnVr.G',
    'remember_token' => NULL,
    'created_at' => '2026-07-06 01:23:45',
    'updated_at' => '2026-07-09 15:28:09',
  ),
  1 => 
  array (
    'id' => 2,
    'name' => 'MUHAMMAD RIDWAN, S.Psi',
    'email' => '1306292@guru.local',
    'email_verified_at' => NULL,
    'password' => '$2y$12$RTGhs9WnX6K3tQYW1TL3fu5LlC4.u7wQsEqtupWnBcrVTRqYB4cEy',
    'remember_token' => NULL,
    'created_at' => '2026-07-09 15:30:31',
    'updated_at' => '2026-07-09 15:30:31',
  ),
));


        Schema::enableForeignKeyConstraints();
    }
}