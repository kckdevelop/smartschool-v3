<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Guru;
use App\Models\LmsKuisJawaban;
use App\Models\LmsKuisSesi;
use App\Models\LmsKuisSetting;
use App\Models\LmsKuisToken;
use App\Models\LmsPengumpulan;
use App\Models\LmsSoal;
use App\Models\LmsSoalPilihan;
use App\Models\LmsTugas;
use App\Models\UserSiswa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class LmsKuisController extends Controller
{
    // =========================================================================
    //  HELPER
    // =========================================================================

    /**
     * Pastikan tugas bertipe kuis dan ada.
     */
    private function getTugasKuis(int $id_tugas): LmsTugas
    {
        $tugas = LmsTugas::findOrFail($id_tugas);
        if ($tugas->tipe !== 'kuis') {
            abort(400, 'Tugas ini bukan bertipe kuis.');
        }
        return $tugas;
    }

    /**
     * Ambil setting kuis, buat default jika belum ada.
     */
    private function getOrCreateSetting(int $id_tugas): LmsKuisSetting
    {
        return LmsKuisSetting::firstOrCreate(
            ['id_tugas' => $id_tugas],
            [
                'durasi_menit'    => 0,
                'acak_soal'       => false,
                'acak_jawaban'    => false,
                'tampilkan_hasil' => true,
                'maks_percobaan'  => 1,
            ]
        );
    }

    // =========================================================================
    //  SOAL CRUD (Guru)
    // =========================================================================

    /**
     * GET /lms/tugas/{id}/soal
     * List semua soal beserta pilihan jawaban (untuk guru).
     */
    public function indexSoal(Request $request, int $id_tugas)
    {
        $tugas = LmsTugas::findOrFail($id_tugas);
        $user  = $request->user();

        // Guru hanya bisa lihat soal miliknya sendiri
        if ($user instanceof Guru) {
            $kursus = $tugas->kursus;
            if ($kursus && $kursus->id_guru !== $user->id_guru) {
                return response()->json(['success' => false, 'message' => 'Akses ditolak.'], 403);
            }
        }

        $soal = LmsSoal::with('pilihan')
            ->where('id_tugas', $id_tugas)
            ->orderBy('nomor_soal')
            ->get()
            ->map(function ($s) {
                return [
                    'id_soal'      => $s->id_soal,
                    'nomor_soal'   => $s->nomor_soal,
                    'jenis_soal'   => $s->jenis_soal,
                    'pertanyaan'   => $s->pertanyaan,
                    'gambar'       => $s->gambar ? asset('storage/' . $s->gambar) : null,
                    'kunci_jawaban'=> $s->kunci_jawaban,
                    'pilihan'      => $s->pilihan->map(fn($p) => [
                        'id_pilihan' => $p->id_pilihan,
                        'kunci'      => $p->kunci,
                        'teks'       => $p->teks,
                        'gambar'     => $p->gambar ? asset('storage/' . $p->gambar) : null,
                        'is_kunci'   => $p->is_kunci,
                    ]),
                ];
            });

        return response()->json([
            'success'      => true,
            'judul_tugas'  => $tugas->judul,
            'jumlah_soal'  => $soal->count(),
            'data'         => $soal,
        ]);
    }

    /**
     * POST /lms/tugas/{id}/soal
     * Tambah soal baru pada sebuah tugas kuis (Guru).
     */
    public function storeSoal(Request $request, int $id_tugas)
    {
        $tugas = $this->getTugasKuis($id_tugas);
        $this->authorizeGuru($request, $tugas);

        $request->validate([
            'jenis_soal'    => 'required|in:pilihan_ganda,benar_salah,pilihan_ganda_komplek',
            'pertanyaan'    => 'required|string',
            'kunci_jawaban' => 'nullable|string',
            'pilihan'       => 'required_unless:jenis_soal,esai|array|min:2',
            'pilihan.*.kunci'    => 'required|string|max:20',
            'pilihan.*.teks'     => 'nullable|string',
            'pilihan.*.is_kunci' => 'required|boolean',
        ]);

        // Tentukan nomor soal berikutnya
        $nomorBerikutnya = (LmsSoal::where('id_tugas', $id_tugas)->max('nomor_soal') ?? 0) + 1;

        DB::beginTransaction();
        try {
            $soal = LmsSoal::create([
                'id_tugas'      => $id_tugas,
                'nomor_soal'    => $nomorBerikutnya,
                'jenis_soal'    => $request->jenis_soal,
                'pertanyaan'    => $request->pertanyaan,
                'kunci_jawaban' => $request->kunci_jawaban,
            ]);

            // Simpan pilihan jawaban
            if ($request->has('pilihan')) {
                foreach ($request->pilihan as $p) {
                    LmsSoalPilihan::create([
                        'id_soal'  => $soal->id_soal,
                        'kunci'    => $p['kunci'],
                        'teks'     => $p['teks'] ?? null,
                        'gambar'   => $p['gambar'] ?? null,
                        'is_kunci' => (bool) $p['is_kunci'],
                    ]);
                }
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => 'Gagal menyimpan soal: ' . $e->getMessage()], 500);
        }

        return response()->json([
            'success' => true,
            'message' => 'Soal berhasil ditambahkan.',
            'data'    => $soal->load('pilihan'),
        ], 201);
    }

    /**
     * PUT /lms/soal/{id_soal}
     * Edit soal (Guru).
     */
    public function updateSoal(Request $request, int $id_soal)
    {
        $soal  = LmsSoal::findOrFail($id_soal);
        $tugas = $this->getTugasKuis($soal->id_tugas);
        $this->authorizeGuru($request, $tugas);

        $request->validate([
            'jenis_soal'    => 'sometimes|in:pilihan_ganda,benar_salah,pilihan_ganda_komplek',
            'pertanyaan'    => 'sometimes|required|string',
            'kunci_jawaban' => 'nullable|string',
        ]);

        $soal->update($request->only('jenis_soal', 'pertanyaan', 'kunci_jawaban'));

        return response()->json([
            'success' => true,
            'message' => 'Soal berhasil diperbarui.',
            'data'    => $soal->load('pilihan'),
        ]);
    }

    /**
     * DELETE /lms/soal/{id_soal}
     * Hapus soal beserta semua pilihannya (Guru).
     */
    public function destroySoal(Request $request, int $id_soal)
    {
        $soal  = LmsSoal::findOrFail($id_soal);
        $tugas = $this->getTugasKuis($soal->id_tugas);
        $this->authorizeGuru($request, $tugas);

        DB::transaction(function () use ($soal) {
            LmsSoalPilihan::where('id_soal', $soal->id_soal)->delete();
            $soal->delete();
        });

        return response()->json([
            'success' => true,
            'message' => 'Soal berhasil dihapus.',
        ]);
    }

    /**
     * POST /lms/soal/{id_soal}/pilihan
     * Tambah pilihan jawaban pada sebuah soal (Guru).
     */
    public function storePilihan(Request $request, int $id_soal)
    {
        $soal  = LmsSoal::findOrFail($id_soal);
        $tugas = $this->getTugasKuis($soal->id_tugas);
        $this->authorizeGuru($request, $tugas);

        $request->validate([
            'kunci'    => 'required|string|max:20',
            'teks'     => 'nullable|string',
            'is_kunci' => 'required|boolean',
        ]);

        $pilihan = LmsSoalPilihan::create([
            'id_soal'  => $id_soal,
            'kunci'    => $request->kunci,
            'teks'     => $request->teks,
            'is_kunci' => (bool) $request->is_kunci,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Pilihan jawaban berhasil ditambahkan.',
            'data'    => $pilihan,
        ], 201);
    }

    /**
     * PUT /lms/soal/pilihan/{id_pilihan}
     * Edit pilihan jawaban (Guru).
     */
    public function updatePilihan(Request $request, int $id_pilihan)
    {
        $pilihan = LmsSoalPilihan::findOrFail($id_pilihan);
        $soal    = LmsSoal::findOrFail($pilihan->id_soal);
        $tugas   = $this->getTugasKuis($soal->id_tugas);
        $this->authorizeGuru($request, $tugas);

        $request->validate([
            'kunci'    => 'sometimes|string|max:20',
            'teks'     => 'nullable|string',
            'is_kunci' => 'sometimes|boolean',
        ]);

        $pilihan->update($request->only('kunci', 'teks', 'is_kunci'));

        return response()->json([
            'success' => true,
            'message' => 'Pilihan berhasil diperbarui.',
            'data'    => $pilihan,
        ]);
    }

    /**
     * DELETE /lms/soal/pilihan/{id_pilihan}
     * Hapus pilihan jawaban (Guru).
     */
    public function destroyPilihan(Request $request, int $id_pilihan)
    {
        $pilihan = LmsSoalPilihan::findOrFail($id_pilihan);
        $soal    = LmsSoal::findOrFail($pilihan->id_soal);
        $tugas   = $this->getTugasKuis($soal->id_tugas);
        $this->authorizeGuru($request, $tugas);

        $pilihan->delete();

        return response()->json([
            'success' => true,
            'message' => 'Pilihan berhasil dihapus.',
        ]);
    }

    // =========================================================================
    //  SETTING KUIS (Guru)
    // =========================================================================

    /**
     * GET /lms/kuis/{id_tugas}/setting
     * Ambil konfigurasi kuis.
     */
    public function getSetting(Request $request, int $id_tugas)
    {
        $tugas   = $this->getTugasKuis($id_tugas);
        $setting = $this->getOrCreateSetting($id_tugas);

        return response()->json([
            'success' => true,
            'data'    => $setting,
        ]);
    }

    /**
     * POST /lms/kuis/{id_tugas}/setting
     * Simpan/update konfigurasi kuis (Guru).
     */
    public function saveSetting(Request $request, int $id_tugas)
    {
        $tugas = $this->getTugasKuis($id_tugas);
        $this->authorizeGuru($request, $tugas);

        $request->validate([
            'durasi_menit'    => 'nullable|integer|min:0',
            'acak_soal'       => 'nullable|boolean',
            'acak_jawaban'    => 'nullable|boolean',
            'tampilkan_hasil' => 'nullable|boolean',
            'maks_percobaan'  => 'nullable|integer|min:1|max:10',
        ]);

        $setting = LmsKuisSetting::updateOrCreate(
            ['id_tugas' => $id_tugas],
            [
                'durasi_menit'    => $request->input('durasi_menit', 0),
                'acak_soal'       => $request->boolean('acak_soal', false),
                'acak_jawaban'    => $request->boolean('acak_jawaban', false),
                'tampilkan_hasil' => $request->boolean('tampilkan_hasil', true),
                'maks_percobaan'  => $request->input('maks_percobaan', 1),
            ]
        );

        return response()->json([
            'success' => true,
            'message' => 'Setting kuis berhasil disimpan.',
            'data'    => $setting,
        ]);
    }

    // =========================================================================
    //  TOKEN MANAGEMENT (Guru)
    // =========================================================================

    /**
     * POST /lms/kuis/{id_tugas}/generate-token
     * Generate token baru untuk sesi kuis (Guru).
     */
    public function generateToken(Request $request, int $id_tugas)
    {
        $tugas = $this->getTugasKuis($id_tugas);
        $this->authorizeGuru($request, $tugas);
        $user = $request->user();

        $request->validate([
            'expired_jam' => 'nullable|integer|min:1|max:168', // max 1 minggu
        ]);

        // Non-aktifkan token lama untuk tugas ini jika ada
        LmsKuisToken::where('id_tugas', $id_tugas)->update(['is_aktif' => false]);

        // Generate token unik 6 karakter alphanumeric uppercase
        do {
            $token = strtoupper(Str::random(6));
        } while (LmsKuisToken::where('token', $token)->exists());

        $expiredAt = null;
        if ($request->filled('expired_jam')) {
            $expiredAt = now()->addHours((int) $request->expired_jam);
        } else {
            // Default: expire 24 jam
            $expiredAt = now()->addHours(24);
        }

        $tokenRecord = LmsKuisToken::create([
            'id_tugas'   => $id_tugas,
            'id_guru'    => $user->id_guru,
            'token'      => $token,
            'is_aktif'   => true,
            'expired_at' => $expiredAt,
        ]);

        return response()->json([
            'success'    => true,
            'message'    => 'Token kuis berhasil di-generate.',
            'data'       => [
                'id_token'   => $tokenRecord->id_token,
                'token'      => $tokenRecord->token,
                'expired_at' => $tokenRecord->expired_at?->format('Y-m-d H:i:s'),
                'is_aktif'   => $tokenRecord->is_aktif,
            ],
        ], 201);
    }

    /**
     * GET /lms/kuis/{id_tugas}/token
     * List semua token untuk tugas ini (Guru).
     */
    public function listToken(Request $request, int $id_tugas)
    {
        $tugas = $this->getTugasKuis($id_tugas);
        $this->authorizeGuru($request, $tugas);

        $tokens = LmsKuisToken::where('id_tugas', $id_tugas)
            ->orderByDesc('created_at')
            ->get()
            ->map(fn($t) => [
                'id_token'   => $t->id_token,
                'token'      => $t->token,
                'is_aktif'   => $t->is_aktif,
                'expired_at' => $t->expired_at?->format('Y-m-d H:i:s'),
                'is_valid'   => $t->isValid(),
                'dibuat'     => $t->created_at->format('Y-m-d H:i:s'),
            ]);

        return response()->json([
            'success' => true,
            'data'    => $tokens,
        ]);
    }

    /**
     * DELETE /lms/kuis/token/{id_token}
     * Nonaktifkan/hapus token (Guru).
     */
    public function destroyToken(Request $request, int $id_token)
    {
        $tokenRecord = LmsKuisToken::findOrFail($id_token);
        $tugas       = $this->getTugasKuis($tokenRecord->id_tugas);
        $this->authorizeGuru($request, $tugas);

        $tokenRecord->update(['is_aktif' => false]);

        return response()->json([
            'success' => true,
            'message' => 'Token berhasil dinonaktifkan.',
        ]);
    }

    // =========================================================================
    //  HASIL KUIS (Guru)
    // =========================================================================

    /**
     * GET /lms/kuis/{id_tugas}/hasil
     * Lihat hasil semua siswa (Guru).
     */
    public function hasilSemua(Request $request, int $id_tugas)
    {
        $tugas = $this->getTugasKuis($id_tugas);
        $this->authorizeGuru($request, $tugas);

        $kursus      = $tugas->kursus;
        $jumlahSoal  = LmsSoal::where('id_tugas', $id_tugas)->count();
        $siswaList   = UserSiswa::where('id_kelas', $kursus->id_kelas)->orderBy('nama_siswa')->get();

        $hasilMap = LmsKuisSesi::where('id_tugas', $id_tugas)
            ->where('status', 'selesai')
            ->get()
            ->groupBy('nis');

        $data = $siswaList->map(function ($s) use ($hasilMap, $jumlahSoal) {
            $sesiSiswa = $hasilMap->get($s->nis);
            $sesiTerakhir = $sesiSiswa ? $sesiSiswa->sortByDesc('percobaan_ke')->first() : null;

            return [
                'nis'           => $s->nis,
                'nama_siswa'    => $s->nama_siswa,
                'percobaan'     => $sesiSiswa ? $sesiSiswa->count() : 0,
                'nilai'         => $sesiTerakhir?->nilai,
                'waktu_mulai'   => $sesiTerakhir?->waktu_mulai?->format('Y-m-d H:i:s'),
                'waktu_selesai' => $sesiTerakhir?->waktu_selesai?->format('Y-m-d H:i:s'),
                'durasi_menit'  => $sesiTerakhir?->waktu_mulai && $sesiTerakhir?->waktu_selesai
                    ? round($sesiTerakhir->waktu_mulai->diffInSeconds($sesiTerakhir->waktu_selesai) / 60, 1)
                    : null,
                'status'        => $sesiTerakhir?->status ?? 'belum',
            ];
        });

        return response()->json([
            'success'     => true,
            'judul_tugas' => $tugas->judul,
            'jumlah_soal' => $jumlahSoal,
            'data'        => $data,
        ]);
    }

    /**
     * GET /lms/kuis/{id_tugas}/hasil/{nis}
     * Detail jawaban satu siswa (Guru).
     */
    public function hasilSiswa(Request $request, int $id_tugas, int $nis)
    {
        $tugas = $this->getTugasKuis($id_tugas);
        $this->authorizeGuru($request, $tugas);

        $setting = $this->getOrCreateSetting($id_tugas);

        $sesi = LmsKuisSesi::where('id_tugas', $id_tugas)
            ->where('nis', $nis)
            ->orderByDesc('percobaan_ke')
            ->first();

        if (!$sesi) {
            return response()->json(['success' => false, 'message' => 'Siswa belum mengerjakan kuis.'], 404);
        }

        $soalList   = LmsSoal::with('pilihan')->where('id_tugas', $id_tugas)->orderBy('nomor_soal')->get();
        $jawabanMap = LmsKuisJawaban::where('id_sesi', $sesi->id_sesi)->get()->keyBy('id_soal');

        $detail = $soalList->map(function ($soal) use ($jawabanMap) {
            $jawaban  = $jawabanMap->get($soal->id_soal);
            $kunciIds = $soal->pilihan->where('is_kunci', true)->pluck('id_pilihan');

            return [
                'id_soal'        => $soal->id_soal,
                'nomor_soal'     => $soal->nomor_soal,
                'pertanyaan'     => $soal->pertanyaan,
                'jenis_soal'     => $soal->jenis_soal,
                'id_pilihan_dipilih' => $jawaban?->id_pilihan,
                'is_benar'       => $jawaban?->is_benar,
                'kunci_benar'    => $kunciIds->values(),
                'pilihan'        => $soal->pilihan->map(fn($p) => [
                    'id_pilihan' => $p->id_pilihan,
                    'kunci'      => $p->kunci,
                    'teks'       => $p->teks,
                    'is_kunci'   => $p->is_kunci,
                ]),
            ];
        });

        $siswa = UserSiswa::where('nis', $nis)->first();

        return response()->json([
            'success'       => true,
            'siswa'         => ['nis' => $nis, 'nama_siswa' => $siswa?->nama_siswa],
            'nilai'         => $sesi->nilai,
            'waktu_mulai'   => $sesi->waktu_mulai?->format('Y-m-d H:i:s'),
            'waktu_selesai' => $sesi->waktu_selesai?->format('Y-m-d H:i:s'),
            'status'        => $sesi->status,
            'data'          => $detail,
        ]);
    }

    // =========================================================================
    //  KUIS SISWA
    // =========================================================================

    /**
     * POST /lms/kuis/{id_tugas}/masuk
     * Siswa masuk kuis dengan token.
     * Jika sudah ada sesi berlangsung, lanjutkan sesi tersebut.
     * Jika sudah selesai, cek apakah masih boleh percobaan lagi.
     *
     * Body: { "token": "ABC123" }
     */
    public function masukKuis(Request $request, int $id_tugas)
    {
        $tugas   = $this->getTugasKuis($id_tugas);
        $user    = $request->user();

        if (!($user instanceof UserSiswa)) {
            return response()->json(['success' => false, 'message' => 'Hanya siswa yang dapat mengerjakan kuis.'], 403);
        }

        $request->validate([
            'token' => 'required|string|max:10',
        ]);

        // Validasi token
        $tokenRecord = LmsKuisToken::where('id_tugas', $id_tugas)
            ->where('token', strtoupper(trim($request->token)))
            ->first();

        if (!$tokenRecord || !$tokenRecord->isValid()) {
            return response()->json([
                'success' => false,
                'message' => 'Token tidak valid atau sudah tidak aktif.',
            ], 422);
        }

        $setting        = $this->getOrCreateSetting($id_tugas);
        $maksPercobaan  = $setting->maks_percobaan;

        // Cek sesi yang sedang berlangsung
        $sesiAktif = LmsKuisSesi::where('id_tugas', $id_tugas)
            ->where('nis', $user->nis)
            ->where('status', 'berlangsung')
            ->orderByDesc('percobaan_ke')
            ->first();

        if ($sesiAktif) {
            // Cek apakah waktu sudah habis (timeout di server)
            if ($setting->durasi_menit > 0) {
                $sisaDetik = $sesiAktif->sisaWaktuDetik($setting->durasi_menit);
                if ($sisaDetik <= 0) {
                    // Auto-timeout
                    $this->prosesSubmit($sesiAktif, $setting);
                    return response()->json([
                        'success' => false,
                        'message' => 'Waktu pengerjaan kuis telah habis.',
                        'status'  => 'timeout',
                    ], 422);
                }
            }

            // Lanjutkan sesi yang sudah ada
            return $this->buildSoalResponse($sesiAktif, $tugas, $setting, 'Melanjutkan sesi kuis.');
        }

        // Cek percobaan yang sudah selesai
        $jumlahSelesai = LmsKuisSesi::where('id_tugas', $id_tugas)
            ->where('nis', $user->nis)
            ->whereIn('status', ['selesai', 'timeout'])
            ->count();

        if ($jumlahSelesai >= $maksPercobaan) {
            return response()->json([
                'success' => false,
                'message' => "Anda sudah menggunakan semua $maksPercobaan percobaan yang tersedia.",
            ], 422);
        }

        // Buat sesi baru
        $percobaan = $jumlahSelesai + 1;
        $soalList  = LmsSoal::with('pilihan')->where('id_tugas', $id_tugas)->get();

        if ($soalList->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' => 'Kuis belum memiliki soal. Hubungi guru.',
            ], 422);
        }

        // Acak urutan soal
        $urutanSoal = $soalList->pluck('id_soal')->toArray();
        if ($setting->acak_soal) {
            shuffle($urutanSoal);
        }

        // Acak urutan pilihan per soal
        $urutanPilihan = [];
        if ($setting->acak_jawaban) {
            foreach ($soalList as $soal) {
                $pilihanIds = $soal->pilihan->pluck('id_pilihan')->toArray();
                shuffle($pilihanIds);
                $urutanPilihan[$soal->id_soal] = $pilihanIds;
            }
        }

        $sesi = LmsKuisSesi::create([
            'id_tugas'       => $id_tugas,
            'nis'            => $user->nis,
            'id_token'       => $tokenRecord->id_token,
            'percobaan_ke'   => $percobaan,
            'urutan_soal'    => $urutanSoal,
            'urutan_pilihan' => $urutanPilihan ?: null,
            'waktu_mulai'    => now(),
            'status'         => 'berlangsung',
        ]);

        return $this->buildSoalResponse($sesi, $tugas, $setting, 'Berhasil masuk kuis. Selamat mengerjakan!');
    }

    /**
     * GET /lms/kuis/{id_tugas}/soal
     * Ambil daftar soal beserta status jawaban siswa (untuk resume).
     */
    public function getSoalSiswa(Request $request, int $id_tugas)
    {
        $tugas = $this->getTugasKuis($id_tugas);
        $user  = $request->user();

        if (!($user instanceof UserSiswa)) {
            return response()->json(['success' => false, 'message' => 'Hanya siswa yang dapat mengakses.'], 403);
        }

        $setting = $this->getOrCreateSetting($id_tugas);

        $sesi = LmsKuisSesi::where('id_tugas', $id_tugas)
            ->where('nis', $user->nis)
            ->where('status', 'berlangsung')
            ->orderByDesc('percobaan_ke')
            ->first();

        if (!$sesi) {
            return response()->json([
                'success' => false,
                'message' => 'Tidak ada sesi kuis aktif. Masukkan token untuk memulai.',
            ], 404);
        }

        return $this->buildSoalResponse($sesi, $tugas, $setting);
    }

    /**
     * POST /lms/kuis/{id_tugas}/jawab
     * Auto-save jawaban satu soal.
     *
     * Body: { "id_soal": 1, "id_pilihan": 5 }
     */
    public function simpanJawaban(Request $request, int $id_tugas)
    {
        $tugas = $this->getTugasKuis($id_tugas);
        $user  = $request->user();

        if (!($user instanceof UserSiswa)) {
            return response()->json(['success' => false, 'message' => 'Hanya siswa yang dapat menjawab.'], 403);
        }

        $request->validate([
            'id_soal'     => 'required|integer|exists:lms_soal,id_soal',
            'id_pilihan'  => 'nullable|integer|exists:lms_soal_pilihan,id_pilihan',
            'jawaban_teks'=> 'nullable|string',
        ]);

        $setting  = $this->getOrCreateSetting($id_tugas);

        $sesi = LmsKuisSesi::where('id_tugas', $id_tugas)
            ->where('nis', $user->nis)
            ->where('status', 'berlangsung')
            ->orderByDesc('percobaan_ke')
            ->first();

        if (!$sesi) {
            return response()->json(['success' => false, 'message' => 'Tidak ada sesi kuis aktif.'], 404);
        }

        // Cek timeout
        if ($setting->durasi_menit > 0 && $sesi->sisaWaktuDetik($setting->durasi_menit) <= 0) {
            $this->prosesSubmit($sesi, $setting);
            return response()->json(['success' => false, 'message' => 'Waktu habis. Kuis otomatis dikumpulkan.', 'status' => 'timeout'], 422);
        }

        $soal = LmsSoal::findOrFail($request->id_soal);

        // Pastikan soal milik tugas ini
        if ($soal->id_tugas !== $id_tugas) {
            return response()->json(['success' => false, 'message' => 'Soal tidak ditemukan dalam kuis ini.'], 422);
        }

        // Tentukan is_benar
        $isBenar = null;
        if ($request->filled('id_pilihan')) {
            $pilihan = LmsSoalPilihan::find($request->id_pilihan);
            $isBenar = $pilihan?->is_kunci ?? false;
        }

        // Untuk pilihan_ganda: hapus jawaban lama lalu simpan yang baru (satu pilihan)
        if ($soal->jenis_soal === 'pilihan_ganda' || $soal->jenis_soal === 'benar_salah') {
            LmsKuisJawaban::where('id_sesi', $sesi->id_sesi)
                ->where('id_soal', $soal->id_soal)
                ->delete();
        }

        // Untuk pilihan_ganda_komplek: toggle pilihan
        if ($soal->jenis_soal === 'pilihan_ganda_komplek' && $request->filled('id_pilihan')) {
            $existing = LmsKuisJawaban::where('id_sesi', $sesi->id_sesi)
                ->where('id_soal', $soal->id_soal)
                ->where('id_pilihan', $request->id_pilihan)
                ->first();

            if ($existing) {
                $existing->delete();
                return response()->json(['success' => true, 'message' => 'Pilihan dibatalkan.', 'action' => 'removed']);
            }
        }

        // Hapus jawaban lama untuk soal ini (untuk kasus reset jawaban: id_pilihan = null)
        if (!$request->filled('id_pilihan') && !$request->filled('jawaban_teks')) {
            LmsKuisJawaban::where('id_sesi', $sesi->id_sesi)
                ->where('id_soal', $soal->id_soal)
                ->delete();
            return response()->json(['success' => true, 'message' => 'Jawaban direset.']);
        }

        $jawaban = LmsKuisJawaban::create([
            'id_sesi'      => $sesi->id_sesi,
            'id_soal'      => $soal->id_soal,
            'id_pilihan'   => $request->id_pilihan,
            'jawaban_teks' => $request->jawaban_teks,
            'is_benar'     => $isBenar,
        ]);

        return response()->json([
            'success'  => true,
            'message'  => 'Jawaban tersimpan.',
            'id_soal'  => $soal->id_soal,
            'is_benar' => $isBenar, // null jika tampilkan_hasil = false
        ]);
    }

    /**
     * POST /lms/kuis/{id_tugas}/submit
     * Submit kuis dan hitung nilai akhir.
     */
    public function submitKuis(Request $request, int $id_tugas)
    {
        $tugas = $this->getTugasKuis($id_tugas);
        $user  = $request->user();

        if (!($user instanceof UserSiswa)) {
            return response()->json(['success' => false, 'message' => 'Hanya siswa yang dapat submit.'], 403);
        }

        $setting = $this->getOrCreateSetting($id_tugas);

        $sesi = LmsKuisSesi::where('id_tugas', $id_tugas)
            ->where('nis', $user->nis)
            ->where('status', 'berlangsung')
            ->orderByDesc('percobaan_ke')
            ->first();

        if (!$sesi) {
            return response()->json(['success' => false, 'message' => 'Tidak ada sesi kuis yang sedang berlangsung.'], 404);
        }

        $nilai  = $this->prosesSubmit($sesi, $setting);
        $sesi->refresh();

        $response = [
            'success' => true,
            'message' => 'Kuis berhasil dikumpulkan.',
            'status'  => $sesi->status,
        ];

        if ($setting->tampilkan_hasil) {
            $response['nilai']       = $nilai;
            $response['waktu_mulai'] = $sesi->waktu_mulai?->format('Y-m-d H:i:s');
            $response['waktu_selesai'] = $sesi->waktu_selesai?->format('Y-m-d H:i:s');
        }

        return response()->json($response);
    }

    /**
     * GET /lms/kuis/{id_tugas}/hasil-saya
     * Siswa melihat hasil kuis miliknya sendiri.
     */
    public function hasilSaya(Request $request, int $id_tugas)
    {
        $tugas = $this->getTugasKuis($id_tugas);
        $user  = $request->user();

        if (!($user instanceof UserSiswa)) {
            return response()->json(['success' => false, 'message' => 'Hanya untuk siswa.'], 403);
        }

        $setting = $this->getOrCreateSetting($id_tugas);

        $sesiList = LmsKuisSesi::where('id_tugas', $id_tugas)
            ->where('nis', $user->nis)
            ->orderByDesc('percobaan_ke')
            ->get();

        if ($sesiList->isEmpty()) {
            return response()->json(['success' => false, 'message' => 'Anda belum pernah mengerjakan kuis ini.'], 404);
        }

        $sesiTerakhir = $sesiList->first();

        $responseData = [
            'success'        => true,
            'judul_tugas'    => $tugas->judul,
            'maks_percobaan' => $setting->maks_percobaan,
            'percobaan_digunakan' => $sesiList->count(),
            'sisa_percobaan' => max(0, $setting->maks_percobaan - $sesiList->whereIn('status', ['selesai', 'timeout'])->count()),
            'percobaan_terakhir' => [
                'percobaan_ke'  => $sesiTerakhir->percobaan_ke,
                'status'        => $sesiTerakhir->status,
                'waktu_mulai'   => $sesiTerakhir->waktu_mulai?->format('Y-m-d H:i:s'),
                'waktu_selesai' => $sesiTerakhir->waktu_selesai?->format('Y-m-d H:i:s'),
            ],
        ];

        if ($setting->tampilkan_hasil && $sesiTerakhir->status !== 'berlangsung') {
            $responseData['percobaan_terakhir']['nilai'] = $sesiTerakhir->nilai;

            // Detail soal + jawaban
            $soalList   = LmsSoal::with('pilihan')->where('id_tugas', $id_tugas)->orderBy('nomor_soal')->get();
            $jawabanMap = LmsKuisJawaban::where('id_sesi', $sesiTerakhir->id_sesi)->get()->keyBy('id_soal');

            $responseData['detail_jawaban'] = $soalList->map(function ($soal) use ($jawabanMap) {
                $jawaban = $jawabanMap->get($soal->id_soal);
                $kunciIds = $soal->pilihan->where('is_kunci', true)->pluck('id_pilihan');
                return [
                    'id_soal'            => $soal->id_soal,
                    'nomor_soal'         => $soal->nomor_soal,
                    'pertanyaan'         => $soal->pertanyaan,
                    'jenis_soal'         => $soal->jenis_soal,
                    'id_pilihan_dipilih' => $jawaban?->id_pilihan,
                    'is_benar'           => $jawaban?->is_benar,
                    'kunci_benar'        => $kunciIds->values(),
                    'pilihan'            => $soal->pilihan->map(fn($p) => [
                        'id_pilihan' => $p->id_pilihan,
                        'kunci'      => $p->kunci,
                        'teks'       => $p->teks,
                        'is_kunci'   => $p->is_kunci,
                    ]),
                ];
            });
        }

        return response()->json($responseData);
    }

    // =========================================================================
    //  PRIVATE HELPERS
    // =========================================================================

    /**
     * Proses submit kuis: hitung nilai, update sesi & pengumpulan.
     */
    private function prosesSubmit(LmsKuisSesi $sesi, LmsKuisSetting $setting): int
    {
        $soalList    = LmsSoal::where('id_tugas', $sesi->id_tugas)->get();
        $jawabanList = LmsKuisJawaban::where('id_sesi', $sesi->id_sesi)->get();

        $totalSoal  = $soalList->count();
        $totalBenar = 0;

        foreach ($soalList as $soal) {
            if ($soal->jenis_soal === 'pilihan_ganda' || $soal->jenis_soal === 'benar_salah') {
                $jawaban = $jawabanList->where('id_soal', $soal->id_soal)->first();
                if ($jawaban && $jawaban->is_benar) {
                    $totalBenar++;
                }
            } elseif ($soal->jenis_soal === 'pilihan_ganda_komplek') {
                // Semua pilihan kunci harus dipilih & tidak ada yang salah
                $kunciIds   = $soal->pilihan->where('is_kunci', true)->pluck('id_pilihan')->toArray();
                $dipilihIds = $jawabanList->where('id_soal', $soal->id_soal)->pluck('id_pilihan')->toArray();
                sort($kunciIds);
                sort($dipilihIds);
                if ($kunciIds === $dipilihIds) {
                    $totalBenar++;
                }
            }
        }

        $nilai = $totalSoal > 0 ? (int) round(($totalBenar / $totalSoal) * 100) : 0;

        // Tentukan status
        $waktuSelesai = now();
        $statusSesi   = 'selesai';
        if ($setting->durasi_menit > 0 && $sesi->sisaWaktuDetik($setting->durasi_menit) <= 0) {
            $statusSesi = 'timeout';
        }

        $sesi->update([
            'waktu_selesai' => $waktuSelesai,
            'nilai'         => $nilai,
            'status'        => $statusSesi,
        ]);

        // Simpan ke lms_pengumpulan (integrasi dengan sistem tugas)
        LmsPengumpulan::updateOrCreate(
            ['id_tugas' => $sesi->id_tugas, 'nis' => $sesi->nis],
            [
                'nilai'   => $nilai,
                'catatan' => "Kuis CBT — Percobaan ke-{$sesi->percobaan_ke} | Benar: $totalBenar/$totalSoal",
                'status'  => 'dinilai',
            ]
        );

        return $nilai;
    }

    /**
     * Bangun response soal untuk siswa (dengan urutan & status jawaban).
     */
    private function buildSoalResponse(LmsKuisSesi $sesi, LmsTugas $tugas, LmsKuisSetting $setting, string $message = null): \Illuminate\Http\JsonResponse
    {
        $urutanSoal    = $sesi->urutan_soal ?? [];
        $urutanPilihan = $sesi->urutan_pilihan ?? [];

        // Ambil semua soal dengan pilihan
        $soalMap = LmsSoal::with('pilihan')->where('id_tugas', $tugas->id_tugas)->get()->keyBy('id_soal');

        // Ambil jawaban yang sudah ada
        $jawabanMap = LmsKuisJawaban::where('id_sesi', $sesi->id_sesi)->get()->groupBy('id_soal');

        // Susun soal sesuai urutan acak
        $soalResponse = collect($urutanSoal)->map(function ($id_soal, $index) use ($soalMap, $urutanPilihan, $jawabanMap) {
            $soal = $soalMap->get($id_soal);
            if (!$soal) return null;

            // Susun pilihan sesuai urutan acak (jika ada)
            $pilihanOrdered = $soal->pilihan;
            if (!empty($urutanPilihan[$id_soal])) {
                $pilihanMap     = $soal->pilihan->keyBy('id_pilihan');
                $pilihanOrdered = collect($urutanPilihan[$id_soal])
                    ->map(fn($pid) => $pilihanMap->get($pid))
                    ->filter();
            }

            // Jawaban siswa untuk soal ini
            $jawabanSoal = $jawabanMap->get($id_soal, collect());

            return [
                'no_urut'         => $index + 1,  // nomor urut tampil (setelah diacak)
                'id_soal'         => $soal->id_soal,
                'nomor_soal_asli' => $soal->nomor_soal,
                'jenis_soal'      => $soal->jenis_soal,
                'pertanyaan'      => $soal->pertanyaan,
                'gambar'          => $soal->gambar ? asset('storage/' . $soal->gambar) : null,
                'sudah_dijawab'   => $jawabanSoal->isNotEmpty(),
                'id_pilihan_dipilih' => $jawabanSoal->pluck('id_pilihan')->filter()->values(),
                'pilihan'         => $pilihanOrdered->map(fn($p) => [
                    'id_pilihan' => $p->id_pilihan,
                    'kunci'      => $p->kunci,
                    'teks'       => $p->teks,
                    'gambar'     => $p->gambar ? asset('storage/' . $p->gambar) : null,
                ]),
            ];
        })->filter()->values();

        $sisaDetik = $sesi->sisaWaktuDetik($setting->durasi_menit);
        $dijawab   = $soalResponse->where('sudah_dijawab', true)->count();

        $result = [
            'success'          => true,
            'message'          => $message,
            'id_sesi'          => $sesi->id_sesi,
            'judul_tugas'      => $tugas->judul,
            'percobaan_ke'     => $sesi->percobaan_ke,
            'waktu_mulai'      => $sesi->waktu_mulai?->format('Y-m-d H:i:s'),
            'durasi_menit'     => $setting->durasi_menit,
            'sisa_waktu_detik' => $sisaDetik,
            'jumlah_soal'      => $soalResponse->count(),
            'sudah_dijawab'    => $dijawab,
            'belum_dijawab'    => $soalResponse->count() - $dijawab,
            'soal'             => $soalResponse,
        ];

        return response()->json($result);
    }

    /**
     * Otorisasi guru: hanya guru pengampu kursus yang boleh mengelola.
     */
    private function authorizeGuru(Request $request, LmsTugas $tugas): void
    {
        $user = $request->user();
        if (!($user instanceof Guru)) {
            abort(403, 'Hanya guru yang dapat mengelola kuis.');
        }
        $kursus = $tugas->kursus;
        if ($kursus && $kursus->id_guru !== $user->id_guru) {
            abort(403, 'Anda bukan guru pengampu kursus ini.');
        }
    }
}
