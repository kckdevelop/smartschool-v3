<?php

namespace App\Http\Controllers\AturData;

use App\Http\Controllers\Controller;
use App\Models\Sekolah;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SekolahController extends Controller
{
    public function index()
    {
        $sekolah = Sekolah::first();
        return view('atur-data.sekolah.index', compact('sekolah'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'npsn'           => 'required|integer',
            'nama_sekolah'   => 'required|string|max:255',
            'kepala_sekolah' => 'required|string|max:255',
            'nip'            => 'nullable|string|max:50',
            'status'         => 'required|in:negeri,swasta',
            'alamat_sekolah' => 'nullable|string',
            'kota'           => 'nullable|string|max:100',
            'logo'           => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'kop'            => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $data = $request->only(['npsn','nama_sekolah','kepala_sekolah','nip','status','alamat_sekolah','kota']);
        if ($request->hasFile('logo'))  $data['logo'] = $request->file('logo')->store('sekolah/logo','public');
        if ($request->hasFile('kop'))   $data['kop']  = $request->file('kop')->store('sekolah/kop','public');
        Sekolah::create($data);

        return redirect()->route('atur-data.sekolah')->with('success', 'Data sekolah berhasil disimpan.');
    }

    public function update(Request $request, $id)
    {
        $sekolah = Sekolah::findOrFail($id);
        $request->validate([
            'npsn'           => 'required|integer',
            'nama_sekolah'   => 'required|string|max:255',
            'kepala_sekolah' => 'required|string|max:255',
            'nip'            => 'nullable|string|max:50',
            'status'         => 'required|in:negeri,swasta',
            'alamat_sekolah' => 'nullable|string',
            'kota'           => 'nullable|string|max:100',
            'logo'           => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'kop'            => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $data = $request->only(['npsn','nama_sekolah','kepala_sekolah','nip','status','alamat_sekolah','kota']);
        
        // Handle Logo file update / deletion
        if ($request->input('delete_logo') == '1') {
            if ($sekolah->logo) Storage::disk('public')->delete($sekolah->logo);
            $data['logo'] = null;
        } elseif ($request->hasFile('logo')) {
            if ($sekolah->logo) Storage::disk('public')->delete($sekolah->logo);
            $data['logo'] = $request->file('logo')->store('sekolah/logo','public');
        }

        // Handle Kop file update / deletion
        if ($request->input('delete_kop') == '1') {
            if ($sekolah->kop) Storage::disk('public')->delete($sekolah->kop);
            $data['kop'] = null;
        } elseif ($request->hasFile('kop')) {
            if ($sekolah->kop) Storage::disk('public')->delete($sekolah->kop);
            $data['kop'] = $request->file('kop')->store('sekolah/kop','public');
        }

        $sekolah->update($data);

        return redirect()->route('atur-data.sekolah')->with('success', 'Data sekolah berhasil diperbarui.');
    }

    public function toggleEditDetailSiswa()
    {
        $sekolah = Sekolah::first();
        if (!$sekolah) {
            return redirect()->route('atur-data.sekolah')->with('error', 'Data sekolah belum dikonfigurasi.');
        }

        $newValue = !$sekolah->edit_detail_siswa;
        $sekolah->update(['edit_detail_siswa' => $newValue]);

        $msg = $newValue
            ? 'Fitur edit profil siswa di aplikasi mobile telah DIAKTIFKAN.'
            : 'Fitur edit profil siswa di aplikasi mobile telah DINONAKTIFKAN.';

        return redirect()->route('atur-data.sekolah')->with('success', $msg);
    }
}
