<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RiwayatReward extends Model
{
    protected $table = 'riwayat_reward';
    protected $primaryKey = 'id_reward';
    public $timestamps = false;

    protected $fillable = [
        'tgl_input',
        'nis',
        'tingkat',
        'reward',
        'point_reward',
        'id_guru',
    ];

    protected $casts = [
        'tgl_input'    => 'date',
        'nis'          => 'integer',
        'tingkat'      => 'integer',
        'point_reward' => 'integer',
        'id_guru'      => 'integer',
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
