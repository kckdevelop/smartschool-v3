<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LmsKuisSesi extends Model
{
    protected $table = 'lms_kuis_sesi';
    protected $primaryKey = 'id_sesi';

    protected $fillable = [
        'id_tugas',
        'nis',
        'id_token',
        'percobaan_ke',
        'urutan_soal',
        'urutan_pilihan',
        'waktu_mulai',
        'waktu_selesai',
        'nilai',
        'status',
    ];

    protected $casts = [
        'id_tugas'       => 'integer',
        'nis'            => 'integer',
        'id_token'       => 'integer',
        'percobaan_ke'   => 'integer',
        'urutan_soal'    => 'array',
        'urutan_pilihan' => 'array',
        'waktu_mulai'    => 'datetime',
        'waktu_selesai'  => 'datetime',
        'nilai'          => 'integer',
    ];

    public function tugas()
    {
        return $this->belongsTo(LmsTugas::class, 'id_tugas', 'id_tugas');
    }

    public function siswa()
    {
        return $this->belongsTo(UserSiswa::class, 'nis', 'nis');
    }

    public function token()
    {
        return $this->belongsTo(LmsKuisToken::class, 'id_token', 'id_token');
    }

    public function jawaban()
    {
        return $this->hasMany(LmsKuisJawaban::class, 'id_sesi', 'id_sesi');
    }

    /**
     * Hitung sisa waktu dalam detik. Null jika tanpa batas.
     */
    public function sisaWaktuDetik(?int $durasiMenit): ?int
    {
        if (!$durasiMenit || $durasiMenit === 0) {
            return null; // tanpa batas waktu
        }
        if (!$this->waktu_mulai) {
            return $durasiMenit * 60;
        }
        $batasWaktu  = $this->waktu_mulai->copy()->addMinutes($durasiMenit);
        $selisih     = now()->diffInSeconds($batasWaktu, false);
        return max(0, $selisih);
    }
}
