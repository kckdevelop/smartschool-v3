<?php

namespace App\Http\Controllers\Uks;

use App\Http\Controllers\Controller;
use App\Models\KunjunganUksGukar;
use App\Models\RiwayatObatGukar;
use App\Models\Guru;
use App\Models\Karyawan;
use Illuminate\Http\Request;
use Carbon\Carbon;

class KunjunganGukarController extends Controller
{
    public function index(Request $request)
    {
        $query = KunjunganUksGukar::with(['guru', 'karyawan'])
            ->orderByDesc('tanggal')
            ->orderByDesc('jam');

        // Filter by Role
        if ($request->filled('role')) {
            if ($request->role === 'guru') {
                $query->whereNotNull('id_guru')->whereNull('id_karyawan');
            } elseif ($request->role === 'karyawan') {
                $query->whereNotNull('id_karyawan')->whereNull('id_guru');
            }
        }

        // Search by name or NIP
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->whereHas('guru', function ($qg) use ($search) {
                    $qg->where('nama_guru', 'like', "%{$search}%")
                       ->orWhere('no_id', 'like', "%{$search}%");
                })->orWhereHas('karyawan', function ($qk) use ($search) {
                    $qk->where('nama_karyawan', 'like', "%{$search}%")
                       ->orWhere('no_id', 'like', "%{$search}%");
                });
            });
        }

        // Filter by date range
        if ($request->filled('tanggal_dari')) {
            $query->where('tanggal', '>=', $request->tanggal_dari);
        }
        if ($request->filled('tanggal_sampai')) {
            $query->where('tanggal', '<=', $request->tanggal_sampai);
        }

        $kunjungan = $query->paginate(15)->withQueryString();

        // Stats
        $hariIni  = KunjunganUksGukar::whereDate('tanggal', today())->count();
        $bulanIni = KunjunganUksGukar::whereMonth('tanggal', now()->month)
                        ->whereYear('tanggal', now()->year)->count();
        $totalAll = KunjunganUksGukar::count();

        $gurus     = Guru::where('status', 'aktif')->orderBy('nama_guru')->get(['id_guru','no_id','nama_guru']);
        $karyawans = Karyawan::where('status', 'aktif')->orderBy('nama_karyawan')->get(['id_karyawan','no_id','nama_karyawan']);

        return view('uks.kunjungan-gukar.index', compact(
            'kunjungan', 'hariIni', 'bulanIni', 'totalAll', 'gurus', 'karyawans'
        ));
    }

    private function parseGukarId($gukarId): array
    {
        $id_guru     = null;
        $id_karyawan = null;
        if (str_starts_with((string) $gukarId, 'guru_')) {
            $id_guru = (int) str_replace('guru_', '', $gukarId);
        } elseif (str_starts_with((string) $gukarId, 'karyawan_')) {
            $id_karyawan = (int) str_replace('karyawan_', '', $gukarId);
        }
        return [$id_guru, $id_karyawan];
    }

    public function store(Request $request)
    {
        $request->validate([
            'gukar_id' => 'required|string',
            'tanggal'  => 'required|date',
            'jam'      => 'required',
            'keluhan'  => 'required|string|max:500',
            'diagnosa' => 'required|string|max:100',
            'tindakan' => 'required|string|max:100',
        ]);

        [$id_guru, $id_karyawan] = $this->parseGukarId($request->gukar_id);

        if (!$id_guru && !$id_karyawan) {
            return back()->withErrors(['gukar_id' => 'Pilihan Guru/Karyawan tidak valid.']);
        }

        $kunjungan = KunjunganUksGukar::create([
            'id_guru'     => $id_guru,
            'id_karyawan' => $id_karyawan,
            'tanggal'     => $request->tanggal,
            'jam'         => $request->jam,
            'keluhan'     => $request->keluhan,
            'diagnosa'    => $request->diagnosa,
            'tindakan'    => $request->tindakan,
        ]);

        // Simpan riwayat obat jika ada
        if ($request->filled('obat_nama')) {
            $obatNama   = $request->obat_nama;
            $obatDosis  = $request->obat_dosis;
            $obatJumlah = $request->obat_jumlah;
            foreach ($obatNama as $i => $nama) {
                if (!empty($nama)) {
                    RiwayatObatGukar::create([
                        'id_kunjungan' => $kunjungan->id_kunjungan,
                        'nama_obat'    => $nama,
                        'dosis'        => $obatDosis[$i] ?? '-',
                        'jumlah'       => $obatJumlah[$i] ?? 1,
                    ]);
                }
            }
        }

        return redirect()->route('uks.kunjungan-gukar.index')
            ->with('success', 'Kunjungan UKS Gukar berhasil dicatat.');
    }

    public function show($id)
    {
        $kunjungan = KunjunganUksGukar::with(['guru', 'karyawan', 'riwayatObat'])
            ->findOrFail($id);
        return view('uks.kunjungan-gukar.show', compact('kunjungan'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'gukar_id' => 'required|string',
            'tanggal'  => 'required|date',
            'jam'      => 'required',
            'keluhan'  => 'required|string|max:500',
            'diagnosa' => 'required|string|max:100',
            'tindakan' => 'required|string|max:100',
        ]);

        $kunjungan = KunjunganUksGukar::findOrFail($id);
        [$id_guru, $id_karyawan] = $this->parseGukarId($request->gukar_id);

        if (!$id_guru && !$id_karyawan) {
            return back()->withErrors(['gukar_id' => 'Pilihan Guru/Karyawan tidak valid.']);
        }

        $kunjungan->update([
            'id_guru'     => $id_guru,
            'id_karyawan' => $id_karyawan,
            'tanggal'     => $request->tanggal,
            'jam'         => $request->jam,
            'keluhan'     => $request->keluhan,
            'diagnosa'    => $request->diagnosa,
            'tindakan'    => $request->tindakan,
        ]);

        // Update riwayat obat: hapus semua, simpan ulang
        $kunjungan->riwayatObat()->delete();
        if ($request->filled('obat_nama')) {
            foreach ($request->obat_nama as $i => $nama) {
                if (!empty($nama)) {
                    RiwayatObatGukar::create([
                        'id_kunjungan' => $kunjungan->id_kunjungan,
                        'nama_obat'    => $nama,
                        'dosis'        => $request->obat_dosis[$i] ?? '-',
                        'jumlah'       => $request->obat_jumlah[$i] ?? 1,
                    ]);
                }
            }
        }

        return redirect()->route('uks.kunjungan-gukar.index')
            ->with('success', 'Data kunjungan berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $kunjungan = KunjunganUksGukar::findOrFail($id);
        $kunjungan->riwayatObat()->delete();
        $kunjungan->delete();

        return redirect()->route('uks.kunjungan-gukar.index')
            ->with('success', 'Data kunjungan berhasil dihapus.');
    }
}
