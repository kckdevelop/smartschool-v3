<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TabelAlquran extends Model
{
    protected $table = 'tabel_alquran';
    public $timestamps = false;

    protected $fillable = [
        'surat',
        'ayat',
    ];

    protected $casts = [
        'ayat' => 'integer',
    ];
}
