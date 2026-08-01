<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TabelIqro extends Model
{
    protected $table = 'tabel_iqro';
    public $timestamps = false;

    protected $fillable = [
        'jilid',
        'halaman',
        'baris',
    ];

    protected $casts = [
        'jilid'   => 'integer',
        'halaman' => 'integer',
        'baris'   => 'integer',
    ];
}
