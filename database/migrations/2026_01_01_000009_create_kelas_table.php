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
        Schema::create('kelas', function (Blueprint $table) {
            $table->integer('id_kelas')->autoIncrement()->primary();
            $table->string('tahun_masuk', 20);
            $table->integer('tingkat');
            $table->integer('id_jurusan');
            $table->string('rombel', 30);
            $table->integer('walikelas')->nullable();
            $table->enum('status', ['aktif', 'tidak']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kelas');
    }
};
