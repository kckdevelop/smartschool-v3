<?php

namespace App\Http\Controllers\Pkl;

use App\Http\Controllers\Controller;
use App\Models\PklPenempatan;
use App\Models\PklGelombang;
use App\Models\PklDudi;
use App\Models\PklPembimbing;
use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class DownloadDataPklController extends Controller
{
    /**
     * Definisi dataset yang tersedia untuk diunduh.
     */
    private function getDatasets(): array
    {
        return [
            'penempatan' => [
                'label'   => 'Data Penempatan Siswa PKL',
                'icon'    => 'fa-users',
                'color'   => '#4f46e5',
                'columns' => [
                    'no'               => 'No',
                    'nis'              => 'NIS',
                    'nama_siswa'       => 'Nama Siswa',
                    'kelas'            => 'Kelas',
                    'jurusan'          => 'Jurusan',
                    'nama_dudi'        => 'Nama DUDI',
                    'alamat_dudi'      => 'Alamat DUDI',
                    'kota_dudi'        => 'Kota',
                    'no_telp_dudi'     => 'No. Telepon DUDI',
                    'nama_pic'         => 'Nama PIC',
                    'jabatan_pic'      => 'Jabatan PIC',
                    'no_hp_pic'        => 'No. HP PIC',
                    'nama_pembimbing'  => 'Nama Pembimbing',
                    'tanggal_masuk'    => 'Tanggal Masuk',
                    'tanggal_keluar'   => 'Tanggal Keluar',
                    'status'           => 'Status',
                    'keterangan'       => 'Keterangan',
                ],
            ],
            'dudi' => [
                'label'   => 'Data DUDI / Perusahaan',
                'icon'    => 'fa-building',
                'color'   => '#0369a1',
                'columns' => [
                    'no'           => 'No',
                    'nama_dudi'    => 'Nama DUDI',
                    'bidang_usaha' => 'Bidang Usaha',
                    'alamat'       => 'Alamat',
                    'kota'         => 'Kota',
                    'kecamatan'    => 'Kecamatan',
                    'kabupaten'    => 'Kabupaten',
                    'no_telepon'   => 'No. Telepon',
                    'email'        => 'Email',
                    'nama_pic'     => 'Nama PIC',
                    'jabatan_pic'  => 'Jabatan PIC',
                    'no_hp_pic'    => 'No. HP PIC',
                    'kuota_siswa'  => 'Kuota Siswa',
                    'status'       => 'Status',
                ],
            ],
            'pembimbing' => [
                'label'   => 'Data Pembimbing PKL',
                'icon'    => 'fa-person-chalkboard',
                'color'   => '#065f46',
                'columns' => [
                    'no'        => 'No',
                    'nama_guru' => 'Nama Pembimbing',
                    'nip'       => 'NIP',
                    'nama_dudi' => 'DUDI / Perusahaan',
                    'gelombang' => 'Gelombang',
                ],
            ],
        ];
    }

    public function index(Request $request)
    {
        $datasets       = $this->getDatasets();
        $gelombangList  = PklGelombang::orderByDesc('id_gelombang')->get();
        $gelombangAktif = PklGelombang::where('status', 'aktif')->first();
        $dudis          = PklDudi::where('status', 'aktif')->orderBy('nama_dudi')->get();

        return view('pkl.download-data.index', compact(
            'datasets', 'gelombangList', 'gelombangAktif', 'dudis'
        ));
    }

    public function download(Request $request)
    {
        $datasets = $this->getDatasets();

        $request->validate([
            'dataset'      => 'required|in:' . implode(',', array_keys($datasets)),
            'columns'      => 'required|array|min:1',
            'id_gelombang' => 'nullable|exists:pkl_gelombang,id_gelombang',
        ]);

        $dataset    = $request->input('dataset');
        $cols       = $request->input('columns', []);
        $allColumns = $datasets[$dataset]['columns'];

        // Hanya kolom yang valid & dipilih, pertahankan urutan definisi
        $selectedCols = array_filter($allColumns, fn($k) => in_array($k, $cols), ARRAY_FILTER_USE_KEY);

        // Kolom 'no' selalu ada di depan jika ada di definisi
        if (isset($allColumns['no']) && !array_key_exists('no', $selectedCols)) {
            $selectedCols = array_merge(['no' => 'No'], $selectedCols);
        }

        // Ambil data sesuai dataset
        $rows = match ($dataset) {
            'penempatan' => $this->getPenempatanData($request),
            'dudi'       => $this->getDudiData($request),
            'pembimbing' => $this->getPembimbingData($request),
            default      => collect(),
        };

        return $this->buildExcel($datasets[$dataset]['label'], $selectedCols, $rows, $request);
    }

    // ─────────────────────────────────────────────
    // Data fetchers
    // ─────────────────────────────────────────────

    private function getPenempatanData(Request $request)
    {
        $query = PklPenempatan::with(['siswa.kelas.jurusan', 'dudi', 'pembimbing.guru'])
            ->orderBy('id_gelombang');

        if ($request->filled('id_gelombang')) {
            $query->where('id_gelombang', $request->id_gelombang);
        }
        if ($request->filled('filter_dudi')) {
            $query->where('id_dudi', $request->filter_dudi);
        }
        if ($request->filled('filter_status')) {
            $query->where('status', $request->filter_status);
        }

        return $query->get()->map(function ($p, $i) {
            return [
                'no'              => $i + 1,
                'nis'             => $p->nis,
                'nama_siswa'      => optional($p->siswa)->nama_siswa ?? '-',
                'kelas'           => optional(optional($p->siswa)->kelas)->nama_kelas ?? '-',
                'jurusan'         => optional(optional(optional($p->siswa)->kelas)->jurusan)->nama_jurusan ?? '-',
                'nama_dudi'       => optional($p->dudi)->nama_dudi ?? '-',
                'alamat_dudi'     => optional($p->dudi)->alamat ?? '-',
                'kota_dudi'       => optional($p->dudi)->kota ?? '-',
                'no_telp_dudi'    => optional($p->dudi)->no_telepon ?? '-',
                'nama_pic'        => optional($p->dudi)->nama_pic ?? '-',
                'jabatan_pic'     => optional($p->dudi)->jabatan_pic ?? '-',
                'no_hp_pic'       => optional($p->dudi)->no_hp_pic ?? '-',
                'nama_pembimbing' => optional(optional($p->pembimbing)->guru)->nama_guru ?? '-',
                'tanggal_masuk'   => $p->tanggal_masuk?->format('d/m/Y') ?? '-',
                'tanggal_keluar'  => $p->tanggal_keluar?->format('d/m/Y') ?? '-',
                'status'          => ucfirst($p->status),
                'keterangan'      => $p->keterangan ?? '-',
            ];
        });
    }

    private function getDudiData(Request $request)
    {
        $query = PklDudi::query()->orderBy('nama_dudi');

        if ($request->filled('filter_status_dudi')) {
            $query->where('status', $request->filter_status_dudi);
        }

        return $query->get()->map(function ($d, $i) {
            return [
                'no'           => $i + 1,
                'nama_dudi'    => $d->nama_dudi,
                'bidang_usaha' => $d->bidang_usaha ?? '-',
                'alamat'       => $d->alamat ?? '-',
                'kota'         => $d->kota ?? '-',
                'kecamatan'    => $d->kecamatan ?? '-',
                'kabupaten'    => $d->kabupaten ?? '-',
                'no_telepon'   => $d->no_telepon ?? '-',
                'email'        => $d->email ?? '-',
                'nama_pic'     => $d->nama_pic ?? '-',
                'jabatan_pic'  => $d->jabatan_pic ?? '-',
                'no_hp_pic'    => $d->no_hp_pic ?? '-',
                'kuota_siswa'  => $d->kuota_siswa,
                'status'       => ucfirst($d->status),
            ];
        });
    }

    private function getPembimbingData(Request $request)
    {
        $query = PklPembimbing::with(['guru', 'dudi', 'gelombang']);

        if ($request->filled('id_gelombang')) {
            $query->where('id_gelombang', $request->id_gelombang);
        }

        return $query->get()->map(function ($p, $i) {
            return [
                'no'        => $i + 1,
                'nama_guru' => optional($p->guru)->nama_guru ?? '-',
                'nip'       => optional($p->guru)->nip ?? '-',
                'nama_dudi' => optional($p->dudi)->nama_dudi ?? '-',
                'gelombang' => optional($p->gelombang)->nama_gelombang ?? '-',
            ];
        });
    }

    // ─────────────────────────────────────────────
    // Excel Builder
    // ─────────────────────────────────────────────

    private function buildExcel(string $title, array $columns, $rows, Request $request)
    {
        $spreadsheet = new Spreadsheet();
        $sheet       = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Data PKL');

        $colCount = count($columns);
        $lastCol  = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($colCount);

        // ── Baris 1: Judul ──
        $sheet->mergeCells("A1:{$lastCol}1");
        $sheet->setCellValue('A1', strtoupper($title));
        $sheet->getStyle('A1')->applyFromArray([
            'font'      => ['bold' => true, 'size' => 14, 'color' => ['rgb' => 'FFFFFF']],
            'fill'      => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '4338CA']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER],
        ]);
        $sheet->getRowDimension(1)->setRowHeight(32);

        // ── Baris 2: Keterangan ──
        $filterInfo = 'Diunduh pada: ' . now()->translatedFormat('l, d F Y H:i');
        if ($request->filled('id_gelombang')) {
            $gelombang   = PklGelombang::find($request->id_gelombang);
            $filterInfo .= ' | Gelombang: ' . ($gelombang?->nama_gelombang ?? '-');
        }
        $sheet->mergeCells("A2:{$lastCol}2");
        $sheet->setCellValue('A2', $filterInfo);
        $sheet->getStyle('A2')->applyFromArray([
            'font'      => ['italic' => true, 'size' => 10, 'color' => ['rgb' => '64748B']],
            'fill'      => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'F8FAFC']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
        ]);
        $sheet->getRowDimension(2)->setRowHeight(18);

        // ── Baris 3: Header kolom ──
        $colIndex = 1;
        foreach ($columns as $colLabel) {
            $cellRef = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($colIndex) . '3';
            $sheet->setCellValue($cellRef, $colLabel);
            $colIndex++;
        }
        $sheet->getStyle("A3:{$lastCol}3")->applyFromArray([
            'font'      => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
            'fill'      => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '6366F1']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER, 'wrapText' => true],
            'borders'   => ['allBorders' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['rgb' => 'C7D2FE']]],
        ]);
        $sheet->getRowDimension(3)->setRowHeight(22);

        // ── Data rows ──
        $rowNum = 4;
        foreach ($rows as $row) {
            $colIndex = 1;
            foreach (array_keys($columns) as $colKey) {
                $cellRef = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($colIndex) . $rowNum;
                $sheet->setCellValue($cellRef, $row[$colKey] ?? '-');
                $colIndex++;
            }
            // Zebra stripe
            $bgColor = ($rowNum % 2 === 0) ? 'EEF2FF' : 'FFFFFF';
            $sheet->getStyle("A{$rowNum}:{$lastCol}{$rowNum}")->applyFromArray([
                'fill'      => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => $bgColor]],
                'borders'   => ['allBorders' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['rgb' => 'E2E8F0']]],
                'alignment' => ['vertical' => Alignment::VERTICAL_CENTER],
            ]);
            $sheet->getRowDimension($rowNum)->setRowHeight(18);
            $rowNum++;
        }

        // Auto-size kolom
        for ($i = 1; $i <= $colCount; $i++) {
            $col = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($i);
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        // Freeze panes (baris header tetap saat scroll)
        $sheet->freezePane('A4');

        // Output file
        $filename = 'pkl_' . $request->input('dataset') . '_' . now()->format('Ymd_His') . '.xlsx';
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        header('Cache-Control: max-age=0');

        $writer = new Xlsx($spreadsheet);
        $writer->save('php://output');
        exit;
    }
}
