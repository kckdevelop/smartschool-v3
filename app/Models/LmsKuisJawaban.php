<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LmsKuisJawaban extends Model
{
    protected $table = 'lms_kuis_jawaban';
    protected $primaryKey = 'id_jawaban';

    protected $fillable = [
        'id_sesi',
        'id_soal',
        'id_pilihan',
        'jawaban_teks',
        'is_benar',
    ];

    protected $casts = [
        'id_sesi'     => 'integer',
        'id_soal'     => 'integer',
        'id_pilihan'  => 'integer',
        'is_benar'    => 'boolean',
    ];

    public function sesi()
    {
        return $this->belongsTo(LmsKuisSesi::class, 'id_sesi', 'id_sesi');
    }

    public function soal()
    {
        return $this->belongsTo(LmsSoal::class, 'id_soal', 'id_soal');
    }

    public function pilihan()
    {
        return $this->belongsTo(LmsSoalPilihan::class, 'id_pilihan', 'id_pilihan');
    }
}
