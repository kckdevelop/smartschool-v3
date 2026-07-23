<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HomeVisit extends Model
{
    protected $table = 'home_visit';
    protected $primaryKey = 'id_home_visit';

    protected $fillable = [
        'tanggal_visit',
        'nis',
        'alamat',
        'tujuan_kunjungan',
        'hasil_kunjungan',
        'tindak_lanjut',
        'status',
        'id_guru',
        'foto_bukti',
    ];

    protected $casts = [
        'tanggal_visit' => 'date',
        'id_guru'       => 'integer',
    ];

    public function guru()
    {
        return $this->belongsTo(Guru::class, 'id_guru', 'id_guru');
    }

    public function siswa()
    {
        return $this->belongsTo(UserSiswa::class, 'nis', 'nis');
    }
}
