<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BimbinganKonseling extends Model
{
    protected $table = 'bimbingan_konseling';
    protected $primaryKey = 'id_bk';
    public $timestamps = false;

    protected $fillable = [
        'tanggal',
        'nis',
        'jenis_masalah',
        'uraian',
        'tindak_lanjut',
        'status',
        'id_guru',
    ];

    protected $casts = [
        'tanggal' => 'date:Y-m-d',
        'nis'     => 'integer',
        'id_guru' => 'integer',
    ];

    // Relations
    public function guru()
    {
        return $this->belongsTo(Guru::class, 'id_guru', 'id_guru');
    }

    public function siswa()
    {
        return $this->belongsTo(UserSiswa::class, 'nis', 'nis');
    }
}
