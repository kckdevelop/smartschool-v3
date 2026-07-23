<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('panggil_ortu', function (Blueprint $table) {
            $table->string('bukti_pertemuan', 255)->nullable()->after('hasil_pertemuan');
            $table->string('surat_pernyataan', 255)->nullable()->after('bukti_pertemuan');
        });
    }

    public function down(): void
    {
        Schema::table('panggil_ortu', function (Blueprint $table) {
            $table->dropColumn(['bukti_pertemuan', 'surat_pernyataan']);
        });
    }
};
