<?php

namespace App\Http\Controllers\Pembayaran;

use App\Http\Controllers\Controller;

use App\Models\Kelas;
use App\Models\UserSiswa;
use App\Models\TagihanPembayaran;
use App\Models\SettingPembayaran;
use App\Models\TahunAjaran;
use App\Models\Semester;
use App\Models\Sekolah;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RekapKelasController extends Controller
{
    /**
     * Tampilkan Rekapitulasi Data Tagihan Per Kelas
     */
    public function index(Request $request)
    {
        $sekolah = Sekolah::first();
        $setting = SettingPembayaran::getSetting();

        $tahunList = TahunAjaran::orderBy('id_tahun', 'desc')->get();
        $semesterList = Semester::all();
        $kelasList = Kelas::where('status', 'aktif')->orderBy('tingkat', 'asc')->orderBy('rombel', 'asc')->get();

        $selectedTahun = $request->get('id_tahun', TahunAjaran::where('status', 'aktif')->value('id_tahun') ?? ($tahunList->first()->id_tahun ?? null));
        $selectedSemester = $request->get('id_semester', Semester::where('status', 'aktif')->value('id_semester') ?? ($semesterList->first()->id_semester ?? null));
        $selectedKelas = $request->get('id_kelas', null);
        $selectedJenis = $request->get('jenis_tagihan', null);

        // Subquery or aggregation per kelas
        $kelasQuery = Kelas::where('status', 'aktif');
        if ($selectedKelas) {
            $kelasQuery->where('id_kelas', $selectedKelas);
        }
        $classes = $kelasQuery->orderBy('tingkat', 'asc')->orderBy('rombel', 'asc')->get();


        $rekapData = [];
        $totalSiswaAll = 0;
        $totalTagihanSpp = 0;
        $totalTagihanNonSpp = 0;
        $totalTunggakan = 0;
        $totalNominalAll = 0;
        $totalLunasAll = 0;
        $totalBelumLunasAll = 0;

        foreach ($classes as $kelas) {
            $jumlahSiswa = UserSiswa::where('id_kelas', $kelas->id_kelas)->where('status', 'aktif')->count();

            $tagihanQuery = TagihanPembayaran::where('id_kelas', $kelas->id_kelas);
            if ($selectedTahun) {
                $tagihanQuery->where('id_tahun', $selectedTahun);
            }
            if ($selectedSemester) {
                $tagihanQuery->where('id_semester', $selectedSemester);
            }
            if ($selectedJenis) {
                $tagihanQuery->where('jenis_tagihan', $selectedJenis);
            }

            $tagihanList = $tagihanQuery->get();

            $spp = $tagihanList->where('jenis_tagihan', 'spp')->sum('nominal');
            $nonSpp = $tagihanList->where('jenis_tagihan', 'non_spp')->sum('nominal');
            $tunggakan = $tagihanList->where('jenis_tagihan', 'tunggakan')->sum('nominal');
            $totalNominal = $tagihanList->sum('nominal');
            $totalLunas = $tagihanList->where('status', 'lunas')->sum('nominal');
            $totalBelumLunas = $tagihanList->where('status', 'belum_bayar')->sum('nominal');
            $countLunas = $tagihanList->where('status', 'lunas')->count();
            $countBelumLunas = $tagihanList->where('status', 'belum_bayar')->count();

            $rekapData[] = [
                'kelas' => $kelas,
                'jumlah_siswa' => $jumlahSiswa,
                'total_spp' => $spp,
                'total_non_spp' => $nonSpp,
                'total_tunggakan' => $tunggakan,
                'total_nominal' => $totalNominal,
                'total_lunas' => $totalLunas,
                'total_belum_lunas' => $totalBelumLunas,
                'count_lunas' => $countLunas,
                'count_belum_lunas' => $countBelumLunas,
                'count_tagihan' => $tagihanList->count(),
            ];

            $totalSiswaAll += $jumlahSiswa;
            $totalTagihanSpp += $spp;
            $totalTagihanNonSpp += $nonSpp;
            $totalTunggakan += $tunggakan;
            $totalNominalAll += $totalNominal;
            $totalLunasAll += $totalLunas;
            $totalBelumLunasAll += $totalBelumLunas;
        }

        return view('pembayaran.rekap-kelas.index', compact(
            'sekolah',
            'setting',
            'tahunList',
            'semesterList',
            'kelasList',
            'selectedTahun',
            'selectedSemester',
            'selectedKelas',
            'selectedJenis',
            'rekapData',
            'totalSiswaAll',
            'totalTagihanSpp',
            'totalTagihanNonSpp',
            'totalTunggakan',
            'totalNominalAll',
            'totalLunasAll',
            'totalBelumLunasAll'
        ));
    }

    /**
     * Generate Tagihan & Nomor VA Massal per Kelas
     */
    public function generateClassBilling(Request $request)
    {
        $validated = $request->validate([
            'id_kelas' => 'required|exists:kelas,id_kelas',
            'jenis_tagihan' => 'required|in:spp,non_spp,tunggakan',
            'nama_tagihan' => 'required|string|max:255',
            'nominal' => 'required|numeric|min:0',
            'tanggal_tagihan' => 'required|date',
            'tanggal_jatuh_tempo' => 'nullable|date',
            'id_tahun' => 'nullable|exists:tahun_ajaran,id_tahun',
            'id_semester' => 'nullable|exists:semester,id_semester',
        ]);

        $setting = SettingPembayaran::getSetting();
        $kelas = Kelas::findOrFail($validated['id_kelas']);
        $siswaList = UserSiswa::where('id_kelas', $kelas->id_kelas)->where('status', 'aktif')->get();

        if ($siswaList->isEmpty()) {
            return redirect()->back()->with('error', "Tidak ada siswa aktif di kelas {$kelas->nama_kelas}.");
        }

        // Tentukan kode tagihan
        $kodeTagihan = $setting->kode_spp; // default 00
        if ($validated['jenis_tagihan'] === 'non_spp') {
            $kodeTagihan = $setting->kode_non_spp; // 01
        } elseif ($validated['jenis_tagihan'] === 'tunggakan') {
            $kodeTagihan = $setting->kode_tunggakan; // 02
        }

        // Year for VA: format YYYY or YY
        $tahunAjaranObj = $validated['id_tahun'] ? TahunAjaran::find($validated['id_tahun']) : TahunAjaran::where('status', 'aktif')->first();
        $tahunVal = date('Y', strtotime($validated['tanggal_tagihan']));
        if ($tahunAjaranObj && !empty($tahunAjaranObj->tahun_ajaran)) {
            // e.g. "2025/2026" -> take "2026"
            $parts = explode('/', $tahunAjaranObj->tahun_ajaran);
            $tahunVal = trim(end($parts));
        }

        DB::beginTransaction();
        try {
            $countGenerated = 0;
            foreach ($siswaList as $siswa) {
                // Generasi VA: id_institusi + tahun + nis + kode_tagihan
                $vaNumber = TagihanPembayaran::generateVaNumber(
                    $setting->id_institusi,
                    $tahunVal,
                    $siswa->nis,
                    $kodeTagihan,
                    $setting->format_tahun
                );

                TagihanPembayaran::create([
                    'trx_id' => TagihanPembayaran::generateTrxId(),
                    'nis' => $siswa->nis,

                    'id_kelas' => $kelas->id_kelas,
                    'id_tahun' => $validated['id_tahun'] ?? ($tahunAjaranObj->id_tahun ?? null),
                    'id_semester' => $validated['id_semester'] ?? (Semester::where('status', 'aktif')->value('id_semester') ?? null),
                    'kode_tagihan' => $kodeTagihan,
                    'jenis_tagihan' => $validated['jenis_tagihan'],
                    'nama_tagihan' => $validated['nama_tagihan'],
                    'nomor_va' => $vaNumber,
                    'nominal' => $validated['nominal'],
                    'nominal_terbayar' => 0,
                    'status' => 'belum_bayar',
                    'tanggal_tagihan' => $validated['tanggal_tagihan'],
                    'tanggal_jatuh_tempo' => $validated['tanggal_jatuh_tempo'] ?? null,
                ]);
                $countGenerated++;
            }
            DB::commit();

            return redirect()->back()->with('success', "Berhasil membuat {$countGenerated} tagihan VA untuk kelas {$kelas->nama_kelas}.");
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', "Gagal membuat tagihan per kelas: " . $e->getMessage());
        }
    }
}
