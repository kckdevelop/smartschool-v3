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
        Schema::create('jenis_checkup', function (Blueprint $table) {
            $table->integer('id_checkup')->autoIncrement()->primary();
            $table->string('jenis_checkup', 50);
            $table->enum('status', ['aktif', 'tidak'])->default('aktif')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jenis_checkup');
    }
};
