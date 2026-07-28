<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KunjunganUksGukar extends Model
{
    protected $table = 'kunjungan_uks_gukar';
    protected $primaryKey = 'id_kunjungan';
    public $timestamps = false;

    protected $fillable = [
        'id_guru',
        'id_karyawan',
        'tanggal',
        'jam',
        'keluhan',
        'diagnosa',
        'tindakan',
    ];

    protected $casts = [
        'id_guru'     => 'integer',
        'id_karyawan' => 'integer',
        'tanggal'     => 'date:Y-m-d',
    ];

    // Relations
    public function guru()
    {
        return $this->belongsTo(Guru::class, 'id_guru', 'id_guru');
    }

    public function karyawan()
    {
        return $this->belongsTo(Karyawan::class, 'id_karyawan', 'id_karyawan');
    }

    public function riwayatObat()
    {
        return $this->hasMany(RiwayatObatGukar::class, 'id_kunjungan', 'id_kunjungan');
    }
}
