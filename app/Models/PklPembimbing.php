<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PklPembimbing extends Model
{
    protected $table = 'pkl_pembimbing';
    protected $primaryKey = 'id_pembimbing';

    protected $fillable = ['id_gelombang', 'id_guru', 'id_dudi'];

    public function gelombang()
    {
        return $this->belongsTo(PklGelombang::class, 'id_gelombang', 'id_gelombang');
    }

    public function guru()
    {
        return $this->belongsTo(Guru::class, 'id_guru', 'id_guru');
    }

    public function dudi()
    {
        return $this->belongsTo(PklDudi::class, 'id_dudi', 'id_dudi');
    }

    public function penempatan()
    {
        return $this->hasMany(PklPenempatan::class, 'id_pembimbing', 'id_pembimbing');
    }
}
