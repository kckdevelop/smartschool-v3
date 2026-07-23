<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LogAbsensi extends Model
{
    protected $table = 'log_absensi';
    protected $primaryKey = 'id_presensi';
    public $timestamps = false;

    protected $fillable = [
        'nis',
        'tanggal',
        'jam',
        'status',
        'keterangan',
        'file',
    ];

    protected $casts = [
        'nis' => 'integer',
    ];

    // Relations
    public function siswa()
    {
        return $this->belongsTo(UserSiswa::class, 'nis', 'nis');
    }
}
