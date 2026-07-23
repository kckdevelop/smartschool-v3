<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pelanggaran_kelas', function (Blueprint $table) {
            $table->id('id_pelanggaran_kelas');
            $table->date('tanggal');
            $table->string('nis', 20);
            $table->integer('id_kelas');
            $table->unsignedTinyInteger('jenis_pelanggaran'); // 1–8
            $table->text('keterangan')->nullable();
            $table->integer('id_guru');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pelanggaran_kelas');
    }
};
