<?php

namespace App\Http\Controllers\Ismuba;

use App\Http\Controllers\Controller;
use App\Models\PantauIbadah;
use App\Models\Kelas;
use App\Models\Guru;
use App\Models\UserSiswa;
use Illuminate\Http\Request;

class IbadahController extends Controller
{
    public function index(Request $request)
    {
        $query = PantauIbadah::with(['siswa.kelas', 'guru', 'kelas'])
            ->orderByDesc('tanggal')
            ->orderBy('jenis_ibadah');

        if ($request->filled('id_kelas')) {
            $query->where('id_kelas', $request->id_kelas);
        }
        if ($request->filled('id_guru')) {
            $query->where('id_guru', $request->id_guru);
        }
        if ($request->filled('jenis_ibadah')) {
            $query->where('jenis_ibadah', $request->jenis_ibadah);
        }
        if ($request->filled('tanggal_dari')) {
            $query->where('tanggal', '>=', $request->tanggal_dari);
        }
        if ($request->filled('tanggal_sampai')) {
            $query->where('tanggal', '<=', $request->tanggal_sampai);
        }
        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('siswa', fn($q) => $q->where('nama_siswa', 'like', "%{$search}%")
                ->orWhere('nis', 'like', "%{$search}%"));
        }

        $ibadahList = $query->paginate(20)->withQueryString();

        $totalHariIni  = PantauIbadah::whereDate('tanggal', today())->count();
        $totalBulanIni = PantauIbadah::whereMonth('tanggal', now()->month)->whereYear('tanggal', now()->year)->count();
        $totalAll      = PantauIbadah::count();

        // Hitung per jenis
        $countFardu   = PantauIbadah::where('jenis_ibadah', 'sholat_fardu')->count();
        $countJenazah = PantauIbadah::where('jenis_ibadah', 'sholat_jenazah')->count();
        $countWudhu   = PantauIbadah::where('jenis_ibadah', 'gerakan_wudhu')->count();

        $kelasList   = Kelas::orderBy('tingkat')->orderBy('rombel')->get();
        $guruIsmuba  = Guru::where('guru_ismuba', 'ya')->orderBy('nama_guru')->get();
        $siswaDaftar = UserSiswa::with('kelas')->orderBy('nama_siswa')->get(['nis', 'nama_siswa', 'id_kelas']);

        return view('ismuba.ibadah.index', compact(
            'ibadahList', 'totalHariIni', 'totalBulanIni', 'totalAll',
            'countFardu', 'countJenazah', 'countWudhu',
            'kelasList', 'guruIsmuba', 'siswaDaftar'
        ));
    }

    public function store(Request $request)
    {
        $request->validate([
            'tanggal'      => 'required|date',
            'nis'          => 'required|integer|exists:user_siswa,nis',
            'id_kelas'     => 'required|integer|exists:kelas,id_kelas',
            'id_guru'      => 'required|integer|exists:guru,id_guru',
            'jenis_ibadah' => 'required|in:sholat_fardu,sholat_jenazah,gerakan_wudhu',
            'nilai'        => 'required|in:A,B,C,D',
            'catatan'      => 'nullable|string|max:500',
        ]);

        PantauIbadah::create($request->only([
            'tanggal', 'nis', 'id_kelas', 'id_guru', 'jenis_ibadah', 'nilai', 'catatan'
        ]));

        return redirect()->route('ismuba.ibadah.index')
            ->with('success', 'Data pantauan ibadah berhasil disimpan.');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'tanggal'      => 'required|date',
            'nis'          => 'required|integer|exists:user_siswa,nis',
            'id_kelas'     => 'required|integer|exists:kelas,id_kelas',
            'id_guru'      => 'required|integer|exists:guru,id_guru',
            'jenis_ibadah' => 'required|in:sholat_fardu,sholat_jenazah,gerakan_wudhu',
            'nilai'        => 'required|in:A,B,C,D',
            'catatan'      => 'nullable|string|max:500',
        ]);

        $ibadah = PantauIbadah::findOrFail($id);
        $ibadah->update($request->only([
            'tanggal', 'nis', 'id_kelas', 'id_guru', 'jenis_ibadah', 'nilai', 'catatan'
        ]));

        return redirect()->route('ismuba.ibadah.index')
            ->with('success', 'Data pantauan ibadah berhasil diperbarui.');
    }

    public function destroy($id)
    {
        PantauIbadah::findOrFail($id)->delete();
        return redirect()->route('ismuba.ibadah.index')
            ->with('success', 'Data pantauan ibadah berhasil dihapus.');
    }
}
