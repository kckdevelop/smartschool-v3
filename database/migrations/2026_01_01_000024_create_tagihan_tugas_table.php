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
        Schema::create('tagihan_tugas', function (Blueprint $table) {
            $table->integer('id_tagihan')->autoIncrement()->primary();
            $table->integer('id_tugas');
            $table->integer('nis');
            $table->text('deskripsi')->nullable();
            $table->text('upload_tugas')->nullable();
            $table->enum('status_tugas', ['belum', 'sudah', 'cek'])->default('belum');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tagihan_tugas');
    }
};
