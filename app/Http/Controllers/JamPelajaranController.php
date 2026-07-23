<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\JamPelajaran;
use App\Models\Sekolah;
use Illuminate\Http\Request;

class JamPelajaranController extends Controller
{
    public function index()
    {
        $jamList = JamPelajaran::orderBy('jam_ke', 'asc')->get();
        $sekolah = Sekolah::first();

        // Default to a fallback if sekolah table is empty (should not be empty)
        $jadwalAktif = $sekolah ? $sekolah->jadwal_aktif : 'normal';

        return view('atur-jam.index', compact('jamList', 'jadwalAktif'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'jam_ke'          => 'required|integer|min:1|unique:jam_pelajaran,jam_ke',
            'normal_mulai'    => 'required|date_format:H:i',
            'normal_selesai'  => 'required|date_format:H:i|after:normal_mulai',
            'upacara_mulai'   => 'required|date_format:H:i',
            'upacara_selesai' => 'required|date_format:H:i|after:upacara_mulai',
            'puasa_mulai'     => 'required|date_format:H:i',
            'puasa_selesai'   => 'required|date_format:H:i|after:puasa_mulai',
        ]);

        JamPelajaran::create([
            'jam_ke'          => $request->jam_ke,
            'normal_mulai'    => $request->normal_mulai,
            'normal_selesai'  => $request->normal_selesai,
            'upacara_mulai'   => $request->upacara_mulai,
            'upacara_selesai' => $request->upacara_selesai,
            'puasa_mulai'     => $request->puasa_mulai,
            'puasa_selesai'   => $request->puasa_selesai,
        ]);

        return redirect()->route('atur-jam.index')->with('success', 'Jam pelajaran ke-' . $request->jam_ke . ' berhasil ditambahkan.');
    }

    public function update(Request $request, $id)
    {
        $jam = JamPelajaran::findOrFail($id);

        $request->validate([
            'jam_ke'          => 'required|integer|min:1|unique:jam_pelajaran,jam_ke,' . $id . ',id_jam',
            'normal_mulai'    => 'required|date_format:H:i',
            'normal_selesai'  => 'required|date_format:H:i|after:normal_mulai',
            'upacara_mulai'   => 'required|date_format:H:i',
            'upacara_selesai' => 'required|date_format:H:i|after:upacara_mulai',
            'puasa_mulai'     => 'required|date_format:H:i',
            'puasa_selesai'   => 'required|date_format:H:i|after:puasa_mulai',
        ]);

        $jam->update([
            'jam_ke'          => $request->jam_ke,
            'normal_mulai'    => $request->normal_mulai,
            'normal_selesai'  => $request->normal_selesai,
            'upacara_mulai'   => $request->upacara_mulai,
            'upacara_selesai' => $request->upacara_selesai,
            'puasa_mulai'     => $request->puasa_mulai,
            'puasa_selesai'   => $request->puasa_selesai,
        ]);

        return redirect()->route('atur-jam.index')->with('success', 'Jam pelajaran ke-' . $request->jam_ke . ' berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $jam = JamPelajaran::findOrFail($id);
        $jamKe = $jam->jam_ke;
        $jam->delete();

        return redirect()->route('atur-jam.index')->with('success', 'Jam pelajaran ke-' . $jamKe . ' berhasil dihapus.');
    }

    public function updateAktif(Request $request)
    {
        $request->validate([
            'jadwal_aktif' => 'required|string|in:normal,upacara,puasa',
        ]);

        $sekolah = Sekolah::first();
        if ($sekolah) {
            $sekolah->update([
                'jadwal_aktif' => $request->jadwal_aktif
            ]);
        }

        $labelMap = [
            'normal'  => 'Normal',
            'upacara' => 'Hari Upacara',
            'puasa'   => 'Bulan Puasa',
        ];

        return redirect()->route('atur-jam.index')->with('success', 'Jadwal aktif sekolah berhasil diubah ke skema: ' . $labelMap[$request->jadwal_aktif] . '.');
    }
}
