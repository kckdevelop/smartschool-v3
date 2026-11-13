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
            if (!Schema::hasColumn('sekolah', 'custom_key')) {
                $table->text('custom_key')->nullable()->after('gemini_quota');
            }
            if (!Schema::hasColumn('sekolah', 'custom_url')) {
                $table->string('custom_url', 255)->default('https://eyay.afdaan.web.id/v1')->after('custom_key');
            }
            if (!Schema::hasColumn('sekolah', 'custom_status')) {
                $table->string('custom_status', 20)->default('aktif')->after('custom_url');
            }
            if (!Schema::hasColumn('sekolah', 'custom_model')) {
                $table->string('custom_model', 100)->default('Assistant-smart')->after('custom_status');
            }
            if (!Schema::hasColumn('sekolah', 'custom_quota')) {
                $table->integer('custom_quota')->default(9999)->after('custom_model');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sekolah', function (Blueprint $table) {
            //
        });
    }
};
