<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Tabel token akses kuis yang di-generate oleh guru.
     * Satu token dapat digunakan oleh semua siswa dalam satu sesi kuis.
     */
    public function up(): void
    {
        Schema::create('lms_kuis_token', function (Blueprint $table) {
            $table->integer('id_token')->autoIncrement()->primary();
            $table->integer('id_tugas');
            $table->integer('id_guru');
            $table->string('token', 10)->unique(); // kode unik 6-10 karakter alphanumeric
            $table->boolean('is_aktif')->default(true);
            $table->timestamp('expired_at')->nullable(); // null = tidak expire
            $table->timestamps();

            $table->index('id_tugas');
            $table->index('token');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lms_kuis_token');
    }
};
