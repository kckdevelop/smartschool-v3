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
        Schema::create('semester', function (Blueprint $table) {
            $table->integer('id_semester')->autoIncrement()->primary();
            $table->integer('id_tahun');
            $table->enum('semester', ['Genap', 'Ganjil']);
            $table->date('awal');
            $table->date('akhir');
            $table->enum('status', ['aktif', 'tidak'])->default('tidak');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('semester');
    }
};
