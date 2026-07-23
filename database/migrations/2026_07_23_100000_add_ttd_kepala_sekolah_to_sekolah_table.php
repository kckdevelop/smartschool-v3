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
        Schema::table('sekolah', function (Blueprint $table) {
            if (!Schema::hasColumn('sekolah', 'ttd_kepala_sekolah')) {
                $table->string('ttd_kepala_sekolah', 255)->nullable()->after('kop');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sekolah', function (Blueprint $table) {
            if (Schema::hasColumn('sekolah', 'ttd_kepala_sekolah')) {
                $table->dropColumn('ttd_kepala_sekolah');
            }
        });
    }
};
