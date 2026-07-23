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
        Schema::table('user_siswa', function (Blueprint $table) {
            $table->string('nisn', 20)->nullable()->after('nis');
            $table->string('nik', 20)->nullable()->after('nisn');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('user_siswa', function (Blueprint $table) {
            $table->dropColumn(['nisn', 'nik']);
        });
    }
};
