<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('kehadiran_pengajian', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_jadwal');
            $table->integer('id_guru');
            $table->enum('status', ['hadir', 'ijin', 'alpha'])->default('alpha');
            $table->time('jam_absen')->nullable();
            $table->string('foto', 255)->nullable();
            $table->text('lokasi_gmaps')->nullable();
            $table->text('keterangan')->nullable();
            $table->timestamps();

            $table->foreign('id_jadwal')->references('id_jadwal')->on('jadwal_pengajian')->onDelete('cascade');
            $table->foreign('id_guru')->references('id_guru')->on('guru')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kehadiran_pengajian');
    }
};
