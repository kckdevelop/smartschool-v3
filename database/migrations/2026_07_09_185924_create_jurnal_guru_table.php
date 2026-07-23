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
        Schema::create('jurnal-guru', function (Blueprint $table) {
            $table->id();
            $table->integer('id_kelas');
            $table->integer('id_guru');
            $table->date('tanggal');
            $table->string('jam_mulai', 10);
            $table->string('jam_selesai', 10);
            $table->text('materi');
            $table->text('hambatan')->nullable();
            $table->text('pemecahan')->nullable();
            $table->string('status', 20)->default('pending');
            $table->text('catatan_penolakan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jurnal-guru');
    }
};
