<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Mengubah enum status pada tabel pkl_penempatan untuk menambah nilai 'pindah'
        DB::statement("ALTER TABLE pkl_penempatan MODIFY COLUMN status ENUM('aktif','selesai','ditarik','batal','pindah') NOT NULL DEFAULT 'aktif'");
    }

    public function down(): void
    {
        // Kembalikan enum tanpa 'pindah' — pastikan tidak ada data 'pindah' sebelum rollback
        DB::statement("ALTER TABLE pkl_penempatan MODIFY COLUMN status ENUM('aktif','selesai','ditarik','batal') NOT NULL DEFAULT 'aktif'");
    }
};
