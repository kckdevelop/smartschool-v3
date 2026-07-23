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
        Schema::table('gaya_belajar', function (Blueprint $table) {
            $table->unsignedTinyInteger('skor_visual')->nullable()->after('gaya_belajar');
            $table->unsignedTinyInteger('skor_auditori')->nullable()->after('skor_visual');
            $table->unsignedTinyInteger('skor_kinestetik')->nullable()->after('skor_auditori');
            // Buat id_guru nullable agar self-assessment bisa disimpan tanpa guru
            $table->integer('id_guru')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('gaya_belajar', function (Blueprint $table) {
            $table->dropColumn(['skor_visual', 'skor_auditori', 'skor_kinestetik']);
            $table->integer('id_guru')->nullable(false)->change();
        });
    }
};
