<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PklPersuratan extends Model
{
    protected $table = 'pkl_persuratan';
    protected $primaryKey = 'id_surat';

    protected $fillable = [
        'nomor_surat', 'jenis_surat', 'id_gelombang', 'id_dudi',
        'tanggal_surat', 'hal', 'file_pdf', 'dicetak_oleh', 'daftar_siswa',
    ];

    protected $casts = [
        'tanggal_surat' => 'date',
        'daftar_siswa'  => 'array',
    ];

    public function gelombang()
    {
        return $this->belongsTo(PklGelombang::class, 'id_gelombang', 'id_gelombang');
    }

    public function dudi()
    {
        return $this->belongsTo(PklDudi::class, 'id_dudi', 'id_dudi');
    }

    public function getJenisSuratLabelAttribute(): string
    {
        return match($this->jenis_surat) {
            'permohonan' => 'Surat Permohonan PKL',
            'penempatan' => 'Surat Pengantar Penempatan',
            'penarikan'  => 'Surat Penarikan Siswa',
            default      => $this->jenis_surat,
        };
    }
}
