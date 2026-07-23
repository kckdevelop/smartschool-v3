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
        Schema::create('btaq', function (Blueprint $table) {
            $table->integer('id_btaq')->autoIncrement()->primary();
            $table->date('tanggal');
            $table->integer('nis');
            $table->integer('id_kelas')->default(13);
            $table->string('level', 15);
            $table->string('awal', 100);
            $table->string('akhir', 100);
            $table->integer('id_guru');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('btaq');
    }
};
