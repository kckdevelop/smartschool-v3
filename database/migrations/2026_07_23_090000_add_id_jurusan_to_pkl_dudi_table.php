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
        Schema::table('pkl_dudi', function (Blueprint $table) {
            $table->integer('id_jurusan')->nullable()->after('id_dudi');
            $table->foreign('id_jurusan')->references('id_jurusan')->on('jurusan')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pkl_dudi', function (Blueprint $table) {
            $table->dropForeign(['id_jurusan']);
            $table->dropColumn('id_jurusan');
        });
    }
};
