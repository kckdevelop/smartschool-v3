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
        Schema::create('riwayat_reward', function (Blueprint $table) {
            $table->integer('id_reward')->autoIncrement()->primary();
            $table->date('tgl_input');
            $table->integer('nis');
            $table->integer('tingkat');
            $table->string('reward', 100);
            $table->integer('point_reward');
            $table->integer('id_guru');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('riwayat_reward');
    }
};
