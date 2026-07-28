<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;

class Guru extends Authenticatable
{
    use HasApiTokens;

    protected $table = 'guru';
    protected $primaryKey = 'id_guru';
    public $timestamps = false;

    protected $fillable = [
        'no_id',
        'nama_guru',
        'jenkel',
        'no_hp',
        'kecamatan',
        'kabupaten',
        'guru_bk',
        'guru_ismuba',
        'status',
        'password',
        'foto',
    ];

    protected $hidden = [
        'password',
    ];

    // Accessor: URL lengkap foto guru
    public function getFotoUrlAttribute(): ?string
    {
        return $this->foto ? asset('storage/' . $this->foto) : null;
    }

    protected $appends = ['foto_url'];

    protected $casts = [
        'no_id' => 'integer',
    ];

    // Relations
    public function kelas()
    {
        return $this->hasMany(Kelas::class, 'walikelas', 'id_guru');
    }

    public function btaq()
    {
        return $this->hasMany(Btaq::class, 'id_guru', 'id_guru');
    }

    public function kemajuan()
    {
        return $this->hasMany(Kemajuan::class, 'id_guru', 'id_guru');
    }

    public function tadarus()
    {
        return $this->hasMany(Tadarus::class, 'id_guru', 'id_guru');
    }

    public function tugas()
    {
        return $this->hasMany(Tugas::class, 'id_guru', 'id_guru');
    }

    public function riwayatPoin()
    {
        return $this->hasMany(RiwayatPoin::class, 'id_guru', 'id_guru');
    }

    public function riwayatReward()
    {
        return $this->hasMany(RiwayatReward::class, 'id_guru', 'id_guru');
    }

    public function kehadiranPengajian()
    {
        return $this->hasMany(KehadiranPengajian::class, 'id_guru', 'id_guru');
    }

    public function pelanggaranKelas()
    {
        return $this->hasMany(PelanggaranKelas::class, 'id_guru', 'id_guru');
    }

    public function checkups()
    {
        return $this->hasMany(DataCheckupGukar::class, 'id_guru', 'id_guru');
    }

    public function kunjunganUks()
    {
        return $this->hasMany(KunjunganUksGukar::class, 'id_guru', 'id_guru');
    }
}
