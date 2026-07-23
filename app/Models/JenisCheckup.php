<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JenisCheckup extends Model
{
    protected $table = 'jenis_checkup';
    protected $primaryKey = 'id_checkup';
    public $timestamps = false;

    protected $fillable = [
        'jenis_checkup',
        'status',
    ];
}
