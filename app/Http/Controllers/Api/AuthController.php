<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\UserSmartschool;
use App\Models\UserSiswa;
use App\Models\Guru;
use App\Models\Karyawan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    /**
     * Login untuk Web/Dashboard (user_smartschool & user_siswa).
     * Endpoint: POST /api/auth/login
     */
    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required|string',
            'password' => 'present',          // hanya wajib hadir, boleh kosong/null
        ]);

        $username = $request->username;
        $password = $request->input('password') ?? '';

        // Try smartschool user first
        $user = UserSmartschool::where('username', $username)->first();
        $userType = 'staff';

        // If not found and username is numeric, try siswa
        if (!$user && is_numeric($username)) {
            $user = UserSiswa::where('nis', $username)->first();
            $userType = 'siswa';
        }

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Credentials do not match our records.'
            ], 401);
        }

        // Check password (supports SHA1 or bcrypt)
        $passwordValid = false;
        if (sha1($password) === $user->password) {
            $passwordValid = true;
        } elseif (Hash::check($password, $user->password)) {
            $passwordValid = true;
        }

        if (!$passwordValid) {
            return response()->json([
                'success' => false,
                'message' => 'Credentials do not match our records.'
            ], 401);
        }

        // Create token
        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'success' => true,
            'token'   => $token,
            'user'    => [
                'id'             => $userType === 'staff' ? $user->id_user : $user->nis,
                'username'       => $userType === 'staff' ? $user->username : $user->nis,
                'nama'           => $userType === 'staff' ? $user->nama_lengkap : $user->nama_siswa,
                'role'           => $userType === 'staff' ? $user->level : 'siswa',
                'type'           => $userType,
                'is_petugas_uks' => $userType === 'staff' && in_array(strtolower((string)$user->level), ['petugas_uks', 'uks', 'admin']),
            ]
        ]);
    }

    /**
     * Unified Login untuk Flutter App.
     * Endpoint: POST /api/login
     */
    public function unifiedLogin(Request $request)
    {
        if ($request->has('role')) {
            return $this->mobileLogin($request);
        }

        if ($request->has('login') && !$request->has('username')) {
            $request->merge(['username' => $request->login]);
        }

        return $this->login($request);
    }

    /**
     * Login Khusus Aplikasi Mobile.
     * Mendukung 4 tipe user: siswa, orang_tua, guru, karyawan.
     */
    public function mobileLogin(Request $request)
    {
        if ($request->role === 'wali') {
            $request->merge(['role' => 'orang_tua']);
        }

        if ($request->has('login') && !$request->has('id')) {
            $request->merge(['id' => $request->login]);
        }

        $request->validate([
            'role'     => 'required|in:siswa,orang_tua,guru,karyawan',
            'id'       => 'required',
            'password' => 'required|string',
        ]);

        $role     = $request->role;
        $id       = $request->id;
        $password = $request->password;

        // ─── Siswa ───────────────────────────────────────────────────────────
        if ($role === 'siswa') {
            $siswa = UserSiswa::where('nis', $id)->where('status', 'aktif')->first();

            if (!$siswa) {
                return $this->unauthorizedResponse('Siswa tidak ditemukan atau tidak aktif.');
            }

            if (!$this->checkPassword($password, $siswa->password)) {
                return $this->unauthorizedResponse('Password salah.');
            }

            $token = $siswa->createToken('mobile_siswa', ['role:siswa'])->plainTextToken;

            return $this->successResponse($token, [
                'id'       => $siswa->nis,
                'nis'      => $siswa->nis,
                'nama'     => $siswa->nama_siswa,
                'role'     => 'siswa',
                'jenkel'   => $siswa->jenkel,
                'id_kelas' => $siswa->id_kelas,
                'kelas'    => $siswa->kelas ? $siswa->kelas->nama_kelas : null,
            ]);
        }

        // ─── Orang Tua ───────────────────────────────────────────────────────
        if ($role === 'orang_tua') {
            $siswa = UserSiswa::with('kelas')->where('nis', $id)->where('status', 'aktif')->first();

            if (!$siswa) {
                return $this->unauthorizedResponse('Data siswa tidak ditemukan atau tidak aktif.');
            }

            if (!$this->checkPassword($password, $siswa->password_wali)) {
                return $this->unauthorizedResponse('Password wali salah.');
            }

            $token = $siswa->createToken('mobile_ortu', ['role:orang_tua'])->plainTextToken;

            return $this->successResponse($token, [
                'id'       => $siswa->nis,
                'nis'      => $siswa->nis,
                'nama'     => 'Wali / Orang Tua ' . $siswa->nama_siswa,
                'nama_anak'=> $siswa->nama_siswa,
                'role'     => 'orang_tua',
                'id_kelas' => $siswa->id_kelas,
                'kelas'    => $siswa->kelas ? $siswa->kelas->nama_kelas : null,
            ]);
        }

        // ─── Guru ─────────────────────────────────────────────────────────────
        if ($role === 'guru') {
            $guru = Guru::where('no_id', $id)->where('status', 'aktif')->first();

            if (!$guru) {
                return $this->unauthorizedResponse('Guru tidak ditemukan atau tidak aktif.');
            }

            if (!$this->checkPassword($password, $guru->password)) {
                return $this->unauthorizedResponse('Password salah.');
            }

            $token = $guru->createToken('mobile_guru', ['role:guru'])->plainTextToken;

            $kelasWali = \App\Models\Kelas::where('walikelas', $guru->id_guru)->where('status', 'aktif')->first();

            return $this->successResponse($token, [
                'id'             => $guru->id_guru,
                'no_id'          => $guru->no_id,
                'nama'           => $guru->nama_guru,
                'no_hp'          => $guru->no_hp,
                'role'           => 'guru',
                'guru_bk'        => $guru->guru_bk,
                'guru_ismuba'    => $guru->guru_ismuba,
                'is_wali_kelas'  => $kelasWali ? true : false,
                'id_kelas_wali'  => $kelasWali?->id_kelas,
                'nama_kelas_wali'=> $kelasWali?->nama_kelas,
                'foto_url'       => $guru->foto_url,
            ]);
        }

        // ─── Karyawan ─────────────────────────────────────────────────────────
        if ($role === 'karyawan') {
            $karyawan = Karyawan::where('no_id', $id)->where('status', 'aktif')->first();

            if (!$karyawan) {
                return $this->unauthorizedResponse('Karyawan tidak ditemukan atau tidak aktif.');
            }

            if (!$this->checkPassword($password, $karyawan->password)) {
                return $this->unauthorizedResponse('Password salah.');
            }

            $token = $karyawan->createToken('mobile_karyawan', ['role:karyawan'])->plainTextToken;

            $isPetugas = in_array(strtolower((string)$karyawan->petugas_uks), ['ya', '1', 'true', 'petugas_uks', 'uks']);

            return $this->successResponse($token, [
                'id'             => $karyawan->id_karyawan,
                'no_id'          => $karyawan->no_id,
                'nama'           => $karyawan->nama_karyawan,
                'role'           => 'karyawan',
                'is_petugas_uks' => $isPetugas,
                'petugas_uks'    => $isPetugas,
                'foto_url'       => $karyawan->foto_url,
            ]);
        }
    }

    /**
     * Informative user info for logged in user.
     * Endpoint: GET /api/auth/me or GET /api/mobile/me
     */
    public function me(Request $request)
    {
        $user = $request->user();

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthenticated.'
            ], 401);
        }

        $data = [];

        if ($user instanceof UserSiswa) {
            $tokenName = $user->currentAccessToken()->name ?? '';
            $isOrtu    = str_contains($tokenName, 'ortu');

            if ($isOrtu) {
                $data = [
                    'id'        => $user->nis,
                    'nis'       => $user->nis,
                    'nama'      => 'Wali / Orang Tua ' . $user->nama_siswa,
                    'nama_anak' => $user->nama_siswa,
                    'role'      => 'orang_tua',
                    'type'      => 'orang_tua',
                    'id_kelas'  => $user->id_kelas,
                    'kelas'     => $user->kelas ? $user->kelas->nama_kelas : null,
                ];
            } else {
                $data = [
                    'id'       => $user->nis,
                    'nis'      => $user->nis,
                    'nama'     => $user->nama_siswa,
                    'role'     => 'siswa',
                    'type'     => 'siswa',
                    'jenkel'   => $user->jenkel,
                    'id_kelas' => $user->id_kelas,
                    'kelas'    => $user->kelas ? $user->kelas->nama_kelas : null,
                ];
            }
        } elseif ($user instanceof Guru) {
            $kelasWali = \App\Models\Kelas::where('walikelas', $user->id_guru)->where('status', 'aktif')->first();
            $data = [
                'id'             => $user->id_guru,
                'no_id'          => $user->no_id,
                'nama'           => $user->nama_guru,
                'no_hp'          => $user->no_hp,
                'role'           => 'guru',
                'type'           => 'guru',
                'guru_bk'        => $user->guru_bk,
                'guru_ismuba'    => $user->guru_ismuba,
                'is_wali_kelas'  => $kelasWali ? true : false,
                'id_kelas_wali'  => $kelasWali?->id_kelas,
                'nama_kelas_wali'=> $kelasWali?->nama_kelas,
                'foto_url'       => $user->foto_url,
            ];
        } elseif ($user instanceof Karyawan) {
            $isPetugas = in_array(strtolower((string)$user->petugas_uks), ['ya', '1', 'true', 'petugas_uks', 'uks']);
            $data = [
                'id'             => $user->id_karyawan,
                'no_id'          => $user->no_id,
                'nama'           => $user->nama_karyawan,
                'role'           => 'karyawan',
                'type'           => 'karyawan',
                'is_petugas_uks' => $isPetugas,
                'petugas_uks'    => $isPetugas,
                'foto_url'       => $user->foto_url,
            ];
        } else {
            // UserSmartschool (staff web)
            $data = [
                'id'             => $user->id_user,
                'username'       => $user->username,
                'nama'           => $user->nama_lengkap,
                'role'           => $user->level,
                'type'           => 'staff',
                'is_petugas_uks' => in_array(strtolower((string)$user->level), ['petugas_uks', 'uks', 'admin']),
            ];
        }

        return response()->json([
            'success' => true,
            'data'    => $data,
        ]);
    }

    /**
     * Ubah password user yang sedang login.
     */
    public function changePassword(Request $request)
    {
        $request->validate([
            'old_password' => 'required|string',
            'new_password' => 'required|string|min:4|confirmed',
        ]);

        $user = $request->user();
        $oldPassword = $request->old_password;
        $newPassword = $request->new_password;

        if ($user instanceof UserSiswa) {
            $tokenName = $user->currentAccessToken()->name ?? '';
            $isOrtu    = str_contains($tokenName, 'ortu');

            if ($isOrtu) {
                if (!$this->checkPassword($oldPassword, $user->password_wali)) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Password lama salah.'
                    ], 422);
                }
                $user->update(['password_wali' => sha1($newPassword)]);
            } else {
                if (!$this->checkPassword($oldPassword, $user->password)) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Password lama salah.'
                    ], 422);
                }
                $user->update(['password' => sha1($newPassword)]);
            }
        } elseif ($user instanceof Guru) {
            if (!$this->checkPassword($oldPassword, $user->password)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Password lama salah.'
                ], 422);
            }
            $user->update(['password' => sha1($newPassword)]);
        } elseif ($user instanceof Karyawan) {
            if (!$this->checkPassword($oldPassword, $user->password)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Password lama salah.'
                ], 422);
            }
            $user->update(['password' => sha1($newPassword)]);
        } else {
            if (!$this->checkPassword($oldPassword, $user->password)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Password lama salah.'
                ], 422);
            }
            $user->update(['password' => sha1($newPassword)]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Password berhasil diperbarui.',
        ]);
    }

    private function checkPassword($plain, $hashedOrSha1)
    {
        if (sha1($plain) === $hashedOrSha1) {
            return true;
        }
        return Hash::check($plain, $hashedOrSha1);
    }

    private function unauthorizedResponse($message)
    {
        return response()->json([
            'success' => false,
            'message' => $message,
        ], 401);
    }

    private function successResponse($token, $user)
    {
        return response()->json([
            'success' => true,
            'token'   => $token,
            'user'    => $user,
        ]);
    }
}
