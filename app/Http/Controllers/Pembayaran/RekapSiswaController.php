<?php

namespace App\Http\Controllers\Pembayaran;

use App\Http\Controllers\Controller;
use App\Models\TagihanPembayaran;
use App\Models\SettingPembayaran;
use App\Models\UserSiswa;
use App\Models\Kelas;
use App\Models\TahunAjaran;
use App\Models\Semester;
use App\Models\Sekolah;
use App\Services\BpdDiyService;
use Illuminate\Http\Request;

class RekapSiswaController extends Controller
{
    /**
     * Tampilkan Rekap Tagihan Per Siswa & Daftar VA
     */
    public function index(Request $request)
    {
        $sekolah = Sekolah::first();
        $setting = SettingPembayaran::getSetting();

        $kelasList = Kelas::where('status', 'aktif')->orderBy('tingkat', 'asc')->orderBy('rombel', 'asc')->get();
        $tahunList = TahunAjaran::orderBy('id_tahun', 'desc')->get();
        $semesterList = Semester::all();

        $selectedTahun = $request->get('id_tahun', TahunAjaran::where('status', 'aktif')->value('id_tahun') ?? ($tahunList->first()->id_tahun ?? null));
        $selectedKelas = $request->get('id_kelas', null);
        $selectedJenis = $request->get('jenis_tagihan', null);
        $selectedStatus = $request->get('status', null);
        $search = $request->get('search', null);

        // Tahun untuk formulasi VA (gunakan tahun awal ajaran, misal 2026 dari 2026/2027)
        $tahunAjaranObj = $selectedTahun ? TahunAjaran::find($selectedTahun) : TahunAjaran::where('status', 'aktif')->first();
        $tahunVal = date('Y');
        if ($tahunAjaranObj && !empty($tahunAjaranObj->tahun)) {
            $parts = explode('/', $tahunAjaranObj->tahun);
            $tahunVal = trim($parts[0]);
        }

        // Query Siswa
        $siswaQuery = UserSiswa::with(['kelas', 'detail'])->where('status', 'aktif');
        if ($selectedKelas) {
            $siswaQuery->where('id_kelas', $selectedKelas);
        }
        if ($search) {
            $siswaQuery->where(function($q) use ($search) {
                $q->where('nama_siswa', 'like', "%{$search}%")
                  ->orWhere('nis', 'like', "%{$search}%");
            });
        }

        $siswaListPaginated = $siswaQuery->orderBy('nama_siswa', 'asc')->paginate(15)->withQueryString();

        // Olah data rekap per siswa (beserta 3 Kode VA)
        $rekapSiswa = [];
        foreach ($siswaListPaginated as $siswa) {
            $vaSpp = TagihanPembayaran::generateVaNumber($setting->id_institusi, $tahunVal, $siswa->nis, $setting->kode_spp, $setting->format_tahun);
            $vaNonSpp = TagihanPembayaran::generateVaNumber($setting->id_institusi, $tahunVal, $siswa->nis, $setting->kode_non_spp, $setting->format_tahun);
            $vaTunggakan = TagihanPembayaran::generateVaNumber($setting->id_institusi, $tahunVal, $siswa->nis, $setting->kode_tunggakan, $setting->format_tahun);

            $tagihanList = TagihanPembayaran::where('nis', $siswa->nis)->get();

            $sppItems      = $tagihanList->where('jenis_tagihan', 'spp');
            $nonSppItems   = $tagihanList->where('jenis_tagihan', 'non_spp');
            $tunggakanItems = $tagihanList->where('jenis_tagihan', 'tunggakan');

            $sppNominal = $sppItems->sum('nominal');
            $sppBayar   = $sppItems->sum('nominal_terbayar');

            $nonSppNominal = $nonSppItems->sum('nominal');
            $nonSppBayar   = $nonSppItems->sum('nominal_terbayar');

            $tunggakanNominal = $tunggakanItems->sum('nominal');
            $tunggakanBayar   = $tunggakanItems->sum('nominal_terbayar');

            $totalNominal   = $tagihanList->sum('nominal');
            $totalBayar     = $tagihanList->sum('nominal_terbayar');
            $sisaPembayaran = max(0, $totalNominal - $totalBayar);

            // Hitung count hanya item yang nominal > 0 agar tidak membingungkan
            $tagihanCountDisplay = $tagihanList->where('nominal', '>', 0)->count();

            $rekapSiswa[] = [
                'siswa'              => $siswa,
                'va_spp'             => $vaSpp,
                'va_non_spp'         => $vaNonSpp,
                'va_tunggakan'       => $vaTunggakan,
                'spp_nominal'        => $sppNominal,
                'spp_bayar'          => $sppBayar,
                'spp_has_record'     => $sppItems->count() > 0,
                'non_spp_nominal'    => $nonSppNominal,
                'non_spp_bayar'      => $nonSppBayar,
                'non_spp_has_record' => $nonSppItems->count() > 0,
                'tunggakan_nominal'  => $tunggakanNominal,
                'tunggakan_bayar'    => $tunggakanBayar,
                'tunggakan_has_record' => $tunggakanItems->count() > 0,
                'total_nominal'      => $totalNominal,
                'total_bayar'        => $totalBayar,
                'sisa_pembayaran'    => $sisaPembayaran,
                'tagihan_count'      => $tagihanCountDisplay,
                'tagihan_items'      => $tagihanList,
            ];
        }

        // Query Transaksi Tagihan Detail (Format Report BPD DIY)
        $trxQuery = TagihanPembayaran::with(['siswa', 'kelas', 'tahunAjaran', 'semester']);
        if ($selectedKelas) {
            $trxQuery->where('id_kelas', $selectedKelas);
        }
        if ($selectedJenis) {
            $trxQuery->where('jenis_tagihan', $selectedJenis);
        }
        if ($selectedStatus) {
            $trxQuery->where('status', $selectedStatus);
        }
        if ($search) {
            $trxQuery->where(function ($q) use ($search) {
                $q->where('nomor_va', 'like', "%{$search}%")
                  ->orWhere('trx_id', 'like', "%{$search}%")
                  ->orWhere('nama_tagihan', 'like', "%{$search}%")
                  ->orWhere('nis', 'like', "%{$search}%")
                  ->orWhereHas('siswa', function ($sq) use ($search) {
                      $sq->where('nama_siswa', 'like', "%{$search}%");
                  });
            });
        }

        $trxList = $trxQuery->orderBy('created_at', 'desc')->paginate(15, ['*'], 'trx_page')->withQueryString();

        // Summary Stats
        $totalNominalAll = TagihanPembayaran::sum('nominal');
        $totalTerbayarAll = TagihanPembayaran::sum('nominal_terbayar');
        $totalBelumBayarAll = max(0, $totalNominalAll - $totalTerbayarAll);
        $totalRecordAll = TagihanPembayaran::count();

        $allSiswaSelect = UserSiswa::where('status', 'aktif')->orderBy('nama_siswa', 'asc')->get();

        return view('pembayaran.rekap-siswa.index', compact(
            'sekolah',
            'setting',
            'rekapSiswa',
            'siswaListPaginated',
            'trxList',
            'allSiswaSelect',
            'kelasList',
            'tahunList',
            'semesterList',
            'selectedTahun',
            'selectedKelas',
            'selectedJenis',
            'selectedStatus',
            'search',
            'totalNominalAll',
            'totalTerbayarAll',
            'totalBelumBayarAll',
            'totalRecordAll'
        ));
    }

    /**
     * Tambah Tagihan Baru untuk Single Siswa
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nis'                 => 'required|exists:user_siswa,nis',
            'jenis_tagihan'       => 'required|in:spp,non_spp,tunggakan',
            'nama_tagihan'        => 'required|string|max:255',
            'nominal'             => 'required|numeric|min:0',
            'tanggal_tagihan'     => 'required|date',
            'tanggal_jatuh_tempo' => 'nullable|date',
            'id_tahun'            => 'nullable|exists:tahun_ajaran,id_tahun',
            'id_semester'         => 'nullable|exists:semester,id_semester',
            'keterangan'          => 'nullable|string',
        ]);

        $setting = SettingPembayaran::getSetting();
        $siswa = UserSiswa::where('nis', $validated['nis'])->firstOrFail();

        // Tentukan kode tagihan: 00 (SPP), 01 (Non SPP), 02 (Tunggakan)
        $kodeTagihan = $setting->kode_spp;
        if ($validated['jenis_tagihan'] === 'non_spp') {
            $kodeTagihan = $setting->kode_non_spp;
        } elseif ($validated['jenis_tagihan'] === 'tunggakan') {
            $kodeTagihan = $setting->kode_tunggakan;
        }

        // Tentukan tahun untuk VA
        $tahunAjaranObj = $validated['id_tahun'] ? TahunAjaran::find($validated['id_tahun']) : TahunAjaran::where('status', 'aktif')->first();
        $tahunVal = date('Y', strtotime($validated['tanggal_tagihan']));
        if ($tahunAjaranObj && !empty($tahunAjaranObj->tahun_ajaran)) {
            $parts = explode('/', $tahunAjaranObj->tahun_ajaran);
            $tahunVal = trim(end($parts));
        }

        // Rumus VA = id_institusi + tahun + '0' + nis + kode_tagihan
        $vaNumber = TagihanPembayaran::generateVaNumber(
            $setting->id_institusi,
            $tahunVal,
            $siswa->nis,
            $kodeTagihan,
            $setting->format_tahun
        );

        TagihanPembayaran::create([
            'trx_id'              => TagihanPembayaran::generateTrxId(),
            'nis'                 => $siswa->nis,
            'id_kelas'            => $siswa->id_kelas,
            'id_tahun'            => $validated['id_tahun'] ?? ($tahunAjaranObj->id_tahun ?? null),
            'id_semester'         => $validated['id_semester'] ?? (Semester::where('status', 'aktif')->value('id_semester') ?? null),
            'kode_tagihan'        => $kodeTagihan,
            'jenis_tagihan'       => $validated['jenis_tagihan'],
            'nama_tagihan'        => $validated['nama_tagihan'],
            'nomor_va'            => $vaNumber,
            'nominal'             => $validated['nominal'],
            'nominal_terbayar'    => 0,
            'status'              => 'belum_bayar',
            'tanggal_tagihan'     => $validated['tanggal_tagihan'],
            'tanggal_jatuh_tempo' => $validated['tanggal_jatuh_tempo'] ?? null,
            'keterangan'          => $validated['keterangan'] ?? null,
        ]);

        return redirect()->route('pembayaran.rekap-siswa.index')
            ->with('success', "Tagihan & Nomor VA ({$vaNumber}) berhasil dibuat untuk {$siswa->nama_siswa}.");
    }

    /**
     * Update Data Tagihan
     */
    public function update(Request $request, $id)
    {
        $tagihan = TagihanPembayaran::findOrFail($id);

        $validated = $request->validate([
            'nama_tagihan'        => 'required|string|max:255',
            'nominal'             => 'required|numeric|min:0',
            'tanggal_tagihan'     => 'required|date',
            'tanggal_jatuh_tempo' => 'nullable|date',
            'keterangan'          => 'nullable|string',
        ]);

        $tagihan->update($validated);

        return redirect()->route('pembayaran.rekap-siswa.index')
            ->with('success', "Tagihan VA #{$tagihan->nomor_va} berhasil diperbarui.");
    }

    /**
     * Update Status Pembayaran (Lunas / Belum Bayar / Batal)
     */
    public function updateStatus(Request $request, $id)
    {
        $tagihan = TagihanPembayaran::findOrFail($id);

        $validated = $request->validate([
            'status'           => 'required|in:belum_bayar,lunas,batal',
            'nominal_terbayar' => 'nullable|numeric|min:0',
            'tanggal_bayar'    => 'nullable|date_format:Y-m-d\TH:i',
        ]);

        $status = $validated['status'];
        $nominalTerbayar = $validated['nominal_terbayar'] ?? ($status === 'lunas' ? $tagihan->nominal : 0);
        $tanggalBayar = $validated['tanggal_bayar'] ?? ($status === 'lunas' ? now() : null);

        $tagihan->update([
            'status'           => $status,
            'nominal_terbayar' => $nominalTerbayar,
            'tanggal_bayar'    => $tanggalBayar,
        ]);

        return redirect()->route('pembayaran.rekap-siswa.index')
            ->with('success', "Status tagihan VA #{$tagihan->nomor_va} berhasil diubah menjadi " . strtoupper($status) . ".");
    }

    /**
     * Hapus Data Tagihan
     */
    public function destroy($id)
    {
        $tagihan = TagihanPembayaran::findOrFail($id);
        $vaNumber = $tagihan->nomor_va;
        $tagihan->delete();

        return redirect()->route('pembayaran.rekap-siswa.index')
            ->with('success', "Tagihan VA #{$vaNumber} berhasil dihapus.");
    }

    /**
     * Cetak Slip Pembayaran / VA Card Siswa
     */
    public function cetakSlip($id)
    {
        $tagihan = TagihanPembayaran::with(['siswa', 'kelas', 'tahunAjaran', 'semester'])->findOrFail($id);
        $sekolah = Sekolah::first();
        $setting = SettingPembayaran::getSetting();

        return view('pembayaran.rekap-siswa.slip', compact('tagihan', 'sekolah', 'setting'));
    }

    /**
     * Cetak Rekap Tagihan Per Kelas (PDF / Print)
     */
    public function cetakRekapKelas(Request $request)
    {
        $sekolah  = Sekolah::first();
        $setting  = SettingPembayaran::getSetting();

        $selectedKelas = $request->get('id_kelas', null);
        $selectedTahun = $request->get('id_tahun',
            TahunAjaran::where('status', 'aktif')->value('id_tahun'));

        $kelasList = Kelas::where('status', 'aktif')
            ->orderBy('tingkat')->orderBy('rombel')->get();

        $kelasObj = $selectedKelas ? Kelas::find($selectedKelas) : null;

        // Tahun ajaran
        $tahunAjaranObj = $selectedTahun
            ? TahunAjaran::find($selectedTahun)
            : TahunAjaran::where('status', 'aktif')->first();

        $tahunVal = date('Y');
        if ($tahunAjaranObj && !empty($tahunAjaranObj->tahun)) {
            $parts    = explode('/', $tahunAjaranObj->tahun);
            $tahunVal = trim($parts[0]);
        }

        // Query siswa
        $siswaQuery = UserSiswa::with(['kelas', 'detail'])->where('status', 'aktif');
        if ($selectedKelas) {
            $siswaQuery->where('id_kelas', $selectedKelas);
        }
        $siswaList = $siswaQuery->orderBy('nama_siswa', 'asc')->get();

        // Olah rekap per siswa
        $rekapSiswa = [];
        $grandTotal = 0;
        $grandBayar = 0;
        $grandSisa  = 0;

        foreach ($siswaList as $siswa) {
            $tagihanList = TagihanPembayaran::where('nis', $siswa->nis)->get();

            $sppNominal       = $tagihanList->where('jenis_tagihan', 'spp')->sum('nominal');
            $sppBayar         = $tagihanList->where('jenis_tagihan', 'spp')->sum('nominal_terbayar');
            $nonSppNominal    = $tagihanList->where('jenis_tagihan', 'non_spp')->sum('nominal');
            $nonSppBayar      = $tagihanList->where('jenis_tagihan', 'non_spp')->sum('nominal_terbayar');
            $tunggakanNominal = $tagihanList->where('jenis_tagihan', 'tunggakan')->sum('nominal');
            $tunggakanBayar   = $tagihanList->where('jenis_tagihan', 'tunggakan')->sum('nominal_terbayar');
            $totalNominal     = $tagihanList->sum('nominal');
            $totalBayar       = $tagihanList->sum('nominal_terbayar');
            $sisaPembayaran   = max(0, $totalNominal - $totalBayar);

            $grandTotal += $totalNominal;
            $grandBayar += $totalBayar;
            $grandSisa  += $sisaPembayaran;

            $rekapSiswa[] = [
                'siswa'             => $siswa,
                'spp_nominal'       => $sppNominal,
                'spp_bayar'         => $sppBayar,
                'non_spp_nominal'   => $nonSppNominal,
                'non_spp_bayar'     => $nonSppBayar,
                'tunggakan_nominal' => $tunggakanNominal,
                'tunggakan_bayar'   => $tunggakanBayar,
                'total_nominal'     => $totalNominal,
                'total_bayar'       => $totalBayar,
                'sisa_pembayaran'   => $sisaPembayaran,
                'tagihan_count'     => $tagihanList->count(),
            ];
        }

        // Timestamp terakhir data ditarik dari BPD
        $lastSyncAt = TagihanPembayaran::where('keterangan', 'like', '%BPD DIY%')
            ->max('created_at');

        return view('pembayaran.rekap-siswa.cetak-kelas', compact(
            'sekolah',
            'setting',
            'kelasObj',
            'kelasList',
            'tahunAjaranObj',
            'rekapSiswa',
            'grandTotal',
            'grandBayar',
            'grandSisa',
            'selectedKelas',
            'selectedTahun',
            'lastSyncAt'
        ));
    }



    /**
     * AJAX: Ambil data tagihan dari portal BPD DIY berdasarkan NIS.
     * Mengembalikan JSON preview data tagihan (TRX ID, NO VA, NAMA VA, nominal, status).
     *
     * GET /pembayaran/rekap-siswa/fetch-bpd?nis=14775
     */
    public function fetchFromBpd(Request $request)
    {
        $nis = trim($request->get('nis', ''));
        if (empty($nis)) {
            return response()->json([
                'success' => false,
                'message' => 'NIS harus diisi untuk pencarian data BPD DIY.',
                'data'    => [],
            ]);
        }

        $setting = SettingPembayaran::getSetting();

        if ($setting->status_api !== 'aktif') {
            return response()->json([
                'success' => false,
                'message' => 'API BPD DIY belum diaktifkan. Silakan aktifkan di Setting Konfigurasi.',
                'data'    => [],
            ]);
        }

        try {
            $service = new BpdDiyService($setting);

            // Login ke portal BPD DIY
            $loginOk = $service->login();
            if (!$loginOk) {
                // Jika login gagal, coba langsung fetch (mungkin sudah ada session di server)
                \Log::warning('[BPD DIY] Login gagal, mencoba fetch tanpa login ulang.');
            }

            // Ambil data tagihan berdasarkan NIS
            $result = $service->fetchTagihanByNis($nis);

            // Enrich data: cocokkan dengan setting format VA
            if ($result['success'] && !empty($result['data'])) {
                $tahunAjaranObj = TahunAjaran::where('status', 'aktif')->first();
                $tahunVal = date('Y');
                if ($tahunAjaranObj && !empty($tahunAjaranObj->tahun)) {
                    $parts    = explode('/', $tahunAjaranObj->tahun);
                    $tahunVal = trim($parts[0]);
                }

                // Generate VA lokal untuk referensi
                $vaRefSpp       = TagihanPembayaran::generateVaNumber($setting->id_institusi, $tahunVal, $nis, $setting->kode_spp,       $setting->format_tahun);
                $vaRefNonSpp    = TagihanPembayaran::generateVaNumber($setting->id_institusi, $tahunVal, $nis, $setting->kode_non_spp,   $setting->format_tahun);
                $vaRefTunggakan = TagihanPembayaran::generateVaNumber($setting->id_institusi, $tahunVal, $nis, $setting->kode_tunggakan, $setting->format_tahun);

                // Enrich setiap row dengan label jenis tagihan berdasarkan match VA atau 2 digit terakhir nomor VA
                foreach ($result['data'] as &$row) {
                    $noVaClean   = preg_replace('/\s+/', '', (string) $row['nomor_va']);
                    $kodeTagihan = substr($noVaClean, -2);

                    if ($noVaClean === $vaRefSpp || $kodeTagihan === (string)$setting->kode_spp || $row['jenis_tagihan'] === 'spp') {
                        $row['jenis_tagihan'] = 'spp';
                        $row['kode_label']    = 'SPP (' . ($setting->kode_spp ?? '00') . ')';
                        $row['va_match']      = true;
                    } elseif ($noVaClean === $vaRefNonSpp || $kodeTagihan === (string)$setting->kode_non_spp || $row['jenis_tagihan'] === 'non_spp') {
                        $row['jenis_tagihan'] = 'non_spp';
                        $row['kode_label']    = 'Non SPP (' . ($setting->kode_non_spp ?? '01') . ')';
                        $row['va_match']      = true;
                    } elseif ($noVaClean === $vaRefTunggakan || $kodeTagihan === (string)$setting->kode_tunggakan || $row['jenis_tagihan'] === 'tunggakan') {
                        $row['jenis_tagihan'] = 'tunggakan';
                        $row['kode_label']    = 'Tunggakan (' . ($setting->kode_tunggakan ?? '02') . ')';
                        $row['va_match']      = true;
                    } else {
                        $row['kode_label'] = 'Kode: ' . $kodeTagihan;
                        $row['va_match']   = true;
                    }
                    $row['nomor_va'] = $noVaClean;
                }
                unset($row);
            }

            // Tambahkan info siswa jika ditemukan
            $siswa = UserSiswa::where('nis', $nis)->with('kelas')->first();
            $result['siswa'] = $siswa ? [
                'nis'        => $siswa->nis,
                'nama_siswa' => $siswa->nama_siswa,
                'kelas'      => $siswa->kelas->nama_kelas ?? '-',
            ] : null;

            return response()->json($result);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi error saat mengambil data dari BPD DIY: ' . $e->getMessage(),
                'data'    => [],
            ]);
        }
    }

    /**
     * POST: Simpan hasil fetch BPD DIY ke tabel tagihan_pembayaran.
     * Menerima array data tagihan dari frontend (hasil fetchFromBpd).
     *
     * POST /pembayaran/rekap-siswa/sync-bpd
     */
    public function syncFromBpd(Request $request)
    {
        $validated = $request->validate([
            'nis'           => 'required|exists:user_siswa,nis',
            'rows'          => 'required|array|min:1',
            'rows.*.trx_id'        => 'required|string',
            'rows.*.nomor_va'      => 'required|string',
            'rows.*.jenis_tagihan' => 'required|in:spp,non_spp,tunggakan',
            'rows.*.nominal'       => 'required|numeric|min:0',
            'rows.*.nama_tagihan'  => 'nullable|string',
        ]);

        $nis     = $validated['nis'];
        $siswa   = UserSiswa::where('nis', $nis)->with('kelas')->firstOrFail();
        $setting = SettingPembayaran::getSetting();

        $tahunAjaranObj = TahunAjaran::where('status', 'aktif')->first();
        $tahunVal = date('Y');
        if ($tahunAjaranObj && !empty($tahunAjaranObj->tahun_ajaran)) {
            $parts    = explode('/', $tahunAjaranObj->tahun_ajaran);
            $tahunVal = trim(end($parts));
        }
        $idTahun    = $tahunAjaranObj->id_tahun ?? null;
        $idSemester = Semester::where('status', 'aktif')->value('id_semester');

        $saved   = 0;
        $updated = 0;
        $skipped = 0;

        foreach ($validated['rows'] as $rowData) {
            $trxId   = $rowData['trx_id'];
            $noVa    = $rowData['nomor_va'];
            $jenis   = $rowData['jenis_tagihan'];
            $nominal = (float) $rowData['nominal'];
            $status  = $rowData['status'] ?? 'belum_bayar';

            // Tentukan kode tagihan
            $kodeTagihan = match($jenis) {
                'non_spp'   => $setting->kode_non_spp,
                'tunggakan' => $setting->kode_tunggakan,
                default     => $setting->kode_spp,
            };

            $namaTagihan = $rowData['nama_tagihan'] ?? match($jenis) {
                'spp'       => 'SPP ' . $tahunVal,
                'non_spp'   => 'Non SPP ' . $tahunVal,
                'tunggakan' => 'Tunggakan ' . $tahunVal,
            };

            $nominalTerbayar = (float) ($rowData['nominal_terbayar'] ?? 0);
            if ($status === 'lunas') {
                $nominalTerbayar = $nominal;
            }

            // Cek apakah sudah ada (berdasarkan trx_id atau nomor_va)
            $existing = TagihanPembayaran::where('trx_id', $trxId)
                ->orWhere('nomor_va', $noVa)
                ->first();

            if ($existing) {
                // Update nominal & status dari BPD
                $existing->update([
                    'nominal'          => $nominal,
                    'nominal_terbayar' => $nominalTerbayar,
                    'status'           => $status === 'lunas' ? 'lunas' : ($status === 'batal' ? 'batal' : 'belum_bayar'),
                    'tanggal_bayar'    => $status === 'lunas' ? ($rowData['tanggal_bayar'] ?? now()) : null,
                ]);
                $updated++;
            } else {
                // Buat record baru
                TagihanPembayaran::create([
                    'trx_id'           => $trxId,
                    'nis'              => $nis,
                    'id_kelas'         => $siswa->id_kelas,
                    'id_tahun'         => $idTahun,
                    'id_semester'      => $idSemester,
                    'kode_tagihan'     => $kodeTagihan,
                    'jenis_tagihan'    => $jenis,
                    'nama_tagihan'     => $namaTagihan,
                    'nomor_va'         => $noVa,
                    'nominal'          => $nominal,
                    'nominal_terbayar' => $nominalTerbayar,
                    'status'           => $status === 'lunas' ? 'lunas' : 'belum_bayar',
                    'tanggal_tagihan'  => now()->toDateString(),
                    'tanggal_bayar'    => $status === 'lunas' ? ($rowData['tanggal_bayar'] ?? now()) : null,
                    'keterangan'       => 'Disinkronkan dari portal BPD DIY',
                ]);
                $saved++;
            }
        }

        return response()->json([
            'success' => true,
            'message' => "Sinkronisasi selesai: {$saved} baru disimpan, {$updated} diperbarui, {$skipped} dilewati.",
            'saved'   => $saved,
            'updated' => $updated,
        ]);
    }
}
