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
     * Aturan range halaman global (1–55) per jilid Iqro.
     * Jilid ditentukan otomatis dari nomor halaman.
     * Jilid 1 : hal. 1  – 16
     * Jilid 2 : hal. 17 – 24
     * Jilid 3 : hal. 25 – 32
     * Jilid 4 : hal. 33 – 40
     * Jilid 5 : hal. 41 – 48
     * Jilid 6 : hal. 49 – 55
     */
    protected array $jilidRanges = [
        1 => ['min' => 1,  'max' => 16],
        2 => ['min' => 17, 'max' => 24],
        3 => ['min' => 25, 'max' => 32],
        4 => ['min' => 33, 'max' => 40],
        5 => ['min' => 41, 'max' => 48],
        6 => ['min' => 49, 'max' => 55],
    ];

    /** Tentukan jilid berdasarkan nomor halaman global */
    protected function jilidFromHalaman(int $halaman): int
    {
        foreach ($this->jilidRanges as $jilid => $range) {
            if ($halaman >= $range['min'] && $halaman <= $range['max']) {
                return $jilid;
            }
        }
        return 6; // default jilid terakhir
    }

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

        $guruIsmuba  = Guru::where('status', 'aktif')->orderBy('nama_guru')->get();
        $siswaDaftar = UserSiswa::where('status', 'aktif')->with('kelas')->orderBy('nama_siswa')->get(['nis', 'nama_siswa', 'id_kelas']);

        // Master BTAQ: Al-Qur'an
        $surahList      = \App\Models\TabelAlquran::select('surat')->distinct()->orderBy('id')->pluck('surat');
        $surahAyatCounts = \App\Models\TabelAlquran::select('surat', \DB::raw('count(*) as total_ayat'))
            ->groupBy('surat')
            ->orderBy(\DB::raw('min(id)'))
            ->get()
            ->pluck('total_ayat', 'surat');

        // Master BTAQ: Iqro — halaman global 1–55, 10 baris per halaman
        $jilidRanges = $this->jilidRanges; // pass ke view
        $maxHalamanIqro = 55;

        // 10 baris per halaman (1–10) untuk setiap halaman 1–55
        $iqroBarisByHalaman = [];
        for ($h = 1; $h <= $maxHalamanIqro; $h++) {
            $iqroBarisByHalaman[$h] = range(1, 10);
        }

        // Fetch latest progress per student
        $latestBtaqPerSiswa = Btaq::with(['iqroAwal', 'alquranAwal'])
            ->whereIn('nis', $siswaDaftar->pluck('nis'))
            ->get()
            ->groupBy('nis')
            ->map(fn($entries) => $entries->sortByDesc('tanggal')->first());

        $latestBtaqMap = $latestBtaqPerSiswa->map(function($btaq) {
            return [
                'id_btaq' => $btaq->id_btaq,
                'level'   => $btaq->level,
                'is_iqro' => (stripos($btaq->level, 'Iqra') !== false || stripos($btaq->level, 'Iqro') !== false),
                'jilid'   => $btaq->iqroAwal?->jilid ?? null,
                'halaman' => $btaq->iqroAwal?->halaman ?? null,
                'baris'   => $btaq->iqroAwal?->baris ?? null,
                'surat'   => $btaq->alquranAwal?->surat ?? null,
                'ayat'    => $btaq->alquranAwal?->ayat ?? null,
            ];
        });

        return view('ismuba.btaq.index', compact(
            'totalHariIni', 'totalBulanIni', 'totalAll',
            'kelasList', 'selectedKelasId', 'calendarDates', 'siswaList', 'btaqEntries',
            'guruIsmuba', 'siswaDaftar', 'surahList', 'surahAyatCounts',
            'jilidRanges', 'maxHalamanIqro', 'iqroBarisByHalaman', 'latestBtaqMap'
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

        $level  = $request->input('level');
        $isIqro = (stripos($level, 'Iqra') !== false || stripos($level, 'Iqro') !== false);

        if ($isIqro) {
            $rules['halaman'] = 'required|integer|min:1|max:55';
            $rules['baris']   = 'required|integer|min:1|max:10';
        } else {
            $rules['surat'] = 'required|string';
            $rules['ayat']  = 'required|integer';
        }

        $request->validate($rules);

        $recordId = null;

        if ($isIqro) {
            $halamanInt = (int) $request->halaman;
            $barisInt   = (int) $request->baris;
            $jilidInt   = $this->jilidFromHalaman($halamanInt);

            $iqroRecord = \App\Models\TabelIqro::firstOrCreate([
                'jilid'   => $jilidInt,
                'halaman' => $halamanInt,
                'baris'   => $barisInt,
            ]);

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
                $lastHalaman = (int) $lastBtaq->iqroAwal->halaman;
                $lastBaris   = (int) $lastBtaq->iqroAwal->baris;
                // Halaman baru harus > halaman lama, atau halaman sama & baris lebih besar
                if ($halamanInt < $lastHalaman) {
                    return back()->withErrors(['halaman' => "Halaman tidak boleh mundur dari halaman {$lastHalaman}."])->withInput();
                }
                if ($halamanInt === $lastHalaman && $barisInt <= $lastBaris) {
                    return back()->withErrors(['baris' => "Baris harus lebih besar dari baris sebelumnya (Baris {$lastBaris})."])->withInput();
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
        $btaqData['awal']  = $recordId;
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

        $level  = $request->input('level');
        $isIqro = (stripos($level, 'Iqra') !== false || stripos($level, 'Iqro') !== false);

        if ($isIqro) {
            $rules['halaman'] = 'required|integer|min:1|max:55';
            $rules['baris']   = 'required|integer|min:1|max:10';
        } else {
            $rules['surat'] = 'required|string';
            $rules['ayat']  = 'required|integer';
        }

        $request->validate($rules);

        $recordId = null;

        if ($isIqro) {
            $halamanInt = (int) $request->halaman;
            $barisInt   = (int) $request->baris;
            $jilidInt   = $this->jilidFromHalaman($halamanInt);

            $iqroRecord = \App\Models\TabelIqro::firstOrCreate([
                'jilid'   => $jilidInt,
                'halaman' => $halamanInt,
                'baris'   => $barisInt,
            ]);

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

        // Progression validation (excluding current record)
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
                $lastHalaman = (int) $lastBtaq->iqroAwal->halaman;
                $lastBaris   = (int) $lastBtaq->iqroAwal->baris;
                if ($halamanInt < $lastHalaman) {
                    return back()->withErrors(['halaman' => "Halaman tidak boleh mundur dari halaman {$lastHalaman}."])->withInput();
                }
                if ($halamanInt === $lastHalaman && $barisInt <= $lastBaris) {
                    return back()->withErrors(['baris' => "Baris harus lebih besar dari baris sebelumnya (Baris {$lastBaris})."])->withInput();
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
        $btaqData['awal']  = $recordId;
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
