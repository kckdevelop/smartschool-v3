<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\RiwayatGenerateSoal;
use App\Models\RiwayatGenerateKisiKisi;
use Illuminate\Http\Request;

class GeneratorSoalController extends Controller
{
    public function historySoal(Request $request)
    {
        $query = RiwayatGenerateSoal::query();

        if ($request->filled('mapel')) {
            $query->where('mapel', 'like', '%' . $request->mapel . '%');
        }

        $perPage = $request->get('per_page', 15);
        $data = $query->orderByDesc('id_riwayat')->paginate($perPage);

        return response()->json([
            'success' => true,
            'data' => $data,
        ]);
    }

    public function showSoal($id)
    {
        $soal = RiwayatGenerateSoal::findOrFail($id);

        return response()->json([
            'success' => true,
            'data' => $soal,
        ]);
    }

    public function destroySoal($id)
    {
        RiwayatGenerateSoal::findOrFail($id)->delete();

        return response()->json([
            'success' => true,
            'message' => 'Riwayat generate soal berhasil dihapus.',
        ]);
    }

    public function historyKisiKisi(Request $request)
    {
        $query = RiwayatGenerateKisiKisi::query();

        if ($request->filled('mapel')) {
            $query->where('mapel', 'like', '%' . $request->mapel . '%');
        }

        $perPage = $request->get('per_page', 15);
        $data = $query->orderByDesc('id_kisi')->paginate($perPage);

        return response()->json([
            'success' => true,
            'data' => $data,
        ]);
    }

    public function showKisiKisi($id)
    {
        $kisi = RiwayatGenerateKisiKisi::findOrFail($id);

        return response()->json([
            'success' => true,
            'data' => $kisi,
        ]);
    }

    public function destroyKisiKisi($id)
    {
        RiwayatGenerateKisiKisi::findOrFail($id)->delete();

        return response()->json([
            'success' => true,
            'message' => 'Riwayat generate kisi-kisi berhasil dihapus.',
        ]);
    }
}
