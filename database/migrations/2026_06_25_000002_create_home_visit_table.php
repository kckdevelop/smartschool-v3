<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('home_visit', function (Blueprint $table) {
            $table->integer('id_home_visit')->autoIncrement()->primary();
            $table->date('tanggal_visit');
            $table->string('nis', 20);
            $table->string('alamat', 255)->nullable();
            $table->text('tujuan_kunjungan');
            $table->text('hasil_kunjungan')->nullable();
            $table->text('tindak_lanjut')->nullable();
            $table->enum('status', ['dijadwalkan', 'selesai', 'batal'])->default('dijadwalkan');
            $table->integer('id_guru');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('home_visit');
    }
};
