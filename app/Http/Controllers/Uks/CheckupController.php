<?php

namespace App\Http\Controllers\Uks;

use App\Http\Controllers\Controller;
use App\Models\DataCheckup;
use App\Models\UserSiswa;
use App\Models\Kelas;
use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\IOFactory;

class CheckupController extends Controller
{
    public function index(Request $request)
    {
        $query = DataCheckup::with(['siswa.kelas.jurusan'])
            ->orderByDesc('tanggal')
            ->orderByDesc('id_checkup');

        if ($request->filled('kelas_id')) {
            $query->whereHas('siswa', function ($q) use ($request) {
                $q->where('id_kelas', $request->kelas_id);
            });
        }

        if ($request->filled('tanggal_dari')) {
            $query->where('tanggal', '>=', $request->tanggal_dari);
        }
        if ($request->filled('tanggal_sampai')) {
            $query->where('tanggal', '<=', $request->tanggal_sampai);
        }
        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('siswa', function ($q) use ($search) {
                $q->where('nama_siswa', 'like', "%{$search}%")
                  ->orWhere('nis', 'like', "%{$search}%");
            });
        }

        $checkups     = $query->paginate(15)->withQueryString();
        $kelases      = Kelas::where('status', 'aktif')->orderBy('tingkat')->orderBy('rombel')->get();
        $siswaDaftar  = UserSiswa::with('kelas')->orderBy('nama_siswa')->get(['nis','nama_siswa','id_kelas']);
        $totalCheckup = DataCheckup::count();
        $hariIni      = DataCheckup::whereDate('tanggal', today())->count();

        return view('uks.checkup.index', compact(
            'checkups', 'kelases', 'siswaDaftar', 'totalCheckup', 'hariIni'
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

    public function store(Request $request)
    {
        $request->validate([
            'nis'          => 'required|integer|exists:user_siswa,nis',
            'tanggal'      => 'required|date',
            'tinggi_badan' => 'nullable|numeric|min:1|max:300',
            'berat_badan'  => 'nullable|numeric|min:1|max:500',
            'tekanan_darah' => 'nullable|string|max:50',
            'is_merokok'    => 'nullable|string|in:Ya,Tidak,Merokok,Tidak Merokok',
        ]);

        [$imt, $kategori] = $this->calculateImtAndKategori($request->tinggi_badan, $request->berat_badan);

        $isMerokok = $request->is_merokok;
        if (in_array($isMerokok, ['Ya', 'Merokok'])) {
            $isMerokok = 'Ya';
        } else {
            $isMerokok = 'Tidak';
        }

        DataCheckup::create([
            'nis'           => $request->nis,
            'tanggal'       => $request->tanggal,
            'jam'           => now()->format('H:i:s'),
            'jenis_checkup' => 'Check-Up Fisik',
            'nilai'         => 0,
            'satuan'        => '-',
            'tinggi_badan'  => $request->tinggi_badan,
            'berat_badan'   => $request->berat_badan,
            'imt'           => $imt,
            'kategori'      => $kategori,
            'tekanan_darah' => $request->tekanan_darah,
            'is_merokok'    => $isMerokok,
        ]);

        return redirect()->route('uks.checkup.index')
            ->with('success', 'Data check-up siswa berhasil disimpan.');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nis'          => 'required|integer|exists:user_siswa,nis',
            'tanggal'      => 'required|date',
            'tinggi_badan' => 'nullable|numeric|min:1|max:300',
            'berat_badan'  => 'nullable|numeric|min:1|max:500',
            'tekanan_darah' => 'nullable|string|max:50',
            'is_merokok'    => 'nullable|string|in:Ya,Tidak,Merokok,Tidak Merokok',
        ]);

        $checkup = DataCheckup::findOrFail($id);

        [$imt, $kategori] = $this->calculateImtAndKategori($request->tinggi_badan, $request->berat_badan);

        $isMerokok = $request->is_merokok;
        if (in_array($isMerokok, ['Ya', 'Merokok'])) {
            $isMerokok = 'Ya';
        } else {
            $isMerokok = 'Tidak';
        }

        $checkup->update([
            'nis'          => $request->nis,
            'tanggal'      => $request->tanggal,
            'tinggi_badan' => $request->tinggi_badan,
            'berat_badan'  => $request->berat_badan,
            'imt'          => $imt,
            'kategori'     => $kategori,
            'tekanan_darah' => $request->tekanan_darah,
            'is_merokok'    => $isMerokok,
        ]);

        return redirect()->route('uks.checkup.index')
            ->with('success', 'Data check-up siswa berhasil diperbarui.');
    }

    public function destroy($id)
    {
        DataCheckup::findOrFail($id)->delete();

        return redirect()->route('uks.checkup.index')
            ->with('success', 'Data check-up siswa berhasil dihapus.');
    }

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

    public function importExcel(Request $request)
    {
        $request->validate([
            'file'      => 'required|file|mimes:xlsx,xls|max:5120',
            'id_kelas'  => 'required|integer|exists:kelas,id_kelas',
            'tanggal'   => 'nullable|date',
        ]);

        $file    = $request->file('file');
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

        // Data starts from row 7
        foreach ($rows as $rowIndex => $row) {
            if ($rowIndex < 7) continue;

            $nis = trim((string) ($row['B'] ?? ''));
            if (empty($nis) || !is_numeric($nis)) continue;

            $nis = (int) $nis;

            // Validate NIS exists
            $siswa = UserSiswa::where('nis', $nis)->first();
            if (!$siswa) {
                $errors[] = "Baris {$rowIndex}: NIS {$nis} tidak ditemukan.";
                $skipped++;
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
                    $tgl = $tanggalInput;
                }
            } else {
                $tgl = $tanggalInput;
            }

            // Calculate IMT
            [$imt, $kategori] = $this->calculateImtAndKategori($tinggiBadan, $beratBadan);

            try {
                DataCheckup::create([
                    'nis'           => $nis,
                    'tanggal'       => $tgl,
                    'jam'           => now()->format('H:i:s'),
                    'jenis_checkup' => 'Check-Up Fisik',
                    'nilai'         => 0,
                    'satuan'        => '-',
                    'tinggi_badan'  => $tinggiBadan,
                    'berat_badan'   => $beratBadan,
                    'imt'           => $imt,
                    'kategori'      => $kategori,
                    'tekanan_darah' => $tekananDarah,
                    'is_merokok'    => $isMerokok,
                ]);
                $imported++;
            } catch (\Exception $e) {
                $errors[] = "Baris {$rowIndex} (NIS {$nis}): " . $e->getMessage();
                $skipped++;
            }
        }

        $msg = "Import selesai. {$imported} data berhasil diimport, {$skipped} dilewati.";
        if (count($errors) > 0) {
            $msg .= " Detail error: " . implode(', ', array_slice($errors, 0, 5));
            if (count($errors) > 5) {
                $msg .= " (+ " . (count($errors) - 5) . " error lainnya)";
            }
            return redirect()->route('uks.checkup.index')->with('error', $msg);
        }

        return redirect()->route('uks.checkup.index')->with('success', $msg);
    }
}
