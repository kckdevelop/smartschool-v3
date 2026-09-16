<?php

namespace App\Http\Controllers\Pembayaran;

use App\Http\Controllers\Controller;
use App\Models\Kelas;
use App\Models\Sekolah;
use App\Models\SettingPembayaran;
use App\Models\TagihanPembayaran;
use App\Models\TahunAjaran;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\StreamedResponse;

class LaporanTransaksiVaController extends Controller
{
    /**
     * Tampilkan Halaman Laporan Transaksi VA dari Database Lokal
     */
    public function index(Request $request)
    {
        $setting    = SettingPembayaran::getSetting();
        $sekolah    = Sekolah::first();
        $tahunAktif = TahunAjaran::where('status', 'aktif')->first();

        $kelasList  = Kelas::where('status', 'aktif')->orderBy('tingkat', 'asc')->orderBy('rombel', 'asc')->get();
        $tahunList  = TahunAjaran::orderBy('id_tahun', 'desc')->get();

        // ── Informasi Waktu Terakhir Data Ditarik / Disinkronkan ──
        $lastPullRecord = TagihanPembayaran::latest('updated_at')->first(['updated_at', 'created_at', 'keterangan']);
        $lastPullTime   = null;
        $lastPullAgo    = null;
        $lastPullKet    = null;

        if ($lastPullRecord && $lastPullRecord->updated_at) {
            Carbon::setLocale('id');
            $lastPullTime = Carbon::parse($lastPullRecord->updated_at)->translatedFormat('d F Y, H:i') . ' WIB';
            $lastPullAgo  = Carbon::parse($lastPullRecord->updated_at)->diffForHumans();
            $lastPullKet  = $lastPullRecord->keterangan;
        }

        // ── Filter Parameters ──
        $search       = trim((string) $request->input('search', ''));
        $jenisTagihan = $request->input('jenis_tagihan', '');
        $status       = $request->input('status', '');
        $idKelas      = $request->input('id_kelas', '');
        $idTahun      = $request->input('id_tahun', '');
        $tglDari      = $request->input('tgl_dari', '');
        $tglSampai    = $request->input('tgl_sampai', '');
        $perPage      = (int) $request->input('per_page', 25);
        if ($perPage <= 0 || $perPage > 500) {
            $perPage = 25;
        }

        // ── Query Builder ──
        $query = TagihanPembayaran::with(['siswa', 'kelas', 'tahunAjaran']);

        // 1. Search Filter
        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('nis', 'like', "%{$search}%")
                  ->orWhere('trx_id', 'like', "%{$search}%")
                  ->orWhere('nomor_va', 'like', "%{$search}%")
                  ->orWhere('nama_tagihan', 'like', "%{$search}%")
                  ->orWhereHas('siswa', function ($sq) use ($search) {
                      $sq->where('nama_siswa', 'like', "%{$search}%")
                        ->orWhere('nis', 'like', "%{$search}%");
                  });
            });
        }

        // 2. Jenis Tagihan
        if (!empty($jenisTagihan)) {
            $query->where('jenis_tagihan', $jenisTagihan);
        }

        // 3. Status Filter
        if (!empty($status) && $status !== 'semua') {
            if ($status === 'lunas') {
                $query->where('status', 'lunas');
            } elseif ($status === 'sebagian') {
                $query->where('status', 'belum_bayar')
                      ->where('nominal_terbayar', '>', 0);
            } elseif ($status === 'belum_bayar') {
                $query->where('status', 'belum_bayar')
                      ->where(function ($q) {
                          $q->whereNull('nominal_terbayar')
                            ->orWhere('nominal_terbayar', '<=', 0);
                      });
            }
        }

        // 4. Kelas Filter
        if (!empty($idKelas)) {
            $query->where(function ($q) use ($idKelas) {
                $q->where('id_kelas', $idKelas)
                  ->orWhereHas('siswa', function ($sq) use ($idKelas) {
                      $sq->where('id_kelas', $idKelas);
                  });
            });
        }

        // 5. Tahun Ajaran Filter
        if (!empty($idTahun)) {
            $query->where('id_tahun', $idTahun);
        }

        // 6. Tanggal Range Filter
        if (!empty($tglDari) && !empty($tglSampai)) {
            $query->where(function ($q) use ($tglDari, $tglSampai) {
                $q->whereBetween('tanggal_bayar', [$tglDari . ' 00:00:00', $tglSampai . ' 23:59:59'])
                  ->orWhereBetween('tanggal_tagihan', [$tglDari, $tglSampai])
                  ->orWhereBetween('created_at', [$tglDari . ' 00:00:00', $tglSampai . ' 23:59:59']);
            });
        } elseif (!empty($tglDari)) {
            $query->where(function ($q) use ($tglDari) {
                $q->where('tanggal_bayar', '>=', $tglDari . ' 00:00:00')
                  ->orWhere('tanggal_tagihan', '>=', $tglDari)
                  ->orWhere('created_at', '>=', $tglDari . ' 00:00:00');
            });
        } elseif (!empty($tglSampai)) {
            $query->where(function ($q) use ($tglSampai) {
                $q->where('tanggal_bayar', '<=', $tglSampai . ' 23:59:59')
                  ->orWhere('tanggal_tagihan', '<=', $tglSampai)
                  ->orWhere('created_at', '<=', $tglSampai . ' 23:59:59');
            });
        }

        // ── Summary Metrics for Filtered Data ──
        $summaryQuery = clone $query;
        $totalRecords = $summaryQuery->count();
        $totalNominal = (float) (clone $summaryQuery)->sum('nominal');
        $totalTerbayar = (float) (clone $summaryQuery)->sum('nominal_terbayar');
        $totalSisa = max(0, $totalNominal - $totalTerbayar);
        $countLunas = (clone $summaryQuery)->where('status', 'lunas')->count();
        $countBelum = $totalRecords - $countLunas;

        $summary = [
            'total_records'  => $totalRecords,
            'total_nilai'    => $totalNominal,
            'total_terbayar' => $totalTerbayar,
            'total_sisa'     => $totalSisa,
            'count_lunas'    => $countLunas,
            'count_belum'    => $countBelum,
            'persen_bayar'   => $totalNominal > 0 ? round(($totalTerbayar / $totalNominal) * 100, 1) : 0,
        ];

        // ── Handle Export CSV / Excel ──
        if ($request->input('export') === 'csv' || $request->input('export') === 'excel') {
            return $this->exportCsv($query, $summary, $lastPullTime);
        }

        // ── Handle AJAX JSON Request ──
        if ($request->ajax() || $request->wantsJson()) {
            $tagihanItems = $query->orderBy('updated_at', 'desc')->orderBy('id', 'desc')->paginate($perPage);

            $formattedRows = [];
            foreach ($tagihanItems as $item) {
                $statusFormatted = $item->status;
                if ($item->status === 'belum_bayar' && (float) $item->nominal_terbayar > 0) {
                    $statusFormatted = 'sebagian';
                }

                $namaSiswa = $item->siswa?->nama_siswa ?? $item->nama_tagihan;
                $kelasNama = $item->kelas?->nama_kelas ?? ($item->siswa?->kelas?->nama_kelas ?? '-');

                $waktuTarikText = $item->updated_at ? Carbon::parse($item->updated_at)->format('d/m/Y H:i') : '-';

                $formattedRows[] = [
                    'id'               => $item->id,
                    'trx_id'           => $item->trx_id ?? '-',
                    'nomor_va'         => $item->nomor_va,
                    'nis'              => $item->nis,
                    'nama_siswa'       => $namaSiswa,
                    'kelas'            => $kelasNama,
                    'jenis_tagihan'    => $item->jenis_tagihan,
                    'nama_tagihan'     => $item->nama_tagihan,
                    'nominal'          => (float) $item->nominal,
                    'nominal_terbayar' => (float) $item->nominal_terbayar,
                    'sisa_pembayaran'  => (float) $item->sisa_pembayaran,
                    'status'           => $statusFormatted,
                    'tanggal_bayar'    => $item->tanggal_bayar ? Carbon::parse($item->tanggal_bayar)->format('d/m/Y H:i') : '-',
                    'tanggal_tagihan'  => $item->tanggal_tagihan ? Carbon::parse($item->tanggal_tagihan)->format('d/m/Y') : '-',
                    'waktu_tarik'      => $waktuTarikText,
                    'keterangan'       => $item->keterangan ?? '-',
                ];
            }

            return response()->json([
                'success'       => true,
                'data'          => $formattedRows,
                'summary'       => $summary,
                'last_pull'     => [
                    'time'       => $lastPullTime,
                    'ago'        => $lastPullAgo,
                    'keterangan' => $lastPullKet,
                ],
                'pagination'    => [
                    'current_page' => $tagihanItems->currentPage(),
                    'last_page'    => $tagihanItems->lastPage(),
                    'per_page'     => $tagihanItems->perPage(),
                    'total'        => $tagihanItems->total(),
                    'from'         => $tagihanItems->firstItem() ?? 0,
                    'to'           => $tagihanItems->lastItem() ?? 0,
                ],
            ]);
        }

        // ── Default View Rendering ──
        $tagihanList = $query->orderBy('updated_at', 'desc')->orderBy('id', 'desc')->paginate($perPage)->withQueryString();

        return view('pembayaran.laporan-transaksi.index', compact(
            'setting',
            'sekolah',
            'tahunAktif',
            'kelasList',
            'tahunList',
            'lastPullRecord',
            'lastPullTime',
            'lastPullAgo',
            'lastPullKet',
            'tagihanList',
            'summary',
            'search',
            'jenisTagihan',
            'status',
            'idKelas',
            'idTahun',
            'tglDari',
            'tglSampai',
            'perPage'
        ));
    }

    /**
     * Export Data Laporan ke file CSV / Excel
     */
    private function exportCsv($query, array $summary, ?string $lastPullTime): StreamedResponse
    {
        $filename = 'Laporan_Transaksi_VA_' . date('Ymd_His') . '.csv';

        $headers = [
            'Content-Type'        => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
            'Pragma'              => 'no-cache',
            'Cache-Control'       => 'must-revalidate, post-check=0, pre-check=0',
            'Expires'             => '0',
        ];

        $callback = function () use ($query, $summary, $lastPullTime) {
            $handle = fopen('php://output', 'w');
            // BOM UTF-8 for Excel Indonesian Support
            fputs($handle, "\xEF\xBB\xBF");

            // Meta header
            fputcsv($handle, ['LAPORAN TRANSAKSI VIRTUAL ACCOUNT (DATABASE SMARTSCHOOL)']);
            fputcsv($handle, ['Tanggal Cetak', date('d/m/Y H:i:s')]);
            fputcsv($handle, ['Terakhir Ditarik / Disinkronkan', $lastPullTime ?? '-']);
            fputcsv($handle, ['Total Data', $summary['total_records']]);
            fputcsv($handle, ['Total Tagihan (Rp)', number_format($summary['total_nilai'], 0, ',', '.')]);
            fputcsv($handle, ['Total Terbayar (Rp)', number_format($summary['total_terbayar'], 0, ',', '.')]);
            fputcsv($handle, ['Total Sisa (Rp)', number_format($summary['total_sisa'], 0, ',', '.')]);
            fputcsv($handle, []);

            // Column Headers
            fputcsv($handle, [
                'NO',
                'TRX ID',
                'NOMOR VA',
                'NIS',
                'NAMA SISWA',
                'KELAS',
                'JENIS TAGIHAN',
                'NAMA TAGIHAN',
                'NOMINAL TAGIHAN (RP)',
                'TERBAYAR (RP)',
                'SISA PEMBAYARAN (RP)',
                'STATUS',
                'TANGGAL BAYAR',
                'TANGGAL TAGIHAN',
                'WAKTU DITARIK KE DB',
                'KETERANGAN',
            ]);

            $no = 1;
            $query->orderBy('updated_at', 'desc')->orderBy('id', 'desc')->chunk(500, function ($items) use ($handle, &$no) {
                foreach ($items as $item) {
                    $statusText = 'Belum Lunas';
                    if ($item->status === 'lunas') {
                        $statusText = 'Lunas';
                    } elseif ((float) $item->nominal_terbayar > 0 && (float) $item->nominal_terbayar < (float) $item->nominal) {
                        $statusText = 'Sebagian';
                    }

                    $namaSiswa = $item->siswa?->nama_siswa ?? $item->nama_tagihan;
                    $kelasNama = $item->kelas?->nama_kelas ?? ($item->siswa?->kelas?->nama_kelas ?? '-');
                    $waktuTarik = $item->updated_at ? Carbon::parse($item->updated_at)->format('d/m/Y H:i:s') : '-';

                    fputcsv($handle, [
                        $no++,
                        $item->trx_id ?? '-',
                        "'" . (string) $item->nomor_va,
                        "'" . (string) $item->nis,
                        $namaSiswa,
                        $kelasNama,
                        strtoupper($item->jenis_tagihan ?? '-'),
                        $item->nama_tagihan,
                        (float) $item->nominal,
                        (float) $item->nominal_terbayar,
                        (float) $item->sisa_pembayaran,
                        $statusText,
                        $item->tanggal_bayar ? Carbon::parse($item->tanggal_bayar)->format('d/m/Y H:i') : '-',
                        $item->tanggal_tagihan ? Carbon::parse($item->tanggal_tagihan)->format('d/m/Y') : '-',
                        $waktuTarik,
                        $item->keterangan ?? '-',
                    ]);
                }
            });

            fclose($handle);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Kosongkan seluruh data transaksi tagihan di database lokal
     * Endpoint: POST /pembayaran/laporan-transaksi/clear-database
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

            Log::info("[LaporanTransaksiVaController] Berhasil mengosongkan seluruh database transaksi tagihan lokal ({$count} record dihapus).");

            return response()->json([
                'success'       => true,
                'message'       => "Berhasil mengosongkan seluruh data transaksi tagihan ({$count} data) dari database.",
                'deleted_count' => $count,
            ]);
        } catch (\Exception $e) {
            Log::error('[LaporanTransaksiVaController clearDatabase] Error: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Gagal mengosongkan database laporan transaksi: ' . $e->getMessage(),
            ], 500);
        }
    }
}
