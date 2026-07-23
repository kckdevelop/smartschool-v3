<?php

namespace App\Http\Controllers\Pkl;

use App\Http\Controllers\Controller;
use App\Models\PklPenempatan;
use App\Models\PklRiwayatPindah;
use App\Models\PklGelombang;
use App\Models\PklDudi;
use App\Models\PklPembimbing;
use App\Models\PklKelasGelombang;
use App\Models\UserSiswa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PindahPenempatanController extends Controller
{
    /**
     * Halaman utama: daftar riwayat pindah + form
     */
    public function index(Request $request)
    {
        $gelombangList  = PklGelombang::orderByDesc('id_gelombang')->get();
        $gelombangAktif = PklGelombang::where('status', 'aktif')->first();

        $selectedGelombang = $request->filled('id_gelombang')
            ? PklGelombang::find($request->id_gelombang)
            : $gelombangAktif;

        // Query riwayat perpindahan
        $query = PklRiwayatPindah::with([
            'siswa.kelas',
            'gelombang',
            'penempatanLama.dudi',
            'penempatanBaru.dudi',
        ])->orderByDesc('tanggal_pindah');

        if ($selectedGelombang) {
            $query->where('id_gelombang', $selectedGelombang->id_gelombang);
        }
        if ($request->filled('nis')) {
            $query->where('nis', $request->nis);
        }

        $riwayat = $query->paginate(20)->withQueryString();

        // Siswa yang sudah ditempatkan (aktif) di gelombang ini — untuk form pindah
        $siswaAktif = collect();
        if ($selectedGelombang) {
            $siswaAktif = PklPenempatan::with(['siswa.kelas', 'dudi'])
                ->where('id_gelombang', $selectedGelombang->id_gelombang)
                ->where('status', 'aktif')
                ->orderBy('nis')
                ->get();
        }

        $dudis = PklDudi::where('status', 'aktif')->orderBy('nama_dudi')->get();

        $pembimbingList = PklPembimbing::with('guru')
            ->when($selectedGelombang, fn($q) =>
                $q->where('id_gelombang', $selectedGelombang->id_gelombang)
            )->get();

        return view('pkl.pindah-penempatan.index', compact(
            'gelombangList', 'selectedGelombang',
            'riwayat', 'siswaAktif', 'dudis', 'pembimbingList'
        ));
    }

    /**
     * Proses perpindahan penempatan siswa
     */
    public function store(Request $request)
    {
        $request->validate([
            'id_penempatan_lama' => 'required|integer|exists:pkl_penempatan,id_penempatan',
            'id_dudi_baru'       => 'required|integer|exists:pkl_dudi,id_dudi',
            'id_pembimbing_baru' => 'nullable|integer',
            'tanggal_pindah'     => 'required|date',
            'tanggal_keluar_est' => 'nullable|date|after_or_equal:tanggal_pindah',
            'alasan'             => 'nullable|string|max:1000',
        ]);

        $penempatanLama = PklPenempatan::findOrFail($request->id_penempatan_lama);

        // Pastikan penempatan masih aktif
        if ($penempatanLama->status !== 'aktif') {
            return back()->with('error', 'Penempatan siswa ini sudah tidak aktif, tidak bisa dipindah.');
        }

        // Cek DUDI baru tidak sama dengan yang lama
        if ($penempatanLama->id_dudi == $request->id_dudi_baru) {
            return back()->with('error', 'DUDI tujuan harus berbeda dengan DUDI saat ini.');
        }

        // Cek kuota DUDI baru
        $dudiTujuan = PklDudi::findOrFail($request->id_dudi_baru);
        if ($dudiTujuan->sisaKuota($penempatanLama->id_gelombang) <= 0) {
            return back()->with('error', 'Kuota DUDI tujuan sudah penuh untuk gelombang ini.');
        }

        DB::transaction(function () use ($request, $penempatanLama) {
            // 1. Tutup penempatan lama → status 'pindah'
            $penempatanLama->update([
                'status'         => 'pindah',
                'tanggal_keluar' => $request->tanggal_pindah,
                'keterangan'     => 'Pindah ke DUDI lain. ' . ($request->alasan ?? ''),
            ]);

            // 2. Buat penempatan baru
            $penempatanBaru = PklPenempatan::create([
                'id_gelombang'   => $penempatanLama->id_gelombang,
                'id_dudi'        => $request->id_dudi_baru,
                'nis'            => $penempatanLama->nis,
                'id_pembimbing'  => $request->id_pembimbing_baru ?: null,
                'tanggal_masuk'  => $request->tanggal_pindah,
                'tanggal_keluar' => $request->tanggal_keluar_est ?: null,
                'status'         => 'aktif',
                'keterangan'     => 'Pindahan dari penempatan #' . $penempatanLama->id_penempatan,
            ]);

            // 3. Catat riwayat perpindahan
            PklRiwayatPindah::create([
                'nis'                => $penempatanLama->nis,
                'id_gelombang'       => $penempatanLama->id_gelombang,
                'id_penempatan_lama' => $penempatanLama->id_penempatan,
                'id_penempatan_baru' => $penempatanBaru->id_penempatan,
                'tanggal_pindah'     => $request->tanggal_pindah,
                'alasan'             => $request->alasan,
                'dicatat_oleh'       => Auth::id(),
            ]);
        });

        return redirect()
            ->route('pkl.pindah-penempatan.index', ['id_gelombang' => $penempatanLama->id_gelombang])
            ->with('success', 'Perpindahan penempatan berhasil dicatat.');
    }

    /**
     * Riwayat perpindahan per siswa (response JSON untuk modal timeline)
     */
    public function historyByNis(string $nis, Request $request)
    {
        $idGelombang = $request->id_gelombang;

        $history = PklRiwayatPindah::with([
            'penempatanLama.dudi',
            'penempatanBaru.dudi',
            'gelombang',
        ])
        ->where('nis', $nis)
        ->when($idGelombang, fn($q) => $q->where('id_gelombang', $idGelombang))
        ->orderBy('tanggal_pindah')
        ->get();

        $siswa = UserSiswa::with('kelas')->where('nis', $nis)->first();

        return response()->json([
            'siswa'   => $siswa ? [
                'nis'        => $siswa->nis,
                'nama_siswa' => $siswa->nama_siswa,
                'nama_kelas' => optional($siswa->kelas)->nama_kelas,
            ] : null,
            'history' => $history->map(fn($r) => [
                'id'               => $r->id,
                'tanggal_pindah'   => $r->tanggal_pindah->format('d/m/Y'),
                'dudi_lama'        => optional(optional($r->penempatanLama)->dudi)->nama_dudi ?? '-',
                'dudi_baru'        => optional(optional($r->penempatanBaru)->dudi)->nama_dudi ?? '-',
                'alasan'           => $r->alasan,
                'gelombang'        => optional($r->gelombang)->nama_gelombang,
                'id_penempatan_lama' => $r->id_penempatan_lama,
                'id_penempatan_baru' => $r->id_penempatan_baru,
            ]),
        ]);
    }

    /**
     * API: ambil pembimbing berdasarkan DUDI + gelombang (untuk auto-fill form)
     */
    public function getPembimbingByDudi(Request $request)
    {
        $idDudi      = $request->id_dudi;
        $idGelombang = $request->id_gelombang;

        if (!$idDudi || !$idGelombang) {
            return response()->json([]);
        }

        $pembimbing = PklPembimbing::with('guru')
            ->where('id_gelombang', $idGelombang)
            ->where('id_dudi', $idDudi)
            ->get()
            ->map(fn($p) => [
                'id_pembimbing' => $p->id_pembimbing,
                'nama_guru'     => optional($p->guru)->nama_guru ?? '-',
            ]);

        return response()->json($pembimbing);
    }

    /**
     * API: cari siswa aktif berdasarkan nama/NIS di gelombang tertentu
     */
    public function searchSiswaAktif(Request $request)
    {
        $idGelombang = $request->id_gelombang;
        $keyword     = $request->q;

        if (!$idGelombang) {
            return response()->json([]);
        }

        $results = PklPenempatan::with(['siswa.kelas', 'dudi'])
            ->where('id_gelombang', $idGelombang)
            ->where('status', 'aktif')
            ->whereHas('siswa', function ($q) use ($keyword) {
                if ($keyword) {
                    $q->where('nama_siswa', 'like', "%{$keyword}%")
                      ->orWhere('nis', 'like', "%{$keyword}%");
                }
            })
            ->orderBy('nis')
            ->limit(30)
            ->get()
            ->map(fn($p) => [
                'id_penempatan' => $p->id_penempatan,
                'nis'           => $p->nis,
                'nama_siswa'    => optional($p->siswa)->nama_siswa ?? $p->nis,
                'nama_kelas'    => optional(optional($p->siswa)->kelas)->nama_kelas ?? '-',
                'nama_dudi'     => optional($p->dudi)->nama_dudi ?? '-',
            ]);

        return response()->json($results);
    }
}
