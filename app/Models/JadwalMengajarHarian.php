<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JadwalMengajarHarian extends Model
{
    protected $table = 'jadwal_mengajar_harian';
    protected $primaryKey = 'id_jadwal_harian';

    protected $fillable = [
        'tanggal',
        'id_guru',
        'id_kelas',
        'id_mapel',
        'jam_ke',
        'status',
        'keterangan',
        'ruang'
    ];

    protected $casts = [
        'tanggal' => 'date:Y-m-d',
        'id_guru' => 'integer',
        'id_kelas' => 'integer',
        'id_mapel' => 'integer',
        'jam_ke' => 'integer'
    ];

    // Relations
    public function guru()
    {
        return $this->belongsTo(Guru::class, 'id_guru', 'id_guru');
    }

    public function kelas()
    {
        return $this->belongsTo(Kelas::class, 'id_kelas', 'id_kelas');
    }

    public function mapel()
    {
        return $this->belongsTo(Mapel::class, 'id_mapel', 'id_mapel');
    }

    public function jamPelajaran()
    {
        return $this->belongsTo(JamPelajaran::class, 'jam_ke', 'jam_ke');
    }
}
