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
        Schema::create('lms_soal', function (Blueprint $table) {
            $table->integer('id_soal')->autoIncrement()->primary();
            $table->integer('id_tugas');
            $table->integer('nomor_soal')->default(1);
            $table->enum('jenis_soal', ['pilihan_ganda', 'benar_salah', 'pilihan_ganda_komplek'])->default('pilihan_ganda');
            $table->text('pertanyaan');
            $table->string('gambar', 255)->nullable();
            $table->text('kunci_jawaban')->nullable();
            $table->timestamps();

            $table->index(['id_tugas']);
        });

        Schema::create('lms_soal_pilihan', function (Blueprint $table) {
            $table->integer('id_pilihan')->autoIncrement()->primary();
            $table->integer('id_soal');
            $table->string('kunci', 20); // A, B, C, D, E, Benar, Salah
            $table->text('teks')->nullable();
            $table->string('gambar', 255)->nullable();
            $table->boolean('is_kunci')->default(false);
            $table->timestamps();

            $table->index(['id_soal']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lms_soal_pilihan');
        Schema::dropIfExists('lms_soal');
    }
};
