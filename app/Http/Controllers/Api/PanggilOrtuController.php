<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\PanggilOrtu;
use App\Models\UserSiswa;
use App\Models\Sekolah;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade\Pdf;

class PanggilOrtuController extends Controller
{
    public function index(Request $request)
    {
        $query = PanggilOrtu::with(['siswa.kelas', 'guru']);

        if ($request->filled('nis')) {
            $query->where('nis', $request->nis);
        }

        if ($request->filled('id_kelas')) {
            $query->whereHas('siswa', function($q) use ($request) {
                $q->where('id_kelas', $request->id_kelas);
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $perPage = $request->get('per_page', 50);
        $data = $query->orderByDesc('tanggal_panggil')->paginate($perPage);

        return response()->json([
            'success' => true,
            'data' => $data,
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'tanggal_panggil' => 'required|date',
            'waktu_pertemuan' => 'required',
            'lokasi_pertemuan' => 'required|string|max:255',
            'nis'             => 'required|string|max:20|exists:user_siswa,nis',
            'alasan_panggil'  => 'required|string',
            'jenis_panggilan' => 'required|in:panggilan_biasa,sp_1,sp_2,sp_3',
            'no_surat'        => 'nullable|string|max:100|unique:panggil_ortu,no_surat',
            'status'          => 'nullable|in:belum_hadir,sudah_hadir,tidak_hadir',
            'nama_ortu'       => 'nullable|string|max:100',
            'no_hp_ortu'      => 'nullable|string|max:20',
            'hasil_pertemuan' => 'nullable|string',
            'bukti_pertemuan' => 'nullable|file|mimes:jpeg,jpg,png,pdf|max:2048',
            'surat_pernyataan'=> 'nullable|file|mimes:jpeg,jpg,png,pdf|max:2048',
        ]);

        $guru = $request->user();

        $data = [
            'no_surat'        => $request->no_surat,
            'tanggal_panggil' => $request->tanggal_panggil,
            'waktu_pertemuan' => $request->waktu_pertemuan,
            'lokasi_pertemuan' => $request->lokasi_pertemuan,
            'nis'             => $request->nis,
            'nama_ortu'       => $request->nama_ortu,
            'no_hp_ortu'      => $request->no_hp_ortu,
            'jenis_panggilan' => $request->jenis_panggilan,
            'alasan_panggil'  => $request->alasan_panggil,
            'hasil_pertemuan' => $request->hasil_pertemuan,
            'status'          => $request->status ?? 'belum_hadir',
            'id_guru'         => $guru->id_guru ?? 1,
        ];

        $buktiPath = \App\Helpers\FileUploadHelper::storeFile($request, 'bukti_pertemuan', 'pemanggilan/bukti');
        if ($buktiPath) {
            $data['bukti_pertemuan'] = $buktiPath;
        }

        $suratPath = \App\Helpers\FileUploadHelper::storeFile($request, 'surat_pernyataan', 'pemanggilan/surat');
        if ($suratPath) {
            $data['surat_pernyataan'] = $suratPath;
        }

        $panggilan = PanggilOrtu::create($data);

        return response()->json([
            'success' => true,
            'message' => 'Pemanggilan orang tua berhasil dibuat.',
            'data' => $panggilan->load(['siswa.kelas', 'guru']),
        ], 201);
    }

    public function show($id)
    {
        $panggilan = PanggilOrtu::with(['siswa.kelas', 'guru'])->findOrFail($id);

        return response()->json([
            'success' => true,
            'data' => $panggilan,
        ]);
    }

    public function update(Request $request, $id)
    {
        $panggilan = PanggilOrtu::findOrFail($id);

        $request->validate([
            'tanggal_panggil' => 'required|date',
            'waktu_pertemuan' => 'required',
            'lokasi_pertemuan' => 'required|string|max:255',
            'nis'             => 'required|string|max:20|exists:user_siswa,nis',
            'alasan_panggil'  => 'required|string',
            'jenis_panggilan' => 'required|in:panggilan_biasa,sp_1,sp_2,sp_3',
            'no_surat'        => 'nullable|string|max:100|unique:panggil_ortu,no_surat,' . $id . ',id_panggil',
            'status'          => 'required|in:belum_hadir,sudah_hadir,tidak_hadir',
            'nama_ortu'       => 'nullable|string|max:100',
            'no_hp_ortu'      => 'nullable|string|max:20',
            'hasil_pertemuan' => 'nullable|string',
        ]);

        $data = $request->only([
            'no_surat', 'tanggal_panggil', 'waktu_pertemuan', 'lokasi_pertemuan', 'nis', 
            'nama_ortu', 'no_hp_ortu', 'jenis_panggilan', 'alasan_panggil', 'hasil_pertemuan', 'status'
        ]);

        if ($request->has('bukti_pertemuan') || $request->hasFile('bukti_pertemuan')) {
            $newBukti = \App\Helpers\FileUploadHelper::storeFile($request, 'bukti_pertemuan', 'pemanggilan/bukti');
            if ($newBukti) {
                if ($panggilan->bukti_pertemuan && Storage::disk('public')->exists($panggilan->bukti_pertemuan)) {
                    Storage::disk('public')->delete($panggilan->bukti_pertemuan);
                }
                $data['bukti_pertemuan'] = $newBukti;
            }
        }

        if ($request->has('surat_pernyataan') || $request->hasFile('surat_pernyataan')) {
            $newSurat = \App\Helpers\FileUploadHelper::storeFile($request, 'surat_pernyataan', 'pemanggilan/surat');
            if ($newSurat) {
                if ($panggilan->surat_pernyataan && Storage::disk('public')->exists($panggilan->surat_pernyataan)) {
                    Storage::disk('public')->delete($panggilan->surat_pernyataan);
                }
                $data['surat_pernyataan'] = $newSurat;
            }
        }

        $panggilan->update($data);

        return response()->json([
            'success' => true,
            'message' => 'Pemanggilan orang tua berhasil diperbarui.',
            'data' => $panggilan->load(['siswa.kelas', 'guru']),
        ]);
    }

    public function destroy($id)
    {
        $panggilan = PanggilOrtu::findOrFail($id);
        
        if ($panggilan->bukti_pertemuan) {
            Storage::disk('public')->delete($panggilan->bukti_pertemuan);
        }
        if ($panggilan->surat_pernyataan) {
            Storage::disk('public')->delete($panggilan->surat_pernyataan);
        }
        
        $panggilan->delete();

        return response()->json([
            'success' => true,
            'message' => 'Data pemanggilan orang tua berhasil dihapus.',
        ]);
    }

    public function getSiswaDetail(Request $request)
    {
        $siswa = UserSiswa::with(['kelas', 'detail'])->where('nis', $request->nis)->first();
        if (!$siswa) {
            return response()->json([
                'success' => false,
                'message' => 'Siswa tidak ditemukan'
            ], 404);
        }

        $namaOrtu = $siswa->detail->nama_wali ?? $siswa->detail->nama_ayah ?? $siswa->detail->nama_ibu ?? '';
        $noHpOrtu = $siswa->detail->no_telp_wali ?? $siswa->detail->no_telp_ayah ?? $siswa->detail->no_telp_ibu ?? '';

        return response()->json([
            'success' => true,
            'data' => [
                'nis' => $siswa->nis,
                'nama_siswa' => $siswa->nama_siswa,
                'nama_kelas' => $siswa->kelas ? $siswa->kelas->nama_kelas : '-',
                'nama_ortu' => $namaOrtu,
                'no_hp_ortu' => $noHpOrtu,
                'alamat' => $siswa->detail->alamat ?? '',
            ]
        ]);
    }

    public function previewPdf(Request $request)
    {
        $sekolah = Sekolah::first() ?? new Sekolah();
        $siswa = UserSiswa::with('kelas')->where('nis', $request->nis)->first();
        $guru = $request->user();

        $panggil = new PanggilOrtu([
            'no_surat'        => $request->no_surat,
            'tanggal_panggil' => $request->tanggal_panggil ?? date('Y-m-d'),
            'waktu_pertemuan' => $request->waktu_pertemuan ?? '08:00',
            'lokasi_pertemuan' => $request->lokasi_pertemuan ?? 'Ruang Bimbingan Konseling (BK)',
            'nis'             => $request->nis ?? '',
            'nama_ortu'       => $request->nama_ortu,
            'no_hp_ortu'      => $request->no_hp_ortu,
            'jenis_panggilan' => $request->jenis_panggilan ?? 'panggilan_biasa',
            'alasan_panggil'  => $request->alasan_panggil ?? '-',
            'created_at'      => now(),
        ]);

        if ($siswa) {
            $panggil->setRelation('siswa', $siswa);
        }
        if ($guru) {
            $panggil->setRelation('guru', $guru);
        }

        try {
            $pdf = Pdf::loadView('bk.panggil-ortu.pdf', compact('panggil', 'sekolah'));
            return response($pdf->output(), 200)
                ->header('Content-Type', 'application/pdf')
                ->header('Content-Disposition', 'inline; filename="preview.pdf"');
        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal membuat PDF: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function downloadPdf($id)
    {
        $panggil = PanggilOrtu::with(['siswa.kelas', 'guru'])->find($id);
        if (!$panggil) {
            return response()->json([
                'success' => false,
                'message' => 'Data pemanggilan orang tua tidak ditemukan.',
            ], 404);
        }

        $sekolah = Sekolah::first() ?? new Sekolah();

        try {
            $pdf = Pdf::loadView('bk.panggil-ortu.pdf', compact('panggil', 'sekolah'));
            $filename = 'surat_pemanggilan_' . ($panggil->jenis_panggilan === 'panggilan_biasa' ? 'undangan' : $panggil->jenis_panggilan) . '_' . $panggil->nis . '.pdf';
            
            return response($pdf->output(), 200)
                ->header('Content-Type', 'application/pdf')
                ->header('Content-Disposition', 'inline; filename="' . $filename . '"');
        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal membuat PDF: ' . $e->getMessage(),
            ], 500);
        }
    }
}
