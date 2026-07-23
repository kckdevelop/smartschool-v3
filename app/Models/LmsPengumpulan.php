<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LmsPengumpulan extends Model
{
    protected $table = 'lms_pengumpulan';
    protected $primaryKey = 'id_pengumpulan';

    protected $fillable = [
        'id_tugas',
        'nis',
        'file_path',
        'catatan',
        'nilai',
        'status',
    ];

    protected $casts = [
        'id_tugas' => 'integer',
        'nis'      => 'integer',
        'nilai'    => 'integer',
    ];

    // Relations
    public function tugas()
    {
        return $this->belongsTo(LmsTugas::class, 'id_tugas', 'id_tugas');
    }

    public function siswa()
    {
        return $this->belongsTo(UserSiswa::class, 'nis', 'nis');
    }
}
