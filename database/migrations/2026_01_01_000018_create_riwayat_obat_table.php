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
        Schema::create('riwayat_obat', function (Blueprint $table) {
            $table->integer('id_riwayat')->autoIncrement()->primary();
            $table->integer('id_kunjungan');
            $table->string('nama_obat', 50);
            $table->string('dosis', 15);
            $table->integer('jumlah');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('riwayat_obat');
    }
};
