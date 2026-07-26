<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PanggilOrtu extends Model
{
    protected $table = 'panggil_ortu';
    protected $primaryKey = 'id_panggil';

    protected $fillable = [
        'no_surat',
        'tanggal_panggil',
        'waktu_pertemuan',
        'lokasi_pertemuan',
        'nis',
        'nama_ortu',
        'no_hp_ortu',
        'jenis_panggilan',
        'alasan_panggil',
        'hasil_pertemuan',
        'bukti_pertemuan',
        'surat_pernyataan',
        'status',
        'id_guru',
    ];

    protected $casts = [
        'tanggal_panggil' => 'date',
        'id_guru'         => 'integer',
    ];

    public function getBuktiPertemuanUrlAttribute(): ?string
    {
        return $this->bukti_pertemuan ? asset('storage/' . $this->bukti_pertemuan) : null;
    }

    public function getSuratPernyataanUrlAttribute(): ?string
    {
        return $this->surat_pernyataan ? asset('storage/' . $this->surat_pernyataan) : null;
    }

    protected $appends = ['bukti_pertemuan_url', 'surat_pernyataan_url'];

    public function guru()
    {
        return $this->belongsTo(Guru::class, 'id_guru', 'id_guru');
    }

    public function siswa()
    {
        return $this->belongsTo(UserSiswa::class, 'nis', 'nis');
    }
}
