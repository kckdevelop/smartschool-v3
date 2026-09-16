<?php

namespace App\Http\Controllers\Pembayaran;

use App\Http\Controllers\Controller;

use App\Models\SettingPembayaran;
use App\Models\Sekolah;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class KonfigurasiController extends Controller
{
    /**
     * Tampilkan halaman Setting Konfigurasi BPD DIY VA
     */
    public function index()
    {
        $setting = SettingPembayaran::getSetting();
        $sekolah = Sekolah::first();

        return view('pembayaran.konfigurasi.index', compact('setting', 'sekolah'));
    }

    /**
     * Simpan / Update Konfigurasi BPD DIY VA
     */
    public function update(Request $request)
    {
        $setting = SettingPembayaran::getSetting();

        $validated = $request->validate([
            'id_institusi'   => 'required|string|max:20',
            'url_report_va'  => 'required|url|max:255',
            'username_maker' => 'required|string|max:100',
            'password_maker' => 'required|string|max:255',
            'mode_api'       => 'required|in:production,sandbox,simulasi',
            'format_tahun'   => 'required|in:YYYY,YY',
            'kode_spp'       => 'required|string|max:10',
            'kode_non_spp'   => 'required|string|max:10',
            'kode_tunggakan' => 'required|string|max:10',
            'status_api'     => 'required|in:aktif,nonaktif',
        ]);

        $setting->update($validated);

        return redirect()->route('pembayaran.konfigurasi.index')
            ->with('success', 'Konfigurasi API Tagihan Pembayaran BPD DIY berhasil disimpan.');
    }

    /**
     * Simpan Session Cookie BPD DIY (dari browser user).
     *
     * POST /pembayaran/konfigurasi/save-cookie
     */
    public function saveCookie(Request $request)
    {
        $validated = $request->validate([
            'session_cookie' => 'required|string|min:4',
        ]);

        $raw = trim($validated['session_cookie']);
        $normalizedCookie = $raw;

        // Cek jika user mem-paste JSON dari EditThisCookie
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

        return redirect()->route('pembayaran.konfigurasi.index')
            ->with('success', '✅ Session Cookie BPD DIY berhasil disimpan. Sekarang Anda bisa mengambil data tagihan dari portal BPD DIY.');
    }

    /**
     * Tes Koneksi ke Portal BPD DIY menggunakan session cookie tersimpan.
     */
    public function testConnection(Request $request)
    {
        $setting = SettingPembayaran::getSetting();

        // Jika ada session cookie, test dengan cookie
        if (!empty($setting->session_cookie)) {
            try {
                $parsed     = parse_url($setting->url_report_va);
                $baseUrl    = ($parsed['scheme'] ?? 'https') . '://' . ($parsed['host'] ?? 'va.bpddiy.co.id');
                $reportUrl  = $setting->url_report_va;

                $response = Http::withOptions(['verify' => false, 'allow_redirects' => true])
                    ->withHeaders([
                        'Cookie'     => $setting->session_cookie,
                        'User-Agent' => 'Mozilla/5.0 SmartSchool/1.0',
                        'Accept'     => 'text/html,application/xhtml+xml,*/*;q=0.8',
                    ])
                    ->timeout(10)
                    ->get($reportUrl);

                $body   = $response->body();
                $status = $response->status();

                // Cek apakah kena redirect ke login
                if (
                    str_contains($body, 'name="username"') ||
                    str_contains($body, '/admin/auth/login') ||
                    $status === 401
                ) {
                    return response()->json([
                        'success' => false,
                        'message' => '⚠️ Session Cookie sudah kadaluarsa. Silakan ambil cookie baru dari browser dan simpan ulang.',
                    ]);
                }

                // Cek apakah halaman berisi konten portal (ada kata "tagihan" atau "VA")
                if (
                    str_contains($body, 'tagihan') ||
                    str_contains($body, 'virtual') ||
                    str_contains($body, 'Tagihan') ||
                    ($status >= 200 && $status < 400)
                ) {
                    $cookieAge = $setting->cookie_updated_at
                        ? '(diperbarui ' . $setting->cookie_updated_at->diffForHumans() . ')'
                        : '';
                    return response()->json([
                        'success' => true,
                        'message' => "✅ Koneksi ke portal BPD DIY BERHASIL menggunakan Session Cookie {$cookieAge}. Data tagihan siap diambil.",
                    ]);
                }

                return response()->json([
                    'success' => true,
                    'message' => "Portal BPD DIY dijangkau (HTTP {$status}). Cookie tersimpan, silakan coba ambil data tagihan.",
                ]);
            } catch (\Exception $e) {
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal terhubung: ' . $e->getMessage(),
                ]);
            }
        }

        // Jika belum ada cookie, test koneksi dasar saja
        try {
            $response   = Http::withOptions(['verify' => false])->timeout(5)->get($setting->url_report_va);
            $statusCode = $response->status();

            return response()->json([
                'success' => true,
                'message' => "Portal BPD DIY dapat dijangkau (HTTP {$statusCode}). ⚠️ Belum ada Session Cookie — silakan tambahkan cookie agar bisa mengambil data tagihan.",
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal terhubung ke portal BPD DIY: ' . $e->getMessage(),
            ]);
        }
    }
}
