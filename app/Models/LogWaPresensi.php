<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LogWaPresensi extends Model
{
    protected $table = 'log_wa_presensi';

    protected $fillable = [
        'tanggal',
        'nis',
        'no_wa',
        'status_presensi',
        'jam_presensi',
        'pesan',
        'status_wa',
        'response',
        'sent_at',
    ];

    protected $casts = [
        'tanggal' => 'date',
        'sent_at' => 'datetime',
        'nis'     => 'integer',
    ];

    public function siswa()
    {
        return $this->belongsTo(UserSiswa::class, 'nis', 'nis');
    }
}
