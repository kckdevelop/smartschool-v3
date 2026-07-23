<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DetailSiswa extends Model
{
    protected $table = 'detail_siswa';
    protected $primaryKey = 'nis';
    public $incrementing = false;

    protected $fillable = [
        'nis',
        'alamat',
        'agama',
        'golongan_darah',
        'nama_ayah',
        'pekerjaan_ayah',
        'no_telp_ayah',
        'nama_ibu',
        'pekerjaan_ibu',
        'no_telp_ibu',
        'nama_wali',
        'pekerjaan_wali',
        'no_telp_wali',
        'no_wa_presensi',
        'foto',
        'latitude',
        'longitude',
    ];

    protected $casts = [
        'latitude'  => 'float',
        'longitude' => 'float',
    ];

    public function userSiswa()
    {
        return $this->belongsTo(UserSiswa::class, 'nis', 'nis');
    }
}
