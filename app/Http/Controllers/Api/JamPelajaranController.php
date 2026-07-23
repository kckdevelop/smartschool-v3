<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\JamPelajaran;
use Illuminate\Http\Request;

class JamPelajaranController extends Controller
{
    /**
     * Daftar semua jam pelajaran yang aktif.
     *
     * @queryParam aktif bool Filter hanya yang aktif (1) atau semua (kosong). Example: 1
     */
    public function index(Request $request)
    {
        $query = JamPelajaran::orderBy('jam_ke');

        if ($request->filled('aktif')) {
            $query->where('aktif', (bool) $request->aktif);
        }

        return response()->json([
            'success' => true,
            'data'    => $query->get(),
        ]);
    }

    /**
     * Simpan jam pelajaran baru.
     */
    public function store(Request $request)
    {
        $request->validate([
            'jam_ke'    => 'required|integer|unique:jam_pelajaran,jam_ke',
            'label'     => 'nullable|string|max:50',
            'jam_mulai' => 'required|string|regex:/^\d{2}:\d{2}$/',
            'jam_selesai' => 'required|string|regex:/^\d{2}:\d{2}$/',
            'aktif'     => 'required|boolean',
        ]);

        $jam = JamPelajaran::create($request->only('jam_ke', 'label', 'jam_mulai', 'jam_selesai', 'aktif'));

        return response()->json([
            'success' => true,
            'message' => 'Jam pelajaran berhasil ditambahkan.',
            'data'    => $jam,
        ], 201);
    }

    /**
     * Detail jam pelajaran.
     */
    public function show($id)
    {
        $jam = JamPelajaran::findOrFail($id);

        return response()->json([
            'success' => true,
            'data'    => $jam,
        ]);
    }

    /**
     * Update jam pelajaran.
     */
    public function update(Request $request, $id)
    {
        $jam = JamPelajaran::findOrFail($id);

        $request->validate([
            'jam_ke'      => 'sometimes|required|integer|unique:jam_pelajaran,jam_ke,' . $id . ',id_jam',
            'label'       => 'nullable|string|max:50',
            'jam_mulai'   => 'sometimes|required|string|regex:/^\d{2}:\d{2}$/',
            'jam_selesai' => 'sometimes|required|string|regex:/^\d{2}:\d{2}$/',
            'aktif'       => 'sometimes|required|boolean',
        ]);

        $jam->update($request->only('jam_ke', 'label', 'jam_mulai', 'jam_selesai', 'aktif'));

        return response()->json([
            'success' => true,
            'message' => 'Jam pelajaran berhasil diperbarui.',
            'data'    => $jam,
        ]);
    }

    /**
     * Hapus jam pelajaran.
     */
    public function destroy($id)
    {
        $jam = JamPelajaran::findOrFail($id);
        $jam->delete();

        return response()->json([
            'success' => true,
            'message' => 'Jam pelajaran berhasil dihapus.',
        ]);
    }

    /**
     * Set jam mana yang aktif digunakan (replace semua sekaligus).
     *
     * @bodyParam jam_ids int[] required Array ID jam yang aktif. Example: [1, 2, 3]
     */
    public function updateAktif(Request $request)
    {
        $request->validate([
            'jam_ids'   => 'required|array',
            'jam_ids.*' => 'integer|exists:jam_pelajaran,id_jam',
        ]);

        JamPelajaran::query()->update(['aktif' => false]);
        JamPelajaran::whereIn('id_jam', $request->jam_ids)->update(['aktif' => true]);

        return response()->json([
            'success' => true,
            'message' => 'Status aktif jam pelajaran berhasil diperbarui.',
        ]);
    }
}
