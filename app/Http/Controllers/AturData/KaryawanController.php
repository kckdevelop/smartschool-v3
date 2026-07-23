<?php

namespace App\Http\Controllers\AturData;

use App\Http\Controllers\Controller;
use App\Models\Karyawan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class KaryawanController extends Controller
{
    public function index(Request $request)
    {
        $query = Karyawan::query();
        if ($request->filled('status')) $query->where('status', $request->status);
        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('nama_karyawan', 'like', '%' . $request->search . '%')
                  ->orWhere('no_id', 'like', '%' . $request->search . '%');
            });
        }

        $perPage = (int) $request->input('per_page', 20);
        $perPage = in_array($perPage, [10, 20, 50, 100]) ? $perPage : 20;

        $karyawanList = $query->orderBy('nama_karyawan')->paginate($perPage)->withQueryString();

        return view('atur-data.karyawan.index', compact('karyawanList'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'no_id'         => 'required|integer|unique:karyawan,no_id',
            'nama_karyawan' => 'required|string|max:100',
            'jenkel'        => 'required|in:L,P',
            'status'        => 'required|in:aktif,tidak',
            'petugas_uks'   => 'nullable|in:ya,tidak',
            'password'      => 'required|string|min:4',
        ]);

        Karyawan::create([
            'no_id'         => $request->no_id,
            'nama_karyawan' => $request->nama_karyawan,
            'jenkel'        => $request->jenkel,
            'status'        => $request->status,
            'petugas_uks'   => $request->input('petugas_uks', 'tidak'),
            'password'      => Hash::make($request->password),
        ]);

        return redirect()->route('atur-data.karyawan')->with('success', 'Data karyawan berhasil ditambahkan.');
    }

    public function update(Request $request, $id)
    {
        $karyawan = Karyawan::findOrFail($id);
        $request->validate([
            'no_id'         => 'required|integer|unique:karyawan,no_id,' . $id . ',id_karyawan',
            'nama_karyawan' => 'required|string|max:100',
            'jenkel'        => 'required|in:L,P',
            'status'        => 'required|in:aktif,tidak',
            'petugas_uks'   => 'nullable|in:ya,tidak',
        ]);

        $karyawan->update([
            'no_id'         => $request->no_id,
            'nama_karyawan' => $request->nama_karyawan,
            'jenkel'        => $request->jenkel,
            'status'        => $request->status,
            'petugas_uks'   => $request->input('petugas_uks', 'tidak'),
        ]);

        return redirect()->route('atur-data.karyawan')->with('success', 'Data karyawan berhasil diperbarui.');
    }

    public function resetPassword(Request $request, $id)
    {
        $karyawan = Karyawan::findOrFail($id);
        $request->validate(['password' => 'required|string|min:4']);

        $karyawan->update(['password' => Hash::make($request->password)]);

        return redirect()->route('atur-data.karyawan')->with('success', 'Password karyawan berhasil direset.');
    }

    public function uploadFoto(Request $request, $id)
    {
        $karyawan = Karyawan::findOrFail($id);

        $request->validate([
            'foto' => 'required|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        // Hapus foto lama jika ada
        if ($karyawan->foto && Storage::disk('public')->exists($karyawan->foto)) {
            Storage::disk('public')->delete($karyawan->foto);
        }

        $path = $request->file('foto')->store('foto-karyawan', 'public');
        $karyawan->update(['foto' => $path]);

        return response()->json([
            'success'  => true,
            'message'  => 'Foto karyawan berhasil diunggah.',
            'foto_url' => asset('storage/' . $path),
        ]);
    }

    public function deleteFoto($id)
    {
        $karyawan = Karyawan::findOrFail($id);

        if ($karyawan->foto && Storage::disk('public')->exists($karyawan->foto)) {
            Storage::disk('public')->delete($karyawan->foto);
        }

        $karyawan->update(['foto' => null]);

        return response()->json([
            'success' => true,
            'message' => 'Foto karyawan berhasil dihapus.',
        ]);
    }

    public function destroy($id)
    {
        $karyawan = Karyawan::findOrFail($id);

        DB::beginTransaction();
        try {
            $karyawan->kehadiranPengajian()->delete();
            $karyawan->delete();
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('atur-data.karyawan')->with('error', 'Gagal menghapus data karyawan: ' . $e->getMessage());
        }

        return redirect()->route('atur-data.karyawan')->with('success', 'Data karyawan berhasil dihapus.');
    }

    public function bulkDestroy(Request $request)
    {
        $ids = $request->input('ids', []);
        if (empty($ids)) {
            return redirect()->route('atur-data.karyawan')->with('error', 'Tidak ada data karyawan yang terpilih.');
        }

        DB::beginTransaction();
        try {
            foreach ($ids as $id) {
                $karyawan = Karyawan::find($id);
                if (!$karyawan) continue;

                $karyawan->kehadiranPengajian()->delete();
                $karyawan->delete();
            }
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('atur-data.karyawan')->with('error', 'Terjadi kesalahan saat menghapus data: ' . $e->getMessage());
        }

        return redirect()->route('atur-data.karyawan')->with('success', 'Berhasil menghapus ' . count($ids) . ' data karyawan terpilih.');
    }

    public function importTemplate()
    {
        $filename = "template_karyawan.xlsx";
        $tmpFile = tempnam(sys_get_temp_dir(), 'karyawan_template_') . '.xlsx';

        $writer = new \OpenSpout\Writer\XLSX\Writer();
        $writer->openToFile($tmpFile);

        // Header Row
        $writer->addRow(\OpenSpout\Common\Entity\Row::fromValues(['no_id', 'nama_karyawan', 'jenkel']));

        // Sample Rows
        $writer->addRow(\OpenSpout\Common\Entity\Row::fromValues(['2001', 'Budi Santoso', 'L']));
        $writer->addRow(\OpenSpout\Common\Entity\Row::fromValues(['2002', 'Dewi Rahmawati', 'P']));
        $writer->addRow(\OpenSpout\Common\Entity\Row::fromValues(['2003', 'Ahmad Fauzi', 'L']));

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

        DB::beginTransaction();

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

                        $noIdIndex = array_search('noid', $headerRow);
                        $namaIndex = array_search('namakaryawan', $headerRow);
                        if ($namaIndex === false) {
                            $namaIndex = array_search('nama', $headerRow);
                        }
                        $jenkelIndex = array_search('jenkel', $headerRow);
                        if ($jenkelIndex === false) {
                            $jenkelIndex = array_search('jeniskelamin', $headerRow);
                        }

                        if ($noIdIndex === false || $namaIndex === false || $jenkelIndex === false) {
                            throw new \Exception('Header Excel harus berisi kolom: no_id, nama_karyawan, jenkel.');
                        }
                        continue;
                    }

                    if (empty($row) || count($row) < 2) continue;

                    $no_id         = isset($row[$noIdIndex]) ? trim((string)$row[$noIdIndex]) : '';
                    $nama_karyawan = isset($row[$namaIndex])  ? trim((string)$row[$namaIndex])  : '';
                    $jenkel        = isset($row[$jenkelIndex]) ? strtoupper(trim((string)$row[$jenkelIndex])) : 'L';

                    if (empty($no_id) || empty($nama_karyawan)) {
                        $skipped[] = "Baris $lineNum: Kolom no_id/nama tidak boleh kosong.";
                        continue;
                    }

                    if (!is_numeric($no_id)) {
                        $skipped[] = "Baris $lineNum: No ID '$no_id' harus berupa angka.";
                        continue;
                    }

                    if ($jenkel !== 'L' && $jenkel !== 'P') {
                        $jenkel = 'L';
                    }

                    if (Karyawan::where('no_id', $no_id)->exists()) {
                        $skipped[] = "Baris $lineNum: No ID '$no_id' sudah terdaftar.";
                        continue;
                    }

                    Karyawan::create([
                        'no_id'         => $no_id,
                        'nama_karyawan' => $nama_karyawan,
                        'jenkel'        => $jenkel,
                        'status'        => 'aktif',
                        'password'      => Hash::make($no_id, ['rounds' => 4]),
                    ]);

                    $imported++;
                }
                break;
            }
            $reader->close();
            DB::commit();
        } catch (\Exception $e) {
            $reader->close();
            DB::rollBack();
            return redirect()->back()->with('error', 'Terjadi kesalahan sistem saat memproses file: ' . $e->getMessage());
        }

        $successMsg = "Berhasil mengimpor $imported data karyawan.";
        if (!empty($skipped)) {
            $skippedMsg = implode(' | ', array_slice($skipped, 0, 5));
            if (count($skipped) > 5) {
                $skippedMsg .= " (dan " . (count($skipped) - 5) . " baris lainnya)";
            }
            return redirect()->route('atur-data.karyawan')
                ->with('success', $successMsg)
                ->with('warning', "Beberapa data dilewati: " . $skippedMsg);
        }

        return redirect()->route('atur-data.karyawan')->with('success', $successMsg);
    }
}
