<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\PantauIbadah;
use Illuminate\Http\Request;

class IbadahController extends Controller
{
    public function index(Request $request)
    {
        $query = PantauIbadah::with('siswa.kelas');

        if ($request->filled('nis')) {
            $query->where('nis', $request->nis);
        }

        if ($request->filled('tanggal')) {
            $query->whereDate('tanggal', $request->tanggal);
        }

        $perPage = $request->get('per_page', 15);
        $data = $query->orderByDesc('tanggal')->paginate($perPage);

        return response()->json([
            'success' => true,
            'data' => $data,
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'nis' => 'required|integer|exists:user_siswa,nis',
            'tanggal' => 'required|date',
            'shubuh' => 'required|in:Berjamaah,Munfarid,Tidak',
            'dhuhur' => 'required|in:Berjamaah,Munfarid,Tidak',
            'ashar' => 'required|in:Berjamaah,Munfarid,Tidak',
            'maghrib' => 'required|in:Berjamaah,Munfarid,Tidak',
            'isya' => 'required|in:Berjamaah,Munfarid,Tidak',
            'tadarus' => 'required|boolean',
            'dhuha' => 'required|boolean',
            'tahajud' => 'required|boolean',
        ]);

        $ibadah = PantauIbadah::create($request->only(
            'nis', 'tanggal', 'shubuh', 'dhuhur', 'ashar', 'maghrib', 'isya',
            'tadarus', 'dhuha', 'tahajud'
        ));

        return response()->json([
            'success' => true,
            'message' => 'Data pantauan ibadah harian berhasil disimpan.',
            'data' => $ibadah->load('siswa.kelas'),
        ], 201);
    }

    public function show($id)
    {
        $ibadah = PantauIbadah::with('siswa.kelas')->findOrFail($id);

        return response()->json([
            'success' => true,
            'data' => $ibadah,
        ]);
    }

    public function update(Request $request, $id)
    {
        $ibadah = PantauIbadah::findOrFail($id);

        $request->validate([
            'shubuh' => 'sometimes|required|in:Berjamaah,Munfarid,Tidak',
            'dhuhur' => 'sometimes|required|in:Berjamaah,Munfarid,Tidak',
            'ashar' => 'sometimes|required|in:Berjamaah,Munfarid,Tidak',
            'maghrib' => 'sometimes|required|in:Berjamaah,Munfarid,Tidak',
            'isya' => 'sometimes|required|in:Berjamaah,Munfarid,Tidak',
            'tadarus' => 'sometimes|required|boolean',
            'dhuha' => 'sometimes|required|boolean',
            'tahajud' => 'sometimes|required|boolean',
        ]);

        $ibadah->update($request->only(
            'shubuh', 'dhuhur', 'ashar', 'maghrib', 'isya',
            'tadarus', 'dhuha', 'tahajud'
        ));

        return response()->json([
            'success' => true,
            'message' => 'Data pantauan ibadah harian berhasil diperbarui.',
            'data' => $ibadah->load('siswa.kelas'),
        ]);
    }

    public function destroy($id)
    {
        PantauIbadah::findOrFail($id)->delete();

        return response()->json([
            'success' => true,
            'message' => 'Data pantauan ibadah harian berhasil dihapus.',
        ]);
    }
}
