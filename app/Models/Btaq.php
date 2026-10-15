<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Btaq extends Model
{
    protected $table = 'btaq';
    protected $primaryKey = 'id_btaq';
    public $timestamps = false;

    protected $fillable = [
        'tanggal',
        'nis',
        'id_kelas',
        'level',
        'awal',
        'akhir',
        'id_guru',
    ];

    protected $casts = [
        'tanggal'   => 'date:Y-m-d',
        'nis'       => 'integer',
        'id_kelas'  => 'integer',
        'id_guru'   => 'integer',
    ];

    // Relations
    public function kelas()
    {
        return $this->belongsTo(Kelas::class, 'id_kelas', 'id_kelas');
    }

    public function guru()
    {
        return $this->belongsTo(Guru::class, 'id_guru', 'id_guru');
    }

    public function siswa()
    {
        return $this->belongsTo(UserSiswa::class, 'nis', 'nis');
    }

    public function iqroAwal()
    {
        return $this->belongsTo(TabelIqro::class, 'awal', 'id');
    }

    public function iqroAkhir()
    {
        return $this->belongsTo(TabelIqro::class, 'akhir', 'id');
    }

    public function alquranAwal()
    {
        return $this->belongsTo(TabelAlquran::class, 'awal', 'id');
    }

    public function alquranAkhir()
    {
        return $this->belongsTo(TabelAlquran::class, 'akhir', 'id');
    }

    public function getAwalFormattedAttribute(): string
    {
        return $this->formatBtaqTarget($this->awal, $this->iqroAwal, $this->alquranAwal);
    }

    public function getAkhirFormattedAttribute(): string
    {
        return $this->formatBtaqTarget($this->akhir, $this->iqroAkhir, $this->alquranAkhir);
    }

    protected function formatBtaqTarget($rawVal, $iqroRel, $quranRel): string
    {
        if ((is_null($rawVal) || $rawVal === '') && !$iqroRel && !$quranRel) {
            return '—';
        }

        $isIqro = (stripos($this->level ?? '', 'iqra') !== false || stripos($this->level ?? '', 'iqro') !== false);

        if ($isIqro) {
            $iqro = $iqroRel;
            if (!$iqro && is_numeric($rawVal)) {
                $iqro = TabelIqro::find($rawVal);
            }
            if ($iqro) {
                $res = "Jilid {$iqro->jilid}, Hal. {$iqro->halaman}";
                if (!empty($iqro->baris)) {
                    $res .= ", Baris {$iqro->baris}";
                }
                return $res;
            }
            if (is_numeric($rawVal)) {
                return "Hal. {$rawVal}";
            }
            return (string) $rawVal;
        } else {
            $quran = $quranRel;
            if (!$quran && is_numeric($rawVal)) {
                $quran = TabelAlquran::find($rawVal);
            }
            if ($quran) {
                return "QS. {$quran->surat}: {$quran->ayat}";
            }
            return (string) $rawVal;
        }
    }
}
