<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Btaq extends Model
{
    protected $table = 'btaq';
    protected $primaryKey = 'id_btaq';
    public $timestamps = false;

    protected $fillable = [
        'tanggal',
        'nis',
        'id_kelas',
        'level',
        'awal',
        'akhir',
        'id_guru',
    ];

    protected $casts = [
        'tanggal'   => 'date:Y-m-d',
        'nis'       => 'integer',
        'id_kelas'  => 'integer',
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

    public function siswa()
    {
        return $this->belongsTo(UserSiswa::class, 'nis', 'nis');
    }

    public function iqroAwal()
    {
        return $this->belongsTo(TabelIqro::class, 'awal', 'id');
    }

    public function iqroAkhir()
    {
        return $this->belongsTo(TabelIqro::class, 'akhir', 'id');
    }

    public function alquranAwal()
    {
        return $this->belongsTo(TabelAlquran::class, 'awal', 'id');
    }

    public function alquranAkhir()
    {
        return $this->belongsTo(TabelAlquran::class, 'akhir', 'id');
    }
}
