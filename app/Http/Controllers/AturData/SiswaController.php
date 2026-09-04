<?php

namespace App\Http\Controllers\AturData;

use App\Http\Controllers\Controller;
use App\Models\UserSiswa;
use App\Models\DetailSiswa;
use App\Models\Kelas;
use App\Models\TahunAjaran;
use App\Models\Semester;
use Illuminate\Http\Request;

class SiswaController extends Controller
{
    public function index(Request $request)
    {
        $query = UserSiswa::with(['kelas.jurusan']);
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        } else {
            $query->where('status', '!=', 'tidak');
        }
        if ($request->filled('id_kelas')) $query->where('id_kelas',$request->id_kelas);
        if ($request->filled('search'))   $query->where(function($q) use($request){
            $q->where('nama_siswa','like','%'.$request->search.'%')->orWhere('nis','like','%'.$request->search.'%');
        });
        $perPage   = (int) $request->input('per_page', 20);
        $perPage   = in_array($perPage, [10, 20, 50, 100]) ? $perPage : 20;
        $siswaList = $query->orderBy('nama_siswa')->paginate($perPage)->withQueryString();
        $kelasList = Kelas::where('status','aktif')->with('jurusan')->orderBy('tingkat')->orderBy('rombel')->get();
        $sekolah   = \App\Models\Sekolah::first();
        return view('atur-data.siswa.index', compact('siswaList','kelasList','sekolah'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nis'          => 'required|integer|unique:user_siswa,nis',
            'nisn'         => 'nullable|string|max:20',
            'nik'          => 'nullable|string|max:20',
            'password'     => 'required|string|min:4',
            'id_kelas'     => 'required|integer|exists:kelas,id_kelas',
            'nama_siswa'   => 'required|string|max:100',
            'jenkel'       => 'required|in:L,P',
            'tempat_lahir' => 'nullable|string|max:30',
            'tgl_lahir'    => 'nullable|date',
            'status'       => 'required|in:aktif,tidak,keluar',
        ]);
        $siswa = UserSiswa::create([
            'nis'           => $request->nis,
            'nisn'          => $request->nisn,
            'nik'           => $request->nik,
            'password'      => sha1($request->password),
            'password_wali' => sha1($request->password),
            'id_kelas'      => $request->id_kelas,
            'nama_siswa'    => $request->nama_siswa,
            'jenkel'        => $request->jenkel,
            'tempat_lahir'  => $request->tempat_lahir,
            'tgl_lahir'     => $request->tgl_lahir,
            'kelengkapan'   => 0,
            'status'        => $request->status,
        ]);

        if ($request->status === 'keluar') {
            $siswa->presensi()->delete();
            $siswa->logAbsensi()->delete();
            $siswa->btaq()->delete();
            $siswa->kesehatan()->delete();
            
            // Delete related riwayat_obat records before deleting kunjungan_uks records to satisfy foreign key constraint
            $kunjunganIds = $siswa->kunjunganUks()->pluck('id_kunjungan');
            \Illuminate\Support\Facades\DB::table('riwayat_obat')->whereIn('id_kunjungan', $kunjunganIds)->delete();

            $siswa->kunjunganUks()->delete();
            $siswa->riwayatPoin()->delete();
            $siswa->riwayatReward()->delete();
            $siswa->dataCheckup()->delete();
            $siswa->tagihan()->delete();
        }

        return redirect()->route('atur-data.siswa')->with('success','Siswa berhasil ditambahkan.');
    }

    public function update(Request $request, $nis)
    {
        $siswa = UserSiswa::findOrFail($nis);
        $request->validate([
            'id_kelas'     => 'required|integer|exists:kelas,id_kelas',
            'nama_siswa'   => 'required|string|max:100',
            'nisn'         => 'nullable|string|max:20',
            'nik'          => 'nullable|string|max:20',
            'jenkel'       => 'required|in:L,P',
            'tempat_lahir' => 'nullable|string|max:30',
            'tgl_lahir'    => 'nullable|date',
            'status'       => 'required|in:aktif,tidak,keluar',
        ]);
        
        $oldStatus = $siswa->status;
        $siswa->update($request->only('id_kelas','nama_siswa','nisn','nik','jenkel','tempat_lahir','tgl_lahir','status'));
        
        if ($request->status === 'keluar' && $oldStatus !== 'keluar') {
            $siswa->presensi()->delete();
            $siswa->logAbsensi()->delete();
            $siswa->btaq()->delete();
            $siswa->kesehatan()->delete();
            
            // Delete related riwayat_obat records before deleting kunjungan_uks records to satisfy foreign key constraint
            $kunjunganIds = $siswa->kunjunganUks()->pluck('id_kunjungan');
            \Illuminate\Support\Facades\DB::table('riwayat_obat')->whereIn('id_kunjungan', $kunjunganIds)->delete();

            $siswa->kunjunganUks()->delete();
            $siswa->riwayatPoin()->delete();
            $siswa->riwayatReward()->delete();
            $siswa->dataCheckup()->delete();
            $siswa->tagihan()->delete();
        }

        return redirect()->route('atur-data.siswa')->with('success','Data siswa berhasil diperbarui.');
    }

    public function resetPassword(Request $request, $nis)
    {
        $siswa = UserSiswa::findOrFail($nis);
        $request->validate(['password' => 'required|string|min:4']);
        $siswa->update(['password' => sha1($request->password), 'password_wali' => sha1($request->password)]);
        return redirect()->route('atur-data.siswa')->with('success','Password siswa berhasil direset.');
    }



    public function show($nis)
    {
        $siswa = UserSiswa::with([
            'kelas.jurusan',
            'detail',
            'riwayatPoin',
            'kunjunganUks',
            'bimbinganKonseling.guru',
            'btaq.guru',
            'pantauIbadah.guru',
            'riwayatKesehatan',
        ])
            ->withCount('presensi')
            ->findOrFail($nis);

        // Status mapping: support both numeric (1,2,3,4) and string (Hadir, Sakit, Izin, Alfa)
        $statusMap = [
            '1' => ['1', 'Hadir', 'hadir'],
            '2' => ['2', 'Sakit', 'sakit'],
            '3' => ['3', 'Izin', 'izin'],
            '4' => ['4', 'Alfa', 'alfa', 'Alpha', 'alpha'],
        ];

        // Data tahun ajaran & semester for filters
        $tahunList     = TahunAjaran::orderByDesc('tahun')->get();
        $semesterList  = Semester::with('tahunAjaran')->orderByDesc('id_tahun')->orderBy('semester')->get();

        $filterStatus   = request('filter_status');
        $filterTahun    = request('filter_tahun');   // e.g. "2024/2025"
        $filterSemester = request('filter_semester'); // id_semester

        // Base query
        $baseQuery = \App\Models\Presensi::where('nis', $nis);

        // Filter by tahun ajaran (match year portion of tanggal)
        if ($filterTahun) {
            // tahun format: "2024/2025" → extract start year and end year
            $parts = explode('/', $filterTahun);
            $yearStart = trim($parts[0] ?? '');
            $yearEnd   = trim($parts[1] ?? $yearStart);
            $baseQuery->where(function ($q) use ($yearStart, $yearEnd) {
                $q->whereRaw("YEAR(tanggal) = ?", [$yearStart])
                  ->orWhereRaw("YEAR(tanggal) = ?", [$yearEnd]);
            });
        }

        // Filter by semester (use awal/akhir dates)
        if ($filterSemester) {
            $sem = Semester::find($filterSemester);
            if ($sem && $sem->awal && $sem->akhir) {
                $baseQuery->whereBetween('tanggal', [
                    $sem->awal->format('Y-m-d'),
                    $sem->akhir->format('Y-m-d'),
                ]);
            }
        }

        // Filter by status — handle both numeric and string values
        if ($filterStatus && isset($statusMap[$filterStatus])) {
            $variants = $statusMap[$filterStatus];
            $baseQuery->where(function ($q) use ($variants) {
                foreach ($variants as $v) {
                    $q->orWhere('status', $v);
                }
            });
        }

        // Clone for stats (without status filter)
        $statsQuery = clone $baseQuery;
        // Remove status filter from statsQuery — rebuild without status condition
        $statsQuery = \App\Models\Presensi::where('nis', $nis);
        if ($filterTahun) {
            $parts = explode('/', $filterTahun);
            $yearStart = trim($parts[0] ?? '');
            $yearEnd   = trim($parts[1] ?? $yearStart);
            $statsQuery->where(function ($q) use ($yearStart, $yearEnd) {
                $q->whereRaw("YEAR(tanggal) = ?", [$yearStart])
                  ->orWhereRaw("YEAR(tanggal) = ?", [$yearEnd]);
            });
        }
        if ($filterSemester) {
            $sem = $sem ?? Semester::find($filterSemester);
            if ($sem && $sem->awal && $sem->akhir) {
                $statsQuery->whereBetween('tanggal', [
                    $sem->awal->format('Y-m-d'),
                    $sem->akhir->format('Y-m-d'),
                ]);
            }
        }

        // Paginate presensi records (10 per page)
        $presensi = (clone $baseQuery)
            ->orderByDesc('tanggal')
            ->orderByDesc('id_presensi')
            ->paginate(10)
            ->withQueryString();

        // Compute aggregate attendance stats (numeric + string variants)
        $presensiStats = $statsQuery->selectRaw("
                SUM(CASE WHEN status IN ('1','Hadir','hadir') THEN 1 ELSE 0 END) as hadir,
                SUM(CASE WHEN status IN ('2','Sakit','sakit') THEN 1 ELSE 0 END) as sakit,
                SUM(CASE WHEN status IN ('3','Izin','izin')   THEN 1 ELSE 0 END) as izin,
                SUM(CASE WHEN status IN ('4','Alfa','alfa','Alpha','alpha') THEN 1 ELSE 0 END) as alfa
            ")->first();

        // ISMUBA data
        $btaqSiswa    = $siswa->btaq->sortByDesc('tanggal');
        $ibadahSiswa  = $siswa->pantauIbadah->sortByDesc('tanggal');

        return view('atur-data.siswa.show', compact(
            'siswa', 'presensi', 'presensiStats',
            'tahunList', 'semesterList',
            'filterTahun', 'filterSemester', 'filterStatus',
            'btaqSiswa', 'ibadahSiswa'
        ));
    }

    public function editDetail($nis)
    {
        $siswa = UserSiswa::with(['kelas', 'detail'])->findOrFail($nis);
        return view('atur-data.siswa.edit-detail', compact('siswa'));
    }

    public function updateDetail(Request $request, $nis)
    {
        $siswa = UserSiswa::findOrFail($nis);

        $request->validate([
            'alamat'         => 'nullable|string',
            'agama'          => 'nullable|string|max:30',
            'golongan_darah' => 'nullable|string|max:5',
            'nama_ayah'      => 'nullable|string|max:100',
            'pekerjaan_ayah' => 'nullable|string|max:100',
            'no_telp_ayah'   => 'nullable|string|max:20',
            'nama_ibu'       => 'nullable|string|max:100',
            'pekerjaan_ibu'  => 'nullable|string|max:100',
            'no_telp_ibu'    => 'nullable|string|max:20',
            'nama_wali'      => 'nullable|string|max:100',
            'pekerjaan_wali' => 'nullable|string|max:100',
            'no_telp_wali'   => 'nullable|string|max:20',
            'no_wa_presensi' => 'nullable|string|max:25',
            'foto'           => 'nullable',
        ]);

        $detail = DetailSiswa::firstOrCreate(['nis' => $nis]);
        
        $data = $request->only([
            'alamat', 'agama', 'golongan_darah',
            'nama_ayah', 'pekerjaan_ayah', 'no_telp_ayah',
            'nama_ibu', 'pekerjaan_ibu', 'no_telp_ibu',
            'nama_wali', 'pekerjaan_wali', 'no_telp_wali',
            'no_wa_presensi',
        ]);

        // Handle foto upload / deletion safely
        if ($request->input('delete_foto') == '1') {
            if ($detail->foto && \Illuminate\Support\Facades\Storage::disk('public')->exists($detail->foto)) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($detail->foto);
            }
            $data['foto'] = null;
        } elseif ($request->hasFile('foto') || $request->filled('foto')) {
            $newFoto = \App\Helpers\FileUploadHelper::storeFile($request, 'foto', 'siswa/foto');
            if ($newFoto) {
                if ($detail->foto && \Illuminate\Support\Facades\Storage::disk('public')->exists($detail->foto)) {
                    \Illuminate\Support\Facades\Storage::disk('public')->delete($detail->foto);
                }
                $data['foto'] = $newFoto;
            }
        }

        $detail->update($data);

        return redirect()->route('atur-data.siswa.show', $nis)->with('success', 'Detail siswa berhasil diperbarui.');
    }

    public function destroy($nis)
    {
        $siswa = UserSiswa::findOrFail($nis);

        // Delete all related data first to avoid integrity constraint violations
        $siswa->presensi()->delete();
        $siswa->logAbsensi()->delete();
        $siswa->btaq()->delete();
        $siswa->kesehatan()->delete();

        // Delete related riwayat_obat records
        $kunjunganIds = $siswa->kunjunganUks()->pluck('id_kunjungan');
        \Illuminate\Support\Facades\DB::table('riwayat_obat')->whereIn('id_kunjungan', $kunjunganIds)->delete();
        
        $siswa->kunjunganUks()->delete();
        $siswa->riwayatKesehatan()->delete();
        $siswa->riwayatPoin()->delete();
        $siswa->riwayatReward()->delete();
        $siswa->dataCheckup()->delete();
        $siswa->tagihan()->delete();

        // Finally, delete the student record
        $siswa->delete();

        return redirect()->route('atur-data.siswa')->with('success','Data siswa dan semua data terkait berhasil dihapus.');
    }

    public function bulkDestroy(Request $request)
    {
        $ids = $request->input('ids', []);
        if (empty($ids)) {
            return redirect()->route('atur-data.siswa')->with('error', 'Tidak ada data siswa yang terpilih.');
        }

        \Illuminate\Support\Facades\DB::beginTransaction();
        try {
            foreach ($ids as $nis) {
                $siswa = UserSiswa::find($nis);
                if ($siswa) {
                    $siswa->presensi()->delete();
                    $siswa->logAbsensi()->delete();
                    $siswa->btaq()->delete();
                    $siswa->kesehatan()->delete();

                    $kunjunganIds = $siswa->kunjunganUks()->pluck('id_kunjungan');
                    \Illuminate\Support\Facades\DB::table('riwayat_obat')->whereIn('id_kunjungan', $kunjunganIds)->delete();
                    
                    $siswa->kunjunganUks()->delete();
                    $siswa->riwayatKesehatan()->delete();
                    $siswa->riwayatPoin()->delete();
                    $siswa->riwayatReward()->delete();
                    $siswa->dataCheckup()->delete();
                    $siswa->tagihan()->delete();
                    $siswa->delete();
                }
            }
            \Illuminate\Support\Facades\DB::commit();
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\DB::rollBack();
            return redirect()->route('atur-data.siswa')->with('error', 'Terjadi kesalahan saat menghapus data siswa: ' . $e->getMessage());
        }

        return redirect()->route('atur-data.siswa')->with('success', 'Berhasil menghapus ' . count($ids) . ' data siswa terpilih.');
    }



    public function importPilihKelas()
    {
        $kelasList = Kelas::where('status', 'aktif')->with('jurusan')->orderBy('tingkat')->orderBy('rombel')->get();
        return view('atur-data.siswa.import-pilih-kelas', compact('kelasList'));
    }

    public function importForm(Request $request)
    {
        $id_kelas = $request->query('id_kelas');
        if (!$id_kelas) {
            return redirect()->route('atur-data.siswa.import-pilih-kelas')->with('error', 'Silakan pilih kelas terlebih dahulu.');
        }

        $kelas = Kelas::with('jurusan')->findOrFail($id_kelas);
        return view('atur-data.siswa.import', compact('kelas'));
    }

    public function importTemplate(Request $request)
    {
        $id_kelas = $request->query('id_kelas');
        $kelas = Kelas::findOrFail($id_kelas);

        $filename = "template_siswa_" . strtolower(str_replace(' ', '_', $kelas->nama_kelas)) . ".xlsx";

        // Write to a temp file first, then serve it
        $tmpFile = tempnam(sys_get_temp_dir(), 'siswa_template_') . '.xlsx';

        $writer = new \OpenSpout\Writer\XLSX\Writer();
        $writer->openToFile($tmpFile);

        // Header Row
        $writer->addRow(\OpenSpout\Common\Entity\Row::fromValues(['nis', 'nama_siswa', 'jenkel', 'tempat_lahir', 'tgl_lahir']));

        // Sample Rows
        $writer->addRow(\OpenSpout\Common\Entity\Row::fromValues(['12345678', 'Ahmad Dani', 'L', 'Surabaya', '2008-05-24']));
        $writer->addRow(\OpenSpout\Common\Entity\Row::fromValues(['12345679', 'Siti Aminah', 'P', 'Jakarta', '2008-09-12']));

        $writer->close();

        return response()->download($tmpFile, $filename, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        ])->deleteFileAfterSend(true);
    }

    public function importProcess(Request $request)
    {
        $request->validate([
            'id_kelas' => 'required|exists:kelas,id_kelas',
            'file' => 'required|file|max:2048',
        ]);

        ini_set('max_execution_time', 300);
        set_time_limit(300);

        $id_kelas = $request->input('id_kelas');
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

                    // Read Header — strip all non-alphanumeric chars (incl. underscores)
                    if ($lineNum === 1) {
                        $headerRow = array_map(function($h) {
                            return trim(strtolower(preg_replace('/[^a-zA-Z0-9]/', '', (string)$h)));
                        }, $row);

                        // Detect column indices after reading the header
                        $nisIndex = array_search('nis', $headerRow);
                        $namaIndex = array_search('namasiswa', $headerRow);
                        if ($namaIndex === false) {
                            $namaIndex = array_search('nama', $headerRow);
                        }
                        $jenkelIndex = array_search('jenkel', $headerRow);
                        $tempatLahirIndex = array_search('tempatlahir', $headerRow);
                        $tglLahirIndex = array_search('tgllahir', $headerRow);

                        if ($nisIndex === false || $namaIndex === false || $jenkelIndex === false) {
                            throw new \Exception('Header Excel harus berisi kolom: nis, nama_siswa, jenkel. Header yang terbaca: ' . implode(', ', $headerRow));
                        }
                        continue;
                    }

                    // Skip empty rows
                    if (empty($row) || count($row) < 3) continue;

                    $nis = isset($row[$nisIndex]) ? trim((string)$row[$nisIndex]) : '';
                    $nama = isset($row[$namaIndex]) ? trim((string)$row[$namaIndex]) : '';
                    $jenkel = isset($row[$jenkelIndex]) ? strtoupper(trim((string)$row[$jenkelIndex])) : '';
                    $tempat_lahir = $tempatLahirIndex !== false && isset($row[$tempatLahirIndex]) ? trim((string)$row[$tempatLahirIndex]) : null;
                    
                    // Handle dates from Excel cell or string
                    $tgl_lahir = null;
                    if ($tglLahirIndex !== false && isset($row[$tglLahirIndex])) {
                        $rawVal = $row[$tglLahirIndex];
                        if ($rawVal instanceof \DateTimeInterface) {
                            $tgl_lahir = $rawVal->format('Y-m-d');
                        } elseif (!empty(trim((string)$rawVal))) {
                            $tgl_lahir = trim((string)$rawVal);
                        }
                    }

                    if (empty($nis) || empty($nama) || empty($jenkel)) {
                        $skipped[] = "Baris $lineNum: Kolom NIS/Nama/Jenkel tidak boleh kosong.";
                        continue;
                    }

                    if (!is_numeric($nis)) {
                        $skipped[] = "Baris $lineNum: NIS '$nis' harus berupa angka.";
                        continue;
                    }

                    if ($jenkel !== 'L' && $jenkel !== 'P') {
                        $skipped[] = "Baris $lineNum: Jenis Kelamin '$jenkel' harus L atau P.";
                        continue;
                    }

                    // Check for duplicate NIS
                    $exists = UserSiswa::where('nis', $nis)->exists();
                    if ($exists) {
                        $skipped[] = "Baris $lineNum: NIS '$nis' sudah terdaftar.";
                        continue;
                    }

                    // Insert student
                    $passwordHashed = sha1($nis); // Default password: NIS
                    UserSiswa::create([
                        'nis' => $nis,
                        'password' => $passwordHashed,
                        'password_wali' => $passwordHashed,
                        'id_kelas' => $id_kelas,
                        'nama_siswa' => $nama,
                        'jenkel' => $jenkel,
                        'tempat_lahir' => $tempat_lahir,
                        'tgl_lahir' => $tgl_lahir,
                        'kelengkapan' => 0,
                        'status' => 'aktif',
                    ]);

                    $imported++;
                }
                break; // Only process the first sheet
            }
            
            $reader->close();
            \Illuminate\Support\Facades\DB::commit();

        } catch (\Exception $e) {
            $reader->close();
            \Illuminate\Support\Facades\DB::rollBack();
            return redirect()->back()->with('error', 'Terjadi kesalahan sistem saat memproses file Excel: ' . $e->getMessage());
        }

        $successMsg = "Berhasil mengimpor $imported data siswa.";
        if (!empty($skipped)) {
            $skippedMsg = implode(' | ', array_slice($skipped, 0, 5));
            if (count($skipped) > 5) {
                $skippedMsg .= " (dan " . (count($skipped) - 5) . " baris lainnya)";
            }
            return redirect()->route('atur-data.siswa', ['id_kelas' => $id_kelas])
                ->with('success', $successMsg)
                ->with('warning', "Beberapa data dilewati: " . $skippedMsg);
        }

        return redirect()->route('atur-data.siswa', ['id_kelas' => $id_kelas])->with('success', $successMsg);
    }

    public function toggleEditAkses()
    {
        $sekolah = \App\Models\Sekolah::first();
        if (!$sekolah) {
            return redirect()->route('atur-data.siswa')->with('error', 'Data sekolah belum dikonfigurasi.');
        }

        $newValue = !$sekolah->edit_detail_siswa;
        $sekolah->update(['edit_detail_siswa' => $newValue]);

        $msg = $newValue
            ? 'Fitur edit profil siswa di aplikasi mobile telah DIAKTIFKAN.'
            : 'Fitur edit profil siswa di aplikasi mobile telah DINONAKTIFKAN.';

        return redirect()->route('atur-data.siswa')->with('success', $msg);
    }
}

