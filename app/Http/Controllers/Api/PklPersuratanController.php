<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\PklPersuratan;
use App\Models\PklPenempatan;
use App\Models\PklNomorSurat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PklPersuratanController extends Controller
{
    public function index(Request $request)
    {
        $query = PklPersuratan::with(['penempatan.siswa.kelas', 'penempatan.dudi']);

        if ($request->filled('id_gelombang')) {
            $query->whereHas('penempatan', function($q) use ($request) {
                $q->where('id_gelombang', $request->id_gelombang);
            });
        }

        $perPage = $request->get('per_page', 15);
        $data = $query->orderByDesc('id_surat')->paginate($perPage);

        return response()->json([
            'success' => true,
            'data' => $data,
        ]);
    }

    public function generate(Request $request)
    {
        $request->validate([
            'id_penempatan' => 'required|integer|exists:pkl_penempatan,id_penempatan|unique:pkl_persuratan,id_penempatan',
            'jenis_surat' => 'required|in:pengantar,permohonan,tugas',
            'tanggal_surat' => 'required|date',
        ]);

        DB::beginTransaction();
        try {
            $nomorSuratSetting = PklNomorSurat::where('jenis', $request->jenis_surat)->first();
            $nextCounter = ($nomorSuratSetting->counter ?? 0) + 1;
            
            // Format nomor: [Format] counter
            $formattedNomor = str_replace('{counter}', sprintf('%03d', $nextCounter), $nomorSuratSetting->format_nomor ?? 'PKL/{counter}');
            
            // Buat Surat
            $surat = PklPersuratan::create([
                'id_penempatan' => $request->id_penempatan,
                'jenis_surat' => $request->jenis_surat,
                'no_surat' => $formattedNomor,
                'tanggal_surat' => $request->tanggal_surat,
            ]);

            // Update counter
            if ($nomorSuratSetting) {
                $nomorSuratSetting->increment('counter');
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Gagal men-generate surat: ' . $e->getMessage()
            ], 500);
        }

        return response()->json([
            'success' => true,
            'message' => 'Surat PKL berhasil di-generate.',
            'data' => $surat->load(['penempatan.siswa', 'penempatan.dudi']),
        ], 201);
    }

    public function show($id)
    {
        $surat = PklPersuratan::with(['penempatan.siswa.kelas', 'penempatan.dudi', 'penempatan.pembimbing.guru'])->findOrFail($id);

        return response()->json([
            'success' => true,
            'data' => $surat,
        ]);
    }

    public function destroy($id)
    {
        PklPersuratan::findOrFail($id)->delete();

        return response()->json([
            'success' => true,
            'message' => 'Surat PKL berhasil dihapus.',
        ]);
    }
}
