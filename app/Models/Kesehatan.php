<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kesehatan extends Model
{
    protected $table = 'kesehatan';
    protected $primaryKey = 'id_kesehatan';
    public $timestamps = false;

    protected $fillable = [
        'tanggal',
        'nis',
        'keluhan',
        'penanganan',
    ];

    protected $casts = [
        'tanggal' => 'date:Y-m-d',
        'nis'     => 'integer',
    ];

    // Relations
    public function siswa()
    {
        return $this->belongsTo(UserSiswa::class, 'nis', 'nis');
    }
}
