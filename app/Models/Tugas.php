<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tugas extends Model
{
    protected $table = 'tugas';
    protected $primaryKey = 'id_tugas';
    public $timestamps = false;

    protected $fillable = [
        'tanggal',
        'id_guru',
        'judul_tugas',
        'id_kelas',
        'deskripsi',
        'lampiran',
        'status',
    ];

    protected $casts = [
        'tanggal'  => 'date',
        'id_guru'  => 'integer',
        'id_kelas' => 'integer',
    ];

    // Relations
    public function guru()
    {
        return $this->belongsTo(Guru::class, 'id_guru', 'id_guru');
    }

    public function kelas()
    {
        return $this->belongsTo(Kelas::class, 'id_kelas', 'id_kelas');
    }

    public function tagihan()
    {
        return $this->hasMany(TagihanTugas::class, 'id_tugas', 'id_tugas');
    }
}
