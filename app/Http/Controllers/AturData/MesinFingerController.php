<?php

namespace App\Http\Controllers\AturData;

use App\Http\Controllers\Controller;
use App\Models\DataMesin;
use App\Models\LogAbsensi;
use App\Models\UserSiswa;
use App\Models\Sekolah;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MesinFingerController extends Controller
{
    // ── Data Mesin ──
    public function index(Request $request)
    {
        $query = DataMesin::query();
        if ($request->filled('search')) $query->where('nama_mesin','like','%'.$request->search.'%')->orWhere('sn','like','%'.$request->search.'%');
        $perPage   = (int) $request->input('per_page', 20);
        $mesinList = $query->orderBy('nama_mesin')->paginate($perPage)->withQueryString();
        return view('atur-data.mesin-finger.index', compact('mesinList'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_mesin' => 'required|string|max:100',
            'sn'         => 'required|string|max:100|unique:data_mesin,sn',
            'password'   => 'nullable|string|max:50',
        ]);
        DataMesin::create([
            'nama_mesin'  => $request->nama_mesin,
            'sn'          => $request->sn,
            'password'    => $request->password,
            'data'        => 0,
            'last_update' => now(),
        ]);
        return redirect()->route('atur-data.mesin-finger')->with('success','Mesin finger berhasil ditambahkan.');
    }

    public function update(Request $request, $id)
    {
        $mesin = DataMesin::findOrFail($id);
        $request->validate([
            'nama_mesin' => 'required|string|max:100',
            'sn'         => 'required|string|max:100|unique:data_mesin,sn,'.$id.',id_mesin',
            'password'   => 'nullable|string|max:50',
        ]);
        $mesin->update([
            'nama_mesin'  => $request->nama_mesin,
            'sn'          => $request->sn,
            'password'    => $request->password ?? $mesin->password,
            'last_update' => now(),
        ]);
        return redirect()->route('atur-data.mesin-finger')->with('success','Mesin finger berhasil diperbarui.');
    }

    public function destroy($id)
    {
        DataMesin::findOrFail($id)->delete();
        return redirect()->route('atur-data.mesin-finger')->with('success','Mesin finger berhasil dihapus.');
    }

    // ── Tarik Data Finger ──
    public function tarikIndex(Request $request)
    {
        $query = LogAbsensi::with(['siswa:nis,nama_siswa,id_kelas', 'siswa.kelas']);
        if ($request->filled('tanggal'))    $query->whereDate('tanggal', $request->tanggal);
        if ($request->filled('nis'))        $query->where('nis', $request->nis);
        if ($request->filled('status'))     $query->where('status', $request->status);
        if ($request->filled('keterangan')) $query->where('keterangan', $request->keterangan);

        $perPage = $request->get('per_page', 25);
        $logList = $query->orderByDesc('tanggal')->orderBy('jam')->paginate($perPage)->withQueryString();

        $mesinList = DataMesin::orderBy('nama_mesin')->get();
        $sekolah   = Sekolah::where('id_sekolah', 1)->first();

        // Summary stats for sync status badges
        $syncSummary = LogAbsensi::selectRaw('keterangan, count(*) as total')
            ->groupBy('keterangan')
            ->pluck('total', 'keterangan')
            ->toArray();

        return view('atur-data.tarik-finger.index', compact('logList', 'mesinList', 'sekolah', 'syncSummary'));
    }

    public function sinkronkan(Request $request)
    {
        $request->validate([
            'tanggal_dari'   => 'required|date',
            'tanggal_sampai' => 'required|date|after_or_equal:tanggal_dari',
        ]);
        $logs   = LogAbsensi::whereBetween('tanggal', [$request->tanggal_dari, $request->tanggal_sampai])->get();
        $synced = 0; $skipped = 0;

        foreach ($logs as $log) {
            try {
                // Siswa tidak terdaftar — skip, tidak perlu ubah keterangan
                if (!UserSiswa::where('nis', $log->nis)->exists()) {
                    $skipped++;
                    continue;
                }

                $sudahAda = DB::table('presensi')
                    ->where('nis', $log->nis)
                    ->whereDate('tanggal', $log->tanggal)
                    ->exists();

                if ($sudahAda) {
                    // Tandai: data sudah ada di presensi
                    DB::table('log_absensi')
                        ->where('id_presensi', $log->id_presensi)
                        ->update(['keterangan' => 'Data sudah ada']);
                    $skipped++;
                    continue;
                }

                DB::table('presensi')->insert([
                    'nis'        => $log->nis,
                    'tanggal'    => $log->tanggal,
                    'jam'        => $log->jam,
                    'status'     => $log->status ?? 'Hadir',
                    'keterangan' => 'Sinkronisasi mesin finger',
                    'file'       => null,
                ]);

                // Tandai: berhasil tersinkron
                DB::table('log_absensi')
                    ->where('id_presensi', $log->id_presensi)
                    ->update(['keterangan' => 'Tersinkron']);

                $synced++;

            } catch (\Exception $e) {
                // Tandai: gagal sinkron
                DB::table('log_absensi')
                    ->where('id_presensi', $log->id_presensi)
                    ->update(['keterangan' => 'Gagal']);
                \Log::error("[Sinkronkan] NIS {$log->nis} tanggal {$log->tanggal}: " . $e->getMessage());
            }
        }

        return redirect()->route('atur-data.tarik-finger')
            ->with('success', "Sinkronisasi selesai. Berhasil: {$synced}, Dilewati: {$skipped}.");
    }

    public function hapusByTanggal(Request $request)
    {
        $request->validate(['tanggal_dari'=>'required|date','tanggal_sampai'=>'required|date|after_or_equal:tanggal_dari']);
        $deleted = LogAbsensi::whereBetween('tanggal',[$request->tanggal_dari,$request->tanggal_sampai])->delete();
        return redirect()->route('atur-data.tarik-finger')->with('success',"{$deleted} data log berhasil dihapus.");
    }

    public function updateSchedule(Request $request)
    {
        $request->validate([
            'sync_interval' => 'nullable|string|in:15,30,60,120,daily',
            'sync_time'     => 'nullable|string|regex:/^\d{2}:\d{2}$/',
        ]);

        $sekolah = Sekolah::where('id_sekolah', 1)->firstOrFail();
        $sekolah->update([
            'sync_otomatis' => $request->has('sync_otomatis') ? true : false,
            'sync_interval' => $request->sync_interval ?? '30',
            'sync_time'     => $request->sync_time ?? '00:00',
        ]);

        return redirect()->route('atur-data.tarik-finger')->with('success', 'Pengaturan jadwal sinkronisasi otomatis berhasil disimpan.');
    }

    public function hapusDataMesinFinger(Request $request)
    {
        set_time_limit(300); // Set time limit to 5 minutes to prevent timeout
        $mesinList = DataMesin::all();
        if ($mesinList->isEmpty()) {
            return back()->with('error', 'Tidak ada mesin finger yang terdaftar.');
        }

        // Pass 1: Send HDM (delete command) to all machines
        foreach ($mesinList as $mesin) {
            try {
                $cookie = $this->loginCloud($mesin->sn, $mesin->password);
                if ($cookie) {
                    $this->hapusDataMesinCloud($cookie);
                    $this->logoutCloud($cookie);
                }
            } catch (\Exception $e) {
                \Log::error("Hapus Data Mesin Pass 1 error SN {$mesin->sn}: " . $e->getMessage());
            }
        }

        // Wait 20 seconds in total for all machines to receive and process the command in parallel
        sleep(20);

        $totalBerhasil = 0;
        $totalGagal = 0;

        // Pass 2: Compact, check and verify status for each machine
        foreach ($mesinList as $mesin) {
            try {
                $cookie = $this->loginCloud($mesin->sn, $mesin->password);
                if (!$cookie) {
                    $totalGagal++;
                    continue;
                }

                $maxRetries = 3;
                $isCleared = false;

                for ($attempt = 1; $attempt <= $maxRetries; $attempt++) {
                    // Send compact command
                    $this->compactCloud($cookie);
                    sleep(2); // Jeda 2s untuk penyelesaian compact di server cloud

                    // Check view — hanya cek baris data presensi yang valid
                    $checkData = $this->fetchCloud($cookie);
                    if (!$this->hasValidData($checkData)) {
                        $isCleared = true;
                        break;
                    }

                    if ($attempt < $maxRetries) {
                        // Jika masih ada data, kirim ulang perintah hapus dan tunggu
                        $this->hapusDataMesinCloud($cookie);
                        sleep(8);
                    }
                }

                if ($isCleared) {
                    $totalBerhasil++;
                    $mesin->update([
                        'data' => 0,
                        'last_update' => now(),
                    ]);
                } else {
                    $totalGagal++;
                    \Log::warning("Data di mesin SN {$mesin->sn} masih ada setelah verifikasi.");
                }

                $this->logoutCloud($cookie);

            } catch (\Exception $e) {
                $totalGagal++;
                \Log::error("Hapus Data Mesin Pass 2 error SN {$mesin->sn}: " . $e->getMessage());
            }
        }

        if ($totalBerhasil > 0) {
            $msg = "Berhasil menghapus/mengosongkan data presensi pada {$totalBerhasil} mesin finger.";
            if ($totalGagal > 0) {
                $msg .= " ({$totalGagal} mesin gagal).";
            }
            return redirect()->route('atur-data.tarik-finger')->with('success', $msg);
        } else {
            return redirect()->route('atur-data.tarik-finger')->with('error', 'Gagal menghapus data presensi pada semua mesin finger.');
        }
    }

    public function hapusSingleMesin($id)
    {
        set_time_limit(120);
        $mesin = DataMesin::findOrFail($id);

        try {
            $cookie = $this->loginCloud($mesin->sn, $mesin->password);
            if (!$cookie) {
                return back()->with('error', "Gagal menghubungkan ke mesin {$mesin->nama_mesin} (SN: {$mesin->sn}). Periksa koneksi.");
            }

            $maxRetries = 5;
            $isCleared = false;

            for ($attempt = 1; $attempt <= $maxRetries; $attempt++) {
                // 1. Kirim perintah hapus ke mesin
                $this->hapusDataMesinCloud($cookie);

                // 2. Compact data cloud
                $this->compactCloud($cookie);
                sleep(3); // Jeda 3s untuk penyelesaian compact

                // 3. Cek apakah masih ada data presensi yang valid
                $checkData = $this->fetchCloud($cookie);
                if (!$this->hasValidData($checkData)) {
                    $isCleared = true;
                    break;
                }

                if ($attempt < $maxRetries) {
                    sleep(5); // Tunggu mesin untuk memproses
                }
            }

            $this->logoutCloud($cookie);

            if ($isCleared) {
                $mesin->update([
                    'data' => 0,
                    'last_update' => now(),
                ]);
                return redirect()->route('atur-data.tarik-finger')->with('success', "Berhasil menghapus seluruh data pada mesin {$mesin->nama_mesin}.");
            } else {
                return redirect()->route('atur-data.tarik-finger')->with('error', "Gagal mengosongkan data mesin {$mesin->nama_mesin} (Data masih ada di server).");
            }

        } catch (\Exception $e) {
            \Log::error("Hapus Single Mesin error SN {$mesin->sn}: " . $e->getMessage());
            return redirect()->route('atur-data.tarik-finger')->with('error', "Terjadi kesalahan: " . $e->getMessage());
        }
    }

    public function tarikProses(Request $request)
    {
        $mesinList = DataMesin::all();
        if ($mesinList->isEmpty()) {
            return back()->with('error', 'Tidak ada mesin finger yang terdaftar.');
        }

        $totalBerhasil = 0;
        
        foreach ($mesinList as $mesin) {
            try {
                $cookie = $this->loginCloud($mesin->sn, $mesin->password);
                if (!$cookie) {
                    continue;
                }

                // Compact
                $this->compactCloud($cookie);

                // Fetch
                $rawData = $this->fetchCloud($cookie);
                if (empty($rawData)) {
                    continue;
                }

                // Parse and Save
                $inserted = $this->insertLogCloud($rawData, $mesin->id_mesin);
                $totalBerhasil += $inserted;

                $mesin->update([
                    'data' => $inserted,
                    'last_update' => now(),
                ]);

            } catch (\Exception $e) {
                \Log::error("Web Pull Error for SN {$mesin->sn}: " . $e->getMessage());
            }
        }

        // Run sync as well
        $synced = $this->sinkronkanProses();

        return redirect()->route('atur-data.tarik-finger')->with('success', "Berhasil menarik {$totalBerhasil} data log baru dan mensinkronkan {$synced} data presensi dari semua mesin.");
    }

    private function loginCloud(string $sn, string $password): ?string
    {
        // Step 1: GET default.asp to fetch initial ASP Session ID cookie
        $ch = curl_init();
        curl_setopt_array($ch, [
            CURLOPT_URL            => 'http://www.solutioncloud.co.id/default.asp',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HEADER         => true,
            CURLOPT_TIMEOUT        => 30,
            CURLOPT_SSL_VERIFYPEER => false,
        ]);
        $res1 = curl_exec($ch);
        if ($res1 === false) {
            curl_close($ch);
            return null;
        }
        $hdrSize1 = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
        $header1 = substr($res1, 0, $hdrSize1);
        curl_close($ch);

        preg_match_all('/^Set-Cookie:\s*([^;]*)/mi', $header1, $cookies1);
        $cookie = $cookies1[1][0] ?? null;

        if (!$cookie) {
            return null;
        }

        // Step 2: POST login details with the session cookie
        $ch = curl_init();
        curl_setopt_array($ch, [
            CURLOPT_URL            => 'http://www.solutioncloud.co.id/sc_pro.asp',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST           => true,
            CURLOPT_POSTFIELDS     => http_build_query(['sn' => $sn, 'pass' => $password]),
            CURLOPT_HTTPHEADER     => [
                'Content-Type: application/x-www-form-urlencoded',
                'Cookie: ' . $cookie
            ],
            CURLOPT_REFERER        => 'http://www.solutioncloud.co.id/default.asp',
            CURLOPT_TIMEOUT        => 30,
            CURLOPT_SSL_VERIFYPEER => false,
        ]);
        curl_exec($ch);
        curl_close($ch);

        // Step 3: GET mesin.asp to initialize/validate session on server side
        $ch = curl_init();
        curl_setopt_array($ch, [
            CURLOPT_URL            => 'http://www.solutioncloud.co.id/mesin.asp',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER     => ['Cookie: ' . $cookie],
            CURLOPT_REFERER        => 'http://www.solutioncloud.co.id/sc_pro.asp',
            CURLOPT_TIMEOUT        => 30,
            CURLOPT_SSL_VERIFYPEER => false,
        ]);
        curl_exec($ch);
        curl_close($ch);

        return $cookie;
    }

    private function compactCloud(string $cookie): void
    {
        $ch = curl_init();
        curl_setopt_array($ch, [
            CURLOPT_URL            => 'http://www.solutioncloud.co.id/mesin.asp?hapus=1',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER     => ['Cookie: ' . $cookie],
            CURLOPT_TIMEOUT        => 30,
            CURLOPT_SSL_VERIFYPEER => false,
        ]);
        curl_exec($ch);
        curl_close($ch);
    }

    private function hapusDataMesinCloud(string $cookie): void
    {
        $ch = curl_init();
        curl_setopt_array($ch, [
            CURLOPT_URL            => 'http://www.solutioncloud.co.id/mesin.asp?hdm=1',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER     => ['Cookie: ' . $cookie],
            CURLOPT_TIMEOUT        => 30,
            CURLOPT_SSL_VERIFYPEER => false,
        ]);
        curl_exec($ch);
        curl_close($ch);
    }

    private function logoutCloud(string $cookie): void
    {
        $ch = curl_init();
        curl_setopt_array($ch, [
            CURLOPT_URL            => 'http://www.solutioncloud.co.id/default.asp?logout=1',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER     => ['Cookie: ' . $cookie],
            CURLOPT_TIMEOUT        => 15,
            CURLOPT_SSL_VERIFYPEER => false,
        ]);
        curl_exec($ch);
        curl_close($ch);
    }

    private function fetchCloud(string $cookie): string
    {
        $ch = curl_init();
        curl_setopt_array($ch, [
            CURLOPT_URL            => 'http://www.solutioncloud.co.id/view.asp',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER     => ['Cookie: ' . $cookie],
            CURLOPT_TIMEOUT        => 60,
            CURLOPT_SSL_VERIFYPEER => false,
        ]);
        $response = curl_exec($ch);
        curl_close($ch);
        return $response ?: '';
    }

    /**
     * Periksa apakah response dari server mengandung baris data presensi yang valid.
     * Data presensi valid: baris yang diawali dengan angka (NIS) diikuti tanggal, jam, status.
     * Menghindari false-positive dari HTML error, whitespace, atau newline kosong.
     */
    private function hasValidData(string $rawData): bool
    {
        if (empty(trim($rawData))) {
            return false;
        }
        $rows = explode("\n", trim($rawData));
        foreach ($rows as $row) {
            $row = trim($row);
            if ($row === '') {
                continue;
            }
            // Baris data presensi diawali dengan karakter numerik (NIS)
            if (preg_match('/^\d+\s+\d{4}-\d{2}-\d{2}/', $row)) {
                return true;
            }
        }
        return false;
    }

    private function insertLogCloud(string $rawData, int $idMesin): int
    {
        $rows     = explode("\n", trim($rawData));
        $inserted = 0;

        foreach ($rows as $row) {
            $cols = preg_split('/\s+/', trim($row));
            if (count($cols) < 4) {
                continue;
            }

            [$nis, $tanggal, $jam, $status] = $cols;

            if (!is_numeric($nis)) {
                continue;
            }

            $nisInt = (int) $nis;

            $exists = DB::table('log_absensi')
                ->where('nis', $nisInt)
                ->where('tanggal', $tanggal)
                ->where('jam', $jam)
                ->exists();

            if ($exists) {
                continue;
            }

            // Insert with default keterangan = 'Belum Tersinkron'
            DB::table('log_absensi')->insert([
                'nis'        => $nisInt,
                'tanggal'    => $tanggal,
                'jam'        => $jam,
                'status'     => $status,
                'keterangan' => 'Belum Tersinkron',
            ]);

            $inserted++;
        }

        return $inserted;
    }

    private function sinkronkanProses(): int
    {
        $logs   = LogAbsensi::orderBy('tanggal')->orderBy('jam')->get();
        $synced = 0;

        foreach ($logs as $log) {
            try {
                $siswaExists = UserSiswa::where('nis', $log->nis)->exists();
                if (!$siswaExists) {
                    continue;
                }

                $sudahAda = DB::table('presensi')
                    ->where('nis', $log->nis)
                    ->whereDate('tanggal', $log->tanggal)
                    ->exists();

                if ($sudahAda) {
                    DB::table('log_absensi')
                        ->where('id_presensi', $log->id_presensi)
                        ->update(['keterangan' => 'Data sudah ada']);
                    continue;
                }

                DB::table('presensi')->insert([
                    'nis'        => $log->nis,
                    'tanggal'    => $log->tanggal,
                    'jam'        => $log->jam,
                    'status'     => $log->status ?? '1',
                    'keterangan' => 'Mesin finger – otomatis',
                    'file'       => null,
                ]);

                DB::table('log_absensi')
                    ->where('id_presensi', $log->id_presensi)
                    ->update(['keterangan' => 'Tersinkron']);

                $synced++;

            } catch (\Exception $e) {
                DB::table('log_absensi')
                    ->where('id_presensi', $log->id_presensi)
                    ->update(['keterangan' => 'Gagal']);
                \Log::error('Sinkronisasi web error NIS ' . $log->nis . ': ' . $e->getMessage());
            }
        }

        return $synced;
    }
}
