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
        // Tambah kecamatan & kabupaten ke tabel guru
        Schema::table('guru', function (Blueprint $table) {
            $table->string('kecamatan', 100)->nullable()->after('no_hp');
            $table->string('kabupaten', 100)->nullable()->after('kecamatan');
        });

        // Tambah kecamatan & kabupaten ke tabel pkl_dudi
        Schema::table('pkl_dudi', function (Blueprint $table) {
            $table->string('kecamatan', 100)->nullable()->after('kota');
            $table->string('kabupaten', 100)->nullable()->after('kecamatan');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('guru', function (Blueprint $table) {
            $table->dropColumn(['kecamatan', 'kabupaten']);
        });

        Schema::table('pkl_dudi', function (Blueprint $table) {
            $table->dropColumn(['kecamatan', 'kabupaten']);
        });
    }
};
