<?php

namespace App\Http\Controllers\Uks;

use App\Http\Controllers\Controller;
use App\Models\KunjunganUks;
use App\Models\RiwayatObat;
use App\Models\UserSiswa;
use App\Models\Kelas;
use Illuminate\Http\Request;
use Carbon\Carbon;

class KunjunganController extends Controller
{
    public function index(Request $request)
    {
        $query = KunjunganUks::with(['siswa.kelas.jurusan'])
            ->orderByDesc('tanggal')
            ->orderByDesc('jam');

        if ($request->filled('tanggal_dari')) {
            $query->where('tanggal', '>=', $request->tanggal_dari);
        }
        if ($request->filled('tanggal_sampai')) {
            $query->where('tanggal', '<=', $request->tanggal_sampai);
        }
        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('siswa', function ($q) use ($search) {
                $q->where('nama_siswa', 'like', "%{$search}%")
                  ->orWhere('nis', 'like', "%{$search}%");
            });
        }

        $kunjungan = $query->paginate(15)->withQueryString();

        // Stats untuk hari ini dan bulan ini
        $hariIni   = KunjunganUks::whereDate('tanggal', today())->count();
        $bulanIni  = KunjunganUks::whereMonth('tanggal', now()->month)
                        ->whereYear('tanggal', now()->year)->count();
        $totalAll  = KunjunganUks::count();

        $siswaDaftar = UserSiswa::with('kelas')
            ->orderBy('nama_siswa')
            ->get(['nis', 'nama_siswa', 'id_kelas']);

        return view('uks.kunjungan.index', compact(
            'kunjungan', 'hariIni', 'bulanIni', 'totalAll', 'siswaDaftar'
        ));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nis'      => 'required|integer|exists:user_siswa,nis',
            'tanggal'  => 'required|date',
            'jam'      => 'required',
            'keluhan'  => 'required|string|max:500',
            'diagnosa' => 'required|string|max:100',
            'tindakan' => 'required|string|max:100',
        ]);

        $kunjungan = KunjunganUks::create([
            'nis'      => $request->nis,
            'tanggal'  => $request->tanggal,
            'jam'      => $request->jam,
            'keluhan'  => $request->keluhan,
            'diagnosa' => $request->diagnosa,
            'tindakan' => $request->tindakan,
        ]);

        // Simpan riwayat obat jika ada
        if ($request->filled('obat_nama')) {
            $obatNama   = $request->obat_nama;
            $obatDosis  = $request->obat_dosis;
            $obatJumlah = $request->obat_jumlah;
            foreach ($obatNama as $i => $nama) {
                if (!empty($nama)) {
                    RiwayatObat::create([
                        'id_kunjungan' => $kunjungan->id_kunjungan,
                        'nama_obat'    => $nama,
                        'dosis'        => $obatDosis[$i] ?? '-',
                        'jumlah'       => $obatJumlah[$i] ?? 1,
                    ]);
                }
            }
        }

        return redirect()->route('uks.kunjungan.index')
            ->with('success', 'Kunjungan UKS berhasil dicatat.');
    }

    public function show($id)
    {
        $kunjungan = KunjunganUks::with(['siswa.kelas.jurusan', 'riwayatObat'])
            ->findOrFail($id);
        return view('uks.kunjungan.show', compact('kunjungan'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nis'      => 'required|integer|exists:user_siswa,nis',
            'tanggal'  => 'required|date',
            'jam'      => 'required',
            'keluhan'  => 'required|string|max:500',
            'diagnosa' => 'required|string|max:100',
            'tindakan' => 'required|string|max:100',
        ]);

        $kunjungan = KunjunganUks::findOrFail($id);
        $kunjungan->update($request->only(['nis','tanggal','jam','keluhan','diagnosa','tindakan']));

        // Update riwayat obat: hapus semua, simpan ulang
        $kunjungan->riwayatObat()->delete();
        if ($request->filled('obat_nama')) {
            foreach ($request->obat_nama as $i => $nama) {
                if (!empty($nama)) {
                    RiwayatObat::create([
                        'id_kunjungan' => $kunjungan->id_kunjungan,
                        'nama_obat'    => $nama,
                        'dosis'        => $request->obat_dosis[$i] ?? '-',
                        'jumlah'       => $request->obat_jumlah[$i] ?? 1,
                    ]);
                }
            }
        }

        return redirect()->route('uks.kunjungan.index')
            ->with('success', 'Data kunjungan berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $kunjungan = KunjunganUks::findOrFail($id);
        $kunjungan->riwayatObat()->delete();
        $kunjungan->delete();

        return redirect()->route('uks.kunjungan.index')
            ->with('success', 'Data kunjungan berhasil dihapus.');
    }
}
