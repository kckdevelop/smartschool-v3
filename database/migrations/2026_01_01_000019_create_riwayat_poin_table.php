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
        Schema::create('riwayat_poin', function (Blueprint $table) {
            $table->integer('id_poin')->autoIncrement()->primary();
            $table->date('tgl_input');
            $table->string('nis', 10);
            $table->integer('tingkat');
            $table->string('pelanggaran', 100);
            $table->integer('poin');
            $table->integer('id_guru');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('riwayat_poin');
    }
};
