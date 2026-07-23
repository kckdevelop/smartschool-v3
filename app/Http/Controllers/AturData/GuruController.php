<?php

namespace App\Http\Controllers\AturData;

use App\Http\Controllers\Controller;
use App\Models\Guru;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class GuruController extends Controller
{
    public function index(Request $request)
    {
        $query = Guru::query();
        if ($request->filled('status'))  $query->where('status',$request->status);
        if ($request->filled('guru_bk')) $query->where('guru_bk',$request->guru_bk);
        if ($request->filled('search'))  $query->where(function($q) use($request){
            $q->where('nama_guru','like','%'.$request->search.'%')
              ->orWhere('no_id','like','%'.$request->search.'%')
              ->orWhere('no_hp','like','%'.$request->search.'%');
        });
        $perPage  = (int) $request->input('per_page', 20);
        $perPage  = in_array($perPage, [10, 20, 50, 100]) ? $perPage : 20;
        $guruList = $query->orderBy('nama_guru')->paginate($perPage)->withQueryString();
        return view('atur-data.guru.index', compact('guruList'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'no_id'       => 'required|integer|unique:guru,no_id',
            'nama_guru'   => 'required|string|max:100',
            'jenkel'      => 'required|in:L,P',
            'no_hp'       => 'nullable|string|max:20',
            'guru_bk'     => 'required|in:ya,tidak',
            'guru_ismuba' => 'required|in:ya,tidak',
            'status'      => 'required|in:aktif,tidak',
            'password'    => 'required|string|min:4',
        ]);
        Guru::create([
            'no_id'       => $request->no_id,
            'nama_guru'   => $request->nama_guru,
            'jenkel'      => $request->jenkel,
            'no_hp'       => $request->no_hp,
            'guru_bk'     => $request->guru_bk,
            'guru_ismuba' => $request->guru_ismuba,
            'status'      => $request->status,
            'password'    => Hash::make($request->password),
        ]);
        return redirect()->route('atur-data.guru')->with('success','Data guru berhasil ditambahkan.');
    }

    public function update(Request $request, $id)
    {
        $guru = Guru::findOrFail($id);

        if ($request->ajax() || $request->wantsJson()) {
            $request->validate([
                'guru_bk'     => 'nullable|in:ya,tidak',
                'guru_ismuba' => 'nullable|in:ya,tidak',
                'status'      => 'nullable|in:aktif,tidak',
            ]);

            $guru->update($request->only('guru_bk', 'guru_ismuba', 'status'));

            return response()->json([
                'success' => true,
                'message' => 'Status guru berhasil diperbarui.',
                'data'    => $guru
            ]);
        }

        $request->validate([
            'no_id'       => 'required|integer|unique:guru,no_id,'.$id.',id_guru',
            'nama_guru'   => 'required|string|max:100',
            'jenkel'      => 'required|in:L,P',
            'no_hp'       => 'nullable|string|max:20',
            'guru_bk'     => 'required|in:ya,tidak',
            'guru_ismuba' => 'required|in:ya,tidak',
            'status'      => 'required|in:aktif,tidak',
        ]);
        $guru->update($request->only('no_id','nama_guru','jenkel','no_hp','guru_bk','guru_ismuba','status'));
        return redirect()->route('atur-data.guru')->with('success','Data guru berhasil diperbarui.');
    }

    public function resetPassword(Request $request, $id)
    {
        $guru = Guru::findOrFail($id);
        $request->validate(['password' => 'required|string|min:4']);
        $guru->update(['password' => Hash::make($request->password)]);
        return redirect()->route('atur-data.guru')->with('success','Password guru berhasil direset.');
    }

    public function uploadFoto(Request $request, $id)
    {
        $guru = Guru::findOrFail($id);

        $request->validate([
            'foto' => 'required|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        // Hapus foto lama jika ada
        if ($guru->foto && Storage::disk('public')->exists($guru->foto)) {
            Storage::disk('public')->delete($guru->foto);
        }

        $path = $request->file('foto')->store('foto-guru', 'public');
        $guru->update(['foto' => $path]);

        return response()->json([
            'success'  => true,
            'message'  => 'Foto guru berhasil diunggah.',
            'foto_url' => asset('storage/' . $path),
        ]);
    }

    public function deleteFoto($id)
    {
        $guru = Guru::findOrFail($id);

        if ($guru->foto && Storage::disk('public')->exists($guru->foto)) {
            Storage::disk('public')->delete($guru->foto);
        }

        $guru->update(['foto' => null]);

        return response()->json([
            'success' => true,
            'message' => 'Foto guru berhasil dihapus.',
        ]);
    }

    public function destroy($id)
    {
        $guru = Guru::findOrFail($id);

        \Illuminate\Support\Facades\DB::beginTransaction();
        try {
            // Lepas wali kelas di semua kelas yang diasuh guru ini
            \Illuminate\Support\Facades\DB::table('kelas')
                ->where('walikelas', $guru->id_guru)
                ->update(['walikelas' => null]);

            // Hapus relasi via model
            $guru->btaq()->delete();
            $guru->kemajuan()->delete();
            $guru->tadarus()->delete();
            $guru->tugas()->delete();
            $guru->riwayatPoin()->delete();
            $guru->riwayatReward()->delete();
            $guru->kehadiranPengajian()->delete();

            // Hapus relasi via DB langsung (tabel tanpa model relation)
            \Illuminate\Support\Facades\DB::table('pkl_pembimbing')->where('id_guru', $guru->id_guru)->delete();
            \Illuminate\Support\Facades\DB::table('buku_kasus')->where('id_guru', $guru->id_guru)->delete();
            \Illuminate\Support\Facades\DB::table('bimbingan_konseling')->where('id_guru', $guru->id_guru)->delete();
            \Illuminate\Support\Facades\DB::table('home_visit')->where('id_guru', $guru->id_guru)->delete();
            \Illuminate\Support\Facades\DB::table('panggil_ortu')->where('id_guru', $guru->id_guru)->delete();
            \Illuminate\Support\Facades\DB::table('gaya_belajar')->where('id_guru', $guru->id_guru)->delete();
            \Illuminate\Support\Facades\DB::table('pantau_ibadah')->where('id_guru', $guru->id_guru)->delete();

            $guru->delete();
            \Illuminate\Support\Facades\DB::commit();
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\DB::rollBack();
            return redirect()->route('atur-data.guru')->with('error', 'Gagal menghapus: ' . $e->getMessage());
        }

        return redirect()->route('atur-data.guru')->with('success','Data guru berhasil dihapus.');
    }

    public function bulkDestroy(Request $request)
    {
        $ids = $request->input('ids', []);
        if (empty($ids)) {
            return redirect()->route('atur-data.guru')->with('error', 'Tidak ada data guru yang terpilih.');
        }

        \Illuminate\Support\Facades\DB::beginTransaction();
        try {
            foreach ($ids as $id) {
                $guru = Guru::find($id);
                if (!$guru) continue;

                // Lepas wali kelas di semua kelas yang diasuh guru ini
                \Illuminate\Support\Facades\DB::table('kelas')
                    ->where('walikelas', $guru->id_guru)
                    ->update(['walikelas' => null]);

                // Hapus relasi via model
                $guru->btaq()->delete();
                $guru->kemajuan()->delete();
                $guru->tadarus()->delete();
                $guru->tugas()->delete();
                $guru->riwayatPoin()->delete();
                $guru->riwayatReward()->delete();
                $guru->kehadiranPengajian()->delete();

                // Hapus relasi via DB langsung (tabel tanpa model relation)
                \Illuminate\Support\Facades\DB::table('pkl_pembimbing')->where('id_guru', $guru->id_guru)->delete();
                \Illuminate\Support\Facades\DB::table('buku_kasus')->where('id_guru', $guru->id_guru)->delete();
                \Illuminate\Support\Facades\DB::table('bimbingan_konseling')->where('id_guru', $guru->id_guru)->delete();
                \Illuminate\Support\Facades\DB::table('home_visit')->where('id_guru', $guru->id_guru)->delete();
                \Illuminate\Support\Facades\DB::table('panggil_ortu')->where('id_guru', $guru->id_guru)->delete();
                \Illuminate\Support\Facades\DB::table('gaya_belajar')->where('id_guru', $guru->id_guru)->delete();
                \Illuminate\Support\Facades\DB::table('pantau_ibadah')->where('id_guru', $guru->id_guru)->delete();

                $guru->delete();
            }
            \Illuminate\Support\Facades\DB::commit();
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\DB::rollBack();
            return redirect()->route('atur-data.guru')->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }

        return redirect()->route('atur-data.guru')->with('success', 'Berhasil menghapus ' . count($ids) . ' data guru terpilih.');
    }



    public function importTemplate()
    {
        $filename = "template_guru.xlsx";
        $tmpFile = tempnam(sys_get_temp_dir(), 'guru_template_') . '.xlsx';

        $writer = new \OpenSpout\Writer\XLSX\Writer();
        $writer->openToFile($tmpFile);

        // Header Row
        $writer->addRow(\OpenSpout\Common\Entity\Row::fromValues(['no_id', 'nama_guru', 'jenkel', 'no_hp', 'guru_bk', 'guru_ismuba']));

        // Sample Rows
        $writer->addRow(\OpenSpout\Common\Entity\Row::fromValues(['1003', 'Ahmad Ridwan, S.Ag.', 'L', '081234567890', 'tidak', 'ya']));
        $writer->addRow(\OpenSpout\Common\Entity\Row::fromValues(['1004', 'Siti Rahma, S.Pd.', 'P', '085712345678', 'ya', 'tidak']));

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
        $skipped = [];
        $lineNum = 0;

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

                        $noIdIndex = array_search('noid', $headerRow);
                        $namaIndex = array_search('namaguru', $headerRow);
                        if ($namaIndex === false) {
                            $namaIndex = array_search('nama', $headerRow);
                        }
                        $jenkelIndex = array_search('jenkel', $headerRow);
                        if ($jenkelIndex === false) {
                            $jenkelIndex = array_search('jeniskelamin', $headerRow);
                        }
                        $noHpIndex = array_search('nohp', $headerRow);
                        if ($noHpIndex === false) {
                            $noHpIndex = array_search('no_hp', $headerRow);
                        }
                        $guruBkIndex = array_search('gurubk', $headerRow);
                        $guruIsmubaIndex = array_search('guruismuba', $headerRow);

                        if ($noIdIndex === false || $namaIndex === false || $jenkelIndex === false || $guruBkIndex === false || $guruIsmubaIndex === false) {
                            throw new \Exception('Header Excel harus berisi kolom: no_id, nama_guru, jenkel, guru_bk, guru_ismuba.');
                        }
                        continue;
                    }

                    if (empty($row) || count($row) < 3) continue;

                    $no_id = isset($row[$noIdIndex]) ? trim((string)$row[$noIdIndex]) : '';
                    $nama_guru = isset($row[$namaIndex]) ? trim((string)$row[$namaIndex]) : '';
                    $jenkel = isset($row[$jenkelIndex]) ? strtoupper(trim((string)$row[$jenkelIndex])) : 'L';
                    $no_hp = ($noHpIndex !== false && isset($row[$noHpIndex])) ? trim((string)$row[$noHpIndex]) : null;
                    $guru_bk = isset($row[$guruBkIndex]) ? strtolower(trim((string)$row[$guruBkIndex])) : 'tidak';
                    $guru_ismuba = isset($row[$guruIsmubaIndex]) ? strtolower(trim((string)$row[$guruIsmubaIndex])) : 'tidak';

                    if (empty($no_id) || empty($nama_guru)) {
                        $skipped[] = "Baris $lineNum: Kolom no_id/Nama tidak boleh kosong.";
                        continue;
                    }

                    if (!is_numeric($no_id)) {
                        $skipped[] = "Baris $lineNum: ID Guru '$no_id' harus berupa angka.";
                        continue;
                    }

                    if ($jenkel !== 'L' && $jenkel !== 'P') {
                        $jenkel = 'L';
                    }

                    if ($guru_bk !== 'ya' && $guru_bk !== 'tidak') {
                        $guru_bk = 'tidak';
                    }

                    if ($guru_ismuba !== 'ya' && $guru_ismuba !== 'tidak') {
                        $guru_ismuba = 'tidak';
                    }

                    $exists = Guru::where('no_id', $no_id)->exists();
                    if ($exists) {
                        $skipped[] = "Baris $lineNum: ID Guru '$no_id' sudah terdaftar.";
                        continue;
                    }

                    Guru::create([
                        'no_id' => $no_id,
                        'nama_guru' => $nama_guru,
                        'jenkel' => $jenkel,
                        'no_hp' => $no_hp,
                        'guru_bk' => $guru_bk,
                        'guru_ismuba' => $guru_ismuba,
                        'status' => 'aktif',
                        'password' => Hash::make($no_id, ['rounds' => 4]),
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
            return redirect()->back()->with('error', 'Terjadi kesalahan sistem saat memproses file Excel: ' . $e->getMessage());
        }

        $successMsg = "Berhasil mengimpor $imported data guru.";
        if (!empty($skipped)) {
            $skippedMsg = implode(' | ', array_slice($skipped, 0, 5));
            if (count($skipped) > 5) {
                $skippedMsg .= " (dan " . (count($skipped) - 5) . " baris lainnya)";
            }
            return redirect()->route('atur-data.guru')
                ->with('success', $successMsg)
                ->with('warning', "Beberapa data dilewati: " . $skippedMsg);
        }

        return redirect()->route('atur-data.guru')->with('success', $successMsg);
    }
}
