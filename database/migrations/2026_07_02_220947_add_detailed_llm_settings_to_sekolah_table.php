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
            $table->text('openai_key')->nullable();
            $table->string('openai_status', 20)->default('nonaktif');
            $table->string('openai_model', 100)->default('gpt-4o-mini');
            $table->integer('openai_quota')->default(100);

            $table->text('gemini_key')->nullable();
            $table->string('gemini_status', 20)->default('nonaktif');
            $table->string('gemini_model', 100)->default('gemini-1.5-flash');
            $table->integer('gemini_quota')->default(100);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sekolah', function (Blueprint $table) {
            $table->dropColumn([
                'openai_key',
                'openai_status',
                'openai_model',
                'openai_quota',
                'gemini_key',
                'gemini_status',
                'gemini_model',
                'gemini_quota'
            ]);
        });
    }
};
