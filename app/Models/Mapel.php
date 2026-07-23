<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Mapel extends Model
{
    protected $table = 'mapel';
    protected $primaryKey = 'id_mapel';
    public $timestamps = false;

    protected $fillable = [
        'kode_mapel',
        'nama_mapel',
    ];

    // Relations
    public function kemajuan()
    {
        return $this->hasMany(Kemajuan::class, 'id_mapel', 'id_mapel');
    }

    public function jadwalTemplate()
    {
        return $this->hasMany(JadwalMengajarTemplate::class, 'id_mapel', 'id_mapel');
    }

    public function jadwalHarian()
    {
        return $this->hasMany(JadwalMengajarHarian::class, 'id_mapel', 'id_mapel');
    }
}
