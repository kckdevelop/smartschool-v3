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
            if (!Schema::hasColumn('guru', 'kecamatan')) {
                $table->string('kecamatan', 100)->nullable()->after('no_hp');
            }
            if (!Schema::hasColumn('guru', 'kabupaten')) {
                $table->string('kabupaten', 100)->nullable()->after('kecamatan');
            }
        });

        // Tambah kecamatan & kabupaten ke tabel pkl_dudi
        Schema::table('pkl_dudi', function (Blueprint $table) {
            if (!Schema::hasColumn('pkl_dudi', 'kecamatan')) {
                $table->string('kecamatan', 100)->nullable()->after('kota');
            }
            if (!Schema::hasColumn('pkl_dudi', 'kabupaten')) {
                $table->string('kabupaten', 100)->nullable()->after('kecamatan');
            }
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
