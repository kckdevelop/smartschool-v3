<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\JamPelajaran;

class JadwalMengajarTemplate extends Model
{
    protected $table = 'jadwal_mengajar_template';
    protected $primaryKey = 'id_template';

    protected $fillable = [
        'id_guru',
        'id_kelas',
        'id_mapel',
        'hari_siklus',
        'jam_ke',
        'ruang'
    ];

    protected $casts = [
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
        // JadwalMengajarTemplate uses column 'jam_ke' which matches JamPelajaran.jam_ke
        // atau bisa juga 'id_jam' — cek fillable; kita gunakan jam_ke
        return $this->belongsTo(JamPelajaran::class, 'jam_ke', 'jam_ke');
    }
}
