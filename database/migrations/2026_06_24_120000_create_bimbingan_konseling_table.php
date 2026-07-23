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
        Schema::create('bimbingan_konseling', function (Blueprint $table) {
            $table->integer('id_bk')->autoIncrement()->primary();
            $table->date('tanggal');
            $table->integer('nis');
            $table->string('jenis_masalah', 100);
            $table->text('uraian');
            $table->text('tindak_lanjut')->nullable();
            $table->enum('status', ['proses', 'selesai'])->default('proses');
            $table->integer('id_guru');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bimbingan_konseling');
    }
};
