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

    public function guru()
    {
        return $this->belongsTo(Guru::class, 'id_guru', 'id_guru');
    }

    public function siswa()
    {
        return $this->belongsTo(UserSiswa::class, 'nis', 'nis');
    }
}
