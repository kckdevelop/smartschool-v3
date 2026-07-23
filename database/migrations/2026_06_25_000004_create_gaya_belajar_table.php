<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('gaya_belajar', function (Blueprint $table) {
            $table->integer('id_gaya_belajar')->autoIncrement()->primary();
            $table->string('nis', 20);
            $table->enum('gaya_belajar', ['visual', 'auditori', 'kinestetik', 'campuran'])->default('campuran');
            $table->string('minat', 255)->nullable();
            $table->text('catatan')->nullable();
            $table->integer('id_guru');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('gaya_belajar');
    }
};
