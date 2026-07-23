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
        Schema::create('riwayat_kesehatan', function (Blueprint $table) {
            $table->integer('id_riwayat_kesehatan')->autoIncrement()->primary();
            $table->integer('nis');
            $table->date('tanggal');
            $table->integer('tinggi_badan')->nullable();
            $table->integer('berat_badan')->nullable();
            $table->string('golongan_darah', 5)->nullable();
            $table->text('penyakit_bawaan')->nullable();
            $table->text('alergi')->nullable();
            $table->text('riwayat_penyakit')->nullable();
            $table->text('catatan_khusus')->nullable();
            $table->timestamps();

            // Foreign key to user_siswa
            $table->foreign('nis')
                  ->references('nis')
                  ->on('user_siswa')
                  ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('riwayat_kesehatan');
    }
};
