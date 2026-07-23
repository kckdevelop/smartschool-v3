<?php

namespace App\Http\Controllers\AturData;

use App\Http\Controllers\Controller;
use App\Models\TahunAjaran;
use App\Models\Semester;
use Illuminate\Http\Request;

class TahunSemesterController extends Controller
{
    public function index()
    {
        $tahunList    = TahunAjaran::with('semester')->orderByDesc('id_tahun')->get();
        $tahunAktif   = TahunAjaran::where('status', 'aktif')->first();
        $semesterList = $tahunAktif
            ? Semester::with('tahunAjaran')
                ->where('id_tahun', $tahunAktif->id_tahun)
                ->orderBy('semester')
                ->get()
            : collect();
        return view('atur-data.tahun-semester.index', compact('tahunList', 'semesterList', 'tahunAktif'));
    }

    // ── Tahun Ajaran ──
    public function storeTahun(Request $request)
    {
        $request->validate([
            'tahun'  => 'required|string|max:20|unique:tahun_ajaran,tahun',
            'status' => 'required|in:aktif,tidak',
        ]);
        if ($request->status === 'aktif') TahunAjaran::where('status','aktif')->update(['status'=>'tidak']);
        TahunAjaran::create($request->only('tahun','status'));
        return redirect()->route('atur-data.tahun-semester')->with('success','Tahun ajaran berhasil ditambahkan.');
    }

    public function updateTahun(Request $request, $id)
    {
        $tahun = TahunAjaran::findOrFail($id);
        $request->validate([
            'tahun'  => 'required|string|max:20|unique:tahun_ajaran,tahun,'.$id.',id_tahun',
            'status' => 'required|in:aktif,tidak',
        ]);
        if ($request->status === 'aktif') TahunAjaran::where('status','aktif')->where('id_tahun','!=',$id)->update(['status'=>'tidak']);
        $tahun->update($request->only('tahun','status'));
        return redirect()->route('atur-data.tahun-semester')->with('success','Tahun ajaran berhasil diperbarui.');
    }

    public function destroyTahun($id)
    {
        $tahun = TahunAjaran::findOrFail($id);
        if ($tahun->semester()->count() > 0) return back()->with('error','Tahun ajaran masih memiliki semester.');
        $tahun->delete();
        return redirect()->route('atur-data.tahun-semester')->with('success','Tahun ajaran berhasil dihapus.');
    }

    // ── Semester ──
    public function storeSemester(Request $request)
    {
        $request->validate([
            'id_tahun' => 'required|integer|exists:tahun_ajaran,id_tahun',
            'semester' => 'required|in:Ganjil,Genap',
            'awal'     => 'required|date',
            'akhir'    => 'required|date|after:awal',
            'status'   => 'required|in:aktif,tidak',
        ]);
        if ($request->status === 'aktif') Semester::where('status','aktif')->update(['status'=>'tidak']);
        Semester::create($request->only('id_tahun','semester','awal','akhir','status'));
        return redirect()->route('atur-data.tahun-semester')->with('success','Semester berhasil ditambahkan.');
    }

    public function updateSemester(Request $request, $id)
    {
        $semester = Semester::findOrFail($id);
        $request->validate([
            'id_tahun' => 'required|integer|exists:tahun_ajaran,id_tahun',
            'semester' => 'required|in:Ganjil,Genap',
            'awal'     => 'required|date',
            'akhir'    => 'required|date|after:awal',
            'status'   => 'required|in:aktif,tidak',
        ]);
        if ($request->status === 'aktif') Semester::where('status','aktif')->where('id_semester','!=',$id)->update(['status'=>'tidak']);
        $semester->update($request->only('id_tahun','semester','awal','akhir','status'));
        return redirect()->route('atur-data.tahun-semester')->with('success','Semester berhasil diperbarui.');
    }

    public function destroySemester($id)
    {
        Semester::findOrFail($id)->delete();
        return redirect()->route('atur-data.tahun-semester')->with('success','Semester berhasil dihapus.');
    }
}
