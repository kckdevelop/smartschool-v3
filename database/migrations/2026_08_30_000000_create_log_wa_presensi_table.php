<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (!Schema::hasColumn('sekolah', 'wa_template_presensi')) {
            Schema::table('sekolah', function (Blueprint $table) {
                $table->text('wa_template_presensi')->nullable()->after('wa_status');
            });

            // Set default template
            $defaultTemplate = "Assalamu'alaikum Wr. Wb.\n\nYth. Orang Tua / Wali dari *{nama_siswa}* (NIS: {nis}), Kelas {kelas}.\n\nMemberitahukan informasi presensi putra/putri Anda pada hari {tanggal}:\nStatus: *{status}*\nJam Presensi: *{jam_presensi}*\nKeterangan: {keterangan}\n\nDemikian informasi ini kami sampaikan. Terima kasih.\n\nWassalamu'alaikum Wr. Wb.\n*{nama_sekolah}*";

            DB::table('sekolah')->update(['wa_template_presensi' => $defaultTemplate]);
        }

        if (!Schema::hasTable('log_wa_presensi')) {
            Schema::create('log_wa_presensi', function (Blueprint $table) {
                $table->id();
                $table->date('tanggal');
                $table->bigInteger('nis');
                $table->string('no_wa', 30)->nullable();
                $table->string('status_presensi', 50)->nullable();
                $table->string('jam_presensi', 20)->nullable();
                $table->text('pesan')->nullable();
                $table->enum('status_wa', ['pending', 'terkirim', 'gagal', 'dilompati'])->default('pending');
                $table->text('response')->nullable();
                $table->timestamp('sent_at')->nullable();
                $table->timestamps();

                $table->index(['tanggal', 'nis']);
                $table->index('status_wa');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('log_wa_presensi');

        if (Schema::hasColumn('sekolah', 'wa_template_presensi')) {
            Schema::table('sekolah', function (Blueprint $table) {
                $table->dropColumn('wa_template_presensi');
            });
        }
    }
};
