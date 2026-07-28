<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RiwayatObatGukar extends Model
{
    protected $table = 'riwayat_obat_gukar';
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
    public function kunjunganUksGukar()
    {
        return $this->belongsTo(KunjunganUksGukar::class, 'id_kunjungan', 'id_kunjungan');
    }
}
