<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Kelas;
use App\Models\UserSiswa;
use App\Models\Presensi;
use App\Models\LogAbsensi;
use App\Models\Sekolah;
use App\Models\LogWaPresensi;
use App\Services\FonnteService;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class WaPresensiController extends Controller
{
    /**
     * Helper to format WA presensi message template.
     */
    public static function formatWaMessage(string $template, UserSiswa $siswa, string $tanggal, ?Presensi $presensi, Sekolah $sekolah): array
    {
        $statusLabel = 'Belum Presensi';
        $jamPresensi = '-';
        $keterangan = '-';

        if ($presensi) {
            $keterangan = $presensi->keterangan ?? '-';
            $st = strval($presensi->status);
            switch ($st) {
                case '1':
                case 'Hadir':
                    $statusLabel = 'Hadir';
                    $jamPresensi = $presensi->jam ? Carbon::parse($presensi->jam)->format('H:i:s') . ' WIB' : '-';
                    break;
                case '2':
                case 'Sakit':
                    $statusLabel = 'Sakit';
                    break;
                case '3':
                case 'Izin':
                    $statusLabel = 'Izin';
                    break;
                case '4':
                case 'Alfa':
                case 'Alpha':
                    $statusLabel = 'Alfa';
                    break;
                default:
                    $statusLabel = $st;
                    break;
            }
        } else {
            // Check LogAbsensi if Presensi is not populated yet
            $logAbsensi = LogAbsensi::where('nis', $siswa->nis)->whereDate('tanggal', $tanggal)->first();
            if ($logAbsensi) {
                $statusLabel = 'Hadir';
                $jamPresensi = $logAbsensi->jam ? Carbon::parse($logAbsensi->jam)->format('H:i:s') . ' WIB' : '-';
                $keterangan = $logAbsensi->keterangan ?? '-';
            }
        }

        $namaKelas = $siswa->kelas ? ($siswa->kelas->tingkat . ' ' . $siswa->kelas->rombel . ' (' . ($siswa->kelas->jurusan->nama_jurusan ?? '') . ')') : '-';
        $tanggalFormat = Carbon::parse($tanggal)->translatedFormat('l, d F Y');

        $replacements = [
            '{nama_siswa}'   => $siswa->nama_siswa,
            '{nis}'          => $siswa->nis,
            '{kelas}'        => $namaKelas,
            '{tanggal}'      => $tanggalFormat,
            '{status}'       => $statusLabel,
            '{jam_presensi}' => $jamPresensi,
            '{keterangan}'   => $keterangan,
            '{nama_sekolah}' => $sekolah->nama_sekolah ?? 'SmartSchool',
        ];

        $pesan = str_replace(array_keys($replacements), array_values($replacements), $template);

        return [
            'pesan'           => $pesan,
            'status_presensi' => $statusLabel,
            'jam_presensi'    => $jamPresensi,
        ];
    }

    /**
     * Process WA send for a single student.
     */
    public static function processSendSingleStudent(UserSiswa $siswa, string $tanggal, string $template, Sekolah $sekolah, FonnteService $fonnteService): array
    {
        $presensi = Presensi::where('nis', $siswa->nis)->whereDate('tanggal', $tanggal)->first();
        $formatted = self::formatWaMessage($template, $siswa, $tanggal, $presensi, $sekolah);

        $detail = $siswa->detail;
        $targetNo = null;

        if ($detail) {
            $targetNo = $detail->no_wa_presensi ?? $detail->no_telp_ayah ?? $detail->no_telp_ibu ?? $detail->no_telp_wali;
        }

        if (empty($targetNo)) {
            $log = LogWaPresensi::updateOrCreate(
                ['tanggal' => $tanggal, 'nis' => $siswa->nis],
                [
                    'no_wa'           => null,
                    'status_presensi' => $formatted['status_presensi'],
                    'jam_presensi'    => $formatted['jam_presensi'],
                    'pesan'           => $formatted['pesan'],
                    'status_wa'       => 'dilompati',
                    'response'        => json_encode(['reason' => 'Nomor WA presensi tidak terisi']),
                    'sent_at'         => null,
                ]
            );

            return [
                'success' => false,
                'status'  => 'dilompati',
                'message' => "Nomor WA untuk {$siswa->nama_siswa} tidak terisi.",
                'log'     => $log,
            ];
        }

        // Send via Fonnte
        $res = $fonnteService->sendMessage($targetNo, $formatted['pesan']);

        $statusWa = $res['success'] ? 'terkirim' : 'gagal';

        $log = LogWaPresensi::updateOrCreate(
            ['tanggal' => $tanggal, 'nis' => $siswa->nis],
            [
                'no_wa'           => $targetNo,
                'status_presensi' => $formatted['status_presensi'],
                'jam_presensi'    => $formatted['jam_presensi'],
                'pesan'           => $formatted['pesan'],
                'status_wa'       => $statusWa,
                'response'        => json_encode($res),
                'sent_at'         => $res['success'] ? now() : null,
            ]
        );

        return [
            'success' => $res['success'],
            'status'  => $statusWa,
            'message' => $res['message'],
            'log'     => $log,
        ];
    }

    // ── 1. Halaman Monitoring ──
    public function index(Request $request)
    {
        $sekolah = Sekolah::first();
        $kelasList = Kelas::where('status', 'aktif')
            ->with('jurusan')
            ->orderBy('tingkat')
            ->orderBy('rombel')
            ->get();

        $tanggal = $request->get('tanggal', Carbon::today()->toDateString());
        $id_kelas = $request->get('id_kelas');
        $status_filter = $request->get('status_wa'); // terkirim, gagal, pending, dilompati

        $siswaQuery = UserSiswa::where('status', 'aktif')
            ->with(['kelas.jurusan', 'detail']);

        if ($id_kelas) {
            $siswaQuery->where('id_kelas', $id_kelas);
        }

        $siswaList = $siswaQuery->orderBy('nama_siswa')->get();

        // Get Presensi for target date
        $presensiMap = Presensi::whereDate('tanggal', $tanggal)
            ->get()
            ->keyBy('nis');

        // Get LogAbsensi for target date as fallback
        $logAbsensiMap = LogAbsensi::whereDate('tanggal', $tanggal)
            ->get()
            ->keyBy('nis');

        // Get LogWaPresensi for target date
        $logWaMap = LogWaPresensi::whereDate('tanggal', $tanggal)
            ->get()
            ->keyBy('nis');

        $stats = [
            'total_siswa'   => $siswaList->count(),
            'terkirim'      => 0,
            'gagal'         => 0,
            'pending'       => 0,
            'dilompati'     => 0,
            'hadir'         => 0,
            'sakit'         => 0,
            'izin'          => 0,
            'alfa'          => 0,
            'belum_absensi' => 0,
        ];

        $monitoringData = [];

        foreach ($siswaList as $siswa) {
            $presensi = $presensiMap->get($siswa->nis);
            $logAbs = $logAbsensiMap->get($siswa->nis);
            $logWa = $logWaMap->get($siswa->nis);

            // Determine attendance status
            $stPresensi = 'Belum Presensi';
            $jamPresensi = '-';

            if ($presensi) {
                $st = strval($presensi->status);
                if (in_array($st, ['1', 'Hadir'])) {
                    $stPresensi = 'Hadir';
                    $jamPresensi = $presensi->jam ? Carbon::parse($presensi->jam)->format('H:i:s') . ' WIB' : '-';
                } elseif (in_array($st, ['2', 'Sakit'])) {
                    $stPresensi = 'Sakit';
                } elseif (in_array($st, ['3', 'Izin'])) {
                    $stPresensi = 'Izin';
                } else {
                    $stPresensi = 'Alfa';
                }
            } elseif ($logAbs) {
                $stPresensi = 'Hadir';
                $jamPresensi = $logAbs->jam ? Carbon::parse($logAbs->jam)->format('H:i:s') . ' WIB' : '-';
            }

            // Stat presensi counter
            if ($stPresensi === 'Hadir') $stats['hadir']++;
            elseif ($stPresensi === 'Sakit') $stats['sakit']++;
            elseif ($stPresensi === 'Izin') $stats['izin']++;
            elseif ($stPresensi === 'Alfa') $stats['alfa']++;
            else $stats['belum_absensi']++;

            // WA status
            $statusWa = $logWa ? $logWa->status_wa : 'pending';
            if ($statusWa === 'terkirim') $stats['terkirim']++;
            elseif ($statusWa === 'gagal') $stats['gagal']++;
            elseif ($statusWa === 'dilompati') $stats['dilompati']++;
            else $stats['pending']++;

            // Apply WA status filter
            if ($status_filter && $status_filter !== 'all') {
                if ($statusWa !== $status_filter) {
                    continue;
                }
            }

            $noWa = $siswa->detail->no_wa_presensi ?? $siswa->detail->no_telp_ayah ?? $siswa->detail->no_telp_ibu ?? $siswa->detail->no_telp_wali ?? null;

            $monitoringData[] = [
                'siswa'           => $siswa,
                'no_wa'           => $noWa,
                'status_presensi' => $stPresensi,
                'jam_presensi'    => $jamPresensi,
                'log_wa'          => $logWa,
                'status_wa'       => $statusWa,
            ];
        }

        return view('presensi-siswa.wa-monitoring', compact(
            'sekolah',
            'kelasList',
            'tanggal',
            'id_kelas',
            'status_filter',
            'stats',
            'monitoringData'
        ));
    }

    // ── 2. Update Template ──
    public function updateTemplate(Request $request)
    {
        $request->validate([
            'wa_template_presensi' => 'required|string',
        ]);

        $sekolah = Sekolah::first();
        if ($sekolah) {
            $sekolah->update([
                'wa_template_presensi' => $request->input('wa_template_presensi')
            ]);
        }

        return back()->with('success', 'Template pesan WA presensi berhasil diperbarui.');
    }

    // ── 3. Send WA Masal ──
    public function sendMasal(Request $request)
    {
        $request->validate([
            'tanggal'  => 'required|date',
            'id_kelas' => 'nullable|exists:kelas,id_kelas',
            'nis_list' => 'nullable|array',
        ]);

        $tanggal = $request->input('tanggal');
        $id_kelas = $request->input('id_kelas');
        $nisList = $request->input('nis_list');

        $sekolah = Sekolah::first();
        if (!$sekolah || $sekolah->wa_status !== 'aktif') {
            return response()->json([
                'success' => false,
                'message' => 'Gateway WhatsApp (Fonnte) sedang tidak aktif. Silakan aktifkan di Pengaturan WhatsApp Gateway.'
            ], 400);
        }

        if (empty($sekolah->wa_token)) {
            return response()->json([
                'success' => false,
                'message' => 'Token WhatsApp (Fonnte) belum dikonfigurasi.'
            ], 400);
        }

        $template = $sekolah->wa_template_presensi;
        if (empty($template)) {
            return response()->json([
                'success' => false,
                'message' => 'Template pesan WA presensi belum diatur.'
            ], 400);
        }

        $siswaQuery = UserSiswa::where('status', 'aktif')->with(['detail', 'kelas.jurusan']);

        if (!empty($nisList)) {
            $siswaQuery->whereIn('nis', $nisList);
        } elseif ($id_kelas) {
            $siswaQuery->where('id_kelas', $id_kelas);
        }

        $siswaList = $siswaQuery->get();
        if ($siswaList->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' => 'Tidak ada siswa yang dipilih atau ditemukan.'
            ], 404);
        }

        $fonnteService = new FonnteService();
        $results = [
            'total'     => $siswaList->count(),
            'terkirim'  => 0,
            'gagal'     => 0,
            'dilompati' => 0,
        ];

        foreach ($siswaList as $siswa) {
            $res = self::processSendSingleStudent($siswa, $tanggal, $template, $sekolah, $fonnteService);
            if ($res['status'] === 'terkirim') {
                $results['terkirim']++;
            } elseif ($res['status'] === 'gagal') {
                $results['gagal']++;
            } else {
                $results['dilompati']++;
            }
        }

        return response()->json([
            'success' => true,
            'message' => "Proses pengiriman WA presensi selesai. (Terkirim: {$results['terkirim']}, Gagal: {$results['gagal']}, Dilompati: {$results['dilompati']})",
            'data'    => $results,
        ]);
    }

    // ── 4. Send Single Student (AJAX Retry) ──
    public function sendSingle(Request $request)
    {
        $request->validate([
            'nis'     => 'required|exists:user_siswa,nis',
            'tanggal' => 'required|date',
        ]);

        $nis = $request->input('nis');
        $tanggal = $request->input('tanggal');

        $sekolah = Sekolah::first();
        if (!$sekolah || $sekolah->wa_status !== 'aktif') {
            return response()->json([
                'success' => false,
                'message' => 'WhatsApp Gateway sedang tidak aktif.'
            ], 400);
        }

        $template = $sekolah->wa_template_presensi;
        $siswa = UserSiswa::where('nis', $nis)->with(['detail', 'kelas.jurusan'])->firstOrFail();

        $fonnteService = new FonnteService();
        $res = self::processSendSingleStudent($siswa, $tanggal, $template, $sekolah, $fonnteService);

        return response()->json($res);
    }
}
