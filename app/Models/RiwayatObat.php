<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RiwayatObat extends Model
{
    protected $table = 'riwayat_obat';
    protected $primaryKey = 'id_riwayat';
    public $timestamps = false;

    protected $fillable = [
        'id_kunjungan',
        'nama_obat',
        'dosis',
        'jumlah',
    ];

    protected $casts = [
        'id_kunjungan' => 'integer',
        'jumlah'       => 'integer',
    ];

    // Relations
    public function kunjunganUks()
    {
        return $this->belongsTo(KunjunganUks::class, 'id_kunjungan', 'id_kunjungan');
    }
}
