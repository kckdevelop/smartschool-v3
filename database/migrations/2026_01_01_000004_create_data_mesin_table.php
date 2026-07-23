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
        Schema::create('data_mesin', function (Blueprint $table) {
            $table->integer('id_mesin')->autoIncrement()->primary();
            $table->string('nama_mesin', 50);
            $table->string('sn', 30);
            $table->string('password', 50);
            $table->integer('data')->nullable();
            $table->dateTime('last_update')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('data_mesin');
    }
};
