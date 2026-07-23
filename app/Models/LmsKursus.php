<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LmsKursus extends Model
{
    protected $table = 'lms_kursus';
    protected $primaryKey = 'id_kursus';

    protected $fillable = [
        'nama_kursus',
        'id_kelas',
        'id_guru',
    ];

    protected $casts = [
        'id_kelas' => 'integer',
        'id_guru'  => 'integer',
    ];

    // Relations
    public function kelas()
    {
        return $this->belongsTo(Kelas::class, 'id_kelas', 'id_kelas');
    }

    public function guru()
    {
        return $this->belongsTo(Guru::class, 'id_guru', 'id_guru');
    }

    public function tugas()
    {
        return $this->hasMany(LmsTugas::class, 'id_kursus', 'id_kursus');
    }
}
