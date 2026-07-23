<?php
 
namespace App\Http\Controllers\Api;
 
use App\Http\Controllers\Controller;
use App\Models\BimbinganKonseling;
use Illuminate\Http\Request;
 
class BukuKonsulasiController extends Controller
{
    public function index(Request $request)
    {
        $query = BimbinganKonseling::with('siswa.kelas');
 
        if ($request->filled('nis')) {
            $query->where('nis', $request->nis);
        }
 
        if ($request->filled('status')) {
            $query->where('status', strtolower($request->status));
        }
 
        $perPage = $request->get('per_page', 15);
        $data = $query->orderByDesc('tanggal')->paginate($perPage);
 
        $data->getCollection()->transform(function ($item) {
            return [
                'id'                => $item->id_bk,
                'nis'               => $item->nis,
                'nama_siswa'        => $item->siswa->nama_siswa ?? '',
                'nama_kelas'        => $item->siswa->kelas->nama_kelas ?? '',
                'tanggal'           => $item->tanggal ? ($item->tanggal instanceof \Carbon\Carbon ? $item->tanggal->format('Y-m-d') : substr($item->tanggal, 0, 10)) : '',
                'materi_konsultasi' => $item->jenis_masalah,
                'uraian'            => $item->uraian,
                'tindak_lanjut'     => $item->tindak_lanjut ?? '',
                'status'            => $item->status,
            ];
        });
 
        return response()->json([
            'success' => true,
            'data' => $data,
        ]);
    }
 
    public function store(Request $request)
    {
        $request->validate([
            'nis'               => 'required|integer|exists:user_siswa,nis',
            'tanggal'           => 'required|date',
            'materi_konsultasi' => 'nullable|string|max:100',
            'permasalahan'      => 'nullable|string|max:100',
            'jenis_masalah'     => 'nullable|string|max:100',
            'uraian'            => 'nullable|string',
            'solusi'            => 'nullable|string',
            'tindak_lanjut'     => 'nullable|string',
            'keterangan'        => 'nullable|string',
            'status'            => 'nullable|string',
        ]);
 
        $jenisMasalah = $request->input('materi_konsultasi') ?? $request->input('permasalahan') ?? $request->input('jenis_masalah') ?? 'Konsultasi BK';
        $uraian = $request->input('uraian') ?? $request->input('solusi') ?? $jenisMasalah;
        $tindakLanjut = $request->input('tindak_lanjut') ?? $request->input('keterangan');
        $status = strtolower($request->input('status') ?? 'proses');
        if (!in_array($status, ['proses', 'selesai'])) {
            $status = 'proses';
        }
 
        $user = auth()->user();
 
        $konsul = BimbinganKonseling::create([
            'tanggal'       => $request->tanggal,
            'nis'           => $request->nis,
            'jenis_masalah' => $jenisMasalah,
            'uraian'        => $uraian,
            'tindak_lanjut' => $tindakLanjut,
            'status'        => $status,
            'id_guru'       => $user->id_guru ?? 1,
        ]);
 
        return response()->json([
            'success' => true,
            'message' => 'Bimbingan konseling berhasil dicatat.',
            'data' => [
                'id'                => $konsul->id_bk,
                'nis'               => $konsul->nis,
                'nama_siswa'        => $konsul->siswa->nama_siswa ?? '',
                'nama_kelas'        => $konsul->siswa->kelas->nama_kelas ?? '',
                'tanggal'           => $konsul->tanggal ? ($konsul->tanggal instanceof \Carbon\Carbon ? $konsul->tanggal->format('Y-m-d') : substr($konsul->tanggal, 0, 10)) : '',
                'materi_konsultasi' => $konsul->jenis_masalah,
                'uraian'            => $konsul->uraian,
                'tindak_lanjut'     => $konsul->tindak_lanjut ?? '',
                'status'            => $konsul->status,
            ],
        ], 201);
    }
 
    public function show($id)
    {
        $item = BimbinganKonseling::with('siswa.kelas')->findOrFail($id);
 
        return response()->json([
            'success' => true,
            'data' => [
                'id'                => $item->id_bk,
                'nis'               => $item->nis,
                'nama_siswa'        => $item->siswa->nama_siswa ?? '',
                'nama_kelas'        => $item->siswa->kelas->nama_kelas ?? '',
                'tanggal'           => $item->tanggal ? ($item->tanggal instanceof \Carbon\Carbon ? $item->tanggal->format('Y-m-d') : substr($item->tanggal, 0, 10)) : '',
                'materi_konsultasi' => $item->jenis_masalah,
                'uraian'            => $item->uraian,
                'tindak_lanjut'     => $item->tindak_lanjut ?? '',
                'status'            => $item->status,
            ],
        ]);
    }
 
    public function update(Request $request, $id)
    {
        $konsul = BimbinganKonseling::findOrFail($id);
 
        $request->validate([
            'materi_konsultasi' => 'nullable|string|max:100',
            'permasalahan'      => 'nullable|string|max:100',
            'jenis_masalah'     => 'nullable|string|max:100',
            'uraian'            => 'nullable|string',
            'solusi'            => 'nullable|string',
            'tindak_lanjut'     => 'nullable|string',
            'keterangan'        => 'nullable|string',
            'status'            => 'nullable|string',
        ]);
 
        $dataUpdate = [];
        if ($request->has('tanggal')) {
            $dataUpdate['tanggal'] = $request->tanggal;
        }
        if ($request->has('nis')) {
            $dataUpdate['nis'] = $request->nis;
        }
 
        if ($request->has('materi_konsultasi') || $request->has('permasalahan') || $request->has('jenis_masalah')) {
            $dataUpdate['jenis_masalah'] = $request->input('materi_konsultasi') ?? $request->input('permasalahan') ?? $request->input('jenis_masalah');
        }
        if ($request->has('uraian') || $request->has('solusi')) {
            $dataUpdate['uraian'] = $request->input('uraian') ?? $request->input('solusi');
        }
        if ($request->has('tindak_lanjut') || $request->has('keterangan')) {
            $dataUpdate['tindak_lanjut'] = $request->input('tindak_lanjut') ?? $request->input('keterangan');
        }
        if ($request->has('status')) {
            $status = strtolower($request->status);
            if (in_array($status, ['proses', 'selesai'])) {
                $dataUpdate['status'] = $status;
            }
        }
 
        $konsul->update($dataUpdate);
 
        return response()->json([
            'success' => true,
            'message' => 'Bimbingan konseling berhasil diperbarui.',
            'data' => [
                'id'                => $konsul->id_bk,
                'nis'               => $konsul->nis,
                'nama_siswa'        => $konsul->siswa->nama_siswa ?? '',
                'nama_kelas'        => $konsul->siswa->kelas->nama_kelas ?? '',
                'tanggal'           => $konsul->tanggal ? ($konsul->tanggal instanceof \Carbon\Carbon ? $konsul->tanggal->format('Y-m-d') : substr($konsul->tanggal, 0, 10)) : '',
                'materi_konsultasi' => $konsul->jenis_masalah,
                'uraian'            => $konsul->uraian,
                'tindak_lanjut'     => $konsul->tindak_lanjut ?? '',
                'status'            => $konsul->status,
            ],
        ]);
    }
 
    public function destroy($id)
    {
        BimbinganKonseling::findOrFail($id)->delete();
 
        return response()->json([
            'success' => true,
            'message' => 'Bimbingan konseling berhasil dihapus.',
        ]);
    }
}
