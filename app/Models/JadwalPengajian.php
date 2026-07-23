<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JadwalPengajian extends Model
{
    protected $table = 'jadwal_pengajian';
    protected $primaryKey = 'id_jadwal';
    public $timestamps = true;

    protected $fillable = [
        'nama_kegiatan',
        'tanggal',
        'jam_mulai',
        'jam_selesai',
        'tempat',
        'lokasi_gmaps',
        'latitude',
        'longitude',
        'radius_meter',
        'keterangan',
    ];

    protected $casts = [
        'tanggal' => 'date:Y-m-d',
        'latitude' => 'float',
        'longitude' => 'float',
        'radius_meter' => 'integer',
    ];

    public function kehadiran()
    {
        return $this->hasMany(KehadiranPengajian::class, 'id_jadwal', 'id_jadwal');
    }

    /**
     * Get count of 'hadir' status
     */
    public function getHadirCountAttribute(): int
    {
        return $this->kehadiran()->where('status', 'hadir')->count();
    }

    /**
     * Get count of 'ijin' status
     */
    public function getIjinCountAttribute(): int
    {
        return $this->kehadiran()->where('status', 'ijin')->count();
    }

    /**
     * Get count of 'alpha' status
     */
    public function getAlphaCountAttribute(): int
    {
        return $this->kehadiran()->where('status', 'alpha')->count();
    }

    /**
     * Total peserta (hadir + ijin + alpha)
     */
    public function getTotalAttribute(): int
    {
        return $this->kehadiran()->count();
    }

    /**
     * Persentase kehadiran
     */
    public function getPersenHadirAttribute(): float
    {
        $total = $this->total;
        $hadir = $this->hadir_count;
        return $total > 0 ? round(($hadir / $total) * 100, 1) : 0;
    }
}
