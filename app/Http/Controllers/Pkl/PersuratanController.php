<?php

namespace App\Http\Controllers\Pkl;

use App\Http\Controllers\Controller;
use App\Models\PklPersuratan;
use App\Models\PklPenempatan;
use App\Models\PklPembimbing;
use App\Models\PklGelombang;
use App\Models\PklDudi;
use App\Models\PklNomorSurat;
use App\Models\Sekolah;
use App\Models\PklKelasGelombang;
use App\Models\UserSiswa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class PersuratanController extends Controller
{
    public function index(Request $request)
    {
        $gelombangList  = PklGelombang::orderByDesc('id_gelombang')->get();
        $dudis          = PklDudi::where('status', 'aktif')->orderBy('nama_dudi')->get();
        $gelombangAktif = PklGelombang::where('status', 'aktif')->first();

        // 1. Query Arsip Surat Permohonan
        $queryPermohonan = PklPersuratan::with(['dudi', 'gelombang'])
            ->where('jenis_surat', 'permohonan')
            ->orderByDesc('id_surat');

        if ($request->filled('id_gelombang')) {
            $queryPermohonan->where('id_gelombang', $request->id_gelombang);
        }

        $dataPermohonan = $queryPermohonan->paginate(10, ['*'], 'page_permohonan')->withQueryString();

        // 2. Query Arsip Surat Penempatan & Penarikan
        $queryLainnya = PklPersuratan::with(['dudi', 'gelombang'])
            ->whereIn('jenis_surat', ['penempatan', 'penarikan'])
            ->orderByDesc('id_surat');

        if ($request->filled('jenis_surat_lainnya')) {
            $queryLainnya->where('jenis_surat', $request->jenis_surat_lainnya);
        }
        if ($request->filled('id_gelombang')) {
            $queryLainnya->where('id_gelombang', $request->id_gelombang);
        }

        $dataLainnya = $queryLainnya->paginate(10, ['*'], 'page_lainnya')->withQueryString();

        $activeTab = $request->get('tab', 'permohonan');

        return view('pkl.persuratan.index', compact(
            'dataPermohonan', 'dataLainnya', 'gelombangList', 'dudis', 'gelombangAktif', 'activeTab'
        ));
    }

    /**
     * AJAX Endpoint: Ambil daftar siswa berdasarkan DUDI dan Gelombang
     */
    public function getSiswaByDudi(Request $request)
    {
        $idGelombang = $request->id_gelombang;
        $idDudi      = $request->id_dudi;

        $dudiSiswa = [];
        if ($idGelombang && $idDudi) {
            $penempatanList = PklPenempatan::with(['siswa.kelas.jurusan'])
                ->where('id_gelombang', $idGelombang)
                ->where('id_dudi', $idDudi)
                ->whereIn('status', ['aktif', 'selesai'])
                ->get();

            foreach ($penempatanList as $p) {
                $dudiSiswa[] = [
                    'nis'        => (string)$p->nis,
                    'nama_siswa' => $p->siswa?->nama_siswa ?? $p->nis,
                    'nama_kelas' => optional(optional($p->siswa)->kelas)->nama_kelas ?? '-',
                    'keahlian'   => optional(optional(optional($p->siswa)->kelas)->jurusan)->nama_jurusan ?? '-',
                    'is_dudi'    => true,
                ];
            }
        }

        $allSiswa = [];
        if ($idGelombang) {
            $kelasIds = PklKelasGelombang::where('id_gelombang', $idGelombang)->pluck('id_kelas');
            if ($kelasIds->isNotEmpty()) {
                $siswaQuery = UserSiswa::with('kelas.jurusan')
                    ->whereIn('id_kelas', $kelasIds)
                    ->where('status', 'aktif')
                    ->orderBy('nama_siswa')
                    ->get();

                foreach ($siswaQuery as $s) {
                    $allSiswa[] = [
                        'nis'        => (string)$s->nis,
                        'nama_siswa' => $s->nama_siswa,
                        'nama_kelas' => optional($s->kelas)->nama_kelas ?? '-',
                        'keahlian'   => optional(optional($s->kelas)->jurusan)->nama_jurusan ?? '-',
                        'is_dudi'    => false,
                    ];
                }
            }
        }

        return response()->json([
            'dudi_siswa' => $dudiSiswa,
            'all_siswa'  => $allSiswa,
        ]);
    }

    /**
     * Generate surat dan simpan ke DB
     */
    /**
     * Generate surat dan simpan ke DB
     */
    public function generate(Request $request)
    {
        $request->validate([
            'jenis_surat'   => 'required|in:permohonan,penempatan,penarikan',
            'id_gelombang'  => 'required|integer',
            'id_dudi'       => 'required|integer',
            'tanggal_surat' => 'required|date',
            'siswa_list'    => 'nullable|array',
            'ttd'           => 'nullable|boolean',
        ]);

        $daftarSiswaData = [];
        if ($request->filled('siswa_list')) {
            foreach ($request->siswa_list as $item) {
                if (is_array($item)) {
                    $daftarSiswaData[] = [
                        'nis'        => $item['nis'] ?? '-',
                        'nama_siswa' => $item['nama_siswa'] ?? '',
                        'nama_kelas' => $item['nama_kelas'] ?? '',
                        'keahlian'   => $item['keahlian'] ?? '',
                    ];
                } else if (!empty($item)) {
                    $decoded = json_decode($item, true);
                    if (is_array($decoded)) {
                        $daftarSiswaData[] = $decoded;
                    } else {
                        $s = UserSiswa::with('kelas.jurusan')->find($item);
                        if ($s) {
                            $daftarSiswaData[] = [
                                'nis'        => (string)$s->nis,
                                'nama_siswa' => $s->nama_siswa,
                                'nama_kelas' => optional($s->kelas)->nama_kelas ?? '-',
                                'keahlian'   => optional(optional($s->kelas)->jurusan)->nama_jurusan ?? '-',
                            ];
                        }
                    }
                }
            }
        }

        $nomorSurat = PklNomorSurat::generateNomor($request->jenis_surat);

        $surat = PklPersuratan::create([
            'nomor_surat'   => $nomorSurat,
            'jenis_surat'   => $request->jenis_surat,
            'id_gelombang'  => $request->id_gelombang,
            'id_dudi'       => $request->id_dudi,
            'tanggal_surat' => $request->tanggal_surat,
            'hal'           => $this->getHal($request->jenis_surat),
            'daftar_siswa'  => !empty($daftarSiswaData) ? $daftarSiswaData : null,
            'dicetak_oleh'  => Auth::id(),
        ]);

        $ttd = $request->get('ttd', '1');

        return redirect()->route('pkl.persuratan.cetak', ['id' => $surat->id_surat, 'ttd' => $ttd]);
    }

    /**
     * Generate surat masal untuk semua DUDI yang terdaftar penempatan pada gelombang tersebut
     */
    public function generateMasal(Request $request)
    {
        $request->validate([
            'jenis_surat'   => 'required|in:penempatan,penarikan',
            'id_gelombang'  => 'required|integer',
            'tanggal_surat' => 'required|date',
            'ttd'           => 'nullable|boolean',
        ]);

        $idGelombang = $request->id_gelombang;
        $jenisSurat  = $request->jenis_surat;
        $tanggalSurat = $request->tanggal_surat;

        // Ambil semua id_dudi yang memiliki penempatan aktif/selesai di gelombang ini
        $dudiIds = PklPenempatan::where('id_gelombang', $idGelombang)
            ->whereIn('status', ['aktif', 'selesai'])
            ->distinct()
            ->pluck('id_dudi');

        if ($dudiIds->isEmpty()) {
            return back()->with('error', 'Tidak ada data penempatan siswa pada gelombang ini.');
        }

        $generatedCount = 0;
        $skippedCount = 0;

        foreach ($dudiIds as $idDudi) {
            // Cek apakah surat untuk DUDI ini pada gelombang ini sudah ada
            $exist = PklPersuratan::where('id_gelombang', $idGelombang)
                ->where('id_dudi', $idDudi)
                ->where('jenis_surat', $jenisSurat)
                ->first();

            if (!$exist) {
                $nomorSurat = PklNomorSurat::generateNomor($jenisSurat);
                PklPersuratan::create([
                    'nomor_surat'   => $nomorSurat,
                    'jenis_surat'   => $jenisSurat,
                    'id_gelombang'  => $idGelombang,
                    'id_dudi'       => $idDudi,
                    'tanggal_surat' => $tanggalSurat,
                    'hal'           => $this->getHal($jenisSurat),
                    'dicetak_oleh'  => Auth::id(),
                ]);
                $generatedCount++;
            } else {
                $skippedCount++;
            }
        }

        $ttd = $request->get('ttd', '1');

        return redirect()->route('pkl.persuratan.cetak-masal', [
            'jenis_surat'  => $jenisSurat,
            'id_gelombang' => $idGelombang,
            'ttd'          => $ttd,
        ])->with('success', "Berhasil men-generate {$generatedCount} surat baru. {$skippedCount} surat sudah ada sebelumnya.");
    }

    /**
     * Cetak semua surat (bulk print) untuk satu jenis surat & gelombang
     */
    public function cetakMasal(Request $request)
    {
        $request->validate([
            'jenis_surat'  => 'required|in:penempatan,penarikan',
            'id_gelombang' => 'required|integer',
        ]);

        $idGelombang = $request->id_gelombang;
        $jenisSurat  = $request->jenis_surat;
        $denganTtd   = $request->get('ttd', '1') == '1';

        $sekolah = Sekolah::first();
        $gelombang = PklGelombang::findOrFail($idGelombang);

        // Ambil semua surat yang terdaftar
        $suratList = PklPersuratan::with('dudi')
            ->where('id_gelombang', $idGelombang)
            ->where('jenis_surat', $jenisSurat)
            ->get();

        if ($suratList->isEmpty()) {
            return redirect()->route('pkl.persuratan.index')
                ->with('error', 'Belum ada surat yang di-generate untuk opsi tersebut.');
        }

        // Ambil penempatan untuk masing-masing surat
        $dataCetak = [];
        foreach ($suratList as $surat) {
            $penempatan = PklPenempatan::with(['siswa.kelas.jurusan'])
                ->where('id_gelombang', $idGelombang)
                ->where('id_dudi', $surat->id_dudi)
                ->whereIn('status', ['aktif', 'selesai'])
                ->get();

            $pembimbing = PklPembimbing::with('guru')
                ->where('id_gelombang', $idGelombang)
                ->where('id_dudi', $surat->id_dudi)
                ->first();

            $dataCetak[] = [
                'surat'      => $surat,
                'penempatan' => $penempatan,
                'pembimbing' => $pembimbing,
            ];
        }

        return view('pkl.persuratan.cetak-masal', compact('dataCetak', 'sekolah', 'gelombang', 'jenisSurat', 'denganTtd'));
    }

    public function cetak(Request $request, int $id)
    {
        $surat     = PklPersuratan::with(['dudi', 'gelombang'])->findOrFail($id);
        $sekolah   = Sekolah::first();
        $denganTtd = $request->get('ttd', '1') == '1';

        if (!empty($surat->daftar_siswa) && is_array($surat->daftar_siswa)) {
            $penempatan = collect($surat->daftar_siswa)->map(function ($item) {
                return (object)[
                    'nis'           => $item['nis'] ?? '-',
                    'tanggal_masuk' => $item['tanggal_masuk'] ?? null,
                    'siswa'         => (object)[
                        'nama_siswa' => $item['nama_siswa'] ?? '-',
                        'nis'        => $item['nis'] ?? '-',
                        'kelas'      => (object)[
                            'nama_kelas' => $item['nama_kelas'] ?? '-',
                            'jurusan'    => (object)[
                                'nama_jurusan' => $item['keahlian'] ?? '-'
                            ]
                        ]
                    ]
                ];
            });
        } else {
            $penempatan = PklPenempatan::with(['siswa.kelas.jurusan'])
                ->where('id_gelombang', $surat->id_gelombang)
                ->where('id_dudi', $surat->id_dudi)
                ->whereIn('status', ['aktif', 'selesai'])
                ->get();
        }

        $pembimbing = PklPembimbing::with('guru')
            ->where('id_gelombang', $surat->id_gelombang)
            ->where('id_dudi', $surat->id_dudi)
            ->first();

        $view = match($surat->jenis_surat) {
            'permohonan' => 'pkl.persuratan.cetak-permohonan',
            'penempatan' => 'pkl.persuratan.cetak-penempatan',
            'penarikan'  => 'pkl.persuratan.cetak-penarikan',
            default      => abort(404),
        };

        return view($view, compact('surat', 'sekolah', 'penempatan', 'pembimbing', 'denganTtd'));
    }

    public function destroy(int $id)
    {
        $surat = PklPersuratan::findOrFail($id);
        if ($surat->file_pdf && Storage::exists($surat->file_pdf)) {
            Storage::delete($surat->file_pdf);
        }
        $surat->delete();

        return back()->with('success', 'Surat berhasil dihapus.');
    }

    private function getHal(string $jenis): string
    {
        return match($jenis) {
            'permohonan' => 'Permohonan Praktik Kerja Lapangan (PKL)',
            'penempatan' => 'Surat Pengantar Penempatan Siswa PKL',
            'penarikan'  => 'Penarikan Siswa Praktik Kerja Lapangan',
            default      => 'Surat PKL',
        };
    }
}
