<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;

class Karyawan extends Authenticatable
{
    use HasApiTokens;

    protected $table = 'karyawan';
    protected $primaryKey = 'id_karyawan';
    public $timestamps = false;

    protected $fillable = [
        'no_id',
        'nama_karyawan',
        'jenkel',
        'status',
        'petugas_uks',
        'password',
        'foto',
    ];

    protected $hidden = [
        'password',
    ];

    // Accessor: URL lengkap foto karyawan
    public function getFotoUrlAttribute(): ?string
    {
        return $this->foto ? asset('storage/' . $this->foto) : null;
    }

    protected $appends = ['foto_url'];

    protected $casts = [
        'no_id' => 'integer',
    ];

    public function kehadiranPengajian()
    {
        return $this->hasMany(KehadiranPengajian::class, 'id_karyawan', 'id_karyawan');
    }

    public function checkups()
    {
        return $this->hasMany(DataCheckupGukar::class, 'id_karyawan', 'id_karyawan');
    }
}
