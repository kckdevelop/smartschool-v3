<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PklKelasGelombang extends Model
{
    protected $table = 'pkl_kelas_gelombang';

    protected $fillable = ['id_gelombang', 'id_kelas'];

    public function gelombang()
    {
        return $this->belongsTo(PklGelombang::class, 'id_gelombang', 'id_gelombang');
    }

    public function kelas()
    {
        return $this->belongsTo(Kelas::class, 'id_kelas', 'id_kelas');
    }
}
