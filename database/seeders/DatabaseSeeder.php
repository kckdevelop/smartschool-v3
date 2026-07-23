<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        Schema::disableForeignKeyConstraints();

        // Execute seeders for all imported/inputted data in proper dependency order
        $this->call([
            SekolahSeeder::class,
            TahunAjaranSemesterSeeder::class,
            UserSmartschoolSeeder::class,
            JurusanSeeder::class,
            GuruSeeder::class,
            KaryawanSeeder::class,
            KelasSeeder::class,
            MapelJamPelajaranSeeder::class,
            UserSiswaSeeder::class,
            DataMesinSeeder::class,
            JenisMasterSeeder::class,
            PklDataSeeder::class,
            BkDataSeeder::class,
            IsmubaDataSeeder::class,
            BtaqMasterSeeder::class,
            TugasSeeder::class,
            PresensiDataSeeder::class,
            GayaBelajarSeeder::class,
        ]);

        Schema::enableForeignKeyConstraints();
    }
}
