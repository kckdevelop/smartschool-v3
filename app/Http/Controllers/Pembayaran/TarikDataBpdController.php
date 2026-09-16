<?php

namespace App\Http\Controllers\Pembayaran;

use App\Http\Controllers\Controller;
use App\Models\Kelas;
use App\Models\Sekolah;
use App\Models\SettingPembayaran;
use App\Models\UserSiswa;
use App\Models\TagihanPembayaran;
use App\Models\TahunAjaran;
use App\Services\BpdDiyService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class TarikDataBpdController extends Controller
{
    /**
     * Tampilkan Halaman Tarik Data Tagihan VA BPD DIY
     */
    public function index(Request $request)
    {
        $setting     = SettingPembayaran::getSetting();
        $sekolah     = Sekolah::first();
        $tahunAktif  = TahunAjaran::where('status', 'aktif')->first();
        $kelasList   = Kelas::where('status', 'aktif')->orderBy('tingkat', 'asc')->orderBy('rombel', 'asc')->get();

        // Hitung status cookie
        $hasCookie      = !empty($setting->session_cookie);
        $cookieUpdated  = $setting->cookie_updated_at;
        $cookieAgeText  = $cookieUpdated ? $cookieUpdated->diffForHumans() : null;

        return view('pembayaran.tarik-data.index', compact(
            'setting',
            'sekolah',
            'tahunAktif',
            'kelasList',
            'hasCookie',
            'cookieUpdated',
            'cookieAgeText'
        ));
    }

    /**
     * Tarik Data dari Portal BPD DIY via AJAX
     * Endpoint: POST /pembayaran/tarik-data/fetch
     */
    public function fetch(Request $request): JsonResponse
    {
        // Perpanjang waktu eksekusi PHP karena proses multi-halaman bisa > 60 detik
        // Catatan: set_time_limit diabaikan di PHP-FPM, gunakan ini_set sebagai fallback
        @set_time_limit(600);
        @ini_set('max_execution_time', 600);
        @ini_set('memory_limit', '512M');

        $setting = SettingPembayaran::getSetting();

        if ($setting->status_api !== 'aktif') {
            return response()->json([
                'success' => false,
                'message' => 'Status API BPD DIY dalam konfigurasi sedang Nonaktif. Silakan aktifkan di menu Setting Konfigurasi.',
                'data'    => [],
                'total'   => 0,
            ]);
        }

        if (empty($setting->session_cookie)) {
            return response()->json([
                'success' => false,
                'message' => 'Belum ada Session Cookie BPD DIY yang tersimpan. Silakan masukkan Cookie dari portal BPD DIY.',
                'data'    => [],
                'total'   => 0,
                'need_cookie' => true,
            ]);
        }

        $tahun = trim((string) $request->input('tahun', date('Y')));
        $idInstitusi = $setting->id_institusi ?? '9990029';
        $prefixInstitusi = str_starts_with($idInstitusi, '999') ? $idInstitusi : ('999' . $idInstitusi);
        $vaPrefix = !empty($tahun) ? ($prefixInstitusi . $tahun) : '';

        $filters = [
            'periode_waktu'    => $request->input('periode_waktu', ''),
            'status_transaksi' => $request->input('status_transaksi', 'semua'),
            'parent_va'        => $request->input('parent_va', ''),
            'mitra'            => $request->input('mitra', $setting->id_institusi ?? ''),
            'search'           => $request->input('search', ''),
            'length'           => (int) $request->input('length', 0), // 0 = ambil semua
            'tahun'            => $tahun,
            'va_prefix'        => $vaPrefix,
        ];

        try {
            $service = new BpdDiyService($setting);
            $result  = $service->fetchReportTagihanVa($filters);

            if (!$result['success']) {
                return response()->json([
                    'success' => false,
                    'message' => $result['message'] ?? 'Gagal menarik data dari portal BPD DIY.',
                    'data'    => [],
                    'total'   => 0,
                ]);
            }

            $rawRows = $result['data'] ?? [];

            $enriched = $this->enrichAndSummarize($rawRows);

            return response()->json([
                'success' => true,
                'data'    => $enriched['data'],
                'total'   => count($enriched['data']),
                'total_remote' => $result['total'] ?? count($enriched['data']),
                'summary' => $enriched['summary'],
                'message' => count($enriched['data']) . ' dari ' . ($result['total'] ?? count($enriched['data'])) . ' data tagihan VA berhasil ditarik dari portal BPD DIY.',
            ]);
        } catch (\Exception $e) {
            Log::error('[TarikDataBpdController fetch] Exception: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan sistem: ' . $e->getMessage(),
                'data'    => [],
                'total'   => 0,
            ], 500);
        }
    }

    /**
     * Upload & Parsing File Excel / CSV Hasil Export dari Portal BPD DIY
     * Endpoint: POST /pembayaran/tarik-data/upload-excel
     */
    public function uploadExcel(Request $request): JsonResponse
    {
        $request->validate([
            'file_excel' => 'required|file|mimes:xlsx,xls,csv,txt|max:20480', // Maks 20MB
        ]);

        try {
            $file    = $request->file('file_excel');
            $setting = SettingPembayaran::getSetting();
            $service = new BpdDiyService($setting);

            $tahun = trim((string) $request->input('tahun', date('Y')));
            $idInstitusi = $setting->id_institusi ?? '9990029';
            $prefixInstitusi = str_starts_with($idInstitusi, '999') ? $idInstitusi : ('999' . $idInstitusi);
            $vaPrefix = !empty($tahun) ? ($prefixInstitusi . $tahun) : '';

            $result  = $service->parseSpreadsheetFile($file->getRealPath(), $vaPrefix);

            if (!$result['success']) {
                return response()->json([
                    'success' => false,
                    'message' => $result['message'] ?? 'Gagal membaca file Excel.',
                    'data'    => [],
                    'total'   => 0,
                ]);
            }

            $rawRows  = $result['data'] ?? [];
            $enriched = $this->enrichAndSummarize($rawRows);

            return response()->json([
                'success' => true,
                'data'    => $enriched['data'],
                'total'   => count($enriched['data']),
                'summary' => $enriched['summary'],
                'message' => count($enriched['data']) . ' data tagihan VA berhasil dimuat dari file Excel / CSV.',
            ]);
        } catch (\Exception $e) {
            Log::error('[TarikDataBpdController uploadExcel] Exception: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Gagal memproses file: ' . $e->getMessage(),
                'data'    => [],
                'total'   => 0,
            ], 500);
        }
    }

    /**
     * Trigger Login Programatik Otomatis ke BPD DIY
     * Endpoint: POST /pembayaran/tarik-data/auto-login
     */
    public function autoLogin(Request $request): JsonResponse
    {
        $setting = SettingPembayaran::getSetting();

        if (empty($setting->username_maker) || empty($setting->password_maker)) {
            return response()->json([
                'success' => false,
                'message' => 'Username Maker & Password Maker belum diisi di Setting Konfigurasi. Silakan isi kredensial akun terlebih dahulu.',
            ]);
        }

        try {
            $service = new BpdDiyService($setting);
            $success = $service->login(true);

            if ($success) {
                return response()->json([
                    'success' => true,
                    'message' => '✅ Login otomatis ke portal BPD DIY berhasil! Sesi dan Cookie baru telah tersimpan.',
                ]);
            }

            return response()->json([
                'success' => false,
                'message' => 'Login otomatis gagal. Silakan periksa kembali Username & Password di Setting Konfigurasi atau masukkan Cookie secara manual.',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat login otomatis: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Helper untuk memperkaya data mentah (cross-check siswa & database lokal + kalkulasi summary).
     */
    /**
     * Helper untuk memperkaya data mentah (cross-check siswa & database lokal + kalkulasi summary).
     */
    private function enrichAndSummarize(array $rawRows): array
    {
        // Ambil semua NIS & VA untuk cross-check dengan DB lokal
        $allVa  = array_unique(array_filter(array_column($rawRows, 'nomor_va')));
        $allTrx = array_unique(array_filter(array_column($rawRows, 'trx_id')));

        $cleanString = function ($str) {
            $str = strtoupper(trim((string)$str));
            $str = preg_replace('/[\'"`\.\-]/', '', $str);
            $str = preg_replace('/\s+/', ' ', $str);
            return trim($str);
        };

        $cleanClassKey = function ($str) {
            $str = strtoupper(preg_replace('/[^A-Z0-9]/i', '', (string)$str));
            if (str_starts_with($str, 'XII')) {
                $str = '12' . substr($str, 3);
            } elseif (str_starts_with($str, 'XI')) {
                $str = '11' . substr($str, 2);
            } elseif (str_starts_with($str, 'X')) {
                $str = '10' . substr($str, 1);
            }
            return $str;
        };

        // Siapkan lookup map kelas
        $kelasList = Kelas::all();
        $kelasMap = [];
        foreach ($kelasList as $k) {
            $fullNama = $k->tingkat . ' ' . $k->rombel;
            $cleanKey = $cleanClassKey($fullNama);
            $kelasMap[$cleanKey] = [
                'id_kelas'   => $k->id_kelas,
                'nama_kelas' => $k->nama_kelas,
            ];
            $roman = $k->tingkat == 10 ? 'X' : ($k->tingkat == 11 ? 'XI' : ($k->tingkat == 12 ? 'XII' : $k->tingkat));
            $kelasMap[$cleanClassKey($roman . ' ' . $k->rombel)] = [
                'id_kelas'   => $k->id_kelas,
                'nama_kelas' => $k->nama_kelas,
            ];
        }

        // Load semua data siswa untuk cross-check cepat (NIS, Nama Lengkap, & Nama + Kelas)
        $siswaList = UserSiswa::with('kelas')->get();
        $nisMap       = [];
        $nameMap      = [];
        $nameClassMap = [];

        foreach ($siswaList as $s) {
            $siswaData = [
                'nis'        => $s->nis,
                'nama_siswa' => $s->nama_siswa ?? $s->nama_lengkap ?? '',
                'nama_kelas' => $s->kelas->nama_kelas ?? '-',
                'id_kelas'   => $s->id_kelas,
            ];
            $nisMap[(string) $s->nis] = $siswaData;

            $cName = $cleanString($s->nama_siswa ?? $s->nama_lengkap ?? '');
            if (!empty($cName)) {
                $nameMap[$cName] = $siswaData;
                if ($s->kelas) {
                    $cKelas = $cleanClassKey($s->kelas->nama_kelas);
                    $nameClassMap[$cName . '_' . $cKelas] = $siswaData;
                }
            }
        }

        // Cek data tagihan yang sudah tersimpan di database lokal
        $tagihanMap = [];
        if (!empty($allVa) || !empty($allTrx)) {
            $existingTagihan = TagihanPembayaran::where(function ($q) use ($allVa, $allTrx) {
                if (!empty($allVa)) $q->whereIn('nomor_va', $allVa);
                if (!empty($allTrx)) $q->orWhereIn('trx_id', $allTrx);
            })->get(['id', 'trx_id', 'nomor_va', 'status', 'nominal_terbayar', 'nominal']);

            foreach ($existingTagihan as $t) {
                if ($t->trx_id) {
                    $tagihanMap['trx_' . $t->trx_id] = $t;
                }
                if ($t->nomor_va) {
                    $tagihanMap['va_' . $t->nomor_va] = $t;
                }
            }
        }

        // Kalkulasi ringkasan / summary
        $totalNilai    = 0;
        $totalTerbayar = 0;
        $totalSisa     = 0;
        $countLunas    = 0;
        $countSebagian = 0;
        $countBelum    = 0;

        $enrichedData = [];
        foreach ($rawRows as $row) {
            $nisKey   = (string) ($row['nomor_identitas'] ?? '');
            $trxKey   = (string) ($row['trx_id'] ?? '');
            $vaKey    = (string) ($row['nomor_va'] ?? '');
            $namaRaw  = (string) ($row['nama_siswa'] ?? $row['nama_va'] ?? '');
            $kelasRaw = (string) ($row['kelas'] ?? '');

            $cName  = $cleanString($namaRaw);
            $cKelas = $cleanClassKey($kelasRaw);

            // Hierarki Pencocokan Siswa & Kelas:
            $siswaInfo = null;

            // 1. Prioritas 1: Cocokkan Nama Siswa + Kelas
            if (!empty($cName) && !empty($cKelas) && isset($nameClassMap[$cName . '_' . $cKelas])) {
                $siswaInfo = $nameClassMap[$cName . '_' . $cKelas];
            }
            // 2. Prioritas 2: Cocokkan Nama Siswa persis
            elseif (!empty($cName) && isset($nameMap[$cName])) {
                $siswaInfo = $nameMap[$cName];
            }
            // 3. Prioritas 3: Cocokkan NIS terdaftar (jika nama cocok/mirip)
            elseif (!empty($nisKey) && isset($nisMap[$nisKey])) {
                $cand = $nisMap[$nisKey];
                $candClean = $cleanString($cand['nama_siswa']);
                if (empty($cName) || str_contains($candClean, $cName) || str_contains($cName, $candClean) || similar_text($candClean, $cName) > (strlen($cName) * 0.7)) {
                    $siswaInfo = $cand;
                }
            }

            // Fallback nama kelas jika siswa belum ada di DB
            $displayKelas = $row['kelas'] ?? '-';
            if ($siswaInfo) {
                $displayKelas = $siswaInfo['nama_kelas'];
            } elseif (!empty($cKelas) && isset($kelasMap[$cKelas])) {
                $displayKelas = $kelasMap[$cKelas]['nama_kelas'];
            }

            $dbTagihan = $tagihanMap['trx_' . $trxKey] ?? ($tagihanMap['va_' . $vaKey] ?? null);

            $nominal  = (float) ($row['total_nilai_tagihan'] ?? 0);
            $terbayar = (float) ($row['nominal_telah_dibayar'] ?? 0);
            $sisa     = (float) ($row['sisa_pembayaran'] ?? max(0, $nominal - $terbayar));
            $status   = $row['status'] ?? 'belum_bayar';

            $totalNilai    += $nominal;
            $totalTerbayar += $terbayar;
            $totalSisa     += $sisa;

            if ($status === 'lunas') {
                $countLunas++;
            } elseif ($status === 'sebagian') {
                $countSebagian++;
            } else {
                $countBelum++;
            }

            $row['in_db']           = $dbTagihan !== null;
            $row['db_id']           = $dbTagihan?->id;
            $row['db_status']       = $dbTagihan?->status;
            $row['db_terbayar']     = (float) ($dbTagihan?->nominal_terbayar ?? 0);
            $row['siswa_terdaftar'] = $siswaInfo !== null;
            $row['siswa_info']      = $siswaInfo;
            $row['kelas']           = $displayKelas;

            // Update info nis_terdaftar jika terdeteksi siswa lokal
            if ($siswaInfo) {
                $row['nis_terdaftar'] = $siswaInfo['nis'];
            }

            $enrichedData[] = $row;
        }

        return [
            'data'    => $enrichedData,
            'summary' => [
                'total_records'  => count($enrichedData),
                'total_nilai'    => $totalNilai,
                'total_terbayar' => $totalTerbayar,
                'total_sisa'     => $totalSisa,
                'count_lunas'    => $countLunas,
                'count_sebagian' => $countSebagian,
                'count_belum'    => $countBelum,
            ],
        ];
    }

    /**
     * Sinkronisasi data yang ditarik ke database SmartSchool (tabel tagihan_pembayaran)
     * Endpoint: POST /pembayaran/tarik-data/sync
     */
    public function sync(Request $request): JsonResponse
    {
        $rows = $request->input('rows', []);
        if (empty($rows) || !is_array($rows)) {
            return response()->json([
                'success' => false,
                'message' => 'Tidak ada data tagihan yang dipilih untuk disimpan/disinkronkan.',
            ]);
        }

        $cleanString = function ($str) {
            $str = strtoupper(trim((string)$str));
            $str = preg_replace('/[\'"`\.\-]/', '', $str);
            $str = preg_replace('/\s+/', ' ', $str);
            return trim($str);
        };

        $cleanClassKey = function ($str) {
            $str = strtoupper(preg_replace('/[^A-Z0-9]/i', '', (string)$str));
            if (str_starts_with($str, 'XII')) {
                $str = '12' . substr($str, 3);
            } elseif (str_starts_with($str, 'XI')) {
                $str = '11' . substr($str, 2);
            } elseif (str_starts_with($str, 'X')) {
                $str = '10' . substr($str, 1);
            }
            return $str;
        };

        // Siapkan lookup map kelas
        $kelasList = Kelas::all();
        $kelasMap = [];
        foreach ($kelasList as $k) {
            $fullNama = $k->tingkat . ' ' . $k->rombel;
            $cleanKey = $cleanClassKey($fullNama);
            $kelasMap[$cleanKey] = [
                'id_kelas'   => $k->id_kelas,
                'nama_kelas' => $k->nama_kelas,
            ];
            $roman = $k->tingkat == 10 ? 'X' : ($k->tingkat == 11 ? 'XI' : ($k->tingkat == 12 ? 'XII' : $k->tingkat));
            $kelasMap[$cleanClassKey($roman . ' ' . $k->rombel)] = [
                'id_kelas'   => $k->id_kelas,
                'nama_kelas' => $k->nama_kelas,
            ];
        }

        // Siapkan lookup map siswa
        $siswaList = UserSiswa::with('kelas')->get();
        $nisMap       = [];
        $nameMap      = [];
        $nameClassMap = [];

        foreach ($siswaList as $s) {
            $siswaData = [
                'nis'        => $s->nis,
                'nama_siswa' => $s->nama_siswa ?? $s->nama_lengkap ?? '',
                'nama_kelas' => $s->kelas->nama_kelas ?? '-',
                'id_kelas'   => $s->id_kelas,
            ];
            $nisMap[(string) $s->nis] = $siswaData;

            $cName = $cleanString($s->nama_siswa ?? $s->nama_lengkap ?? '');
            if (!empty($cName)) {
                $nameMap[$cName] = $siswaData;
                if ($s->kelas) {
                    $cKelas = $cleanClassKey($s->kelas->nama_kelas);
                    $nameClassMap[$cName . '_' . $cKelas] = $siswaData;
                }
            }
        }

        $setting    = SettingPembayaran::getSetting();
        $tahunAktif = TahunAjaran::where('status', 'aktif')->first();
        $idTahun    = $tahunAktif?->id_tahun;

        $deletedCount = 0;
        $insertedCount = 0;
        $skippedCount = 0;

        DB::beginTransaction();
        try {
            // Kosongkan dahulu semua data yang ada di dalam tabel tagihan_pembayaran
            $deletedCount = TagihanPembayaran::count();
            TagihanPembayaran::query()->delete();

            $insertBatch = [];
            $now = now();

            foreach ($rows as $row) {
                $nisKey   = trim((string) ($row['nomor_identitas'] ?? ''));
                $noVa     = preg_replace('/\s+/', '', (string) ($row['nomor_va'] ?? ''));
                $trxId    = preg_replace('/\s+/', '', (string) ($row['trx_id'] ?? ''));
                $namaRaw  = (string) ($row['nama_siswa'] ?? $row['nama_va'] ?? '');
                $kelasRaw = (string) ($row['kelas'] ?? '');

                if (empty($noVa) && empty($nisKey)) {
                    $skippedCount++;
                    continue;
                }

                $cName  = $cleanString($namaRaw);
                $cKelas = $cleanClassKey($kelasRaw);

                // Hierarki Pencocokan Siswa & Kelas
                $siswaInfo = null;
                if (!empty($row['nis_terdaftar']) && isset($nisMap[$row['nis_terdaftar']])) {
                    $siswaInfo = $nisMap[$row['nis_terdaftar']];
                } elseif (!empty($cName) && !empty($cKelas) && isset($nameClassMap[$cName . '_' . $cKelas])) {
                    $siswaInfo = $nameClassMap[$cName . '_' . $cKelas];
                } elseif (!empty($cName) && isset($nameMap[$cName])) {
                    $siswaInfo = $nameMap[$cName];
                } elseif (!empty($nisKey) && isset($nisMap[$nisKey])) {
                    $cand = $nisMap[$nisKey];
                    $candClean = $cleanString($cand['nama_siswa']);
                    if (empty($cName) || str_contains($candClean, $cName) || str_contains($cName, $candClean) || similar_text($candClean, $cName) > (strlen($cName) * 0.7)) {
                        $siswaInfo = $cand;
                    }
                }

                $finalNis = $siswaInfo ? (string) $siswaInfo['nis'] : ($nisKey ?: '-');
                $idKelas  = $siswaInfo ? $siswaInfo['id_kelas'] : null;

                if (!$idKelas && !empty($cKelas) && isset($kelasMap[$cKelas])) {
                    $idKelas = $kelasMap[$cKelas]['id_kelas'];
                }

                $nominal  = (float) ($row['total_nilai_tagihan'] ?? 0);
                $terbayar = (float) ($row['nominal_telah_dibayar'] ?? 0);
                $status   = $row['status'] ?? 'belum_bayar';
                $tglBayar = !empty($row['tanggal_dibayar']) ? date('Y-m-d H:i:s', strtotime($row['tanggal_dibayar'])) : null;

                $jenisTagihan = $row['jenis_tagihan'] ?? 'spp';
                $kodeTagihan  = $row['kode_tagihan'] ?? substr($noVa, -2) ?: '00';
                $namaTagihan  = $row['nama_va'] ?? 'Tagihan VA ' . strtoupper($jenisTagihan);

                $insertBatch[] = [
                    'id_sekolah'          => $setting->id_sekolah ?? null,
                    'trx_id'              => $trxId ?: null,
                    'nis'                 => $finalNis,
                    'id_kelas'            => $idKelas,
                    'id_tahun'            => $idTahun,
                    'id_semester'         => null,
                    'kode_tagihan'        => $kodeTagihan,
                    'jenis_tagihan'       => in_array($jenisTagihan, ['spp', 'non_spp', 'tunggakan']) ? $jenisTagihan : 'spp',
                    'nama_tagihan'        => $namaTagihan,
                    'nomor_va'            => $noVa,
                    'nominal'             => $nominal,
                    'nominal_terbayar'    => $terbayar,
                    'status'              => $status === 'sebagian' ? 'belum_bayar' : $status,
                    'tanggal_tagihan'     => $now->toDateString(),
                    'tanggal_jatuh_tempo' => null,
                    'tanggal_bayar'       => $tglBayar,
                    'keterangan'          => 'Ditarik dari BPD DIY Portal ' . $now->format('d/m/Y H:i'),
                    'created_at'          => $now,
                    'updated_at'          => $now,
                ];

                if (count($insertBatch) >= 500) {
                    TagihanPembayaran::insert($insertBatch);
                    $insertedCount += count($insertBatch);
                    $insertBatch = [];
                }
            }

            if (!empty($insertBatch)) {
                TagihanPembayaran::insert($insertBatch);
                $insertedCount += count($insertBatch);
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => "Berhasil mengosongkan tabel ({$deletedCount} data lama) dan menyimpan {$insertedCount} data tagihan terbaru dari portal BPD DIY.",
                'summary' => [
                    'deleted'  => $deletedCount,
                    'created'  => $insertedCount,
                    'inserted' => $insertedCount,
                    'skipped'  => $skippedCount,
                ],
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('[TarikDataBpdController sync] Exception: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Gagal sinkronisasi data ke database: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Simpan / Perbarui Session Cookie BPD DIY secara cepat dari halaman ini
     * Endpoint: POST /pembayaran/tarik-data/save-cookie
     */
    public function saveCookie(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'session_cookie' => 'required|string|min:4',
        ]);

        $raw = trim($validated['session_cookie']);
        $normalizedCookie = $raw;

        // Cek jika user mem-paste format JSON dari EditThisCookie
        if (str_starts_with($raw, '[') && str_ends_with($raw, ']')) {
            $json = json_decode($raw, true);
            if (is_array($json)) {
                $pairs = [];
                foreach ($json as $item) {
                    if (isset($item['name']) && isset($item['value'])) {
                        $pairs[] = $item['name'] . '=' . $item['value'];
                    }
                }
                if (!empty($pairs)) {
                    $normalizedCookie = implode('; ', $pairs);
                }
            }
        }

        $setting = SettingPembayaran::getSetting();
        $setting->update([
            'session_cookie'    => $normalizedCookie,
            'cookie_updated_at' => now(),
        ]);

        return response()->json([
            'success' => true,
            'message' => '✅ Session Cookie BPD DIY berhasil disimpan dan diperbarui.',
            'cookie_age' => 'baru saja',
        ]);
    }

    /**
     * Kosongkan seluruh data tagihan pembayaran di database lokal
     * Endpoint: POST /pembayaran/tarik-data/clear-database
     */
    public function clearDatabase(Request $request): JsonResponse
    {
        try {
            $count = TagihanPembayaran::count();

            try {
                TagihanPembayaran::truncate();
            } catch (\Throwable $e) {
                TagihanPembayaran::query()->delete();
            }

            Log::info("[TarikDataBpdController] Berhasil mengosongkan seluruh database tagihan lokal ({$count} record dihapus).");

            return response()->json([
                'success' => true,
                'message' => "Berhasil mengosongkan seluruh data tagihan ({$count} record) dari database lokal.",
                'deleted_count' => $count,
            ]);
        } catch (\Exception $e) {
            Log::error('[TarikDataBpdController clearDatabase] Error: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Gagal mengosongkan database tagihan lokal: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Tarik Otomatis Seluruh Data dari BPD DIY dan Langsung Ganti / Timpa Database Lokal
     * Endpoint: POST /pembayaran/tarik-data/auto-pull-replace
     */
    public function autoPullAndReplace(Request $request): JsonResponse
    {
        @set_time_limit(600);
        @ini_set('max_execution_time', 600);
        @ini_set('memory_limit', '512M');

        $setting = SettingPembayaran::getSetting();

        if ($setting->status_api !== 'aktif') {
            return response()->json([
                'success' => false,
                'message' => 'Status API BPD DIY dalam konfigurasi sedang Nonaktif. Silakan aktifkan di menu Setting Konfigurasi.',
            ]);
        }

        $tahun = trim((string) $request->input('tahun', date('Y')));
        $idInstitusi = $setting->id_institusi ?? '9990029';
        $prefixInstitusi = str_starts_with($idInstitusi, '999') ? $idInstitusi : ('999' . $idInstitusi);
        $vaPrefix = !empty($tahun) ? ($prefixInstitusi . $tahun) : '';

        $filters = [
            'periode_waktu'    => $request->input('periode_waktu', ''),
            'status_transaksi' => $request->input('status_transaksi', 'semua'),
            'parent_va'        => $request->input('parent_va', ''),
            'mitra'            => $request->input('mitra', $setting->id_institusi ?? ''),
            'search'           => $request->input('search', ''),
            'length'           => 0, // Ambil seluruh data
            'tahun'            => $tahun,
            'va_prefix'        => $vaPrefix,
        ];

        try {
            $service = new BpdDiyService($setting);
            $result  = $service->pullAndReplaceDatabase($filters);

            return response()->json($result, $result['success'] ? 200 : 400);
        } catch (\Exception $e) {
            Log::error('[TarikDataBpdController autoPullAndReplace] Error: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan sistem saat penarikan & penggantian database: ' . $e->getMessage(),
            ], 500);
        }
    }
}
