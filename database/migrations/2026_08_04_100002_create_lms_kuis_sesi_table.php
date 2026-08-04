<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Tabel sesi pengerjaan kuis per siswa.
     * Menyimpan urutan soal yang sudah diacak (seed per siswa) agar konsisten saat resume.
     */
    public function up(): void
    {
        Schema::create('lms_kuis_sesi', function (Blueprint $table) {
            $table->integer('id_sesi')->autoIncrement()->primary();
            $table->integer('id_tugas');
            $table->integer('nis');
            $table->integer('id_token')->nullable(); // token yang dipakai masuk
            $table->integer('percobaan_ke')->default(1); // nomor percobaan
            $table->json('urutan_soal')->nullable(); // array id_soal dalam urutan acak yang konsisten
            $table->json('urutan_pilihan')->nullable(); // array map {id_soal: [id_pilihan,...]} urutan acak pilihan
            $table->timestamp('waktu_mulai')->nullable();
            $table->timestamp('waktu_selesai')->nullable();
            $table->integer('nilai')->nullable(); // nilai akhir 0-100
            $table->enum('status', ['berlangsung', 'selesai', 'timeout'])->default('berlangsung');
            $table->timestamps();

            $table->index('id_tugas');
            $table->index('nis');
            $table->unique(['id_tugas', 'nis', 'percobaan_ke']); // 1 siswa 1 percobaan = 1 sesi
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lms_kuis_sesi');
    }
};
