<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GayaBelajar extends Model
{
    protected $table = 'gaya_belajar';
    protected $primaryKey = 'id_gaya_belajar';

    protected $fillable = [
        'nis',
        'gaya_belajar',
        'skor_visual',
        'skor_auditori',
        'skor_kinestetik',
        'minat',
        'catatan',
        'id_guru',
    ];

    protected $casts = [
        'id_guru'         => 'integer',
        'skor_visual'     => 'integer',
        'skor_auditori'   => 'integer',
        'skor_kinestetik' => 'integer',
    ];

    public function guru()
    {
        return $this->belongsTo(Guru::class, 'id_guru', 'id_guru');
    }

    public function siswa()
    {
        return $this->belongsTo(UserSiswa::class, 'nis', 'nis');
    }
}
