<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\JadwalMengajarTemplate;
use App\Models\JadwalMengajarHarian;
use App\Models\Guru;
use App\Models\Kelas;
use App\Models\Mapel;
use App\Models\JamPelajaran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class JadwalMengajarController extends Controller
{
    /**
     * Get Daftar Jadwal Mengajar Template (Acuan Siklus)
     */
    public function indexTemplate(Request $request)
    {
        $query = JadwalMengajarTemplate::with(['guru', 'kelas', 'mapel', 'jamPelajaran']);

        if ($request->filled('id_guru')) {
            $query->where('id_guru', $request->id_guru);
        }
        if ($request->filled('id_kelas')) {
            $query->where('id_kelas', $request->id_kelas);
        }
        if ($request->filled('hari_siklus')) {
            $query->where('hari_siklus', $request->hari_siklus);
        }

        // Urutkan berdasarkan hari siklus dan jam_ke
        $data = $query->orderBy('hari_siklus')->orderBy('jam_ke')->get();

        return response()->json([
            'success' => true,
            'data' => $data,
        ]);
    }

    /**
     * Simpan Item Jadwal Mengajar Template baru
     */
    public function storeTemplate(Request $request)
    {
        // Resolve id_mapel from nama_mapel if not provided
        if (!$request->filled('id_mapel') && $request->filled('nama_mapel')) {
            $mapel = Mapel::where('nama_mapel', $request->nama_mapel)
                ->orWhere('kode_mapel', $request->nama_mapel)
                ->first();
            if ($mapel) {
                $request->merge(['id_mapel' => $mapel->id_mapel]);
            } else {
                $mapel = Mapel::where('nama_mapel', 'like', '%' . $request->nama_mapel . '%')->first();
                if ($mapel) {
                    $request->merge(['id_mapel' => $mapel->id_mapel]);
                } else {
                    $newMapel = Mapel::create([
                        'nama_mapel' => $request->nama_mapel,
                        'kode_mapel' => strtoupper(substr(str_replace(' ', '', $request->nama_mapel), 0, 5)),
                    ]);
                    $request->merge(['id_mapel' => $newMapel->id_mapel]);
                }
            }
        }

        $request->validate([
            'id_guru'      => 'required|integer|exists:guru,id_guru',
            'id_kelas'     => 'required|integer|exists:kelas,id_kelas',
            'id_mapel'     => 'required|integer|exists:mapel,id_mapel',
            'jam_ke'       => 'required|integer',
            'hari_siklus'  => 'required|string',
            'ruang'        => 'nullable|string|max:50',
        ]);

        // Cek Bentrokan Guru
        $bentrokGuru = JadwalMengajarTemplate::where('hari_siklus', $request->hari_siklus)
            ->where('jam_ke', $request->jam_ke)
            ->where('id_guru', $request->id_guru)
            ->exists();

        if ($bentrokGuru) {
            return response()->json([
                'success' => false,
                'message' => 'Guru tersebut sudah memiliki jadwal mengajar di hari dan jam yang sama.',
            ], 422);
        }

        // Cek Bentrokan Kelas
        $bentrokKelas = JadwalMengajarTemplate::where('hari_siklus', $request->hari_siklus)
            ->where('jam_ke', $request->jam_ke)
            ->where('id_kelas', $request->id_kelas)
            ->exists();

        if ($bentrokKelas) {
            return response()->json([
                'success' => false,
                'message' => 'Kelas tersebut sudah terisi oleh mata pelajaran lain di hari dan jam yang sama.',
            ], 422);
        }

        $template = JadwalMengajarTemplate::create($request->only('id_guru', 'id_kelas', 'id_mapel', 'jam_ke', 'hari_siklus', 'ruang'));

        return response()->json([
            'success' => true,
            'message' => 'Jadwal mengajar template berhasil disimpan.',
            'data' => $template->load(['guru', 'kelas', 'mapel', 'jamPelajaran']),
        ], 201);
    }

    /**
     * Hapus Item Jadwal Template
     */
    public function destroyTemplate($id)
    {
        JadwalMengajarTemplate::findOrFail($id)->delete();

        return response()->json([
            'success' => true,
            'message' => 'Item jadwal template berhasil dihapus.',
        ]);
    }

    /**
     * Generate Jadwal Harian dari Acuan Template Siklus untuk range tanggal tertentu
     */
    public function generateHarian(Request $request)
    {
        $request->validate([
            'tanggal_mulai'   => 'required|date',
            'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
        ]);

        $tanggalMulai = new \DateTime($request->tanggal_mulai);
        $tanggalSelesai = new \DateTime($request->tanggal_selesai);
        $interval = new \DateInterval('P1D');
        $period = new \DatePeriod($tanggalMulai, $interval, $tanggalSelesai->modify('+1 day'));

        $templates = JadwalMengajarTemplate::all();
        $generatedCount = 0;
        $skippedCount = 0;

        DB::beginTransaction();
        try {
            foreach ($period as $dt) {
                $tglStr = $dt->format('Y-m-d');
                $hariIndex = (int) $dt->format('N'); // 1 = Senin, 7 = Minggu

                // Cari template yang harinya cocok
                $templatesHariIni = $templates->where('hari_siklus', $hariIndex);

                foreach ($templatesHariIni as $tpl) {
                    // Cek jika sudah ada jadwal harian di tanggal, kelas, jam tersebut
                    $exists = JadwalMengajarHarian::where('tanggal', $tglStr)
                        ->where('id_kelas', $tpl->id_kelas)
                        ->where('jam_ke', $tpl->jam_ke)
                        ->exists();

                    if ($exists) {
                        $skippedCount++;
                        continue;
                    }

                    JadwalMengajarHarian::create([
                        'tanggal'  => $tglStr,
                        'id_kelas' => $tpl->id_kelas,
                        'id_mapel' => $tpl->id_mapel,
                        'id_guru'  => $tpl->id_guru,
                        'jam_ke'   => $tpl->jam_ke,
                        'status'   => 'draft',
                    ]);

                    $generatedCount++;
                }
            }
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Gagal meng-generate jadwal harian: ' . $e->getMessage(),
            ], 500);
        }

        return response()->json([
            'success' => true,
            'message' => "Jadwal harian berhasil di-generate. Berhasil: {$generatedCount}, Dilewati: {$skippedCount}.",
            'generated_count' => $generatedCount,
            'skipped_count' => $skippedCount,
        ]);
    }
}
