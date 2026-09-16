<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use App\Models\SettingPembayaran;
use App\Models\TagihanPembayaran;
use App\Models\UserSiswa;
use App\Models\Kelas;
use App\Models\TahunAjaran;

/**
 * BpdDiyService — Mengambil data tagihan Virtual Account dari portal BPD DIY
 * URL: https://va.bpddiy.co.id/admin/report/tagihan_va
 *
 * Alur (dengan session cookie dari browser):
 * 1. Gunakan session cookie yang sudah disimpan di setting_pembayaran
 * 2. Melakukan request ke halaman tagihan_va dengan filter NIS
 * 3. Parsing response HTML/JSON untuk mendapatkan data tagihan
 */
class BpdDiyService
{
    private string $baseUrl;
    private string $reportPath;
    private string $username;
    private string $password;
    private ?string $sessionCookie;
    private SettingPembayaran $setting;

    public function __construct(SettingPembayaran $setting)
    {
        $this->setting   = $setting;
        $parsed          = parse_url($setting->url_report_va ?? 'https://va.bpddiy.co.id/admin/report/tagihan_va');
        $this->baseUrl   = ($parsed['scheme'] ?? 'https') . '://' . ($parsed['host'] ?? 'va.bpddiy.co.id');
        $this->reportPath = $parsed['path'] ?? '/admin/report/tagihan_va';
        $this->username  = $setting->username_maker;
        $this->password  = $setting->password_maker;
        $this->sessionCookie = $setting->session_cookie;
    }

    /**
     * Coba login programatik ke portal BPD DIY.
     * Jika session cookie sudah tersimpan di setting dan $force = false, method ini bisa dilewati.
     *
     * @param bool $force Paksa re-login meskipun sudah ada session cookie
     * @return bool true jika berhasil
     */
    public function login(bool $force = false): bool
    {
        // Jika sudah ada cookie tersimpan dan tidak dipaksa re-login, gunakan cookie tersebut
        if (!$force && !empty($this->sessionCookie)) {
            return true;
        }

        if (empty($this->username) || empty($this->password)) {
            return false;
        }

        try {
            $loginPageUrl    = $this->baseUrl . '/login';
            $loginProcessUrl = $this->baseUrl . '/login_process';

            // 1. GET halaman login untuk mendapatkan CSRF token & cookie awal
            $getResp = Http::withOptions([
                'verify'          => false,
                'allow_redirects' => true,
            ])->withHeaders([
                'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/122.0.0.0 Safari/537.36',
                'Accept'     => 'text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8',
            ])->timeout(15)->get($loginPageUrl);

            $initialCookie = $this->extractSessionCookieFromHeaders($getResp->headers());
            $html          = $getResp->body();
            $token         = $this->extractCsrfToken($html);

            // 2. POST login credentials ke endpoint login_process
            $postResp = Http::withOptions([
                'verify'          => false,
                'allow_redirects' => false,
            ])->withHeaders([
                'Referer'    => $loginPageUrl,
                'Origin'     => $this->baseUrl,
                'Cookie'     => $initialCookie,
                'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/122.0.0.0 Safari/537.36',
            ])->asForm()->timeout(20)->post($loginProcessUrl, [
                '_token'   => $token,
                'username' => $this->username,
                'password' => $this->password,
            ]);

            $location = (string) ($postResp->header('Location') ?? '');
            $body     = $postResp->body();
            $status   = $postResp->status();

            if (
                $status === 302 ||
                str_contains($location, '/admin') ||
                str_contains($body, '/admin/dashboard') ||
                (!str_contains($body, 'password salah') && !str_contains($body, 'Login gagal') && !str_contains($body, 'name="username"'))
            ) {
                $postCookies = $this->extractSessionCookieFromHeaders($postResp->headers());
                $mergedCookies = $this->mergeCookieStrings($initialCookie, $postCookies);
                $this->sessionCookie = $mergedCookies ?: $postCookies ?: $initialCookie;

                // Simpan cookie baru ke database SettingPembayaran
                SettingPembayaran::getSetting()->update([
                    'session_cookie'    => $this->sessionCookie,
                    'cookie_updated_at' => now(),
                ]);

                Log::info('[BPD DIY] Auto-login berhasil. Session cookie diperbarui.');
                return true;
            }

            return false;
        } catch (\Exception $e) {
            Log::error('[BPD DIY] Login exception: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Tarik seluruh data laporan Tagihan VA dari portal BPD DIY berdasarkan filter pencarian.
     * Endpoint DataTables resmi: https://va.bpddiy.co.id/admin/report/datatable/tagihan-va
     *
     * @param array $filters [
     *   'periode_waktu'   => string (e.g. '2026-07-01 to 2026-08-31' atau '01/07/2026 - 31/08/2026'),
     *   'status_transaksi'=> string (e.g. 'semua', 'lunas', 'belum_bayar'),
     *   'parent_va'       => string,
     *   'mitra'           => string,
     *   'search'          => string,
     *   'length'          => int,
     * ]
     * @param bool $isRetry Apakah ini panggilan retry setelah auto-login
     * @return array
     */
    public function fetchReportTagihanVa(array $filters = [], bool $isRetry = false): array
    {
        // Jika belum ada session cookie, coba lakukan login otomatis terlebih dahulu
        if (empty($this->sessionCookie)) {
            $loginSuccess = $this->login(true);
            if (!$loginSuccess) {
                return [
                    'success'     => false,
                    'data'        => [],
                    'total'       => 0,
                    'need_cookie' => true,
                    'message'     => 'Session cookie BPD DIY belum diisi atau akun maker belum terkonfigurasi. Silakan simpan cookie BPD DIY atau atur username & password di Setting Konfigurasi.',
                    'raw'         => '',
                ];
            }
        }

        $reportPageUrl = $this->baseUrl . $this->reportPath; // https://va.bpddiy.co.id/admin/report/tagihan_va
        $datatableUrl  = $this->baseUrl . '/admin/report/datatable/tagihan-va';

        $searchVal  = trim((string) ($filters['search'] ?? ''));
        $pageSize   = 500;   // Jumlah record per request ke BPD DIY
        $maxRecords = (int) ($filters['length'] ?? 0); // 0 = ambil semua

        $baseHeaders = [
            'User-Agent'       => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/122.0.0.0 Safari/537.36',
            'Accept-Language'  => 'id-ID,id;q=0.9,en-US;q=0.8,en;q=0.7',
            'Referer'          => $reportPageUrl,
            'Cookie'           => $this->sessionCookie,
        ];

        // ── 1. Ambil CSRF Token & Mitra Code dari halaman report ──
        $csrfToken = '';
        $rawMitra  = $filters['mitra'] ?? '0029';
        $mitraCode = (strlen($rawMitra) === 7 && str_starts_with($rawMitra, '999')) ? substr($rawMitra, 3) : $rawMitra;

        try {
            $pageResp = Http::withOptions(['verify' => false, 'allow_redirects' => true])
                ->withHeaders(array_merge($baseHeaders, [
                    'Accept' => 'text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8',
                ]))
                ->timeout(20)
                ->get($reportPageUrl);

            $pageHtml = $pageResp->body();

            // Cek jika session expired / redirect ke login
            if (
                str_contains($pageHtml, 'name="username"') ||
                str_contains($pageHtml, '/admin/auth/login') ||
                str_contains($pageHtml, 'Silakan login') ||
                $pageResp->status() === 401 ||
                $pageResp->status() === 419
            ) {
                // Coba auto-login jika belum pernah retry
                if (!$isRetry && $this->login(true)) {
                    return $this->fetchReportTagihanVa($filters, true);
                }

                return [
                    'success'     => false,
                    'data'        => [],
                    'total'       => 0,
                    'need_cookie' => true,
                    'message'     => '⚠️ Session Cookie BPD DIY sudah kadaluarsa. Silakan perbarui cookie atau login ulang di portal BPD DIY.',
                    'raw'         => '',
                ];
            }

            $csrfToken = $this->extractCsrfToken($pageHtml);

            if (preg_match('/<input[^>]+(?:id|name)="mitraCode"[^>]+value="([^"]+)"/i', $pageHtml, $mMitra)) {
                $mitraCode = $mMitra[1];
            }
        } catch (\Exception $e) {
            Log::warning('[BPD DIY fetchReportTagihanVa] Gagal mengambil halaman utama: ' . $e->getMessage());
        }

        // Jika CSRF token kosong, coba auto-login dan retry
        if (empty($csrfToken) && !$isRetry) {
            if ($this->login(true)) {
                return $this->fetchReportTagihanVa($filters, true);
            }
        }

        // ── 2. Siapkan parameter filter untuk endpoint DataTables BPD DIY ──
        $startDate = '';
        $endDate   = '';
        if (!empty($filters['periode_waktu'])) {
            $rawPeriode = $filters['periode_waktu'];
            if (str_contains($rawPeriode, 'to')) {
                $parts = explode('to', $rawPeriode);
                $startDate = trim($parts[0]);
                $endDate   = trim($parts[1]);
            } elseif (str_contains($rawPeriode, '-')) {
                $parts = explode('-', $rawPeriode);
                if (count($parts) === 2 && strlen(trim($parts[0])) > 5) {
                    $startDate = trim($parts[0]);
                    $endDate   = trim($parts[1]);
                } else {
                    $startDate = trim($rawPeriode);
                    $endDate   = trim($rawPeriode);
                }
            } else {
                $startDate = trim($rawPeriode);
                $endDate   = trim($rawPeriode);
            }
        }

        $statusTransaksi = $filters['status_transaksi'] ?? '';
        if ($statusTransaksi === 'semua' || $statusTransaksi === '') {
            $statusTransaksi = '';
        } elseif ($statusTransaksi === 'lunas' || $statusTransaksi === '1' || $statusTransaksi === 'terbayar') {
            $statusTransaksi = '1';
        } elseif ($statusTransaksi === 'belum_bayar' || $statusTransaksi === '0') {
            $statusTransaksi = '0';
        }

        $parentVa = $filters['parent_va'] ?? '';

        $cols = [
            ['data' => null, 'name' => '', 'searchable' => 'false', 'orderable' => 'false', 'search' => ['value' => '', 'regex' => 'false']],
            ['data' => 'trxId', 'name' => 'trxId', 'searchable' => 'true', 'orderable' => 'true', 'search' => ['value' => '', 'regex' => 'false']],
            ['data' => 'virtualAccountNo', 'name' => 'virtualAccountNo', 'searchable' => 'true', 'orderable' => 'true', 'search' => ['value' => '', 'regex' => 'false']],
            ['data' => 'virtualAccountName', 'name' => 'virtualAccountName', 'searchable' => 'true', 'orderable' => 'true', 'search' => ['value' => '', 'regex' => 'false']],
            ['data' => 'virtualAccountIdentityNo', 'name' => 'virtualAccountIdentityNo', 'searchable' => 'true', 'orderable' => 'true', 'search' => ['value' => '', 'regex' => 'false']],
            ['data' => 'totalAmountValue', 'name' => 'totalAmountValue', 'searchable' => 'true', 'orderable' => 'true', 'search' => ['value' => '', 'regex' => 'false']],
            ['data' => 'id', 'name' => 'id', 'searchable' => 'false', 'orderable' => 'false', 'search' => ['value' => '', 'regex' => 'false']],
            ['data' => 'remainingPayment', 'name' => 'remainingPayment', 'searchable' => 'true', 'orderable' => 'true', 'search' => ['value' => '', 'regex' => 'false']],
            ['data' => 'paidAt', 'name' => 'paidAt', 'searchable' => 'true', 'orderable' => 'true', 'search' => ['value' => '', 'regex' => 'false']],
            ['data' => 'expiredDate', 'name' => 'expiredDate', 'searchable' => 'true', 'orderable' => 'true', 'search' => ['value' => '', 'regex' => 'false']],
            ['data' => 'statusTransaction', 'name' => 'statusTransaction', 'searchable' => 'true', 'orderable' => 'true', 'search' => ['value' => '', 'regex' => 'false']],
        ];

        // ── 3. Header POST untuk DataTables ──
        $postHeaders = array_merge($baseHeaders, [
            'Accept'           => 'application/json, text/javascript, */*; q=0.01',
            'X-Requested-With' => 'XMLHttpRequest',
            'Origin'           => $this->baseUrl,
        ]);
        if (!empty($csrfToken)) {
            $postHeaders['X-CSRF-TOKEN'] = $csrfToken;
        }

        $searchParams = [
            'value'            => $searchVal,
            'regex'            => 'false',
            'mitraCode'        => $mitraCode,
            'startDate'        => $startDate,
            'endDate'          => $endDate,
            'status_transaksi' => $statusTransaksi,
            'parent'           => '',
        ];

        $order = [
            ['column' => '2', 'dir' => 'asc'] // Urutkan berdasarkan virtualAccountNo agar pagination deterministik & tidak ada baris yang terlewat
        ];

        // ── 4. Ambil halaman pertama untuk mengetahui total record ──
        $allRows    = [];
        $totalRemote = 0;
        $draw        = 1;

        try {
            $firstPayload = [
                'draw'    => $draw,
                'columns' => $cols,
                'order'   => $order,
                'start'   => 0,
                'length'  => $pageSize,
                'search'  => $searchParams,
            ];

            $firstResp = Http::withOptions(['verify' => false, 'allow_redirects' => true])
                ->withHeaders($postHeaders)
                ->asForm()
                ->timeout(45)
                ->post($datatableUrl, $firstPayload);

            $firstBody   = $firstResp->body();
            $firstStatus = $firstResp->status();

            if (
                $firstStatus === 419 ||
                $firstStatus === 401 ||
                str_contains($firstBody, '"login"') ||
                str_contains($firstBody, 'Unauthenticated') ||
                str_contains($firstBody, 'CSRF token mismatch')
            ) {
                if (!$isRetry && $this->login(true)) {
                    return $this->fetchReportTagihanVa($filters, true);
                }
                return [
                    'success'     => false,
                    'data'        => [],
                    'total'       => 0,
                    'need_cookie' => true,
                    'message'     => '⚠️ Session Cookie BPD DIY sudah kadaluarsa. Silakan lakukan Tes Auto-Login atau perbarui cookie.',
                    'raw'         => '',
                ];
            }

            $firstJson = null;
            try { $firstJson = $firstResp->json(); } catch (\Throwable $e) {}

            if (!$firstJson || !isset($firstJson['data'])) {
                return [
                    'success' => true,
                    'data'    => [],
                    'total'   => 0,
                    'message' => 'Tidak ada data tagihan yang ditemukan untuk filter ini di portal BPD DIY.',
                    'raw'     => '',
                ];
            }

            $totalRemote = (int) ($firstJson['recordsFiltered'] ?? count($firstJson['data']));
            $allRows     = $firstJson['data'] ?? [];

        } catch (\Exception $e) {
            Log::warning('[BPD DIY fetchReportTagihanVa] Halaman pertama gagal: ' . $e->getMessage());
            if (!$isRetry && $this->login(true)) {
                return $this->fetchReportTagihanVa($filters, true);
            }
            return [
                'success' => true,
                'data'    => [],
                'total'   => 0,
                'message' => 'Gagal menghubungi portal BPD DIY: ' . $e->getMessage(),
                'raw'     => '',
            ];
        }

        // ── 5. Ambil halaman berikutnya sampai semua data diperoleh ──
        $effectiveMax = ($maxRecords > 0) ? min($maxRecords, $totalRemote) : $totalRemote;
        $start        = $pageSize;
        $draw         = 2;

        while ($start < $effectiveMax) {
            $batchSize = min($pageSize, $effectiveMax - $start);
            try {
                $batchPayload = [
                    'draw'    => $draw++,
                    'columns' => $cols,
                    'order'   => $order,
                    'start'   => $start,
                    'length'  => $batchSize,
                    'search'  => $searchParams,
                ];

                $batchResp = Http::withOptions(['verify' => false, 'allow_redirects' => true])
                    ->withHeaders($postHeaders)
                    ->asForm()
                    ->timeout(45)
                    ->post($datatableUrl, $batchPayload);

                if ($batchResp->status() === 419 || $batchResp->status() === 401) {
                    Log::warning('[BPD DIY] Session expired saat pagination start=' . $start . '. Berhenti.');
                    break;
                }

                $batchJson = null;
                try { $batchJson = $batchResp->json(); } catch (\Throwable $e) {}

                if ($batchJson && isset($batchJson['data']) && count($batchJson['data']) > 0) {
                    $allRows = array_merge($allRows, $batchJson['data']);
                } else {
                    break; // Tidak ada data lagi
                }

            } catch (\Exception $e) {
                Log::warning('[BPD DIY fetchReportTagihanVa] Batch gagal di start=' . $start . ': ' . $e->getMessage());
                break;
            }

            $start += $batchSize;
        }

        // ── 6. Parse & filter semua data yang terkumpul ──
        $vaPrefix = trim((string) ($filters['va_prefix'] ?? ''));
        $parsed   = $this->parseReportRows($allRows, '', $vaPrefix);

        if (!empty($parentVa)) {
            $parentUpper = strtoupper($parentVa);
            $parsed = array_values(array_filter($parsed, function ($r) use ($parentUpper) {
                if ($parentUpper === 'SPP') {
                    return ($r['jenis_tagihan'] === 'spp' || ($r['kode_tagihan'] ?? '') === '00');
                } elseif ($parentUpper === 'NON_SPP' || $parentUpper === 'NON-SPP') {
                    return ($r['jenis_tagihan'] === 'non_spp' || ($r['kode_tagihan'] ?? '') === '01');
                } elseif ($parentUpper === 'TUNGGAKAN') {
                    return ($r['jenis_tagihan'] === 'tunggakan' || ($r['kode_tagihan'] ?? '') === '02');
                }
                return true;
            }));
        }

        return [
            'success' => true,
            'data'    => $parsed,
            'total'   => $totalRemote,
            'message' => count($parsed) . ' dari ' . $totalRemote . ' data berhasil ditarik dari portal BPD DIY.',
            'raw'     => '',
        ];
    }

    /**
     * Ambil data tagihan dari portal BPD DIY berdasarkan NIS.
     *
     * @param  string $nis       NIS / nomor identitas VA
     * @param  string $vaKode    Kode VA filter (kosong = semua)
     * @return array
     */
    public function fetchTagihanByNis(string $nis, string $vaKode = ''): array
    {
        $result = $this->fetchReportTagihanVa([
            'search'           => $nis,
            'status_transaksi' => 'semua',
            'length'           => 100,
        ]);

        if (!$result['success']) {
            return $result;
        }

        $filtered = [];
        foreach ($result['data'] as $row) {
            if ($vaKode && !empty($row['nomor_va']) && !str_ends_with((string) $row['nomor_va'], $vaKode)) {
                continue;
            }
            $filtered[] = $row;
        }

        return [
            'success' => true,
            'data'    => $filtered,
            'total'   => count($filtered),
            'message' => count($filtered) . ' data tagihan ditemukan untuk NIS ' . $nis,
            'raw'     => '',
        ];
    }

    /**
     * Parsing baris-baris data dari JSON response BPD DIY Report Tagihan VA.
     */
    private function parseReportRows(array $rows, string $searchKeyword = '', string $vaPrefix = ''): array
    {
        $parsed = [];
        $searchLower = strtolower($searchKeyword);
        $vaPrefixClean = preg_replace('/\s+/', '', $vaPrefix);

        foreach ($rows as $index => $row) {
            $trxId    = $this->getValue($row, ['trxId', 'trx_id', 'TRX_ID', 'id', 'transaction_id', 0, 1]);
            $noVa     = $this->getValue($row, ['virtualAccountNo', 'no_va', 'nomor_va', 'va_number', 'NO_VA', 1, 2]);
            $namaVa   = $this->getValue($row, ['virtualAccountName', 'nama_va', 'name', 'NAMA_VA', 'nama_pelanggan', 2, 3]);
            $nomorId  = $this->getValue($row, ['virtualAccountIdentityNo', 'nomor_identitas', 'identity_number', 'nis', 'NOMOR_IDENTITAS', 3, 4]);
            $address  = $this->getValue($row, ['virtualAccountAddress', 'address', 'alamat', 'kelas']);
            $totalVal = $this->getValue($row, ['totalAmountValue', 'total_nilai_tagihan', 'nominal', 'amount', 'nilai', 'tagihan', 'total_tagihan', 4, 5]);
            $sisaVal  = $this->getValue($row, ['remainingPayment', 'sisa_pembayaran', 'sisa', 'remaining', 6, 7]);
            $terbayar = $this->getValue($row, ['nominal_telah_dibayar', 'terbayar', 'paid', 'bayar', 'nominal_bayar', 5, 6]);
            $tglBayar = $this->getValue($row, ['paidAt', 'tanggal_dibayar', 'tgl_bayar', 'tanggal_bayar', 'paid_at', 'pay_date', 7, 8]);
            $statusRaw = $this->getValue($row, ['statusTransaction', 'status', 'status_transaksi', 'payment_status', 8, 9]);

            $noVaClean   = preg_replace('/\s+/', '', (string) $noVa);
            $trxIdClean  = preg_replace('/\s+/', '', (string) $trxId);
            $nomorIdClean = preg_replace('/\s+/', '', (string) $nomorId);
            $namaVaStr   = trim((string) $namaVa);

            // Filter awalan nomor VA (misal: 99900292026)
            if (!empty($vaPrefixClean)) {
                if (!str_starts_with($noVaClean, $vaPrefixClean)) {
                    continue;
                }
            }

            // Filter search jika ada
            if ($searchLower !== '') {
                $combined = strtolower($trxIdClean . ' ' . $noVaClean . ' ' . $namaVaStr . ' ' . $nomorIdClean . ' ' . (string)$address);
                if (!str_contains($combined, $searchLower)) {
                    continue;
                }
            }

            // Ekstrak nama siswa & kelas
            $namaSiswa = $namaVaStr;
            $kelas     = !empty($address) ? trim((string)$address) : '-';

            if (preg_match('/^(.*?)\s*[\-\(\[\/]?\s*((?:10|11|12|X|XI|XII)\b.*?)$/i', $namaVaStr, $matches)) {
                $namaSiswa = trim($matches[1], " /-\t\n\r\0\x0B()[]");
                $extractedKelas = trim($matches[2], " /-\t\n\r\0\x0B()[]");
                if ($kelas === '-' || empty($kelas)) {
                    $kelas = $extractedKelas;
                }
            } elseif (str_contains($namaVaStr, '/')) {
                $parts = explode('/', $namaVaStr, 2);
                $namaSiswa = trim($parts[0], " /-\t\n\r\0\x0B()[]");
                if ($kelas === '-' || empty($kelas)) {
                    $kelas = trim($parts[1], " /-\t\n\r\0\x0B()[]");
                }
            }
            $namaSiswa = trim($namaSiswa, " /-\t\n\r\0\x0B()[]");

            $nominalFloat  = $this->parseNominal($totalVal);
            $sisaFloat     = $sisaVal !== null ? $this->parseNominal($sisaVal) : 0;
            $terbayarFloat = $terbayar !== null ? $this->parseNominal($terbayar) : max(0, $nominalFloat - $sisaFloat);

            // Jika sisaVal belum diset tapi terbayar diset
            if ($sisaVal === null && $nominalFloat > 0) {
                $sisaFloat = max(0, $nominalFloat - $terbayarFloat);
            }

            // Normalisasi format tanggal bayar
            $tglBayarFormatted = null;
            if ($tglBayar) {
                try {
                    $tglBayarFormatted = date('d-m-Y H:i:s', strtotime((string)$tglBayar));
                } catch (\Throwable $e) {
                    $tglBayarFormatted = (string) $tglBayar;
                }
            }

            // Normalisasi status transaksi
            $status = $this->normalizeStatusReport($statusRaw, $nominalFloat, $terbayarFloat, $sisaFloat);

            $parsed[] = [
                'index'                 => $index + 1,
                'trx_id'                => $trxIdClean ?: ('TRX-' . ($index + 1)),
                'nomor_va'              => $noVaClean,
                'nama_va'               => $namaVaStr,
                'nama_siswa'            => $namaSiswa,
                'kelas'                 => $kelas,
                'nomor_identitas'       => $nomorIdClean,
                'total_nilai_tagihan'   => $nominalFloat,
                'nominal_telah_dibayar' => $terbayarFloat,
                'sisa_pembayaran'       => $sisaFloat,
                'tanggal_dibayar'       => $tglBayarFormatted,
                'status'                => $status,
                'jenis_tagihan'         => $this->detectJenisTagihan($noVaClean),
                'kode_tagihan'          => substr($noVaClean, -2) ?: '00',
            ];
        }

        return $parsed;
    }

    /**
     * Parsing tabel HTML dari portal BPD DIY jika AJAX tidak mengembalikan JSON.
     */
    private function parseHtmlTableReport(string $html, string $searchKeyword = '', string $vaPrefix = ''): array
    {
        $parsed = [];
        $searchLower = strtolower($searchKeyword);
        $vaPrefixClean = preg_replace('/\s+/', '', $vaPrefix);
        preg_match_all('/<tr[^>]*>(.*?)<\/tr>/is', $html, $rows);

        $rowIdx = 0;
        foreach ($rows[1] ?? [] as $rowHtml) {
            preg_match_all('/<td[^>]*>(.*?)<\/td>/is', $rowHtml, $cols);
            $cells = array_values(array_map(fn($c) => trim(strip_tags($c)), $cols[1] ?? []));

            if (count($cells) < 4) continue;

            // Cek apakah cell pertama adalah expand / icon
            $offset = 0;
            if (strlen($cells[0]) <= 2 || $cells[0] === 'v' || $cells[0] === '+') {
                $offset = 1;
            }

            $trxId    = preg_replace('/\s+/', '', $cells[$offset] ?? '');
            $noVa     = preg_replace('/\s+/', '', $cells[$offset + 1] ?? '');
            $namaVa   = trim($cells[$offset + 2] ?? '');
            $nomorId  = preg_replace('/\s+/', '', $cells[$offset + 3] ?? '');
            $nominal  = $this->parseNominal($cells[$offset + 4] ?? 0);
            $terbayar = $this->parseNominal($cells[$offset + 5] ?? 0);
            $sisa     = isset($cells[$offset + 6]) ? $this->parseNominal($cells[$offset + 6]) : max(0, $nominal - $terbayar);
            $tglBayar = $cells[$offset + 7] ?? null;
            $statusRaw = $cells[$offset + 8] ?? '';

            if (empty($noVa) && empty($trxId)) continue;

            // Filter awalan nomor VA (misal: 99900292026)
            if (!empty($vaPrefixClean)) {
                if (!str_starts_with($noVa, $vaPrefixClean)) {
                    continue;
                }
            }

            // Search filter
            if ($searchLower !== '') {
                $combined = strtolower($trxId . ' ' . $noVa . ' ' . $namaVa . ' ' . $nomorId);
                if (!str_contains($combined, $searchLower)) {
                    continue;
                }
            }

            $namaSiswa = $namaVa;
            $kelas     = '-';
            if (preg_match('/^(.*?)\s*[\-\(\[\/]?\s*((?:10|11|12|X|XI|XII)\b.*?)$/i', $namaVa, $matches)) {
                $namaSiswa = trim($matches[1], " /-\t\n\r\0\x0B()[]");
                $kelas     = trim($matches[2], " /-\t\n\r\0\x0B()[]");
            } elseif (str_contains($namaVa, '/')) {
                $parts = explode('/', $namaVa, 2);
                $namaSiswa = trim($parts[0], " /-\t\n\r\0\x0B()[]");
                $kelas     = trim($parts[1], " /-\t\n\r\0\x0B()[]");
            }
            $namaSiswa = trim($namaSiswa, " /-\t\n\r\0\x0B()[]");

            $status = $this->normalizeStatusReport($statusRaw, $nominal, $terbayar, $sisa);

            $rowIdx++;
            $parsed[] = [
                'index'                 => $rowIdx,
                'trx_id'                => $trxId ?: ('TRX-' . $rowIdx),
                'nomor_va'              => $noVa,
                'nama_va'               => $namaVa,
                'nama_siswa'            => $namaSiswa,
                'kelas'                 => $kelas,
                'nomor_identitas'       => $nomorId,
                'total_nilai_tagihan'   => $nominal,
                'nominal_telah_dibayar' => $terbayar,
                'sisa_pembayaran'       => $sisa,
                'tanggal_dibayar'       => $tglBayar,
                'status'                => $status,
                'jenis_tagihan'         => $this->detectJenisTagihan($noVa),
                'kode_tagihan'          => substr($noVa, -2) ?: '00',
            ];
        }

        return $parsed;
    }

    private function normalizeStatusReport(mixed $rawStatus, float $nominal, float $terbayar, float $sisa): string
    {
        $raw = strtolower(trim((string) $rawStatus));
        if (in_array($raw, ['lunas', 'paid', 'success', 'sukses', 'settlement', 'berhasil'])) {
            return 'lunas';
        }
        if (in_array($raw, ['batal', 'cancel', 'cancelled', 'expired'])) {
            return 'batal';
        }
        if ($sisa <= 0 && $nominal > 0 && $terbayar >= $nominal) {
            return 'lunas';
        }
        if ($terbayar > 0 && $sisa > 0) {
            return 'sebagian';
        }
        return 'belum_bayar';
    }

    private function parseJsonResponse(?array $json, string $nis, string $vaKode): array
    {
        $rows = $json['data'] ?? $json['rows'] ?? $json['result'] ?? [];

        if (empty($rows)) {
            return [
                'success' => true,
                'data'    => [],
                'message' => "Tidak ada data tagihan untuk NIS {$nis} di portal BPD DIY.",
                'raw'     => json_encode($json),
            ];
        }

        $parsed = [];
        foreach ($rows as $row) {
            $trxId    = $this->getValue($row, ['trx_id', 'TRX_ID', 'id', 0]);
            $noVa     = $this->getValue($row, ['no_va', 'nomor_va', 'va_number', 'NO_VA', 1]);
            $namaVa   = $this->getValue($row, ['nama_va', 'name', 'NAMA_VA', 2]);
            $nomorId  = $this->getValue($row, ['nomor_identitas', 'identity_number', 'nis', 3]);
            $nominal  = $this->getValue($row, ['nominal', 'amount', 'nilai', 'tagihan', 4]);
            $terbayar = $this->getValue($row, ['terbayar', 'paid', 'bayar', 5]);
            $status   = $this->getValue($row, ['status', 6]);
            $tglBayar = $this->getValue($row, ['tgl_bayar', 'tanggal_bayar', 'paid_at', 7]);

            if ($vaKode && $noVa && !str_ends_with((string) $noVa, $vaKode)) {
                continue;
            }

            $parsed[] = [
                'trx_id'           => preg_replace('/\s+/', '', (string) $trxId),
                'nomor_va'         => preg_replace('/\s+/', '', (string) $noVa),
                'nama_va'          => $namaVa,
                'nomor_identitas'  => preg_replace('/\s+/', '', (string) $nomorId),
                'nominal'          => $this->parseNominal($nominal),
                'nominal_terbayar' => $this->parseNominal($terbayar),
                'status'           => $this->normalizeStatus($status),
                'tanggal_bayar'    => $tglBayar,
                'jenis_tagihan'    => $this->detectJenisTagihan((string) $noVa),
                'kode_tagihan'     => substr(preg_replace('/\s+/', '', (string) $noVa), -2),
            ];
        }

        return [
            'success' => true,
            'data'    => $parsed,
            'message' => count($parsed) . ' data tagihan ditemukan untuk NIS ' . $nis,
            'raw'     => '',
        ];
    }

    private function parseHtmlTable(string $html, string $nis, string $vaKode): array
    {
        $parsed = [];
        preg_match_all('/<tr[^>]*>(.*?)<\/tr>/is', $html, $rows);

        foreach ($rows[1] ?? [] as $rowHtml) {
            preg_match_all('/<td[^>]*>(.*?)<\/td>/is', $rowHtml, $cols);
            $cells = array_values(array_map(fn($c) => trim(strip_tags($c)), $cols[1] ?? []));

            if (count($cells) < 3) continue;

            $trxId   = preg_replace('/\s+/', '', $cells[0] ?? '');
            $noVa    = preg_replace('/\s+/', '', $cells[1] ?? '');
            $namaVa  = $cells[2] ?? '';
            $nomorId = preg_replace('/\s+/', '', $cells[3] ?? '');

            // Filter NIS
            if ($nis && !str_contains($nomorId, $nis) && !str_contains($noVa, $nis)) continue;
            if ($vaKode && $noVa && !str_ends_with($noVa, $vaKode)) continue;
            if (!preg_match('/^\d{10,}$/', $noVa)) continue;

            $parsed[] = [
                'trx_id'           => $trxId,
                'nomor_va'         => $noVa,
                'nama_va'          => $namaVa,
                'nomor_identitas'  => $nomorId,
                'nominal'          => $this->parseNominal($cells[4] ?? 0),
                'nominal_terbayar' => $this->parseNominal($cells[5] ?? 0),
                'status'           => $this->normalizeStatus($cells[6] ?? ''),
                'tanggal_bayar'    => null,
                'jenis_tagihan'    => $this->detectJenisTagihan($noVa),
                'kode_tagihan'     => substr($noVa, -2),
            ];
        }

        if (empty($parsed)) {
            return [
                'success' => true,
                'data'    => [],
                'message' => "Tidak ada data tagihan untuk NIS {$nis} ditemukan di halaman portal BPD DIY.",
                'raw'     => '',
            ];
        }

        return [
            'success' => true,
            'data'    => $parsed,
            'message' => count($parsed) . ' data ditemukan dari halaman portal BPD DIY.',
            'raw'     => '',
        ];
    }

    private function detectJenisTagihan(string $noVa): string
    {
        $setting = SettingPembayaran::getSetting();
        $kode    = substr(trim($noVa), -2);
        if ($kode === $setting->kode_spp)       return 'spp';
        if ($kode === $setting->kode_non_spp)   return 'non_spp';
        if ($kode === $setting->kode_tunggakan) return 'tunggakan';
        return 'spp';
    }

    private function normalizeStatus(mixed $s): string
    {
        $s = strtolower(trim((string) $s));
        if (in_array($s, ['lunas', 'paid', 'success', 'sukses', 'berhasil'])) return 'lunas';
        if (in_array($s, ['batal', 'cancel', 'cancelled']))                   return 'batal';
        return 'belum_bayar';
    }

    private function parseNominal(mixed $val): float
    {
        $str = preg_replace('/[^0-9]/', '', (string) $val);
        return (float) $str;
    }

    private function getValue(mixed $row, array $keys): mixed
    {
        foreach ($keys as $key) {
            if (is_array($row)  && array_key_exists($key, $row)) return $row[$key];
            if (is_object($row) && isset($row->$key))             return $row->$key;
        }
        return null;
    }

    private function extractCsrfToken(string $html): string
    {
        if (preg_match('/<meta name="csrf-token" content="([^"]+)"/', $html, $m)) return $m[1];
        if (preg_match('/<input[^>]+name="_token"[^>]+value="([^"]+)"/', $html, $m)) return $m[1];
        if (preg_match('/<input[^>]+value="([^"]+)"[^>]+name="_token"/', $html, $m)) return $m[1];
        return '';
    }

    private function extractSessionCookieFromHeaders(array $headers): string
    {
        $setCookies = $headers['Set-Cookie'] ?? $headers['set-cookie'] ?? [];
        if (is_string($setCookies)) $setCookies = [$setCookies];
        $result = [];
        foreach ($setCookies as $c) {
            $kv = trim(explode(';', $c)[0]);
            if ($kv && str_contains($kv, '=')) $result[] = $kv;
        }
        return implode('; ', $result);
    }

    /**
     * Menggabungkan dua cookie string (menimpa key yang sama).
     */
    private function mergeCookieStrings(string $oldCookie, string $newCookie): string
    {
        $cookieMap = [];
        foreach (explode(';', $oldCookie) as $part) {
            $kv = trim($part);
            if (str_contains($kv, '=')) {
                [$k, $v] = explode('=', $kv, 2);
                $cookieMap[trim($k)] = trim($v);
            }
        }
        foreach (explode(';', $newCookie) as $part) {
            $kv = trim($part);
            if (str_contains($kv, '=')) {
                [$k, $v] = explode('=', $kv, 2);
                $cookieMap[trim($k)] = trim($v);
            }
        }
        $pairs = [];
        foreach ($cookieMap as $k => $v) {
            $pairs[] = "{$k}={$v}";
        }
        return implode('; ', $pairs);
    }

    /**
     * Membaca file Excel / CSV hasil Export dari portal BPD DIY (tagihan_va).
     *
     * @param string $filePath Path file di filesystem lokal
     * @return array
     */
    public function parseSpreadsheetFile(string $filePath, string $vaPrefix = ''): array
    {
        if (!file_exists($filePath)) {
            return [
                'success' => false,
                'data'    => [],
                'total'   => 0,
                'message' => 'File tidak ditemukan di server.',
            ];
        }

        try {
            $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($filePath);
            $sheet       = $spreadsheet->getActiveSheet();
            $rows        = $sheet->toArray(null, true, true, true);

            if (empty($rows) || count($rows) < 2) {
                return [
                    'success' => false,
                    'data'    => [],
                    'total'   => 0,
                    'message' => 'File Excel / CSV kosong atau tidak memiliki data.',
                ];
            }

            // Cari baris header
            $headerRowIdx = null;
            $colMap       = [];

            foreach ($rows as $idx => $row) {
                $rowLower = array_map(fn($v) => strtolower(trim((string)$v)), $row);
                $hasVa    = false;
                $hasName  = false;

                foreach ($rowLower as $cKey => $cVal) {
                    if (str_contains($cVal, 'va') || str_contains($cVal, 'virtual account') || str_contains($cVal, 'no va')) {
                        $hasVa = true;
                    }
                    if (str_contains($cVal, 'nama') || str_contains($cVal, 'siswa') || str_contains($cVal, 'name')) {
                        $hasName = true;
                    }
                }

                if ($hasVa && $hasName) {
                    $headerRowIdx = $idx;
                    foreach ($rowLower as $cKey => $cVal) {
                        if (str_contains($cVal, 'trx') || str_contains($cVal, 'transaksi') || str_contains($cVal, 'id transaksi')) {
                            $colMap['trx_id'] = $cKey;
                        } elseif (str_contains($cVal, 'no va') || str_contains($cVal, 'virtual account no') || (str_contains($cVal, 'va') && !str_contains($cVal, 'nama'))) {
                            $colMap['no_va'] = $cKey;
                        } elseif (str_contains($cVal, 'nama va') || str_contains($cVal, 'virtual account name') || str_contains($cVal, 'nama')) {
                            $colMap['nama_va'] = $cKey;
                        } elseif (str_contains($cVal, 'identitas') || str_contains($cVal, 'nis') || str_contains($cVal, 'identity')) {
                            $colMap['nomor_identitas'] = $cKey;
                        } elseif (str_contains($cVal, 'total') || str_contains($cVal, 'nilai') || str_contains($cVal, 'tagihan') || str_contains($cVal, 'amount')) {
                            if (!isset($colMap['total'])) $colMap['total'] = $cKey;
                        } elseif (str_contains($cVal, 'dibayar') || str_contains($cVal, 'terbayar') || str_contains($cVal, 'paid')) {
                            $colMap['terbayar'] = $cKey;
                        } elseif (str_contains($cVal, 'sisa') || str_contains($cVal, 'remaining')) {
                            $colMap['sisa'] = $cKey;
                        } elseif (str_contains($cVal, 'tgl bayar') || str_contains($cVal, 'tanggal bayar') || str_contains($cVal, 'paid at') || str_contains($cVal, 'paid_at')) {
                            $colMap['tgl_bayar'] = $cKey;
                        } elseif (str_contains($cVal, 'status') || str_contains($cVal, 'keterangan')) {
                            $colMap['status'] = $cKey;
                        } elseif (str_contains($cVal, 'alamat') || str_contains($cVal, 'kelas') || str_contains($cVal, 'address')) {
                            $colMap['kelas'] = $cKey;
                        }
                    }
                    break;
                }
            }

            // Fallback default kolom jika header tidak terdeteksi secara otomatis (urutan kolom standar export BPD DIY)
            if ($headerRowIdx === null) {
                $headerRowIdx = 1;
                $colMap = [
                    'trx_id'          => 'B',
                    'no_va'           => 'C',
                    'nama_va'         => 'D',
                    'nomor_identitas' => 'E',
                    'total'           => 'F',
                    'terbayar'        => 'G',
                    'sisa'            => 'H',
                    'tgl_bayar'       => 'I',
                    'status'          => 'K',
                ];
            }

            $parsed = [];
            $dataIdx = 0;
            $vaPrefixClean = preg_replace('/\s+/', '', $vaPrefix);

            foreach ($rows as $idx => $row) {
                if ($idx <= $headerRowIdx) continue;

                $trxId    = trim((string)($row[$colMap['trx_id'] ?? 'B'] ?? ''));
                $noVa     = preg_replace('/\s+/', '', (string)($row[$colMap['no_va'] ?? 'C'] ?? ''));
                $namaVa   = trim((string)($row[$colMap['nama_va'] ?? 'D'] ?? ''));
                $nomorId  = preg_replace('/\s+/', '', (string)($row[$colMap['nomor_identitas'] ?? 'E'] ?? ''));
                $totalVal = $row[$colMap['total'] ?? 'F'] ?? 0;
                $terbayar = $row[$colMap['terbayar'] ?? 'G'] ?? 0;
                $sisaVal  = isset($colMap['sisa']) ? ($row[$colMap['sisa']] ?? null) : null;
                $tglBayar = trim((string)($row[$colMap['tgl_bayar'] ?? 'I'] ?? ''));
                $statusRaw= trim((string)($row[$colMap['status'] ?? 'K'] ?? ''));
                $kelasVal = isset($colMap['kelas']) ? trim((string)($row[$colMap['kelas']] ?? '')) : '-';

                if (empty($noVa) && empty($trxId) && empty($namaVa)) {
                    continue;
                }

                // Filter awalan nomor VA (misal: 99900292026)
                if (!empty($vaPrefixClean)) {
                    if (!str_starts_with($noVa, $vaPrefixClean)) {
                        continue;
                    }
                }

                $nominalFloat  = $this->parseNominal($totalVal);
                $terbayarFloat = $this->parseNominal($terbayar);
                $sisaFloat     = $sisaVal !== null ? $this->parseNominal($sisaVal) : max(0, $nominalFloat - $terbayarFloat);

                $namaSiswa = $namaVa;
                $kelas     = $kelasVal ?: '-';
                if (preg_match('/^(.*?)\s*[\-\(\[\/]?\s*((?:10|11|12|X|XI|XII)\b.*?)$/i', $namaVa, $matches)) {
                    $namaSiswa = trim($matches[1], " /-\t\n\r\0\x0B()[]");
                    $extractedKelas = trim($matches[2], " /-\t\n\r\0\x0B()[]");
                    if ($kelas === '-' || empty($kelas)) {
                        $kelas = $extractedKelas;
                    }
                } elseif (str_contains($namaVa, '/')) {
                    $parts = explode('/', $namaVa, 2);
                    $namaSiswa = trim($parts[0], " /-\t\n\r\0\x0B()[]");
                    if ($kelas === '-' || empty($kelas)) {
                        $kelas = trim($parts[1], " /-\t\n\r\0\x0B()[]");
                    }
                }
                $namaSiswa = trim($namaSiswa, " /-\t\n\r\0\x0B()[]");

                $dataIdx++;
                $parsed[] = [
                    'index'                 => $dataIdx,
                    'trx_id'                => $trxId ?: ('TRX-' . $dataIdx),
                    'nomor_va'              => $noVa,
                    'nama_va'               => $namaVa,
                    'nama_siswa'            => $namaSiswa,
                    'kelas'                 => $kelas,
                    'nomor_identitas'       => $nomorId,
                    'total_nilai_tagihan'   => $nominalFloat,
                    'nominal_telah_dibayar' => $terbayarFloat,
                    'sisa_pembayaran'       => $sisaFloat,
                    'tanggal_dibayar'       => $tglBayar ?: null,
                    'status'                => $this->normalizeStatusReport($statusRaw, $nominalFloat, $terbayarFloat, $sisaFloat),
                    'jenis_tagihan'         => $this->detectJenisTagihan($noVa),
                    'kode_tagihan'          => substr($noVa, -2) ?: '00',
                ];
            }

            return [
                'success' => true,
                'data'    => $parsed,
                'total'   => count($parsed),
                'message' => count($parsed) . ' data berhasil diimpor dari file spreadsheet.',
            ];
        } catch (\Exception $e) {
            Log::error('[BpdDiyService parseSpreadsheetFile] Error: ' . $e->getMessage());
            return [
                'success' => false,
                'data'    => [],
                'total'   => 0,
                'message' => 'Gagal membaca file Excel / CSV: ' . $e->getMessage(),
            ];
        }
    }

    /**
     * Tarik data tagihan dari portal BPD DIY secara langsung,
     * hapus seluruh data tagihan lama di database lokal, dan ganti dengan data baru.
     *
     * @param array $filters Filter penarikan (tahun, status, parent_va, dll)
     * @return array
     */
    public function pullAndReplaceDatabase(array $filters = []): array
    {
        // Pastikan waktu eksekusi cukup untuk proses panjang di server
        @set_time_limit(600);
        @ini_set('max_execution_time', 600);
        @ini_set('memory_limit', '512M');

        // 1. Fetch seluruh data dari portal BPD DIY
        $fetchResult = $this->fetchReportTagihanVa($filters);

        if (!$fetchResult['success']) {
            return [
                'success'     => false,
                'message'     => $fetchResult['message'] ?? 'Gagal menarik data dari portal BPD DIY.',
                'need_cookie' => $fetchResult['need_cookie'] ?? false,
                'deleted'     => 0,
                'inserted'    => 0,
                'total'       => 0,
            ];
        }

        $rawRows = $fetchResult['data'] ?? [];

        if (empty($rawRows)) {
            return [
                'success'     => false,
                'message'     => 'Tidak ada data tagihan yang ditemukan dari portal BPD DIY untuk filter yang dipilih. Database lama tidak dihapus untuk mencegah kehilangan data.',
                'deleted'     => 0,
                'inserted'    => 0,
                'total'       => 0,
            ];
        }

        // 2. Load context data: Setting, Siswa & Tahun Ajaran
        $tahunAktif = TahunAjaran::where('status', 'aktif')->first();
        $idTahun    = $tahunAktif?->id_tahun;
        $idSekolah  = $this->setting->id_sekolah;

        $cleanString = function ($str) {
            $str = strtoupper(trim((string)$str));
            $str = preg_replace('/[\\\\\'"`\.\-]/', '', $str);
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

        // Siapkan lookup map siswa untuk matching akurat
        $siswaList    = UserSiswa::with('kelas')->get();
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

        // 3. Eksekusi transaksi DB: Hapus data lama & Insert data baru
        $deletedCount  = 0;
        $insertedCount = 0;
        $totalNilai    = 0;
        $totalTerbayar = 0;
        $countLunas    = 0;
        $countBelum    = 0;

        DB::beginTransaction();
        try {
            // Hitung data lama & hapus bersih
            $deletedCount = TagihanPembayaran::count();
            TagihanPembayaran::query()->delete();

            $insertBatch = [];
            $now = now();

            foreach ($rawRows as $row) {
                $nisKey   = (string) ($row['nomor_identitas'] ?? '');
                $trxKey   = preg_replace('/\s+/', '', (string) ($row['trx_id'] ?? ''));
                $noVa     = preg_replace('/\s+/', '', (string) ($row['nomor_va'] ?? ''));
                $namaRaw  = (string) ($row['nama_siswa'] ?? $row['nama_va'] ?? '');
                $kelasRaw = (string) ($row['kelas'] ?? '');

                if (empty($noVa) && empty($nisKey)) {
                    continue;
                }

                $cName  = $cleanString($namaRaw);
                $cKelas = $cleanClassKey($kelasRaw);

                // Hierarki Pencocokan Siswa & Kelas:
                $siswaInfo = null;

                // 1. Prioritas 1: Cocokkan Nama Siswa + Kelas (Paling Akurat, menghindari bentrok NIS sementara PPDB kelas 10)
                if (!empty($cName) && !empty($cKelas) && isset($nameClassMap[$cName . '_' . $cKelas])) {
                    $siswaInfo = $nameClassMap[$cName . '_' . $cKelas];
                }
                // 2. Prioritas 2: Cocokkan Nama Siswa persis
                elseif (!empty($cName) && isset($nameMap[$cName])) {
                    $siswaInfo = $nameMap[$cName];
                }
                // 3. Prioritas 3: Cocokkan NIS terdaftar (hanya jika nama siswa di NIS tersebut cocok/mirip atau nama di BPD kosong)
                elseif (!empty($nisKey) && isset($nisMap[$nisKey])) {
                    $cand = $nisMap[$nisKey];
                    $candClean = $cleanString($cand['nama_siswa']);
                    if (empty($cName) || str_contains($candClean, $cName) || str_contains($cName, $candClean) || similar_text($candClean, $cName) > (strlen($cName) * 0.7)) {
                        $siswaInfo = $cand;
                    }
                }

                // Tentukan NIS & ID Kelas yang tersimpan di DB
                $finalNis = $siswaInfo ? (string) $siswaInfo['nis'] : ($nisKey ?: '-');
                $idKelas  = $siswaInfo ? $siswaInfo['id_kelas'] : null;

                // Fallback ID Kelas jika siswa belum terdaftar di user_siswa tetapi kelasnya ada di tabel kelas
                if (!$idKelas && !empty($cKelas) && isset($kelasMap[$cKelas])) {
                    $idKelas = $kelasMap[$cKelas]['id_kelas'];
                }

                $nominal  = (float) ($row['total_nilai_tagihan'] ?? 0);
                $terbayar = (float) ($row['nominal_telah_dibayar'] ?? 0);
                $status   = $row['status'] ?? 'belum_bayar';
                $tglBayar = !empty($row['tanggal_dibayar']) ? date('Y-m-d H:i:s', strtotime($row['tanggal_dibayar'])) : null;

                $jenisTagihan = $row['jenis_tagihan'] ?? 'spp';
                $kodeTagihan  = $row['kode_tagihan'] ?? substr($noVa, -2) ?: '00';
                $namaTagihan  = $row['nama_va'] ?? ('Tagihan VA ' . strtoupper($jenisTagihan));

                $totalNilai    += $nominal;
                $totalTerbayar += $terbayar;
                if ($status === 'lunas') {
                    $countLunas++;
                } else {
                    $countBelum++;
                }

                $insertBatch[] = [
                    'id_sekolah'          => $idSekolah,
                    'trx_id'              => $trxKey ?: null,
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
                    'keterangan'          => 'Otomatis ditarik dari BPD DIY ' . $now->format('d/m/Y H:i'),
                    'created_at'          => $now,
                    'updated_at'          => $now,
                ];

                // Chunk insert tiap 500 baris
                if (count($insertBatch) >= 500) {
                    TagihanPembayaran::insert($insertBatch);
                    $insertedCount += count($insertBatch);
                    $insertBatch = [];
                }
            }

            // Sisa chunk
            if (!empty($insertBatch)) {
                TagihanPembayaran::insert($insertBatch);
                $insertedCount += count($insertBatch);
            }

            DB::commit();

            Log::info("[BPD DIY pullAndReplaceDatabase] Sukses: {$deletedCount} data lama dihapus, {$insertedCount} data baru dimasukkan.");

            return [
                'success'        => true,
                'message'        => "✅ Berhasil! {$deletedCount} data lama dihapus, dan {$insertedCount} data tagihan baru dari BPD DIY berhasil disimpan ke database.",
                'deleted_count'  => $deletedCount,
                'inserted_count' => $insertedCount,
                'total_remote'   => $fetchResult['total'] ?? count($rawRows),
                'summary'        => [
                    'total_nilai'    => $totalNilai,
                    'total_terbayar' => $totalTerbayar,
                    'total_sisa'     => max(0, $totalNilai - $totalTerbayar),
                    'count_lunas'    => $countLunas,
                    'count_belum'    => $countBelum,
                ],
            ];
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('[BpdDiyService pullAndReplaceDatabase] Exception: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
            ]);

            return [
                'success' => false,
                'message' => 'Gagal memperbarui database: ' . $e->getMessage(),
                'deleted' => 0,
                'inserted'=> 0,
            ];
        }
    }
}
