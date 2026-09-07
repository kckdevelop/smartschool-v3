<?php

namespace App\Http\Controllers\AturData;

use App\Http\Controllers\Controller;
use App\Models\Sekolah;
use App\Models\WaGateway;
use App\Services\FonnteService;
use Illuminate\Http\Request;

class WhatsappGatewayController extends Controller
{
    /**
     * Tampilkan halaman kelola WhatsApp Gateway Multi-API.
     */
    public function index()
    {
        $sekolah  = Sekolah::first();
        $gateways = WaGateway::orderByDesc('id')->get();

        $totalGateways    = $gateways->count();
        $activeGateways   = $gateways->where('status', 'aktif')->count();
        $inactiveGateways = $gateways->where('status', 'nonaktif')->count();

        return view('atur-data.whatsapp-gateway.index', compact(
            'sekolah',
            'gateways',
            'totalGateways',
            'activeGateways',
            'inactiveGateways'
        ));
    }

    /**
     * Simpan WA Gateway baru.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama'       => 'required|string|max:100',
            'token'      => 'required|string|max:255',
            'provider'   => 'nullable|string|max:50',
            'nomor_wa'   => 'nullable|string|max:30',
            'keterangan' => 'nullable|string|max:500',
            'status'     => 'required|in:aktif,nonaktif',
        ]);

        WaGateway::create([
            'nama'       => $request->nama,
            'token'      => $request->token,
            'provider'   => $request->provider ?? 'fonnte',
            'nomor_wa'   => $request->nomor_wa,
            'keterangan' => $request->keterangan,
            'status'     => $request->status,
        ]);

        return redirect()->route('atur-data.whatsapp-gateway')->with('success', 'WhatsApp Gateway baru berhasil ditambahkan.');
    }

    /**
     * Update data WA Gateway.
     */
    public function update(Request $request, $id)
    {
        $gateway = WaGateway::findOrFail($id);

        $request->validate([
            'nama'       => 'required|string|max:100',
            'token'      => 'required|string|max:255',
            'provider'   => 'nullable|string|max:50',
            'nomor_wa'   => 'nullable|string|max:30',
            'keterangan' => 'nullable|string|max:500',
            'status'     => 'required|in:aktif,nonaktif',
        ]);

        $gateway->update([
            'nama'       => $request->nama,
            'token'      => $request->token,
            'provider'   => $request->provider ?? 'fonnte',
            'nomor_wa'   => $request->nomor_wa,
            'keterangan' => $request->keterangan,
            'status'     => $request->status,
        ]);

        return redirect()->route('atur-data.whatsapp-gateway')->with('success', 'Data WhatsApp Gateway berhasil diperbarui.');
    }

    /**
     * Hapus WA Gateway.
     */
    public function destroy($id)
    {
        $gateway = WaGateway::findOrFail($id);
        $gateway->delete();

        return redirect()->route('atur-data.whatsapp-gateway')->with('success', 'WhatsApp Gateway berhasil dihapus.');
    }

    /**
     * Toggle status aktif / nonaktif WA Gateway.
     */
    public function toggleStatus($id)
    {
        $gateway = WaGateway::findOrFail($id);
        $gateway->status = ($gateway->status === 'aktif') ? 'nonaktif' : 'aktif';
        $gateway->save();

        return response()->json([
            'success' => true,
            'message' => "Status {$gateway->nama} berhasil diubah menjadi " . ucfirst($gateway->status) . '.',
            'status'  => $gateway->status,
        ]);
    }

    /**
     * Kirim pesan uji coba dengan pesan custom.
     */
    public function test(Request $request)
    {
        $request->validate([
            'target'     => 'required|string|max:30',
            'message'    => 'required|string|max:1000',
            'gateway_id' => 'nullable',
        ]);

        $gatewayId = $request->input('gateway_id');
        $service = null;
        $targetGateway = null;

        if ($gatewayId && $gatewayId !== 'random') {
            $targetGateway = WaGateway::find($gatewayId);
            if (!$targetGateway) {
                return response()->json([
                    'success' => false,
                    'message' => 'Gateway yang dipilih tidak ditemukan.'
                ], 404);
            }
            $service = new FonnteService($targetGateway);
        } else {
            $service = new FonnteService();
        }

        $result = $service->sendMessage($request->target, $request->message);

        if ($result['success']) {
            $usedGatewayName = $result['gateway_nama'] ?? ($targetGateway ? $targetGateway->nama : 'Acak/Default');
            return response()->json([
                'success' => true,
                'message' => 'Pesan uji coba berhasil dikirim ke ' . $request->target . " via [{$usedGatewayName}]",
                'data'    => $result,
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => $result['message'] ?? 'Gagal mengirim pesan uji coba.',
            'data'    => $result,
        ], 400);
    }

    /**
     * Cek status dan detail perangkat Fonnte secara live via AJAX untuk gateway tertentu.
     */
    public function deviceStatus($id = null)
    {
        $token = null;
        if ($id && $id !== 'default') {
            $gateway = WaGateway::find($id);
            if ($gateway) {
                $token = $gateway->token;
            }
        }

        $service = new FonnteService();
        $result = $service->checkDeviceStatus($token);

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
