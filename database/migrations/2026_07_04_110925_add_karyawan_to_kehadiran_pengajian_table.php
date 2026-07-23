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
        Schema::table('kehadiran_pengajian', function (Blueprint $table) {
            $table->integer('id_guru')->nullable()->change();
            $table->integer('id_karyawan')->nullable()->after('id_guru');
            $table->foreign('id_karyawan')->references('id_karyawan')->on('karyawan')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::table('kehadiran_pengajian', function (Blueprint $table) {
            $table->dropForeign(['id_karyawan']);
            $table->dropColumn('id_karyawan');
            $table->integer('id_guru')->nullable(false)->change();
        });
    }
};
