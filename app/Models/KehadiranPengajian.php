<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KehadiranPengajian extends Model
{
    protected $table = 'kehadiran_pengajian';
    protected $primaryKey = 'id';
    public $timestamps = true;

    protected $fillable = [
        'id_jadwal',
        'id_guru',
        'id_karyawan',
        'status',
        'jam_absen',
        'foto',
        'lokasi_gmaps',
        'keterangan',
    ];

    public function jadwal()
    {
        return $this->belongsTo(JadwalPengajian::class, 'id_jadwal', 'id_jadwal');
    }

    public function guru()
    {
        return $this->belongsTo(Guru::class, 'id_guru', 'id_guru');
    }

    public function karyawan()
    {
        return $this->belongsTo(Karyawan::class, 'id_karyawan', 'id_karyawan');
    }
}
