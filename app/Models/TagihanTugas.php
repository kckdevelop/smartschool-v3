<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TagihanTugas extends Model
{
    protected $table = 'tagihan_tugas';
    protected $primaryKey = 'id_tagihan';
    public $timestamps = false;

    protected $fillable = [
        'id_tugas',
        'nis',
        'deskripsi',
        'upload_tugas',
        'status_tugas',
    ];

    protected $casts = [
        'id_tugas' => 'integer',
        'nis'      => 'integer',
    ];

    // Relations
    public function tugas()
    {
        return $this->belongsTo(Tugas::class, 'id_tugas', 'id_tugas');
    }

    public function siswa()
    {
        return $this->belongsTo(UserSiswa::class, 'nis', 'nis');
    }
}
