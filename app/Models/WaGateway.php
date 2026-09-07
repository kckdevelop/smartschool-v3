<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WaGateway extends Model
{
    use HasFactory;

    protected $table = 'wa_gateways';

    protected $fillable = [
        'nama',
        'token',
        'provider',
        'nomor_wa',
        'keterangan',
        'status',
    ];

    /**
     * Scope query to active gateways only.
     */
    public function scopeAktif($query)
    {
        return $query->where('status', 'aktif');
    }

    /**
     * Get masked token for safe display (e.g. "abcd...1234").
     */
    public function getMaskedTokenAttribute(): string
    {
        if (empty($this->token)) {
            return '—';
        }

        $len = strlen($this->token);
        if ($len <= 8) {
            return str_repeat('*', $len);
        }

        return substr($this->token, 0, 4) . str_repeat('•', max(4, $len - 8)) . substr($this->token, -4);
    }
}
