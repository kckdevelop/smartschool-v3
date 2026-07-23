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
        Schema::create('user_siswa', function (Blueprint $table) {
            $table->integer('nis')->primary();
            $table->string('password', 100);
            $table->string('password_wali', 100)->default('16d65292e7f1386954439235d665ca8b1d6509e8');
            $table->integer('id_kelas');
            $table->string('nama_siswa', 100);
            $table->enum('jenkel', ['L', 'P']);
            $table->string('tempat_lahir', 30)->nullable();
            $table->date('tgl_lahir')->nullable();
            $table->integer('kelengkapan')->default(0)->nullable();
            $table->enum('status', ['aktif', 'tidak'])->default('aktif');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_siswa');
    }
};
