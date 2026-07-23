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
        Schema::table('jadwal_mengajar_template', function (Blueprint $table) {
            $table->string('ruang', 50)->nullable()->after('jam_ke');
        });

        Schema::table('jadwal_mengajar_harian', function (Blueprint $table) {
            $table->string('ruang', 50)->nullable()->after('jam_ke');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('jadwal_mengajar_template', function (Blueprint $table) {
            $table->dropColumn('ruang');
        });

        Schema::table('jadwal_mengajar_harian', function (Blueprint $table) {
            $table->dropColumn('ruang');
        });
    }
};
