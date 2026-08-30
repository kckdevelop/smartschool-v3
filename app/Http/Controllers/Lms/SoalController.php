<?php

namespace App\Http\Controllers\Lms;

use App\Http\Controllers\Controller;
use App\Models\LmsSoal;
use App\Models\LmsSoalPilihan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SoalController extends Controller
{
    /**
     * Upload gambar yang di-paste ke dalam editor soal (via AJAX).
     */
    public function uploadImage(Request $request)
    {
        $request->validate([
            'image' => 'required|image|mimes:jpg,jpeg,png,gif,webp|max:5120',
        ]);

        $path = $request->file('image')->store('kuis_images', 'public');

        return response()->json([
            'success' => true,
            'url'     => asset('storage/' . $path),
            'path'    => $path,
        ]);
    }

    /**
     * Update soal beserta pilihan jawabannya.
     */
    public function update(Request $request, $id_soal)
    {
        $soal = LmsSoal::with('pilihan')->findOrFail($id_soal);
        $id_tugas = $soal->id_tugas;

        $request->validate([
            'jenis_soal'    => 'required|in:pilihan_ganda,benar_salah,pilihan_ganda_komplek',
            'pertanyaan'    => 'required|string',
            'kunci_jawaban' => 'required|string',
            // Pilihan: array dinamis
            'pilihan'       => 'required|array|min:2',
            'pilihan.*.id_pilihan' => 'nullable|integer',
            'pilihan.*.kunci'     => 'required|string|max:5',
            'pilihan.*.teks'      => 'required|string',
            'pilihan.*.is_kunci'  => 'nullable',
        ]);

        // Update soal utama
        $soal->update([
            'jenis_soal'    => $request->jenis_soal,
            'pertanyaan'    => $request->pertanyaan,
            'kunci_jawaban' => $request->kunci_jawaban,
        ]);

        // Hapus semua pilihan lama dan buat ulang
        LmsSoalPilihan::where('id_soal', $soal->id_soal)->delete();

        $kunciList = array_map('trim', explode(',', strtoupper($request->kunci_jawaban)));

        foreach ($request->pilihan as $p) {
            $isKunci = in_array(strtoupper(trim($p['kunci'])), $kunciList);
            LmsSoalPilihan::create([
                'id_soal'  => $soal->id_soal,
                'kunci'    => strtoupper(trim($p['kunci'])),
                'teks'     => $p['teks'],
                'gambar'   => null,
                'is_kunci' => $isKunci,
            ]);
        }

        return redirect()->route('lms.tugas.show', $id_tugas)
            ->with('success', 'Soal No. ' . $soal->nomor_soal . ' berhasil diperbarui.');
    }

    /**
     * Hapus sebuah soal beserta semua pilihan jawabannya.
     */
    public function destroy($id_soal)
    {
        $soal = LmsSoal::findOrFail($id_soal);
        $id_tugas = $soal->id_tugas;
        $nomor = $soal->nomor_soal;

        // Hapus gambar soal jika ada
        if ($soal->gambar) {
            Storage::disk('public')->delete($soal->gambar);
        }

        // Hapus pilihan jawaban terkait
        LmsSoalPilihan::where('id_soal', $id_soal)->delete();

        $soal->delete();

        // Renumber soal yang tersisa
        $remaining = LmsSoal::where('id_tugas', $id_tugas)->orderBy('nomor_soal')->get();
        foreach ($remaining as $index => $s) {
            $s->update(['nomor_soal' => $index + 1]);
        }

        return redirect()->route('lms.tugas.show', $id_tugas)
            ->with('success', 'Soal No. ' . $nomor . ' berhasil dihapus. Nomor soal diperbarui secara otomatis.');
    }
}
