<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TagihanPembayaran extends Model
{
    use HasFactory;

    protected $table = 'tagihan_pembayaran';

    protected $fillable = [
        'id_sekolah',
        'trx_id',
        'nis',
        'id_kelas',
        'id_tahun',
        'id_semester',
        'kode_tagihan',
        'jenis_tagihan',
        'nama_tagihan',
        'nomor_va',
        'nominal',
        'nominal_terbayar',
        'status',
        'tanggal_tagihan',
        'tanggal_jatuh_tempo',
        'tanggal_bayar',
        'keterangan',
    ];

    protected $casts = [
        'nominal' => 'decimal:2',
        'nominal_terbayar' => 'decimal:2',
        'tanggal_tagihan' => 'date',
        'tanggal_jatuh_tempo' => 'date',
        'tanggal_bayar' => 'datetime',
    ];

    /**
     * Relationship to Siswa
     */
    public function siswa()
    {
        return $this->belongsTo(UserSiswa::class, 'nis', 'nis');
    }

    /**
     * Relationship to Kelas
     */
    public function kelas()
    {
        return $this->belongsTo(Kelas::class, 'id_kelas', 'id_kelas');
    }

    /**
     * Relationship to Tahun Ajaran
     */
    public function tahunAjaran()
    {
        return $this->belongsTo(TahunAjaran::class, 'id_tahun', 'id_tahun');
    }

    /**
     * Relationship to Semester
     */
    public function semester()
    {
        return $this->belongsTo(Semester::class, 'id_semester', 'id_semester');
    }

    /**
     * Get Sisa Pembayaran (Nominal Tagihan - Nominal Terbayar)
     */
    public function getSisaPembayaranAttribute()
    {
        $sisa = $this->nominal - $this->nominal_terbayar;
        return $sisa > 0 ? $sisa : 0;
    }

    /**
     * Generate 19-digit TRX ID
     * Example: 2026073115523600577 (YYYYMMDDHHMMSS + 5 digits random sequence)
     */
    public static function generateTrxId()
    {
        return date('YmdHis') . str_pad(mt_rand(1, 99999), 5, '0', STR_PAD_LEFT);
    }

    /**
     * Generate Virtual Account Number based on:
     * Formula: id_institusi + tahun + '0' + nis + kode_tagihan
     * Example: 9990029 + 2026 + 014008 + 02 = 9990029202601400802
     */
    public static function generateVaNumber($idInstitusi, $tahun, $nis, $kodeTagihan, $formatTahun = 'YYYY', $addLeadingZeroNis = true)
    {
        // Extract year: if format is YY and input is 2026, take 26, otherwise full year 2026
        $yearFormatted = $tahun;
        if (strlen($tahun) === 4 && $formatTahun === 'YY') {
            $yearFormatted = substr($tahun, 2);
        }

        // Clean NIS to numeric string
        $cleanNis = preg_replace('/[^0-9]/', '', (string)$nis);

        // Format NIS with leading '0' if requested or not already prefixed
        if ($addLeadingZeroNis) {
            $nisFormatted = '0' . ltrim($cleanNis, '0');
        } else {
            $nisFormatted = $cleanNis;
        }

        // Ensure 2-digit billing code
        $kodeFormatted = str_pad((string)$kodeTagihan, 2, '0', STR_PAD_LEFT);

        return (string)$idInstitusi . (string)$yearFormatted . (string)$nisFormatted . (string)$kodeFormatted;
    }
}
