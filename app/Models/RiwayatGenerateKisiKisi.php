<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RiwayatGenerateKisiKisi extends Model
{
    protected $table = 'riwayat_generate_kisikisi';
    protected $primaryKey = 'id_kisikisi';
    public $timestamps = true;

    protected $fillable = [
        'id_guru',
        'id_mapel',
        'id_kelas',
        'semester',
        'jenis_penilaian',
        'tahun_pelajaran',
        'kurikulum',
        'alokasi_waktu',
        'jumlah_soal',
        'tipe_soal',
        'hasil_json',
    ];

    protected $casts = [
        'id_guru'         => 'integer',
        'id_mapel'        => 'integer',
        'id_kelas'        => 'integer',
        'semester'        => 'integer',
        'alokasi_waktu'   => 'integer',
        'jumlah_soal'     => 'integer',
        'hasil_json'      => 'array',
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
