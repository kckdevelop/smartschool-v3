<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sekolah extends Model
{
    protected $table = 'sekolah';
    protected $primaryKey = 'id_sekolah';
    public $timestamps = false;

    protected $fillable = [
        'npsn',
        'nama_sekolah',
        'kepala_sekolah',
        'nip',
        'status',
        'alamat_sekolah',
        'kota',
        'logo',
        'kop',
        'ttd_kepala_sekolah',
        'ijin',
        'edit_detail_siswa',
        'sync_otomatis',
        'sync_interval',
        'sync_time',
        'jadwal_aktif',
        'llm_provider',
        'llm_api_key',
        'llm_model',
        'groq_key',
        'groq_status',
        'groq_model',
        'groq_quota',
        'gemini_key',
        'gemini_status',
        'gemini_model',
        'gemini_quota',
        'wa_token',
        'wa_status',
    ];


    protected $casts = [
        'npsn'              => 'integer',
        'edit_detail_siswa' => 'boolean',
        'sync_otomatis'     => 'boolean',
        'groq_quota'        => 'integer',
        'gemini_quota'      => 'integer',
    ];
}
