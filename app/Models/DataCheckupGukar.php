<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DataCheckupGukar extends Model
{
    protected $table = 'data_checkup_gukar';
    protected $primaryKey = 'id_checkup';
    public $timestamps = false;

    protected $fillable = [
        'id_guru',
        'id_karyawan',
        'tanggal',
        'jam',
        'tinggi_badan',
        'berat_badan',
        'imt',
        'kategori',
        'tekanan_darah',
        'kolesterol',
        'gula_darah',
        'tipe_gula_darah',
        'asam_urat',
    ];

    protected $casts = [
        'tanggal'      => 'date:Y-m-d',
        'id_guru'      => 'integer',
        'id_karyawan'  => 'integer',
        'tinggi_badan' => 'float',
        'berat_badan'  => 'float',
        'imt'          => 'float',
        'kolesterol'   => 'float',
        'gula_darah'   => 'float',
        'asam_urat'    => 'float',
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
}
