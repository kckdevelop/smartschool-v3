<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Presensi extends Model
{
    protected $table = 'presensi';
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

    public function getFileUrlAttribute(): ?string
    {
        return $this->file ? asset('storage/' . $this->file) : null;
    }

    protected $appends = ['file_url'];

    // Relations
    public function siswa()
    {
        return $this->belongsTo(UserSiswa::class, 'nis', 'nis');
    }
}
