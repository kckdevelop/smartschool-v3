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
        Schema::create('riwayat_generate_kisikisi', function (Blueprint $table) {
            $table->integer('id_kisikisi')->autoIncrement()->primary();
            $table->integer('id_guru')->nullable();
            $table->integer('id_mapel')->nullable();
            $table->integer('id_kelas')->nullable();
            $table->integer('semester')->default(1);
            $table->string('jenis_penilaian', 100)->default('Harian');
            $table->string('tahun_pelajaran', 50)->default('2026/2027');
            $table->string('kurikulum', 50)->default('Merdeka');
            $table->integer('alokasi_waktu')->default(90);
            $table->longText('hasil_json');
            $table->timestamps();

            // Foreign Key Constraints
            $table->foreign('id_guru')->references('id_guru')->on('guru')->onDelete('set null');
            $table->foreign('id_mapel')->references('id_mapel')->on('mapel')->onDelete('set null');
            $table->foreign('id_kelas')->references('id_kelas')->on('kelas')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('riwayat_generate_kisikisi');
    }
};
