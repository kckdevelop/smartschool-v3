<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class PklNomorSurat extends Model
{
    protected $table = 'pkl_nomor_surat';

    protected $fillable = [
        'jenis_surat', 'format_nomor', 'prefix',
        'counter_terakhir', 'tahun_reset',
    ];

    /**
     * Generate nomor surat berikutnya dan simpan counter
     */
    public static function generateNomor(string $jenis): string
    {
        $record = self::where('jenis_surat', $jenis)->lockForUpdate()->first();
        if (!$record) {
            $record = self::create([
                'jenis_surat'      => $jenis,
                'format_nomor'     => '{NO}/PKL/{BULAN-ROMAWI}/{TAHUN}',
                'counter_terakhir' => 0,
                'tahun_reset'      => date('Y'),
            ]);
        }

        // Reset counter tiap tahun
        if ($record->tahun_reset !== date('Y')) {
            $record->counter_terakhir = 0;
            $record->tahun_reset = date('Y');
        }

        $record->counter_terakhir += 1;
        $record->save();

        $romawi = ['', 'I','II','III','IV','V','VI','VII','VIII','IX','X','XI','XII'];
        $bulanRomawi = $romawi[(int) date('n')];

        $kodeJenis = match($jenis) {
            'permohonan' => 'PM',
            'penempatan' => 'PP',
            'penarikan'  => 'PT',
            default      => strtoupper(substr($jenis, 0, 2)),
        };

        $nomor = str_pad($record->counter_terakhir, 3, '0', STR_PAD_LEFT);
        $format = $record->format_nomor;
        $format = str_replace('{NO}',          $nomor,       $format);
        $format = str_replace('{KODE}',        $kodeJenis,   $format);
        $format = str_replace('{BULAN-ROMAWI}',$bulanRomawi, $format);
        $format = str_replace('{TAHUN}',       date('Y'),    $format);

        if ($record->prefix) {
            $format = $record->prefix . '/' . $format;
        }

        return $format;
    }
}
