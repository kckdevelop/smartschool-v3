<?php

namespace App\Http\Controllers\Ismuba;

use App\Http\Controllers\Controller;
use App\Models\Tadarus;
use App\Models\Kelas;
use App\Models\Guru;
use Illuminate\Http\Request;

class TadarusController extends Controller
{
    public function index(Request $request)
    {
        $kelasList = Kelas::where('status', 'aktif')->orderBy('tingkat')->orderBy('rombel')->get();
        $selectedKelasId = $request->input('id_kelas') ?: ($kelasList->first()?->id_kelas ?? null);

        $query = Tadarus::with(['kelas', 'guru'])
            ->orderByDesc('tanggal')
            ->orderByDesc('id_tadarus');

        if ($selectedKelasId) {
            $query->where('id_kelas', $selectedKelasId);
        }
        if ($request->filled('id_guru')) {
            $query->where('id_guru', $request->id_guru);
        }
        if ($request->filled('tanggal_dari')) {
            $query->where('tanggal', '>=', $request->tanggal_dari);
        }
        if ($request->filled('tanggal_sampai')) {
            $query->where('tanggal', '<=', $request->tanggal_sampai);
        }

        $tadarusList = $query->paginate(20)->withQueryString();

        $totalHariIni  = Tadarus::whereDate('tanggal', today())->count();
        $totalBulanIni = Tadarus::whereMonth('tanggal', now()->month)->whereYear('tanggal', now()->year)->count();
        $totalAll      = Tadarus::count();

        $guruList = Guru::where('status', 'aktif')->orderBy('nama_guru')->get();
        if ($guruList->isEmpty()) {
            $guruList = Guru::orderBy('nama_guru')->get();
        }

        // Master Al-Qur'an data
        $surahList = \App\Models\TabelAlquran::select('surat')->distinct()->orderBy('id')->pluck('surat');
        $surahAyatCounts = \App\Models\TabelAlquran::select('surat', \DB::raw('count(*) as total_ayat'))
            ->groupBy('surat')
            ->orderBy(\DB::raw('min(id)'))
            ->get()
            ->pluck('total_ayat', 'surat');

        // All tadarus records for JS timeline mapping
        $allTadarusRecords = Tadarus::orderBy('tanggal')
            ->orderBy('id_tadarus')
            ->get(['id_tadarus', 'id_kelas', 'tanggal', 'akhir_surat', 'akhir_ayat']);

        return view('ismuba.tadarus.index', compact(
            'tadarusList', 'totalHariIni', 'totalBulanIni', 'totalAll',
            'kelasList', 'guruList', 'surahList', 'surahAyatCounts', 'allTadarusRecords', 'selectedKelasId'
        ));
    }

    public function store(Request $request)
    {
        $request->validate([
            'tanggal'  => 'required|date',
            'id_kelas' => 'required|integer|exists:kelas,id_kelas',
            'surat'    => 'required|string|max:50',
            'ayat'     => 'required|integer|min:1',
            'id_guru'  => 'required|integer|exists:guru,id_guru',
        ]);

        $quranRecord = \App\Models\TabelAlquran::where('surat', $request->surat)
            ->where('ayat', $request->ayat)
            ->first();

        if (!$quranRecord) {
            return back()->withErrors(['ayat' => 'Data Al-Qur\'an tidak valid atau tidak ditemukan.'])->withInput();
        }

        // Progression validation
        $otherRecords = Tadarus::where('id_kelas', $request->id_kelas)
            ->get()
            ->map(function($r) {
                return [
                    'id_tadarus'  => $r->id_tadarus,
                    'tanggal'     => $r->tanggal->format('Y-m-d'),
                    'akhir_surat' => $r->akhir_surat,
                    'akhir_ayat'  => $r->akhir_ayat,
                ];
            })
            ->toArray();

        // Add the new record — use PHP_INT_MAX so it sorts AFTER existing same-date records
        $otherRecords[] = [
            'id_tadarus'  => PHP_INT_MAX,
            'tanggal'     => $request->tanggal,
            'akhir_surat' => $request->surat,
            'akhir_ayat'  => $request->ayat,
        ];

        // Sort by tanggal then by id_tadarus
        usort($otherRecords, function($a, $b) {
            $cmp = strcmp($a['tanggal'], $b['tanggal']);
            if ($cmp === 0) {
                return $a['id_tadarus'] <=> $b['id_tadarus'];
            }
            return $cmp;
        });

        // Validate sequence
        $currentStartId = 1;
        foreach ($otherRecords as $item) {
            $endQuran = \App\Models\TabelAlquran::where('surat', $item['akhir_surat'])
                ->where('ayat', $item['akhir_ayat'])
                ->first();

            if (!$endQuran) {
                return back()->withErrors(['ayat' => 'Data Al-Qur\'an tidak valid.'])->withInput();
            }

            if ($endQuran->id < $currentStartId) {
                $startQuran = \App\Models\TabelAlquran::find($currentStartId) ?: \App\Models\TabelAlquran::find(1);
                $formattedDate = \Carbon\Carbon::parse($item['tanggal'])->translatedFormat('d M Y');
                return back()->withErrors([
                    'ayat' => "Progress tadarus untuk tanggal {$formattedDate} tidak boleh mundur ke ayat sebelumnya. Harus lebih besar atau sama dengan QS. {$startQuran->surat}: {$startQuran->ayat}."
                ])->withInput();
            }

            $currentStartId = $endQuran->id + 1;
        }

        // Determine temporary awal_surat and awal_ayat (will be finalized by recalculateTadarusProgress)
        $lastTadarus = Tadarus::where('id_kelas', $request->id_kelas)
            ->where('tanggal', '<=', $request->tanggal)
            ->orderByDesc('tanggal')
            ->orderByDesc('id_tadarus')
            ->first();

        $awal_surat = 'Al-Fatihah';
        $awal_ayat = 1;

        if ($lastTadarus) {
            $lastQuran = \App\Models\TabelAlquran::where('surat', $lastTadarus->akhir_surat)
                ->where('ayat', $lastTadarus->akhir_ayat)
                ->first();
            if ($lastQuran) {
                $nextQuran = \App\Models\TabelAlquran::find($lastQuran->id + 1);
                if ($nextQuran) {
                    $awal_surat = $nextQuran->surat;
                    $awal_ayat = $nextQuran->ayat;
                } else {
                    $awal_surat = $lastTadarus->akhir_surat;
                    $awal_ayat = $lastTadarus->akhir_ayat;
                }
            }
        }

        Tadarus::create([
            'tanggal'     => $request->tanggal,
            'id_kelas'    => $request->id_kelas,
            'awal_surat'  => $awal_surat,
            'awal_ayat'   => $awal_ayat,
            'akhir_surat' => $request->surat,
            'akhir_ayat'  => $request->ayat,
            'id_guru'     => $request->id_guru,
        ]);

        $this->recalculateTadarusProgress($request->id_kelas);

        return redirect()->route('ismuba.tadarus.index', ['id_kelas' => $request->id_kelas])
            ->with('success', 'Data pantauan tadarus kelas berhasil disimpan.');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'tanggal'  => 'required|date',
            'id_kelas' => 'required|integer|exists:kelas,id_kelas',
            'surat'    => 'required|string|max:50',
            'ayat'     => 'required|integer|min:1',
            'id_guru'  => 'required|integer|exists:guru,id_guru',
        ]);

        $quranRecord = \App\Models\TabelAlquran::where('surat', $request->surat)
            ->where('ayat', $request->ayat)
            ->first();

        if (!$quranRecord) {
            return back()->withErrors(['ayat' => 'Data Al-Qur\'an tidak valid atau tidak ditemukan.'])->withInput();
        }

        // Progression validation
        $otherRecords = Tadarus::where('id_kelas', $request->id_kelas)
            ->where('id_tadarus', '!=', $id)
            ->get()
            ->map(function($r) {
                return [
                    'id_tadarus'  => $r->id_tadarus,
                    'tanggal'     => $r->tanggal->format('Y-m-d'),
                    'akhir_surat' => $r->akhir_surat,
                    'akhir_ayat'  => $r->akhir_ayat,
                ];
            })
            ->toArray();

        // Add updated record
        $otherRecords[] = [
            'id_tadarus'  => $id,
            'tanggal'     => $request->tanggal,
            'akhir_surat' => $request->surat,
            'akhir_ayat'  => $request->ayat,
        ];

        // Sort by tanggal then by id_tadarus
        usort($otherRecords, function($a, $b) {
            $cmp = strcmp($a['tanggal'], $b['tanggal']);
            if ($cmp === 0) {
                return $a['id_tadarus'] <=> $b['id_tadarus'];
            }
            return $cmp;
        });

        // Validate sequence
        $currentStartId = 1;
        foreach ($otherRecords as $item) {
            $endQuran = \App\Models\TabelAlquran::where('surat', $item['akhir_surat'])
                ->where('ayat', $item['akhir_ayat'])
                ->first();

            if (!$endQuran) {
                return back()->withErrors(['ayat' => 'Data Al-Qur\'an tidak valid.'])->withInput();
            }

            if ($endQuran->id < $currentStartId) {
                $startQuran = \App\Models\TabelAlquran::find($currentStartId) ?: \App\Models\TabelAlquran::find(1);
                $formattedDate = \Carbon\Carbon::parse($item['tanggal'])->translatedFormat('d M Y');
                return back()->withErrors([
                    'ayat' => "Progress tadarus untuk tanggal {$formattedDate} tidak boleh mundur ke ayat sebelumnya. Harus lebih besar atau sama dengan QS. {$startQuran->surat}: {$startQuran->ayat}."
                ])->withInput();
            }

            $currentStartId = $endQuran->id + 1;
        }

        $tadarus = Tadarus::findOrFail($id);
        $oldKelas = $tadarus->id_kelas;

        $tadarus->update([
            'tanggal'     => $request->tanggal,
            'id_kelas'    => $request->id_kelas,
            'akhir_surat' => $request->surat,
            'akhir_ayat'  => $request->ayat,
            'id_guru'     => $request->id_guru,
        ]);

        $this->recalculateTadarusProgress($request->id_kelas);
        if ($oldKelas != $request->id_kelas) {
            $this->recalculateTadarusProgress($oldKelas);
        }

        return redirect()->route('ismuba.tadarus.index', ['id_kelas' => $request->id_kelas])
            ->with('success', 'Data pantauan tadarus berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $tadarus = Tadarus::findOrFail($id);
        $idKelas = $tadarus->id_kelas;
        $tadarus->delete();

        $this->recalculateTadarusProgress($idKelas);

        return redirect()->route('ismuba.tadarus.index', ['id_kelas' => $idKelas])
            ->with('success', 'Data pantauan tadarus berhasil dihapus.');
    }

    protected function recalculateTadarusProgress($id_kelas)
    {
        $records = Tadarus::where('id_kelas', $id_kelas)
            ->orderBy('tanggal')
            ->orderBy('id_tadarus')
            ->get();

        $currentStartId = 1;

        foreach ($records as $record) {
            $startQuran = \App\Models\TabelAlquran::find($currentStartId);
            if ($startQuran) {
                $record->awal_surat = $startQuran->surat;
                $record->awal_ayat = $startQuran->ayat;
            } else {
                $record->awal_surat = 'Al-Fatihah';
                $record->awal_ayat = 1;
            }

            $endQuran = \App\Models\TabelAlquran::where('surat', $record->akhir_surat)
                ->where('ayat', $record->akhir_ayat)
                ->first();

            if ($endQuran) {
                $currentStartId = $endQuran->id + 1;
            }

            $record->save();
        }
    }
}
