<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BukuKasus extends Model
{
    protected $table = 'buku_kasus';
    protected $primaryKey = 'id_kasus';

    protected $fillable = [
        'tanggal',
        'nis',
        'judul_kasus',
        'uraian_kasus',
        'tindak_lanjut',
        'status',
        'id_guru',
    ];

    protected $casts = [
        'tanggal'  => 'date',
        'id_guru'  => 'integer',
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
