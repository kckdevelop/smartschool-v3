<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('panggil_ortu', function (Blueprint $table) {
            $table->string('no_surat', 100)->nullable()->unique()->after('id_panggil');
            $table->enum('jenis_panggilan', ['panggilan_biasa', 'sp_1', 'sp_2', 'sp_3'])->default('panggilan_biasa')->after('no_hp_ortu');
            $table->time('waktu_pertemuan')->nullable()->after('tanggal_panggil');
            $table->string('lokasi_pertemuan', 255)->default('Ruang Bimbingan Konseling (BK)')->after('waktu_pertemuan');
        });
    }

    public function down(): void
    {
        Schema::table('panggil_ortu', function (Blueprint $table) {
            $table->dropColumn(['no_surat', 'jenis_panggilan', 'waktu_pertemuan', 'lokasi_pertemuan']);
        });
    }
};
