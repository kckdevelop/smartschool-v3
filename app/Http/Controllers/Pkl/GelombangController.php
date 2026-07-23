<?php

namespace App\Http\Controllers\Pkl;

use App\Http\Controllers\Controller;
use App\Models\PklGelombang;
use App\Models\PklKelasGelombang;
use App\Models\Kelas;
use Illuminate\Http\Request;

class GelombangController extends Controller
{
    public function index(Request $request)
    {
        $data = PklGelombang::withCount(['penempatan', 'siswa'])
            ->with('kelasGelombang.kelas')
            ->orderByDesc('id_gelombang')
            ->paginate(15)->withQueryString();

        return view('pkl.gelombang.index', compact('data'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_gelombang'  => 'required|string|max:100',
            'tahun_ajaran'    => 'required|string|max:20',
            'tanggal_mulai'   => 'required|date',
            'tanggal_selesai' => 'required|date|after:tanggal_mulai',
            'status'          => 'required|in:draft,aktif,selesai',
            'keterangan'      => 'nullable|string',
            'id_kelas'        => 'nullable|array',
            'id_kelas.*'      => 'integer',
        ]);

        // Hanya boleh 1 gelombang aktif
        if ($request->status === 'aktif') {
            PklGelombang::where('status', 'aktif')->update(['status' => 'draft']);
        }

        $gelombang = PklGelombang::create($request->only([
            'nama_gelombang', 'tahun_ajaran', 'tanggal_mulai',
            'tanggal_selesai', 'status', 'keterangan',
        ]));

        // Simpan kelas yang dipilih
        if ($request->filled('id_kelas')) {
            foreach ($request->id_kelas as $idKelas) {
                PklKelasGelombang::create([
                    'id_gelombang' => $gelombang->id_gelombang,
                    'id_kelas'     => $idKelas,
                ]);
            }
        }

        return redirect()->route('pkl.gelombang.index')
            ->with('success', 'Gelombang PKL berhasil ditambahkan.');
    }

    public function update(Request $request, int $id)
    {
        $gelombang = PklGelombang::findOrFail($id);

        $request->validate([
            'nama_gelombang'  => 'required|string|max:100',
            'tahun_ajaran'    => 'required|string|max:20',
            'tanggal_mulai'   => 'required|date',
            'tanggal_selesai' => 'required|date|after:tanggal_mulai',
            'status'          => 'required|in:draft,aktif,selesai',
            'keterangan'      => 'nullable|string',
            'id_kelas'        => 'nullable|array',
            'id_kelas.*'      => 'integer',
        ]);

        if ($request->status === 'aktif') {
            PklGelombang::where('status', 'aktif')
                ->where('id_gelombang', '!=', $id)
                ->update(['status' => 'draft']);
        }

        $gelombang->update($request->only([
            'nama_gelombang', 'tahun_ajaran', 'tanggal_mulai',
            'tanggal_selesai', 'status', 'keterangan',
        ]));

        // Sync kelas
        PklKelasGelombang::where('id_gelombang', $id)->delete();
        if ($request->filled('id_kelas')) {
            foreach ($request->id_kelas as $idKelas) {
                PklKelasGelombang::create([
                    'id_gelombang' => $id,
                    'id_kelas'     => $idKelas,
                ]);
            }
        }

        return redirect()->route('pkl.gelombang.index')
            ->with('success', 'Gelombang PKL berhasil diperbarui.');
    }

    public function destroy(int $id)
    {
        $gelombang = PklGelombang::findOrFail($id);

        \Illuminate\Support\Facades\DB::beginTransaction();
        try {
            // Hapus semua data terkait gelombang
            \Illuminate\Support\Facades\DB::table('pkl_kelas_gelombang')->where('id_gelombang', $id)->delete();
            \Illuminate\Support\Facades\DB::table('pkl_penempatan')->where('id_gelombang', $id)->delete();
            \Illuminate\Support\Facades\DB::table('pkl_pembimbing')->where('id_gelombang', $id)->delete();
            \Illuminate\Support\Facades\DB::table('pkl_persuratan')->where('id_gelombang', $id)->delete();
            \Illuminate\Support\Facades\DB::table('pkl_riwayat_pindah')->where('id_gelombang', $id)->delete();

            $gelombang->delete();

            \Illuminate\Support\Facades\DB::commit();
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\DB::rollBack();
            return back()->with('error', 'Gagal menghapus gelombang: ' . $e->getMessage());
        }

        return redirect()->route('pkl.gelombang.index')
            ->with('success', 'Gelombang PKL beserta seluruh data terkait berhasil dihapus.');
    }

    // API: Ambil kelas yang sudah terdaftar di gelombang
    public function getKelasGelombang(int $id)
    {
        $ids = PklKelasGelombang::where('id_gelombang', $id)->pluck('id_kelas');
        return response()->json($ids);
    }

    // API: Ambil info detail gelombang (tanggal mulai & selesai)
    public function getInfo(int $id)
    {
        $gelombang = PklGelombang::findOrFail($id);
        return response()->json([
            'id_gelombang'    => $gelombang->id_gelombang,
            'nama_gelombang'  => $gelombang->nama_gelombang,
            'tanggal_mulai'   => $gelombang->tanggal_mulai,
            'tanggal_selesai' => $gelombang->tanggal_selesai,
            'status'          => $gelombang->status,
        ]);
    }
}
