<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Kemajuan;
use App\Models\Guru;
use App\Models\Kelas;
use App\Models\Mapel;
use App\Models\UserSiswa;
use App\Models\JadwalMengajarHarian;
use App\Models\Presensi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class JurnalGuruController extends Controller
{
    // ─── Main Page: Date-based Schedule View ───────────────────────────────
    public function index(Request $request)
    {
        $periode = $request->input('periode', 'hari_ini');
        $tanggal = $request->input('tanggal', date('Y-m-d'));
        $tanggal_dari = $request->input('tanggal_dari', date('Y-m-d'));
        $tanggal_sampai = $request->input('tanggal_sampai', date('Y-m-d'));

        // 5-day navigator: -2 to +2 from selected date
        $dateNav = [];
        for ($i = -2; $i <= 2; $i++) {
            $d = Carbon::parse($tanggal)->addDays($i);
            $dateNav[] = $d;
        }

        // Dates that have jadwal (for dot indicator)
        $navStart = Carbon::parse($tanggal)->subDays(2)->format('Y-m-d');
        $navEnd   = Carbon::parse($tanggal)->addDays(2)->format('Y-m-d');
        $datesWithJadwal = JadwalMengajarHarian::whereBetween('tanggal', [$navStart, $navEnd])
            ->where('status', 'KBM')
            ->pluck('tanggal')
            ->map(fn($t) => is_string($t) ? $t : Carbon::parse($t)->format('Y-m-d'))
            ->unique()
            ->values()
            ->toArray();

        // Build query for schedules
        $query = JadwalMengajarHarian::with(['guru', 'kelas.jurusan', 'mapel'])
            ->where('status', 'KBM');

        if ($periode === 'minggu_ini') {
            $start = Carbon::now()->startOfWeek()->format('Y-m-d');
            $end = Carbon::now()->endOfWeek()->format('Y-m-d');
            $query->whereBetween('tanggal', [$start, $end]);
        } elseif ($periode === 'bulan_ini') {
            $start = Carbon::now()->startOfMonth()->format('Y-m-d');
            $end = Carbon::now()->endOfMonth()->format('Y-m-d');
            $query->whereBetween('tanggal', [$start, $end]);
        } elseif ($periode === 'tanggal_pilihan') {
            $start = $tanggal_dari ?: date('Y-m-d');
            $end = $tanggal_sampai ?: date('Y-m-d');
            $query->whereBetween('tanggal', [$start, $end]);
        } else {
            // Default: hari_ini
            $query->where('tanggal', $tanggal);
        }

        $jadwalListRaw = $query->orderBy('tanggal', 'asc')
            ->orderBy('jam_ke', 'asc')
            ->get();

        // Query journals for the same date range
        $jurnalsQuery = Kemajuan::query();
        if ($periode === 'minggu_ini') {
            $start = Carbon::now()->startOfWeek()->format('Y-m-d');
            $end = Carbon::now()->endOfWeek()->format('Y-m-d');
            $jurnalsQuery->whereBetween('tanggal', [$start, $end]);
        } elseif ($periode === 'bulan_ini') {
            $start = Carbon::now()->startOfMonth()->format('Y-m-d');
            $end = Carbon::now()->endOfMonth()->format('Y-m-d');
            $jurnalsQuery->whereBetween('tanggal', [$start, $end]);
        } elseif ($periode === 'tanggal_pilihan') {
            $start = $tanggal_dari ?: date('Y-m-d');
            $end = $tanggal_sampai ?: date('Y-m-d');
            $jurnalsQuery->whereBetween('tanggal', [$start, $end]);
        } else {
            $jurnalsQuery->where('tanggal', $tanggal);
        }
        $jurnals = $jurnalsQuery->get();

        // Group schedules by tanggal, id_guru, id_kelas, id_mapel
        $grouped = $jadwalListRaw->groupBy(function ($item) {
            $dateStr = is_string($item->tanggal) ? $item->tanggal : $item->tanggal->format('Y-m-d');
            return $dateStr . '_' . $item->id_guru . '_' . $item->id_kelas . '_' . $item->id_mapel;
        });

        $processedJadwal = [];
        foreach ($grouped as $key => $items) {
            $first = $items->first();
            
            // Extract and format jam range
            $hours = $items->pluck('jam_ke')->unique()->toArray();
            $jamRangeStr = $this->formatJamRange($hours);
            
            // Clone the first record and customize its jam_ke
            $virtualJadwal = clone $first;
            $virtualJadwal->mergeCasts(['jam_ke' => 'string']);
            $virtualJadwal->jam_ke = $jamRangeStr;
            
            // Match with journal
            $virtualJadwal->jurnal = $jurnals->first(function ($j) use ($first) {
                $jDate = is_string($j->tanggal) ? $j->tanggal : $j->tanggal->format('Y-m-d');
                $firstDate = is_string($first->tanggal) ? $first->tanggal : $first->tanggal->format('Y-m-d');
                return $j->id_guru  == $first->id_guru
                    && $j->id_kelas == $first->id_kelas
                    && $j->id_mapel == $first->id_mapel
                    && $jDate       == $firstDate;
            });
            
            $processedJadwal[] = $virtualJadwal;
        }

        $jadwalList = collect($processedJadwal);

        return view('jurnal-guru.index', compact(
            'jadwalList', 'tanggal', 'dateNav', 'datesWithJadwal',
            'periode', 'tanggal_dari', 'tanggal_sampai'
        ));
    }

    // ─── AJAX: students by class ────────────────────────────────────────────
    public function getStudentsByKelas($idKelas, Request $request)
    {
        $students = UserSiswa::where('id_kelas', $idKelas)
            ->where('status', 'aktif')
            ->orderBy('nama_siswa')
            ->get(['nis', 'nama_siswa']);

        $tanggal = $request->input('tanggal', date('Y-m-d'));

        // Query data presensi harian siswa pada tanggal tersebut
        $presensiMap = Presensi::whereIn('nis', $students->pluck('nis'))
            ->whereDate('tanggal', $tanggal)
            ->get()
            ->keyBy('nis');

        $result = $students->map(function ($s) use ($presensiMap) {
            $p = $presensiMap->get($s->nis);
            $dailyStatus = 'alpha'; // default alpha jika siswa tidak finger / tidak ada presensi harian
            if ($p) {
                $st = strtolower((string) $p->status);
                if (in_array($st, ['hadir', '1'])) {
                    $dailyStatus = 'hadir';
                } elseif (in_array($st, ['sakit', '2'])) {
                    $dailyStatus = 'sakit';
                } elseif (in_array($st, ['izin', 'ijin', '3'])) {
                    $dailyStatus = 'ijin';
                } elseif (in_array($st, ['alfa', 'alpha', '4', '0'])) {
                    $dailyStatus = 'alpha';
                } else {
                    $dailyStatus = 'alpha';
                }
            }
            return [
                'nis'          => $s->nis,
                'nama_siswa'   => $s->nama_siswa,
                'daily_status' => $dailyStatus,
            ];
        });

        return response()->json($result);
    }

    // ─── Store ──────────────────────────────────────────────────────────────
    public function store(Request $request)
    {
        $request->validate([
            'tanggal'    => 'required|date',
            'jam_ke'     => 'required|string|max:20',
            'id_mapel'   => 'required|integer|exists:mapel,id_mapel',
            'id_guru'    => 'required|integer|exists:guru,id_guru',
            'materi'     => 'required|string',
            'id_kelas'   => 'required|integer|exists:kelas,id_kelas',
            'jml_siswa'  => 'required|integer|min:0',
            'absen'      => 'nullable|string',
            'keterangan' => 'nullable|string',
        ]);

        $data = $request->only([
            'tanggal', 'jam_ke', 'id_mapel', 'id_guru',
            'materi', 'id_kelas', 'jml_siswa', 'absen',
        ]);

        $keterangan = $request->input('keterangan');
        $data['keterangan'] = json_encode([
            'hambatan' => $keterangan ?: null,
            'pemecahan' => null
        ]);

        // Upload new files from fotos[] array
        $uploaded = [];
        $fotosFiles = $request->file('fotos', []);
        foreach ($fotosFiles as $file) {
            if ($file && $file->isValid()) {
                $request->validate(['fotos.*' => 'image|mimes:jpg,jpeg,png,webp|max:5120']);
                $uploaded[] = $file->store('jurnal-foto', 'public');
            }
        }

        // Foto opsional — tidak ada validasi minimum

        $data['fotos'] = $uploaded;
        $data['foto_1'] = $uploaded[0] ?? null;
        $data['foto_2'] = $uploaded[1] ?? null;
        $data['foto_3'] = $uploaded[2] ?? null;

        Kemajuan::create($data);

        if ($request->wantsJson()) {
            return response()->json(['success' => true, 'message' => 'Jurnal berhasil disimpan.']);
        }

        return redirect()->route('jurnal-guru.index', ['tanggal' => $request->tanggal])
            ->with('success', 'Jurnal guru berhasil ditambahkan.');
    }

    // ─── Update ─────────────────────────────────────────────────────────────────
    public function update(Request $request, $id)
    {
        $jurnal = Kemajuan::findOrFail($id);

        if ($jurnal->status_approval === 'approved') {
            if ($request->wantsJson()) {
                return response()->json(['success' => false, 'message' => 'Jurnal yang telah disetujui tidak dapat diubah.'], 403);
            }
            return redirect()->route('jurnal-guru.index')->with('error', 'Jurnal yang telah disetujui tidak dapat diubah.');
        }

        $request->validate([
            'tanggal'    => 'required|date',
            'jam_ke'     => 'required|string|max:20',
            'id_mapel'   => 'required|integer|exists:mapel,id_mapel',
            'id_guru'    => 'required|integer|exists:guru,id_guru',
            'materi'     => 'required|string',
            'id_kelas'   => 'required|integer|exists:kelas,id_kelas',
            'jml_siswa'  => 'required|integer|min:0',
            'absen'      => 'nullable|string',
            'keterangan' => 'nullable|string',
        ]);

        $data = $request->only([
            'tanggal', 'jam_ke', 'id_mapel', 'id_guru',
            'materi', 'id_kelas', 'jml_siswa', 'absen',
        ]);

        $keterangan = $request->input('keterangan');
        $oldKeterangan = $jurnal->keterangan;
        $keteranganData = json_decode($oldKeterangan, true);
        if (is_array($keteranganData)) {
            $keteranganData['hambatan'] = $keterangan ?: null;
            $data['keterangan'] = json_encode($keteranganData);
        } else {
            $data['keterangan'] = json_encode([
                'hambatan' => $keterangan ?: null,
                'pemecahan' => null
            ]);
        }


        // Process existing photos kept by user (sent as existing_fotos[] with full URLs)
        $existingRaw = $request->input('existing_fotos', []);
        $existingCleaned = array_values(array_filter(array_map(function ($path) {
            if (empty($path)) return null;
            // Strip the asset URL prefix to get the storage-relative path
            if (preg_match('#/storage/(.+)$#', $path, $m)) {
                return $m[1];
            }
            return $path;
        }, (array) $existingRaw)));

        // Process new file uploads from fotos[] array
        $newUploads = [];
        $fotosFiles = $request->file('fotos', []);
        foreach ($fotosFiles as $file) {
            if ($file && $file->isValid()) {
                $request->validate(['fotos.*' => 'image|mimes:jpg,jpeg,png,webp|max:5120']);
                $newUploads[] = $file->store('jurnal-foto', 'public');
            }
        }

        // Merge: existing kept photos + new uploads
        $finalPhotos = array_values(array_merge($existingCleaned, $newUploads));

        // Foto opsional — tidak ada validasi minimum

        // Delete old photos that are no longer kept
        $oldPhotos = $jurnal->fotos ?: [];
        if (empty($oldPhotos)) {
            if ($jurnal->foto_1) $oldPhotos[] = $jurnal->foto_1;
            if ($jurnal->foto_2) $oldPhotos[] = $jurnal->foto_2;
            if ($jurnal->foto_3) $oldPhotos[] = $jurnal->foto_3;
        }

        $deletedPhotos = array_diff($oldPhotos, $finalPhotos);
        foreach ($deletedPhotos as $pPath) {
            if (!empty($pPath)) {
                Storage::disk('public')->delete($pPath);
            }
        }

        $data['fotos'] = $finalPhotos;
        $data['foto_1'] = $finalPhotos[0] ?? null;
        $data['foto_2'] = $finalPhotos[1] ?? null;
        $data['foto_3'] = $finalPhotos[2] ?? null;

        $jurnal->update($data);

        if ($request->wantsJson()) {
            return response()->json(['success' => true, 'message' => 'Jurnal berhasil diperbarui.']);
        }

        return redirect()->route('jurnal-guru.index', ['tanggal' => $request->tanggal])
            ->with('success', 'Jurnal guru berhasil diperbarui.');
    }

    // ─── Destroy ─────────────────────────────────────────────────────────────
    public function destroy($id)
    {
        $jurnal = Kemajuan::findOrFail($id);

        if ($jurnal->status_approval === 'approved') {
            return redirect()->route('jurnal-guru.index')->with('error', 'Jurnal yang telah disetujui tidak dapat dihapus.');
        }

        $photos = $jurnal->fotos ?: [];
        if (empty($photos)) {
            if ($jurnal->foto_1) $photos[] = $jurnal->foto_1;
            if ($jurnal->foto_2) $photos[] = $jurnal->foto_2;
            if ($jurnal->foto_3) $photos[] = $jurnal->foto_3;
        }

        foreach ($photos as $pPath) {
            if (!empty($pPath)) {
                Storage::disk('public')->delete($pPath);
            }
        }

        $jurnal->delete();

        return redirect()->route('jurnal-guru.index')->with('success', 'Jurnal guru berhasil dihapus.');
    }

    // ─── Approve / Reject ────────────────────────────────────────────────────
    public function approve($id)
    {
        $jurnal = Kemajuan::findOrFail($id);
        $jurnal->update(['status_approval' => 'approved']);

        if (request()->wantsJson()) {
            return response()->json(['success' => true, 'message' => 'Jurnal berhasil disetujui.']);
        }
        return redirect()->back()->with('success', 'Jurnal berhasil disetujui.');
    }

    public function reject($id)
    {
        $jurnal = Kemajuan::findOrFail($id);
        $jurnal->update(['status_approval' => 'rejected']);

        if (request()->wantsJson()) {
            return response()->json(['success' => true, 'message' => 'Jurnal berhasil ditolak.']);
        }
        return redirect()->back()->with('success', 'Jurnal berhasil ditolak.');
    }

    private function formatJamRange(array $hours)
    {
        if (empty($hours)) return '';
        $hours = array_map('intval', $hours);
        sort($hours);
        $ranges = [];
        $start = $hours[0];
        $end = $hours[0];
        for ($i = 1; $i < count($hours); $i++) {
            if ($hours[$i] == $end + 1) {
                $end = $hours[$i];
            } else {
                if ($start == $end) {
                    $ranges[] = $start;
                } else {
                    $ranges[] = $start . '-' . $end;
                }
                $start = $hours[$i];
                $end = $hours[$i];
            }
        }
        if ($start == $end) {
            $ranges[] = $start;
        } else {
            $ranges[] = $start . '-' . $end;
        }
        return implode(', ', $ranges);
    }
}
