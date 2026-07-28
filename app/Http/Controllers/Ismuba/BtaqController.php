<?php

namespace App\Http\Controllers\Ismuba;

use App\Http\Controllers\Controller;
use App\Models\Btaq;
use App\Models\Kelas;
use App\Models\Guru;
use App\Models\UserSiswa;
use Illuminate\Http\Request;
use Carbon\Carbon;

class BtaqController extends Controller
{
    /**
     * Aturan range halaman yang valid untuk setiap jilid Iqro.
     * Jilid 1 : hal. 1 – 10
     * Jilid 2 : hal. 11 – 16
     * Jilid 3 : hal. 14 – 22
     * Jilid 4 : hal. 23 – 31
     * Jilid 5 : hal. 32 – 41
     * Jilid 6 : hal. 42 – 55
     */
    protected array $iqroJilidRules = [
        1 => ['min' => 1,  'max' => 10],
        2 => ['min' => 11, 'max' => 16],
        3 => ['min' => 14, 'max' => 22],
        4 => ['min' => 23, 'max' => 31],
        5 => ['min' => 32, 'max' => 41],
        6 => ['min' => 42, 'max' => 55],
    ];

    public function index(Request $request)
    {
        $kelasList = Kelas::where('status', 'aktif')->orderBy('tingkat')->orderBy('rombel')->get();
        $selectedKelasId = $request->input('id_kelas') ?: ($kelasList->first()?->id_kelas ?? null);

        // Kalender: 5 hari ke belakang s/d hari ini (6 kolom)
        $today = Carbon::today();
        $calendarDates = [];
        for ($i = -5; $i <= 0; $i++) {
            $calendarDates[] = $today->copy()->addDays($i)->format('Y-m-d');
        }

        $siswaList  = collect();
        $btaqEntries = collect();

        if ($selectedKelasId) {
            // Fetch students for the selected class
            $siswaQuery = UserSiswa::where('id_kelas', $selectedKelasId)
                ->where('status', 'aktif');
            if ($request->filled('search')) {
                $search = $request->search;
                $siswaQuery->where(function($q) use ($search) {
                    $q->where('nama_siswa', 'like', "%{$search}%")
                      ->orWhere('nis', 'like', "%{$search}%");
                });
            }
            $siswaList = $siswaQuery->orderBy('nama_siswa')->get();

            // Load BTAQ entries for students × kalender dates matrix
            if ($siswaList->count() > 0) {
                $btaqEntries = Btaq::with(['guru', 'iqroAwal', 'iqroAkhir', 'alquranAwal', 'alquranAkhir'])
                    ->where('id_kelas', $selectedKelasId)
                    ->whereIn('tanggal', $calendarDates)
                    ->whereIn('nis', $siswaList->pluck('nis'))
                    ->get()
                    ->groupBy(['nis', fn($r) => Carbon::parse($r->tanggal)->format('Y-m-d')]);
            }
        }

        // Stats
        $totalHariIni  = Btaq::whereDate('tanggal', today())->count();
        $totalBulanIni = Btaq::whereMonth('tanggal', now()->month)->whereYear('tanggal', now()->year)->count();
        $totalAll      = Btaq::count();

        $guruIsmuba   = Guru::where('status', 'aktif')->orderBy('nama_guru')->get();
        $siswaDaftar  = UserSiswa::where('status', 'aktif')->with('kelas')->orderBy('nama_siswa')->get(['nis', 'nama_siswa', 'id_kelas']);

        // Master BTAQ data
        $surahList = \App\Models\TabelAlquran::select('surat')->distinct()->orderBy('id')->pluck('surat');
        $iqroJilids = \App\Models\TabelIqro::select('jilid')->distinct()->orderBy('jilid')->pluck('jilid');
        $iqroHalamans = \App\Models\TabelIqro::select('halaman')->distinct()->orderBy('halaman')->pluck('halaman');
        $surahAyatCounts = \App\Models\TabelAlquran::select('surat', \DB::raw('count(*) as total_ayat'))
            ->groupBy('surat')
            ->orderBy(\DB::raw('min(id)'))
            ->get()
            ->pluck('total_ayat', 'surat');

        // Fetch latest progress per student
        $latestBtaqPerSiswa = Btaq::with(['iqroAwal', 'alquranAwal'])
            ->whereIn('nis', $siswaDaftar->pluck('nis'))
            ->get()
            ->groupBy('nis')
            ->map(function($entries) {
                return $entries->sortByDesc('tanggal')->first();
            });

        $latestBtaqMap = $latestBtaqPerSiswa->map(function($btaq) {
            return [
                'level' => $btaq->level,
                'is_iqro' => (stripos($btaq->level, 'Iqra') !== false || stripos($btaq->level, 'Iqro') !== false),
                'jilid' => $btaq->iqroAwal?->jilid ?? null,
                'halaman' => $btaq->iqroAwal?->halaman ?? null,
                'surat' => $btaq->alquranAwal?->surat ?? null,
                'ayat' => $btaq->alquranAwal?->ayat ?? null,
            ];
        });

        return view('ismuba.btaq.index', compact(
            'totalHariIni', 'totalBulanIni', 'totalAll',
            'kelasList', 'selectedKelasId', 'calendarDates', 'siswaList', 'btaqEntries',
            'guruIsmuba', 'siswaDaftar', 'surahList', 'iqroJilids', 'iqroHalamans', 'surahAyatCounts', 'latestBtaqMap'
        ));
    }

    public function store(Request $request)
    {
        $rules = [
            'tanggal'  => 'required|date',
            'nis'      => 'required|integer|exists:user_siswa,nis',
            'id_kelas' => 'required|integer|exists:kelas,id_kelas',
            'level'    => 'required|string|max:15',
            'id_guru'  => 'required|integer|exists:guru,id_guru',
        ];

        $level = $request->input('level');
        $isIqro = (stripos($level, 'Iqra') !== false || stripos($level, 'Iqro') !== false);

        if ($isIqro) {
            $rules['jilid'] = 'required|integer';
            $rules['halaman'] = 'required|integer';
        } else {
            $rules['surat'] = 'required|string';
            $rules['ayat'] = 'required|integer';
        }

        $request->validate($rules);

        $recordId = null;

        if ($isIqro) {
            $iqroRecord = \App\Models\TabelIqro::where('jilid', $request->jilid)
                ->where('halaman', $request->halaman)
                ->first();

            if (!$iqroRecord) {
                return back()->withErrors(['halaman' => 'Data Iqro tidak valid atau tidak ditemukan.'])->withInput();
            }

            // Validasi: halaman harus sesuai range jilid
            $jilidInt = (int) $request->jilid;
            if (isset($this->iqroJilidRules[$jilidInt])) {
                $rule = $this->iqroJilidRules[$jilidInt];
                $halamanInt = (int) $request->halaman;
                if ($halamanInt < $rule['min'] || $halamanInt > $rule['max']) {
                    return back()->withErrors([
                        'halaman' => "Jilid {$jilidInt} hanya memiliki halaman {$rule['min']}–{$rule['max']}. Halaman {$halamanInt} tidak valid."
                    ])->withInput();
                }
            }

            $recordId = $iqroRecord->id;
        } else {
            $quranRecord = \App\Models\TabelAlquran::where('surat', $request->surat)
                ->where('ayat', $request->ayat)
                ->first();

            if (!$quranRecord) {
                return back()->withErrors(['ayat' => 'Data Al-Qur\'an tidak valid atau tidak ditemukan.'])->withInput();
            }

            $recordId = $quranRecord->id;
        }

        // Progression validation
        $lastBtaq = Btaq::with(['iqroAwal', 'alquranAwal'])
            ->where('nis', $request->nis)
            ->where('tanggal', '<=', $request->tanggal)
            ->orderByDesc('tanggal')
            ->orderByDesc('id_btaq')
            ->first();

        if ($lastBtaq) {
            $lastIsIqro = (stripos($lastBtaq->level, 'Iqra') !== false || stripos($lastBtaq->level, 'Iqro') !== false);
            
            if ($isIqro && $lastIsIqro && $lastBtaq->iqroAwal) {
                if ($request->jilid < $lastBtaq->iqroAwal->jilid) {
                    return back()->withErrors(['jilid' => 'Jilid tidak boleh lebih kecil dari jilid sebelumnya (Jilid ' . $lastBtaq->iqroAwal->jilid . ').'])->withInput();
                }
                if ($request->jilid == $lastBtaq->iqroAwal->jilid && $request->halaman <= $lastBtaq->iqroAwal->halaman) {
                    return back()->withErrors(['halaman' => 'Halaman harus lebih besar dari halaman sebelumnya (Hal. ' . $lastBtaq->iqroAwal->halaman . ').'])->withInput();
                }
            } elseif (!$isIqro && !$lastIsIqro && $lastBtaq->alquranAwal) {
                if ($recordId <= $lastBtaq->awal) {
                    return back()->withErrors(['ayat' => 'Progress surat/ayat harus lebih besar dari sebelumnya (QS. ' . $lastBtaq->alquranAwal->surat . ': ' . $lastBtaq->alquranAwal->ayat . ').'])->withInput();
                }
            } elseif ($isIqro && !$lastIsIqro) {
                return back()->withErrors(['level' => 'Siswa sudah mencapai tingkat Al-Qur\'an, tidak bisa kembali ke Iqro.'])->withInput();
            }
        }

        $btaqData = $request->only(['tanggal', 'nis', 'id_kelas', 'level', 'id_guru']);
        $btaqData['awal'] = $recordId;
        $btaqData['akhir'] = $recordId;

        Btaq::create($btaqData);

        return redirect()->route('ismuba.btaq.index', ['id_kelas' => $request->id_kelas])
            ->with('success', 'Data pantauan BTAQ berhasil disimpan.');
    }

    public function update(Request $request, $id)
    {
        $rules = [
            'tanggal'  => 'required|date',
            'nis'      => 'required|integer|exists:user_siswa,nis',
            'id_kelas' => 'required|integer|exists:kelas,id_kelas',
            'level'    => 'required|string|max:15',
            'id_guru'  => 'required|integer|exists:guru,id_guru',
        ];

        $level = $request->input('level');
        $isIqro = (stripos($level, 'Iqra') !== false || stripos($level, 'Iqro') !== false);

        if ($isIqro) {
            $rules['jilid'] = 'required|integer';
            $rules['halaman'] = 'required|integer';
        } else {
            $rules['surat'] = 'required|string';
            $rules['ayat'] = 'required|integer';
        }

        $request->validate($rules);

        $recordId = null;

        if ($isIqro) {
            $iqroRecord = \App\Models\TabelIqro::where('jilid', $request->jilid)
                ->where('halaman', $request->halaman)
                ->first();

            if (!$iqroRecord) {
                return back()->withErrors(['halaman' => 'Data Iqro tidak valid atau tidak ditemukan.'])->withInput();
            }

            // Validasi: halaman harus sesuai range jilid
            $jilidInt = (int) $request->jilid;
            if (isset($this->iqroJilidRules[$jilidInt])) {
                $rule = $this->iqroJilidRules[$jilidInt];
                $halamanInt = (int) $request->halaman;
                if ($halamanInt < $rule['min'] || $halamanInt > $rule['max']) {
                    return back()->withErrors([
                        'halaman' => "Jilid {$jilidInt} hanya memiliki halaman {$rule['min']}–{$rule['max']}. Halaman {$halamanInt} tidak valid."
                    ])->withInput();
                }
            }

            $recordId = $iqroRecord->id;
        } else {
            $quranRecord = \App\Models\TabelAlquran::where('surat', $request->surat)
                ->where('ayat', $request->ayat)
                ->first();

            if (!$quranRecord) {
                return back()->withErrors(['ayat' => 'Data Al-Qur\'an tidak valid atau tidak ditemukan.'])->withInput();
            }

            $recordId = $quranRecord->id;
        }

        // Progression validation (excluding the current record)
        $lastBtaq = Btaq::with(['iqroAwal', 'alquranAwal'])
            ->where('nis', $request->nis)
            ->where('tanggal', '<=', $request->tanggal)
            ->where('id_btaq', '!=', $id)
            ->orderByDesc('tanggal')
            ->orderByDesc('id_btaq')
            ->first();

        if ($lastBtaq) {
            $lastIsIqro = (stripos($lastBtaq->level, 'Iqra') !== false || stripos($lastBtaq->level, 'Iqro') !== false);
            
            if ($isIqro && $lastIsIqro && $lastBtaq->iqroAwal) {
                if ($request->jilid < $lastBtaq->iqroAwal->jilid) {
                    return back()->withErrors(['jilid' => 'Jilid tidak boleh lebih kecil dari jilid sebelumnya (Jilid ' . $lastBtaq->iqroAwal->jilid . ').'])->withInput();
                }
                if ($request->jilid == $lastBtaq->iqroAwal->jilid && $request->halaman <= $lastBtaq->iqroAwal->halaman) {
                    return back()->withErrors(['halaman' => 'Halaman harus lebih besar dari halaman sebelumnya (Hal. ' . $lastBtaq->iqroAwal->halaman . ').'])->withInput();
                }
            } elseif (!$isIqro && !$lastIsIqro && $lastBtaq->alquranAwal) {
                if ($recordId <= $lastBtaq->awal) {
                    return back()->withErrors(['ayat' => 'Progress surat/ayat harus lebih besar dari sebelumnya (QS. ' . $lastBtaq->alquranAwal->surat . ': ' . $lastBtaq->alquranAwal->ayat . ').'])->withInput();
                }
            } elseif ($isIqro && !$lastIsIqro) {
                return back()->withErrors(['level' => 'Siswa sudah mencapai tingkat Al-Qur\'an, tidak bisa kembali ke Iqro.'])->withInput();
            }
        }

        $btaq = Btaq::findOrFail($id);
        $btaqData = $request->only(['tanggal', 'nis', 'id_kelas', 'level', 'id_guru']);
        $btaqData['awal'] = $recordId;
        $btaqData['akhir'] = $recordId;

        $btaq->update($btaqData);

        return redirect()->route('ismuba.btaq.index', ['id_kelas' => $request->id_kelas])
            ->with('success', 'Data pantauan BTAQ berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $btaq = Btaq::findOrFail($id);
        $idKelas = $btaq->id_kelas;
        $btaq->delete();

        return redirect()->route('ismuba.btaq.index', ['id_kelas' => $idKelas])
            ->with('success', 'Data pantauan BTAQ berhasil dihapus.');
    }
}
