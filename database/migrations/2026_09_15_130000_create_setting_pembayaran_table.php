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
        Schema::create('setting_pembayaran', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_sekolah')->nullable();
            $table->string('id_institusi', 20)->default('1023');
            $table->string('url_report_va', 255)->default('https://va.bpddiy.co.id/admin/report/tagihan_va');
            $table->string('username_maker', 100)->default('USERMAKER');
            $table->string('password_maker', 255)->default('#Musaba888');
            $table->enum('mode_api', ['production', 'sandbox', 'simulasi'])->default('production');
            $table->enum('format_tahun', ['YYYY', 'YY'])->default('YYYY');
            $table->string('kode_spp', 10)->default('00');
            $table->string('kode_non_spp', 10)->default('01');
            $table->string('kode_tunggakan', 10)->default('02');
            $table->enum('status_api', ['aktif', 'nonaktif'])->default('aktif');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('setting_pembayaran');
    }
};
