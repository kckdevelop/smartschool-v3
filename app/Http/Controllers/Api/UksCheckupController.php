<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\DataCheckup;
use App\Models\Kelas;
use App\Models\UserSiswa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\IOFactory;

class UksCheckupController extends Controller
{
    public function index(Request $request)
    {
        $query = DataCheckup::with(['siswa']);

        if ($request->filled('nis')) {
            $query->where('nis', $request->nis);
        }

        if ($request->filled('search')) {
            $keyword = $request->search;
            $query->where(function ($q) use ($keyword) {
                $q->where('nis', 'like', "%{$keyword}%")
                  ->orWhereHas('siswa', fn($s) => $s->where('nama_siswa', 'like', "%{$keyword}%"));
            });
        }

        if ($request->filled('tahun_ajaran')) {
            $query->whereYear('tanggal', substr($request->tahun_ajaran, 0, 4));
        }

        $perPage = (int) $request->get('per_page', 20);
        $data = $query->orderByDesc('tanggal')->orderByDesc('id_checkup')->paginate($perPage);

        // Flatten data for Flutter
        $mapped = $data->getCollection()->map(function ($item) {
            return [
                'id'            => $item->id_checkup,
                'nis'           => (string) $item->nis,
                'nama_siswa'    => $item->siswa?->nama_siswa,
                'tanggal'       => $item->tanggal?->format('Y-m-d') ?? $item->tanggal,
                'tinggi_badan'  => $item->tinggi_badan,
                'berat_badan'   => $item->berat_badan,
                'imt'           => $item->imt,
                'kategori'      => $item->kategori,
                'tekanan_darah' => $item->tekanan_darah,
                'is_merokok'    => $item->is_merokok ?? 'Tidak',
                'tensi_sistolik'  => null,
                'tensi_diastolik' => null,
                'mata_normal'   => null,
                'kondisi_gigi'  => $item->keterangan,
                'keterangan'    => null,
            ];
        });
        $data->setCollection($mapped);

        return response()->json([
            'success'      => true,
            'data'         => $data->items(),
            'current_page' => $data->currentPage(),
            'last_page'    => $data->lastPage(),
            'total'        => $data->total(),
            'has_more'     => $data->hasMorePages(),
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'nis'           => 'required|integer|exists:user_siswa,nis',
            'tanggal'       => 'required|date',
            'tinggi_badan'  => 'nullable|numeric',
            'berat_badan'   => 'nullable|numeric',
            'tekanan_darah' => 'nullable|string|max:50',
            'is_merokok'    => 'nullable|string',
            'keterangan'    => 'nullable|string',
        ]);

        $data = $request->only(['nis', 'tanggal', 'tinggi_badan', 'berat_badan', 'tekanan_darah', 'is_merokok', 'keterangan']);

        if (isset($data['is_merokok'])) {
            $m = strtolower($data['is_merokok']);
            $data['is_merokok'] = in_array($m, ['ya', 'merokok', '1', 'true', 'y']) ? 'Ya' : 'Tidak';
        } else {
            $data['is_merokok'] = 'Tidak';
        }

        // Calculate IMT
        if (!empty($data['tinggi_badan']) && !empty($data['berat_badan']) && $data['tinggi_badan'] > 0) {
            $tinggiM = $data['tinggi_badan'] / 100;
            $data['imt'] = round($data['berat_badan'] / ($tinggiM * $tinggiM), 2);
            $data['kategori'] = $this->kategoriImt($data['imt']);
        }

        $checkup = DataCheckup::create($data);

        return response()->json([
            'success' => true,
            'message' => 'Data checkup kesehatan berhasil dicatat.',
            'data'    => $checkup->load('siswa'),
        ], 201);
    }

    public function show($id)
    {
        $checkup = DataCheckup::with('siswa')->findOrFail($id);

        return response()->json([
            'success' => true,
            'data'    => $checkup,
        ]);
    }

    public function update(Request $request, $id)
    {
        $checkup = DataCheckup::findOrFail($id);

        $request->validate([
            'tinggi_badan'  => 'nullable|numeric',
            'berat_badan'   => 'nullable|numeric',
            'tekanan_darah' => 'nullable|string|max:50',
            'is_merokok'    => 'nullable|string',
            'keterangan'    => 'nullable|string',
        ]);

        $data = $request->only(['tinggi_badan', 'berat_badan', 'tekanan_darah', 'is_merokok', 'keterangan', 'tanggal']);

        if (isset($data['is_merokok'])) {
            $m = strtolower($data['is_merokok']);
            $data['is_merokok'] = in_array($m, ['ya', 'merokok', '1', 'true', 'y']) ? 'Ya' : 'Tidak';
        }

        if (!empty($data['tinggi_badan']) && !empty($data['berat_badan']) && $data['tinggi_badan'] > 0) {
            $tinggiM = $data['tinggi_badan'] / 100;
            $data['imt'] = round($data['berat_badan'] / ($tinggiM * $tinggiM), 2);
            $data['kategori'] = $this->kategoriImt($data['imt']);
        }

        $checkup->update($data);

        return response()->json([
            'success' => true,
            'message' => 'Data checkup berhasil diperbarui.',
            'data'    => $checkup->load('siswa'),
        ]);
    }

    public function destroy($id)
    {
        DataCheckup::findOrFail($id)->delete();

        return response()->json([
            'success' => true,
            'message' => 'Data checkup berhasil dihapus.',
        ]);
    }

    // ─── Download Template Excel per Kelas ──────────────────────────────────

    public function downloadTemplate($id_kelas)
    {
        $kelas = Kelas::with('siswa')->findOrFail($id_kelas);
        $siswas = $kelas->siswa()->orderBy('nama_siswa')->get();

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Checkup Kesehatan');

        // ── Header Info ──────────────────────────────────────────────────
        $sheet->setCellValue('A1', 'TEMPLATE CHECKUP KESEHATAN SISWA');
        $sheet->setCellValue('A2', 'Kelas: ' . $kelas->nama_kelas);
        $sheet->setCellValue('A3', 'Tanggal: ' . now()->format('d/m/Y'));
        $sheet->setCellValue('A4', '* Jangan mengubah kolom NIS. Isi data mulai baris ke-7.');

        // Style header info
        $sheet->mergeCells('A1:H1');
        $sheet->getStyle('A1')->applyFromArray([
            'font'      => ['bold' => true, 'size' => 14, 'color' => ['rgb' => 'FFFFFF']],
            'fill'      => ['fillType' => Fill::FILL_SOLID, 'color' => ['rgb' => '1976D2']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
        ]);

        $sheet->mergeCells('A2:H2');
        $sheet->getStyle('A2')->applyFromArray([
            'font'      => ['bold' => true, 'size' => 12, 'color' => ['rgb' => '1976D2']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_LEFT],
        ]);

        $sheet->mergeCells('A3:H3');
        $sheet->mergeCells('A4:H4');
        $sheet->getStyle('A4')->applyFromArray([
            'font' => ['italic' => true, 'color' => ['rgb' => 'FF0000']],
        ]);

        // ── Column Headers (Row 6) ───────────────────────────────────────
        $headers = [
            'A' => 'No',
            'B' => 'NIS',
            'C' => 'Nama Siswa',
            'D' => 'Tanggal Checkup (YYYY-MM-DD)',
            'E' => 'Tinggi Badan (cm)',
            'F' => 'Berat Badan (kg)',
            'G' => 'Tekanan Darah (Sistol/Diastol)',
            'H' => 'Merokok (Ya/Tidak)',
        ];

        foreach ($headers as $col => $label) {
            $sheet->setCellValue("{$col}6", $label);
        }

        $headerRange = 'A6:H6';
        $sheet->getStyle($headerRange)->applyFromArray([
            'font'      => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
            'fill'      => ['fillType' => Fill::FILL_SOLID, 'color' => ['rgb' => '2196F3']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER],
            'borders'   => ['allBorders' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['rgb' => 'BBDEFB']]],
        ]);

        // ── Fill Siswa Data ──────────────────────────────────────────────
        $today = now()->format('Y-m-d');
        $row = 7;
        $no = 1;
        foreach ($siswas as $siswa) {
            $sheet->setCellValue("A{$row}", $no++);
            $sheet->setCellValue("B{$row}", $siswa->nis);
            $sheet->setCellValue("C{$row}", $siswa->nama_siswa);
            $sheet->setCellValue("D{$row}", $today);

            // Lock NIS column (read-only hint via color)
            $sheet->getStyle("B{$row}")->applyFromArray([
                'fill' => ['fillType' => Fill::FILL_SOLID, 'color' => ['rgb' => 'E3F2FD']],
                'font' => ['bold' => true],
            ]);
            $sheet->getStyle("C{$row}")->applyFromArray([
                'fill' => ['fillType' => Fill::FILL_SOLID, 'color' => ['rgb' => 'E3F2FD']],
            ]);

            // Alternate row color
            if ($no % 2 === 0) {
                $sheet->getStyle("A{$row}:H{$row}")->applyFromArray([
                    'fill' => ['fillType' => Fill::FILL_SOLID, 'color' => ['rgb' => 'F5F5F5']],
                ]);
            }

            $sheet->getStyle("A{$row}:H{$row}")->applyFromArray([
                'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['rgb' => 'CCCCCC']]],
            ]);

            $row++;
        }

        // ── Column Widths ────────────────────────────────────────────────
        $colWidths = ['A' => 5, 'B' => 14, 'C' => 30, 'D' => 28, 'E' => 18, 'F' => 18, 'G' => 25, 'H' => 18];
        foreach ($colWidths as $col => $width) {
            $sheet->getColumnDimension($col)->setWidth($width);
        }

        // ── Output ───────────────────────────────────────────────────────
        $namaKelas = str_replace(' ', '_', $kelas->nama_kelas);
        $filename = "Template_Checkup_{$namaKelas}_" . now()->format('Ymd') . '.xlsx';

        $writer = new Xlsx($spreadsheet);

        return response()->streamDownload(function () use ($writer) {
            $writer->save('php://output');
        }, $filename, [
            'Content-Type'        => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
            'Cache-Control'       => 'max-age=0',
        ]);
    }

    // ─── Import Data Checkup dari Excel ─────────────────────────────────────

    public function importByKelas(Request $request)
    {
        $request->validate([
            'file'      => 'required|file|mimes:xlsx,xls',
            'id_kelas'  => 'required|integer|exists:kelas,id_kelas',
            'tanggal'   => 'nullable|date',
        ]);

        $file    = $request->file('file');
        $tanggal = $request->input('tanggal', now()->format('Y-m-d'));

        try {
            $spreadsheet = IOFactory::load($file->getRealPath());
            $sheet       = $spreadsheet->getActiveSheet();
            $rows        = $sheet->toArray(null, true, true, true);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal membaca file Excel: ' . $e->getMessage(),
            ], 422);
        }

        $results = [
            'imported' => 0,
            'skipped'  => 0,
            'errors'   => [],
        ];

        // Data starts from row 7
        foreach ($rows as $rowIndex => $row) {
            if ($rowIndex < 7) continue;

            $nis = trim((string) ($row['B'] ?? ''));
            if (empty($nis) || !is_numeric($nis)) continue;

            $nis = (int) $nis;

            // Validate NIS exists
            $siswa = UserSiswa::where('nis', $nis)->first();
            if (!$siswa) {
                $results['errors'][] = "Baris {$rowIndex}: NIS {$nis} tidak ditemukan.";
                $results['skipped']++;
                continue;
            }

            $tanggalRow   = trim((string) ($row['D'] ?? ''));
            $tinggiBadan  = is_numeric($row['E'] ?? '') ? (float) $row['E'] : null;
            $beratBadan   = is_numeric($row['F'] ?? '') ? (float) $row['F'] : null;
            $tekananDarah = trim((string) ($row['G'] ?? '')) ?: null;
            $merokokRaw   = trim((string) ($row['H'] ?? ''));

            $isMerokok = 'Tidak';
            if (!empty($merokokRaw)) {
                $mLower = strtolower($merokokRaw);
                if (in_array($mLower, ['ya', 'y', '1', 'true', 'merokok'])) {
                    $isMerokok = 'Ya';
                }
            }

            // Use row date if valid, else use request tanggal
            $tgl = null;
            if (!empty($tanggalRow)) {
                try {
                    $tgl = \Carbon\Carbon::parse($tanggalRow)->format('Y-m-d');
                } catch (\Exception $e) {
                    $tgl = $tanggal;
                }
            } else {
                $tgl = $tanggal;
            }

            // Calculate IMT
            $imt      = null;
            $kategori = null;
            if ($tinggiBadan && $beratBadan && $tinggiBadan > 0) {
                $tinggiM  = $tinggiBadan / 100;
                $imt      = round($beratBadan / ($tinggiM * $tinggiM), 2);
                $kategori = $this->kategoriImt($imt);
            }

            try {
                DataCheckup::create([
                    'nis'           => $nis,
                    'tanggal'       => $tgl,
                    'tinggi_badan'  => $tinggiBadan,
                    'berat_badan'   => $beratBadan,
                    'imt'           => $imt,
                    'kategori'      => $kategori,
                    'tekanan_darah' => $tekananDarah,
                    'is_merokok'    => $isMerokok,
                ]);
                $results['imported']++;
            } catch (\Exception $e) {
                $results['errors'][] = "Baris {$rowIndex} (NIS {$nis}): " . $e->getMessage();
                $results['skipped']++;
            }
        }

        return response()->json([
            'success' => true,
            'message' => "Import selesai. {$results['imported']} data berhasil diimport, {$results['skipped']} dilewati.",
            'data'    => $results,
        ]);
    }

    // ─── Helper ─────────────────────────────────────────────────────────────

    private function kategoriImt(float $imt): string
    {
        if ($imt < 17.0) return 'Kurus Berat';
        if ($imt < 18.5) return 'Kurus';
        if ($imt < 25.0) return 'Normal';
        if ($imt < 27.0) return 'Gemuk';
        return 'Obesitas';
    }
}
