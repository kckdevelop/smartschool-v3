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
        Schema::table('log_absensi', function (Blueprint $table) {
            // Expand keterangan from varchar(15) to varchar(50) to hold sync status labels
            $table->string('keterangan', 50)->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('log_absensi', function (Blueprint $table) {
            $table->string('keterangan', 15)->nullable()->change();
        });
    }
};
