<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('jadwal_pengajian', function (Blueprint $table) {
            $table->id('id_jadwal');
            $table->date('tanggal');
            $table->string('tempat', 200);
            $table->string('penerima_jadwal', 200)->comment('Nama penerima/peserta jadwal pengajian');
            $table->text('lokasi_gmaps')->nullable()->comment('Link Google Maps lokasi');
            $table->integer('hadir')->default(0);
            $table->integer('ijin')->default(0);
            $table->integer('alpha')->default(0);
            $table->text('keterangan')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('jadwal_pengajian');
    }
};
