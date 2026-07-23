<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('kemajuan', function (Blueprint $table) {
            if (!Schema::hasColumn('kemajuan', 'fotos')) {
                $table->text('fotos')->nullable()->after('keterangan');
            }
        });

        // Migrate existing foto_1, foto_2, foto_3 to the new JSON fotos column
        $jurnals = \DB::table('kemajuan')->get();
        foreach ($jurnals as $j) {
            $arr = [];
            if (isset($j->foto_1) && !empty($j->foto_1)) $arr[] = $j->foto_1;
            if (isset($j->foto_2) && !empty($j->foto_2)) $arr[] = $j->foto_2;
            if (isset($j->foto_3) && !empty($j->foto_3)) $arr[] = $j->foto_3;
            
            if (count($arr) > 0) {
                \DB::table('kemajuan')
                    ->where('id_kemajuan', $j->id_kemajuan)
                    ->update(['fotos' => json_encode($arr)]);
            }
        }
    }

    public function down(): void
    {
        Schema::table('kemajuan', function (Blueprint $table) {
            if (Schema::hasColumn('kemajuan', 'fotos')) {
                $table->dropColumn('fotos');
            }
        });
    }
};
