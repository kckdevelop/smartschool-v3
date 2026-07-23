<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pantau_ibadah', function (Blueprint $table) {
            $table->integer('id_ibadah')->autoIncrement()->primary();
            $table->date('tanggal');
            $table->integer('nis');
            $table->integer('id_kelas');
            $table->integer('id_guru');
            $table->enum('jenis_ibadah', ['sholat_fardu', 'sholat_jenazah', 'gerakan_wudhu']);
            $table->enum('nilai', ['A', 'B', 'C', 'D'])->default('B');
            $table->text('catatan')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pantau_ibadah');
    }
};
