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
        Schema::create('kunjungan_uks_gukar', function (Blueprint $table) {
            $table->integer('id_kunjungan')->autoIncrement()->primary();
            $table->integer('id_guru')->nullable();
            $table->integer('id_karyawan')->nullable();
            $table->date('tanggal');
            $table->time('jam');
            $table->text('keluhan');
            $table->string('diagnosa', 100);
            $table->string('tindakan', 100);
        });

        Schema::create('riwayat_obat_gukar', function (Blueprint $table) {
            $table->integer('id_riwayat')->autoIncrement()->primary();
            $table->integer('id_kunjungan');
            $table->string('nama_obat', 50);
            $table->string('dosis', 15);
            $table->integer('jumlah');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('riwayat_obat_gukar');
        Schema::dropIfExists('kunjungan_uks_gukar');
    }
};
