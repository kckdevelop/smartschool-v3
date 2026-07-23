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
        Schema::create('sekolah', function (Blueprint $table) {
            $table->integer('id_sekolah')->autoIncrement()->primary();
            $table->integer('npsn')->nullable();
            $table->string('nama_sekolah', 100)->nullable();
            $table->string('kepala_sekolah', 100)->nullable();
            $table->string('nip', 15)->default('-');
            $table->enum('status', ['negeri', 'swasta'])->nullable();
            $table->text('alamat_sekolah')->nullable();
            $table->string('logo', 200)->nullable();
            $table->string('kop', 255)->nullable();
            $table->enum('ijin', ['ya', 'tidak'])->default('tidak');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sekolah');
    }
};
