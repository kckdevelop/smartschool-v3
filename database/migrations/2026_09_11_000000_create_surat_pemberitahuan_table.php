<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('surat_pemberitahuan', function (Blueprint $table) {
            $table->integer('id_pemberitahuan')->autoIncrement()->primary();
            $table->string('no_surat', 100)->nullable()->unique();
            $table->date('tanggal_surat');
            $table->string('nis', 20);
            $table->string('nama_ortu', 100)->nullable();
            $table->string('no_hp_ortu', 20)->nullable();
            $table->text('alasan_pemberitahuan');
            $table->text('tindakan_sekolah')->nullable();
            $table->enum('status', ['diterbitkan', 'disampaikan', 'lanjut_sp1', 'selesai'])->default('diterbitkan');
            $table->integer('id_panggil_sp1')->nullable();
            $table->integer('id_guru');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('surat_pemberitahuan');
    }
};
