<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('guru', function (Blueprint $table) {
            $table->string('foto')->nullable()->after('status');
        });

        Schema::table('karyawan', function (Blueprint $table) {
            $table->string('foto')->nullable()->after('status');
        });
    }

    public function down(): void
    {
        Schema::table('guru', function (Blueprint $table) {
            $table->dropColumn('foto');
        });

        Schema::table('karyawan', function (Blueprint $table) {
            $table->dropColumn('foto');
        });
    }
};
