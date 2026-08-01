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
        Schema::table('tabel_iqro', function (Blueprint $table) {
            $table->integer('baris')->default(1)->after('halaman');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tabel_iqro', function (Blueprint $table) {
            $table->dropColumn('baris');
        });
    }
};
