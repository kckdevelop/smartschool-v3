<?php

namespace App\Http\Controllers\AturData;

use App\Http\Controllers\Controller;
use App\Models\Kelas;
use App\Models\Guru;
use Illuminate\Http\Request;

class WaliKelasController extends Controller
{
    public function index(Request $request)
    {
        $query = Kelas::with(['jurusan','guru'])->where('status', 'aktif');
        if ($request->filled('tingkat')) $query->where('tingkat',$request->tingkat);
        $perPage   = (int) $request->input('per_page', 20);
        $perPage   = in_array($perPage, [10, 20, 50, 100]) ? $perPage : 20;
        $kelasList = $query->orderBy('tingkat')->orderBy('rombel')->paginate($perPage)->withQueryString();
        $guruList  = Guru::where('status','aktif')->orderBy('nama_guru')->get();
        return view('atur-data.wali-kelas.index', compact('kelasList','guruList'));
    }

    public function tetapkan(Request $request, $id_kelas)
    {
        $kelas = Kelas::findOrFail($id_kelas);
        $request->validate(['id_guru' => 'required|integer|exists:guru,id_guru']);
        
        // Find if this guru is assigned as walikelas in any OTHER ACTIVE class
        $existingActive = Kelas::where('walikelas',$request->id_guru)
            ->where('id_kelas','!=',$id_kelas)
            ->where('status', 'aktif')
            ->first();
            
        if ($existingActive) {
            return back()->with('error',"Guru sudah menjadi wali kelas {$existingActive->tingkat} {$existingActive->rombel}.");
        }

        // If the teacher is assigned to any inactive classes, remove the assignment (set walikelas to null)
        Kelas::where('walikelas',$request->id_guru)
            ->where('id_kelas','!=',$id_kelas)
            ->where('status', 'tidak')
            ->update(['walikelas' => null]);
            
        $kelas->update(['walikelas' => $request->id_guru]);
        return redirect()->route('atur-data.wali-kelas')->with('success','Wali kelas berhasil ditetapkan.');
    }

    public function lepas($id_kelas)
    {
        Kelas::findOrFail($id_kelas)->update(['walikelas' => null]);
        return redirect()->route('atur-data.wali-kelas')->with('success','Wali kelas berhasil dilepas.');
    }
}
