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
        Schema::create('tugas', function (Blueprint $table) {
            $table->integer('id_tugas')->autoIncrement()->primary();
            $table->date('tanggal');
            $table->integer('id_guru');
            $table->string('judul_tugas', 50);
            $table->integer('id_kelas');
            $table->text('deskripsi');
            $table->text('lampiran')->nullable();
            $table->enum('status', ['aktif', 'tidak']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tugas');
    }
};
