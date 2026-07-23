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
        Schema::create('guru', function (Blueprint $table) {
            $table->integer('id_guru')->autoIncrement()->primary();
            $table->integer('no_id');
            $table->string('nama_guru', 50);
            $table->enum('guru_bk', ['ya', 'tidak'])->default('tidak');
            $table->enum('status', ['aktif', 'tidak'])->default('aktif');
            $table->string('password', 100);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('guru');
    }
};
