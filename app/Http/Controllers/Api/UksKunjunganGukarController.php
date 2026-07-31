<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\KunjunganUksGukar;
use App\Models\RiwayatObatGukar;
use App\Models\Guru;
use App\Models\Karyawan;
use Illuminate\Http\Request;
use Carbon\Carbon;

class UksKunjunganGukarController extends Controller
{
    /**
     * GET /api/uks/kunjungan-gukar
     * Daftar kunjungan UKS Gukar dengan filter & paginasi.
     */
    public function index(Request $request)
    {
        $query = KunjunganUksGukar::with(['guru', 'karyawan', 'riwayatObat'])
            ->orderByDesc('tanggal')
            ->orderByDesc('jam');

        // Filter by role
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

        // Filter by bulan & tahun
        if ($request->filled('bulan') && $request->filled('tahun')) {
            $query->whereMonth('tanggal', $request->bulan)
                  ->whereYear('tanggal', $request->tahun);
        }

        $perPage   = $request->get('per_page', 20);
        $paginated = $query->paginate($perPage);

        $hariIni  = KunjunganUksGukar::whereDate('tanggal', today())->count();
        $bulanIni = KunjunganUksGukar::whereMonth('tanggal', now()->month)
                        ->whereYear('tanggal', now()->year)->count();
        $totalAll = KunjunganUksGukar::count();

        $items = $paginated->getCollection()->map(fn ($item) => $this->formatItem($item));

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

    /**
     * POST /api/uks/kunjungan-gukar
     */
    public function store(Request $request)
    {
        $request->validate([
            'gukar_id'    => 'nullable|string',
            'id_guru'     => 'nullable|integer',
            'id_karyawan' => 'nullable|integer',
            'tanggal'     => 'required|date',
            'jam'         => 'nullable|string|max:10',
            'keluhan'     => 'required|string|max:500',
            'diagnosa'    => 'nullable|string|max:200',
            'tindakan'    => 'required|string|max:200',
            'obat'        => 'nullable',
        ]);

        $id_guru = $request->id_guru;
        $id_karyawan = $request->id_karyawan;

        if (!$id_guru && !$id_karyawan && $request->filled('gukar_id')) {
            [$id_guru, $id_karyawan] = $this->parseGukarId($request->gukar_id);
        }

        if (!$id_guru && !$id_karyawan) {
            return response()->json(['success' => false, 'message' => 'Pilih guru atau karyawan terlebih dahulu.'], 422);
        }

        $kunjungan = KunjunganUksGukar::create([
            'id_guru'     => $id_guru,
            'id_karyawan' => $id_karyawan,
            'tanggal'     => $request->tanggal,
            'jam'         => $request->jam ?? now()->format('H:i'),
            'keluhan'     => $request->keluhan,
            'diagnosa'    => $request->diagnosa ?? '',
            'tindakan'    => $request->tindakan,
        ]);

        if ($request->filled('obat')) {
            $obatData = $request->obat;
            if (is_string($obatData)) {
                $lines = explode("\n", $obatData);
                foreach ($lines as $line) {
                    $nama = trim($line);
                    if (!empty($nama)) {
                        RiwayatObatGukar::create([
                            'id_kunjungan' => $kunjungan->id_kunjungan,
                            'nama_obat'    => $nama,
                            'dosis'        => '-',
                            'jumlah'       => 1,
                        ]);
                    }
                }
            } elseif (is_array($obatData)) {
                foreach ($obatData as $o) {
                    $nama = is_array($o) ? ($o['nama_obat'] ?? '') : (string) $o;
                    if (!empty($nama)) {
                        RiwayatObatGukar::create([
                            'id_kunjungan' => $kunjungan->id_kunjungan,
                            'nama_obat'    => $nama,
                            'dosis'        => is_array($o) ? ($o['dosis'] ?? '-') : '-',
                            'jumlah'       => is_array($o) ? ($o['jumlah'] ?? 1) : 1,
                        ]);
                    }
                }
            }
        }

        $kunjungan->load(['guru', 'karyawan', 'riwayatObat']);

        return response()->json([
            'success' => true,
            'message' => 'Kunjungan UKS Gukar berhasil dicatat.',
            'data'    => $this->formatItem($kunjungan),
        ], 201);
    }

    /**
     * GET /api/uks/kunjungan-gukar/{id}
     */
    public function show($id)
    {
        $kunjungan = KunjunganUksGukar::with(['guru', 'karyawan', 'riwayatObat'])->findOrFail($id);
        return response()->json([
            'success' => true,
            'data'    => $this->formatItem($kunjungan),
        ]);
    }

    /**
     * PUT /api/uks/kunjungan-gukar/{id}
     */
    public function update(Request $request, $id)
    {
        $kunjungan = KunjunganUksGukar::findOrFail($id);

        $request->validate([
            'gukar_id'    => 'nullable|string',
            'id_guru'     => 'nullable|integer',
            'id_karyawan' => 'nullable|integer',
            'tanggal'     => 'sometimes|required|date',
            'jam'         => 'nullable|string|max:10',
            'keluhan'     => 'sometimes|required|string|max:500',
            'diagnosa'    => 'nullable|string|max:200',
            'tindakan'    => 'sometimes|required|string|max:200',
            'obat'        => 'nullable',
        ]);

        if ($request->filled('gukar_id')) {
            [$id_guru, $id_karyawan] = $this->parseGukarId($request->gukar_id);
            $kunjungan->id_guru     = $id_guru;
            $kunjungan->id_karyawan = $id_karyawan;
        } elseif ($request->filled('id_guru') || $request->filled('id_karyawan')) {
            $kunjungan->id_guru     = $request->id_guru;
            $kunjungan->id_karyawan = $request->id_karyawan;
        }

        $kunjungan->update($request->only('tanggal', 'jam', 'keluhan', 'diagnosa', 'tindakan'));

        if ($request->has('obat')) {
            $kunjungan->riwayatObat()->delete();
            $obatData = $request->obat;
            if (is_string($obatData)) {
                $lines = explode("\n", $obatData);
                foreach ($lines as $line) {
                    $nama = trim($line);
                    if (!empty($nama)) {
                        RiwayatObatGukar::create([
                            'id_kunjungan' => $kunjungan->id_kunjungan,
                            'nama_obat'    => $nama,
                            'dosis'        => '-',
                            'jumlah'       => 1,
                        ]);
                    }
                }
            } elseif (is_array($obatData)) {
                foreach ($obatData as $o) {
                    $nama = is_array($o) ? ($o['nama_obat'] ?? '') : (string) $o;
                    if (!empty($nama)) {
                        RiwayatObatGukar::create([
                            'id_kunjungan' => $kunjungan->id_kunjungan,
                            'nama_obat'    => $nama,
                            'dosis'        => is_array($o) ? ($o['dosis'] ?? '-') : '-',
                            'jumlah'       => is_array($o) ? ($o['jumlah'] ?? 1) : 1,
                        ]);
                    }
                }
            }
        }

        $kunjungan->load(['guru', 'karyawan', 'riwayatObat']);

        return response()->json([
            'success' => true,
            'message' => 'Kunjungan UKS Gukar berhasil diperbarui.',
            'data'    => $this->formatItem($kunjungan),
        ]);
    }

    /**
     * DELETE /api/uks/kunjungan-gukar/{id}
     */
    public function destroy($id)
    {
        $kunjungan = KunjunganUksGukar::findOrFail($id);
        $kunjungan->riwayatObat()->delete();
        $kunjungan->delete();

        return response()->json([
            'success' => true,
            'message' => 'Data kunjungan UKS Gukar berhasil dihapus.',
        ]);
    }

    /**
     * GET /api/kunjungan_uks/gukar
     * Riwayat kunjungan UKS untuk akun Guru/Karyawan yang sedang login.
     */
    public function getKunjunganGukarHistory(Request $request)
    {
        $user        = $request->user();
        $idGuru      = null;
        $idKaryawan  = null;
        $namaUser    = '';

        if ($user instanceof Guru) {
            $idGuru   = $user->id_guru;
            $namaUser = $user->nama_guru;
        } elseif ($user instanceof Karyawan) {
            $idKaryawan = $user->id_karyawan;
            $namaUser   = $user->nama_karyawan;
        } else {
            if (isset($user->no_id)) {
                $guru = Guru::where('no_id', $user->no_id)->first();
                if ($guru) {
                    $idGuru   = $guru->id_guru;
                    $namaUser = $guru->nama_guru;
                } else {
                    $karyawan = Karyawan::where('no_id', $user->no_id)->first();
                    if ($karyawan) {
                        $idKaryawan = $karyawan->id_karyawan;
                        $namaUser   = $karyawan->nama_karyawan;
                    }
                }
            }
        }

        if (!$idGuru && !$idKaryawan) {
            return response()->json([
                'success' => false,
                'message' => 'Data Guru / Karyawan tidak ditemukan untuk akun ini.',
            ], 404);
        }

        $query = KunjunganUksGukar::with('riwayatObat');
        if ($idGuru) {
            $query->where('id_guru', $idGuru);
        } else {
            $query->where('id_karyawan', $idKaryawan);
        }

        $kunjungan = $query->orderByDesc('tanggal')->orderByDesc('id_kunjungan')->get();

        $history = $kunjungan->map(function ($item) {
            return [
                'id_kunjungan' => $item->id_kunjungan,
                'tanggal'      => $item->tanggal ? $item->tanggal->toDateString() : '',
                'jam'          => $item->jam ?? '',
                'keluhan'      => $item->keluhan ?? '',
                'diagnosa'     => $item->diagnosa ?? '',
                'tindakan'     => $item->tindakan ?? '',
                'daftar_obat'  => $item->riwayatObat->map(function ($o) {
                    return [
                        'id_riwayat' => $o->id_riwayat,
                        'nama_obat'  => $o->nama_obat,
                        'dosis'      => $o->dosis,
                        'jumlah'     => $o->jumlah,
                    ];
                })->values()->all(),
            ];
        });

        return response()->json([
            'success' => true,
            'data'    => [
                'nama'    => $namaUser,
                'history' => $history,
            ],
        ]);
    }

    // ─── Private Helpers ──────────────────────────────────────────────────────

    private function parseGukarId(string $gukarId): array
    {
        if (str_starts_with($gukarId, 'guru_')) {
            return [(int) str_replace('guru_', '', $gukarId), null];
        }
        if (str_starts_with($gukarId, 'karyawan_')) {
            return [null, (int) str_replace('karyawan_', '', $gukarId)];
        }
        return [null, null];
    }

    private function formatItem(KunjunganUksGukar $item): array
    {
        $isGuru = (bool) $item->id_guru;
        $nama   = $isGuru ? ($item->guru?->nama_guru ?? '-') : ($item->karyawan?->nama_karyawan ?? '-');
        $noId   = $isGuru ? (string) ($item->guru?->no_id ?? '-') : (string) ($item->karyawan?->no_id ?? '-');
        $peran  = $isGuru ? 'Guru' : 'Karyawan';

        return [
            'id'           => $item->id_kunjungan,
            'id_guru'      => $item->id_guru,
            'id_karyawan'  => $item->id_karyawan,
            'gukar_id'     => $isGuru ? "guru_{$item->id_guru}" : "karyawan_{$item->id_karyawan}",
            'peran'        => $peran,
            'nama'         => $nama,
            'no_id'        => $noId,
            'tanggal'      => $item->tanggal ? $item->tanggal->toDateString() : '',
            'jam'          => $item->jam ?? '',
            'keluhan'      => $item->keluhan ?? '',
            'diagnosa'     => $item->diagnosa ?? '',
            'tindakan'     => $item->tindakan ?? '',
            'daftar_obat'  => $item->riwayatObat->map(function ($o) {
                return [
                    'id_riwayat' => $o->id_riwayat,
                    'nama_obat'  => $o->nama_obat,
                    'dosis'      => $o->dosis ?? '-',
                    'jumlah'     => (int) ($o->jumlah ?? 1),
                ];
            })->values()->all(),
            'obat'         => $item->riwayatObat->map(function ($o) {
                $str = $o->nama_obat;
                if (!empty($o->dosis) && $o->dosis !== '-') $str .= " ({$o->dosis})";
                if (!empty($o->jumlah) && (int)$o->jumlah > 1) $str .= " - {$o->jumlah} Pcs";
                return $str;
            })->implode("\n"),
        ];
    }
}
