<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PklRiwayatPindah extends Model
{
    protected $table = 'pkl_riwayat_pindah';

    protected $fillable = [
        'nis', 'id_gelombang',
        'id_penempatan_lama', 'id_penempatan_baru',
        'tanggal_pindah', 'alasan', 'dicatat_oleh',
    ];

    protected $casts = [
        'tanggal_pindah' => 'date',
    ];

    /* ── Relasi ── */

    public function siswa()
    {
        return $this->belongsTo(UserSiswa::class, 'nis', 'nis');
    }

    public function gelombang()
    {
        return $this->belongsTo(PklGelombang::class, 'id_gelombang', 'id_gelombang');
    }

    public function penempatanLama()
    {
        return $this->belongsTo(PklPenempatan::class, 'id_penempatan_lama', 'id_penempatan');
    }

    public function penempatanBaru()
    {
        return $this->belongsTo(PklPenempatan::class, 'id_penempatan_baru', 'id_penempatan');
    }
}
