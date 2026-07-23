<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RiwayatKesehatan extends Model
{
    protected $table = 'riwayat_kesehatan';
    protected $primaryKey = 'id_riwayat_kesehatan';

    protected $fillable = [
        'nis',
        'tanggal',
        'tinggi_badan',
        'berat_badan',
        'golongan_darah',
        'penyakit_bawaan',
        'alergi',
        'riwayat_penyakit',
        'catatan_khusus',
    ];

    protected $casts = [
        'tanggal'      => 'date:Y-m-d',
        'nis'          => 'integer',
        'tinggi_badan' => 'integer',
        'berat_badan'  => 'integer',
    ];

    // Relation back to student
    public function siswa()
    {
        return $this->belongsTo(UserSiswa::class, 'nis', 'nis');
    }
}
