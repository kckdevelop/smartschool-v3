<?php

namespace App\Http\Controllers\Bk;

use App\Http\Controllers\Controller;
use App\Models\HomeVisit;
use App\Models\UserSiswa;
use App\Models\Kelas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class HomeVisitController extends Controller
{
    public function index(Request $request)
    {
        $kelas = Kelas::orderBy('tingkat')->orderBy('rombel')->get();
        $query = HomeVisit::with(['siswa.kelas', 'guru'])->orderByDesc('tanggal_visit');

        if ($request->filled('id_kelas')) {
            $query->whereHas('siswa', function($q) use ($request) {
                $q->where('id_kelas', $request->id_kelas);
            });
        }

        $data = $query->paginate(15)->withQueryString();
        return view('bk.home-visit.index', compact('data', 'kelas'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'tanggal_visit'     => 'required|date',
            'nis'               => 'required|string|max:20',
            'tujuan_kunjungan'  => 'required|string',
            'foto_bukti'        => 'nullable|image|max:2048',
        ]);

        $guru = Auth::user();

        $path = null;
        if ($request->hasFile('foto_bukti')) {
            $path = $request->file('foto_bukti')->store('home-visit', 'public');
        }

        HomeVisit::create([
            'tanggal_visit'    => $request->tanggal_visit,
            'nis'              => $request->nis,
            'alamat'           => $request->alamat,
            'tujuan_kunjungan' => $request->tujuan_kunjungan,
            'hasil_kunjungan'  => $request->hasil_kunjungan,
            'tindak_lanjut'    => $request->tindak_lanjut,
            'status'           => $request->status ?? 'dijadwalkan',
            'id_guru'          => $guru->id_guru ?? 1,
            'foto_bukti'       => $path,
        ]);

        return redirect()->route('bk.home-visit.index')
            ->with('success', 'Data home visit berhasil dicatat.');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'tanggal_visit'    => 'required|date',
            'nis'              => 'required|string|max:20',
            'tujuan_kunjungan' => 'required|string',
            'status'           => 'required|in:dijadwalkan,selesai,batal',
            'foto_bukti'        => 'nullable|image|max:2048',
        ]);

        $visit = HomeVisit::findOrFail($id);

        $data = $request->only([
            'tanggal_visit', 'nis', 'alamat', 'tujuan_kunjungan',
            'hasil_kunjungan', 'tindak_lanjut', 'status'
        ]);

        if ($request->hasFile('foto_bukti')) {
            if ($visit->foto_bukti) {
                Storage::disk('public')->delete($visit->foto_bukti);
            }
            $data['foto_bukti'] = $request->file('foto_bukti')->store('home-visit', 'public');
        } elseif ($request->input('remove_foto_bukti') == '1') {
            if ($visit->foto_bukti) {
                Storage::disk('public')->delete($visit->foto_bukti);
            }
            $data['foto_bukti'] = null;
        }

        $visit->update($data);

        return redirect()->route('bk.home-visit.index')
            ->with('success', 'Data home visit berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $visit = HomeVisit::findOrFail($id);
        if ($visit->foto_bukti) {
            Storage::disk('public')->delete($visit->foto_bukti);
        }
        $visit->delete();
        return redirect()->route('bk.home-visit.index')
            ->with('success', 'Data home visit berhasil dihapus.');
    }
}
