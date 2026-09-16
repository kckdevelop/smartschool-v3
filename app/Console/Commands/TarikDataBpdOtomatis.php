<?php

namespace App\Console\Commands;

use App\Models\SettingPembayaran;
use App\Services\BpdDiyService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class TarikDataBpdOtomatis extends Command
{
    /**
     * Signature command Artisan
     */
    protected $signature = 'bpd:tarik-otomatis 
                            {--tahun= : Tahun VA yang ditarik (contoh: 2026)} 
                            {--status=semua : Filter status transaksi (semua, lunas, belum_bayar)} 
                            {--force : Jalankan tanpa meminta konfirmasi interaktif}';

    /**
     * Deskripsi command
     */
    protected $description = 'Tarik seluruh data tagihan dari portal BPD DIY secara otomatis, hapus seluruh data lama di database lokal, dan masukkan data baru.';

    /**
     * Eksekusi command
     */
    public function handle(): int
    {
        $this->info('===========================================================');
        $this->info('  SMARTSCHOOL — PENARIKAN OTOMATIS DATA TAGIHAN BPD DIY');
        $this->info('===========================================================');

        $setting = SettingPembayaran::getSetting();

        if ($setting->status_api !== 'aktif') {
            $this->error('❌ Status API BPD DIY pada Setting Konfigurasi sedang Nonaktif.');
            Log::warning('[TarikDataBpdOtomatis] Dibatalkan karena status_api nonaktif.');
            return self::FAILURE;
        }

        $tahun = trim((string) ($this->option('tahun') ?: date('Y')));
        $statusTransaksi = (string) ($this->option('status') ?: 'semua');
        $force = (bool) $this->option('force');

        $idInstitusi = $setting->id_institusi ?? '9990029';
        $prefixInstitusi = str_starts_with($idInstitusi, '999') ? $idInstitusi : ('999' . $idInstitusi);
        $vaPrefix = !empty($tahun) ? ($prefixInstitusi . $tahun) : '';

        $this->line("• Mitra ID      : <comment>{$idInstitusi}</comment>");
        $this->line("• Tahun VA      : <comment>{$tahun}</comment>");
        $this->line("• Awalan VA     : <comment>{$vaPrefix}</comment>");
        $this->line("• Filter Status : <comment>{$statusTransaksi}</comment>");
        $this->newLine();

        if (!$force) {
            $confirm = $this->confirm('⚠️ PERINGATAN: Seluruh data tagihan lama di database lokal akan DIHAPUS dan diganti dengan data baru dari BPD DIY. Lanjutkan?', true);
            if (!$confirm) {
                $this->warn('Operasi dibatalkan oleh pengguna.');
                return self::SUCCESS;
            }
        }

        $this->info('▶ Sedang menghubungi portal BPD DIY & menarik data tagihan VA...');

        $filters = [
            'periode_waktu'    => '',
            'status_transaksi' => $statusTransaksi,
            'parent_va'        => '',
            'mitra'            => $setting->id_institusi ?? '',
            'search'           => '',
            'length'           => 0,
            'tahun'            => $tahun,
            'va_prefix'        => $vaPrefix,
        ];

        try {
            $service = new BpdDiyService($setting);
            $result  = $service->pullAndReplaceDatabase($filters);

            if ($result['success']) {
                $this->newLine();
                $this->info('✅ ' . $result['message']);
                $this->table(
                    ['Keterangan', 'Jumlah / Nilai'],
                    [
                        ['Data Lama Dihapus', number_format($result['deleted_count'], 0, ',', '.') . ' baris'],
                        ['Data Baru Dimasukkan', number_format($result['inserted_count'], 0, ',', '.') . ' baris'],
                        ['Total Nilai Tagihan', 'Rp ' . number_format($result['summary']['total_nilai'] ?? 0, 0, ',', '.')],
                        ['Total Telah Dibayar', 'Rp ' . number_format($result['summary']['total_terbayar'] ?? 0, 0, ',', '.')],
                        ['Total Sisa Tagihan', 'Rp ' . number_format($result['summary']['total_sisa'] ?? 0, 0, ',', '.')],
                        ['Jumlah Tagihan Lunas', ($result['summary']['count_lunas'] ?? 0) . ' data'],
                        ['Jumlah Belum Lunas', ($result['summary']['count_belum'] ?? 0) . ' data'],
                    ]
                );

                Log::info("[TarikDataBpdOtomatis] Penarikan otomatis sukses. Deleted: {$result['deleted_count']}, Inserted: {$result['inserted_count']}");
                return self::SUCCESS;
            }

            $this->newLine();
            $this->error('❌ Gagal: ' . ($result['message'] ?? 'Terjadi kesalahan saat memproses data.'));
            Log::error("[TarikDataBpdOtomatis] Gagal: " . ($result['message'] ?? ''));
            return self::FAILURE;

        } catch (\Exception $e) {
            $this->newLine();
            $this->error('❌ Exception: ' . $e->getMessage());
            Log::error("[TarikDataBpdOtomatis] Exception: " . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
            ]);
            return self::FAILURE;
        }
    }
}
