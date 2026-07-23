<?php

namespace App\Http\Controllers\AturData;

use App\Http\Controllers\Controller;
use App\Models\Jurusan;
use Illuminate\Http\Request;

class JurusanController extends Controller
{
    public function index(Request $request)
    {
        $query = Jurusan::query();
        if ($request->filled('search')) $query->where('nama_jurusan','like','%'.$request->search.'%')->orWhere('kode_jurusan','like','%'.$request->search.'%');
        $perPage     = (int) $request->input('per_page', 20);
        $perPage     = in_array($perPage, [10, 20, 50, 100]) ? $perPage : 20;
        $jurusanList = $query->orderBy('nama_jurusan')->paginate($perPage)->withQueryString();
        return view('atur-data.jurusan.index', compact('jurusanList'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'kode_jurusan' => 'required|string|max:20|unique:jurusan,kode_jurusan',
            'nama_jurusan' => 'required|string|max:100|unique:jurusan,nama_jurusan',
            'status'       => 'required|in:aktif,tidak',
        ]);
        Jurusan::create($request->only('kode_jurusan','nama_jurusan','status'));
        return redirect()->route('atur-data.jurusan')->with('success','Jurusan berhasil ditambahkan.');
    }

    public function update(Request $request, $id)
    {
        $jurusan = Jurusan::findOrFail($id);
        $request->validate([
            'kode_jurusan' => 'required|string|max:20|unique:jurusan,kode_jurusan,'.$id.',id_jurusan',
            'nama_jurusan' => 'required|string|max:100|unique:jurusan,nama_jurusan,'.$id.',id_jurusan',
            'status'       => 'required|in:aktif,tidak',
        ]);
        $jurusan->update($request->only('kode_jurusan','nama_jurusan','status'));
        return redirect()->route('atur-data.jurusan')->with('success','Jurusan berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $jurusan = Jurusan::findOrFail($id);
        if ($jurusan->kelas()->count() > 0) return back()->with('error','Jurusan masih memiliki kelas aktif.');
        $jurusan->delete();
        return redirect()->route('atur-data.jurusan')->with('success','Jurusan berhasil dihapus.');
    }

    public function bulkDestroy(Request $request)
    {
        $ids = $request->input('ids', []);
        if (empty($ids)) {
            return redirect()->route('atur-data.jurusan')->with('error', 'Tidak ada data jurusan yang terpilih.');
        }

        \Illuminate\Support\Facades\DB::beginTransaction();
        try {
            foreach ($ids as $id) {
                $jurusan = Jurusan::find($id);
                if ($jurusan) {
                    if ($jurusan->kelas()->count() > 0) {
                        return redirect()->route('atur-data.jurusan')->with('error', 'Gagal menghapus masal: Jurusan ' . $jurusan->nama_jurusan . ' masih memiliki kelas aktif.');
                    }
                    $jurusan->delete();
                }
            }
            \Illuminate\Support\Facades\DB::commit();
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\DB::rollBack();
            return redirect()->route('atur-data.jurusan')->with('error', 'Terjadi kesalahan saat menghapus data jurusan: ' . $e->getMessage());
        }

        return redirect()->route('atur-data.jurusan')->with('success', 'Berhasil menghapus ' . count($ids) . ' data jurusan terpilih.');
    }
}
