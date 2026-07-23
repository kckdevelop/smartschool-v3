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
            $table->renameColumn('openai_key', 'groq_key');
            $table->renameColumn('openai_status', 'groq_status');
            $table->renameColumn('openai_model', 'groq_model');
            $table->renameColumn('openai_quota', 'groq_quota');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sekolah', function (Blueprint $table) {
            $table->renameColumn('groq_key', 'openai_key');
            $table->renameColumn('groq_status', 'openai_status');
            $table->renameColumn('groq_model', 'openai_model');
            $table->renameColumn('groq_quota', 'openai_quota');
        });
    }
};
