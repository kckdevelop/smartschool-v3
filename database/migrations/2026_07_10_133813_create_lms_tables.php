<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Tabel lms_kursus
        Schema::create('lms_kursus', function (Blueprint $table) {
            $table->integer('id_kursus')->autoIncrement()->primary();
            $table->string('nama_kursus', 100);
            $table->integer('id_kelas');
            $table->integer('id_guru');
            $table->timestamps();

            $table->index(['id_kelas']);
            $table->index(['id_guru']);
        });

        // 2. Tabel lms_tugas
        Schema::create('lms_tugas', function (Blueprint $table) {
            $table->integer('id_tugas')->autoIncrement()->primary();
            $table->integer('id_kursus');
            $table->string('judul', 150);
            $table->text('deskripsi');
            $table->dateTime('tenggat')->nullable();
            $table->timestamps();

            $table->index(['id_kursus']);
        });

        // 3. Tabel lms_pengumpulan
        Schema::create('lms_pengumpulan', function (Blueprint $table) {
            $table->integer('id_pengumpulan')->autoIncrement()->primary();
            $table->integer('id_tugas');
            $table->integer('nis');
            $table->string('file_path', 255)->nullable();
            $table->text('catatan')->nullable();
            $table->integer('nilai')->nullable();
            $table->enum('status', ['belum', 'diserahkan', 'dinilai'])->default('belum');
            $table->timestamps();

            $table->index(['id_tugas']);
            $table->index(['nis']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lms_pengumpulan');
        Schema::dropIfExists('lms_tugas');
        Schema::dropIfExists('lms_kursus');
    }
};
