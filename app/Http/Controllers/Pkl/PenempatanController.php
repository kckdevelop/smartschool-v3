<?php

namespace App\Http\Controllers\Pkl;

use App\Http\Controllers\Controller;
use App\Models\PklPenempatan;
use App\Models\PklGelombang;
use App\Models\PklDudi;
use App\Models\PklPembimbing;
use App\Models\PklKelasGelombang;
use App\Models\UserSiswa;
use App\Models\Kelas;
use App\Models\Jurusan;
use Illuminate\Http\Request;

class PenempatanController extends Controller
{
    public function index(Request $request)
    {
        $gelombangList  = PklGelombang::orderByDesc('id_gelombang')->get();
        $gelombangAktif = PklGelombang::where('status', 'aktif')->first();

        // Referensi Gelombang: Default ke gelombang aktif jika tidak diset
        $selectedGelombang = $request->filled('id_gelombang')
            ? PklGelombang::find($request->id_gelombang)
            : ($gelombangAktif ?? $gelombangList->first());

        $idGelombang = optional($selectedGelombang)->id_gelombang;

        // Ambil SEMUA DUDI aktif (dengan relasi jurusan)
        $dudiQuery = PklDudi::with('jurusan')->where('status', 'aktif');
        if ($request->filled('search_dudi')) {
            $search = $request->search_dudi;
            $dudiQuery->where(function($q) use ($search) {
                $q->where('nama_dudi', 'like', '%' . $search . '%')
                  ->orWhere('kota', 'like', '%' . $search . '%')
                  ->orWhere('bidang_usaha', 'like', '%' . $search . '%')
                  ->orWhereHas('jurusan', function($j) use ($search) {
                      $j->where('nama_jurusan', 'like', '%' . $search . '%')
                        ->orWhere('kode_jurusan', 'like', '%' . $search . '%');
                  });
            });
        }
        if ($request->filled('id_jurusan')) {
            $dudiQuery->where('id_jurusan', $request->id_jurusan);
        }
        $allDudis = $dudiQuery->orderBy('nama_dudi')->get();

        // Map DUDI dengan data penempatan & kuota per GELOMBANG INI
        $dudisWithPenempatan = $allDudis->map(function ($dudi) use ($idGelombang) {
            $penempatanList = PklPenempatan::with(['siswa.kelas', 'pembimbing.guru'])
                ->where('id_dudi', $dudi->id_dudi)
                ->when($idGelombang, fn($q) => $q->where('id_gelombang', $idGelombang))
                ->whereIn('status', ['aktif', 'selesai', 'ditarik'])
                ->orderBy('id_penempatan')
                ->get();

            $terpakai = $penempatanList->whereIn('status', ['aktif', 'selesai'])->count();
            $sisaKuota = max(0, $dudi->kuota_siswa - $terpakai);

            return (object)[
                'dudi'           => $dudi,
                'penempatanList' => $penempatanList,
                'terpakai'       => $terpakai,
                'sisa_kuota'     => $sisaKuota,
            ];
        });

        // Kelompokkan DUDI Berdasarkan Jurusan yang ada di tabel jurusan
        $groupedDudis = $dudisWithPenempatan->groupBy(function ($item) {
            if ($item->dudi->jurusan) {
                return $item->dudi->jurusan->nama_jurusan;
            }
            return !empty($item->dudi->bidang_usaha) ? trim($item->dudi->bidang_usaha) : 'Umum / Lainnya';
        });

        // Ambil data penempatan flat untuk tabel alternatif
        $queryFlat = PklPenempatan::with(['siswa.kelas', 'dudi.jurusan', 'pembimbing.guru'])
            ->orderByDesc('id_penempatan');

        if ($idGelombang) {
            $queryFlat->where('id_gelombang', $idGelombang);
        }
        if ($request->filled('id_dudi')) {
            $queryFlat->where('id_dudi', $request->id_dudi);
        }
        if ($request->filled('status')) {
            $queryFlat->where('status', $request->status);
        }
        if ($request->filled('search_dudi')) {
            $search = $request->search_dudi;
            $queryFlat->where(function($q) use ($search) {
                $q->whereHas('siswa', function($s) use ($search) {
                    $s->where('nama_siswa', 'like', '%' . $search . '%')
                      ->orWhere('nis', 'like', '%' . $search . '%');
                })->orWhereHas('dudi', function($d) use ($search) {
                    $d->where('nama_dudi', 'like', '%' . $search . '%')
                      ->orWhere('kota', 'like', '%' . $search . '%');
                });
            });
        }
        if ($request->filled('id_jurusan')) {
            $queryFlat->whereHas('dudi', function($d) use ($request) {
                $d->where('id_jurusan', $request->id_jurusan);
            });
        }

        $data = $queryFlat->paginate(20)->withQueryString();

        $pembimbingList = PklPembimbing::with('guru')
            ->when($selectedGelombang, fn($q) =>
                $q->where('id_gelombang', $selectedGelombang->id_gelombang)
            )->get();

        $jurusanList = Jurusan::orderBy('nama_jurusan')->get();

        return view('pkl.penempatan.index', compact(
            'data', 'gelombangList', 'selectedGelombang', 'gelombangAktif',
            'allDudis', 'dudisWithPenempatan', 'groupedDudis', 'pembimbingList', 'jurusanList'
        ));
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_gelombang'   => 'required|integer',
            'id_dudi'        => 'required|integer',
            'nis_list'       => 'nullable|array',
            'nis'            => 'nullable|string',
            'id_pembimbing'  => 'nullable|integer',
            'tanggal_masuk'  => 'nullable|date',
            'tanggal_keluar' => 'nullable|date|after_or_equal:tanggal_masuk',
            'status'         => 'required|in:aktif,selesai,ditarik,batal',
            'keterangan'     => 'nullable|string',
        ]);

        $idGelombang = $request->id_gelombang;
        $idDudi      = $request->id_dudi;
        $dudi        = PklDudi::findOrFail($idDudi);

        // Kumpulkan daftar NIS (bisa array nis_list atau single nis)
        $nisList = [];
        if ($request->filled('nis_list') && is_array($request->nis_list)) {
            $nisList = array_unique(array_filter($request->nis_list));
        } elseif ($request->filled('nis')) {
            $nisList = [$request->nis];
        }

        if (empty($nisList)) {
            return back()->with('error', 'Silakan pilih atau isi setidaknya 1 siswa.');
        }

        $insertedCount = 0;
        $skippedCount = 0;

        foreach ($nisList as $nisItem) {
            // Cek duplikasi siswa di gelombang ini
            $exist = PklPenempatan::where('id_gelombang', $idGelombang)
                ->where('nis', $nisItem)
                ->whereIn('status', ['aktif', 'selesai'])
                ->exists();

            if ($exist) {
                $skippedCount++;
                continue;
            }

            // Cek kuota DUDI untuk gelombang ini
            if ($dudi->sisaKuota($idGelombang) <= 0) {
                return back()->with('error', "Kuota {$dudi->nama_dudi} sudah penuh untuk gelombang ini. {$insertedCount} siswa berhasil ditambahkan.");
            }

            PklPenempatan::create([
                'id_gelombang'   => $idGelombang,
                'id_dudi'        => $idDudi,
                'nis'            => $nisItem,
                'id_pembimbing'  => $request->id_pembimbing,
                'tanggal_masuk'  => $request->tanggal_masuk,
                'tanggal_keluar' => $request->tanggal_keluar,
                'status'         => $request->status ?? 'aktif',
                'keterangan'     => $request->keterangan,
            ]);

            $insertedCount++;
        }

        $msg = "Berhasil menempatkan {$insertedCount} siswa ke {$dudi->nama_dudi}.";
        if ($skippedCount > 0) {
            $msg .= " ({$skippedCount} siswa dilewati karena sudah memiliki penempatan di gelombang ini).";
        }

        return redirect()->route('pkl.penempatan.index', ['id_gelombang' => $idGelombang])
            ->with('success', $msg);
    }

    public function update(Request $request, int $id)
    {
        $penempatan = PklPenempatan::findOrFail($id);

        $request->validate([
            'id_dudi'        => 'required|integer',
            'id_pembimbing'  => 'nullable|integer',
            'tanggal_masuk'  => 'nullable|date',
            'tanggal_keluar' => 'nullable|date',
            'status'         => 'required|in:aktif,selesai,ditarik,batal',
            'keterangan'     => 'nullable|string',
        ]);

        $penempatan->update($request->only([
            'id_dudi', 'id_pembimbing', 'tanggal_masuk',
            'tanggal_keluar', 'status', 'keterangan',
        ]));

        return redirect()->route('pkl.penempatan.index', ['id_gelombang' => $penempatan->id_gelombang])
            ->with('success', 'Data penempatan berhasil diperbarui.');
    }

    public function destroy(int $id)
    {
        $penempatan = PklPenempatan::findOrFail($id);
        $idGelombang = $penempatan->id_gelombang;
        $penempatan->delete();

        return redirect()->route('pkl.penempatan.index', ['id_gelombang' => $idGelombang])
            ->with('success', 'Data penempatan berhasil dihapus.');
    }

    // API: Siswa dari kelas yang masuk gelombang
    public function getSiswaByGelombang(Request $request)
    {
        $idGelombang = $request->id_gelombang;
        $kelasIds = PklKelasGelombang::where('id_gelombang', $idGelombang)->pluck('id_kelas');
        $sudahDitempatkan = PklPenempatan::where('id_gelombang', $idGelombang)
            ->whereIn('status', ['aktif', 'selesai'])->pluck('nis');

        $siswa = UserSiswa::with('kelas')
            ->whereIn('id_kelas', $kelasIds)
            ->where('status', 'aktif')
            ->orderBy('nama_siswa')
            ->get()
            ->map(fn($s) => [
                'nis'          => $s->nis,
                'nama_siswa'   => $s->nama_siswa,
                'nama_kelas'   => $s->kelas->nama_kelas ?? '-',
                'sudah_ditempatkan' => $sudahDitempatkan->contains($s->nis),
            ]);

        return response()->json($siswa);
    }

    // Halaman: Daftar siswa belum ditempatkan
    public function belumDitempatkan(Request $request)
    {
        $gelombangList  = PklGelombang::orderByDesc('id_gelombang')->get();
        $gelombangAktif = PklGelombang::where('status', 'aktif')->first();
        $selectedGelombang = $request->filled('id_gelombang')
            ? PklGelombang::find($request->id_gelombang)
            : $gelombangAktif;

        $siswaList = collect();
        if ($selectedGelombang) {
            $kelasIds = PklKelasGelombang::where('id_gelombang', $selectedGelombang->id_gelombang)
                ->pluck('id_kelas');
            $sudahNis = PklPenempatan::where('id_gelombang', $selectedGelombang->id_gelombang)
                ->whereIn('status', ['aktif', 'selesai'])->pluck('nis');
            $siswaList = UserSiswa::with(['kelas.jurusan'])
                ->whereIn('id_kelas', $kelasIds)
                ->where('status', 'aktif')
                ->whereNotIn('nis', $sudahNis)
                ->when($request->filled('search'), fn($q) =>
                    $q->where(fn($q2) =>
                        $q2->where('nama_siswa', 'like', '%'.$request->search.'%')
                           ->orWhere('nis', 'like', '%'.$request->search.'%')
                    )
                )
                ->orderBy('nama_siswa')
                ->paginate(25)->withQueryString();
        }

        return view('pkl.penempatan.belum-ditempatkan', compact(
            'gelombangList', 'selectedGelombang', 'siswaList'
        ));
    }

    // Quick Place: Penempatan langsung dari halaman belum ditempatkan
    public function quickPlace(Request $request)
    {
        $request->validate([
            'id_gelombang' => 'required|integer',
            'nis'          => 'required|string',
            'id_dudi'      => 'required|integer',
        ]);

        $exist = PklPenempatan::where('id_gelombang', $request->id_gelombang)
            ->where('nis', $request->nis)
            ->whereIn('status', ['aktif', 'selesai'])->exists();
        if ($exist) {
            return back()->with('error', 'Siswa sudah memiliki penempatan aktif.');
        }

        $dudi = PklDudi::findOrFail($request->id_dudi);
        if ($dudi->sisaKuota($request->id_gelombang) <= 0) {
            return back()->with('error', 'Kuota DUDI sudah penuh untuk gelombang ini.');
        }

        // Ambil tanggal dari gelombang
        $gelombang = PklGelombang::findOrFail($request->id_gelombang);
        PklPenempatan::create([
            'id_gelombang'  => $request->id_gelombang,
            'nis'           => $request->nis,
            'id_dudi'       => $request->id_dudi,
            'tanggal_masuk' => $gelombang->tanggal_mulai,
            'tanggal_keluar'=> $gelombang->tanggal_selesai,
            'status'        => 'aktif',
        ]);

        return back()->with('success', 'Siswa berhasil ditempatkan.');
    }

    // API: DUDI berdasarkan jurusan siswa (untuk modal quick place)
    public function getDudiByJurusan(Request $request)
    {
        $idGelombang = $request->id_gelombang;
        $idJurusan   = $request->id_jurusan;

        // DUDI yang relevan: id_jurusan cocok atau bidang_usaha cocok jurusan
        $dudis = PklDudi::where('status', 'aktif')
            ->when($idJurusan, function($q) use ($idJurusan) {
                $jurusan = \App\Models\Jurusan::find($idJurusan);
                $q->where(function($q2) use ($idJurusan, $jurusan) {
                    $q2->where('id_jurusan', $idJurusan)
                       ->orWhereNull('id_jurusan');
                    if ($jurusan) {
                        $q2->orWhere('bidang_usaha', 'like', '%'.$jurusan->nama_jurusan.'%')
                           ->orWhere('bidang_usaha', 'like', '%'.$jurusan->kode_jurusan.'%');
                    }
                });
            })
            ->get()
            ->map(function($d) use ($idGelombang) {
                $sisa = $idGelombang ? $d->sisaKuota((int)$idGelombang) : $d->kuota_siswa;
                return [
                    'id_dudi'     => $d->id_dudi,
                    'nama_dudi'   => $d->nama_dudi,
                    'bidang_usaha'=> $d->bidang_usaha,
                    'kota'        => $d->kota,
                    'kuota_siswa' => $d->kuota_siswa,
                    'sisa_kuota'  => $sisa,
                ];
            });

        // Jika filter jurusan tapi hasilnya kosong, kembalikan semua
        if ($idJurusan && $dudis->isEmpty()) {
            $dudis = PklDudi::where('status', 'aktif')->get()->map(function($d) use ($idGelombang) {
                $sisa = $idGelombang ? $d->sisaKuota((int)$idGelombang) : $d->kuota_siswa;
                return [
                    'id_dudi'     => $d->id_dudi,
                    'nama_dudi'   => $d->nama_dudi,
                    'bidang_usaha'=> $d->bidang_usaha,
                    'kota'        => $d->kota,
                    'kuota_siswa' => $d->kuota_siswa,
                    'sisa_kuota'  => $sisa,
                ];
            });
        }

        return response()->json($dudis);
    }
}
