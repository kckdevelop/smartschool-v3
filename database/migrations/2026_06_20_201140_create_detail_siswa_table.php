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
        Schema::create('detail_siswa', function (Blueprint $table) {
            $table->integer('nis')->primary();
            $table->text('alamat')->nullable();
            $table->string('agama', 30)->nullable();
            $table->string('golongan_darah', 5)->nullable();
            $table->string('nama_ayah', 100)->nullable();
            $table->string('pekerjaan_ayah', 100)->nullable();
            $table->string('no_telp_ayah', 20)->nullable();
            $table->string('nama_ibu', 100)->nullable();
            $table->string('pekerjaan_ibu', 100)->nullable();
            $table->string('no_telp_ibu', 20)->nullable();
            $table->string('nama_wali', 100)->nullable();
            $table->string('pekerjaan_wali', 100)->nullable();
            $table->string('no_telp_wali', 20)->nullable();
            $table->timestamps();
            
            $table->foreign('nis')->references('nis')->on('user_siswa')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('detail_siswa');
    }
};
