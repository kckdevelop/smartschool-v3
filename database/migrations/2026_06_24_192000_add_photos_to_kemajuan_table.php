<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('kemajuan', function (Blueprint $table) {
            if (!Schema::hasColumn('kemajuan', 'foto_1')) {
                $table->string('foto_1', 255)->nullable()->after('keterangan');
            }
            if (!Schema::hasColumn('kemajuan', 'foto_2')) {
                $table->string('foto_2', 255)->nullable()->after('foto_1');
            }
            if (!Schema::hasColumn('kemajuan', 'foto_3')) {
                $table->string('foto_3', 255)->nullable()->after('foto_2');
            }
        });
    }

    public function down(): void
    {
        Schema::table('kemajuan', function (Blueprint $table) {
            $table->dropColumn(array_filter(['foto_1', 'foto_2', 'foto_3'], function ($col) {
                return Schema::hasColumn('kemajuan', $col);
            }));
        });
    }
};
