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
        Schema::table('detail_siswa', function (Blueprint $table) {
            // Koordinat lokasi rumah siswa (untuk pin di map)
            $table->decimal('latitude', 10, 7)->nullable()->after('no_wa_presensi');
            $table->decimal('longitude', 10, 7)->nullable()->after('latitude');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('detail_siswa', function (Blueprint $table) {
            $table->dropColumn(['latitude', 'longitude']);
        });
    }
};
