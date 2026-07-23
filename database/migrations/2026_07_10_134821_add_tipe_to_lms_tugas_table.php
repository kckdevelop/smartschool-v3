<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('lms_tugas', function (Blueprint $table) {
            $table->enum('tipe', ['pdf', 'gambar', 'teks'])->default('pdf')->after('tenggat');
        });
    }

    public function down(): void
    {
        Schema::table('lms_tugas', function (Blueprint $table) {
            $table->dropColumn('tipe');
        });
    }
};
