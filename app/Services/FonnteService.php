<?php

namespace App\Services;

use App\Models\Sekolah;
use App\Models\WaGateway;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class FonnteService
{
    protected ?string $token;
    protected string $status;
    protected ?WaGateway $activeGateway = null;

    public function __construct(?WaGateway $gateway = null, ?string $explicitToken = null)
    {
        if ($explicitToken) {
            $this->token = $explicitToken;
            $this->status = 'aktif';
        } elseif ($gateway) {
            $this->activeGateway = $gateway;
            $this->token = $gateway->token;
            $this->status = $gateway->status;
        } else {
            // Pick a random active gateway from wa_gateways table
            $randomGateway = WaGateway::scopeAktif()->inRandomOrder()->first();
            if ($randomGateway) {
                $this->activeGateway = $randomGateway;
                $this->token = $randomGateway->token;
                $this->status = 'aktif';
            } else {
                // Fallback to legacy sekolah wa_token
                $sekolah = Sekolah::first();
                $this->token = $sekolah->wa_token ?? null;
                $this->status = $sekolah->wa_status ?? 'nonaktif';
            }
        }
    }

    /**
     * Get currently selected gateway model instance if any.
     */
    public function getGateway(): ?WaGateway
    {
        return $this->activeGateway;
    }

    /**
     * Send a WhatsApp message.
     *
     * @param string $target Recipient number (e.g. 0812345678 or 62812345678)
     * @param string $message The message body
     * @param WaGateway|string|null $gatewayOrToken Optional specific gateway model or token string
     * @return array{success: bool, message: string, data?: array, gateway_nama?: string}
     */
    public function sendMessage(string $target, string $message, $gatewayOrToken = null): array
    {
        $usedToken = $this->token;
        $usedStatus = $this->status;
        $gatewayNama = $this->activeGateway ? $this->activeGateway->nama : 'Default Gateway';

        if ($gatewayOrToken instanceof WaGateway) {
            $usedToken = $gatewayOrToken->token;
            $usedStatus = $gatewayOrToken->status;
            $gatewayNama = $gatewayOrToken->nama;
        } elseif (is_string($gatewayOrToken) && !empty($gatewayOrToken)) {
            $usedToken = $gatewayOrToken;
            $usedStatus = 'aktif';
            $gatewayNama = 'Custom Token';
        }

        if ($usedStatus !== 'aktif') {
            return [
                'success' => false,
                'message' => 'WhatsApp Gateway sedang tidak aktif. Silakan aktifkan di Pengaturan WhatsApp Gateway.',
                'gateway_nama' => $gatewayNama,
            ];
        }

        if (empty($usedToken)) {
            return [
                'success' => false,
                'message' => 'Token WhatsApp belum dikonfigurasi. Tambahkan API token di Pengaturan WhatsApp Gateway.',
                'gateway_nama' => $gatewayNama,
            ];
        }

        try {
            $response = Http::withHeaders([
                'Authorization' => $usedToken,
            ])->post('https://api.fonnte.com/send', [
                'target'      => $target,
                'message'     => $message,
                'countryCode' => '62',
            ]);

            $result = $response->json();

            if ($response->successful() && isset($result['status']) && $result['status'] === true) {
                return [
                    'success'      => true,
                    'message'      => 'Pesan berhasil dikirim.',
                    'gateway_nama' => $gatewayNama,
                    'data'         => $result,
                ];
            }

            return [
                'success'      => false,
                'message'      => $result['reason'] ?? 'Gagal mengirim pesan via Fonnte.',
                'gateway_nama' => $gatewayNama,
                'data'         => $result,
            ];
        } catch (\Exception $e) {
            Log::error("Fonnte sendMessage error (Gateway: {$gatewayNama}): " . $e->getMessage());
            return [
                'success'      => false,
                'message'      => 'Terjadi kesalahan saat mengirim pesan: ' . $e->getMessage(),
                'gateway_nama' => $gatewayNama,
            ];
        }
    }

    /**
     * Check Fonnte API key / device details for a given token string or current gateway.
     */
    public function checkDeviceStatus(?string $tokenString = null): array
    {
        $targetToken = $tokenString ?: $this->token;

        if (empty($targetToken)) {
            return [
                'success' => false,
                'message' => 'Token WhatsApp (Fonnte) kosong.'
            ];
        }

        try {
            $response = Http::withHeaders([
                'Authorization' => $targetToken,
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
