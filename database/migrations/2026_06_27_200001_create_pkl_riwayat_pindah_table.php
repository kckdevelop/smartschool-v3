<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pkl_riwayat_pindah', function (Blueprint $table) {
            $table->id();
            $table->string('nis', 20);                          // Siswa yang pindah
            $table->integer('id_gelombang');                    // Gelombang PKL
            $table->integer('id_penempatan_lama');              // Penempatan asal (status jadi 'pindah')
            $table->integer('id_penempatan_baru');              // Penempatan tujuan (status 'aktif')
            $table->date('tanggal_pindah');                     // Tanggal mulai di tempat baru
            $table->text('alasan')->nullable();                 // Alasan perpindahan
            $table->integer('dicatat_oleh')->nullable();        // ID user yang mencatat
            $table->timestamps();

            // Index untuk query cepat
            $table->index('nis');
            $table->index('id_gelombang');
            $table->index('tanggal_pindah');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pkl_riwayat_pindah');
    }
};
