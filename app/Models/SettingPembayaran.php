<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SettingPembayaran extends Model
{
    use HasFactory;

    protected $table = 'setting_pembayaran';

    protected $fillable = [
        'id_sekolah',
        'id_institusi',
        'url_report_va',
        'username_maker',
        'password_maker',
        'mode_api',
        'format_tahun',
        'kode_spp',
        'kode_non_spp',
        'kode_tunggakan',
        'status_api',
        'session_cookie',
        'cookie_updated_at',
    ];

    protected $casts = [
        'cookie_updated_at' => 'datetime',
    ];

    /**
     * Apakah session cookie masih segar (disimpan < 8 jam lalu).
     */
    public function isCookieFresh(): bool
    {
        if (empty($this->session_cookie)) return false;
        if (empty($this->cookie_updated_at)) return false;
        return $this->cookie_updated_at->diffInHours(now()) < 8;
    }

    /**
     * Get or create default configuration.
     */
    public static function getSetting()
    {
        $setting = self::first();
        if (!$setting) {
            $setting = self::create([
                'id_institusi' => '9990029',

                'url_report_va' => 'https://va.bpddiy.co.id/admin/report/tagihan_va',
                'username_maker' => 'USERMAKER',
                'password_maker' => '#Musaba888',
                'mode_api' => 'production',
                'format_tahun' => 'YYYY',
                'kode_spp' => '00',
                'kode_non_spp' => '01',
                'kode_tunggakan' => '02',
                'status_api' => 'aktif',
            ]);
        }
        return $setting;
    }
}
