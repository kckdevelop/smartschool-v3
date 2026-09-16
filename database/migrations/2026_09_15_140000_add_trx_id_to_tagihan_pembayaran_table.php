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
        Schema::table('tagihan_pembayaran', function (Blueprint $table) {
            if (!Schema::hasColumn('tagihan_pembayaran', 'trx_id')) {
                $table->string('trx_id', 30)->nullable()->after('id_sekolah')->index();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tagihan_pembayaran', function (Blueprint $table) {
            if (Schema::hasColumn('tagihan_pembayaran', 'trx_id')) {
                $table->dropColumn('trx_id');
            }
        });
    }
};
