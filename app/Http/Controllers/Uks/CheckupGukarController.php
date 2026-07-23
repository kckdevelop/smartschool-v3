<?php

namespace App\Http\Controllers\Uks;

use App\Http\Controllers\Controller;
use App\Models\DataCheckupGukar;
use App\Models\Guru;
use App\Models\Karyawan;
use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\IOFactory;

class CheckupGukarController extends Controller
{
    public function index(Request $request)
    {
        $query = DataCheckupGukar::with(['guru', 'karyawan'])
            ->orderByDesc('tanggal')
            ->orderByDesc('id_checkup');

        // Filter by Role (Guru / Karyawan)
        if ($request->filled('role')) {
            $role = $request->role;
            if ($role === 'guru') {
                $query->whereNotNull('id_guru');
            } elseif ($role === 'karyawan') {
                $query->whereNotNull('id_karyawan');
            }
        }

        // Search by Name or ID/NIP
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->whereHas('guru', function ($qg) use ($search) {
                    $qg->where('nama_guru', 'like', "%{$search}%")
                       ->orWhere('no_id', 'like', "%{$search}%");
                })->orWhereHas('karyawan', function ($qk) use ($search) {
                    $qk->where('nama_karyawan', 'like', "%{$search}%")
                       ->orWhere('no_id', 'like', "%{$search}%");
                });
            });
        }

        // Filter by Date Range
        if ($request->filled('tanggal_dari')) {
            $query->where('tanggal', '>=', $request->tanggal_dari);
        }
        if ($request->filled('tanggal_sampai')) {
            $query->where('tanggal', '<=', $request->tanggal_sampai);
        }

        $checkups     = $query->paginate(15)->withQueryString();
        $gurus        = Guru::where('status', 'aktif')->orderBy('nama_guru')->get();
        $karyawans    = Karyawan::where('status', 'aktif')->orderBy('nama_karyawan')->get();
        
        $totalCheckup = DataCheckupGukar::count();
        $hariIni      = DataCheckupGukar::whereDate('tanggal', today())->count();

        return view('uks.checkup-gukar.index', compact(
            'checkups', 'gurus', 'karyawans', 'totalCheckup', 'hariIni'
        ));
    }

    private function calculateImtAndKategori($tb, $bb)
    {
        if (!$tb || !$bb || $tb <= 0 || $bb <= 0) {
            return [null, null];
        }
        $tb_m = $tb / 100;
        $imt = round($bb / ($tb_m * $tb_m), 1);

        if ($imt < 18.5) {
            $kategori = 'Kurus';
        } elseif ($imt <= 25.0) {
            $kategori = 'Normal';
        } elseif ($imt <= 27.0) {
            $kategori = 'Gemuk';
        } else {
            $kategori = 'Obesitas';
        }

        return [$imt, $kategori];
    }

    private function parseGukarId($gukarId)
    {
        $id_guru = null;
        $id_karyawan = null;

        if (strpos($gukarId, 'guru_') === 0) {
            $id_guru = (int) str_replace('guru_', '', $gukarId);
        } elseif (strpos($gukarId, 'karyawan_') === 0) {
            $id_karyawan = (int) str_replace('karyawan_', '', $gukarId);
        }

        return [$id_guru, $id_karyawan];
    }

    public function store(Request $request)
    {
        $request->validate([
            'gukar_id'        => 'required|string',
            'tanggal'         => 'required|date',
            'tinggi_badan'    => 'nullable|numeric|min:1|max:300',
            'berat_badan'     => 'nullable|numeric|min:1|max:500',
            'tekanan_darah'   => 'nullable|string|max:50',
            'kolesterol'      => 'nullable|numeric|min:0|max:1000',
            'gula_darah'      => 'nullable|numeric|min:0|max:1000',
            'tipe_gula_darah' => 'nullable|in:sewaktu,puasa',
            'asam_urat'       => 'nullable|numeric|min:0|max:50',
        ]);

        [$id_guru, $id_karyawan] = $this->parseGukarId($request->gukar_id);

        if (!$id_guru && !$id_karyawan) {
            return redirect()->back()->withErrors(['gukar_id' => 'Guru atau Karyawan tidak valid.']);
        }

        [$imt, $kategori] = $this->calculateImtAndKategori($request->tinggi_badan, $request->berat_badan);

        DataCheckupGukar::create([
            'id_guru'         => $id_guru,
            'id_karyawan'     => $id_karyawan,
            'tanggal'         => $request->tanggal,
            'jam'             => now()->format('H:i:s'),
            'tinggi_badan'    => $request->tinggi_badan,
            'berat_badan'     => $request->berat_badan,
            'imt'             => $imt,
            'kategori'        => $kategori,
            'tekanan_darah'   => $request->tekanan_darah,
            'kolesterol'      => $request->kolesterol,
            'gula_darah'      => $request->gula_darah,
            'tipe_gula_darah' => $request->input('tipe_gula_darah', 'sewaktu'),
            'asam_urat'       => $request->asam_urat,
        ]);

        return redirect()->route('uks.checkup-gukar.index')
            ->with('success', 'Data check-up Gukar berhasil disimpan.');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'gukar_id'        => 'required|string',
            'tanggal'         => 'required|date',
            'tinggi_badan'    => 'nullable|numeric|min:1|max:300',
            'berat_badan'     => 'nullable|numeric|min:1|max:500',
            'tekanan_darah'   => 'nullable|string|max:50',
            'kolesterol'      => 'nullable|numeric|min:0|max:1000',
            'gula_darah'      => 'nullable|numeric|min:0|max:1000',
            'tipe_gula_darah' => 'nullable|in:sewaktu,puasa',
            'asam_urat'       => 'nullable|numeric|min:0|max:50',
        ]);

        $checkup = DataCheckupGukar::findOrFail($id);

        [$id_guru, $id_karyawan] = $this->parseGukarId($request->gukar_id);

        if (!$id_guru && !$id_karyawan) {
            return redirect()->back()->withErrors(['gukar_id' => 'Guru atau Karyawan tidak valid.']);
        }

        [$imt, $kategori] = $this->calculateImtAndKategori($request->tinggi_badan, $request->berat_badan);

        $checkup->update([
            'id_guru'         => $id_guru,
            'id_karyawan'     => $id_karyawan,
            'tanggal'         => $request->tanggal,
            'tinggi_badan'    => $request->tinggi_badan,
            'berat_badan'     => $request->berat_badan,
            'imt'             => $imt,
            'kategori'        => $kategori,
            'tekanan_darah'   => $request->tekanan_darah,
            'kolesterol'      => $request->kolesterol,
            'gula_darah'      => $request->gula_darah,
            'tipe_gula_darah' => $request->input('tipe_gula_darah', 'sewaktu'),
            'asam_urat'       => $request->asam_urat,
        ]);

        return redirect()->route('uks.checkup-gukar.index')
            ->with('success', 'Data check-up Gukar berhasil diperbarui.');
    }

    public function destroy($id)
    {
        DataCheckupGukar::findOrFail($id)->delete();

        return redirect()->route('uks.checkup-gukar.index')
            ->with('success', 'Data check-up Gukar berhasil dihapus.');
    }

    public function downloadTemplate()
    {
        $gurus = Guru::where('status', 'aktif')->orderBy('nama_guru')->get();
        $karyawans = Karyawan::where('status', 'aktif')->orderBy('nama_karyawan')->get();

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Checkup Gukar');

        // Header Info
        $sheet->setCellValue('A1', 'TEMPLATE CHECKUP KESEHATAN GURU & KARYAWAN');
        $sheet->setCellValue('A2', 'Tanggal: ' . now()->format('d/m/Y'));
        $sheet->setCellValue('A3', '* Jangan mengubah kolom NIP/ID, Nama, dan Peran. Isi data check-up mulai baris ke-6.');

        $sheet->mergeCells('A1:L1');
        $sheet->getStyle('A1')->applyFromArray([
            'font'      => ['bold' => true, 'size' => 14, 'color' => ['rgb' => 'FFFFFF']],
            'fill'      => ['fillType' => Fill::FILL_SOLID, 'color' => ['rgb' => '1976D2']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
        ]);

        $sheet->mergeCells('A2:L2');
        $sheet->getStyle('A2')->applyFromArray([
            'font'      => ['bold' => true, 'size' => 12, 'color' => ['rgb' => '1976D2']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_LEFT],
        ]);

        $sheet->mergeCells('A3:L3');
        $sheet->getStyle('A3')->applyFromArray([
            'font' => ['italic' => true, 'color' => ['rgb' => 'FF0000']],
        ]);

        // Column Headers (Row 5)
        $headers = [
            'A' => 'No',
            'B' => 'NIP/ID',
            'C' => 'Nama',
            'D' => 'Peran (Guru/Karyawan)',
            'E' => 'Tanggal Checkup (YYYY-MM-DD)',
            'F' => 'Tinggi Badan (cm)',
            'G' => 'Berat Badan (kg)',
            'H' => 'Tekanan Darah (mmHg)',
            'I' => 'Kolesterol (mg/dL)',
            'J' => 'Gula Darah (mg/dL)',
            'K' => 'Tipe Gula Darah (Sewaktu/Puasa)',
            'L' => 'Asam Urat (mg/dL)',
        ];

        foreach ($headers as $col => $label) {
            $sheet->setCellValue("{$col}5", $label);
        }

        $headerRange = 'A5:L5';
        $sheet->getStyle($headerRange)->applyFromArray([
            'font'      => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
            'fill'      => ['fillType' => Fill::FILL_SOLID, 'color' => ['rgb' => '2196F3']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER],
            'borders'   => ['allBorders' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['rgb' => 'BBDEFB']]],
        ]);

        $today = now()->format('Y-m-d');
        $row = 6;
        $no = 1;

        // Fill Gurus
        foreach ($gurus as $guru) {
            $sheet->setCellValue("A{$row}", $no++);
            $sheet->setCellValue("B{$row}", $guru->no_id);
            $sheet->setCellValue("C{$row}", $guru->nama_guru);
            $sheet->setCellValue("D{$row}", 'Guru');
            $sheet->setCellValue("E{$row}", $today);
            $sheet->setCellValue("K{$row}", 'Sewaktu'); // Default value in template

            // Styling read-only hints
            $sheet->getStyle("B{$row}:D{$row}")->applyFromArray([
                'fill' => ['fillType' => Fill::FILL_SOLID, 'color' => ['rgb' => 'E3F2FD']],
            ]);

            $sheet->getStyle("A{$row}:L{$row}")->applyFromArray([
                'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['rgb' => 'CCCCCC']]],
            ]);
            $row++;
        }

        // Fill Karyawans
        foreach ($karyawans as $karyawan) {
            $sheet->setCellValue("A{$row}", $no++);
            $sheet->setCellValue("B{$row}", $karyawan->no_id);
            $sheet->setCellValue("C{$row}", $karyawan->nama_karyawan);
            $sheet->setCellValue("D{$row}", 'Karyawan');
            $sheet->setCellValue("E{$row}", $today);
            $sheet->setCellValue("K{$row}", 'Sewaktu'); // Default value in template

            // Styling read-only hints
            $sheet->getStyle("B{$row}:D{$row}")->applyFromArray([
                'fill' => ['fillType' => Fill::FILL_SOLID, 'color' => ['rgb' => 'E8F5E9']],
            ]);

            $sheet->getStyle("A{$row}:L{$row}")->applyFromArray([
                'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['rgb' => 'CCCCCC']]],
            ]);
            $row++;
        }

        // Column Widths
        $colWidths = [
            'A' => 5, 'B' => 15, 'C' => 30, 'D' => 25, 'E' => 28, 'F' => 18, 
            'G' => 18, 'H' => 22, 'I' => 18, 'J' => 18, 'K' => 30, 'L' => 18
        ];
        foreach ($colWidths as $col => $width) {
            $sheet->getColumnDimension($col)->setWidth($width);
        }

        $filename = "Template_Checkup_Gukar_" . now()->format('Ymd') . '.xlsx';
        $writer = new Xlsx($spreadsheet);

        return response()->streamDownload(function () use ($writer) {
            $writer->save('php://output');
        }, $filename, [
            'Content-Type'        => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
            'Cache-Control'       => 'max-age=0',
        ]);
    }

    public function importExcel(Request $request)
    {
        $request->validate([
            'file'    => 'required|file|mimes:xlsx,xls|max:5120',
            'tanggal' => 'nullable|date',
        ]);

        $file = $request->file('file');
        $tanggalInput = $request->input('tanggal', now()->format('Y-m-d'));

        try {
            $spreadsheet = IOFactory::load($file->getRealPath());
            $sheet       = $spreadsheet->getActiveSheet();
            $rows        = $sheet->toArray(null, true, true, true);
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal membaca file Excel: ' . $e->getMessage());
        }

        $imported = 0;
        $skipped  = 0;
        $errors   = [];

        // Data starts from row 6
        foreach ($rows as $rowIndex => $row) {
            if ($rowIndex < 6) continue;

            $noId = trim((string) ($row['B'] ?? ''));
            if (empty($noId) || !is_numeric($noId)) continue;

            $noId = (int) $noId;
            $peranInput = strtolower(trim((string) ($row['D'] ?? '')));

            $id_guru = null;
            $id_karyawan = null;

            // Resolve target Guru or Karyawan
            if ($peranInput === 'guru') {
                $guru = Guru::where('no_id', $noId)->first();
                if ($guru) {
                    $id_guru = $guru->id_guru;
                } else {
                    $errors[] = "Baris {$rowIndex}: Guru dengan NIP {$noId} tidak ditemukan.";
                    $skipped++;
                    continue;
                }
            } elseif ($peranInput === 'karyawan') {
                $karyawan = Karyawan::where('no_id', $noId)->first();
                if ($karyawan) {
                    $id_karyawan = $karyawan->id_karyawan;
                } else {
                    $errors[] = "Baris {$rowIndex}: Karyawan dengan ID {$noId} tidak ditemukan.";
                    $skipped++;
                    continue;
                }
            } else {
                // Fallback search in both tables
                $guru = Guru::where('no_id', $noId)->first();
                if ($guru) {
                    $id_guru = $guru->id_guru;
                } else {
                    $karyawan = Karyawan::where('no_id', $noId)->first();
                    if ($karyawan) {
                        $id_karyawan = $karyawan->id_karyawan;
                    } else {
                        $errors[] = "Baris {$rowIndex}: NIP/ID {$noId} tidak ditemukan di tabel Guru maupun Karyawan.";
                        $skipped++;
                        continue;
                    }
                }
            }

            $tanggalRow  = trim((string) ($row['E'] ?? ''));
            $tinggiBadan = is_numeric($row['F'] ?? '') ? (float) $row['F'] : null;
            $beratBadan  = is_numeric($row['G'] ?? '') ? (float) $row['G'] : null;
            $tekananDarah = trim((string) ($row['H'] ?? ''));
            $kolesterol  = is_numeric($row['I'] ?? '') ? (float) $row['I'] : null;
            $gulaDarah   = is_numeric($row['J'] ?? '') ? (float) $row['J'] : null;
            $tipeInput   = strtolower(trim((string) ($row['K'] ?? '')));
            $tipeGulaDarah = str_contains($tipeInput, 'puasa') ? 'puasa' : 'sewaktu';
            $asamUrat    = is_numeric($row['L'] ?? '') ? (float) $row['L'] : null;

            // Use row date if valid, else use request tanggal
            $tgl = null;
            if (!empty($tanggalRow)) {
                try {
                    $tgl = \Carbon\Carbon::parse($tanggalRow)->format('Y-m-d');
                } catch (\Exception $e) {
                    $tgl = $tanggalInput;
                }
            } else {
                $tgl = $tanggalInput;
            }

            // Calculate IMT
            [$imt, $kategori] = $this->calculateImtAndKategori($tinggiBadan, $beratBadan);

            try {
                DataCheckupGukar::create([
                    'id_guru'       => $id_guru,
                    'id_karyawan'   => $id_karyawan,
                    'tanggal'       => $tgl,
                    'jam'           => now()->format('H:i:s'),
                    'tinggi_badan'  => $tinggiBadan,
                    'berat_badan'   => $beratBadan,
                    'imt'           => $imt,
                    'kategori'      => $kategori,
                    'tekanan_darah' => $tekananDarah ?: null,
                    'kolesterol'    => $kolesterol,
                    'gula_darah'    => $gulaDarah,
                    'tipe_gula_darah' => $tipeGulaDarah,
                    'asam_urat'     => $asamUrat,
                ]);
                $imported++;
            } catch (\Exception $e) {
                $errors[] = "Baris {$rowIndex} (NIP/ID {$noId}): " . $e->getMessage();
                $skipped++;
            }
        }

        $msg = "Import selesai. {$imported} data check-up Gukar berhasil diimport, {$skipped} dilewati.";
        if (count($errors) > 0) {
            $msg .= " Detail error: " . implode(', ', array_slice($errors, 0, 5));
            if (count($errors) > 5) {
                $msg .= " (+ " . (count($errors) - 5) . " error lainnya)";
            }
            return redirect()->route('uks.checkup-gukar.index')->with('error', $msg);
        }

        return redirect()->route('uks.checkup-gukar.index')->with('success', $msg);
    }
}
