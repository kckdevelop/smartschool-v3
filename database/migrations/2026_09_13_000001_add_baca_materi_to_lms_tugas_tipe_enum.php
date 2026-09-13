<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Menambahkan tipe 'baca_materi' ke ENUM tipe pada tabel lms_tugas.
     * Tugas baca_materi: guru upload PDF, tugas otomatis selesai saat siswa membuka file.
     */
    public function up(): void
    {
        DB::statement("ALTER TABLE `lms_tugas` MODIFY COLUMN `tipe` ENUM('pdf', 'gambar', 'teks', 'kuis', 'baca_materi') NOT NULL DEFAULT 'pdf'");
    }

    public function down(): void
    {
        // Rollback: kembalikan ke ENUM sebelumnya (tanpa 'baca_materi')
        DB::statement("ALTER TABLE `lms_tugas` MODIFY COLUMN `tipe` ENUM('pdf', 'gambar', 'teks', 'kuis') NOT NULL DEFAULT 'pdf'");
    }
};
