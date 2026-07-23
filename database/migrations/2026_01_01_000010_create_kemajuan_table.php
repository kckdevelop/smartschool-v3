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
        Schema::create('kemajuan', function (Blueprint $table) {
            $table->integer('id_kemajuan')->autoIncrement()->primary();
            $table->date('tanggal');
            $table->string('jam_ke', 10);
            $table->integer('id_mapel');
            $table->integer('id_guru');
            $table->text('materi');
            $table->integer('id_kelas');
            $table->integer('jml_siswa');
            $table->text('absen')->nullable();
            $table->text('keterangan')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kemajuan');
    }
};
