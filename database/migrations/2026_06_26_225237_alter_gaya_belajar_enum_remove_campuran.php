<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Delete all existing 'campuran' records
        DB::table('gaya_belajar')->where('gaya_belajar', 'campuran')->delete();

        // 2. Alter the enum to remove 'campuran'
        DB::statement("ALTER TABLE gaya_belajar MODIFY COLUMN gaya_belajar ENUM('visual','auditori','kinestetik') NOT NULL DEFAULT 'visual'");
    }

    public function down(): void
    {
        // Restore enum with 'campuran' option
        DB::statement("ALTER TABLE gaya_belajar MODIFY COLUMN gaya_belajar ENUM('visual','auditori','kinestetik','campuran') NOT NULL DEFAULT 'campuran'");
    }
};
