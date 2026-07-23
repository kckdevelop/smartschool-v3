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
        Schema::table('guru', function (Blueprint $table) {
            $table->enum('jenkel', ['L', 'P'])->default('L')->after('nama_guru');
        });

        Schema::table('karyawan', function (Blueprint $table) {
            $table->enum('jenkel', ['L', 'P'])->default('L')->after('nama_karyawan');
        });

        Schema::table('data_checkup_gukar', function (Blueprint $table) {
            $table->enum('tipe_gula_darah', ['sewaktu', 'puasa'])->default('sewaktu')->after('gula_darah');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('data_checkup_gukar', function (Blueprint $table) {
            $table->dropColumn('tipe_gula_darah');
        });

        Schema::table('karyawan', function (Blueprint $table) {
            $table->dropColumn('jenkel');
        });

        Schema::table('guru', function (Blueprint $table) {
            $table->dropColumn('jenkel');
        });
    }
};
