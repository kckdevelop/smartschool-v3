<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DaftarSurat extends Model
{
    protected $table = 'daftar_surat';
    protected $primaryKey = 'id_surat';
    public $timestamps = false;

    protected $fillable = [
        'urutan',
        'nama_surat',
    ];

    protected $casts = [
        'urutan' => 'integer',
    ];
}
