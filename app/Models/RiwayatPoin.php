<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RiwayatPoin extends Model
{
    protected $table = 'riwayat_poin';
    protected $primaryKey = 'id_poin';
    public $timestamps = false;

    protected $fillable = [
        'tgl_input',
        'nis',
        'tingkat',
        'pelanggaran',
        'poin',
        'id_guru',
    ];

    protected $casts = [
        'tgl_input' => 'date',
        'tingkat'   => 'integer',
        'poin'      => 'integer',
        'id_guru'   => 'integer',
    ];

    // Relations
    public function guru()
    {
        return $this->belongsTo(Guru::class, 'id_guru', 'id_guru');
    }

    public function siswa()
    {
        return $this->belongsTo(UserSiswa::class, 'nis', 'nis');
    }
}
