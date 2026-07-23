<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JenisPelanggaran extends Model
{
    protected $table = 'jenis_pelanggaran';
    protected $primaryKey = 'id_jenis_pelanggaran';
    public $timestamps = false;

    protected $fillable = [
        'jenis_pelanggaran',
        'poin',
    ];

    protected $casts = [
        'poin' => 'integer',
    ];

    // Relations
    public function pelanggaran()
    {
        return $this->hasMany(Pelanggaran::class, 'id_jenis_pelanggaran', 'id_jenis_pelanggaran');
    }
}
