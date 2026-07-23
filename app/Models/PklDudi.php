<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PklDudi extends Model
{
    protected $table = 'pkl_dudi';
    protected $primaryKey = 'id_dudi';

    protected $fillable = [
        'id_jurusan',
        'nama_dudi', 'bidang_usaha', 'alamat', 'kota',
        'kecamatan', 'kabupaten',
        'no_telepon', 'email', 'nama_pic', 'jabatan_pic',
        'no_hp_pic', 'kuota_siswa', 'status',
    ];

    public function jurusan()
    {
        return $this->belongsTo(Jurusan::class, 'id_jurusan', 'id_jurusan');
    }

    public function penempatan()
    {
        return $this->hasMany(PklPenempatan::class, 'id_dudi', 'id_dudi');
    }

    public function pembimbing()
    {
        return $this->hasMany(PklPembimbing::class, 'id_dudi', 'id_dudi');
    }

    // Hitung sisa kuota untuk gelombang tertentu
    public function sisaKuota(int $idGelombang): int
    {
        $terpakai = $this->penempatan()
            ->where('id_gelombang', $idGelombang)
            ->whereIn('status', ['aktif', 'selesai'])
            ->count();
        return max(0, $this->kuota_siswa - $terpakai);
    }
}
