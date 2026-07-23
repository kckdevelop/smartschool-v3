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
        Schema::create('jadwal_siklus', function (Blueprint $table) {
            $table->integer('id_jadwal')->autoIncrement()->primary();
            $table->date('tanggal')->unique();
            $table->string('hari_ke', 10); // 'D1' s.d 'D5', 'D8' s.d 'D12', atau 'Off'
            $table->integer('siklus')->default(0); // 1 untuk Siklus 1, 2 untuk Siklus 2, 0 untuk Off
            $table->string('status', 20)->default('KBM'); // 'KBM' atau 'Libur'
            $table->string('keterangan', 255)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jadwal_siklus');
    }
};
