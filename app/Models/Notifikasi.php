<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Carbon\Carbon;

class Notifikasi extends Model
{
    protected $table = 'notifikasi';
    protected $primaryKey = 'id_notifikasi';

    protected $fillable = [
        'id_guru',
        'id_user',
        'role',
        'judul',
        'pesan',
        'tipe',
        'referensi_id',
        'data',
        'is_read',
        'read_at',
    ];

    protected $casts = [
        'id_notifikasi' => 'integer',
        'id_guru'       => 'integer',
        'id_user'       => 'integer',
        'referensi_id'  => 'integer',
        'data'          => 'array',
        'is_read'       => 'boolean',
        'read_at'       => 'datetime',
        'created_at'    => 'datetime',
        'updated_at'    => 'datetime',
    ];

    protected $appends = [
        'formatted_time',
    ];

    /**
     * Relasi ke data Guru.
     */
    public function guru(): BelongsTo
    {
        return $this->belongsTo(Guru::class, 'id_guru', 'id_guru');
    }

    /**
     * Scope notifikasi belum dibaca.
     */
    public function scopeUnread($query)
    {
        return $query->where('is_read', false);
    }

    /**
     * Scope notifikasi untuk guru tertentu.
     */
    public function scopeForGuru($query, int $idGuru)
    {
        return $query->where('id_guru', $idGuru);
    }

    /**
     * Tandai notifikasi sebagai sudah dibaca.
     */
    public function markAsRead(): bool
    {
        if (!$this->is_read) {
            return $this->update([
                'is_read' => true,
                'read_at' => Carbon::now(),
            ]);
        }
        return true;
    }

    /**
     * Format waktu yang ramah pengguna.
     */
    public function getFormattedTimeAttribute(): string
    {
        if (!$this->created_at) {
            return '';
        }
        return $this->created_at->locale('id')->diffForHumans();
    }
}
