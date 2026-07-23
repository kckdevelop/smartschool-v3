<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JadwalSiklus extends Model
{
    protected $table = 'jadwal_siklus';
    protected $primaryKey = 'id_jadwal';

    protected $fillable = [
        'tanggal',
        'hari_ke',
        'siklus',
        'status',
        'keterangan'
    ];

    protected $casts = [
        'tanggal' => 'date:Y-m-d',
        'siklus' => 'integer'
    ];
}
