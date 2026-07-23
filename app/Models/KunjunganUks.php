<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KunjunganUks extends Model
{
    protected $table = 'kunjungan_uks';
    protected $primaryKey = 'id_kunjungan';
    public $timestamps = false;

    protected $fillable = [
        'nis',
        'tanggal',
        'jam',
        'keluhan',
        'diagnosa',
        'tindakan',
    ];

    protected $casts = [
        'nis'     => 'integer',
        'tanggal' => 'date:Y-m-d',
    ];

    // Relations
    public function siswa()
    {
        return $this->belongsTo(UserSiswa::class, 'nis', 'nis');
    }

    public function riwayatObat()
    {
        return $this->hasMany(RiwayatObat::class, 'id_kunjungan', 'id_kunjungan');
    }
}
