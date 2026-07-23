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
        Schema::create('log_absensi', function (Blueprint $table) {
            $table->integer('id_presensi')->autoIncrement()->primary();
            $table->integer('nis');
            $table->string('tanggal', 30)->nullable();
            $table->string('jam', 15)->nullable();
            $table->string('status', 25)->nullable();
            $table->string('keterangan', 15)->nullable();
            $table->string('file', 255)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('log_absensi');
    }
};
