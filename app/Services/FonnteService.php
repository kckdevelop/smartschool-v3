<?php

namespace App\Services;

use App\Models\Sekolah;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class FonnteService
{
    protected ?string $token;
    protected string $status;

    public function __construct()
    {
        $sekolah = Sekolah::first();
        $this->token = $sekolah->wa_token ?? null;
        $this->status = $sekolah->wa_status ?? 'nonaktif';
    }

    /**
     * Send a WhatsApp message.
     *
     * @param string $target Recipient number (e.g. 0812345678 or 62812345678)
     * @param string $message The message body
     * @return array{success: bool, message: string, data?: array}
     */
    public function sendMessage(string $target, string $message): array
    {
        if ($this->status !== 'aktif') {
            return [
                'success' => false,
                'message' => 'WhatsApp Gateway (Fonnte) sedang tidak aktif. Silakan aktifkan di Pengaturan.'
            ];
        }

        if (empty($this->token)) {
            return [
                'success' => false,
                'message' => 'Token WhatsApp (Fonnte) belum dikonfigurasi.'
            ];
        }

        try {
            $response = Http::withHeaders([
                'Authorization' => $this->token,
            ])->post('https://api.fonnte.com/send', [
                'target'      => $target,
                'message'     => $message,
                'countryCode' => '62',
            ]);

            $result = $response->json();

            if ($response->successful() && isset($result['status']) && $result['status'] === true) {
                return [
                    'success' => true,
                    'message' => 'Pesan berhasil dikirim.',
                    'data'    => $result,
                ];
            }

            return [
                'success' => false,
                'message' => $result['reason'] ?? 'Gagal mengirim pesan via Fonnte.',
                'data'    => $result,
            ];
        } catch (\Exception $e) {
            Log::error('Fonnte sendMessage error: ' . $e->getMessage());
            return [
                'success' => false,
                'message' => 'Terjadi kesalahan saat mengirim pesan: ' . $e->getMessage(),
            ];
        }
    }

    /**
     * Check Fonnte API key / device details.
     *
     * @return array{success: bool, message: string, device?: array}
     */
    public function checkDeviceStatus(): array
    {
        if (empty($this->token)) {
            return [
                'success' => false,
                'message' => 'Token WhatsApp (Fonnte) kosong.'
            ];
        }

        try {
            $response = Http::withHeaders([
                'Authorization' => $this->token,
            ])->post('https://api.fonnte.com/device');

            $result = $response->json();

            if ($response->successful() && isset($result['status']) && $result['status'] === true) {
                return [
                    'success' => true,
                    'message' => 'Koneksi ke Fonnte berhasil.',
                    'device'  => $result,
                ];
            }

            return [
                'success' => false,
                'message' => $result['reason'] ?? 'Token Fonnte tidak valid atau perangkat tidak terhubung.',
                'data'    => $result,
            ];
        } catch (\Exception $e) {
            Log::error('Fonnte checkDeviceStatus error: ' . $e->getMessage());
            return [
                'success' => false,
                'message' => 'Gagal menghubungkan ke server Fonnte: ' . $e->getMessage(),
            ];
        }
    }
}
