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
        Schema::create('data_checkup', function (Blueprint $table) {
            $table->integer('id_checkup')->autoIncrement()->primary();
            $table->date('tanggal');
            $table->time('jam');
            $table->integer('nis');
            $table->string('jenis_checkup', 50);
            $table->integer('nilai');
            $table->string('satuan', 30)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('data_checkup');
    }
};
