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
        // 1. Table: jadwal_mengajar_template (Cycle schedule)
        Schema::create('jadwal_mengajar_template', function (Blueprint $table) {
            $table->integer('id_template')->autoIncrement()->primary();
            $table->integer('id_guru');
            $table->integer('id_kelas');
            $table->integer('id_mapel');
            $table->string('hari_siklus', 10); // 'D1' s.d 'D12'
            $table->integer('jam_ke');
            $table->timestamps();

            // Foreign Key or references could be added, but keeping consistent with project where migrations don't enforce strict FKs.
            $table->index(['id_guru']);
            $table->index(['id_kelas']);
            $table->index(['hari_siklus']);
        });

        // 2. Table: jadwal_mengajar_harian (Real generated calendar schedules)
        Schema::create('jadwal_mengajar_harian', function (Blueprint $table) {
            $table->integer('id_jadwal_harian')->autoIncrement()->primary();
            $table->date('tanggal');
            $table->integer('id_guru');
            $table->integer('id_kelas');
            $table->integer('id_mapel');
            $table->integer('jam_ke');
            $table->string('status', 20)->default('KBM'); // 'KBM', 'Libur', 'Kosong'
            $table->string('keterangan', 255)->nullable();
            $table->timestamps();

            $table->index(['tanggal']);
            $table->index(['id_guru']);
            $table->index(['id_kelas']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jadwal_mengajar_harian');
        Schema::dropIfExists('jadwal_mengajar_template');
    }
};
