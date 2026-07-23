<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DataCheckup extends Model
{
    protected $table = 'data_checkup';
    protected $primaryKey = 'id_checkup';
    public $timestamps = false;

    protected $fillable = [
        'tanggal',
        'jam',
        'nis',
        'jenis_checkup',
        'nilai',
        'satuan',
        'tinggi_badan',
        'berat_badan',
        'imt',
        'kategori',
        'tekanan_darah',
        'is_merokok',
    ];

    protected $casts = [
        'tanggal'      => 'date:Y-m-d',
        'nis'          => 'integer',
        'nilai'        => 'integer',
        'tinggi_badan' => 'float',
        'berat_badan'  => 'float',
        'imt'          => 'float',
    ];

    // Relations
    public function siswa()
    {
        return $this->belongsTo(UserSiswa::class, 'nis', 'nis');
    }
}
