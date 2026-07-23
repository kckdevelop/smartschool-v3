<?php

namespace App\Http\Controllers\Uks;

use App\Http\Controllers\Controller;
use App\Models\JenisCheckup;
use Illuminate\Http\Request;

class JenisCheckupController extends Controller
{
    public function index()
    {
        $jenis = JenisCheckup::orderBy('jenis_checkup')->paginate(20);
        return view('uks.jenis-checkup.index', compact('jenis'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'jenis_checkup' => 'required|string|max:100|unique:jenis_checkup,jenis_checkup',
            'status'        => 'required|in:aktif,tidak',
        ]);

        JenisCheckup::create($request->only(['jenis_checkup','status']));

        return redirect()->route('uks.jenis-checkup.index')
            ->with('success', 'Jenis check-up berhasil ditambahkan.');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'jenis_checkup' => 'required|string|max:100|unique:jenis_checkup,jenis_checkup,'.$id.',id_checkup',
            'status'        => 'required|in:aktif,tidak',
        ]);

        JenisCheckup::findOrFail($id)->update($request->only(['jenis_checkup','status']));

        return redirect()->route('uks.jenis-checkup.index')
            ->with('success', 'Jenis check-up berhasil diperbarui.');
    }

    public function destroy($id)
    {
        JenisCheckup::findOrFail($id)->delete();
        return redirect()->route('uks.jenis-checkup.index')
            ->with('success', 'Jenis check-up berhasil dihapus.');
    }
}
