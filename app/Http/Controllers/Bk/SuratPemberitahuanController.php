<?php

namespace App\Http\Controllers\Bk;

use App\Http\Controllers\Controller;
use App\Models\SuratPemberitahuan;
use App\Models\PanggilOrtu;
use App\Models\UserSiswa;
use App\Models\Kelas;
use App\Models\Sekolah;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;

class SuratPemberitahuanController extends Controller
{
    public function index(Request $request)
    {
        $kelas = Kelas::where('status', 'aktif')->orderBy('tingkat')->orderBy('rombel')->get();
        $query = SuratPemberitahuan::with(['siswa.kelas.guru', 'guru', 'sp1'])->orderByDesc('tanggal_surat');

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
        return view('bk.surat-pemberitahuan.index', compact('data', 'kelas'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'tanggal_surat'        => 'required|date',
            'nis'                  => 'required|string|max:20',
            'alasan_pemberitahuan' => 'required|string',
            'no_surat'             => 'nullable|string|max:100|unique:surat_pemberitahuan,no_surat',
        ]);

        $guru = Auth::user();
        SuratPemberitahuan::create([
            'no_surat'             => $request->no_surat,
            'tanggal_surat'        => $request->tanggal_surat,
            'nis'                  => $request->nis,
            'nama_ortu'            => $request->nama_ortu,
            'no_hp_ortu'           => $request->no_hp_ortu,
            'alasan_pemberitahuan' => $request->alasan_pemberitahuan,
            'tindakan_sekolah'     => $request->tindakan_sekolah,
            'status'               => $request->status ?? 'diterbitkan',
            'id_guru'              => $guru->id_guru ?? 1,
        ]);

        return redirect()->route('bk.surat-pemberitahuan.index')
            ->with('success', 'Surat Pemberitahuan Orang Tua berhasil dibuat.');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'tanggal_surat'        => 'required|date',
            'nis'                  => 'required|string|max:20',
            'alasan_pemberitahuan' => 'required|string',
            'no_surat'             => 'nullable|string|max:100|unique:surat_pemberitahuan,no_surat,' . $id . ',id_pemberitahuan',
            'status'               => 'required|in:diterbitkan,disampaikan,lanjut_sp1,selesai',
        ]);

        $surat = SuratPemberitahuan::findOrFail($id);
        $data = $request->only([
            'no_surat', 'tanggal_surat', 'nis', 'nama_ortu', 'no_hp_ortu',
            'alasan_pemberitahuan', 'tindakan_sekolah', 'status'
        ]);

        $surat->update($data);

        return redirect()->route('bk.surat-pemberitahuan.index')
            ->with('success', 'Surat Pemberitahuan Orang Tua berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $surat = SuratPemberitahuan::findOrFail($id);
        $surat->delete();

        return redirect()->route('bk.surat-pemberitahuan.index')
            ->with('success', 'Surat Pemberitahuan Orang Tua berhasil dihapus.');
    }

    public function getSiswaDetail(Request $request)
    {
        $siswa = UserSiswa::with(['kelas.guru', 'detail'])->where('nis', $request->nis)->first();
        if (!$siswa) {
            return response()->json(['message' => 'Siswa tidak ditemukan'], 404);
        }

        $namaOrtu = $siswa->detail->nama_wali ?? $siswa->detail->nama_ayah ?? $siswa->detail->nama_ibu ?? '';
        $noHpOrtu = $siswa->detail->no_telp_wali ?? $siswa->detail->no_telp_ayah ?? $siswa->detail->no_telp_ibu ?? '';
        $waliKelasName = $siswa->kelas && $siswa->kelas->guru ? $siswa->kelas->guru->nama_guru : '-';
        $waliKelasNip = $siswa->kelas && $siswa->kelas->guru ? ($siswa->kelas->guru->no_id ?? '-') : '-';

        return response()->json([
            'nis'             => $siswa->nis,
            'nama_siswa'      => $siswa->nama_siswa,
            'nama_kelas'      => $siswa->kelas ? $siswa->kelas->nama_kelas : '-',
            'nama_ortu'       => $namaOrtu,
            'no_hp_ortu'      => $noHpOrtu,
            'alamat'          => $siswa->detail->alamat ?? '',
            'nama_wali_kelas' => $waliKelasName,
            'nip_wali_kelas'  => $waliKelasNip,
        ]);
    }

    public function searchSiswa(Request $request)
    {
        $q = $request->get('q', '');
        $siswa = UserSiswa::with(['kelas.guru', 'detail'])
            ->where(function ($query) use ($q) {
                $query->where('nama_siswa', 'like', "%{$q}%")
                      ->orWhere('nis', 'like', "%{$q}%");
            })
            ->where('status', 'aktif')
            ->orderBy('nama_siswa')
            ->limit(15)
            ->get();

        return response()->json($siswa->map(function ($s) {
            $namaOrtu = $s->detail->nama_wali ?? $s->detail->nama_ayah ?? $s->detail->nama_ibu ?? '';
            $noHpOrtu = $s->detail->no_telp_wali ?? $s->detail->no_telp_ayah ?? $s->detail->no_telp_ibu ?? '';
            $waliKelasName = $s->kelas && $s->kelas->guru ? $s->kelas->guru->nama_guru : '-';
            $waliKelasNip = $s->kelas && $s->kelas->guru ? ($s->kelas->guru->no_id ?? '-') : '-';

            return [
                'nis'             => $s->nis,
                'nama_siswa'      => $s->nama_siswa,
                'nama_kelas'      => $s->kelas ? $s->kelas->nama_kelas : '-',
                'nama_ortu'       => $namaOrtu,
                'no_hp_ortu'      => $noHpOrtu,
                'nama_wali_kelas' => $waliKelasName,
                'nip_wali_kelas'  => $waliKelasNip,
            ];
        }));
    }

    public function preview(Request $request)
    {
        $request->validate([
            'tanggal_surat'        => 'required|date',
            'nis'                  => 'required|string|max:20',
            'alasan_pemberitahuan' => 'required|string',
            'no_surat'             => 'nullable|string|max:100',
        ]);

        $sekolah = Sekolah::first();
        $siswa = UserSiswa::with(['kelas.guru', 'detail'])->where('nis', $request->nis)->first();
        $guru = Auth::user();

        $surat = new SuratPemberitahuan([
            'no_surat'             => $request->no_surat,
            'tanggal_surat'        => $request->tanggal_surat,
            'nis'                  => $request->nis,
            'nama_ortu'            => $request->nama_ortu,
            'no_hp_ortu'           => $request->no_hp_ortu,
            'alasan_pemberitahuan' => $request->alasan_pemberitahuan,
            'tindakan_sekolah'     => $request->tindakan_sekolah,
            'created_at'           => now(),
        ]);

        $surat->setRelation('siswa', $siswa);
        $surat->setRelation('guru', $guru);

        return view('bk.surat-pemberitahuan.pdf', compact('surat', 'sekolah'))->with('isPreview', true);
    }

    public function downloadPdf($id)
    {
        $surat = SuratPemberitahuan::with(['siswa.kelas.guru', 'siswa.detail', 'guru'])->findOrFail($id);
        $sekolah = Sekolah::first();

        $pdf = Pdf::loadView('bk.surat-pemberitahuan.pdf', compact('surat', 'sekolah'))
            ->setPaper('a4', 'portrait')
            ->setOption('isRemoteEnabled', true);
        
        $filename = 'surat_pemberitahuan_ortu_' . $surat->nis . '.pdf';
        return $pdf->download($filename);
    }

    public function escalateSp1(Request $request, $id)
    {
        $request->validate([
            'tanggal_panggil'  => 'required|date',
            'waktu_pertemuan'  => 'required',
            'lokasi_pertemuan' => 'required|string|max:255',
            'no_surat_sp1'     => 'nullable|string|max:100|unique:panggil_ortu,no_surat',
        ]);

        $surat = SuratPemberitahuan::with('siswa')->findOrFail($id);
        $guru = Auth::user();

        // Buat record Pemanggilan Ortu dengan jenis SP 1
        $panggilSp1 = PanggilOrtu::create([
            'no_surat'        => $request->no_surat_sp1,
            'tanggal_panggil' => $request->tanggal_panggil,
            'waktu_pertemuan' => $request->waktu_pertemuan,
            'lokasi_pertemuan' => $request->lokasi_pertemuan,
            'nis'             => $surat->nis,
            'nama_ortu'       => $surat->nama_ortu,
            'no_hp_ortu'      => $surat->no_hp_ortu,
            'jenis_panggilan' => 'sp_1',
            'alasan_panggil'  => $surat->alasan_pemberitahuan . ($request->catatan_tambahan ? "\nCatatan Tambahan: " . $request->catatan_tambahan : ''),
            'status'          => 'belum_hadir',
            'id_guru'         => $guru->id_guru ?? $surat->id_guru ?? 1,
        ]);

        // Update status surat pemberitahuan menjadi lanjut_sp1 & hubungkan ke id_panggil_sp1
        $surat->update([
            'status'         => 'lanjut_sp1',
            'id_panggil_sp1' => $panggilSp1->id_panggil,
        ]);

        return redirect()->route('bk.panggil-ortu.index')
            ->with('success', 'Surat Pemberitahuan berhasil dilanjutkan menjadi Surat Peringatan 1 (SP 1).');
    }
}
