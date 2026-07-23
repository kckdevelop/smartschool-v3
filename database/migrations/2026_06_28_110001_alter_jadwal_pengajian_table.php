<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('jadwal_pengajian', function (Blueprint $table) {
            $table->dropColumn(['hadir', 'ijin', 'alpha', 'penerima_jadwal']);
            $table->string('nama_kegiatan', 200)->after('id_jadwal')->comment('Nama/judul kegiatan pengajian');
        });
    }

    public function down(): void
    {
        Schema::table('jadwal_pengajian', function (Blueprint $table) {
            $table->integer('hadir')->default(0);
            $table->integer('ijin')->default(0);
            $table->integer('alpha')->default(0);
            $table->string('penerima_jadwal', 200)->nullable();
            $table->dropColumn('nama_kegiatan');
        });
    }
};
