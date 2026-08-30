<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Sekolah;
use App\Models\UserSiswa;
use App\Http\Controllers\WaPresensiController;
use App\Services\FonnteService;
use Carbon\Carbon;

class SendWaPresensiHarian extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'wa:send-presensi-harian {--tanggal= : Tanggal presensi YYYY-MM-DD} {--force : Tetap jalankan di akhir pekan (Sabtu/Minggu)}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Kirim otomatis laporan presensi siswa harian ke WhatsApp orang tua pukul 09:00 WIB (Senin - Jumat)';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $tanggal = $this->option('tanggal') ?: Carbon::today()->toDateString();
        $dateObj = Carbon::parse($tanggal);

        $this->info("=== PROSES PENGIRIMAN WA PRESENSI SISWA HARIAN ({$tanggal}) ===");

        // Pengecekan akhir pekan (Sabtu / Minggu)
        if ($dateObj->isWeekend() && !$this->option('force')) {
            $this->warn("Pengiriman dilewati karena tanggal {$tanggal} adalah hari akhir pekan ({$dateObj->translatedFormat('l')}).");
            return 0;
        }

        $sekolah = Sekolah::first();
        if (!$sekolah) {
            $this->error("Data sekolah tidak ditemukan.");
            return 1;
        }

        if ($sekolah->wa_status !== 'aktif') {
            $this->warn("Pengiriman dibatalkan: WhatsApp Gateway (Fonnte) sedang tidak aktif di Pengaturan Sekolah.");
            return 1;
        }

        if (empty($sekolah->wa_token)) {
            $this->error("Pengiriman dibatalkan: Token WhatsApp (Fonnte) belum dikonfigurasi.");
            return 1;
        }

        $template = $sekolah->wa_template_presensi;
        if (empty($template)) {
            $this->error("Pengiriman dibatalkan: Template pesan WA presensi belum diisi.");
            return 1;
        }

        $siswaList = UserSiswa::where('status', 'aktif')
            ->with(['detail', 'kelas.jurusan'])
            ->orderBy('id_kelas')
            ->orderBy('nama_siswa')
            ->get();

        if ($siswaList->isEmpty()) {
            $this->info("Tidak ada data siswa aktif yang ditemukan.");
            return 0;
        }

        $fonnteService = new FonnteService();
        $bar = $this->output->createProgressBar($siswaList->count());
        $bar->start();

        $terkirim = 0;
        $gagal = 0;
        $dilompati = 0;

        foreach ($siswaList as $siswa) {
            $res = WaPresensiController::processSendSingleStudent($siswa, $tanggal, $template, $sekolah, $fonnteService);
            if ($res['status'] === 'terkirim') {
                $terkirim++;
            } elseif ($res['status'] === 'gagal') {
                $gagal++;
            } else {
                $dilompati++;
            }
            $bar->advance();
        }

        $bar->finish();
        $this->newLine(2);

        $this->info("Selesai diproses!");
        $this->table(
            ['Ringkasan Status', 'Jumlah Siswa'],
            [
                ['Total Siswa Diproses', $siswaList->count()],
                ['Terkirim Sukses', $terkirim],
                ['Gagal Dikirim', $gagal],
                ['Dilompati (Tanpa WA)', $dilompati],
            ]
        );

        return 0;
    }
}
