<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RiwayatGenerateSoal extends Model
{
    protected $table = 'riwayat_generate_soal';
    protected $primaryKey = 'id_riwayat';
    public $timestamps = true;

    protected $fillable = [
        'id_guru',
        'id_mapel',
        'id_kelas',
        'topik',
        'jumlah_soal',
        'tipe_soal',
        'kesulitan',
        'hasil_json',
        'semester',
        'kompetensi_dasar',
        'indikator',
    ];

    protected $casts = [
        'id_guru'     => 'integer',
        'id_mapel'    => 'integer',
        'id_kelas'    => 'integer',
        'jumlah_soal' => 'integer',
        'hasil_json'  => 'array',
        'semester'    => 'integer',
    ];

    // Relations
    public function guru()
    {
        return $this->belongsTo(Guru::class, 'id_guru', 'id_guru');
    }

    public function mapel()
    {
        return $this->belongsTo(Mapel::class, 'id_mapel', 'id_mapel');
    }

    public function kelas()
    {
        return $this->belongsTo(Kelas::class, 'id_kelas', 'id_kelas');
    }
}
