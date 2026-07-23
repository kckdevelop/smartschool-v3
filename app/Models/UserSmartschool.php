<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;

class UserSmartschool extends Authenticatable
{
    use HasApiTokens;

    protected $table = 'user_smartschool';
    protected $primaryKey = 'id_user';
    public $timestamps = false;

    protected $fillable = [
        'username',
        'password',
        'nama_lengkap',
        'level',
    ];

    protected $hidden = [
        'password',
    ];

    /**
     * Daftar role resmi di SmartSchool
     */
    public const ROLES = [
        'super_admin'     => 'Super Admin',
        'admin_kurikulum' => 'Admin Data dan Kurikulum',
        'guru_bk'         => 'Guru BK',
        'petugas_uks'     => 'Petugas UKS',
        'admin_ismuba'    => 'Admin ISMUBA',
        'admin_pkl'       => 'Admin PKL',
    ];

    /**
     * Key-value mapping lengkap role label termasuk legacy/fallback values
     */
    public static function getRoleLabels(): array
    {
        return [
            'super_admin'     => 'Super Admin',
            'admin_kurikulum' => 'Admin Data dan Kurikulum',
            'admin'           => 'Admin Data dan Kurikulum',
            'guru_bk'         => 'Guru BK',
            'bk'              => 'Guru BK',
            'petugas_uks'     => 'Petugas UKS',
            'uks'             => 'Petugas UKS',
            'admin_ismuba'    => 'Admin ISMUBA',
            'ismuba'          => 'Admin ISMUBA',
            'admin_pkl'       => 'Admin PKL',
            'pkl'             => 'Admin PKL',
        ];
    }

    /**
     * Accessor label role yang rapi dan dapat dibaca manusia
     */
    public function getRoleLabelAttribute(): string
    {
        $labels = self::getRoleLabels();
        return $labels[$this->level] ?? ucfirst(str_replace('_', ' ', $this->level ?? ''));
    }

    /**
     * Badge class CSS untuk tampilan tabel
     */
    public function getRoleBadgeClassAttribute(): string
    {
        return match ($this->level) {
            'super_admin'               => 'badge-role-super',
            'admin_kurikulum', 'admin' => 'badge-role-kurikulum',
            'guru_bk', 'bk'             => 'badge-role-bk',
            'petugas_uks', 'uks'        => 'badge-role-uks',
            'admin_ismuba', 'ismuba'    => 'badge-role-ismuba',
            'admin_pkl', 'pkl'          => 'badge-role-pkl',
            default                     => 'badge-role-default',
        };
    }
}


