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
        Schema::create('data_checkup_gukar', function (Blueprint $table) {
            $table->integer('id_checkup')->autoIncrement()->primary();
            $table->integer('id_guru')->nullable();
            $table->integer('id_karyawan')->nullable();
            $table->date('tanggal');
            $table->time('jam')->nullable();
            $table->float('tinggi_badan')->nullable();
            $table->float('berat_badan')->nullable();
            $table->float('imt')->nullable();
            $table->string('kategori', 50)->nullable();
            $table->string('tekanan_darah', 50)->nullable();
            $table->float('kolesterol')->nullable();
            $table->float('gula_darah')->nullable();
            $table->float('asam_urat')->nullable();

            $table->foreign('id_guru')->references('id_guru')->on('guru')->onDelete('cascade');
            $table->foreign('id_karyawan')->references('id_karyawan')->on('karyawan')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('data_checkup_gukar');
    }
};
