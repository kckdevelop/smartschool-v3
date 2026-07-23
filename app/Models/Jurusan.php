<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Jurusan extends Model
{
    protected $table = 'jurusan';
    protected $primaryKey = 'id_jurusan';
    public $timestamps = false;

    protected $fillable = [
        'kode_jurusan',
        'nama_jurusan',
        'status',
    ];

    // Relations
    public function kelas()
    {
        return $this->hasMany(Kelas::class, 'id_jurusan', 'id_jurusan');
    }

    public function dudi()
    {
        return $this->hasMany(PklDudi::class, 'id_jurusan', 'id_jurusan');
    }
}
