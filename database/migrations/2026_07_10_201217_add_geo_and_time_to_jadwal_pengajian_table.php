<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('jadwal_pengajian', function (Blueprint $table) {
            $table->decimal('latitude', 10, 8)->nullable()->after('lokasi_gmaps');
            $table->decimal('longitude', 11, 8)->nullable()->after('latitude');
            $table->integer('radius_meter')->default(100)->after('longitude');
            $table->time('jam_mulai')->nullable()->after('tanggal');
            $table->time('jam_selesai')->nullable()->after('jam_mulai');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('jadwal_pengajian', function (Blueprint $table) {
            $table->dropColumn(['latitude', 'longitude', 'radius_meter', 'jam_mulai', 'jam_selesai']);
        });
    }
};
