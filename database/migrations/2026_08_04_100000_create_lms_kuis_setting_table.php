<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Tabel konfigurasi kuis CBT per tugas (tipe=kuis).
     */
    public function up(): void
    {
        Schema::create('lms_kuis_setting', function (Blueprint $table) {
            $table->integer('id_kuis_setting')->autoIncrement()->primary();
            $table->integer('id_tugas')->unique(); // 1 tugas = 1 setting
            $table->unsignedSmallInteger('durasi_menit')->default(0); // 0 = tanpa batas waktu
            $table->boolean('acak_soal')->default(false);
            $table->boolean('acak_jawaban')->default(false);
            $table->boolean('tampilkan_hasil')->default(true); // tampilkan nilai & kunci jawaban setelah submit
            $table->unsignedTinyInteger('maks_percobaan')->default(1); // berapa kali siswa boleh mengikuti kuis
            $table->timestamps();

            $table->index('id_tugas');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lms_kuis_setting');
    }
};
