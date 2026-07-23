<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\PklNomorSurat;
use Illuminate\Http\Request;

class PklNomorSuratController extends Controller
{
    public function index(Request $request)
    {
        $data = PklNomorSurat::all();

        return response()->json([
            'success' => true,
            'data' => $data,
        ]);
    }

    public function update(Request $request, $jenis)
    {
        $nomor = PklNomorSurat::where('jenis', $jenis)->firstOrFail();

        $request->validate([
            'format_nomor' => 'required|string|max:100',
            'counter' => 'required|integer|min:0',
        ]);

        $nomor->update([
            'format_nomor' => $request->format_nomor,
            'counter' => $request->counter,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Format nomor surat berhasil diperbarui.',
            'data' => $nomor,
        ]);
    }

    public function resetCounter($jenis)
    {
        $nomor = PklNomorSurat::where('jenis', $jenis)->firstOrFail();
        $nomor->update(['counter' => 0]);

        return response()->json([
            'success' => true,
            'message' => 'Counter nomor surat berhasil di-reset ke 0.',
            'data' => $nomor,
        ]);
    }
}
