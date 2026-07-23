<?php
 
namespace App\Http\Controllers\Api;
 
use App\Http\Controllers\Controller;
use App\Models\HomeVisit;
use Illuminate\Http\Request;
 
class HomeVisitController extends Controller
{
    public function index(Request $request)
    {
        $query = HomeVisit::with('siswa.kelas');
 
        if ($request->filled('nis')) {
            $query->where('nis', $request->nis);
        }
 
        if ($request->filled('status')) {
            $query->where('status', strtolower($request->status));
        }
 
        $perPage = $request->get('per_page', 15);
        $data = $query->orderByDesc('tanggal_visit')->paginate($perPage);
 
        $data->getCollection()->transform(function ($item) {
            return [
                'id'            => $item->id_home_visit,
                'nis'           => $item->nis,
                'nama_siswa'    => $item->siswa->nama_siswa ?? '',
                'nama_kelas'    => $item->siswa->kelas->nama_kelas ?? '',
                'tanggal'       => $item->tanggal_visit ? ($item->tanggal_visit instanceof \Carbon\Carbon ? $item->tanggal_visit->format('Y-m-d') : substr($item->tanggal_visit, 0, 10)) : '',
                'tujuan'        => $item->tujuan_kunjungan,
                'hasil'         => $item->hasil_kunjungan ?? '',
                'alamat'        => $item->alamat ?? '',
                'tindak_lanjut' => $item->tindak_lanjut ?? '',
                'status'        => $item->status,
                'foto_bukti'    => $item->foto_bukti ? asset('storage/' . $item->foto_bukti) : null,
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
            'nis'              => 'required|integer|exists:user_siswa,nis',
            'tanggal'          => 'nullable|date',
            'tanggal_visit'    => 'nullable|date',
            'tujuan'           => 'nullable|string',
            'tujuan_kunjungan' => 'nullable|string',
            'hasil'            => 'nullable|string',
            'hasil_kunjungan'  => 'nullable|string',
            'alamat'           => 'nullable|string|max:255',
            'tindak_lanjut'    => 'nullable|string',
            'keterangan'       => 'nullable|string',
            'status'           => 'nullable|string',
            'foto_bukti'       => 'nullable|image|max:2048',
        ]);
 
        $tanggalVisit = $request->input('tanggal_visit') ?? $request->input('tanggal') ?? date('Y-m-d');
        $tujuan = $request->input('tujuan_kunjungan') ?? $request->input('tujuan') ?? 'Kunjungan Rumah';
        $hasil = $request->input('hasil_kunjungan') ?? $request->input('hasil') ?? '';
        $tindakLanjut = $request->input('tindak_lanjut') ?? $request->input('keterangan') ?? '';
        $status = strtolower($request->input('status') ?? 'dijadwalkan');
        if (!in_array($status, ['dijadwalkan', 'selesai', 'batal'])) {
            $status = 'dijadwalkan';
        }
 
        $path = null;
        if ($request->hasFile('foto_bukti')) {
            $path = $request->file('foto_bukti')->store('home-visit', 'public');
        }
 
        $user = auth()->user();
 
        $visit = HomeVisit::create([
            'tanggal_visit'    => $tanggalVisit,
            'nis'              => $request->nis,
            'alamat'           => $request->alamat,
            'tujuan_kunjungan' => $tujuan,
            'hasil_kunjungan'  => $hasil,
            'tindak_lanjut'    => $tindakLanjut,
            'status'           => $status,
            'id_guru'          => $user->id_guru ?? 1,
            'foto_bukti'       => $path,
        ]);
 
        return response()->json([
            'success' => true,
            'message' => 'Home visit berhasil dicatat.',
            'data' => [
                'id'            => $visit->id_home_visit,
                'nis'           => $visit->nis,
                'nama_siswa'    => $visit->siswa->nama_siswa ?? '',
                'nama_kelas'    => $visit->siswa->kelas->nama_kelas ?? '',
                'tanggal'       => $visit->tanggal_visit ? ($visit->tanggal_visit instanceof \Carbon\Carbon ? $visit->tanggal_visit->format('Y-m-d') : substr($visit->tanggal_visit, 0, 10)) : '',
                'tujuan'        => $visit->tujuan_kunjungan,
                'hasil'         => $visit->hasil_kunjungan ?? '',
                'alamat'        => $visit->alamat ?? '',
                'tindak_lanjut' => $visit->tindak_lanjut ?? '',
                'status'        => $visit->status,
                'foto_bukti'    => $visit->foto_bukti ? asset('storage/' . $visit->foto_bukti) : null,
            ],
        ], 201);
    }
 
    public function show($id)
    {
        $item = HomeVisit::with('siswa.kelas')->findOrFail($id);
 
        return response()->json([
            'success' => true,
            'data' => [
                'id'            => $item->id_home_visit,
                'nis'           => $item->nis,
                'nama_siswa'    => $item->siswa->nama_siswa ?? '',
                'nama_kelas'    => $item->siswa->kelas->nama_kelas ?? '',
                'tanggal'       => $item->tanggal_visit ? ($item->tanggal_visit instanceof \Carbon\Carbon ? $item->tanggal_visit->format('Y-m-d') : substr($item->tanggal_visit, 0, 10)) : '',
                'tujuan'        => $item->tujuan_kunjungan,
                'hasil'         => $item->hasil_kunjungan ?? '',
                'alamat'        => $item->alamat ?? '',
                'tindak_lanjut' => $item->tindak_lanjut ?? '',
                'status'        => $item->status,
                'foto_bukti'    => $item->foto_bukti ? asset('storage/' . $item->foto_bukti) : null,
            ],
        ]);
    }
 
    public function update(Request $request, $id)
    {
        $visit = HomeVisit::findOrFail($id);
 
        $request->validate([
            'tanggal'          => 'nullable|date',
            'tanggal_visit'    => 'nullable|date',
            'tujuan'           => 'nullable|string',
            'tujuan_kunjungan' => 'nullable|string',
            'hasil'            => 'nullable|string',
            'hasil_kunjungan'  => 'nullable|string',
            'alamat'           => 'nullable|string|max:255',
            'tindak_lanjut'    => 'nullable|string',
            'keterangan'       => 'nullable|string',
            'status'           => 'nullable|string',
            'foto_bukti'       => 'nullable|image|max:2048',
        ]);
 
        $dataUpdate = [];
        if ($request->has('tanggal') || $request->has('tanggal_visit')) {
            $dataUpdate['tanggal_visit'] = $request->input('tanggal_visit') ?? $request->input('tanggal');
        }
        if ($request->has('nis')) {
            $dataUpdate['nis'] = $request->nis;
        }
        if ($request->has('alamat')) {
            $dataUpdate['alamat'] = $request->alamat;
        }
        if ($request->has('tujuan') || $request->has('tujuan_kunjungan')) {
            $dataUpdate['tujuan_kunjungan'] = $request->input('tujuan_kunjungan') ?? $request->input('tujuan');
        }
        if ($request->has('hasil') || $request->has('hasil_kunjungan')) {
            $dataUpdate['hasil_kunjungan'] = $request->input('hasil_kunjungan') ?? $request->input('hasil');
        }
        if ($request->has('tindak_lanjut') || $request->has('keterangan')) {
            $dataUpdate['tindak_lanjut'] = $request->input('tindak_lanjut') ?? $request->input('keterangan');
        }
        if ($request->has('status')) {
            $status = strtolower($request->status);
            if (in_array($status, ['dijadwalkan', 'selesai', 'batal'])) {
                $dataUpdate['status'] = $status;
            }
        }
 
        if ($request->hasFile('foto_bukti')) {
            if ($visit->foto_bukti) {
                \Storage::disk('public')->delete($visit->foto_bukti);
            }
            $dataUpdate['foto_bukti'] = $request->file('foto_bukti')->store('home-visit', 'public');
        }
 
        $visit->update($dataUpdate);
 
        return response()->json([
            'success' => true,
            'message' => 'Home visit berhasil diperbarui.',
            'data' => [
                'id'            => $visit->id_home_visit,
                'nis'           => $visit->nis,
                'nama_siswa'    => $visit->siswa->nama_siswa ?? '',
                'nama_kelas'    => $visit->siswa->kelas->nama_kelas ?? '',
                'tanggal'       => $visit->tanggal_visit ? ($visit->tanggal_visit instanceof \Carbon\Carbon ? $visit->tanggal_visit->format('Y-m-d') : substr($visit->tanggal_visit, 0, 10)) : '',
                'tujuan'        => $visit->tujuan_kunjungan,
                'hasil'         => $visit->hasil_kunjungan ?? '',
                'alamat'        => $visit->alamat ?? '',
                'tindak_lanjut' => $visit->tindak_lanjut ?? '',
                'status'        => $visit->status,
                'foto_bukti'    => $visit->foto_bukti ? asset('storage/' . $visit->foto_bukti) : null,
            ],
        ]);
    }
 
    public function destroy($id)
    {
        HomeVisit::findOrFail($id)->delete();
 
        return response()->json([
            'success' => true,
            'message' => 'Data home visit berhasil dihapus.',
        ]);
    }
}
