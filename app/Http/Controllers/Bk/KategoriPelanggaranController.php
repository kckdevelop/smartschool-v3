<?php

namespace App\Http\Controllers\Bk;

use App\Http\Controllers\Controller;
use App\Models\JenisPelanggaran;
use Illuminate\Http\Request;

class KategoriPelanggaranController extends Controller
{
    public function index()
    {
        $data = JenisPelanggaran::orderBy('jenis_pelanggaran')->paginate(20);
        return view('bk.kategori-pelanggaran.index', compact('data'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'jenis_pelanggaran' => 'required|string|max:100|unique:jenis_pelanggaran,jenis_pelanggaran',
            'poin'              => 'required|integer|min:1|max:100',
        ]);

        JenisPelanggaran::create($request->only('jenis_pelanggaran', 'poin'));

        return redirect()->route('bk.kategori-pelanggaran.index')
            ->with('success', 'Kategori pelanggaran berhasil ditambahkan.');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'jenis_pelanggaran' => 'required|string|max:100|unique:jenis_pelanggaran,jenis_pelanggaran,'.$id.',id_jenis_pelanggaran',
            'poin'              => 'required|integer|min:1|max:100',
        ]);

        JenisPelanggaran::findOrFail($id)->update($request->only('jenis_pelanggaran', 'poin'));

        return redirect()->route('bk.kategori-pelanggaran.index')
            ->with('success', 'Kategori pelanggaran berhasil diperbarui.');
    }

    public function destroy($id)
    {
        JenisPelanggaran::findOrFail($id)->delete();
        return redirect()->route('bk.kategori-pelanggaran.index')
            ->with('success', 'Kategori pelanggaran berhasil dihapus.');
    }

    /**
     * AJAX: search kategori pelanggaran by keyword (for catat-pelanggaran form)
     */
    public function search(Request $request)
    {
        $q = $request->get('q', '');
        $results = JenisPelanggaran::when($q, function ($query) use ($q) {
                $query->where('jenis_pelanggaran', 'like', "%{$q}%");
            })
            ->orderBy('jenis_pelanggaran')
            ->limit(20)
            ->get(['id_jenis_pelanggaran', 'jenis_pelanggaran', 'poin']);

        return response()->json($results);
    }
}
