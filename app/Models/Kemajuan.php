<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kemajuan extends Model
{
    protected $table = 'kemajuan';
    protected $primaryKey = 'id_kemajuan';
    public $timestamps = false;

    protected $fillable = [
        'tanggal',
        'jam_ke',
        'id_mapel',
        'id_guru',
        'materi',
        'id_kelas',
        'jml_siswa',
        'absen',
        'keterangan',
        'status_approval',
        'foto_1',
        'foto_2',
        'foto_3',
        'fotos',
    ];

    protected $casts = [
        'tanggal'   => 'date',
        'id_mapel'  => 'integer',
        'id_guru'   => 'integer',
        'id_kelas'  => 'integer',
        'jml_siswa' => 'integer',
        'fotos'     => 'array',
    ];

    // Relations
    public function mapel()
    {
        return $this->belongsTo(Mapel::class, 'id_mapel', 'id_mapel');
    }

    public function guru()
    {
        return $this->belongsTo(Guru::class, 'id_guru', 'id_guru');
    }

    public function kelas()
    {
        return $this->belongsTo(Kelas::class, 'id_kelas', 'id_kelas');
    }
}
