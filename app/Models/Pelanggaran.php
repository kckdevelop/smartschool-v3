<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pelanggaran extends Model
{
    protected $table = 'pelanggaran';
    protected $primaryKey = 'id_pelanggaran';
    public $timestamps = false;

    protected $fillable = [
        'id_jenis_pelanggaran',
        'detail_pelanggaran',
        'skor',
    ];

    protected $casts = [
        'id_jenis_pelanggaran' => 'integer',
        'skor'                 => 'integer',
    ];

    // Relations
    public function jenisPelanggaran()
    {
        return $this->belongsTo(JenisPelanggaran::class, 'id_jenis_pelanggaran', 'id_jenis_pelanggaran');
    }
}
