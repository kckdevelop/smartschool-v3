<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('panggil_ortu', function (Blueprint $table) {
            $table->integer('id_panggil')->autoIncrement()->primary();
            $table->date('tanggal_panggil');
            $table->string('nis', 20);
            $table->string('nama_ortu', 100)->nullable();
            $table->string('no_hp_ortu', 20)->nullable();
            $table->text('alasan_panggil');
            $table->text('hasil_pertemuan')->nullable();
            $table->enum('status', ['belum_hadir', 'sudah_hadir', 'tidak_hadir'])->default('belum_hadir');
            $table->integer('id_guru');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('panggil_ortu');
    }
};
