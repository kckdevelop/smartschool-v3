<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Semester extends Model
{
    protected $table = 'semester';
    protected $primaryKey = 'id_semester';
    public $timestamps = false;

    protected $fillable = [
        'id_tahun',
        'semester',
        'awal',
        'akhir',
        'status',
    ];

    protected $casts = [
        'id_tahun' => 'integer',
        'awal'     => 'date',
        'akhir'    => 'date',
    ];

    // Relations
    public function tahunAjaran()
    {
        return $this->belongsTo(TahunAjaran::class, 'id_tahun', 'id_tahun');
    }
}
