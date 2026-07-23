<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Tadarus;
use App\Models\Semester;
use Illuminate\Http\Request;

class TadarusController extends Controller
{
    public function index(Request $request)
    {
        $query = Tadarus::with('kelas');

        if ($request->filled('id_kelas')) {
            $query->where('id_kelas', $request->id_kelas);
        }

        if ($request->filled('tanggal')) {
            $query->whereDate('tanggal', $request->tanggal);
        }

        // Filter berdasarkan semester aktif jika tidak ada filter tanggal spesifik
        if (!$request->filled('tanggal') && !$request->filled('ignore_semester')) {
            $semester = Semester::where('status', 'aktif')->first();
            if ($semester) {
                $query->whereBetween('tanggal', [
                    $semester->awal->format('Y-m-d'),
                    $semester->akhir->format('Y-m-d'),
                ]);
            }
        }

        $data = $query->orderByDesc('tanggal')->get();

        // Map kolom DB ke nama field yang diharapkan Flutter TadarusModel
        $mapped = $data->map(function ($t) {
            $kelasObj = $t->kelas;
            return [
                'id'               => $t->id_tadarus,
                'id_kelas'         => $t->id_kelas,
                'id_guru'          => $t->id_guru,
                'nama_kelas'       => $kelasObj?->nama_kelas,
                'tingkat'          => $kelasObj?->tingkat,
                'rombel'           => $kelasObj?->rombel,
                'tanggal'          => $t->tanggal?->format('Y-m-d'),
                'surat_mulai'      => $t->awal_surat,
                'ayat_mulai'       => $t->awal_ayat,
                'surat_selesai'    => $t->akhir_surat,
                'ayat_selesai'     => $t->akhir_ayat,
                'pembaca_terakhir' => $t->guru?->nama_guru ?? 'Guru ISMUBA',
                'keterangan'       => $t->keterangan ?? null,
            ];
        });

        return response()->json([
            'success' => true,
            'data'    => $mapped,
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_kelas' => 'required|integer|exists:kelas,id_kelas',
            'tanggal' => 'required|date',
            'surat_mulai' => 'required|string|max:100',
            'ayat_mulai' => 'required|integer',
            'surat_selesai' => 'required|string|max:100',
            'ayat_selesai' => 'required|integer',
        ]);

        $user = auth()->user();
        $idGuru = null;
        if ($user instanceof \App\Models\Guru) {
            $idGuru = $user->id_guru;
        }

        $tadarus = Tadarus::create([
            'id_kelas' => $request->id_kelas,
            'tanggal' => $request->tanggal,
            'awal_surat' => $request->surat_mulai,
            'awal_ayat' => $request->ayat_mulai,
            'akhir_surat' => $request->surat_selesai,
            'akhir_ayat' => $request->ayat_selesai,
            'id_guru' => $idGuru,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Data tadarus kelas berhasil disimpan.',
            'data' => $tadarus->load(['kelas']),
        ], 201);
    }

    public function show($id)
    {
        $tadarus = Tadarus::with(['kelas', 'guru'])->findOrFail($id);

        return response()->json([
            'success' => true,
            'data' => $tadarus,
        ]);
    }

    public function update(Request $request, $id)
    {
        $tadarus = Tadarus::findOrFail($id);

        $request->validate([
            'surat_mulai' => 'sometimes|required|string|max:100',
            'ayat_mulai' => 'sometimes|required|integer',
            'surat_selesai' => 'sometimes|required|string|max:100',
            'ayat_selesai' => 'sometimes|required|integer',
        ]);

        $data = [];
        if ($request->has('surat_mulai')) $data['awal_surat'] = $request->surat_mulai;
        if ($request->has('ayat_mulai')) $data['awal_ayat'] = $request->ayat_mulai;
        if ($request->has('surat_selesai')) $data['akhir_surat'] = $request->surat_selesai;
        if ($request->has('ayat_selesai')) $data['akhir_ayat'] = $request->ayat_selesai;

        $user = auth()->user();
        if ($user instanceof \App\Models\Guru) {
            $data['id_guru'] = $user->id_guru;
        }

        $tadarus->update($data);

        return response()->json([
            'success' => true,
            'message' => 'Data tadarus kelas berhasil diperbarui.',
            'data' => $tadarus->load(['kelas']),
        ]);
    }

    public function destroy($id)
    {
        $tadarus = Tadarus::findOrFail($id);

        // Pastikan hanya guru yang menginput data ini yang bisa menghapus
        $user = auth()->user();
        if ($user instanceof \App\Models\Guru) {
            if ($tadarus->id_guru !== $user->id_guru) {
                return response()->json([
                    'success' => false,
                    'message' => 'Anda tidak memiliki izin untuk menghapus data ini.',
                ], 403);
            }
        }

        $tadarus->delete();

        return response()->json([
            'success' => true,
            'message' => 'Data tadarus kelas berhasil dihapus.',
        ]);
    }
}
