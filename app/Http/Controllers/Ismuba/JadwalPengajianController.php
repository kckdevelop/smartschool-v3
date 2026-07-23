<?php

namespace App\Http\Controllers\Ismuba;

use App\Http\Controllers\Controller;
use App\Models\JadwalPengajian;
use App\Models\Guru;
use App\Models\Karyawan;
use App\Models\KehadiranPengajian;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class JadwalPengajianController extends Controller
{
    public function index(Request $request)
    {
        // Semua data jadwal pengajian
        $perPage    = (int) $request->input('per_page', 15);
        $jadwalList = JadwalPengajian::orderByDesc('tanggal')->paginate($perPage)->withQueryString();

        // Statistik
        $totalKegiatan     = JadwalPengajian::count();
        $kegiatanTahunIni  = JadwalPengajian::whereYear('tanggal', Carbon::now()->year)->count();
        $kegiatanBulanIni  = JadwalPengajian::whereMonth('tanggal', Carbon::now()->month)
                                            ->whereYear('tanggal', Carbon::now()->year)
                                            ->count();

        $totalHadir        = KehadiranPengajian::where('status', 'hadir')->count();
        $totalIjin         = KehadiranPengajian::where('status', 'ijin')->count();
        $totalAlpha        = KehadiranPengajian::where('status', 'alpha')->count();
        $totalPeserta      = $totalHadir + $totalIjin + $totalAlpha;

        $rataRataKehadiran = $totalPeserta > 0
            ? round(($totalHadir / $totalPeserta) * 100, 1)
            : 0;

        // Rekap kehadiran per guru
        $rekapGuru = Guru::where('status', 'aktif')
            ->withCount([
                'kehadiranPengajian as total_kegiatan',
                'kehadiranPengajian as total_hadir' => function ($q) { $q->where('status', 'hadir'); },
                'kehadiranPengajian as total_ijin' => function ($q) { $q->where('status', 'ijin'); },
                'kehadiranPengajian as total_alpha' => function ($q) { $q->where('status', 'alpha'); },
            ])
            ->orderBy('nama_guru')
            ->get()
            ->map(function ($row) {
                $row->total_peserta = $row->total_hadir + $row->total_ijin + $row->total_alpha;
                $row->persen_hadir = $row->total_peserta > 0
                    ? round(($row->total_hadir / $row->total_peserta) * 100, 1)
                    : 0;
                $row->tipe = 'guru';
                $row->nama_tampil = $row->nama_guru;
                return $row;
            });

        // Rekap kehadiran per karyawan
        $rekapKaryawan = Karyawan::where('status', 'aktif')
            ->withCount([
                'kehadiranPengajian as total_kegiatan',
                'kehadiranPengajian as total_hadir' => function ($q) { $q->where('status', 'hadir'); },
                'kehadiranPengajian as total_ijin' => function ($q) { $q->where('status', 'ijin'); },
                'kehadiranPengajian as total_alpha' => function ($q) { $q->where('status', 'alpha'); },
            ])
            ->orderBy('nama_karyawan')
            ->get()
            ->map(function ($row) {
                $row->total_peserta = $row->total_hadir + $row->total_ijin + $row->total_alpha;
                $row->persen_hadir = $row->total_peserta > 0
                    ? round(($row->total_hadir / $row->total_peserta) * 100, 1)
                    : 0;
                $row->tipe = 'karyawan';
                $row->nama_tampil = $row->nama_karyawan;
                return $row;
            });

        // Gabungkan guru dan karyawan
        $rekapPenerima = $rekapGuru->concat($rekapKaryawan)->sortBy('nama_tampil')->values();

        return view('ismuba.jadwal-pengajian.index', compact(
            'jadwalList',
            'totalKegiatan',
            'kegiatanTahunIni',
            'kegiatanBulanIni',
            'rataRataKehadiran',
            'totalHadir',
            'totalIjin',
            'totalAlpha',
            'totalPeserta',
            'rekapPenerima'
        ));
    }

    public function store(Request $request)
    {
        $request->validate([
            'tanggal'        => 'required|date',
            'tempat'         => 'required|string|max:200',
            'nama_kegiatan'  => 'required|string|max:200',
            'lokasi_gmaps'   => 'nullable|max:2000',
            'keterangan'     => 'nullable|string|max:1000',
            'jam_mulai'      => 'nullable|string|max:10',
            'jam_selesai'    => 'nullable|string|max:10',
            'radius_meter'   => 'nullable|integer|min:1',
        ]);

        DB::transaction(function() use ($request) {
            $data = $request->only([
                'nama_kegiatan', 'tanggal', 'tempat', 'lokasi_gmaps', 'keterangan',
                'jam_mulai', 'jam_selesai', 'radius_meter',
            ]);

            // Ekstrak koordinat dari link Google Maps
            $coords = $this->extractCoordinates($request->lokasi_gmaps);
            $data['latitude']  = $coords['latitude'];
            $data['longitude'] = $coords['longitude'];

            $jadwal = JadwalPengajian::create($data);

            // Auto-generate default alpha kehadiran for all active guru
            $gurus = Guru::where('status', 'aktif')->get();
            foreach ($gurus as $guru) {
                KehadiranPengajian::create([
                    'id_jadwal'    => $jadwal->id_jadwal,
                    'id_guru'      => $guru->id_guru,
                    'id_karyawan'  => null,
                    'status'       => 'alpha',
                ]);
            }

            // Auto-generate default alpha kehadiran for all active karyawan
            $karyawans = Karyawan::where('status', 'aktif')->get();
            foreach ($karyawans as $karyawan) {
                KehadiranPengajian::create([
                    'id_jadwal'    => $jadwal->id_jadwal,
                    'id_guru'      => null,
                    'id_karyawan'  => $karyawan->id_karyawan,
                    'status'       => 'alpha',
                ]);
            }
        });

        return redirect()->route('ismuba.jadwal-pengajian.index')
            ->with('success', 'Jadwal pengajian berhasil ditambahkan dan daftar kehadiran guru telah digenerate.');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'tanggal'        => 'required|date',
            'tempat'         => 'required|string|max:200',
            'nama_kegiatan'  => 'required|string|max:200',
            'lokasi_gmaps'   => 'nullable|max:2000',
            'keterangan'     => 'nullable|string|max:1000',
            'jam_mulai'      => 'nullable|string|max:10',
            'jam_selesai'    => 'nullable|string|max:10',
            'radius_meter'   => 'nullable|integer|min:1',
        ]);

        $jadwal = JadwalPengajian::findOrFail($id);

        $data = $request->only([
            'nama_kegiatan', 'tanggal', 'tempat', 'lokasi_gmaps', 'keterangan',
            'jam_mulai', 'jam_selesai', 'radius_meter',
        ]);

        // Ekstrak koordinat dari link Google Maps
        $coords = $this->extractCoordinates($request->lokasi_gmaps);
        $data['latitude']  = $coords['latitude'];
        $data['longitude'] = $coords['longitude'];

        $jadwal->update($data);

        return redirect()->route('ismuba.jadwal-pengajian.index')
            ->with('success', 'Jadwal pengajian berhasil diperbarui.');
    }

    /**
     * Ekstrak latitude & longitude dari URL Google Maps.
     * Mendukung format panjang (/@lat,lng) dan format query (?q=lat,lng).
     * Untuk short link (maps.app.goo.gl), resolve redirect terlebih dahulu.
     */
    private function extractCoordinates(?string $url): array
    {
        if (empty($url)) {
            return ['latitude' => null, 'longitude' => null];
        }

        try {
            // Resolve short URL (maps.app.goo.gl / goo.gl/maps)
            if (preg_match('/maps\.app\.goo\.gl|goo\.gl\/maps/', $url)) {
                $context = stream_context_create([
                    'http' => ['method' => 'HEAD', 'follow_location' => 1, 'timeout' => 5],
                    'ssl'  => ['verify_peer' => false, 'verify_peer_name' => false],
                ]);
                $headers = @get_headers($url, 1, $context);
                if ($headers && isset($headers['Location'])) {
                    $url = is_array($headers['Location']) ? end($headers['Location']) : $headers['Location'];
                }
            }

            // Format 1: .../@-7.9265,110.3232,17z/...
            if (preg_match('/@(-?\d+\.\d+),(-?\d+\.\d+)/', $url, $m)) {
                return ['latitude' => (float)$m[1], 'longitude' => (float)$m[2]];
            }

            // Format 2: ?q=-7.9265,110.3232 atau &ll=-7.9265,110.3232
            if (preg_match('/[?&](?:q|ll)=(-?\d+\.\d+),(-?\d+\.\d+)/', $url, $m)) {
                return ['latitude' => (float)$m[1], 'longitude' => (float)$m[2]];
            }

            // Format 3: /place/.../@lat,lng or embedded in URL path
            if (preg_match('/!3d(-?\d+\.\d+)!4d(-?\d+\.\d+)/', $url, $m)) {
                return ['latitude' => (float)$m[1], 'longitude' => (float)$m[2]];
            }
        } catch (\Exception $e) {
            // Gagal resolve, kembalikan null
        }

        return ['latitude' => null, 'longitude' => null];
    }

    public function destroy($id)
    {
        $jadwal = JadwalPengajian::findOrFail($id);
        // Delete any uploaded photos first
        foreach ($jadwal->kehadiran as $k) {
            if ($k->foto) {
                Storage::disk('public')->delete($k->foto);
            }
        }
        $jadwal->delete();

        return redirect()->route('ismuba.jadwal-pengajian.index')
            ->with('success', 'Jadwal pengajian berhasil dihapus.');
    }

    public function getDetail($id)
    {
        $jadwal = JadwalPengajian::with(['kehadiran.guru', 'kehadiran.karyawan'])->findOrFail($id);

        $kehadiran = $jadwal->kehadiran->map(function($k) {
            // Nama dari guru atau karyawan
            if ($k->id_guru && $k->guru) {
                $nama = $k->guru->nama_guru;
            } elseif ($k->id_karyawan && $k->karyawan) {
                $nama = $k->karyawan->nama_karyawan;
            } else {
                $nama = 'N/A';
            }

            return [
                'id'           => $k->id,
                'nama_guru'    => $nama,
                'tipe'         => $k->id_guru ? 'guru' : 'karyawan',
                'status'       => $k->status,
                'jam_absen'    => $k->jam_absen,
                'foto'         => $k->foto ? url('storage/pengajian/selfie/' . $k->foto) : null,
                'lokasi_gmaps' => $k->lokasi_gmaps,
                'keterangan'   => $k->keterangan,
            ];
        })->sortBy('nama_guru')->values();

        return response()->json([
            'jadwal'    => $jadwal,
            'kehadiran' => $kehadiran,
        ]);
    }

    public function updateKehadiran(Request $request, $id)
    {
        $request->validate([
            'kehadiran' => 'required|array',
            'kehadiran.*.status' => 'required|in:hadir,ijin,alpha',
            'kehadiran.*.keterangan' => 'nullable|string|max:255',
            'kehadiran.*.lokasi_gmaps' => 'nullable|url|max:2000',
            'kehadiran.*.foto' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        DB::transaction(function() use ($request) {
            foreach ($request->input('kehadiran') as $kehadiranId => $data) {
                $kehadiran = KehadiranPengajian::findOrFail($kehadiranId);
                $updateData = [
                    'status' => $data['status'],
                    'keterangan' => $data['keterangan'] ?? null,
                ];

                if (isset($data['lokasi_gmaps'])) {
                    $updateData['lokasi_gmaps'] = $data['lokasi_gmaps'];
                }

                if ($request->hasFile("kehadiran.{$kehadiranId}.foto")) {
                    if ($kehadiran->foto) {
                        Storage::disk('public')->delete($kehadiran->foto);
                    }
                    $file = $request->file("kehadiran.{$kehadiranId}.foto");
                    $updateData['foto'] = $file->store('pengajian/kehadiran', 'public');
                }

                if ($data['status'] === 'hadir') {
                    if (!$kehadiran->jam_absen) {
                        $updateData['jam_absen'] = Carbon::now()->format('H:i:s');
                    }
                } else {
                    $updateData['jam_absen'] = null;
                }

                $kehadiran->update($updateData);
            }
        });

        return redirect()->route('ismuba.jadwal-pengajian.index')
            ->with('success', 'Kehadiran pengajian berhasil disimpan.');
    }

    public function print(Request $request)
    {
        $tanggalMulai = $request->input('tanggal_mulai');
        $tanggalAkhir = $request->input('tanggal_akhir');

        $query = JadwalPengajian::orderBy('tanggal');

        if ($tanggalMulai && $tanggalAkhir) {
            $query->whereBetween('tanggal', [$tanggalMulai, $tanggalAkhir]);
        } elseif ($tanggalMulai) {
            $query->where('tanggal', '>=', $tanggalMulai);
        } elseif ($tanggalAkhir) {
            $query->where('tanggal', '<=', $tanggalAkhir);
        }

        $jadwalList = $query->get();

        $totalHadir = KehadiranPengajian::whereIn('id_jadwal', $jadwalList->pluck('id_jadwal'))
            ->where('status', 'hadir')
            ->count();
        $totalIjin = KehadiranPengajian::whereIn('id_jadwal', $jadwalList->pluck('id_jadwal'))
            ->where('status', 'ijin')
            ->count();
        $totalAlpha = KehadiranPengajian::whereIn('id_jadwal', $jadwalList->pluck('id_jadwal'))
            ->where('status', 'alpha')
            ->count();
        $totalPeserta = $totalHadir + $totalIjin + $totalAlpha;

        if ($tanggalMulai && $tanggalAkhir) {
            $periodeLabel = Carbon::parse($tanggalMulai)->translatedFormat('d F Y')
                          . ' s/d '
                          . Carbon::parse($tanggalAkhir)->translatedFormat('d F Y');
        } elseif ($tanggalMulai) {
            $periodeLabel = 'Mulai ' . Carbon::parse($tanggalMulai)->translatedFormat('d F Y');
        } elseif ($tanggalAkhir) {
            $periodeLabel = 'Sampai ' . Carbon::parse($tanggalAkhir)->translatedFormat('d F Y');
        } else {
            $periodeLabel = 'Semua Periode';
        }

        return view('ismuba.jadwal-pengajian.print', compact(
            'jadwalList', 'totalHadir', 'totalIjin', 'totalAlpha', 'totalPeserta', 'periodeLabel',
            'tanggalMulai', 'tanggalAkhir'
        ));
    }
}
