<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SuratPemberitahuan extends Model
{
    protected $table = 'surat_pemberitahuan';
    protected $primaryKey = 'id_pemberitahuan';

    protected $fillable = [
        'no_surat',
        'tanggal_surat',
        'nis',
        'nama_ortu',
        'no_hp_ortu',
        'alasan_pemberitahuan',
        'tindakan_sekolah',
        'status',
        'id_panggil_sp1',
        'id_guru',
    ];

    protected $casts = [
        'tanggal_surat' => 'date',
        'id_guru'       => 'integer',
        'id_panggil_sp1'=> 'integer',
    ];

    public function guru()
    {
        return $this->belongsTo(Guru::class, 'id_guru', 'id_guru');
    }

    public function siswa()
    {
        return $this->belongsTo(UserSiswa::class, 'nis', 'nis');
    }

    public function sp1()
    {
        return $this->belongsTo(PanggilOrtu::class, 'id_panggil_sp1', 'id_panggil');
    }
}
