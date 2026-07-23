<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PklPenempatan extends Model
{
    protected $table = 'pkl_penempatan';
    protected $primaryKey = 'id_penempatan';

    protected $fillable = [
        'id_gelombang', 'id_dudi', 'nis', 'id_pembimbing',
        'tanggal_masuk', 'tanggal_keluar', 'status', 'keterangan',
    ];

    public function riwayatPindah()
    {
        return $this->hasMany(PklRiwayatPindah::class, 'id_penempatan_lama', 'id_penempatan');
    }

    protected $casts = [
        'tanggal_masuk'  => 'date',
        'tanggal_keluar' => 'date',
    ];

    public function gelombang()
    {
        return $this->belongsTo(PklGelombang::class, 'id_gelombang', 'id_gelombang');
    }

    public function dudi()
    {
        return $this->belongsTo(PklDudi::class, 'id_dudi', 'id_dudi');
    }

    public function siswa()
    {
        return $this->belongsTo(UserSiswa::class, 'nis', 'nis');
    }

    public function pembimbing()
    {
        return $this->belongsTo(PklPembimbing::class, 'id_pembimbing', 'id_pembimbing');
    }

    public function getStatusLabelAttribute(): string
    {
        return match($this->status) {
            'aktif'   => 'Aktif',
            'selesai' => 'Selesai',
            'ditarik' => 'Ditarik',
            'batal'   => 'Batal',
            'pindah'  => 'Pindah',
            default   => $this->status,
        };
    }

    public function getStatusColorAttribute(): string
    {
        return match($this->status) {
            'aktif'   => 'success',
            'selesai' => 'info',
            'ditarik' => 'warning',
            'batal'   => 'danger',
            'pindah'  => 'warning',
            default   => 'muted',
        };
    }
}
