<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PantauIbadah extends Model
{
    protected $table = 'pantau_ibadah';
    protected $primaryKey = 'id_ibadah';
    public $timestamps = false;

    protected $fillable = [
        'tanggal',
        'nis',
        'id_kelas',
        'id_guru',
        'jenis_ibadah',
        'nilai',
        'catatan',
    ];

    protected $casts = [
        'tanggal'  => 'date:Y-m-d',
        'nis'      => 'integer',
        'id_kelas' => 'integer',
        'id_guru'  => 'integer',
    ];

    public function siswa()
    {
        return $this->belongsTo(UserSiswa::class, 'nis', 'nis');
    }

    public function kelas()
    {
        return $this->belongsTo(Kelas::class, 'id_kelas', 'id_kelas');
    }

    public function guru()
    {
        return $this->belongsTo(Guru::class, 'id_guru', 'id_guru');
    }

    public function getLabelJenisAttribute(): string
    {
        return match($this->jenis_ibadah) {
            'sholat_fardu'   => 'Bacaan Sholat Fardu',
            'sholat_jenazah' => 'Sholat Jenazah',
            'gerakan_wudhu'  => 'Gerakan Wudhu',
            default          => $this->jenis_ibadah,
        };
    }
}
