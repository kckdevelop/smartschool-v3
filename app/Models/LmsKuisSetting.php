<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LmsKuisSetting extends Model
{
    protected $table = 'lms_kuis_setting';
    protected $primaryKey = 'id_kuis_setting';

    protected $fillable = [
        'id_tugas',
        'durasi_menit',
        'acak_soal',
        'acak_jawaban',
        'tampilkan_hasil',
        'maks_percobaan',
    ];

    protected $casts = [
        'id_tugas'        => 'integer',
        'durasi_menit'    => 'integer',
        'acak_soal'       => 'boolean',
        'acak_jawaban'    => 'boolean',
        'tampilkan_hasil' => 'boolean',
        'maks_percobaan'  => 'integer',
    ];

    public function tugas()
    {
        return $this->belongsTo(LmsTugas::class, 'id_tugas', 'id_tugas');
    }
}
