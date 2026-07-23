<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\KunjunganUks;
use App\Models\RiwayatObat;
use Illuminate\Http\Request;
use Carbon\Carbon;

class UksKunjunganController extends Controller
{
    public function index(Request $request)
    {
        $query = KunjunganUks::with(['siswa.kelas.jurusan', 'riwayatObat'])
            ->orderByDesc('tanggal')
            ->orderByDesc('jam');

        // Filter by search (nama siswa atau NIS)
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nis', 'like', "%{$search}%")
                  ->orWhereHas('siswa', function ($sq) use ($search) {
                      $sq->where('nama_siswa', 'like', "%{$search}%");
                  });
            });
        }

        // Filter by tanggal_dari
        if ($request->filled('tanggal_dari')) {
            $query->where('tanggal', '>=', $request->tanggal_dari);
        }

        // Filter by tanggal_sampai
        if ($request->filled('tanggal_sampai')) {
            $query->where('tanggal', '<=', $request->tanggal_sampai);
        }

        // Filter by bulan & tahun (legacy support)
        if ($request->filled('bulan') && $request->filled('tahun')) {
            $query->whereMonth('tanggal', $request->bulan)
                  ->whereYear('tanggal', $request->tahun);
        }

        $perPage = $request->get('per_page', 20);
        $paginated = $query->paginate($perPage);

        // Stats
        $hariIni  = KunjunganUks::whereDate('tanggal', today())->count();
        $bulanIni = KunjunganUks::whereMonth('tanggal', now()->month)
                        ->whereYear('tanggal', now()->year)->count();
        $totalAll = KunjunganUks::count();

        // Format data items
        $items = $paginated->getCollection()->map(function ($item) {
            $siswa = $item->siswa;
            $kelas = $siswa?->kelas;

            $namaKelas = '-';
            if ($kelas) {
                $namaKelas = $kelas->nama_kelas ?? (($kelas->tingkat ?? '') . ' ' . ($kelas->rombel ?? ''));
                if ($kelas->jurusan) {
                    $namaKelas = ($kelas->tingkat ?? '') . ' ' . ($kelas->jurusan->singkatan ?? $kelas->jurusan->nama_jurusan ?? '') . ' ' . ($kelas->rombel ?? '');
                }
            }

            return [
                'id'          => $item->id_kunjungan,
                'nis'         => (string) $item->nis,
                'nama_siswa'  => $siswa?->nama_siswa ?? '-',
                'kelas'       => trim($namaKelas),
                'tanggal'     => $item->tanggal ? $item->tanggal->toDateString() : '',
                'jam'         => $item->jam ?? '',
                'keluhan'     => $item->keluhan ?? '',
                'diagnosa'    => $item->diagnosa ?? '',
                'tindakan'    => $item->tindakan ?? '',
                'obat'        => $item->riwayatObat->map(function ($o) {
                    return [
                        'id_riwayat'   => $o->id_riwayat,
                        'id_kunjungan' => $o->id_kunjungan,
                        'nama_obat'    => $o->nama_obat,
                        'dosis'        => $o->dosis ?? '-',
                        'jumlah'       => (int) ($o->jumlah ?? 1),
                    ];
                })->values(),
            ];
        });

        return response()->json([
            'success'      => true,
            'hari_ini'     => $hariIni,
            'bulan_ini'    => $bulanIni,
            'total_all'    => $totalAll,
            'has_more'     => $paginated->hasMorePages(),
            'current_page' => $paginated->currentPage(),
            'data'         => $items->values(),
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'nis'      => 'required|integer|exists:user_siswa,nis',
            'tanggal'  => 'required|date',
            'jam'      => 'nullable|string|max:10',
            'keluhan'  => 'required|string|max:500',
            'diagnosa' => 'nullable|string|max:200',
            'tindakan' => 'required|string|max:200',
            'obat'     => 'nullable|array',
        ]);

        $kunjungan = KunjunganUks::create([
            'nis'      => $request->nis,
            'tanggal'  => $request->tanggal,
            'jam'      => $request->jam ?? now()->format('H:i'),
            'keluhan'  => $request->keluhan,
            'diagnosa' => $request->diagnosa ?? '',
            'tindakan' => $request->tindakan,
        ]);

        // Simpan riwayat obat jika ada
        if ($request->filled('obat') && is_array($request->obat)) {
            foreach ($request->obat as $o) {
                $nama = is_array($o) ? ($o['nama_obat'] ?? '') : (string) $o;
                if (!empty($nama)) {
                    RiwayatObat::create([
                        'id_kunjungan' => $kunjungan->id_kunjungan,
                        'nama_obat'    => $nama,
                        'dosis'        => is_array($o) ? ($o['dosis'] ?? '-') : '-',
                        'jumlah'       => is_array($o) ? ($o['jumlah'] ?? 1) : 1,
                    ]);
                }
            }
        }

        $kunjungan->load(['siswa.kelas.jurusan', 'riwayatObat']);
        $siswa  = $kunjungan->siswa;
        $kelas  = $siswa?->kelas;

        $namaKelas = '-';
        if ($kelas) {
            if ($kelas->jurusan) {
                $namaKelas = ($kelas->tingkat ?? '') . ' ' . ($kelas->jurusan->singkatan ?? $kelas->jurusan->nama_jurusan ?? '') . ' ' . ($kelas->rombel ?? '');
            } else {
                $namaKelas = $kelas->nama_kelas ?? (($kelas->tingkat ?? '') . ' ' . ($kelas->rombel ?? ''));
            }
        }

        return response()->json([
            'success' => true,
            'message' => 'Kunjungan UKS berhasil dicatat.',
            'data'    => [
                'id'         => $kunjungan->id_kunjungan,
                'nis'        => (string) $kunjungan->nis,
                'nama_siswa' => $siswa?->nama_siswa ?? '-',
                'kelas'      => trim($namaKelas),
                'tanggal'    => $kunjungan->tanggal ? $kunjungan->tanggal->toDateString() : '',
                'jam'        => $kunjungan->jam ?? '',
                'keluhan'    => $kunjungan->keluhan ?? '',
                'diagnosa'   => $kunjungan->diagnosa ?? '',
                'tindakan'   => $kunjungan->tindakan ?? '',
                'obat'       => $kunjungan->riwayatObat->map(function ($o) {
                    return [
                        'id_riwayat'   => $o->id_riwayat,
                        'id_kunjungan' => $o->id_kunjungan,
                        'nama_obat'    => $o->nama_obat,
                        'dosis'        => $o->dosis ?? '-',
                        'jumlah'       => (int) ($o->jumlah ?? 1),
                    ];
                })->values(),
            ],
        ], 201);
    }

    public function show($id)
    {
        $kunjungan = KunjunganUks::with(['siswa.kelas.jurusan', 'riwayatObat'])->findOrFail($id);

        return response()->json([
            'success' => true,
            'data'    => $kunjungan,
        ]);
    }

    public function update(Request $request, $id)
    {
        $kunjungan = KunjunganUks::findOrFail($id);

        $request->validate([
            'nis'      => 'sometimes|required|integer|exists:user_siswa,nis',
            'tanggal'  => 'sometimes|required|date',
            'jam'      => 'nullable|string|max:10',
            'keluhan'  => 'sometimes|required|string|max:500',
            'diagnosa' => 'nullable|string|max:200',
            'tindakan' => 'sometimes|required|string|max:200',
            'obat'     => 'nullable|array',
        ]);

        $kunjungan->update($request->only('nis', 'tanggal', 'jam', 'keluhan', 'diagnosa', 'tindakan'));

        // Update riwayat obat
        if ($request->has('obat')) {
            $kunjungan->riwayatObat()->delete();
            if (is_array($request->obat)) {
                foreach ($request->obat as $o) {
                    $nama = is_array($o) ? ($o['nama_obat'] ?? '') : (string) $o;
                    if (!empty($nama)) {
                        RiwayatObat::create([
                            'id_kunjungan' => $kunjungan->id_kunjungan,
                            'nama_obat'    => $nama,
                            'dosis'        => is_array($o) ? ($o['dosis'] ?? '-') : '-',
                            'jumlah'       => is_array($o) ? ($o['jumlah'] ?? 1) : 1,
                        ]);
                    }
                }
            }
        }

        $kunjungan->load(['siswa.kelas.jurusan', 'riwayatObat']);

        $siswa = $kunjungan->siswa;
        $kelas = $siswa?->kelas;
        $namaKelas = '-';
        if ($kelas) {
            if ($kelas->jurusan) {
                $namaKelas = ($kelas->tingkat ?? '') . ' ' . ($kelas->jurusan->singkatan ?? $kelas->jurusan->nama_jurusan ?? '') . ' ' . ($kelas->rombel ?? '');
            } else {
                $namaKelas = $kelas->nama_kelas ?? (($kelas->tingkat ?? '') . ' ' . ($kelas->rombel ?? ''));
            }
        }

        return response()->json([
            'success' => true,
            'message' => 'Kunjungan UKS berhasil diperbarui.',
            'data'    => [
                'id'         => $kunjungan->id_kunjungan,
                'nis'        => (string) $kunjungan->nis,
                'nama_siswa' => $siswa?->nama_siswa ?? '-',
                'kelas'      => trim($namaKelas),
                'tanggal'    => $kunjungan->tanggal ? $kunjungan->tanggal->toDateString() : '',
                'jam'        => $kunjungan->jam ?? '',
                'keluhan'    => $kunjungan->keluhan ?? '',
                'diagnosa'   => $kunjungan->diagnosa ?? '',
                'tindakan'   => $kunjungan->tindakan ?? '',
                'obat'       => $kunjungan->riwayatObat->map(function ($o) {
                    return [
                        'id_riwayat'   => $o->id_riwayat,
                        'id_kunjungan' => $o->id_kunjungan,
                        'nama_obat'    => $o->nama_obat,
                        'dosis'        => $o->dosis ?? '-',
                        'jumlah'       => (int) ($o->jumlah ?? 1),
                    ];
                })->values(),
            ],
        ]);
    }

    public function destroy($id)
    {
        $kunjungan = KunjunganUks::findOrFail($id);
        $kunjungan->riwayatObat()->delete();
        $kunjungan->delete();

        return response()->json([
            'success' => true,
            'message' => 'Data kunjungan UKS berhasil dihapus.',
        ]);
    }

    /**
     * Get UKS visit history for logged-in student/wali.
     * Endpoint: GET /api/kunjungan_uks/siswa
     */
    public function getKunjunganSiswaHistory(Request $request)
    {
        $user = $request->user();
        $nis  = $user->nis ?? null;

        if (!$nis) {
            return response()->json([
                'success' => false,
                'message' => 'Data siswa tidak ditemukan.',
            ], 403);
        }

        $kunjungan = KunjunganUks::with('riwayatObat')
            ->where('nis', $nis)
            ->orderByDesc('tanggal')
            ->orderByDesc('id_kunjungan')
            ->get();

        $history = $kunjungan->map(function ($item) {
            return [
                'kunjungan' => [
                    'id_kunjungan' => $item->id_kunjungan,
                    'nis'          => (string) $item->nis,
                    'tanggal'      => $item->tanggal ? $item->tanggal->toDateString() : '',
                    'jam'          => $item->jam ?? '',
                    'keluhan'      => $item->keluhan ?? '',
                    'diagnosa'     => $item->diagnosa ?? '',
                    'tindakan'     => $item->tindakan ?? '',
                ],
                'obat' => $item->riwayatObat->map(function ($o) {
                    return [
                        'id_riwayat'   => $o->id_riwayat,
                        'id_kunjungan' => $o->id_kunjungan,
                        'nama_obat'    => $o->nama_obat,
                        'dosis'        => $o->dosis,
                        'jumlah'       => $o->jumlah,
                    ];
                })
            ];
        });

        return response()->json([
            'success' => true,
            'data'    => [
                'nis'     => (string) $nis,
                'history' => $history,
            ]
        ]);
    }
}
