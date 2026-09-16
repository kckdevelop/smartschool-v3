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
        Schema::create('tagihan_pembayaran', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_sekolah')->nullable();
            $table->string('nis', 30);
            $table->unsignedBigInteger('id_kelas')->nullable();
            $table->unsignedBigInteger('id_tahun')->nullable();
            $table->unsignedBigInteger('id_semester')->nullable();
            $table->string('kode_tagihan', 10); // 00, 01, 02
            $table->enum('jenis_tagihan', ['spp', 'non_spp', 'tunggakan'])->default('spp');
            $table->string('nama_tagihan', 255);
            $table->string('nomor_va', 50)->index();
            $table->decimal('nominal', 15, 2)->default(0);
            $table->decimal('nominal_terbayar', 15, 2)->default(0);
            $table->enum('status', ['belum_bayar', 'lunas', 'batal'])->default('belum_bayar');
            $table->date('tanggal_tagihan');
            $table->date('tanggal_jatuh_tempo')->nullable();
            $table->dateTime('tanggal_bayar')->nullable();
            $table->text('keterangan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tagihan_pembayaran');
    }
};
