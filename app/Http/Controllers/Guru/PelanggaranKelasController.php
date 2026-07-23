<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\PelanggaranKelas;
use App\Models\Kelas;
use App\Models\UserSiswa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PelanggaranKelasController extends Controller
{
    private array $daftarJenis;

    public function __construct()
    {
        $this->daftarJenis = PelanggaranKelas::daftarJenis();
    }

    // ──────────────────────────────────────────────────────────────
    //  INDEX — Daftar pelanggaran + filter kelas
    // ──────────────────────────────────────────────────────────────

    public function index(Request $request)
    {
        $kelasList = Kelas::where('status', 'aktif')
            ->orderBy('tingkat')
            ->orderBy('rombel')
            ->get();

        $query = PelanggaranKelas::with(['siswa.kelas', 'guru'])
            ->orderByDesc('tanggal')
            ->orderByDesc('id_pelanggaran_kelas');

        if ($request->filled('id_kelas')) {
            $query->where('id_kelas', $request->id_kelas);
        }

        if ($request->filled('tanggal_dari')) {
            $query->where('tanggal', '>=', $request->tanggal_dari);
        }

        if ($request->filled('tanggal_sampai')) {
            $query->where('tanggal', '<=', $request->tanggal_sampai);
        }

        if ($request->filled('jenis')) {
            $query->where('jenis_pelanggaran', $request->jenis);
        }

        $data        = $query->paginate(20)->withQueryString();
        $daftarJenis = $this->daftarJenis;

        $totalHariIni  = PelanggaranKelas::whereDate('tanggal', today())->count();
        $totalBulanIni = PelanggaranKelas::whereMonth('tanggal', now()->month)
            ->whereYear('tanggal', now()->year)->count();
        $totalAll      = PelanggaranKelas::count();

        return view('guru.pelanggaran-kelas.index', compact(
            'data', 'kelasList', 'daftarJenis',
            'totalHariIni', 'totalBulanIni', 'totalAll'
        ));
    }

    // ──────────────────────────────────────────────────────────────
    //  STORE
    // ──────────────────────────────────────────────────────────────

    public function store(Request $request)
    {
        $request->validate([
            'tanggal'           => 'required|date',
            'nis'               => 'required|string|max:20|exists:user_siswa,nis',
            'id_kelas'          => 'required|integer|exists:kelas,id_kelas',
            'jenis_pelanggaran' => 'required|integer|min:1|max:8',
            'keterangan'        => 'nullable|string|max:500',
        ]);

        $guru = Auth::user();

        PelanggaranKelas::create([
            'tanggal'           => $request->tanggal,
            'nis'               => $request->nis,
            'id_kelas'          => $request->id_kelas,
            'jenis_pelanggaran' => $request->jenis_pelanggaran,
            'keterangan'        => $request->keterangan,
            'id_guru'           => $guru->id_guru ?? $guru->id ?? 1,
        ]);

        return redirect()->route('guru-kelas.pelanggaran.index')
            ->with('success', 'Pelanggaran siswa berhasil dicatat.');
    }

    // ──────────────────────────────────────────────────────────────
    //  UPDATE
    // ──────────────────────────────────────────────────────────────

    public function update(Request $request, int $id)
    {
        $request->validate([
            'tanggal'           => 'required|date',
            'nis'               => 'required|string|max:20|exists:user_siswa,nis',
            'id_kelas'          => 'required|integer|exists:kelas,id_kelas',
            'jenis_pelanggaran' => 'required|integer|min:1|max:8',
            'keterangan'        => 'nullable|string|max:500',
        ]);

        PelanggaranKelas::findOrFail($id)->update($request->only([
            'tanggal', 'nis', 'id_kelas', 'jenis_pelanggaran', 'keterangan',
        ]));

        return redirect()->route('guru-kelas.pelanggaran.index')
            ->with('success', 'Catatan pelanggaran berhasil diperbarui.');
    }

    // ──────────────────────────────────────────────────────────────
    //  DESTROY
    // ──────────────────────────────────────────────────────────────

    public function destroy(int $id)
    {
        PelanggaranKelas::findOrFail($id)->delete();

        return redirect()->route('guru-kelas.pelanggaran.index')
            ->with('success', 'Catatan pelanggaran berhasil dihapus.');
    }

    // ──────────────────────────────────────────────────────────────
    //  REKAP — Rekap pelanggaran per siswa, filter by kelas
    // ──────────────────────────────────────────────────────────────

    public function rekap(Request $request)
    {
        $kelasList   = Kelas::where('status', 'aktif')
            ->orderBy('tingkat')
            ->orderBy('rombel')
            ->get();

        $selectedKelasId = $request->input('id_kelas');
        $daftarJenis     = $this->daftarJenis;

        $rekapSiswa = collect();

        if ($selectedKelasId) {
            $siswaDiKelas = UserSiswa::where('id_kelas', $selectedKelasId)
                ->where('status', 'aktif')
                ->orderBy('nama_siswa')
                ->get();

            foreach ($siswaDiKelas as $siswa) {
                $pelanggaran = PelanggaranKelas::where('nis', $siswa->nis)
                    ->where('id_kelas', $selectedKelasId)
                    ->get();

                $perJenis = [];
                for ($j = 1; $j <= 8; $j++) {
                    $perJenis[$j] = $pelanggaran->where('jenis_pelanggaran', $j)->count();
                }

                $rekapSiswa->push([
                    'siswa'    => $siswa,
                    'total'    => $pelanggaran->count(),
                    'perJenis' => $perJenis,
                    'terbaru'  => $pelanggaran->sortByDesc('tanggal')->first(),
                ]);
            }

            // Sort by total descending
            $rekapSiswa = $rekapSiswa->sortByDesc('total')->values();
        }

        $selectedKelas = $selectedKelasId
            ? Kelas::find($selectedKelasId)
            : null;

        return view('guru.pelanggaran-kelas.rekap', compact(
            'kelasList', 'selectedKelasId', 'selectedKelas',
            'rekapSiswa', 'daftarJenis'
        ));
    }

    // ──────────────────────────────────────────────────────────────
    //  AJAX — Siswa by kelas
    // ──────────────────────────────────────────────────────────────

    public function getSiswaByKelas(Request $request)
    {
        $siswa = UserSiswa::where('id_kelas', $request->id_kelas)
            ->where('status', 'aktif')
            ->orderBy('nama_siswa')
            ->get(['nis', 'nama_siswa']);

        return response()->json($siswa);
    }
}
