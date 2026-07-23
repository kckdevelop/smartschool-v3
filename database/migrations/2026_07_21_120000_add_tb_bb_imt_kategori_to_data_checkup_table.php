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
        Schema::table('data_checkup', function (Blueprint $table) {
            $table->time('jam')->nullable()->change();
            $table->string('jenis_checkup', 100)->nullable()->change();
            $table->integer('nilai')->nullable()->change();
            $table->string('satuan', 30)->nullable()->change();

            $table->float('tinggi_badan')->nullable()->after('nis');
            $table->float('berat_badan')->nullable()->after('tinggi_badan');
            $table->float('imt')->nullable()->after('berat_badan');
            $table->string('kategori', 50)->nullable()->after('imt');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('data_checkup', function (Blueprint $table) {
            $table->dropColumn(['tinggi_badan', 'berat_badan', 'imt', 'kategori']);
        });
    }
};
