<?php
 
namespace App\Http\Controllers\Api;
 
use App\Http\Controllers\Controller;
use App\Models\BukuKasus;
use Illuminate\Http\Request;
 
class BukuKasusController extends Controller
{
    public function index(Request $request)
    {
        $query = BukuKasus::with('siswa.kelas');
 
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
                'id'            => $item->id_kasus,
                'nis'           => $item->nis,
                'nama_siswa'    => $item->siswa->nama_siswa ?? '',
                'nama_kelas'    => $item->siswa->kelas->nama_kelas ?? '',
                'tanggal'       => $item->tanggal ? ($item->tanggal instanceof \Carbon\Carbon ? $item->tanggal->format('Y-m-d') : substr($item->tanggal, 0, 10)) : '',
                'nama_kasus'    => $item->judul_kasus,
                'uraian_kasus'  => $item->uraian_kasus,
                'tindak_lanjut' => $item->tindak_lanjut ?? '',
                'status'        => $item->status,
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
            'nis'          => 'required|integer|exists:user_siswa,nis',
            'tanggal'      => 'required|date',
            'nama_kasus'   => 'nullable|string|max:150',
            'kasus'        => 'nullable|string|max:150',
            'uraian_kasus' => 'nullable|string',
            'keterangan'   => 'nullable|string',
            'tindak_lanjut'=> 'nullable|string',
            'tindakan'     => 'nullable|string',
            'status'       => 'nullable|string',
        ]);
 
        $judul = $request->input('nama_kasus') ?? $request->input('kasus') ?? 'Kasus BK';
        $uraian = $request->input('uraian_kasus') ?? $request->input('keterangan') ?? 'Catatan Kasus';
        $tindakLanjut = $request->input('tindak_lanjut') ?? $request->input('tindakan');
        $status = strtolower($request->input('status') ?? 'proses');
        if (!in_array($status, ['proses', 'selesai'])) {
            $status = 'proses';
        }
 
        $user = auth()->user();
 
        $kasus = BukuKasus::create([
            'tanggal'       => $request->tanggal,
            'nis'           => $request->nis,
            'judul_kasus'   => $judul,
            'uraian_kasus'  => $uraian,
            'tindak_lanjut' => $tindakLanjut,
            'status'        => $status,
            'id_guru'       => $user->id_guru ?? 1,
        ]);
 
        return response()->json([
            'success' => true,
            'message' => 'Buku kasus berhasil dicatat.',
            'data' => [
                'id'            => $kasus->id_kasus,
                'nis'           => $kasus->nis,
                'nama_siswa'    => $kasus->siswa->nama_siswa ?? '',
                'nama_kelas'    => $kasus->siswa->kelas->nama_kelas ?? '',
                'tanggal'       => $kasus->tanggal ? ($kasus->tanggal instanceof \Carbon\Carbon ? $kasus->tanggal->format('Y-m-d') : substr($kasus->tanggal, 0, 10)) : '',
                'nama_kasus'    => $kasus->judul_kasus,
                'uraian_kasus'  => $kasus->uraian_kasus,
                'tindak_lanjut' => $kasus->tindak_lanjut ?? '',
                'status'        => $kasus->status,
            ],
        ], 201);
    }
 
    public function show($id)
    {
        $item = BukuKasus::with('siswa.kelas')->findOrFail($id);
 
        return response()->json([
            'success' => true,
            'data' => [
                'id'            => $item->id_kasus,
                'nis'           => $item->nis,
                'nama_siswa'    => $item->siswa->nama_siswa ?? '',
                'nama_kelas'    => $item->siswa->kelas->nama_kelas ?? '',
                'tanggal'       => $item->tanggal ? ($item->tanggal instanceof \Carbon\Carbon ? $item->tanggal->format('Y-m-d') : substr($item->tanggal, 0, 10)) : '',
                'nama_kasus'    => $item->judul_kasus,
                'uraian_kasus'  => $item->uraian_kasus,
                'tindak_lanjut' => $item->tindak_lanjut ?? '',
                'status'        => $item->status,
            ],
        ]);
    }
 
    public function update(Request $request, $id)
    {
        $kasus = BukuKasus::findOrFail($id);
 
        $request->validate([
            'nama_kasus'   => 'nullable|string|max:150',
            'kasus'        => 'nullable|string|max:150',
            'uraian_kasus' => 'nullable|string',
            'keterangan'   => 'nullable|string',
            'tindak_lanjut'=> 'nullable|string',
            'tindakan'     => 'nullable|string',
            'status'       => 'nullable|string',
        ]);
 
        $dataUpdate = [];
        if ($request->has('tanggal')) {
            $dataUpdate['tanggal'] = $request->tanggal;
        }
        if ($request->has('nis')) {
            $dataUpdate['nis'] = $request->nis;
        }
 
        if ($request->has('nama_kasus') || $request->has('kasus')) {
            $dataUpdate['judul_kasus'] = $request->input('nama_kasus') ?? $request->input('kasus');
        }
        if ($request->has('uraian_kasus') || $request->has('keterangan')) {
            $dataUpdate['uraian_kasus'] = $request->input('uraian_kasus') ?? $request->input('keterangan');
        }
        if ($request->has('tindak_lanjut') || $request->has('tindakan')) {
            $dataUpdate['tindak_lanjut'] = $request->input('tindak_lanjut') ?? $request->input('tindakan');
        }
        if ($request->has('status')) {
            $status = strtolower($request->status);
            if (in_array($status, ['proses', 'selesai'])) {
                $dataUpdate['status'] = $status;
            }
        }
 
        $kasus->update($dataUpdate);
 
        return response()->json([
            'success' => true,
            'message' => 'Buku kasus berhasil diperbarui.',
            'data' => [
                'id'            => $kasus->id_kasus,
                'nis'           => $kasus->nis,
                'nama_siswa'    => $kasus->siswa->nama_siswa ?? '',
                'nama_kelas'    => $kasus->siswa->kelas->nama_kelas ?? '',
                'tanggal'       => $kasus->tanggal ? ($kasus->tanggal instanceof \Carbon\Carbon ? $kasus->tanggal->format('Y-m-d') : substr($kasus->tanggal, 0, 10)) : '',
                'nama_kasus'    => $kasus->judul_kasus,
                'uraian_kasus'  => $kasus->uraian_kasus,
                'tindak_lanjut' => $kasus->tindak_lanjut ?? '',
                'status'        => $kasus->status,
            ],
        ]);
    }
 
    public function destroy($id)
    {
        BukuKasus::findOrFail($id)->delete();
 
        return response()->json([
            'success' => true,
            'message' => 'Data buku kasus berhasil dihapus.',
        ]);
    }
}
