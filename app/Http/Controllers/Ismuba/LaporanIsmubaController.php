<?php

namespace App\Http\Controllers\Ismuba;

use App\Http\Controllers\Controller;
use App\Models\Btaq;
use App\Models\Tadarus;
use App\Models\PantauIbadah;
use App\Models\Kelas;
use App\Models\Guru;
use Illuminate\Http\Request;

class LaporanIsmubaController extends Controller
{
    public function index(Request $request)
    {
        $kelasList  = Kelas::orderBy('tingkat')->orderBy('rombel')->get();
        $guruIsmuba = Guru::where('guru_ismuba', 'ya')->orderBy('nama_guru')->get();

        // Default filter bulan & tahun ini
        $bulan = $request->input('bulan', now()->format('Y-m'));
        [$tahun, $bln] = explode('-', $bulan);

        // Rekap BTAQ per kelas
        $rekapBtaq = Btaq::with('kelas')
            ->whereMonth('tanggal', $bln)
            ->whereYear('tanggal', $tahun)
            ->when($request->filled('id_kelas'), fn($q) => $q->where('id_kelas', $request->id_kelas))
            ->selectRaw('id_kelas, COUNT(*) as total_sesi, COUNT(DISTINCT nis) as total_siswa')
            ->groupBy('id_kelas')
            ->get();

        // Rekap Tadarus per kelas
        $rekapTadarus = Tadarus::with('kelas')
            ->whereMonth('tanggal', $bln)
            ->whereYear('tanggal', $tahun)
            ->when($request->filled('id_kelas'), fn($q) => $q->where('id_kelas', $request->id_kelas))
            ->selectRaw('id_kelas, COUNT(*) as total_sesi')
            ->groupBy('id_kelas')
            ->get();

        // Rekap Ibadah per jenis
        $rekapIbadah = PantauIbadah::with('kelas')
            ->whereMonth('tanggal', $bln)
            ->whereYear('tanggal', $tahun)
            ->when($request->filled('id_kelas'), fn($q) => $q->where('id_kelas', $request->id_kelas))
            ->selectRaw('id_kelas, jenis_ibadah, COUNT(*) as total, COUNT(DISTINCT nis) as total_siswa')
            ->groupBy('id_kelas', 'jenis_ibadah')
            ->get();

        // Summary cards
        $statBtaq    = Btaq::whereMonth('tanggal', $bln)->whereYear('tanggal', $tahun)->count();
        $statTadarus = Tadarus::whereMonth('tanggal', $bln)->whereYear('tanggal', $tahun)->count();
        $statIbadah  = PantauIbadah::whereMonth('tanggal', $bln)->whereYear('tanggal', $tahun)->count();

        return view('ismuba.laporan.index', compact(
            'kelasList', 'guruIsmuba', 'bulan',
            'rekapBtaq', 'rekapTadarus', 'rekapIbadah',
            'statBtaq', 'statTadarus', 'statIbadah'
        ));
    }
}
