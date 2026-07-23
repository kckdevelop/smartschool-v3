<?php

namespace App\Http\Controllers\Pkl;

use App\Http\Controllers\Controller;
use App\Models\PklPembimbing;
use App\Models\PklGelombang;
use App\Models\PklDudi;
use App\Models\Guru;
use App\Models\Sekolah;
use Illuminate\Http\Request;

class PembimbingController extends Controller
{
    public function index(Request $request)
    {
        $gelombangList  = PklGelombang::orderByDesc('id_gelombang')->get();
        $gelombangAktif = PklGelombang::where('status', 'aktif')->first();
        $selectedId     = $request->id_gelombang ?? optional($gelombangAktif)->id_gelombang;

        // Query DUDI data with search filter by DUDI name
        $query = PklDudi::where('status', 'aktif');

        if ($request->filled('search')) {
            $query->where('nama_dudi', 'like', '%' . $request->search . '%');
        }

        // Eager load pembimbing relation filtered by the selected gelombang
        $data = $query->with(['pembimbing' => function($q) use ($selectedId) {
                $q->where('id_gelombang', $selectedId)->with('guru');
            }])
            ->orderBy('nama_dudi')
            ->paginate(15)
            ->withQueryString();

        $guru = Guru::where('status', 'aktif')->orderBy('nama_guru')->get();

        return view('pkl.pembimbing.index', compact(
            'data', 'gelombangList', 'selectedId', 'guru', 'gelombangAktif'
        ));
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_gelombang' => 'required|integer',
            'id_dudi'      => 'required|integer',
            'id_guru'      => 'nullable|integer',
        ]);

        $idGelombang = $request->id_gelombang;
        $idDudi = $request->id_dudi;
        $idGuru = $request->id_guru;

        // Find existing pembimbing assignment for this DUDI and Gelombang
        $pembimbing = PklPembimbing::where('id_gelombang', $idGelombang)
            ->where('id_dudi', $idDudi)
            ->first();

        if (empty($idGuru)) {
            // Remove assignment if empty
            if ($pembimbing) {
                // Update related penempatan records to null pembimbing first
                \App\Models\PklPenempatan::where('id_gelombang', $idGelombang)
                    ->where('id_dudi', $idDudi)
                    ->where('id_pembimbing', $pembimbing->id_pembimbing)
                    ->update(['id_pembimbing' => null]);

                $pembimbing->delete();
            }
            return redirect()->route('pkl.pembimbing.index', ['id_gelombang' => $idGelombang])
                ->with('success', 'Pembimbing berhasil dihapus.');
        }

        if ($pembimbing) {
            // Update existing
            $pembimbing->update(['id_guru' => $idGuru]);
        } else {
            // Create new
            $newPembimbing = PklPembimbing::create([
                'id_gelombang' => $idGelombang,
                'id_dudi'      => $idDudi,
                'id_guru'      => $idGuru,
            ]);

            // Update any existing penempatan records for this DUDI and Gelombang to point to this new pembimbing
            \App\Models\PklPenempatan::where('id_gelombang', $idGelombang)
                ->where('id_dudi', $idDudi)
                ->update(['id_pembimbing' => $newPembimbing->id_pembimbing]);
        }

        return redirect()->route('pkl.pembimbing.index', ['id_gelombang' => $idGelombang])
            ->with('success', 'Pembimbing PKL berhasil diatur.');
    }

    public function update(Request $request, int $id)
    {
        // Re-use store logic to avoid duplicate code paths
        return $this->store($request);
    }

    /**
     * Cetak laporan pembimbing PKL:
     * urut berdasarkan nama guru → dudi → siswa di dudi tersebut.
     */
    public function cetak(Request $request)
    {
        $gelombangList  = PklGelombang::orderByDesc('id_gelombang')->get();
        $gelombangAktif = PklGelombang::where('status', 'aktif')->first();
        $selectedId     = $request->id_gelombang ?? optional($gelombangAktif)->id_gelombang;
        $selectedGelombang = $selectedId ? PklGelombang::find($selectedId) : $gelombangAktif;

        $sekolah = Sekolah::first();

        // Ambil semua pembimbing untuk gelombang terpilih, urut nama guru
        $pembimbings = PklPembimbing::with([
                'guru',
                'dudi',
                'penempatan' => function ($q) use ($selectedId) {
                    $q->with(['siswa.kelas'])
                      ->whereIn('status', ['aktif', 'selesai']);
                    if ($selectedId) {
                        $q->where('id_gelombang', $selectedId);
                    }
                },
            ])
            ->when($selectedId, fn($q) => $q->where('id_gelombang', $selectedId))
            ->join('guru', 'pkl_pembimbing.id_guru', '=', 'guru.id_guru')
            ->orderBy('guru.nama_guru')
            ->select('pkl_pembimbing.*')
            ->get();

        return view('pkl.pembimbing.cetak', compact(
            'pembimbings', 'selectedGelombang', 'gelombangList', 'selectedId', 'sekolah'
        ));
    }

    public function destroy(int $id)
    {
        $p = PklPembimbing::findOrFail($id);
        $idGelombang = $p->id_gelombang;

        // Update related penempatan records to null pembimbing first
        \App\Models\PklPenempatan::where('id_gelombang', $idGelombang)
            ->where('id_dudi', $p->id_dudi)
            ->where('id_pembimbing', $p->id_pembimbing)
            ->update(['id_pembimbing' => null]);

        $p->delete();

        return redirect()->route('pkl.pembimbing.index', ['id_gelombang' => $idGelombang])
            ->with('success', 'Pembimbing PKL berhasil dihapus.');
    }
}
