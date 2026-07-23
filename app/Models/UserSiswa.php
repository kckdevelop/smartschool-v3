<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;

class UserSiswa extends Authenticatable
{
    use HasApiTokens;

    protected $table = 'user_siswa';
    protected $primaryKey = 'nis';
    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = [
        'nis',
        'nisn',
        'nik',
        'password',
        'password_wali',
        'id_kelas',
        'nama_siswa',
        'jenkel',
        'tempat_lahir',
        'tgl_lahir',
        'kelengkapan',
        'status',
    ];

    protected $hidden = [
        'password',
        'password_wali',
    ];

    protected $casts = [
        'nis'         => 'integer',
        'id_kelas'    => 'integer',
        'tgl_lahir'   => 'date',
        'kelengkapan' => 'integer',
    ];

    // Relations
    public function detail()
    {
        return $this->hasOne(DetailSiswa::class, 'nis', 'nis');
    }

    public function kelas()
    {
        return $this->belongsTo(Kelas::class, 'id_kelas', 'id_kelas');
    }

    public function presensi()
    {
        return $this->hasMany(Presensi::class, 'nis', 'nis');
    }

    public function logAbsensi()
    {
        return $this->hasMany(LogAbsensi::class, 'nis', 'nis');
    }

    public function btaq()
    {
        return $this->hasMany(Btaq::class, 'nis', 'nis');
    }

    public function kesehatan()
    {
        return $this->hasMany(Kesehatan::class, 'nis', 'nis');
    }

    public function kunjunganUks()
    {
        return $this->hasMany(KunjunganUks::class, 'nis', 'nis');
    }

    public function riwayatKesehatan()
    {
        return $this->hasMany(RiwayatKesehatan::class, 'nis', 'nis');
    }

    public function riwayatPoin()
    {
        return $this->hasMany(RiwayatPoin::class, 'nis', 'nis');
    }

    public function riwayatReward()
    {
        return $this->hasMany(RiwayatReward::class, 'nis', 'nis');
    }

    public function dataCheckup()
    {
        return $this->hasMany(DataCheckup::class, 'nis', 'nis');
    }

    public function tagihan()
    {
        return $this->hasMany(TagihanTugas::class, 'nis', 'nis');
    }

    public function bimbinganKonseling()
    {
        return $this->hasMany(BimbinganKonseling::class, 'nis', 'nis');
    }

    public function pantauIbadah()
    {
        return $this->hasMany(PantauIbadah::class, 'nis', 'nis');
    }
}
