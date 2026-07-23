<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tadarus extends Model
{
    protected $table = 'tadarus';
    protected $primaryKey = 'id_tadarus';
    public $timestamps = false;

    protected $fillable = [
        'tanggal',
        'id_kelas',
        'awal_surat',
        'awal_ayat',
        'akhir_surat',
        'akhir_ayat',
        'id_guru',
    ];

    protected $casts = [
        'tanggal'   => 'date:Y-m-d',
        'id_kelas'  => 'integer',
        'awal_ayat' => 'integer',
        'akhir_ayat'=> 'integer',
        'id_guru'   => 'integer',
    ];

    // Relations
    public function kelas()
    {
        return $this->belongsTo(Kelas::class, 'id_kelas', 'id_kelas');
    }

    public function guru()
    {
        return $this->belongsTo(Guru::class, 'id_guru', 'id_guru');
    }
}
