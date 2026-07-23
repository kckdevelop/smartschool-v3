<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\TahunAjaran;
use App\Models\Semester;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TahunSemesterController extends Controller
{
    // ===========================
    //  TAHUN AJARAN
    // ===========================

    /**
     * Daftar semua tahun ajaran beserta semester-nya.
     */
    public function indexTahun()
    {
        $data = TahunAjaran::with('semester')->orderByDesc('id_tahun')->get();

        return response()->json([
            'success' => true,
            'data'    => $data,
        ]);
    }

    /**
     * Simpan tahun ajaran baru.
     */
    public function storeTahun(Request $request)
    {
        $request->validate([
            'tahun'  => 'required|string|max:20|unique:tahun_ajaran,tahun',
            'status' => 'required|in:aktif,tidak',
        ]);

        // Jika diset aktif, nonaktifkan yang lain
        if ($request->status === 'aktif') {
            TahunAjaran::where('status', 'aktif')->update(['status' => 'tidak']);
        }

        $tahun = TahunAjaran::create($request->only('tahun', 'status'));

        return response()->json([
            'success' => true,
            'message' => 'Tahun ajaran berhasil ditambahkan.',
            'data'    => $tahun,
        ], 201);
    }

    /**
     * Detail tahun ajaran.
     */
    public function showTahun($id)
    {
        $tahun = TahunAjaran::with('semester')->findOrFail($id);

        return response()->json([
            'success' => true,
            'data'    => $tahun,
        ]);
    }

    /**
     * Update tahun ajaran.
     */
    public function updateTahun(Request $request, $id)
    {
        $tahun = TahunAjaran::findOrFail($id);

        $request->validate([
            'tahun'  => 'sometimes|required|string|max:20|unique:tahun_ajaran,tahun,' . $id . ',id_tahun',
            'status' => 'sometimes|required|in:aktif,tidak',
        ]);

        if ($request->status === 'aktif') {
            TahunAjaran::where('status', 'aktif')->where('id_tahun', '!=', $id)->update(['status' => 'tidak']);
        }

        $tahun->update($request->only('tahun', 'status'));

        return response()->json([
            'success' => true,
            'message' => 'Tahun ajaran berhasil diperbarui.',
            'data'    => $tahun,
        ]);
    }

    /**
     * Hapus tahun ajaran.
     */
    public function destroyTahun($id)
    {
        $tahun = TahunAjaran::findOrFail($id);

        if ($tahun->semester()->count() > 0) {
            return response()->json([
                'success' => false,
                'message' => 'Tahun ajaran masih memiliki semester. Hapus semester terlebih dahulu.',
            ], 422);
        }

        $tahun->delete();

        return response()->json([
            'success' => true,
            'message' => 'Tahun ajaran berhasil dihapus.',
        ]);
    }

    // ===========================
    //  SEMESTER
    // ===========================

    /**
     * Daftar semua semester.
     */
    public function indexSemester()
    {
        $data = Semester::with('tahunAjaran')->orderByDesc('id_semester')->get();

        return response()->json([
            'success' => true,
            'data'    => $data,
        ]);
    }

    /**
     * Simpan semester baru.
     */
    public function storeSemester(Request $request)
    {
        $request->validate([
            'id_tahun' => 'required|integer|exists:tahun_ajaran,id_tahun',
            'semester' => 'required|in:Ganjil,Genap',
            'awal'     => 'required|date',
            'akhir'    => 'required|date|after:awal',
            'status'   => 'required|in:aktif,tidak',
        ]);

        // Jika diset aktif, nonaktifkan yang lain
        if ($request->status === 'aktif') {
            Semester::where('status', 'aktif')->update(['status' => 'tidak']);
        }

        $semester = Semester::create($request->only('id_tahun', 'semester', 'awal', 'akhir', 'status'));

        return response()->json([
            'success' => true,
            'message' => 'Semester berhasil ditambahkan.',
            'data'    => $semester->load('tahunAjaran'),
        ], 201);
    }

    /**
     * Detail semester.
     */
    public function showSemester($id)
    {
        $semester = Semester::with('tahunAjaran')->findOrFail($id);

        return response()->json([
            'success' => true,
            'data'    => $semester,
        ]);
    }

    /**
     * Update semester.
     */
    public function updateSemester(Request $request, $id)
    {
        $semester = Semester::findOrFail($id);

        $request->validate([
            'id_tahun' => 'sometimes|required|integer|exists:tahun_ajaran,id_tahun',
            'semester' => 'sometimes|required|in:Ganjil,Genap',
            'awal'     => 'sometimes|required|date',
            'akhir'    => 'sometimes|required|date|after:awal',
            'status'   => 'sometimes|required|in:aktif,tidak',
        ]);

        if ($request->status === 'aktif') {
            Semester::where('status', 'aktif')->where('id_semester', '!=', $id)->update(['status' => 'tidak']);
        }

        $semester->update($request->only('id_tahun', 'semester', 'awal', 'akhir', 'status'));

        return response()->json([
            'success' => true,
            'message' => 'Semester berhasil diperbarui.',
            'data'    => $semester->load('tahunAjaran'),
        ]);
    }

    /**
     * Hapus semester.
     */
    public function destroySemester($id)
    {
        $semester = Semester::findOrFail($id);
        $semester->delete();

        return response()->json([
            'success' => true,
            'message' => 'Semester berhasil dihapus.',
        ]);
    }

    /**
     * Aktifkan semester (shortcut).
     */
    public function aktivasiSemester($id)
    {
        Semester::where('status', 'aktif')->update(['status' => 'tidak']);
        $semester = Semester::findOrFail($id);
        $semester->update(['status' => 'aktif']);

        return response()->json([
            'success' => true,
            'message' => "Semester {$semester->semester} berhasil diaktifkan.",
            'data'    => $semester->load('tahunAjaran'),
        ]);
    }
}
