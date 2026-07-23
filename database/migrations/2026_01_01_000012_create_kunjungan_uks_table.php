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
        Schema::create('kunjungan_uks', function (Blueprint $table) {
            $table->integer('id_kunjungan')->autoIncrement()->primary();
            $table->integer('nis');
            $table->date('tanggal');
            $table->time('jam');
            $table->text('keluhan');
            $table->string('diagnosa', 50);
            $table->string('tindakan', 50);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kunjungan_uks');
    }
};
