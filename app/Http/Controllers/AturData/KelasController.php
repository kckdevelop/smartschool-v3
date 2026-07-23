<?php

namespace App\Http\Controllers\AturData;

use App\Http\Controllers\Controller;
use App\Models\Kelas;
use App\Models\Jurusan;
use App\Models\Guru;
use Illuminate\Http\Request;

class KelasController extends Controller
{
    public function index(Request $request)
    {
        $query = Kelas::with(['jurusan','guru'])->where('status', 'aktif');
        if ($request->filled('id_jurusan')) $query->where('id_jurusan',$request->id_jurusan);
        if ($request->filled('tingkat'))    $query->where('tingkat',$request->tingkat);
        if ($request->filled('search'))     $query->where('rombel','like','%'.$request->search.'%');
        $perPage    = (int) $request->input('per_page', 20);
        $perPage    = in_array($perPage, [10, 20, 50, 100]) ? $perPage : 20;
        $kelasList  = $query->orderBy('tingkat')->orderBy('rombel')->paginate($perPage)->withQueryString();
        $jurusanList = Jurusan::orderBy('nama_jurusan')->get();
        $guruList    = Guru::where('status','aktif')->orderBy('nama_guru')->get();
        return view('atur-data.kelas.index', compact('kelasList','jurusanList','guruList'))->with('isInactive', false);
    }

    /**
     * Display kelas that are not active (status = 'tidak').
     */
    public function indexTidakAktif(Request $request)
    {
        $query = Kelas::with(['jurusan','guru'])->where('status', 'tidak');
        if ($request->filled('id_jurusan')) $query->where('id_jurusan',$request->id_jurusan);
        if ($request->filled('tingkat'))    $query->where('tingkat',$request->tingkat);
        if ($request->filled('search'))     $query->where('rombel','like','%'.$request->search.'%');
        $perPage    = (int) $request->input('per_page', 20);
        $perPage    = in_array($perPage, [10, 20, 50, 100]) ? $perPage : 20;
        $kelasList  = $query->orderBy('tingkat')->orderBy('rombel')->paginate($perPage)->withQueryString();
        $jurusanList = Jurusan::orderBy('nama_jurusan')->get();
        $guruList    = Guru::where('status','aktif')->orderBy('nama_guru')->get();
        return view('atur-data.kelas.index', compact('kelasList','jurusanList','guruList'))->with('isInactive', true);
    }

    public function store(Request $request)
    {
        $request->validate([
            'tahun_masuk' => 'required|string|max:10',
            'tingkat'     => 'required|integer|min:10',
            'id_jurusan'  => 'required|integer|exists:jurusan,id_jurusan',
            'rombel'      => 'required|string|max:50',
            'walikelas'   => 'nullable|integer|exists:guru,id_guru',
            'status'      => 'required|in:aktif,tidak',
        ]);
        Kelas::create($request->only('tahun_masuk','tingkat','id_jurusan','rombel','walikelas','status'));
        return redirect()->route('atur-data.kelas')->with('success','Kelas berhasil ditambahkan.');
    }

    public function update(Request $request, $id)
    {
        $kelas = Kelas::findOrFail($id);
        $request->validate([
            'tahun_masuk' => 'required|string|max:10',
            'tingkat'     => 'required|integer|min:10',
            'id_jurusan'  => 'required|integer|exists:jurusan,id_jurusan',
            'rombel'      => 'required|string|max:50',
            'walikelas'   => 'nullable|integer|exists:guru,id_guru',
            'status'      => 'required|in:aktif,tidak',
        ]);
        $kelas->update($request->only('tahun_masuk','tingkat','id_jurusan','rombel','walikelas','status'));
        return redirect()->route('atur-data.kelas')->with('success','Kelas berhasil diperbarui.');
    }

    public function naikTingkat()
    {
        $classes = Kelas::where('status', 'aktif')->get();
        $promotedClassesCount = 0;
        $graduatedClassesCount = 0;
        $graduatedStudentsCount = 0;

        \Illuminate\Support\Facades\DB::beginTransaction();
        try {
            foreach ($classes as $kelas) {
                $newTingkat = $kelas->tingkat + 1;
                if ($newTingkat > 12) {
                    // Change status of all active students using this id_kelas to 'tidak'
                    $updatedStudents = \App\Models\UserSiswa::where('id_kelas', $kelas->id_kelas)
                        ->where('status', 'aktif')
                        ->update(['status' => 'tidak']);
                    
                    $graduatedStudentsCount += $updatedStudents;

                    // Set class tingkat to newTingkat and status to 'tidak'
                    $kelas->update([
                        'tingkat' => $newTingkat,
                        'status'  => 'tidak',
                    ]);
                    $graduatedClassesCount++;
                } else {
                    // Update class tingkat
                    $kelas->update([
                        'tingkat' => $newTingkat,
                    ]);
                    $promotedClassesCount++;
                }
            }
            \Illuminate\Support\Facades\DB::commit();
            return redirect()->route('atur-data.kelas')->with('success', "Proses naik tingkat selesai. $promotedClassesCount kelas berhasil naik tingkat, $graduatedClassesCount kelas dinonaktifkan (tingkat > 12), dan $graduatedStudentsCount siswa berhasil diubah statusnya menjadi Tidak Aktif.");
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\DB::rollBack();
            return redirect()->route('atur-data.kelas')->with('error', "Terjadi kesalahan saat memproses naik tingkat: " . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        $kelas = Kelas::findOrFail($id);

        \Illuminate\Support\Facades\DB::beginTransaction();
        try {
            $this->deleteRelatedData($kelas);
            $kelas->delete();
            \Illuminate\Support\Facades\DB::commit();
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\DB::rollBack();
            return redirect()->route('atur-data.kelas')->with('error', 'Terjadi kesalahan saat menghapus kelas: ' . $e->getMessage());
        }

        return redirect()->route('atur-data.kelas')->with('success','Kelas dan seluruh data terkait berhasil dihapus.');
    }

    public function bulkDestroy(Request $request)
    {
        $ids = $request->input('ids', []);
        if (empty($ids)) {
            return redirect()->route('atur-data.kelas')->with('error', 'Tidak ada data kelas yang terpilih.');
        }

        \Illuminate\Support\Facades\DB::beginTransaction();
        try {
            foreach ($ids as $id) {
                $kelas = Kelas::find($id);
                if ($kelas) {
                    $this->deleteRelatedData($kelas);
                    $kelas->delete();
                }
            }
            \Illuminate\Support\Facades\DB::commit();
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\DB::rollBack();
            return redirect()->route('atur-data.kelas')->with('error', 'Terjadi kesalahan saat menghapus data kelas: ' . $e->getMessage());
        }

        return redirect()->route('atur-data.kelas')->with('success', 'Berhasil menghapus ' . count($ids) . ' data kelas terpilih beserta seluruh data terkait.');
    }

    private function deleteRelatedData($kelas)
    {
        // 1. Delete all students and their child relations (presensi, uks, points, etc.)
        $siswaList = $kelas->siswa;
        foreach ($siswaList as $siswa) {
            $siswa->presensi()->delete();
            $siswa->logAbsensi()->delete();
            $siswa->btaq()->delete();
            $siswa->kesehatan()->delete();

            $kunjunganIds = $siswa->kunjunganUks()->pluck('id_kunjungan');
            \Illuminate\Support\Facades\DB::table('riwayat_obat')->whereIn('id_kunjungan', $kunjunganIds)->delete();
            
            $siswa->kunjunganUks()->delete();
            $siswa->riwayatPoin()->delete();
            $siswa->riwayatReward()->delete();
            $siswa->dataCheckup()->delete();
            $siswa->tagihan()->delete();
            $siswa->delete();
        }

        // 2. Delete Tugas and TagihanTugas
        foreach ($kelas->tugas as $tugas) {
            $tugas->tagihan()->delete();
            $tugas->delete();
        }

        // 3. Delete other direct class relations
        $kelas->kemajuan()->delete();
        $kelas->btaq()->delete();
        $kelas->tadarus()->delete();

        // 4. Delete DB rows that reference this class
        \App\Models\RiwayatGenerateSoal::where('id_kelas', $kelas->id_kelas)->delete();
        \App\Models\RiwayatGenerateKisiKisi::where('id_kelas', $kelas->id_kelas)->delete();
        \App\Models\PklKelasGelombang::where('id_kelas', $kelas->id_kelas)->delete();
        \App\Models\PantauIbadah::where('id_kelas', $kelas->id_kelas)->delete();
        \App\Models\JadwalMengajarTemplate::where('id_kelas', $kelas->id_kelas)->delete();
        \App\Models\JadwalMengajarHarian::where('id_kelas', $kelas->id_kelas)->delete();
    }



    /**
     * Return all active kelas as JSON for AJAX usage.
     */
    public function indexJson()
    {
        $kelas = Kelas::where('status', 'aktif')
            ->orderBy('tingkat')
            ->orderBy('rombel')
            ->get(['id_kelas', 'tingkat', 'rombel'])
            ->map(fn($k) => [
                'id_kelas'  => $k->id_kelas,
                'nama_kelas'=> $k->tingkat . ' ' . $k->rombel,
            ]);

        return response()->json($kelas);
    }
}
