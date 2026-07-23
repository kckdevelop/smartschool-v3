<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Guru;
use App\Models\JadwalMengajarHarian;
use App\Models\JadwalMengajarTemplate;
use App\Models\JadwalSiklus;
use Carbon\Carbon;
use Illuminate\Http\Request;

class GuruDashboardController extends Controller
{
    /**
     * Helper: cari id_guru dari user yang login.
     */
    private function resolveIdGuru(Request $request): ?int
    {
        $user = $request->user();
        if (!$user) return null;

        if (isset($user->id_guru)) return $user->id_guru;

        if (isset($user->no_id)) {
            return Guru::where('no_id', $user->no_id)->value('id_guru');
        }

        return null;
    }

    /**
     * Helper: cari hari siklus (D1–D12) untuk tanggal tertentu.
     */
    private function getHariSiklus(string $tanggal): ?string
    {
        return JadwalSiklus::where('tanggal', $tanggal)->value('hari_ke');
    }

    /**
     * Ambil jadwal mengajar guru hari ini (berdasarkan token login).
     * Endpoint: GET /api/guru/jadwal-hari-ini
     *
     * Logika:
     *  1. Cek JadwalMengajarHarian untuk tanggal hari ini.
     *  2. Jika kosong, fallback ke JadwalMengajarTemplate berdasarkan siklus hari ini.
     *
     * Returns:
     *   { tanggal, id_guru, jumlah_jadwal, hari_siklus, sumber, jadwal: [...] }
     */
    public function jadwalHariIni(Request $request)
    {
        $idGuru = $this->resolveIdGuru($request);

        if (!$idGuru) {
            return response()->json([
                'success' => false,
                'message' => 'Data guru tidak ditemukan.',
            ], 403);
        }

        $tanggal    = $request->query('tanggal') ?: Carbon::today()->toDateString();
        $hariSiklus = $this->getHariSiklus($tanggal);

        // ── 1. Ambil dari JadwalMengajarHarian ─────────────────────────────
        $harianList = JadwalMengajarHarian::with(['kelas', 'mapel', 'jamPelajaran'])
            ->where('id_guru', $idGuru)
            ->whereDate('tanggal', $tanggal)
            ->orderBy('jam_ke')
            ->get();

        if ($harianList->isNotEmpty()) {
            $jadwalList = $harianList->map(function ($j) {
                return [
                    'id'          => $j->id_jadwal_harian ?? $j->id ?? null,
                    'kelas'       => $j->kelas?->nama_kelas ?? null,
                    'mapel'       => $j->mapel?->nama_mapel ?? null,
                    'jam_ke'      => $j->jam_ke ?? null,
                    'status'      => $j->status ?? null,
                    'jam_mulai'   => $j->jamPelajaran?->jam_mulai ?? null,
                    'jam_selesai' => $j->jamPelajaran?->jam_selesai ?? null,
                ];
            })->toArray();

            return response()->json([
                'success' => true,
                'data'    => [
                    'tanggal'       => $tanggal,
                    'id_guru'       => $idGuru,
                    'jumlah_jadwal' => count($jadwalList),
                    'hari_siklus'   => $hariSiklus,
                    'sumber'        => 'harian',
                    'jadwal'        => $jadwalList,
                ],
            ]);
        }

        // ── 2. Fallback ke JadwalMengajarTemplate ──────────────────────────
        if ($hariSiklus) {
            $templateList = JadwalMengajarTemplate::with(['kelas', 'mapel', 'jamPelajaran'])
                ->where('id_guru', $idGuru)
                ->where('hari_siklus', $hariSiklus)
                ->orderBy('id_jam')
                ->get();

            $jadwalList = $templateList->map(function ($t) use ($hariSiklus) {
                return [
                    'id'          => $t->id_template ?? null,
                    'kelas'       => $t->kelas?->nama_kelas ?? null,
                    'mapel'       => $t->mapel?->nama_mapel ?? null,
                    'jam_ke'      => $t->jam_ke ?? null,
                    'status'      => null,
                    'jam_mulai'   => $t->jamPelajaran?->jam_mulai ?? null,
                    'jam_selesai' => $t->jamPelajaran?->jam_selesai ?? null,
                ];
            })->toArray();

            return response()->json([
                'success' => true,
                'data'    => [
                    'tanggal'       => $tanggal,
                    'id_guru'       => $idGuru,
                    'jumlah_jadwal' => count($jadwalList),
                    'hari_siklus'   => $hariSiklus,
                    'sumber'        => 'template',
                    'jadwal'        => $jadwalList,
                ],
            ]);
        }

        // ── 3. Tidak ada jadwal & siklus tidak ditemukan ───────────────────
        return response()->json([
            'success' => true,
            'data'    => [
                'tanggal'       => $tanggal,
                'id_guru'       => $idGuru,
                'jumlah_jadwal' => 0,
                'hari_siklus'   => null,
                'sumber'        => 'none',
                'jadwal'        => [],
            ],
        ]);
    }

    /**
     * Rekap jurnal mengajar guru hari ini/tanggal tertentu.
     * Endpoint: GET /api/guru/jurnal-hari-ini
     *
     * Logika:
     *  - total_jadwal: dari JadwalMengajarHarian jika ada, fallback template.
     *  - jurnal_terisi: dari JadwalMengajarHarian yang sudah ber-status.
     *
     * Returns:
     *   { tanggal, id_guru, jurnal_terisi, total_jadwal, hari_siklus }
     */
    public function jurnalHariIni(Request $request)
    {
        $idGuru = $this->resolveIdGuru($request);

        if (!$idGuru) {
            return response()->json([
                'success' => false,
                'message' => 'Data guru tidak ditemukan.',
            ], 403);
        }

        $tanggal    = $request->query('tanggal') ?: Carbon::today()->toDateString();
        $hariSiklus = $this->getHariSiklus($tanggal);

        // Jumlah jadwal harian
        $totalJadwal = JadwalMengajarHarian::where('id_guru', $idGuru)
            ->whereDate('tanggal', $tanggal)
            ->count();

        // Fallback ke template jika harian kosong
        if ($totalJadwal === 0 && $hariSiklus) {
            $totalJadwal = JadwalMengajarTemplate::where('id_guru', $idGuru)
                ->where('hari_siklus', $hariSiklus)
                ->count();
        }

        // Jurnal terisi = jadwal harian yang sudah punya status
        $jurnalTerisi = JadwalMengajarHarian::where('id_guru', $idGuru)
            ->whereDate('tanggal', $tanggal)
            ->whereNotNull('status')
            ->count();

        return response()->json([
            'success' => true,
            'data'    => [
                'tanggal'       => $tanggal,
                'id_guru'       => $idGuru,
                'jurnal_terisi' => $jurnalTerisi,
                'total_jadwal'  => $totalJadwal,
                'hari_siklus'   => $hariSiklus,
            ],
        ]);
    }

    /**
     * Grouped schedule with journal status matching web /jurnal-guru.
     * Endpoint: GET /api/guru/jurnal-guru
     */
    public function jurnalGuru(Request $request)
    {
        $idGuru = $this->resolveIdGuru($request);

        if (!$idGuru) {
            return response()->json([
                'success' => false,
                'message' => 'Data guru tidak ditemukan.',
            ], 403);
        }

        $tanggal    = $request->query('tanggal') ?: Carbon::today()->toDateString();
        $hariSiklus = $this->getHariSiklus($tanggal);

        // ── 1. Fetch schedules from JadwalMengajarHarian ──────────────────
        $harianList = JadwalMengajarHarian::with(['kelas.jurusan', 'mapel', 'jamPelajaran', 'guru'])
            ->where('id_guru', $idGuru)
            ->whereDate('tanggal', $tanggal)
            ->orderBy('jam_ke')
            ->get();

        $schedules = $harianList;
        $sumber = 'harian';

        // ── 2. Fallback to JadwalMengajarTemplate if empty ────────────────
        if ($schedules->isEmpty() && $hariSiklus) {
            $templateList = JadwalMengajarTemplate::with(['kelas.jurusan', 'mapel', 'jamPelajaran', 'guru'])
                ->where('id_guru', $idGuru)
                ->where('hari_siklus', $hariSiklus)
                ->orderBy('jam_ke')
                ->get();
            $schedules = $templateList;
            $sumber = 'template';
        }

        // ── 3. Fetch journals (Kemajuan) for this teacher and date ────────
        $jurnals = \App\Models\Kemajuan::where('id_guru', $idGuru)
            ->whereDate('tanggal', $tanggal)
            ->get();

        // ── 4. Group consecutive and identical schedule hours ─────────────
        $processedGroups = [];

        if ($schedules->isNotEmpty()) {
            // Sort by jam_ke just to be sure
            $sorted = $schedules->sortBy('jam_ke')->values();
            
            $groups = [];
            $currentGroup = [$sorted[0]];
            
            for ($i = 1; $i < count($sorted); $i++) {
                $prev = $sorted[$i - 1];
                $next = $sorted[$i];
                
                $consecutive = ($next->jam_ke == $prev->jam_ke + 1);
                $sameMapel = ($next->id_mapel == $prev->id_mapel);
                $sameKelas = ($next->id_kelas == $prev->id_kelas);
                
                if ($consecutive && $sameMapel && $sameKelas) {
                    $currentGroup[] = $next;
                } else {
                    $groups[] = $currentGroup;
                    $currentGroup = [$next];
                }
            }
            $groups[] = $currentGroup;

            foreach ($groups as $groupItems) {
                $first = $groupItems[0];
                $last = $groupItems[count($groupItems) - 1];
                
                $hours = array_unique(array_map(fn($item) => (int)$item->jam_ke, $groupItems));
                sort($hours);
                
                if (count($hours) === 1) {
                    $jamRangeStr = (string)$hours[0];
                } else {
                    $jamRangeStr = $hours[0] . '-' . $hours[count($hours) - 1];
                }

                $ids = [];
                foreach ($groupItems as $item) {
                    $ids[] = $item->id_jadwal_harian ?? $item->id_template ?? null;
                }

                // Match with journal (Kemajuan)
                $matchingJurnal = $jurnals->first(function ($j) use ($first) {
                    return $j->id_kelas == $first->id_kelas
                        && $j->id_mapel == $first->id_mapel;
                });

                $namaKelas = '';
                if ($first->kelas) {
                    $namaKelas = $first->kelas->tingkat . ' ' . $first->kelas->rombel;
                }

                // Parse keterangan JSON or legacy plain text
                $hambatan = null;
                $pemecahan = null;
                if ($matchingJurnal) {
                    $keteranganData = json_decode($matchingJurnal->keterangan, true);
                    if (is_array($keteranganData)) {
                        $hambatan = $keteranganData['hambatan'] ?? null;
                        $pemecahan = $keteranganData['pemecahan'] ?? null;
                    } else {
                        $hambatan = $matchingJurnal->keterangan;
                    }
                }

                // Get teacher name
                $namaGuru = $first->guru->nama_guru ?? ($request->user()->nama_karyawan ?? $request->user()->username ?? '—');

                $processedGroups[] = [
                    'ids' => $ids,
                    'id_kelas' => $first->id_kelas,
                    'nama_kelas' => $namaKelas ?: ($first->kelas?->nama_kelas ?? '—'),
                    'id_mapel' => $first->id_mapel,
                    'nama_mapel' => $first->mapel?->nama_mapel ?? '—',
                    'jam_mulai' => $first->jamPelajaran?->jam_mulai ?? null,
                    'jam_selesai' => $last->jamPelajaran?->jam_selesai ?? null,
                    'jam_ke_list' => $hours,
                    'jam_ke' => $jamRangeStr,
                    'tanggal' => $tanggal,
                    'nama_guru' => $namaGuru,
                    'status' => $matchingJurnal ? $matchingJurnal->status_approval : null,
                    'jurnal' => $matchingJurnal ? [
                        'id_kemajuan' => $matchingJurnal->id_kemajuan,
                        'tanggal' => $matchingJurnal->tanggal instanceof Carbon 
                            ? $matchingJurnal->tanggal->toDateString() 
                            : (is_string($matchingJurnal->tanggal) ? substr($matchingJurnal->tanggal, 0, 10) : $matchingJurnal->tanggal),
                        'jam_ke' => $matchingJurnal->jam_ke,
                        'materi' => $matchingJurnal->materi,
                        'jml_siswa' => $matchingJurnal->jml_siswa,
                        'absen' => $matchingJurnal->absen,
                        'keterangan' => $matchingJurnal->keterangan,
                        'hambatan' => $hambatan,
                        'pemecahan' => $pemecahan,
                        'status_approval' => $matchingJurnal->status_approval,
                        'fotos' => is_array($matchingJurnal->fotos) 
                            ? array_map(fn($f) => request()->getSchemeAndHttpHost().'/storage/'.$f, $matchingJurnal->fotos) 
                            : ($matchingJurnal->foto_1 
                                ? array_filter([
                                    $matchingJurnal->foto_1 ? request()->getSchemeAndHttpHost().'/storage/'.$matchingJurnal->foto_1 : null,
                                    $matchingJurnal->foto_2 ? request()->getSchemeAndHttpHost().'/storage/'.$matchingJurnal->foto_2 : null,
                                    $matchingJurnal->foto_3 ? request()->getSchemeAndHttpHost().'/storage/'.$matchingJurnal->foto_3 : null,
                                ]) 
                                : []
                            ),
                    ] : null
                ];
            }
        }

        return response()->json([
            'success' => true,
            'data' => [
                'tanggal' => $tanggal,
                'id_guru' => $idGuru,
                'jumlah_jadwal' => count($schedules),
                'hari_siklus' => $hariSiklus,
                'sumber' => $sumber,
                'groups' => $processedGroups,
            ],
        ]);
    }
}
