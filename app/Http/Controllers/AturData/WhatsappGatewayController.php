<?php

namespace App\Http\Controllers\AturData;

use App\Http\Controllers\Controller;
use App\Models\Sekolah;
use App\Services\FonnteService;
use Illuminate\Http\Request;

class WhatsappGatewayController extends Controller
{
    /**
     * Tampilkan halaman pengaturan WhatsApp Gateway.
     */
    public function index()
    {
        $sekolah = Sekolah::first();
        return view('atur-data.whatsapp-gateway.index', compact('sekolah'));
    }

    /**
     * Update token dan status WhatsApp Gateway.
     */
    public function update(Request $request)
    {
        $sekolah = Sekolah::first();
        if (!$sekolah) {
            return redirect()->route('atur-data.whatsapp-gateway')->with('error', 'Data sekolah belum dikonfigurasi.');
        }

        $request->validate([
            'wa_token'  => 'nullable|string|max:255',
            'wa_status' => 'required|in:aktif,nonaktif',
        ]);

        $sekolah->update([
            'wa_token'  => $request->wa_token,
            'wa_status' => $request->wa_status,
        ]);

        return redirect()->route('atur-data.whatsapp-gateway')->with('success', 'Pengaturan WhatsApp Gateway berhasil diperbarui.');
    }

    /**
     * Kirim pesan uji coba dengan pesan custom.
     */
    public function test(Request $request)
    {
        $request->validate([
            'target'  => 'required|string|max:30',
            'message' => 'required|string|max:1000',
        ]);

        $sekolah = Sekolah::first();
        if (!$sekolah || empty($sekolah->wa_token)) {
            return response()->json([
                'success' => false,
                'message' => 'Token WhatsApp (Fonnte) belum disimpan atau tidak ditemukan.'
            ], 400);
        }

        // Temporarily set active during the test so the message goes through
        $originalStatus = $sekolah->wa_status;
        $sekolah->wa_status = 'aktif';
        $sekolah->save();

        $service = new FonnteService();
        $result = $service->sendMessage($request->target, $request->message);

        // Restore original status
        $sekolah->wa_status = $originalStatus;
        $sekolah->save();

        if ($result['success']) {
            return response()->json([
                'success' => true,
                'message' => 'Pesan uji coba berhasil dikirim ke ' . $request->target
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => $result['message'] ?? 'Gagal mengirim pesan uji coba.'
        ], 400);
    }

    /**
     * Cek status dan detail perangkat Fonnte secara live via AJAX.
     */
    public function deviceStatus()
    {
        $service = new FonnteService();
        $result = $service->checkDeviceStatus();

        if ($result['success']) {
            return response()->json([
                'success' => true,
                'device'  => $result['device']
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => $result['message'] ?? 'Gagal mengambil status perangkat dari Fonnte.'
        ], 400);
    }
}
