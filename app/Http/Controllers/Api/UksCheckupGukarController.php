<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\DataCheckupGukar;
use App\Models\Guru;
use App\Models\Karyawan;
use Illuminate\Http\Request;

class UksCheckupGukarController extends Controller
{
    /**
     * GET /api/uks/gukar-list
     * Daftar Guru & Karyawan untuk dropdown form checkup.
     */
    public function gukarList()
    {
        $gurus = Guru::orderBy('nama_guru')->get()->map(fn($g) => [
            'gukar_id' => "guru_{$g->id_guru}",
            'id_guru'   => $g->id_guru,
            'id_karyawan' => null,
            'nama'      => (string) $g->nama_guru,
            'no_id'     => (string) ($g->no_id ?? '-'),
            'peran'     => 'Guru',
        ]);

        $karyawans = Karyawan::orderBy('nama_karyawan')->get()->map(fn($k) => [
            'gukar_id' => "karyawan_{$k->id_karyawan}",
            'id_guru'   => null,
            'id_karyawan' => $k->id_karyawan,
            'nama'      => (string) $k->nama_karyawan,
            'no_id'     => (string) ($k->no_id ?? '-'),
            'peran'     => 'Karyawan',
        ]);

        $list = $gurus->concat($karyawans)->values();

        return response()->json([
            'success' => true,
            'data'    => $list,
        ]);
    }

    /**
     * GET /api/uks/checkup-gukar
     * Daftar data check-up Guru & Karyawan dengan filter & paginasi.
     */
    public function index(Request $request)
    {
        $query = DataCheckupGukar::with(['guru', 'karyawan'])
            ->orderByDesc('tanggal')
            ->orderByDesc('id_checkup');

        // Filter by Role (guru / karyawan)
        if ($request->filled('role')) {
            $role = $request->role;
            if ($role === 'guru') {
                $query->whereNotNull('id_guru');
            } elseif ($role === 'karyawan') {
                $query->whereNotNull('id_karyawan');
            }
        }

        // Search by name or NIP/ID
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

        // Filter by Date Range
        if ($request->filled('tanggal_dari')) {
            $query->where('tanggal', '>=', $request->tanggal_dari);
        }
        if ($request->filled('tanggal_sampai')) {
            $query->where('tanggal', '<=', $request->tanggal_sampai);
        }

        $perPage = (int) $request->get('per_page', 20);
        $data    = $query->paginate($perPage);

        $mapped = $data->getCollection()->map(fn($item) => $this->formatItem($item));
        $data->setCollection($mapped);

        return response()->json([
            'success'      => true,
            'data'         => $data->items(),
            'current_page' => $data->currentPage(),
            'last_page'    => $data->lastPage(),
            'total'        => $data->total(),
            'has_more'     => $data->hasMorePages(),
            'hari_ini'     => DataCheckupGukar::whereDate('tanggal', today())->count(),
            'total_checkup'=> DataCheckupGukar::count(),
        ]);
    }

    /**
     * POST /api/uks/checkup-gukar
     */
    public function store(Request $request)
    {
        $request->validate([
            'gukar_id'       => 'required|string', // format: guru_X atau karyawan_X
            'tanggal'        => 'required|date',
            'tinggi_badan'   => 'nullable|numeric|min:1',
            'berat_badan'    => 'nullable|numeric|min:1',
            'tekanan_darah'  => 'nullable|string|max:20',
            'kolesterol'     => 'nullable|numeric',
            'gula_darah'     => 'nullable|numeric',
            'tipe_gula_darah'=> 'nullable|in:puasa,sewaktu',
            'asam_urat'      => 'nullable|numeric',
        ]);

        [$id_guru, $id_karyawan] = $this->parseGukarId($request->gukar_id);

        if (!$id_guru && !$id_karyawan) {
            return response()->json(['success' => false, 'message' => 'gukar_id tidak valid.'], 422);
        }

        [$imt, $kategori] = $this->calculateImt($request->tinggi_badan, $request->berat_badan);

        $checkup = DataCheckupGukar::create([
            'id_guru'        => $id_guru,
            'id_karyawan'    => $id_karyawan,
            'tanggal'        => $request->tanggal,
            'tinggi_badan'   => $request->tinggi_badan,
            'berat_badan'    => $request->berat_badan,
            'imt'            => $imt,
            'kategori'       => $kategori,
            'tekanan_darah'  => $request->tekanan_darah,
            'kolesterol'     => $request->kolesterol,
            'gula_darah'     => $request->gula_darah,
            'tipe_gula_darah'=> $request->tipe_gula_darah ?? 'sewaktu',
            'asam_urat'      => $request->asam_urat,
        ]);

        $checkup->load(['guru', 'karyawan']);

        return response()->json([
            'success' => true,
            'message' => 'Data check-up berhasil ditambahkan.',
            'data'    => $this->formatItem($checkup),
        ], 201);
    }

    /**
     * PUT /api/uks/checkup-gukar/{id}
     */
    public function update(Request $request, $id)
    {
        $checkup = DataCheckupGukar::findOrFail($id);

        $request->validate([
            'gukar_id'       => 'nullable|string',
            'tanggal'        => 'required|date',
            'tinggi_badan'   => 'nullable|numeric|min:1',
            'berat_badan'    => 'nullable|numeric|min:1',
            'tekanan_darah'  => 'nullable|string|max:20',
            'kolesterol'     => 'nullable|numeric',
            'gula_darah'     => 'nullable|numeric',
            'tipe_gula_darah'=> 'nullable|in:puasa,sewaktu',
            'asam_urat'      => 'nullable|numeric',
        ]);

        if ($request->filled('gukar_id')) {
            [$id_guru, $id_karyawan] = $this->parseGukarId($request->gukar_id);
            $checkup->id_guru     = $id_guru;
            $checkup->id_karyawan = $id_karyawan;
        }

        [$imt, $kategori] = $this->calculateImt($request->tinggi_badan, $request->berat_badan);

        $checkup->update([
            'tanggal'        => $request->tanggal,
            'tinggi_badan'   => $request->tinggi_badan,
            'berat_badan'    => $request->berat_badan,
            'imt'            => $imt,
            'kategori'       => $kategori,
            'tekanan_darah'  => $request->tekanan_darah,
            'kolesterol'     => $request->kolesterol,
            'gula_darah'     => $request->gula_darah,
            'tipe_gula_darah'=> $request->tipe_gula_darah ?? $checkup->tipe_gula_darah,
            'asam_urat'      => $request->asam_urat,
        ]);

        $checkup->load(['guru', 'karyawan']);

        return response()->json([
            'success' => true,
            'message' => 'Data check-up berhasil diperbarui.',
            'data'    => $this->formatItem($checkup),
        ]);
    }

    /**
     * DELETE /api/uks/checkup-gukar/{id}
     */
    public function destroy($id)
    {
        $checkup = DataCheckupGukar::findOrFail($id);
        $checkup->delete();

        return response()->json([
            'success' => true,
            'message' => 'Data check-up berhasil dihapus.',
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

    private function calculateImt($tb, $bb): array
    {
        if (!$tb || !$bb || $tb <= 0 || $bb <= 0) {
            return [null, null];
        }
        $tbM = $tb / 100;
        $imt = round($bb / ($tbM * $tbM), 1);
        $kategori = match (true) {
            $imt < 18.5  => 'Kurus',
            $imt <= 25.0 => 'Normal',
            $imt <= 27.0 => 'Gemuk',
            default      => 'Obesitas',
        };
        return [$imt, $kategori];
    }

    private function formatItem(DataCheckupGukar $item): array
    {
        $isGuru  = (bool) $item->id_guru;
        $nama    = $isGuru ? ($item->guru?->nama_guru ?? '-') : ($item->karyawan?->nama_karyawan ?? '-');
        $noId    = $isGuru ? (string) ($item->guru?->no_id ?? '-') : (string) ($item->karyawan?->no_id ?? '-');
        $jenkel  = $isGuru ? ($item->guru?->jenkel ?? 'L') : ($item->karyawan?->jenkel ?? 'L');

        return [
            'id'             => $item->id_checkup,
            'id_guru'        => $item->id_guru,
            'id_karyawan'    => $item->id_karyawan,
            'gukar_id'       => $isGuru ? "guru_{$item->id_guru}" : "karyawan_{$item->id_karyawan}",
            'peran'          => $isGuru ? 'Guru' : 'Karyawan',
            'nama'           => $nama,
            'no_id'          => $noId,
            'jenkel'         => $jenkel,
            'tanggal'        => $item->tanggal ? \Carbon\Carbon::parse($item->tanggal)->format('Y-m-d') : null,
            'tinggi_badan'   => $item->tinggi_badan,
            'berat_badan'    => $item->berat_badan,
            'imt'            => $item->imt,
            'kategori'       => $item->kategori,
            'tekanan_darah'  => $item->tekanan_darah,
            'kolesterol'     => $item->kolesterol,
            'gula_darah'     => $item->gula_darah,
            'tipe_gula_darah'=> $item->tipe_gula_darah ?? 'sewaktu',
            'asam_urat'      => $item->asam_urat,
        ];
    }
}
