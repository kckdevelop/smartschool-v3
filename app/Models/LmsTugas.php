<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LmsTugas extends Model
{
    protected $table = 'lms_tugas';
    protected $primaryKey = 'id_tugas';

    protected $fillable = [
        'id_kursus',
        'judul',
        'deskripsi',
        'tenggat',
        'tipe',
        'file_path',
        'is_published',
    ];

    protected $casts = [
        'id_kursus'    => 'integer',
        'tenggat'      => 'datetime',
        'is_published' => 'boolean',
    ];

    public function getFileUrlAttribute(): ?string
    {
        return $this->file_path ? asset('storage/' . $this->file_path) : null;
    }

    protected $appends = ['file_url'];

    // Relations
    public function kursus()
    {
        return $this->belongsTo(LmsKursus::class, 'id_kursus', 'id_kursus');
    }

    public function pengumpulan()
    {
        return $this->hasMany(LmsPengumpulan::class, 'id_tugas', 'id_tugas');
    }

    public function soal()
    {
        return $this->hasMany(LmsSoal::class, 'id_tugas', 'id_tugas')->orderBy('nomor_soal', 'asc');
    }
}
