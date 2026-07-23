<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\UserSmartschool;
use Illuminate\Support\Facades\Hash;

class UserSmartschoolSeeder extends Seeder
{
    /**
     * Seed user data terbagi dalam 6 role utama:
     * - Super Admin
     * - Admin Data dan Kurikulum
     * - Guru BK
     * - Petugas UKS
     * - Admin ISMUBA
     * - Admin PKL
     */
    public function run(): void
    {
        $users = [
            [
                'username'     => 'superadmin',
                'password'     => Hash::make('123456'),
                'nama_lengkap' => 'Super Administrator',
                'level'        => 'super_admin',
            ],
            [
                'username'     => 'admin',
                'password'     => Hash::make('123456'),
                'nama_lengkap' => 'Admin Data & Kurikulum',
                'level'        => 'admin_kurikulum',
            ],
            [
                'username'     => 'guru_bk',
                'password'     => Hash::make('123456'),
                'nama_lengkap' => 'Guru BK / Konselor',
                'level'        => 'guru_bk',
            ],
            [
                'username'     => 'petugas_uks',
                'password'     => Hash::make('123456'),
                'nama_lengkap' => 'Petugas UKS',
                'level'        => 'petugas_uks',
            ],
            [
                'username'     => 'admin_ismuba',
                'password'     => Hash::make('123456'),
                'nama_lengkap' => 'Admin ISMUBA',
                'level'        => 'admin_ismuba',
            ],
            [
                'username'     => 'admin_pkl',
                'password'     => Hash::make('123456'),
                'nama_lengkap' => 'Admin PKL',
                'level'        => 'admin_pkl',
            ],
        ];

        foreach ($users as $userData) {
            UserSmartschool::updateOrCreate(
                ['username' => $userData['username']],
                $userData
            );
        }
    }
}
