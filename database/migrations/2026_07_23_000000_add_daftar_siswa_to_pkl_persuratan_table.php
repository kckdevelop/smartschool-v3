<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasColumn('pkl_persuratan', 'daftar_siswa')) {
            Schema::table('pkl_persuratan', function (Blueprint $table) {
                $table->json('daftar_siswa')->nullable()->after('hal');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('pkl_persuratan', 'daftar_siswa')) {
            Schema::table('pkl_persuratan', function (Blueprint $table) {
                $table->dropColumn('daftar_siswa');
            });
        }
    }
};
