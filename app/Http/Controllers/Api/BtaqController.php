<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Btaq;
use App\Models\UserSiswa;
use App\Models\TabelIqro;
use App\Models\TabelAlquran;
use App\Models\Guru;
use Illuminate\Http\Request;
use Carbon\Carbon;

class BtaqController extends Controller
{
    /** Aturan jilid berdasarkan halaman global 1–55 */
    protected array $jilidRanges = [
        1 => ['min' => 1,  'max' => 16],
        2 => ['min' => 17, 'max' => 24],
        3 => ['min' => 25, 'max' => 32],
        4 => ['min' => 33, 'max' => 40],
        5 => ['min' => 41, 'max' => 48],
        6 => ['min' => 49, 'max' => 55],
    ];

    protected function jilidFromHalaman(int $halaman): int
    {
        foreach ($this->jilidRanges as $jilid => $range) {
            if ($halaman >= $range['min'] && $halaman <= $range['max']) return $jilid;
        }
        return 6;
    }

    public function index(Request $request)
    {
        $query = Btaq::with(['guru', 'siswa.kelas', 'iqroAwal', 'iqroAkhir', 'alquranAwal', 'alquranAkhir']);

        if ($request->filled('nis')) {
            $query->where('nis', $request->nis);
        }

        if ($request->filled('id_kelas')) {
            $query->where('id_kelas', $request->id_kelas);
        }

        if ($request->filled('tanggal')) {
            $query->whereDate('tanggal', $request->tanggal);
        }

        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereBetween('tanggal', [$request->start_date, $request->end_date]);
        }

        $perPage = $request->get('per_page', 15);
        $data = $query->orderByDesc('tanggal')->paginate($perPage);

        return response()->json([
            'success' => true,
            'data' => $data,
        ]);
    }

    public function masterData()
    {
        $surahList  = TabelAlquran::select('surat')->distinct()->orderBy('id')->pluck('surat');
        // Halaman Iqro: global 1–55, jilid otomatis dari range
        $iqroHalamans = TabelIqro::select('halaman')->distinct()->orderBy('halaman')->pluck('halaman');
        $iqroBarisPerHalaman = [];
        TabelIqro::select('halaman', 'baris')->distinct()->orderBy('halaman')->orderBy('baris')
            ->get()->groupBy('halaman')
            ->each(function ($rows, $halaman) use (&$iqroBarisPerHalaman) {
                $iqroBarisPerHalaman[$halaman] = $rows->pluck('baris')->sort()->values()->toArray();
            });

        return response()->json([
            'success' => true,
            'data' => [
                'surah_list'           => $surahList,
                'iqro_halamans'        => $iqroHalamans,
                'iqro_baris_per_halaman' => $iqroBarisPerHalaman,
                'jilid_ranges'         => $this->jilidRanges,
            ]
        ]);
    }

    public function byNis($nis)
    {
        $query = Btaq::with(['guru', 'iqroAwal', 'iqroAkhir', 'alquranAwal', 'alquranAkhir'])
            ->where('nis', $nis)
            ->orderByDesc('tanggal');

        $perPage = request()->get('per_page', 15);
        $data = $query->paginate($perPage);

        return response()->json([
            'success' => true,
            'data' => $data,
        ]);
    }

    public function store(Request $request)
    {
        $rules = [
            'tanggal'  => 'required|date',
            'nis'      => 'required|integer|exists:user_siswa,nis',
            'id_kelas' => 'nullable|integer|exists:kelas,id_kelas',
            'level'    => 'required|string|max:15',
            'id_guru'  => 'nullable|integer|exists:guru,id_guru',
        ];

        $level = $request->input('level');
        $isIqro = (stripos($level, 'Iqra') !== false || stripos($level, 'Iqro') !== false);

        if ($isIqro) {
            $rules['halaman'] = 'required|integer|min:1|max:55';
            $rules['baris']   = 'required|integer|min:1|max:15';
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

            $iqroRecord = TabelIqro::firstOrCreate([
                'jilid'   => $jilidInt,
                'halaman' => $halamanInt,
                'baris'   => $barisInt,
            ]);

            $recordId = $iqroRecord->id;
        } else {
            $quranRecord = TabelAlquran::where('surat', $request->surat)
                ->where('ayat', $request->ayat)
                ->first();

            if (!$quranRecord) {
                return response()->json([
                    'success' => false,
                    'message' => 'Data Al-Qur\'an tidak valid atau tidak ditemukan.'
                ], 422);
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
                if ($halamanInt < $lastHalaman) {
                    return response()->json(['success' => false, 'message' => "Halaman tidak boleh mundur dari halaman {$lastHalaman}."], 422);
                }
                if ($halamanInt === $lastHalaman && $barisInt <= $lastBaris) {
                    return response()->json(['success' => false, 'message' => "Baris harus lebih besar dari baris sebelumnya (Baris {$lastBaris})."], 422);
                }
            } elseif (!$isIqro && !$lastIsIqro && $lastBtaq->alquranAwal) {
                if ($recordId < $lastBtaq->awal) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Progress surat/ayat tidak boleh lebih kecil dari sebelumnya (QS. ' . $lastBtaq->alquranAwal->surat . ': ' . $lastBtaq->alquranAwal->ayat . ').'
                    ], 422);
                }
            } elseif ($isIqro && !$lastIsIqro) {
                return response()->json(['success' => false, 'message' => 'Siswa sudah mencapai tingkat Al-Qur\'an, tidak bisa kembali ke Iqro.'], 422);
            }
        }

        // Auto resolve id_kelas
        $idKelas = $request->id_kelas;
        if (!$idKelas) {
            $siswa = UserSiswa::where('nis', $request->nis)->first();
            $idKelas = $siswa ? $siswa->id_kelas : null;
        }
        if (!$idKelas) {
            return response()->json([
                'success' => false,
                'message' => 'Kelas siswa tidak ditemukan.'
            ], 422);
        }

        // Auto resolve id_guru
        $idGuru = $request->id_guru;
        if (!$idGuru) {
            $user = $request->user();
            if ($user instanceof Guru) {
                $idGuru = $user->id_guru;
            }
        }
        if (!$idGuru) {
            $firstGuru = Guru::where('status', 'aktif')->first();
            $idGuru = $firstGuru ? $firstGuru->id_guru : null;
        }
        if (!$idGuru) {
            return response()->json([
                'success' => false,
                'message' => 'Guru pembimbing tidak ditemukan.'
            ], 422);
        }

        $btaqData = [
            'tanggal' => $request->tanggal,
            'nis' => $request->nis,
            'id_kelas' => $idKelas,
            'level' => $request->level,
            'id_guru' => $idGuru,
            'awal' => $recordId,
            'akhir' => $recordId
        ];

        $btaq = Btaq::create($btaqData);

        return response()->json([
            'success' => true,
            'message' => 'Data BTAQ berhasil ditambahkan.',
            'data' => $btaq->load(['guru', 'siswa.kelas', 'iqroAwal', 'iqroAkhir', 'alquranAwal', 'alquranAkhir']),
        ], 201);
    }

    public function show($id)
    {
        $btaq = Btaq::with(['guru', 'siswa.kelas', 'iqroAwal', 'iqroAkhir', 'alquranAwal', 'alquranAkhir'])->findOrFail($id);

        return response()->json([
            'success' => true,
            'data' => $btaq,
        ]);
    }

    public function update(Request $request, $id)
    {
        $btaq = Btaq::findOrFail($id);

        $user = $request->user();
        if ($user instanceof Guru && $btaq->id_guru != $user->id_guru) {
            return response()->json([
                'success' => false,
                'message' => 'Anda tidak memiliki hak untuk memperbarui data BTAQ ini.'
            ], 403);
        }

        $rules = [
            'tanggal'  => 'required|date',
            'nis'      => 'required|integer|exists:user_siswa,nis',
            'id_kelas' => 'nullable|integer|exists:kelas,id_kelas',
            'level'    => 'required|string|max:15',
            'id_guru'  => 'nullable|integer|exists:guru,id_guru',
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
            $iqroRecord = TabelIqro::firstOrCreate([
                'jilid' => (int) $request->jilid,
                'halaman' => (int) $request->halaman,
            ]);

            $recordId = $iqroRecord->id;
        } else {
            $quranRecord = TabelAlquran::where('surat', $request->surat)
                ->where('ayat', $request->ayat)
                ->first();

            if (!$quranRecord) {
                return response()->json([
                    'success' => false,
                    'message' => 'Data Al-Qur\'an tidak valid atau tidak ditemukan.'
                ], 422);
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
                    return response()->json([
                        'success' => false,
                        'message' => 'Jilid tidak boleh lebih kecil dari jilid sebelumnya (Jilid ' . $lastBtaq->iqroAwal->jilid . ').'
                    ], 422);
                }
                if ($request->jilid == $lastBtaq->iqroAwal->jilid && $request->halaman < $lastBtaq->iqroAwal->halaman) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Halaman tidak boleh lebih kecil dari halaman sebelumnya (Hal. ' . $lastBtaq->iqroAwal->halaman . ').'
                    ], 422);
                }
            } elseif (!$isIqro && !$lastIsIqro && $lastBtaq->alquranAwal) {
                if ($recordId < $lastBtaq->awal) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Progress surat/ayat tidak boleh lebih kecil dari sebelumnya (QS. ' . $lastBtaq->alquranAwal->surat . ': ' . $lastBtaq->alquranAwal->ayat . ').'
                    ], 422);
                }
            } elseif ($isIqro && !$lastIsIqro) {
                return response()->json([
                    'success' => false,
                    'message' => 'Siswa sudah mencapai tingkat Al-Qur\'an, tidak bisa kembali ke Iqro.'
                ], 422);
            }
        }

        // Auto resolve id_kelas
        $idKelas = $request->id_kelas;
        if (!$idKelas) {
            $siswa = UserSiswa::where('nis', $request->nis)->first();
            $idKelas = $siswa ? $siswa->id_kelas : null;
        }
        if (!$idKelas) {
            return response()->json([
                'success' => false,
                'message' => 'Kelas siswa tidak ditemukan.'
            ], 422);
        }

        // Auto resolve id_guru
        $idGuru = $request->id_guru;
        if (!$idGuru) {
            $user = $request->user();
            if ($user instanceof Guru) {
                $idGuru = $user->id_guru;
            }
        }
        if (!$idGuru) {
            $firstGuru = Guru::where('status', 'aktif')->first();
            $idGuru = $firstGuru ? $firstGuru->id_guru : null;
        }
        if (!$idGuru) {
            return response()->json([
                'success' => false,
                'message' => 'Guru pembimbing tidak ditemukan.'
            ], 422);
        }

        $btaqData = [
            'tanggal' => $request->tanggal,
            'nis' => $request->nis,
            'id_kelas' => $idKelas,
            'level' => $request->level,
            'id_guru' => $idGuru,
            'awal' => $recordId,
            'akhir' => $recordId
        ];

        $btaq->update($btaqData);

        return response()->json([
            'success' => true,
            'message' => 'Data BTAQ berhasil diperbarui.',
            'data' => $btaq->load(['guru', 'siswa.kelas', 'iqroAwal', 'iqroAkhir', 'alquranAwal', 'alquranAkhir']),
        ]);
    }

    public function destroy($id)
    {
        $btaq = Btaq::findOrFail($id);

        $user = request()->user();
        if ($user instanceof Guru && $btaq->id_guru != $user->id_guru) {
            return response()->json([
                'success' => false,
                'message' => 'Anda tidak memiliki hak untuk menghapus data BTAQ ini.'
            ], 403);
        }

        $btaq->delete();

        return response()->json([
            'success' => true,
            'message' => 'Data BTAQ berhasil dihapus.',
        ]);
    }

    public function forSiswa(Request $request)
    {
        $user = $request->user();

        $nis = null;
        if ($user && isset($user->nis)) {
            $nis = $user->nis;
        } elseif ($user && isset($user->id_siswa)) {
            $nis = $user->id_siswa;
        }

        if (!$nis) {
            return response()->json([
                'success' => false,
                'message' => 'Data siswa tidak ditemukan.',
            ], 403);
        }

        $query = Btaq::with(['guru', 'iqroAwal', 'iqroAkhir', 'alquranAwal', 'alquranAkhir'])->where('nis', $nis);

        if ($request->filled('bulan') && $request->filled('tahun')) {
            $query->whereMonth('tanggal', $request->bulan)
                  ->whereYear('tanggal', $request->tahun);
        } elseif ($request->filled('tahun')) {
            $query->whereYear('tanggal', $request->tahun);
        }

        $perPage = $request->get('per_page', 50);
        $data = $query->orderByDesc('tanggal')->paginate($perPage);

        return response()->json([
            'success' => true,
            'nis'     => $nis,
            'data'    => $data,
        ]);
    }

    public function latest(Request $request)
    {
        $user = $request->user();

        $nis = null;
        if ($user && isset($user->nis)) {
            $nis = $user->nis;
        } elseif ($user && isset($user->id_siswa)) {
            $nis = $user->id_siswa;
        }

        if (!$nis) {
            return response()->json([
                'success' => false,
                'message' => 'Data siswa tidak ditemukan.',
            ], 403);
        }

        $btaq = Btaq::with(['guru', 'iqroAwal', 'iqroAkhir', 'alquranAwal', 'alquranAkhir'])
            ->where('nis', $nis)
            ->orderByDesc('tanggal')
            ->first();

        return response()->json([
            'success' => true,
            'data'    => $btaq,
        ]);
    }
}
