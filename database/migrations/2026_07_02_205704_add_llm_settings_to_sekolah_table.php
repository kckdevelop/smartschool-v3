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
            $table->string('llm_provider', 50)->default('gemini');
            $table->text('llm_api_key')->nullable();
            $table->string('llm_model', 100)->default('gemini-1.5-flash');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sekolah', function (Blueprint $table) {
            $table->dropColumn(['llm_provider', 'llm_api_key', 'llm_model']);
        });
    }
};
