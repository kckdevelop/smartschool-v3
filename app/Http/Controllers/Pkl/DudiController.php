<?php

namespace App\Http\Controllers\Pkl;

use App\Http\Controllers\Controller;
use App\Models\PklDudi;
use Illuminate\Http\Request;

class DudiController extends Controller
{
    public function index(Request $request)
    {
        $query = PklDudi::query();

        if ($request->filled('search')) {
            $query->where('nama_dudi', 'like', '%' . $request->search . '%')
                  ->orWhere('kota', 'like', '%' . $request->search . '%');
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $data = $query->orderByDesc('id_dudi')->paginate(20)->withQueryString();

        return view('pkl.dudi.index', compact('data'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_dudi'    => 'required|string|max:200',
            'bidang_usaha' => 'nullable|string|max:100',
            'alamat'       => 'required|string',
            'kota'         => 'nullable|string|max:100',
            'no_telepon'   => 'nullable|string|max:20',
            'email'        => 'nullable|email|max:100',
            'nama_pic'     => 'nullable|string|max:100',
            'jabatan_pic'  => 'nullable|string|max:100',
            'no_hp_pic'    => 'nullable|string|max:20',
            'kuota_siswa'  => 'required|integer|min:1|max:100',
            'status'       => 'required|in:aktif,nonaktif',
        ]);

        PklDudi::create($request->all());

        return redirect()->route('pkl.dudi.index')
            ->with('success', 'Data DUDI berhasil ditambahkan.');
    }

    public function update(Request $request, int $id)
    {
        $dudi = PklDudi::findOrFail($id);

        $request->validate([
            'nama_dudi'    => 'required|string|max:200',
            'bidang_usaha' => 'nullable|string|max:100',
            'alamat'       => 'required|string',
            'kota'         => 'nullable|string|max:100',
            'no_telepon'   => 'nullable|string|max:20',
            'email'        => 'nullable|email|max:100',
            'nama_pic'     => 'nullable|string|max:100',
            'jabatan_pic'  => 'nullable|string|max:100',
            'no_hp_pic'    => 'nullable|string|max:20',
            'kuota_siswa'  => 'required|integer|min:1|max:100',
            'status'       => 'required|in:aktif,nonaktif',
        ]);

        $dudi->update($request->all());

        return redirect()->route('pkl.dudi.index')
            ->with('success', 'Data DUDI berhasil diperbarui.');
    }

    public function destroy(int $id)
    {
        $dudi = PklDudi::findOrFail($id);
        if ($dudi->penempatan()->count() > 0) {
            return back()->with('error', 'DUDI tidak dapat dihapus karena sudah memiliki siswa yang ditempatkan.');
        }
        $dudi->delete();

        return redirect()->route('pkl.dudi.index')
            ->with('success', 'Data DUDI berhasil dihapus.');
    }

    // API: Ambil daftar DUDI aktif dengan sisa kuota
    public function getByGelombang(Request $request)
    {
        $idGelombang = $request->id_gelombang;
        $dudis = PklDudi::where('status', 'aktif')->get()->map(function ($d) use ($idGelombang) {
            $terpakai = $idGelombang
                ? $d->penempatan()->where('id_gelombang', $idGelombang)->whereIn('status', ['aktif', 'selesai'])->count()
                : 0;
            return [
                'id_dudi'       => $d->id_dudi,
                'nama_dudi'     => $d->nama_dudi,
                'kuota_siswa'   => $d->kuota_siswa,
                'terpakai'      => $terpakai,
                'sisa_kuota'    => max(0, $d->kuota_siswa - $terpakai),
            ];
        });

        return response()->json($dudis);
    }
}
