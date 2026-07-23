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
        Schema::create('user_smartschool', function (Blueprint $table) {
            $table->integer('id_user')->autoIncrement()->primary();
            $table->string('username', 100);
            $table->string('password', 100);
            $table->string('nama_lengkap', 50);
            $table->string('level', 50)->default('admin_kurikulum');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_smartschool');
    }
};
