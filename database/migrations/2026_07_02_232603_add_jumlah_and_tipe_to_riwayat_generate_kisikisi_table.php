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
        Schema::table('riwayat_generate_kisikisi', function (Blueprint $table) {
            $table->integer('jumlah_soal')->default(20)->after('alokasi_waktu');
            $table->string('tipe_soal', 50)->default('pilihan_ganda')->after('jumlah_soal');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('riwayat_generate_kisikisi', function (Blueprint $table) {
            $table->dropColumn(['jumlah_soal', 'tipe_soal']);
        });
    }
};
