<?php

namespace App\Http\Controllers\Bk;

use App\Http\Controllers\Controller;
use App\Models\PanggilOrtu;
use App\Models\UserSiswa;
use App\Models\Kelas;
use App\Models\Sekolah;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;

class PanggilOrtuController extends Controller
{
    public function index(Request $request)
    {
        $kelas = Kelas::where('status', 'aktif')->orderBy('tingkat')->orderBy('rombel')->get();
        $query = PanggilOrtu::with(['siswa.kelas', 'guru'])->orderByDesc('tanggal_panggil');

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('nis')) {
            $query->where('nis', $request->nis);
        }
        if ($request->filled('id_kelas')) {
            $query->whereHas('siswa', function($q) use ($request) {
                $q->where('id_kelas', $request->id_kelas);
            });
        }

        $data = $query->paginate(15)->withQueryString();
        return view('bk.panggil-ortu.index', compact('data', 'kelas'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'tanggal_panggil' => 'required|date',
            'waktu_pertemuan' => 'required',
            'lokasi_pertemuan' => 'required|string|max:255',
            'nis'             => 'required|string|max:20',
            'alasan_panggil'  => 'required|string',
            'jenis_panggilan' => 'required|in:panggilan_biasa,sp_1,sp_2,sp_3',
            'no_surat'        => 'nullable|string|max:100|unique:panggil_ortu,no_surat',
            'bukti_pertemuan' => 'nullable|file|mimes:jpeg,jpg,png,pdf|max:2048',
            'surat_pernyataan'=> 'nullable|file|mimes:jpeg,jpg,png,pdf|max:2048',
        ]);

        $buktiPath = null;
        if ($request->hasFile('bukti_pertemuan')) {
            $buktiPath = $request->file('bukti_pertemuan')->store('pemanggilan/bukti', 'public');
        }

        $suratPath = null;
        if ($request->hasFile('surat_pernyataan')) {
            $suratPath = $request->file('surat_pernyataan')->store('pemanggilan/surat', 'public');
        }

        $guru = Auth::user();
        PanggilOrtu::create([
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
            'bukti_pertemuan' => $buktiPath,
            'surat_pernyataan'=> $suratPath,
            'status'          => $request->status ?? 'belum_hadir',
            'id_guru'         => $guru->id_guru ?? 1,
        ]);

        return redirect()->route('bk.panggil-ortu.index')
            ->with('success', 'Data panggil orang tua berhasil dicatat.');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'tanggal_panggil' => 'required|date',
            'waktu_pertemuan' => 'required',
            'lokasi_pertemuan' => 'required|string|max:255',
            'nis'             => 'required|string|max:20',
            'alasan_panggil'  => 'required|string',
            'jenis_panggilan' => 'required|in:panggilan_biasa,sp_1,sp_2,sp_3',
            'no_surat'        => 'nullable|string|max:100|unique:panggil_ortu,no_surat,' . $id . ',id_panggil',
            'status'          => 'required|in:belum_hadir,sudah_hadir,tidak_hadir',
            'bukti_pertemuan' => 'nullable|file|mimes:jpeg,jpg,png,pdf|max:2048',
            'surat_pernyataan'=> 'nullable|file|mimes:jpeg,jpg,png,pdf|max:2048',
        ]);

        $panggil = PanggilOrtu::findOrFail($id);
        $data = $request->only([
            'no_surat', 'tanggal_panggil', 'waktu_pertemuan', 'lokasi_pertemuan', 'nis', 'nama_ortu', 'no_hp_ortu',
            'jenis_panggilan', 'alasan_panggil', 'hasil_pertemuan', 'status'
        ]);

        if ($request->hasFile('bukti_pertemuan')) {
            if ($panggil->bukti_pertemuan) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($panggil->bukti_pertemuan);
            }
            $data['bukti_pertemuan'] = $request->file('bukti_pertemuan')->store('pemanggilan/bukti', 'public');
        }

        if ($request->hasFile('surat_pernyataan')) {
            if ($panggil->surat_pernyataan) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($panggil->surat_pernyataan);
            }
            $data['surat_pernyataan'] = $request->file('surat_pernyataan')->store('pemanggilan/surat', 'public');
        }

        $panggil->update($data);

        return redirect()->route('bk.panggil-ortu.index')
            ->with('success', 'Data panggil orang tua berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $panggil = PanggilOrtu::findOrFail($id);
        if ($panggil->bukti_pertemuan) {
            \Illuminate\Support\Facades\Storage::disk('public')->delete($panggil->bukti_pertemuan);
        }
        if ($panggil->surat_pernyataan) {
            \Illuminate\Support\Facades\Storage::disk('public')->delete($panggil->surat_pernyataan);
        }
        $panggil->delete();

        return redirect()->route('bk.panggil-ortu.index')
            ->with('success', 'Data panggil orang tua berhasil dihapus.');
    }

    public function getSiswaDetail(Request $request)
    {
        $siswa = UserSiswa::with(['kelas', 'detail'])->where('nis', $request->nis)->first();
        if (!$siswa) {
            return response()->json(['message' => 'Siswa tidak ditemukan'], 404);
        }

        $namaOrtu = $siswa->detail->nama_wali ?? $siswa->detail->nama_ayah ?? $siswa->detail->nama_ibu ?? '';
        $noHpOrtu = $siswa->detail->no_telp_wali ?? $siswa->detail->no_telp_ayah ?? $siswa->detail->no_telp_ibu ?? '';

        return response()->json([
            'nis' => $siswa->nis,
            'nama_siswa' => $siswa->nama_siswa,
            'nama_kelas' => $siswa->kelas ? $siswa->kelas->nama_kelas : '-',
            'nama_ortu' => $namaOrtu,
            'no_hp_ortu' => $noHpOrtu,
            'alamat' => $siswa->detail->alamat ?? '',
        ]);
    }

    public function preview(Request $request)
    {
        $request->validate([
            'tanggal_panggil' => 'required|date',
            'waktu_pertemuan' => 'required',
            'lokasi_pertemuan' => 'required|string|max:255',
            'nis'             => 'required|string|max:20',
            'alasan_panggil'  => 'required|string',
            'jenis_panggilan' => 'required|in:panggilan_biasa,sp_1,sp_2,sp_3',
            'no_surat'        => 'nullable|string|max:100',
        ]);

        $sekolah = Sekolah::first();
        $siswa = UserSiswa::with('kelas')->where('nis', $request->nis)->first();
        $guru = Auth::user();

        $panggil = new PanggilOrtu([
            'no_surat'        => $request->no_surat,
            'tanggal_panggil' => $request->tanggal_panggil,
            'waktu_pertemuan' => $request->waktu_pertemuan,
            'lokasi_pertemuan' => $request->lokasi_pertemuan,
            'nis'             => $request->nis,
            'nama_ortu'       => $request->nama_ortu,
            'no_hp_ortu'      => $request->no_hp_ortu,
            'jenis_panggilan' => $request->jenis_panggilan,
            'alasan_panggil'  => $request->alasan_panggil,
            'created_at'      => now(),
        ]);

        $panggil->setRelation('siswa', $siswa);
        $panggil->setRelation('guru', $guru);

        return view('bk.panggil-ortu.pdf', compact('panggil', 'sekolah'))->with('isPreview', true);
    }

    public function downloadPdf($id)
    {
        $panggil = PanggilOrtu::with(['siswa.kelas', 'guru'])->findOrFail($id);
        $sekolah = Sekolah::first();

        $pdf = Pdf::loadView('bk.panggil-ortu.pdf', compact('panggil', 'sekolah'));
        
        $filename = 'surat_pemanggilan_' . ($panggil->jenis_panggilan === 'panggilan_biasa' ? 'undangan' : $panggil->jenis_panggilan) . '_' . $panggil->nis . '.pdf';
        return $pdf->download($filename);
    }
}
