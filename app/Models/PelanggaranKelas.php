<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PelanggaranKelas extends Model
{
    protected $table      = 'pelanggaran_kelas';
    protected $primaryKey = 'id_pelanggaran_kelas';

    protected $fillable = [
        'tanggal',
        'nis',
        'id_kelas',
        'jenis_pelanggaran',
        'keterangan',
        'id_guru',
    ];

    protected $casts = [
        'tanggal'           => 'date',
        'id_kelas'          => 'integer',
        'jenis_pelanggaran' => 'integer',
        'id_guru'           => 'integer',
    ];

    /**
     * Daftar jenis pelanggaran beserta deskripsi dan pembinaan.
     */
    public static function daftarJenis(): array
    {
        return [
            1 => [
                'label'     => 'HP dikumpulkan selama pembelajaran',
                'pembinaan' => 'HP di ambil Guru (ambil di BK) pulang habis ashar',
            ],
            2 => [
                'label'     => 'Menginstal game',
                'pembinaan' => 'Hapus game dan keliling lapangan 3x',
            ],
            3 => [
                'label'     => 'Merubah wallpaper',
                'pembinaan' => 'Mengembalikan wallpaper awal & membersihkan lab',
            ],
            4 => [
                'label'     => 'Tidak mengikuti tadarus (masuk max 07.10)',
                'pembinaan' => 'Tadarus sendiri di depan',
            ],
            5 => [
                'label'     => 'Mengobrol ketika guru berbicara / misuh',
                'pembinaan' => 'Push up 5x',
            ],
            6 => [
                'label'     => 'Terlambat (masuk setelah berdoa mulai, max 07.10)',
                'pembinaan' => 'Push up 5x, meningkat kelipatan 5x',
            ],
            7 => [
                'label'     => 'Seragam tidak sesuai',
                'pembinaan' => 'Push up 10x (Senin: Putih abu, Selasa: Wear pack, Rabu: Batik, Kamis: Putih abu, Jumat: Muslim bebas/wear pack)',
            ],
            8 => [
                'label'     => 'Makan di dalam Lab saat KBM ataupun istirahat',
                'pembinaan' => 'Bersihkan lab',
            ],
        ];
    }

    // ── Relations ──────────────────────────────────────────────────

    public function siswa()
    {
        return $this->belongsTo(UserSiswa::class, 'nis', 'nis');
    }

    public function kelas()
    {
        return $this->belongsTo(Kelas::class, 'id_kelas', 'id_kelas');
    }

    public function guru()
    {
        return $this->belongsTo(Guru::class, 'id_guru', 'id_guru');
    }

    // ── Accessors ──────────────────────────────────────────────────

    public function getLabelPelanggaranAttribute(): string
    {
        return self::daftarJenis()[$this->jenis_pelanggaran]['label'] ?? "Pelanggaran #{$this->jenis_pelanggaran}";
    }

    public function getPembinaanAttribute(): string
    {
        return self::daftarJenis()[$this->jenis_pelanggaran]['pembinaan'] ?? '-';
    }
}
