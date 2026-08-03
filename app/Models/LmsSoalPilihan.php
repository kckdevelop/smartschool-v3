<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LmsSoalPilihan extends Model
{
    protected $table = 'lms_soal_pilihan';
    protected $primaryKey = 'id_pilihan';

    protected $fillable = [
        'id_soal',
        'kunci',
        'teks',
        'gambar',
        'is_kunci',
    ];

    protected $casts = [
        'id_soal'  => 'integer',
        'is_kunci' => 'boolean',
    ];

    public function soal()
    {
        return $this->belongsTo(LmsSoal::class, 'id_soal', 'id_soal');
    }
}
