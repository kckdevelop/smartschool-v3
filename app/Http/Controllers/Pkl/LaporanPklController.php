<?php

namespace App\Http\Controllers\Pkl;

use App\Http\Controllers\Controller;
use App\Models\PklPenempatan;
use App\Models\PklGelombang;
use App\Models\PklDudi;
use App\Models\Sekolah;
use Illuminate\Http\Request;

class LaporanPklController extends Controller
{
    public function index(Request $request)
    {
        $gelombangList = PklGelombang::orderByDesc('id_gelombang')->get();
        $gelombangAktif = PklGelombang::where('status', 'aktif')->first();
        $selectedGelombang = $request->filled('id_gelombang')
            ? PklGelombang::find($request->id_gelombang)
            : $gelombangAktif;

        // Query rekap siswa
        $query = PklPenempatan::with(['siswa.kelas', 'dudi', 'pembimbing.guru']);

        if ($selectedGelombang) {
            $query->where('id_gelombang', $selectedGelombang->id_gelombang);
        }
        if ($request->filled('id_dudi')) {
            $query->where('id_dudi', $request->id_dudi);
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $penempatanAll = $query->get();

        // Paginasi untuk tabel
        $perPage    = (int) $request->input('per_page', 20);
        $penempatan = $query->paginate($perPage)->withQueryString();

        // Rekapitulasi per DUDI
        $rekapDudi = collect();
        if ($selectedGelombang) {
            $rekapDudi = PklDudi::where('status', 'aktif')
                ->withCount([
                    'penempatan as total_siswa' => fn($q) => $q->where('id_gelombang', $selectedGelombang->id_gelombang),
                    'penempatan as aktif_siswa' => fn($q) => $q->where('id_gelombang', $selectedGelombang->id_gelombang)->where('status', 'aktif'),
                    'penempatan as selesai_siswa' => fn($q) => $q->where('id_gelombang', $selectedGelombang->id_gelombang)->where('status', 'selesai'),
                    'penempatan as ditarik_siswa' => fn($q) => $q->where('id_gelombang', $selectedGelombang->id_gelombang)->where('status', 'ditarik'),
                ])
                ->get()
                ->filter(fn($d) => $d->total_siswa > 0);
        }

        $dudis = PklDudi::where('status', 'aktif')->orderBy('nama_dudi')->get();

        return view('pkl.laporan.index', compact(
            'penempatan', 'penempatanAll', 'gelombangList', 'selectedGelombang',
            'rekapDudi', 'dudis'
        ));
    }

    public function print(Request $request)
    {
        $selectedGelombang = PklGelombang::find($request->id_gelombang);
        $sekolah = Sekolah::first();

        $query = PklPenempatan::with(['siswa.kelas', 'dudi', 'pembimbing.guru']);
        if ($selectedGelombang) {
            $query->where('id_gelombang', $selectedGelombang->id_gelombang);
        }
        if ($request->filled('id_dudi')) {
            $query->where('id_dudi', $request->id_dudi);
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $penempatan = $query->get();

        return view('pkl.laporan.print', compact('penempatan', 'selectedGelombang', 'sekolah'));
    }
}
