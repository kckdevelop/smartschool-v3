<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JamPelajaran extends Model
{
    protected $table = 'jam_pelajaran';
    protected $primaryKey = 'id_jam';

    protected $fillable = [
        'jam_ke',
        'normal_mulai',
        'normal_selesai',
        'upacara_mulai',
        'upacara_selesai',
        'puasa_mulai',
        'puasa_selesai'
    ];

    protected $casts = [
        'jam_ke' => 'integer'
    ];
}
