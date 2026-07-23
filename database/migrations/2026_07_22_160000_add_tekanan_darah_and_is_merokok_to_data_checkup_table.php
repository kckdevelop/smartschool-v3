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
            $table->string('tekanan_darah', 50)->nullable()->after('kategori');
            $table->string('is_merokok', 20)->default('Tidak')->after('tekanan_darah');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('data_checkup', function (Blueprint $table) {
            $table->dropColumn(['tekanan_darah', 'is_merokok']);
        });
    }
};
