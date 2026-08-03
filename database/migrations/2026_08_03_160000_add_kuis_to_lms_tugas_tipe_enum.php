<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // MySQL ALTER TABLE MODIFY untuk mengubah ENUM agar include 'kuis'
        DB::statement("ALTER TABLE `lms_tugas` MODIFY COLUMN `tipe` ENUM('pdf', 'gambar', 'teks', 'kuis') NOT NULL DEFAULT 'pdf'");
    }

    public function down(): void
    {
        // Rollback: kembalikan ke ENUM semula (tanpa 'kuis')
        DB::statement("ALTER TABLE `lms_tugas` MODIFY COLUMN `tipe` ENUM('pdf', 'gambar', 'teks') NOT NULL DEFAULT 'pdf'");
    }
};
