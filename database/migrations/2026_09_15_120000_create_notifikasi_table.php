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
        Schema::create('notifikasi', function (Blueprint $table) {
            $table->bigIncrements('id_notifikasi');
            $table->unsignedInteger('id_guru')->nullable()->index();
            $table->unsignedBigInteger('id_user')->nullable()->index();
            $table->string('role', 50)->default('guru')->index();
            $table->string('judul', 255);
            $table->text('pesan');
            $table->string('tipe', 50)->default('jurnal_approved')->index();
            $table->unsignedBigInteger('referensi_id')->nullable()->index();
            $table->json('data')->nullable();
            $table->boolean('is_read')->default(false)->index();
            $table->timestamp('read_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notifikasi');
    }
};
