<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Gelombang PKL
        Schema::create('pkl_gelombang', function (Blueprint $table) {
            $table->integer('id_gelombang')->autoIncrement()->primary();
            $table->string('nama_gelombang', 100);
            $table->string('tahun_ajaran', 20);
            $table->date('tanggal_mulai');
            $table->date('tanggal_selesai');
            $table->enum('status', ['draft', 'aktif', 'selesai'])->default('draft');
            $table->text('keterangan')->nullable();
            $table->timestamps();
        });

        // 2. Kelas yang masuk gelombang PKL
        Schema::create('pkl_kelas_gelombang', function (Blueprint $table) {
            $table->id();
            $table->integer('id_gelombang');
            $table->integer('id_kelas');
            $table->timestamps();
            $table->unique(['id_gelombang', 'id_kelas']);
        });

        // 3. Data DUDI (Dunia Usaha/Dunia Industri)
        Schema::create('pkl_dudi', function (Blueprint $table) {
            $table->integer('id_dudi')->autoIncrement()->primary();
            $table->string('nama_dudi', 200);
            $table->string('bidang_usaha', 100)->nullable();
            $table->text('alamat');
            $table->string('kota', 100)->nullable();
            $table->string('no_telepon', 20)->nullable();
            $table->string('email', 100)->nullable();
            $table->string('nama_pic', 100)->nullable();
            $table->string('jabatan_pic', 100)->nullable();
            $table->string('no_hp_pic', 20)->nullable();
            $table->integer('kuota_siswa')->default(5);
            $table->enum('status', ['aktif', 'nonaktif'])->default('aktif');
            $table->timestamps();
        });

        // 4. Pembimbing PKL (guru yang mendampingi siswa di DUDI)
        Schema::create('pkl_pembimbing', function (Blueprint $table) {
            $table->integer('id_pembimbing')->autoIncrement()->primary();
            $table->integer('id_gelombang');
            $table->integer('id_guru');
            $table->integer('id_dudi');
            $table->timestamps();
        });

        // 5. Data Penempatan siswa
        Schema::create('pkl_penempatan', function (Blueprint $table) {
            $table->integer('id_penempatan')->autoIncrement()->primary();
            $table->integer('id_gelombang');
            $table->integer('id_dudi');
            $table->string('nis', 20);
            $table->integer('id_pembimbing')->nullable();
            $table->date('tanggal_masuk')->nullable();
            $table->date('tanggal_keluar')->nullable();
            $table->enum('status', ['aktif', 'selesai', 'ditarik', 'batal'])->default('aktif');
            $table->text('keterangan')->nullable();
            $table->timestamps();
        });

        // 6. Format & counter nomor surat
        Schema::create('pkl_nomor_surat', function (Blueprint $table) {
            $table->id();
            $table->enum('jenis_surat', ['permohonan', 'penempatan', 'penarikan']);
            $table->string('format_nomor', 150)->default('{NO}/PKL/{BULAN-ROMAWI}/{TAHUN}');
            $table->string('prefix', 50)->nullable();
            $table->integer('counter_terakhir')->default(0);
            $table->string('tahun_reset', 10)->nullable();
            $table->timestamps();
            $table->unique('jenis_surat');
        });

        // 7. Arsip surat yang sudah di-generate
        Schema::create('pkl_persuratan', function (Blueprint $table) {
            $table->integer('id_surat')->autoIncrement()->primary();
            $table->string('nomor_surat', 150);
            $table->enum('jenis_surat', ['permohonan', 'penempatan', 'penarikan']);
            $table->integer('id_gelombang');
            $table->integer('id_dudi');
            $table->date('tanggal_surat');
            $table->string('hal', 255)->nullable();
            $table->string('file_pdf', 255)->nullable();
            $table->integer('dicetak_oleh')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pkl_persuratan');
        Schema::dropIfExists('pkl_nomor_surat');
        Schema::dropIfExists('pkl_penempatan');
        Schema::dropIfExists('pkl_pembimbing');
        Schema::dropIfExists('pkl_dudi');
        Schema::dropIfExists('pkl_kelas_gelombang');
        Schema::dropIfExists('pkl_gelombang');
    }
};
