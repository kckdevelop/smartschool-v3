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
        Schema::create('riwayat_generate_soal', function (Blueprint $table) {
            $table->integer('id_riwayat')->autoIncrement()->primary();
            $table->integer('id_guru')->nullable();
            $table->integer('id_mapel')->nullable();
            $table->integer('id_kelas')->nullable();
            $table->string('topik', 255)->nullable();
            $table->integer('jumlah_soal')->default(5);
            $table->string('tipe_soal', 50)->default('pilihan_ganda');
            $table->string('kesulitan', 50)->default('sedang');
            $table->longText('hasil_json');
            $table->timestamps();

            // Foreign Key Constraints (pointing to their respective tables/keys)
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
        Schema::dropIfExists('riwayat_generate_soal');
    }
};
