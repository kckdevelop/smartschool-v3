<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PklGelombang extends Model
{
    protected $table = 'pkl_gelombang';
    protected $primaryKey = 'id_gelombang';

    protected $fillable = [
        'nama_gelombang', 'tahun_ajaran', 'tanggal_mulai',
        'tanggal_selesai', 'status', 'keterangan',
    ];

    protected $casts = [
        'tanggal_mulai'   => 'date',
        'tanggal_selesai' => 'date',
    ];

    public function kelasGelombang()
    {
        return $this->hasMany(PklKelasGelombang::class, 'id_gelombang', 'id_gelombang');
    }

    public function penempatan()
    {
        return $this->hasMany(PklPenempatan::class, 'id_gelombang', 'id_gelombang');
    }

    public function siswa()
    {
        return $this->hasManyThrough(
            UserSiswa::class,
            PklKelasGelombang::class,
            'id_gelombang',
            'id_kelas',
            'id_gelombang',
            'id_kelas'
        )->where('user_siswa.status', 'aktif');
    }

    public function pembimbing()
    {
        return $this->hasMany(PklPembimbing::class, 'id_gelombang', 'id_gelombang');
    }

    public function getStatusLabelAttribute(): string
    {
        return match($this->status) {
            'draft'   => 'Draft',
            'aktif'   => 'Aktif',
            'selesai' => 'Selesai',
            default   => $this->status,
        };
    }
}
