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
        Schema::create('tadarus', function (Blueprint $table) {
            $table->integer('id_tadarus')->autoIncrement()->primary();
            $table->date('tanggal');
            $table->integer('id_kelas');
            $table->string('awal_surat', 50);
            $table->integer('awal_ayat');
            $table->string('akhir_surat', 50);
            $table->integer('akhir_ayat');
            $table->integer('id_guru');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tadarus');
    }
};
