<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DataMesin extends Model
{
    protected $table = 'data_mesin';
    protected $primaryKey = 'id_mesin';
    public $timestamps = false;

    protected $fillable = [
        'nama_mesin',
        'sn',
        'password',
        'data',
        'last_update',
    ];

    protected $hidden = [
        'password',
    ];

    protected $casts = [
        'data'        => 'integer',
        'last_update' => 'datetime',
    ];
}
