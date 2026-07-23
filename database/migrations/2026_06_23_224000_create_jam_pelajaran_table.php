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
        Schema::create('jam_pelajaran', function (Blueprint $table) {
            $table->integer('id_jam')->autoIncrement()->primary();
            $table->integer('jam_ke')->unique();
            $table->time('normal_mulai');
            $table->time('normal_selesai');
            $table->time('upacara_mulai');
            $table->time('upacara_selesai');
            $table->time('puasa_mulai');
            $table->time('puasa_selesai');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jam_pelajaran');
    }
};
