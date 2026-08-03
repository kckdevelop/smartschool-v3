<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LmsSoal extends Model
{
    protected $table = 'lms_soal';
    protected $primaryKey = 'id_soal';

    protected $fillable = [
        'id_tugas',
        'nomor_soal',
        'jenis_soal',
        'pertanyaan',
        'gambar',
        'kunci_jawaban',
    ];

    protected $casts = [
        'id_tugas'   => 'integer',
        'nomor_soal' => 'integer',
    ];

    public function tugas()
    {
        return $this->belongsTo(LmsTugas::class, 'id_tugas', 'id_tugas');
    }

    public function pilihan()
    {
        return $this->hasMany(LmsSoalPilihan::class, 'id_soal', 'id_soal');
    }
}
