<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LmsKuisToken extends Model
{
    protected $table = 'lms_kuis_token';
    protected $primaryKey = 'id_token';

    protected $fillable = [
        'id_tugas',
        'id_guru',
        'token',
        'is_aktif',
        'expired_at',
    ];

    protected $casts = [
        'id_tugas'   => 'integer',
        'id_guru'    => 'integer',
        'is_aktif'   => 'boolean',
        'expired_at' => 'datetime',
    ];

    public function tugas()
    {
        return $this->belongsTo(LmsTugas::class, 'id_tugas', 'id_tugas');
    }

    public function guru()
    {
        return $this->belongsTo(Guru::class, 'id_guru', 'id_guru');
    }

    /**
     * Cek apakah token masih valid (aktif & belum expire)
     */
    public function isValid(): bool
    {
        if (!$this->is_aktif) {
            return false;
        }
        if ($this->expired_at && now()->isAfter($this->expired_at)) {
            return false;
        }
        return true;
    }
}
