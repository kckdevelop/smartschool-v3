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
        Schema::table('riwayat_generate_soal', function (Blueprint $table) {
            $table->integer('semester')->default(1);
            $table->text('kompetensi_dasar')->nullable();
            $table->text('indikator')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('riwayat_generate_soal', function (Blueprint $table) {
            $table->dropColumn(['semester', 'kompetensi_dasar', 'indikator']);
        });
    }
};
