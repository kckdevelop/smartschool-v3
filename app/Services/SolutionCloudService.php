<?php

namespace App\Services;

use Exception;

class SolutionCloudService
{
    protected string $baseUrl = 'http://www.solutioncloud.co.id';

    /**
     * Ambil semua data presensi dari Solution Cloud.
     *
     * @param  string  $nomorMesin  Serial Number mesin absensi (SN)
     * @param  string  $password    Password cloud server
     * @return array{success: bool, data: array, total: int, message: string}
     */
    public function fetchPresensi(string $nomorMesin, string $password): array
    {
        try {
            // Step 1: GET default.asp → dapat ASP Session ID
            [$status, $hdr, $body] = $this->doRequest($this->baseUrl . '/default.asp');
            $cookie = $this->extractCookie($hdr);

            if (empty($cookie)) {
                return $this->fail('Gagal mendapatkan session dari server.');
            }

            // Step 2: POST login ke sc_pro.asp
            [$status, $hdr, $body] = $this->doRequest(
                $this->baseUrl . '/sc_pro.asp',
                ['sn' => $nomorMesin, 'pass' => $password],
                $cookie,
                $this->baseUrl . '/default.asp'
            );

            // Cek apakah redirect ke mesin.asp (login berhasil)
            if (strpos($body, "window.location='mesin.asp'") === false) {
                return $this->fail('Login gagal. Periksa kembali nomor mesin dan password.');
            }

            // Step 3: GET mesin.asp (validasi session)
            $this->doRequest(
                $this->baseUrl . '/mesin.asp',
                [],
                $cookie,
                $this->baseUrl . '/sc_pro.asp'
            );

            // Step 4: GET download.asp → ambil data presensi (tab-separated)
            [$status, $hdr, $body] = $this->doRequest(
                $this->baseUrl . '/download.asp',
                [],
                $cookie,
                $this->baseUrl . '/mesin.asp'
            );

            if ($status !== 200 || empty(trim($body))) {
                return $this->fail('Tidak ada data presensi yang tersedia di server.');
            }

            $records = $this->parseTabSeparated($body);

            if (empty($records)) {
                return $this->fail('Format data tidak dikenali atau data kosong.');
            }

            return [
                'success' => true,
                'data'    => $records,
                'total'   => count($records),
                'message' => 'Berhasil mengambil ' . count($records) . ' data presensi.',
            ];

        } catch (Exception $e) {
            return $this->fail('Error: ' . $e->getMessage());
        }
    }

    /**
     * Ambil data presensi dengan pembatasan index (slice).
     *
     * @param  string  $nomorMesin  Serial Number mesin absensi
     * @param  string  $password    Password cloud server
     * @param  int     $startIndex  Index awal (0-based)
     * @param  int     $endIndex    Index akhir inclusive (0-based)
     * @return array
     */
    public function fetchPresensiByIndex(string $nomorMesin, string $password, int $startIndex = 0, int $endIndex = 9): array
    {
        $result = $this->fetchPresensi($nomorMesin, $password);

        if (!$result['success']) {
            return $result;
        }

        $allData  = $result['data'];
        $totalAll = $result['total'];
        $length   = $endIndex - $startIndex + 1;
        $sliced   = array_slice($allData, $startIndex, $length);

        return [
            'success'   => true,
            'data'      => $sliced,
            'total'     => count($sliced),
            'total_all' => $totalAll,
            'message'   => "Menampilkan data index {$startIndex} s/d {$endIndex} dari total {$totalAll} data.",
        ];
    }

    // ──────────────────────────────────────────────────────────────
    // Private Helpers
    // ──────────────────────────────────────────────────────────────

    /**
     * Lakukan HTTP request menggunakan cURL dengan manual cookie.
     *
     * @return array [statusCode, responseHeader, responseBody]
     */
    private function doRequest(string $url, array $postData = [], string $cookie = '', string $referer = ''): array
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, false);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        curl_setopt($ch, CURLOPT_HEADER, true);
        curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 Chrome/120.0.0.0 Safari/537.36');

        $headers = [
            'Accept: text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8',
            'Accept-Language: id-ID,id;q=0.9,en;q=0.8',
            'Connection: keep-alive',
        ];

        if (!empty($cookie)) {
            $headers[] = 'Cookie: ' . $cookie;
        }
        if (!empty($referer)) {
            curl_setopt($ch, CURLOPT_REFERER, $referer);
        }
        if (!empty($postData)) {
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($postData));
            $headers[] = 'Content-Type: application/x-www-form-urlencoded';
        }

        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        $response = curl_exec($ch);
        $status   = (int) curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $error    = curl_error($ch);
        curl_close($ch);

        if ($error) {
            throw new Exception('cURL error: ' . $error);
        }

        // Pisahkan header dan body
        $parts  = preg_split('/\r\n\r\n/', $response, 2);
        $header = $parts[0] ?? '';
        $body   = $parts[1] ?? '';

        return [$status, $header, $body];
    }

    /**
     * Extract cookie string dari response header.
     */
    private function extractCookie(string $header): string
    {
        $cookies = [];
        foreach (explode("\r\n", $header) as $line) {
            if (stripos($line, 'Set-Cookie:') === 0) {
                $raw       = trim(substr($line, strlen('Set-Cookie:')));
                $nameValue = explode(';', $raw)[0];
                $cookies[] = trim($nameValue);
            }
        }
        return implode('; ', $cookies);
    }

    /**
     * Parse data tab-separated dari respons download.asp.
     * Format: PIN\tDatetime\tVerifyType\tInOut\t...
     *
     * @return array
     */
    private function parseTabSeparated(string $text): array
    {
        $records = [];
        $lines   = preg_split('/\r\n|\r|\n/', trim($text));

        foreach ($lines as $index => $line) {
            $line = trim($line);
            if (empty($line)) {
                continue;
            }

            $cols = explode("\t", $line);

            // Minimal harus ada PIN dan Datetime
            if (count($cols) < 2) {
                continue;
            }

            $pin      = trim($cols[0]);
            $datetime = trim($cols[1]);

            // Validasi: PIN harus angka, kolom 2 harus datetime
            if (!is_numeric($pin) || !preg_match('/^\d{4}-\d{2}-\d{2}[\s]\d{2}:\d{2}/', $datetime)) {
                continue;
            }

            $records[] = [
                'index'       => $index,
                'pin'         => $pin,
                'datetime'    => $datetime,
                'date'        => substr($datetime, 0, 10),
                'time'        => substr($datetime, 11, 8),
                'verify_type' => trim($cols[2] ?? ''),
                'in_out'      => trim($cols[3] ?? ''),
                'reserved1'   => trim($cols[4] ?? ''),
                'work_code'   => trim($cols[5] ?? ''),
            ];
        }

        return $records;
    }

    /**
     * Helper untuk return error response.
     */
    private function fail(string $message): array
    {
        return [
            'success' => false,
            'data'    => [],
            'total'   => 0,
            'message' => $message,
        ];
    }
}
