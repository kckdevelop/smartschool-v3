<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Tabel auto-save jawaban per soal per sesi kuis.
     * Jawaban disimpan setiap kali siswa memilih jawaban, sehingga
     * tidak perlu mengulang jika aplikasi ditutup.
     */
    public function up(): void
    {
        Schema::create('lms_kuis_jawaban', function (Blueprint $table) {
            $table->integer('id_jawaban')->autoIncrement()->primary();
            $table->integer('id_sesi'); // FK ke lms_kuis_sesi
            $table->integer('id_soal'); // FK ke lms_soal
            $table->integer('id_pilihan')->nullable(); // FK ke lms_soal_pilihan (untuk PG & PG Komplek)
            $table->text('jawaban_teks')->nullable(); // untuk jenis soal esai (jika ada di masa depan)
            $table->boolean('is_benar')->nullable(); // null = belum dinilai / esai, true/false untuk PG
            $table->timestamps();

            $table->index('id_sesi');
            $table->index('id_soal');
            // Satu soal = satu jawaban per sesi (kecuali PG Komplek bisa multiple)
            // Untuk PG Komplek, tidak ada unique constraint di id_soal saja
            $table->unique(['id_sesi', 'id_soal', 'id_pilihan'], 'unique_jawaban_sesi_soal_pilihan');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lms_kuis_jawaban');
    }
};
