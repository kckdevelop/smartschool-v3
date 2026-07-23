<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kelas extends Model
{
    protected $table = 'kelas';
    protected $primaryKey = 'id_kelas';
    public $timestamps = false;

    protected $fillable = [
        'tahun_masuk',
        'tingkat',
        'id_jurusan',
        'rombel',
        'walikelas',
        'status',
    ];

    protected $casts = [
        'tingkat'    => 'integer',
        'id_jurusan' => 'integer',
        'walikelas'  => 'integer',
    ];

    protected $appends = [
        'nama_kelas',
    ];

    public function getNamaKelasAttribute()
    {
        return $this->tingkat . ' ' . $this->rombel;
    }

    // Relations
    public function jurusan()
    {
        return $this->belongsTo(Jurusan::class, 'id_jurusan', 'id_jurusan');
    }

    public function guru()
    {
        return $this->belongsTo(Guru::class, 'walikelas', 'id_guru');
    }

    public function siswa()
    {
        return $this->hasMany(UserSiswa::class, 'id_kelas', 'id_kelas');
    }

    public function kemajuan()
    {
        return $this->hasMany(Kemajuan::class, 'id_kelas', 'id_kelas');
    }

    public function tugas()
    {
        return $this->hasMany(Tugas::class, 'id_kelas', 'id_kelas');
    }

    public function btaq()
    {
        return $this->hasMany(Btaq::class, 'id_kelas', 'id_kelas');
    }

    public function tadarus()
    {
        return $this->hasMany(Tadarus::class, 'id_kelas', 'id_kelas');
    }
}
