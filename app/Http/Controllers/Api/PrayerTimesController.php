<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Exception;

class PrayerTimesController extends Controller
{
    /**
     * Get prayer times for Bantul, Yogyakarta using Aladhan API.
     * Endpoint: GET /api/prayer-times/bantul
     */
    public function getBantulTimes()
    {
        try {
            $response = Http::timeout(10)->get('https://api.aladhan.com/v1/timingsByCity', [
                'city' => 'Bantul',
                'country' => 'Indonesia',
                'method' => 11, // Majlis Ugama Islam Singapura / Indonesia
            ]);

            if ($response->successful()) {
                $data = $response->json();
                if (isset($data['data'])) {
                    return response()->json([
                        'status' => 'success',
                        'data' => $data['data']
                    ]);
                }
            }

            Log::error('Aladhan API error: ' . $response->body());
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal mengambil jadwal sholat dari server publik.'
            ], 502);

        } catch (Exception $e) {
            Log::error('Prayer times fetch exception: ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Terjadi kesalahan saat menghubungi server jadwal sholat.'
            ], 500);
        }
    }
}
