<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\DetailSiswa;
use App\Models\Sekolah;
use App\Models\UserSiswa;
use App\Models\RiwayatKesehatan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SiswaProfilController extends Controller
{
    /**
     * Ambil profil lengkap siswa yang sedang login.
     * Endpoint: GET /api/mobile/siswa/profil
     */
    public function show(Request $request)
    {
        $user = $request->user();

        if (!($user instanceof UserSiswa)) {
            return response()->json(['success' => false, 'message' => 'Akses ditolak.'], 403);
        }

        $siswa = UserSiswa::with(['kelas.jurusan', 'detail'])
            ->where('nis', $user->nis)
            ->firstOrFail();

        $sekolah = Sekolah::first();

        return response()->json([
            'success' => true,
            'data' => [
                'nis'          => $siswa->nis,
                'nisn'         => $siswa->nisn,
                'nik'          => $siswa->nik,
                'nama_siswa'   => $siswa->nama_siswa,
                'jenkel'       => $siswa->jenkel,
                'tempat_lahir' => $siswa->tempat_lahir,
                'tgl_lahir'    => $siswa->tgl_lahir?->format('Y-m-d'),
                'status'       => $siswa->status,
                'id_kelas'     => $siswa->id_kelas,
                'kelas'        => $siswa->kelas?->nama_kelas,
                'jurusan'      => $siswa->kelas?->jurusan?->nama_jurusan,
                'detail'       => $siswa->detail ? [
                    'alamat'          => $siswa->detail->alamat,
                    'agama'           => $siswa->detail->agama,
                    'golongan_darah'  => $siswa->detail->golongan_darah,
                    'nama_ayah'       => $siswa->detail->nama_ayah,
                    'pekerjaan_ayah'  => $siswa->detail->pekerjaan_ayah,
                    'no_telp_ayah'    => $siswa->detail->no_telp_ayah,
                    'nama_ibu'        => $siswa->detail->nama_ibu,
                    'pekerjaan_ibu'   => $siswa->detail->pekerjaan_ibu,
                    'no_telp_ibu'     => $siswa->detail->no_telp_ibu,
                    'nama_wali'       => $siswa->detail->nama_wali,
                    'pekerjaan_wali'  => $siswa->detail->pekerjaan_wali,
                    'no_telp_wali'    => $siswa->detail->no_telp_wali,
                    'no_wa_presensi'  => $siswa->detail->no_wa_presensi,
                    'latitude'        => $siswa->detail->latitude,
                    'longitude'       => $siswa->detail->longitude,
                    'foto_url'        => $siswa->detail->foto
                        ? asset('storage/' . $siswa->detail->foto)
                        : null,
                ] : null,
                'riwayat_kesehatan' => RiwayatKesehatan::where('nis', $siswa->nis)
                    ->orderBy('tanggal', 'desc')
                    ->get()
                    ->map(fn($r) => [
                        'id_riwayat_kesehatan' => $r->id_riwayat_kesehatan,
                        'tanggal'              => $r->tanggal?->format('Y-m-d'),
                        'tinggi_badan'         => $r->tinggi_badan,
                        'berat_badan'          => $r->berat_badan,
                        'golongan_darah'       => $r->golongan_darah,
                        'penyakit_bawaan'      => $r->penyakit_bawaan,
                        'alergi'               => $r->alergi,
                        'riwayat_penyakit'     => $r->riwayat_penyakit,
                        'catatan_khusus'       => $r->catatan_khusus,
                    ])
                    ->values()
                    ->all(),
                'edit_diizinkan' => (bool) ($sekolah?->edit_detail_siswa ?? false),
            ],
        ]);
    }

    /**
     * Update data detail siswa oleh siswa sendiri.
     * Hanya boleh jika admin mengizinkan (edit_detail_siswa = true).
     * Endpoint: POST /api/mobile/siswa/profil
     */
    public function update(Request $request)
    {
        $user = $request->user();

        if (!($user instanceof UserSiswa)) {
            return response()->json(['success' => false, 'message' => 'Akses ditolak.'], 403);
        }

        // Cek apakah edit diizinkan oleh admin
        $sekolah = Sekolah::first();
        if (!$sekolah?->edit_detail_siswa) {
            return response()->json([
                'success' => false,
                'message' => 'Fitur edit profil belum diaktifkan oleh admin.',
            ], 403);
        }

        $request->validate([
            // Data UserSiswa
            'nisn'           => 'nullable|string|max:20',
            'nik'            => 'nullable|string|max:20',
            'jenkel'         => 'nullable|in:L,P',
            'tempat_lahir'   => 'nullable|string|max:50',
            'tgl_lahir'      => 'nullable|date',
            // Data DetailSiswa
            'alamat'         => 'nullable|string',
            'agama'          => 'nullable|string|max:30',
            'golongan_darah' => 'nullable|string|max:5',
            'penyakit_bawaan'  => 'nullable|string',
            'alergi'           => 'nullable|string',
            'riwayat_penyakit' => 'nullable|string',
            'catatan_khusus'   => 'nullable|string',
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
            'latitude'       => 'nullable|numeric|between:-90,90',
            'longitude'      => 'nullable|numeric|between:-180,180',
        ]);

        $siswa  = UserSiswa::findOrFail($user->nis);
        $detail = $siswa->detail()->firstOrCreate(['nis' => $user->nis]);

        // Update data identitas di tabel user_siswa (jika dikirim)
        $siswaData = array_filter($request->only(['nisn', 'nik', 'jenkel', 'tempat_lahir', 'tgl_lahir']),
            fn($v) => $v !== null && $v !== '');
        if (!empty($siswaData)) {
            $siswa->update($siswaData);
        }

        // Update data detail di tabel detail_siswa
        $detailData = $request->only([
            'alamat', 'agama', 'golongan_darah',
            'nama_ayah', 'pekerjaan_ayah', 'no_telp_ayah',
            'nama_ibu', 'pekerjaan_ibu', 'no_telp_ibu',
            'nama_wali', 'pekerjaan_wali', 'no_telp_wali',
            'no_wa_presensi', 'latitude', 'longitude',
        ]);

        if ($request->has('foto')) {
            $fotoPath = \App\Helpers\FileUploadHelper::storeFile($request, 'foto', 'siswa/foto');
            if ($fotoPath) {
                if ($detail->foto && Storage::disk('public')->exists($detail->foto)) {
                    Storage::disk('public')->delete($detail->foto);
                }
                $detailData['foto'] = $fotoPath;
            }
        }

        $detail->update($detailData);

        // Jika ada data riwayat kesehatan, simpan ke tabel riwayat_kesehatan
        $kesehatanFields = ['penyakit_bawaan', 'alergi', 'riwayat_penyakit', 'catatan_khusus'];
        $kesehatanData = $request->only($kesehatanFields);
        $kesehatanData = array_filter($kesehatanData, fn($v) => $v !== null && $v !== '');
        if (!empty($kesehatanData)) {
            RiwayatKesehatan::updateOrCreate(
                ['nis' => $user->nis, 'tanggal' => now()->toDateString()],
                $kesehatanData
            );
        }

        // Refresh siswa agar data terbaru dibaca
        $siswa->refresh();

        return response()->json([
            'success' => true,
            'message' => 'Profil berhasil diperbarui.',
            'data'    => [
                'nisn'           => $siswa->nisn,
                'nik'            => $siswa->nik,
                'jenkel'         => $siswa->jenkel,
                'tempat_lahir'   => $siswa->tempat_lahir,
                'tgl_lahir'      => $siswa->tgl_lahir?->format('Y-m-d'),
                'alamat'         => $detail->alamat,
                'agama'          => $detail->agama,
                'golongan_darah' => $detail->golongan_darah,
                'nama_ayah'      => $detail->nama_ayah,
                'pekerjaan_ayah' => $detail->pekerjaan_ayah,
                'no_telp_ayah'   => $detail->no_telp_ayah,
                'nama_ibu'       => $detail->nama_ibu,
                'pekerjaan_ibu'  => $detail->pekerjaan_ibu,
                'no_telp_ibu'    => $detail->no_telp_ibu,
                'nama_wali'      => $detail->nama_wali,
                'pekerjaan_wali' => $detail->pekerjaan_wali,
                'no_telp_wali'   => $detail->no_telp_wali,
                'no_wa_presensi' => $detail->no_wa_presensi,
                'latitude'       => $detail->latitude,
                'longitude'      => $detail->longitude,
                'foto_url'       => $detail->foto_url,
            ],
        ]);
    }

    /**
     * Upload foto profil siswa.
     * Endpoint: POST /api/mobile/siswa/foto
     */
    public function uploadFoto(Request $request)
    {
        $user = $request->user();

        if (!($user instanceof UserSiswa)) {
            return response()->json(['success' => false, 'message' => 'Akses ditolak.'], 403);
        }

        $sekolah = Sekolah::first();
        if (!$sekolah?->edit_detail_siswa) {
            return response()->json([
                'success' => false,
                'message' => 'Fitur edit profil belum diaktifkan oleh admin.',
            ], 403);
        }

        $request->validate([
            'foto' => 'required',
        ]);

        $detail = DetailSiswa::firstOrCreate(['nis' => $user->nis]);
        $path = \App\Helpers\FileUploadHelper::storeFile($request, 'foto', 'siswa/foto');

        if ($path) {
            if ($detail->foto && Storage::disk('public')->exists($detail->foto)) {
                Storage::disk('public')->delete($detail->foto);
            }
            $detail->foto = $path;
            $detail->save();

            return response()->json([
                'success'  => true,
                'message'  => 'Foto profil siswa berhasil diperbarui.',
                'foto_url' => $detail->foto_url,
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'File foto tidak ditemukan atau format tidak valid.',
        ], 400);
    }

    /**
     * Cek apakah fitur edit detail diizinkan oleh admin.
     * Endpoint: GET /api/mobile/siswa/edit-akses
     */
    public function editAkses()
    {
        $sekolah = Sekolah::first();
        return response()->json([
            'success'        => true,
            'edit_diizinkan' => (bool) ($sekolah?->edit_detail_siswa ?? false),
        ]);
    }

    /**
     * Ambil riwayat kesehatan siswa yang login.
     * Endpoint: GET /api/mobile/siswa/riwayat-kesehatan
     */
    public function getRiwayatKesehatan(Request $request)
    {
        $user = $request->user();
        if (!($user instanceof UserSiswa)) {
            return response()->json(['success' => false, 'message' => 'Akses ditolak.'], 403);
        }

        $riwayat = RiwayatKesehatan::where('nis', $user->nis)
            ->orderBy('tanggal', 'desc')
            ->orderBy('id_riwayat_kesehatan', 'desc')
            ->get();

        return response()->json([
            'success' => true,
            'data'    => $riwayat
        ]);
    }

    /**
     * Simpan data riwayat kesehatan baru.
     * Endpoint: POST /api/mobile/siswa/riwayat-kesehatan
     */
    public function storeRiwayatKesehatan(Request $request)
    {
        $user = $request->user();
        if (!($user instanceof UserSiswa)) {
            return response()->json(['success' => false, 'message' => 'Akses ditolak.'], 403);
        }

        $request->validate([
            'tanggal'          => 'required|date',
            'tinggi_badan'     => 'nullable|integer|min:0',
            'berat_badan'      => 'nullable|integer|min:0',
            'golongan_darah'   => 'nullable|string|max:5',
            'penyakit_bawaan'  => 'nullable|string',
            'alergi'           => 'nullable|string',
            'riwayat_penyakit' => 'nullable|string',
            'catatan_khusus'   => 'nullable|string',
        ]);

        $riwayat = RiwayatKesehatan::create([
            'nis'              => $user->nis,
            'tanggal'          => $request->tanggal,
            'tinggi_badan'     => $request->tinggi_badan,
            'berat_badan'      => $request->berat_badan,
            'golongan_darah'   => $request->golongan_darah,
            'penyakit_bawaan'  => $request->penyakit_bawaan,
            'alergi'           => $request->alergi,
            'riwayat_penyakit' => $request->riwayat_penyakit,
            'catatan_khusus'   => $request->catatan_khusus,
        ]);

        // Opsional: Jika golongan darah diisi, sync juga ke tabel detail_siswa agar tetap sinkron
        if ($request->golongan_darah) {
            $detail = $user->detail()->firstOrCreate(['nis' => $user->nis]);
            $detail->update(['golongan_darah' => $request->golongan_darah]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Riwayat kesehatan berhasil dicatat.',
            'data'    => $riwayat
        ], 201);
    }

    /**
     * Ubah nomor HP/WA penerima notifikasi presensi siswa.
     * Bisa dipanggil oleh siswa maupun orang_tua (guard UserSiswa).
     * Endpoint: POST /api/mobile/siswa/update-wa-presensi
     *
     * Body (JSON/form):
     *   - no_wa_presensi  : string, required, max 25 char
     *
     * Response:
     *   {
     *     "success": true,
     *     "message": "Nomor WA presensi berhasil diperbarui.",
     *     "data": {
     *       "nis": "...",
     *       "nama_siswa": "...",
     *       "no_wa_presensi": "08xxxxxxxxxx"
     *     }
     *   }
     */
    public function updateNoWaPresensi(Request $request)
    {
        $user = $request->user();

        if (!($user instanceof UserSiswa)) {
            return response()->json(['success' => false, 'message' => 'Akses ditolak. Hanya siswa yang dapat menggunakan endpoint ini.'], 403);
        }

        $request->validate([
            'no_wa_presensi' => [
                'required',
                'string',
                'max:25',
                // Format dasar: hanya angka, +, -, spasi (termasuk kode negara)
                'regex:/^[\d\+\-\s]{8,25}$/',
            ],
        ], [
            'no_wa_presensi.required' => 'Nomor WhatsApp penerima presensi wajib diisi.',
            'no_wa_presensi.max'      => 'Nomor WhatsApp maksimal 25 karakter.',
            'no_wa_presensi.regex'    => 'Format nomor WhatsApp tidak valid. Gunakan format: 08xxxxxxxxxx atau +628xxxxxxxxxx.',
        ]);

        $detail = DetailSiswa::firstOrCreate(['nis' => $user->nis]);
        $noWaLama = $detail->no_wa_presensi;

        // Normalisasi: hapus spasi dan tanda hubung berlebih
        $noWaBaru = preg_replace('/[\s\-]+/', '', $request->no_wa_presensi);

        $detail->update(['no_wa_presensi' => $noWaBaru]);

        return response()->json([
            'success' => true,
            'message' => 'Nomor WA presensi berhasil diperbarui.',
            'data'    => [
                'nis'              => $user->nis,
                'nama_siswa'       => $user->nama_siswa,
                'no_wa_presensi'   => $detail->fresh()->no_wa_presensi,
                'no_wa_presensi_lama' => $noWaLama,
            ],
        ]);
    }

    /**
     * Update nomor WA presensi siswa oleh guru/wali kelas.
     * Endpoint: POST /api/mobile/guru/update-wa-siswa/{nis}
     *
     * Dapat dipanggil oleh guru, wali kelas, atau admin yang login.
     * Body (JSON):
     *   - no_wa_presensi  : string, required, max 25 char
     *
     * Response:
     *   {
     *     "success": true,
     *     "message": "...",
     *     "data": { "nis": "...", "no_wa_presensi": "..." }
     *   }
     */
    public function updateWaByGuru(Request $request, $nis)
    {
        $siswa = UserSiswa::find($nis);

        if (!$siswa) {
            return response()->json([
                'success' => false,
                'message' => "Siswa dengan NIS {$nis} tidak ditemukan.",
            ], 404);
        }

        $request->validate([
            'no_wa_presensi' => 'required|string|max:25',
        ], [
            'no_wa_presensi.required' => 'Nomor WhatsApp wajib diisi.',
            'no_wa_presensi.max'      => 'Nomor WhatsApp maksimal 25 karakter.',
        ]);

        // Normalisasi: hapus spasi dan tanda hubung berlebih
        $noWaBaru = preg_replace('/[\s\-]+/', '', $request->no_wa_presensi);

        $detail = DetailSiswa::firstOrCreate(['nis' => $nis]);
        $detail->update([
            'no_wa_presensi' => $noWaBaru,
            'no_telp_wali'   => $noWaBaru,
        ]);

        return response()->json([
            'success' => true,
            'message' => "Nomor WA ortu {$siswa->nama_siswa} berhasil disimpan.",
            'data'    => [
                'nis'            => $nis,
                'nama_siswa'     => $siswa->nama_siswa,
                'no_wa_presensi' => $detail->fresh()->no_wa_presensi,
            ],
        ]);
    }
}

