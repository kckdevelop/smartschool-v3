<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        try {
            DB::statement("ALTER TABLE `user_smartschool` MODIFY COLUMN `level` VARCHAR(50) NOT NULL DEFAULT 'admin_kurikulum'");
        } catch (\Throwable $e) {
            // Silence if already updated or DB doesn't require statement
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // No reversal needed
    }
};
