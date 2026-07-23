<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasColumn('gaya_belajar', 'minat')) {
            Schema::table('gaya_belajar', function (Blueprint $table) {
                $table->string('minat')->nullable()->after('gaya_belajar')
                    ->comment('Rencana siswa setelah lulus, misal: Kuliah, Bekerja, Wirausaha');
            });
        }
    }

    public function down(): void
    {
        Schema::table('gaya_belajar', function (Blueprint $table) {
            $table->dropColumn('minat');
        });
    }
};
