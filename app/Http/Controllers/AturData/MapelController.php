<?php

namespace App\Http\Controllers\AturData;

use App\Http\Controllers\Controller;
use App\Models\Mapel;
use Illuminate\Http\Request;

class MapelController extends Controller
{
    public function index(Request $request)
    {
        $query = Mapel::query();
        if ($request->filled('search')) $query->where('nama_mapel','like','%'.$request->search.'%')->orWhere('kode_mapel','like','%'.$request->search.'%');
        $perPage   = (int) $request->input('per_page', 20);
        $perPage   = in_array($perPage, [10, 20, 50, 100]) ? $perPage : 20;
        $mapelList = $query->orderBy('nama_mapel')->paginate($perPage)->withQueryString();
        return view('atur-data.mapel.index', compact('mapelList'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'kode_mapel' => 'required|string|max:20|unique:mapel,kode_mapel',
            'nama_mapel' => 'required|string|max:100|unique:mapel,nama_mapel',
        ]);
        Mapel::create($request->only('kode_mapel','nama_mapel'));
        return redirect()->route('atur-data.mapel')->with('success','Mata pelajaran berhasil ditambahkan.');
    }

    public function update(Request $request, $id)
    {
        $mapel = Mapel::findOrFail($id);
        $request->validate([
            'kode_mapel' => 'required|string|max:20|unique:mapel,kode_mapel,'.$id.',id_mapel',
            'nama_mapel' => 'required|string|max:100|unique:mapel,nama_mapel,'.$id.',id_mapel',
        ]);
        $mapel->update($request->only('kode_mapel','nama_mapel'));
        return redirect()->route('atur-data.mapel')->with('success','Mata pelajaran berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $mapel = Mapel::findOrFail($id);

        // Cascade delete related records
        $mapel->kemajuan()->delete();
        $mapel->jadwalTemplate()->delete();
        $mapel->jadwalHarian()->delete();

        $mapel->delete();
        return redirect()->route('atur-data.mapel')->with('success', 'Mata pelajaran beserta data terkait berhasil dihapus.');
    }

    public function bulkDestroy(Request $request)
    {
        $ids = $request->input('ids', []);
        if (empty($ids)) {
            return redirect()->route('atur-data.mapel')->with('error', 'Tidak ada data mata pelajaran yang terpilih.');
        }

        \Illuminate\Support\Facades\DB::beginTransaction();
        try {
            foreach ($ids as $id) {
                $mapel = Mapel::find($id);
                if ($mapel) {
                    // Cascade delete related records
                    $mapel->kemajuan()->delete();
                    $mapel->jadwalTemplate()->delete();
                    $mapel->jadwalHarian()->delete();

                    $mapel->delete();
                }
            }
            \Illuminate\Support\Facades\DB::commit();
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\DB::rollBack();
            return redirect()->route('atur-data.mapel')->with('error', 'Terjadi kesalahan saat menghapus data mata pelajaran: ' . $e->getMessage());
        }

        return redirect()->route('atur-data.mapel')->with('success', 'Berhasil menghapus ' . count($ids) . ' data mata pelajaran terpilih.');
    }

    public function importTemplate()
    {
        $filename = "template_mata_pelajaran.xlsx";
        $tmpFile = tempnam(sys_get_temp_dir(), 'mapel_template_') . '.xlsx';

        $writer = new \OpenSpout\Writer\XLSX\Writer();
        $writer->openToFile($tmpFile);

        // Header Row
        $writer->addRow(\OpenSpout\Common\Entity\Row::fromValues(['kode_mapel', 'nama_mapel']));

        // Sample Rows
        $writer->addRow(\OpenSpout\Common\Entity\Row::fromValues(['MTK', 'Matematika']));
        $writer->addRow(\OpenSpout\Common\Entity\Row::fromValues(['IND', 'Bahasa Indonesia']));
        $writer->addRow(\OpenSpout\Common\Entity\Row::fromValues(['ING', 'Bahasa Inggris']));

        $writer->close();

        return response()->download($tmpFile, $filename, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        ])->deleteFileAfterSend(true);
    }

    public function importProcess(Request $request)
    {
        $request->validate([
            'file' => 'required|file|max:2048',
        ]);

        ini_set('max_execution_time', 300);
        set_time_limit(300);

        $file = $request->file('file');
        $path = $file->getRealPath();

        $reader = new \OpenSpout\Reader\XLSX\Reader();
        try {
            $reader->open($path);
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal membuka file Excel: ' . $e->getMessage());
        }

        $imported = 0;
        $skipped  = [];
        $lineNum  = 0;

        \Illuminate\Support\Facades\DB::beginTransaction();

        try {
            $headerRow = [];
            foreach ($reader->getSheetIterator() as $sheet) {
                foreach ($sheet->getRowIterator() as $rowEntity) {
                    $lineNum++;
                    $row = $rowEntity->toArray();

                    if ($lineNum === 1) {
                        $headerRow = array_map(function($h) {
                            return trim(strtolower(preg_replace('/[^a-zA-Z0-9]/', '', (string)$h)));
                        }, $row);

                        $kodeIndex = array_search('kodemapel', $headerRow);
                        $namaIndex = array_search('namamapel', $headerRow);

                        if ($kodeIndex === false || $namaIndex === false) {
                            throw new \Exception('Header Excel harus berisi kolom: kode_mapel, nama_mapel.');
                        }
                        continue;
                    }

                    if (empty($row) || count($row) < 2) continue;

                    $kode_mapel = isset($row[$kodeIndex]) ? trim((string)$row[$kodeIndex]) : '';
                    $nama_mapel = isset($row[$namaIndex]) ? trim((string)$row[$namaIndex]) : '';

                    if (empty($kode_mapel) || empty($nama_mapel)) {
                        $skipped[] = "Baris $lineNum: Kolom kode_mapel/nama_mapel tidak boleh kosong.";
                        continue;
                    }

                    if (Mapel::where('kode_mapel', $kode_mapel)->exists()) {
                        $skipped[] = "Baris $lineNum: Kode Mapel '$kode_mapel' sudah terdaftar.";
                        continue;
                    }

                    if (Mapel::where('nama_mapel', $nama_mapel)->exists()) {
                        $skipped[] = "Baris $lineNum: Nama Mata Pelajaran '$nama_mapel' sudah terdaftar.";
                        continue;
                    }

                    Mapel::create([
                        'kode_mapel' => $kode_mapel,
                        'nama_mapel' => $nama_mapel,
                    ]);

                    $imported++;
                }
                break;
            }
            $reader->close();
            \Illuminate\Support\Facades\DB::commit();
        } catch (\Exception $e) {
            $reader->close();
            \Illuminate\Support\Facades\DB::rollBack();
            return redirect()->back()->with('error', 'Terjadi kesalahan sistem saat memproses file: ' . $e->getMessage());
        }

        $successMsg = "Berhasil mengimpor $imported data mata pelajaran.";
        if (!empty($skipped)) {
            $skippedMsg = implode(' | ', array_slice($skipped, 0, 5));
            if (count($skipped) > 5) {
                $skippedMsg .= " (dan " . (count($skipped) - 5) . " baris lainnya)";
            }
            return redirect()->route('atur-data.mapel')
                ->with('success', $successMsg)
                ->with('warning', "Beberapa data dilewati: " . $skippedMsg);
        }

        return redirect()->route('atur-data.mapel')->with('success', $successMsg);
    }
}
