<?php

namespace App\Http\Controllers\Pkl;

use App\Http\Controllers\Controller;
use App\Models\PklDudi;
use App\Models\Jurusan;
use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\IOFactory;

class DudiController extends Controller
{
    public function index(Request $request)
    {
        $query = PklDudi::with('jurusan');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nama_dudi', 'like', '%' . $search . '%')
                  ->orWhere('kota', 'like', '%' . $search . '%')
                  ->orWhere('kabupaten', 'like', '%' . $search . '%')
                  ->orWhere('kecamatan', 'like', '%' . $search . '%')
                  ->orWhere('bidang_usaha', 'like', '%' . $search . '%');
            });
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('id_jurusan')) {
            $query->where('id_jurusan', $request->id_jurusan);
        }

        $data = $query->orderByDesc('id_dudi')->paginate(20)->withQueryString();
        $jurusanList = Jurusan::orderBy('nama_jurusan')->get();

        return view('pkl.dudi.index', compact('data', 'jurusanList'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_dudi'    => 'required|string|max:200',
            'id_jurusan'   => 'nullable|integer|exists:jurusan,id_jurusan',
            'bidang_usaha' => 'nullable|string|max:100',
            'alamat'       => 'required|string',
            'kota'         => 'nullable|string|max:100',
            'kecamatan'    => 'nullable|string|max:100',
            'kabupaten'    => 'nullable|string|max:100',
            'no_telepon'   => 'nullable|string|max:20',
            'email'        => 'nullable|email|max:100',
            'nama_pic'     => 'nullable|string|max:100',
            'jabatan_pic'  => 'nullable|string|max:100',
            'no_hp_pic'    => 'nullable|string|max:20',
            'kuota_siswa'  => 'required|integer|min:1|max:100',
            'status'       => 'required|in:aktif,nonaktif',
        ]);

        PklDudi::create($request->all());

        return redirect()->route('pkl.dudi.index')
            ->with('success', 'Data DUDI berhasil ditambahkan.');
    }

    public function update(Request $request, int $id)
    {
        $dudi = PklDudi::findOrFail($id);

        $request->validate([
            'nama_dudi'    => 'required|string|max:200',
            'id_jurusan'   => 'nullable|integer|exists:jurusan,id_jurusan',
            'bidang_usaha' => 'nullable|string|max:100',
            'alamat'       => 'required|string',
            'kota'         => 'nullable|string|max:100',
            'kecamatan'    => 'nullable|string|max:100',
            'kabupaten'    => 'nullable|string|max:100',
            'no_telepon'   => 'nullable|string|max:20',
            'email'        => 'nullable|email|max:100',
            'nama_pic'     => 'nullable|string|max:100',
            'jabatan_pic'  => 'nullable|string|max:100',
            'no_hp_pic'    => 'nullable|string|max:20',
            'kuota_siswa'  => 'required|integer|min:1|max:100',
            'status'       => 'required|in:aktif,nonaktif',
        ]);

        $dudi->update($request->all());

        return redirect()->route('pkl.dudi.index')
            ->with('success', 'Data DUDI berhasil diperbarui.');
    }

    public function destroy(int $id)
    {
        $dudi = PklDudi::findOrFail($id);
        if ($dudi->penempatan()->count() > 0) {
            return back()->with('error', 'DUDI tidak dapat dihapus karena sudah memiliki siswa yang ditempatkan.');
        }
        $dudi->delete();

        return redirect()->route('pkl.dudi.index')
            ->with('success', 'Data DUDI berhasil dihapus.');
    }

    // Download Template Excel untuk Import DUDI
    public function downloadTemplate()
    {
        $spreadsheet = new Spreadsheet();

        // Sheet 1: Data DUDI
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Data DUDI');

        $headers = [
            'A1' => 'No',
            'B1' => 'Nama DUDI *',
            'C1' => 'Jurusan (Kode / Nama)',
            'D1' => 'Bidang Usaha',
            'E1' => 'Alamat Lengkap *',
            'F1' => 'Kecamatan',
            'G1' => 'Kabupaten / Kota',
            'H1' => 'No. Telepon',
            'I1' => 'Email',
            'J1' => 'Nama PIC',
            'K1' => 'Jabatan PIC',
            'L1' => 'No. HP PIC',
            'M1' => 'Kuota Siswa',
            'N1' => 'Status (aktif/nonaktif)',
        ];

        foreach ($headers as $cell => $value) {
            $sheet->setCellValue($cell, $value);
        }

        // Header Styling
        $headerStyle = [
            'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF'], 'size' => 11],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => '0D9488'], // Teal color matching app UI
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical'   => Alignment::VERTICAL_CENTER,
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['rgb' => 'CCCCCC'],
                ],
            ],
        ];

        $sheet->getStyle('A1:N1')->applyFromArray($headerStyle);
        $sheet->getRowDimension(1)->setRowHeight(28);

        // Contoh Data Row 2
        $jurusans = Jurusan::all();
        $sampleJurusan = $jurusans->first() ? $jurusans->first()->kode_jurusan : 'RPL';

        $sampleRow = [
            'A2' => 1,
            'B2' => 'PT Teknologi Nusantara',
            'C2' => $sampleJurusan,
            'D2' => 'Software & Web Development',
            'E2' => 'Jl. Malioboro No. 12',
            'F2' => 'Danurejan',
            'G2' => 'Yogyakarta',
            'H2' => '0274-123456',
            'I2' => 'info@teknus.com',
            'J2' => 'Budi Santoso',
            'K2' => 'Manager HRD',
            'L2' => '081234567890',
            'M2' => 5,
            'N2' => 'aktif',
        ];

        foreach ($sampleRow as $cell => $value) {
            $sheet->setCellValue($cell, $value);
        }

        $rowStyle = [
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['rgb' => 'DDDDDD'],
                ],
            ],
            'alignment' => [
                'vertical' => Alignment::VERTICAL_CENTER,
            ],
        ];
        $sheet->getStyle('A2:N2')->applyFromArray($rowStyle);

        // Sheet 2: Referensi Jurusan
        $refSheet = $spreadsheet->createSheet();
        $refSheet->setTitle('Referensi Jurusan');

        $refSheet->setCellValue('A1', 'ID Jurusan');
        $refSheet->setCellValue('B1', 'Kode Jurusan');
        $refSheet->setCellValue('C1', 'Nama Jurusan');

        $refSheet->getStyle('A1:C1')->applyFromArray([
            'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => '475569'],
            ],
        ]);

        $rIndex = 2;
        foreach ($jurusans as $j) {
            $refSheet->setCellValue('A' . $rIndex, $j->id_jurusan);
            $refSheet->setCellValue('B' . $rIndex, $j->kode_jurusan);
            $refSheet->setCellValue('C' . $rIndex, $j->nama_jurusan);
            $rIndex++;
        }

        foreach (range('A', 'C') as $col) {
            $refSheet->getColumnDimension($col)->setAutoSize(true);
        }

        // Set autosize for sheet 1
        foreach (range('A', 'N') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        // Switch back to sheet 1 as active sheet
        $spreadsheet->setActiveSheetIndex(0);

        $filename = 'Template_Import_DUDI_' . date('Ymd_His') . '.xlsx';

        return response()->streamDownload(function () use ($spreadsheet) {
            $writer = new Xlsx($spreadsheet);
            $writer->save('php://output');
        }, $filename, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'Cache-Control' => 'max-age=0',
        ]);
    }

    // Import Data DUDI dari File Excel
    public function importExcel(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:xlsx,xls,csv|max:5120',
        ]);

        $file = $request->file('file');

        try {
            $spreadsheet = IOFactory::load($file->getRealPath());
            $sheet = $spreadsheet->getActiveSheet();
            $rows = $sheet->toArray(null, true, true, true);
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal membaca file Excel: ' . $e->getMessage());
        }

        // Load all jurusans to match against
        $jurusans = Jurusan::all();
        $jurusanMap = [];
        foreach ($jurusans as $j) {
            $jurusanMap[(string) $j->id_jurusan] = $j->id_jurusan;
            $jurusanMap[strtolower(trim($j->kode_jurusan))] = $j->id_jurusan;
            $jurusanMap[strtolower(trim($j->nama_jurusan))] = $j->id_jurusan;
        }

        $imported = 0;
        $skipped = 0;

        foreach ($rows as $rowIndex => $row) {
            // Header is at row 1
            if ($rowIndex <= 1) continue;

            $namaDudi = trim((string) ($row['B'] ?? ''));
            if (empty($namaDudi)) {
                continue;
            }

            $jurusanInput = strtolower(trim((string) ($row['C'] ?? '')));
            $idJurusan = null;
            if (!empty($jurusanInput) && isset($jurusanMap[$jurusanInput])) {
                $idJurusan = $jurusanMap[$jurusanInput];
            }

            $bidangUsaha = trim((string) ($row['D'] ?? '')) ?: null;
            $alamat      = trim((string) ($row['E'] ?? '')) ?: '-';
            $kecamatan   = trim((string) ($row['F'] ?? '')) ?: null;
            $kota        = trim((string) ($row['G'] ?? '')) ?: null;
            $noTelepon   = trim((string) ($row['H'] ?? '')) ?: null;
            $email       = trim((string) ($row['I'] ?? '')) ?: null;
            $namaPic     = trim((string) ($row['J'] ?? '')) ?: null;
            $jabatanPic  = trim((string) ($row['K'] ?? '')) ?: null;
            $noHpPic     = trim((string) ($row['L'] ?? '')) ?: null;
            
            $kuotaInput  = trim((string) ($row['M'] ?? ''));
            $kuotaSiswa  = (is_numeric($kuotaInput) && (int)$kuotaInput > 0) ? (int)$kuotaInput : 5;

            $statusInput = strtolower(trim((string) ($row['N'] ?? '')));
            $status      = in_array($statusInput, ['aktif', 'nonaktif']) ? $statusInput : 'aktif';

            PklDudi::create([
                'id_jurusan'   => $idJurusan,
                'nama_dudi'    => $namaDudi,
                'bidang_usaha' => $bidangUsaha,
                'alamat'       => $alamat,
                'kecamatan'    => $kecamatan,
                'kota'         => $kota,
                'kabupaten'    => $kota,
                'no_telepon'   => $noTelepon,
                'email'        => $email,
                'nama_pic'     => $namaPic,
                'jabatan_pic'  => $jabatanPic,
                'no_hp_pic'    => $noHpPic,
                'kuota_siswa'  => $kuotaSiswa,
                'status'       => $status,
            ]);

            $imported++;
        }

        if ($imported === 0) {
            return redirect()->back()->with('error', 'Tidak ada data DUDI valid yang berhasil diimpor.');
        }

        return redirect()->route('pkl.dudi.index')
            ->with('success', "Berhasil mengimpor {$imported} data DUDI dari file Excel.");
    }

    // API: Ambil daftar DUDI aktif dengan sisa kuota
    public function getByGelombang(Request $request)
    {
        $idGelombang = $request->id_gelombang;
        $dudis = PklDudi::where('status', 'aktif')->get()->map(function ($d) use ($idGelombang) {
            $terpakai = $idGelombang
                ? $d->penempatan()->where('id_gelombang', $idGelombang)->whereIn('status', ['aktif', 'selesai'])->count()
                : 0;
            return [
                'id_dudi'       => $d->id_dudi,
                'nama_dudi'     => $d->nama_dudi,
                'kuota_siswa'   => $d->kuota_siswa,
                'terpakai'      => $terpakai,
                'sisa_kuota'    => max(0, $d->kuota_siswa - $terpakai),
            ];
        });

        return response()->json($dudis);
    }
}
