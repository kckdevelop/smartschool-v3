<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\JadwalPengajian;
use App\Models\KehadiranPengajian;
use App\Models\Karyawan;
use App\Models\Guru;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class JadwalPengajianController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        $idGuru = $user ? ($user->id_guru ?? null) : null;
        $idKaryawan = $user ? ($user->id_karyawan ?? null) : null;

        $query = JadwalPengajian::query();

        if ($idGuru) {
            $query->with(['kehadiran' => function ($q) use ($idGuru) {
                $q->where('id_guru', $idGuru);
            }]);
        } elseif ($idKaryawan) {
            $query->with(['kehadiran' => function ($q) use ($idKaryawan) {
                $q->where('id_karyawan', $idKaryawan);
            }]);
        }

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('nama_kegiatan', 'like', '%' . $request->search . '%')
                  ->orWhere('tempat', 'like', '%' . $request->search . '%');
            });
        }

        $perPage = $request->get('per_page', 15);
        $data = $query->orderByDesc('tanggal')->paginate($perPage);

        return response()->json([
            'success' => true,
            'data' => $data,
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_kegiatan' => 'required|string|max:150',
            'tanggal' => 'required|date',
            'tempat' => 'required|string|max:150',
            'lokasi_gmaps' => 'nullable|string',
            'keterangan' => 'nullable|string',
            'jam_mulai' => 'nullable|string',
            'jam_selesai' => 'nullable|string',
        ]);

        DB::beginTransaction();
        try {
            $data = $request->only(
                'nama_kegiatan', 'tanggal', 'tempat', 'lokasi_gmaps', 'keterangan', 'jam_mulai', 'jam_selesai'
            );

            // Ekstrak koordinat dari link Google Maps
            $coords = $this->extractCoordinates($request->lokasi_gmaps);
            $data['latitude']  = $coords['latitude'];
            $data['longitude'] = $coords['longitude'];
            if ($request->filled('radius_meter')) {
                $data['radius_meter'] = (int) $request->radius_meter;
            }

            $pengajian = JadwalPengajian::create($data);

            // Generate Kehadiran Pengajian otomatis untuk semua guru dan karyawan aktif
            $gurus = Guru::where('status', 'aktif')->get();
            $karyawans = Karyawan::where('status', 'aktif')->get();

            foreach ($gurus as $guru) {
                KehadiranPengajian::create([
                    'id_jadwal' => $pengajian->id_jadwal,
                    'id_guru' => $guru->id_guru,
                    'id_karyawan' => null,
                    'status' => 'alpha',
                    'keterangan' => null,
                ]);
            }

            foreach ($karyawans as $karyawan) {
                KehadiranPengajian::create([
                    'id_jadwal' => $pengajian->id_jadwal,
                    'id_guru' => null,
                    'id_karyawan' => $karyawan->id_karyawan,
                    'status' => 'alpha',
                    'keterangan' => null,
                ]);
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Gagal membuat jadwal pengajian: ' . $e->getMessage()
            ], 500);
        }

        return response()->json([
            'success' => true,
            'message' => 'Jadwal pengajian berhasil ditambahkan dan rekap kehadiran di-generate.',
            'data' => $pengajian,
        ], 201);
    }

    public function show($id)
    {
        $pengajian = JadwalPengajian::with(['kehadiran.guru', 'kehadiran.karyawan'])->findOrFail($id);

        return response()->json([
            'success' => true,
            'data' => $pengajian,
        ]);
    }

    public function update(Request $request, $id)
    {
        $pengajian = JadwalPengajian::findOrFail($id);

        $request->validate([
            'nama_kegiatan' => 'sometimes|required|string|max:150',
            'tanggal' => 'sometimes|required|date',
            'tempat' => 'sometimes|required|string|max:150',
            'lokasi_gmaps' => 'nullable|string',
            'keterangan' => 'nullable|string',
            'jam_mulai' => 'nullable|string',
            'jam_selesai' => 'nullable|string',
        ]);

        $data = $request->only(
            'nama_kegiatan', 'tanggal', 'tempat', 'lokasi_gmaps', 'keterangan', 'jam_mulai', 'jam_selesai'
        );

        // Ekstrak koordinat dari link Google Maps
        $coords = $this->extractCoordinates($request->lokasi_gmaps);
        $data['latitude']  = $coords['latitude'];
        $data['longitude'] = $coords['longitude'];
        if ($request->filled('radius_meter')) {
            $data['radius_meter'] = (int) $request->radius_meter;
        }

        $pengajian->update($data);

        return response()->json([
            'success' => true,
            'message' => 'Jadwal pengajian berhasil diperbarui.',
            'data' => $pengajian,
        ]);
    }

    public function destroy($id)
    {
        $pengajian = JadwalPengajian::findOrFail($id);

        DB::beginTransaction();
        try {
            $pengajian->kehadiran()->delete();
            $pengajian->delete();
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus jadwal pengajian: ' . $e->getMessage()
            ], 500);
        }

        return response()->json([
            'success' => true,
            'message' => 'Jadwal pengajian berhasil dihapus.',
        ]);
    }

    /**
     * Ekstrak latitude & longitude dari URL Google Maps.
     */
    private function extractCoordinates(?string $url): array
    {
        if (empty($url)) {
            return ['latitude' => null, 'longitude' => null];
        }
        try {
            // Resolve short URL
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
            // Format /@lat,lng
            if (preg_match('/@(-?\d+\.\d+),(-?\d+\.\d+)/', $url, $m)) {
                return ['latitude' => (float)$m[1], 'longitude' => (float)$m[2]];
            }
            // Format ?q=lat,lng atau &ll=lat,lng
            if (preg_match('/[?&](?:q|ll)=(-?\d+\.\d+),(-?\d+\.\d+)/', $url, $m)) {
                return ['latitude' => (float)$m[1], 'longitude' => (float)$m[2]];
            }
            // Format !3d...!4d...
            if (preg_match('/!3d(-?\d+\.\d+)!4d(-?\d+\.\d+)/', $url, $m)) {
                return ['latitude' => (float)$m[1], 'longitude' => (float)$m[2]];
            }
        } catch (\Exception $e) {
            // Fallback
        }
        return ['latitude' => null, 'longitude' => null];
    }

    public function updateKehadiran(Request $request, $id_jadwal)
    {
        $request->validate([
            'kehadiran' => 'required|array',
            'kehadiran.*.id_kehadiran' => 'required|integer|exists:kehadiran_pengajian,id_kehadiran',
            'kehadiran.*.status' => 'required|in:hadir,ijin,alpha',
            'kehadiran.*.keterangan' => 'nullable|string|max:255',
        ]);

        DB::beginTransaction();
        try {
            foreach ($request->kehadiran as $item) {
                KehadiranPengajian::where('id', $item['id_kehadiran'] ?? $item['id'])
                    ->where('id_jadwal', $id_jadwal)
                    ->update([
                        'status' => $item['status'],
                        'keterangan' => $item['keterangan'] ?? null,
                    ]);
            }
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Gagal memperbarui data kehadiran: ' . $e->getMessage()
            ], 500);
        }

        return response()->json([
            'success' => true,
            'message' => 'Data kehadiran pengajian berhasil diperbarui.',
        ]);
    }

    /**
     * Get active pengajian schedule today for logged-in guru/karyawan.
     * GET /api/pengajian/aktif
     */
    public function aktif(Request $request)
    {
        $user = $request->user();
        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized.'
            ], 401);
        }

        $idGuru = $user->id_guru ?? null;
        $idKaryawan = $user->id_karyawan ?? null;

        if (!$idGuru && !$idKaryawan) {
            return response()->json([
                'success' => false,
                'message' => 'Hanya untuk Guru dan Karyawan.'
            ], 403);
        }

        $today = \Carbon\Carbon::today()->toDateString();
        $jadwal = JadwalPengajian::whereDate('tanggal', $today)->first();

        if (!$jadwal) {
            return response()->json([
                'success' => true,
                'has_jadwal' => false,
                'message' => 'Tidak ada jadwal pengajian hari ini.'
            ]);
        }

        // Cari atau buat kehadiran jika belum ada
        $kehadiran = null;
        if ($idGuru) {
            $kehadiran = KehadiranPengajian::where('id_jadwal', $jadwal->id_jadwal)
                ->where('id_guru', $idGuru)
                ->first();
            if (!$kehadiran) {
                $kehadiran = KehadiranPengajian::create([
                    'id_jadwal' => $jadwal->id_jadwal,
                    'id_guru' => $idGuru,
                    'status' => 'alpha',
                ]);
            }
        } elseif ($idKaryawan) {
            $kehadiran = KehadiranPengajian::where('id_jadwal', $jadwal->id_jadwal)
                ->where('id_karyawan', $idKaryawan)
                ->first();
            if (!$kehadiran) {
                $kehadiran = KehadiranPengajian::create([
                    'id_jadwal' => $jadwal->id_jadwal,
                    'id_karyawan' => $idKaryawan,
                    'status' => 'alpha',
                ]);
            }
        }

        return response()->json([
            'success' => true,
            'has_jadwal' => true,
            'jadwal' => [
                'id_jadwal' => $jadwal->id_jadwal,
                'nama_kegiatan' => $jadwal->nama_kegiatan,
                'tanggal' => $jadwal->tanggal ? $jadwal->tanggal->toDateString() : $today,
                'jam_mulai' => $jadwal->jam_mulai ? substr($jadwal->jam_mulai, 0, 5) : null,
                'jam_selesai' => $jadwal->jam_selesai ? substr($jadwal->jam_selesai, 0, 5) : null,
                'tempat' => $jadwal->tempat,
                'lokasi_gmaps' => $jadwal->lokasi_gmaps,
                'latitude' => $jadwal->latitude ? (float)$jadwal->latitude : null,
                'longitude' => $jadwal->longitude ? (float)$jadwal->longitude : null,
                'radius_meter' => $jadwal->radius_meter ? (int)$jadwal->radius_meter : 100,
                'keterangan' => $jadwal->keterangan,
            ],
            'kehadiran' => $kehadiran ? [
                'id' => $kehadiran->id,
                'status' => $kehadiran->status,
                'jam_absen' => $kehadiran->jam_absen ? substr($kehadiran->jam_absen, 0, 5) : null,
                'foto' => $kehadiran->foto ? asset('storage/pengajian/selfie/' . $kehadiran->foto) : null,
                'lokasi_gmaps' => $kehadiran->lokasi_gmaps,
                'keterangan' => $kehadiran->keterangan,
            ] : null
        ]);
    }

    /**
     * Submit attendance selfie + geolocation.
     * POST /api/pengajian/absen
     */
    public function absen(Request $request)
    {
        $user = $request->user();
        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized.'
            ], 401);
        }

        $idGuru = $user->id_guru ?? null;
        $idKaryawan = $user->id_karyawan ?? null;

        if (!$idGuru && !$idKaryawan) {
            return response()->json([
                'success' => false,
                'message' => 'Hanya untuk Guru dan Karyawan.'
            ], 403);
        }

        $request->validate([
            'foto'      => 'required',
            'latitude'  => 'required|numeric',
            'longitude' => 'required|numeric',
        ]);

        $today = \Carbon\Carbon::today()->toDateString();
        $jadwal = JadwalPengajian::whereDate('tanggal', $today)->first();

        if (!$jadwal) {
            return response()->json([
                'success' => false,
                'message' => 'Tidak ada jadwal pengajian aktif hari ini.'
            ], 404);
        }

        // Cari atau buat record kehadiran
        $kehadiran = null;
        if ($idGuru) {
            $kehadiran = KehadiranPengajian::where('id_jadwal', $jadwal->id_jadwal)
                ->where('id_guru', $idGuru)
                ->first();
            if (!$kehadiran) {
                $kehadiran = KehadiranPengajian::create([
                    'id_jadwal' => $jadwal->id_jadwal,
                    'id_guru' => $idGuru,
                    'status' => 'alpha',
                ]);
            }
        } elseif ($idKaryawan) {
            $kehadiran = KehadiranPengajian::where('id_jadwal', $jadwal->id_jadwal)
                ->where('id_karyawan', $idKaryawan)
                ->first();
            if (!$kehadiran) {
                $kehadiran = KehadiranPengajian::create([
                    'id_jadwal' => $jadwal->id_jadwal,
                    'id_karyawan' => $idKaryawan,
                    'status' => 'alpha',
                ]);
            }
        }

        if ($kehadiran->status === 'hadir') {
            return response()->json([
                'success' => false,
                'message' => 'Anda sudah melakukan absensi pengajian hari ini.'
            ], 400);
        }

        // Validasi Geolocation Radius (Haversine Formula)
        if ($jadwal->latitude && $jadwal->longitude) {
            $lat1 = (float)$jadwal->latitude;
            $lon1 = (float)$jadwal->longitude;
            $lat2 = (float)$request->latitude;
            $lon2 = (float)$request->longitude;

            $earthRadius = 6371000; // meter
            $latFrom = deg2rad($lat1);
            $lonFrom = deg2rad($lon1);
            $latTo = deg2rad($lat2);
            $lonTo = deg2rad($lon2);

            $latDelta = $latTo - $latFrom;
            $lonDelta = $lonTo - $lonFrom;

            $angle = 2 * asin(sqrt(pow(sin($latDelta / 2), 2) +
                cos($latFrom) * cos($latTo) * pow(sin($lonDelta / 2), 2)));
            $distance = $angle * $earthRadius;

            $allowedRadius = 500;
            if ($distance > (float)$allowedRadius) {
                return response()->json([
                    'success' => false,
                    'message' => 'Anda berada di luar radius lokasi pengajian (Jarak Anda: ' . round($distance) . ' meter, batas radius: ' . $allowedRadius . ' meter).'
                ], 400);
            }
        }

        // Simpan foto selfie
        $storedPath = \App\Helpers\FileUploadHelper::storeFile($request, 'foto', 'pengajian/selfie');

        if ($storedPath) {
            // Ambil nama file dari relative path
            $filename = basename($storedPath);

            // Update Kehadiran
            $kehadiran->update([
                'status'       => 'hadir',
                'jam_absen'    => \Carbon\Carbon::now()->toTimeString(),
                'foto'         => $filename,
                'lokasi_gmaps' => $request->latitude . ',' . $request->longitude,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Absensi pengajian berhasil dikirim!',
                'data' => [
                    'status'       => 'hadir',
                    'jam_absen'    => substr($kehadiran->jam_absen, 0, 5),
                    'foto'         => asset('storage/pengajian/selfie/' . $filename),
                    'lokasi_gmaps' => $kehadiran->lokasi_gmaps,
                ]
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Foto selfie wajib diunggah atau format tidak valid.'
        ], 400);
    }
}

