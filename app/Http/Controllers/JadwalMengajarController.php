<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\JadwalMengajarTemplate;
use App\Models\JadwalMengajarHarian;
use App\Models\JadwalSiklus;
use App\Models\JamPelajaran;
use App\Models\Guru;
use App\Models\Kelas;
use App\Models\Mapel;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Carbon\CarbonPeriod;

class JadwalMengajarController extends Controller
{
    public function index(Request $request)
    {
        $query = JadwalMengajarHarian::with(['guru', 'kelas.jurusan', 'mapel']);

        $tanggal = $request->input('tanggal', date('Y-m-d'));
        $query->where('tanggal', $tanggal);

        if ($request->filled('id_guru')) {
            $query->where('id_guru', $request->id_guru);
        }

        if ($request->filled('id_kelas')) {
            $query->where('id_kelas', $request->id_kelas);
        }

        $harianListRaw = $query->orderBy('jam_ke', 'asc')->get();

        $guruList = Guru::orderBy('nama_guru')->get();
        $kelasList = Kelas::where('status', 'aktif')->with('jurusan')->orderBy('tingkat')->orderBy('rombel')->get();

        // Get calendar cycle details for selected date
        $siklusHari = JadwalSiklus::where('tanggal', $tanggal)->first();

        // Retrieve all teacher journals for the selected date
        $jurnals = \App\Models\Kemajuan::where('tanggal', $tanggal)->get();

        // Group schedules by tanggal, id_guru, id_kelas, id_mapel
        $grouped = $harianListRaw->groupBy(function ($item) {
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

        $harianList = collect($processedJadwal);

        return view('jadwal-mengajar.index', compact('harianList', 'guruList', 'kelasList', 'tanggal', 'siklusHari'));
    }

    public function templateIndex(Request $request)
    {
        $query = JadwalMengajarTemplate::with(['guru', 'kelas.jurusan', 'mapel']);

        if ($request->filled('hari_siklus')) {
            $query->where('hari_siklus', $request->hari_siklus);
        }

        if ($request->filled('id_guru')) {
            $query->where('id_guru', $request->id_guru);
        }

        if ($request->filled('id_kelas')) {
            $query->where('id_kelas', $request->id_kelas);
        }

        $templateList = $query->orderBy('hari_siklus', 'asc')
            ->orderBy('jam_ke', 'asc')
            ->paginate(50)
            ->withQueryString();

        $guruList   = Guru::orderBy('nama_guru')->get();
        $kelasList  = Kelas::where('status', 'aktif')->with('jurusan')->orderBy('tingkat')->orderBy('rombel')->get();
        $mapelList  = Mapel::orderBy('nama_mapel')->get();

        // Max jam for pills — read from jam_pelajaran, fallback 12
        $maxJam = JamPelajaran::max('jam_ke') ?? 12;

        return view('jadwal-mengajar.template', compact('templateList', 'guruList', 'kelasList', 'mapelList', 'maxJam'));
    }

    // ─── AJAX: return occupied jam slots for a given hari_siklus + id_kelas ───
    public function getOccupiedJam(Request $request)
    {
        $hariSiklus = $request->input('hari_siklus', '');
        $idKelas    = $request->input('id_kelas', '');
        $excludeId  = $request->input('exclude_id'); // exclude current template on edit mode

        if (!$hariSiklus || !$idKelas) {
            return response()->json(['occupied' => [], 'max_jam' => JamPelajaran::max('jam_ke') ?? 12]);
        }

        $query = JadwalMengajarTemplate::with(['guru:id_guru,nama_guru', 'mapel:id_mapel,nama_mapel'])
            ->where('hari_siklus', $hariSiklus)
            ->where('id_kelas', $idKelas);

        if ($excludeId) {
            $query->where('id_template', '!=', $excludeId);
        }

        $rows     = $query->get();
        $occupied = [];

        foreach ($rows as $r) {
            $occupied[$r->jam_ke] = [
                'guru'  => $r->guru->nama_guru ?? '—',
                'mapel' => $r->mapel->nama_mapel ?? '—',
            ];
        }

        $maxJam = JamPelajaran::max('jam_ke') ?? 12;

        return response()->json([
            'occupied' => $occupied,
            'max_jam'  => max($maxJam, 12), // always show at least 12 pills
        ]);
    }

    // ─── Store: supports saving multiple jam_ke at once ───
    public function templateStore(Request $request)
    {
        $request->validate([
            'id_guru'     => 'required|integer|exists:guru,id_guru',
            'id_kelas'    => 'required|integer|exists:kelas,id_kelas',
            'id_mapel'    => 'required|integer|exists:mapel,id_mapel',
            'hari_siklus' => 'required|string|max:10',
            'jam_ke'      => 'required|array|min:1',
            'jam_ke.*'    => 'integer|min:1',
        ]);

        $hariSiklus = $request->hari_siklus;
        $errors     = [];
        $saved      = 0;

        foreach ($request->jam_ke as $jam) {
            // Check Teacher Conflict
            $teacherConflict = JadwalMengajarTemplate::where('id_guru', $request->id_guru)
                ->where('hari_siklus', $hariSiklus)
                ->where('jam_ke', $jam)
                ->first();

            if ($teacherConflict) {
                $guru  = Guru::find($request->id_guru);
                $kelas = Kelas::find($teacherConflict->id_kelas);
                $errors[] = "Jam $jam: Guru <strong>" . ($guru->nama_guru ?? '?') . "</strong> sudah mengajar kelas " . ($kelas->tingkat ?? '') . " " . ($kelas->rombel ?? '') . ".";
                continue;
            }

            // Check Class Conflict
            $classConflict = JadwalMengajarTemplate::where('id_kelas', $request->id_kelas)
                ->where('hari_siklus', $hariSiklus)
                ->where('jam_ke', $jam)
                ->first();

            if ($classConflict) {
                $mapel  = Mapel::find($classConflict->id_mapel);
                $errors[] = "Jam $jam: Kelas sudah memiliki pelajaran <strong>" . ($mapel->nama_mapel ?? '?') . "</strong>.";
                continue;
            }

            JadwalMengajarTemplate::create([
                'id_guru'     => $request->id_guru,
                'id_kelas'    => $request->id_kelas,
                'id_mapel'    => $request->id_mapel,
                'hari_siklus' => $hariSiklus,
                'jam_ke'      => $jam,
            ]);
            $saved++;
        }

        $redirect = redirect()->route('jadwal-mengajar.template', ['hari_siklus' => $hariSiklus]);

        if ($saved > 0 && !empty($errors)) {
            // Partial success
            return $redirect
                ->with('success', "$saved jam berhasil disimpan.")
                ->with('warning', 'Beberapa jam dilewati karena konflik: ' . implode(' | ', $errors));
        } elseif ($saved > 0) {
            return $redirect->with('success', "$saved jam pelajaran template berhasil disimpan pada Hari Siklus $hariSiklus.");
        } else {
            return $redirect->withInput()->with('error', 'Gagal menyimpan, semua jam mengalami konflik: ' . implode(' | ', $errors));
        }
    }

    public function templateUpdate(Request $request, $id)
    {
        $template = JadwalMengajarTemplate::findOrFail($id);

        $request->validate([
            'id_guru'     => 'required|integer|exists:guru,id_guru',
            'id_kelas'    => 'required|integer|exists:kelas,id_kelas',
            'id_mapel'    => 'required|integer|exists:mapel,id_mapel',
            'hari_siklus' => 'required|string|max:10',
            'jam_ke'      => 'required|integer|min:1',
        ]);

        $hariSiklus = $request->hari_siklus;
        $jamKe      = $request->jam_ke;

        // Check Teacher Conflict (exclude self)
        $teacherConflict = JadwalMengajarTemplate::where('id_guru', $request->id_guru)
            ->where('hari_siklus', $hariSiklus)
            ->where('jam_ke', $jamKe)
            ->where('id_template', '!=', $id)
            ->first();

        if ($teacherConflict) {
            $guru  = Guru::find($request->id_guru);
            $kelas = Kelas::find($teacherConflict->id_kelas);
            return redirect()->back()->withInput()
                ->with('error', "Guru " . ($guru->nama_guru ?? '') . " sudah mengajar kelas " . ($kelas->tingkat ?? '') . " " . ($kelas->rombel ?? '') . " pada $hariSiklus Jam Ke $jamKe.");
        }

        // Check Class Conflict (exclude self)
        $classConflict = JadwalMengajarTemplate::where('id_kelas', $request->id_kelas)
            ->where('hari_siklus', $hariSiklus)
            ->where('jam_ke', $jamKe)
            ->where('id_template', '!=', $id)
            ->first();

        if ($classConflict) {
            $kelas = Kelas::find($request->id_kelas);
            $mapel = Mapel::find($classConflict->id_mapel);
            return redirect()->back()->withInput()
                ->with('error', "Kelas " . ($kelas->tingkat ?? '') . " " . ($kelas->rombel ?? '') . " sudah memiliki pelajaran " . ($mapel->nama_mapel ?? '') . " pada $hariSiklus Jam Ke $jamKe.");
        }

        $template->update($request->only(['id_guru', 'id_kelas', 'id_mapel', 'hari_siklus', 'jam_ke']));

        return redirect()->route('jadwal-mengajar.template', ['hari_siklus' => $hariSiklus])
            ->with('success', 'Template jadwal siklus berhasil diperbarui.');
    }

    public function templateDestroy($id)
    {
        $template = JadwalMengajarTemplate::findOrFail($id);
        $hari = $template->hari_siklus;
        $template->delete();

        return redirect()->route('jadwal-mengajar.template', ['hari_siklus' => $hari])
            ->with('success', 'Template jadwal siklus berhasil dihapus.');
    }

    public function generate(Request $request)
    {
        $request->validate([
            'tanggal_mulai'   => 'nullable|date',
            'tanggal_selesai' => 'nullable|date|after_or_equal:tanggal_mulai',
        ]);

        $mulaiRaw = $request->input('tanggal_mulai');
        $selesaiRaw = $request->input('tanggal_selesai');

        if (!$mulaiRaw) {
            $start = Carbon::today();
        } else {
            $start = Carbon::parse($mulaiRaw);
        }

        // Snap start date to nearest Monday (upcoming or today)
        $dayOfWeek = $start->dayOfWeek; // 0 = Sunday, 1 = Monday, ..., 6 = Saturday
        if ($dayOfWeek === Carbon::SUNDAY) {
            $start->addDay();
        } elseif ($dayOfWeek !== Carbon::MONDAY) {
            $start->addDays(8 - $dayOfWeek);
        }

        if (!$selesaiRaw) {
            // Default to Friday of next week (Monday + 11 days)
            $end = $start->copy()->addDays(11);
        } else {
            $end = Carbon::parse($selesaiRaw);
        }

        $period = CarbonPeriod::create($start, $end);

        $generatedDays = 0;
        $totalLessons  = 0;

        foreach ($period as $date) {
            $dateStr = $date->format('Y-m-d');
            
            // Check day of week to determine default cycle values
            $dayOfWeek = $date->dayOfWeek; // 0 = Sunday, 6 = Saturday
            if ($dayOfWeek === Carbon::SATURDAY || $dayOfWeek === Carbon::SUNDAY) {
                $hariKe = 'Off';
                $siklusNum = 0;
                $status = 'Libur';
                $keterangan = 'Akhir Pekan';
            } else {
                $diffInDays = (int) round(($date->timestamp - $start->timestamp) / 86400);
                $weekNumber = (int) floor($diffInDays / 7);
                if ($weekNumber % 2 === 0) {
                    $siklusNum = 1;
                    $dayIndex = ($diffInDays % 7) + 1;
                    $hariKe = 'D' . $dayIndex;
                } else {
                    $siklusNum = 2;
                    $dayIndex = ($diffInDays % 7) + 6;
                    $hariKe = 'D' . $dayIndex;
                }
                $status = 'KBM';
                $keterangan = "KBM Siklus {$siklusNum} ({$hariKe})";
            }

            // Check if there is an existing record
            $existingSiklus = JadwalSiklus::where('tanggal', $dateStr)->first();
            if ($existingSiklus) {
                // Preserve custom holidays (if status is Libur and it's not just weekend Off)
                if ($existingSiklus->status === 'Libur' && $existingSiklus->hari_ke !== 'Off') {
                    $status = $existingSiklus->status;
                    $keterangan = $existingSiklus->keterangan;
                }
            }

            // Create or update JadwalSiklus record
            $siklus = JadwalSiklus::updateOrCreate(
                ['tanggal' => $dateStr],
                [
                    'hari_ke'    => $hariKe,
                    'siklus'     => $siklusNum,
                    'status'     => $status,
                    'keterangan' => $keterangan,
                ]
            );

            if ($siklus->status == 'Libur' || $siklus->hari_ke == 'Off') {
                JadwalMengajarHarian::where('tanggal', $dateStr)->delete();
                continue;
            }

            JadwalMengajarHarian::where('tanggal', $dateStr)->delete();

            $templates = JadwalMengajarTemplate::where('hari_siklus', $siklus->hari_ke)->get();

            foreach ($templates as $t) {
                JadwalMengajarHarian::create([
                    'tanggal'    => $dateStr,
                    'id_guru'    => $t->id_guru,
                    'id_kelas'   => $t->id_kelas,
                    'id_mapel'   => $t->id_mapel,
                    'jam_ke'     => $t->jam_ke,
                    'status'     => 'KBM',
                    'keterangan' => 'Generated dari Siklus ' . $t->hari_siklus,
                    'ruang'      => $t->ruang,
                ]);
                $totalLessons++;
            }

            $generatedDays++;
        }

        return redirect()->route('jadwal-mengajar.index', ['tanggal' => $start->format('Y-m-d')])
            ->with('success', "Berhasil men-generate jadwal mengajar harian. Total: $totalLessons jam pelajaran pada $generatedDays hari belajar aktif.");
    }

    public function clear(Request $request)
    {
        $request->validate([
            'clear_start_date' => 'required|date',
            'clear_end_date'   => 'required|date|after_or_equal:clear_start_date',
        ]);

        $deleted = JadwalMengajarHarian::whereBetween('tanggal', [
            $request->clear_start_date,
            $request->clear_end_date,
        ])->delete();

        return redirect()->route('jadwal-mengajar.index')
            ->with('success', "Berhasil menghapus $deleted data jadwal mengajar harian pada periode tersebut.");
    }

    public function getTeacherGrid(Request $request)
    {
        $idGuru = $request->input('id_guru');
        if (!$idGuru) {
            return response()->json(['success' => false, 'message' => 'Guru tidak dipilih.'], 400);
        }

        $schedules = JadwalMengajarTemplate::with(['kelas', 'mapel'])
            ->where('id_guru', $idGuru)
            ->get();

        $formatted = [];
        foreach ($schedules as $s) {
            $key = $s->hari_siklus . '_' . $s->jam_ke;
            $formatted[$key] = [
                'id_template' => $s->id_template,
                'id_kelas' => $s->id_kelas,
                'kelas_name' => ($s->kelas->tingkat ?? '') . ' ' . ($s->kelas->rombel ?? ''),
                'id_mapel' => $s->id_mapel,
                'mapel_name' => $s->mapel->kode_mapel ?? ($s->mapel->nama_mapel ?? ''),
                'ruang' => $s->ruang ?? '',
            ];
        }

        return response()->json([
            'success' => true,
            'schedules' => $formatted
        ]);
    }

    public function saveGrid(Request $request)
    {
        $request->validate([
            'id_guru' => 'required|integer|exists:guru,id_guru',
            'id_kelas' => 'required|integer|exists:kelas,id_kelas',
            'id_mapel' => 'required|integer|exists:mapel,id_mapel',
            'ruang' => 'nullable|string|max:50',
            'cells' => 'required|array|min:1',
            'cells.*' => 'string|regex:/^D\d+_\d+$/'
        ]);

        $idGuru = $request->id_guru;
        $idKelas = $request->id_kelas;
        $idMapel = $request->id_mapel;
        $ruang = $request->ruang;
        $cells = $request->cells;

        $errors = [];
        $saved = 0;

        foreach ($cells as $cell) {
            list($hariSiklus, $jamKe) = explode('_', $cell);
            $jamKe = (int)$jamKe;

            // Check Class Conflict: Does this class already have a lesson at this day/hour with another teacher?
            $classConflict = JadwalMengajarTemplate::where('id_kelas', $idKelas)
                ->where('hari_siklus', $hariSiklus)
                ->where('jam_ke', $jamKe)
                ->where('id_guru', '!=', $idGuru)
                ->first();

            if ($classConflict) {
                $otherGuru = Guru::find($classConflict->id_guru);
                $otherMapel = Mapel::find($classConflict->id_mapel);
                $errors[] = "Hari $hariSiklus Jam Ke $jamKe: Kelas sudah memiliki pelajaran " . ($otherMapel->kode_mapel ?? ($otherMapel->nama_mapel ?? '')) . " bersama Guru " . ($otherGuru->nama_guru ?? '') . ".";
                continue;
            }

            // Delete existing slot for this teacher at this cell to overwrite it
            JadwalMengajarTemplate::where('id_guru', $idGuru)
                ->where('hari_siklus', $hariSiklus)
                ->where('jam_ke', $jamKe)
                ->delete();

            // Create new slot
            JadwalMengajarTemplate::create([
                'id_guru' => $idGuru,
                'id_kelas' => $idKelas,
                'id_mapel' => $idMapel,
                'hari_siklus' => $hariSiklus,
                'jam_ke' => $jamKe,
                'ruang' => $ruang,
            ]);
            $saved++;
        }

        if (count($errors) > 0) {
            return response()->json([
                'success' => $saved > 0,
                'message' => $saved > 0 ? "Berhasil menyimpan $saved slot. Beberapa dilewati karena konflik." : "Gagal menyimpan slot.",
                'errors' => $errors
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => "Berhasil menyimpan $saved slot jadwal."
        ]);
    }

    public function deleteGrid(Request $request)
    {
        $idTemplate = $request->input('id_template');
        if ($idTemplate) {
            JadwalMengajarTemplate::where('id_template', $idTemplate)->delete();
            return response()->json(['success' => true, 'message' => 'Jadwal berhasil dihapus.']);
        }

        $idGuru = $request->input('id_guru');
        $hariSiklus = $request->input('hari_siklus');
        $jamKe = $request->input('jam_ke');

        if ($idGuru && $hariSiklus && $jamKe) {
            JadwalMengajarTemplate::where('id_guru', $idGuru)
                ->where('hari_siklus', $hariSiklus)
                ->where('jam_ke', $jamKe)
                ->delete();
            return response()->json(['success' => true, 'message' => 'Jadwal berhasil dihapus.']);
        }

        return response()->json(['success' => false, 'message' => 'Parameter tidak lengkap.'], 400);
    }

    public function clearGrid(Request $request)
    {
        $idGuru = $request->input('id_guru');
        if (!$idGuru) {
            return response()->json(['success' => false, 'message' => 'ID Guru tidak boleh kosong.'], 400);
        }

        JadwalMengajarTemplate::where('id_guru', $idGuru)->delete();
        return response()->json(['success' => true, 'message' => 'Semua template jadwal untuk guru ini berhasil dihapus.']);
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
