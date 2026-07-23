<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Sekolah;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SekolahController extends Controller
{
    /**
     * Tampilkan data sekolah (hanya 1 record).
     */
    public function index()
    {
        $sekolah = Sekolah::first();

        return response()->json([
            'success' => true,
            'data'    => $sekolah,
        ]);
    }

    /**
     * Simpan data sekolah baru.
     */
    public function store(Request $request)
    {
        $request->validate([
            'npsn'           => 'required|integer',
            'nama_sekolah'   => 'required|string|max:255',
            'kepala_sekolah' => 'required|string|max:255',
            'nip'            => 'nullable|string|max:50',
            'status'         => 'required|in:negeri,swasta',
            'alamat_sekolah' => 'nullable|string',
            'logo'           => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'kop'            => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'ijin'           => 'nullable|in:ya,tidak',
        ]);

        $data = $request->only([
            'npsn', 'nama_sekolah', 'kepala_sekolah', 'nip',
            'status', 'alamat_sekolah', 'ijin',
        ]);

        if ($request->hasFile('logo')) {
            $data['logo'] = $request->file('logo')->store('sekolah/logo', 'public');
        }

        if ($request->hasFile('kop')) {
            $data['kop'] = $request->file('kop')->store('sekolah/kop', 'public');
        }

        $sekolah = Sekolah::create($data);

        return response()->json([
            'success' => true,
            'message' => 'Data sekolah berhasil disimpan.',
            'data'    => $sekolah,
        ], 201);
    }

    /**
     * Tampilkan detail sekolah berdasarkan ID.
     */
    public function show($id)
    {
        $sekolah = Sekolah::findOrFail($id);

        return response()->json([
            'success' => true,
            'data'    => $sekolah,
        ]);
    }

    /**
     * Update data sekolah.
     */
    public function update(Request $request, $id)
    {
        $sekolah = Sekolah::findOrFail($id);

        $request->validate([
            'npsn'           => 'sometimes|required|integer',
            'nama_sekolah'   => 'sometimes|required|string|max:255',
            'kepala_sekolah' => 'sometimes|required|string|max:255',
            'nip'            => 'nullable|string|max:50',
            'status'         => 'sometimes|required|in:negeri,swasta',
            'alamat_sekolah' => 'nullable|string',
            'logo'           => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'kop'            => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'ijin'           => 'nullable|in:ya,tidak',
        ]);

        $data = $request->only([
            'npsn', 'nama_sekolah', 'kepala_sekolah', 'nip',
            'status', 'alamat_sekolah', 'ijin',
        ]);

        if ($request->hasFile('logo')) {
            if ($sekolah->logo) {
                Storage::disk('public')->delete($sekolah->logo);
            }
            $data['logo'] = $request->file('logo')->store('sekolah/logo', 'public');
        }

        if ($request->hasFile('kop')) {
            if ($sekolah->kop) {
                Storage::disk('public')->delete($sekolah->kop);
            }
            $data['kop'] = $request->file('kop')->store('sekolah/kop', 'public');
        }

        $sekolah->update($data);

        return response()->json([
            'success' => true,
            'message' => 'Data sekolah berhasil diperbarui.',
            'data'    => $sekolah,
        ]);
    }

    /**
     * Toggle fitur edit detail siswa di aplikasi mobile.
     * Endpoint: POST /api/admin/sekolah/toggle-edit-siswa
     */
    public function toggleEditDetailSiswa(Request $request)
    {
        $sekolah = Sekolah::first();

        if (!$sekolah) {
            return response()->json(['success' => false, 'message' => 'Data sekolah belum dikonfigurasi.'], 404);
        }

        $newValue = !$sekolah->edit_detail_siswa;
        $sekolah->update(['edit_detail_siswa' => $newValue]);

        return response()->json([
            'success'        => true,
            'message'        => $newValue
                ? 'Fitur edit profil siswa di aplikasi mobile telah DIAKTIFKAN.'
                : 'Fitur edit profil siswa di aplikasi mobile telah DINONAKTIFKAN.',
            'edit_diizinkan' => $newValue,
        ]);
    }

    /**
     * Hapus data sekolah.
     */
    public function destroy($id)
    {
        $sekolah = Sekolah::findOrFail($id);

        if ($sekolah->logo) {
            Storage::disk('public')->delete($sekolah->logo);
        }
        if ($sekolah->kop) {
            Storage::disk('public')->delete($sekolah->kop);
        }

        $sekolah->delete();

        return response()->json([
            'success' => true,
            'message' => 'Data sekolah berhasil dihapus.',
        ]);
    }
}
