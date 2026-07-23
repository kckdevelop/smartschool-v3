<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('buku_kasus', function (Blueprint $table) {
            $table->integer('id_kasus')->autoIncrement()->primary();
            $table->date('tanggal');
            $table->string('nis', 20);
            $table->string('judul_kasus', 150);
            $table->text('uraian_kasus');
            $table->text('tindak_lanjut')->nullable();
            $table->enum('status', ['proses', 'selesai'])->default('proses');
            $table->integer('id_guru');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('buku_kasus');
    }
};
