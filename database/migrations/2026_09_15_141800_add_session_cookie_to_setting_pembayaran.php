<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('setting_pembayaran', function (Blueprint $table) {
            // Menyimpan session cookie BPD DIY dari browser user
            $table->text('session_cookie')->nullable()->after('status_api');
            $table->timestamp('cookie_updated_at')->nullable()->after('session_cookie');
        });
    }

    public function down(): void
    {
        Schema::table('setting_pembayaran', function (Blueprint $table) {
            $table->dropColumn(['session_cookie', 'cookie_updated_at']);
        });
    }
};
