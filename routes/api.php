<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\DashboardController;
use App\Http\Controllers\Api\PresensiMesinController;

// ─── Public Routes ────────────────────────────────────────────────────────────
Route::post('/auth/login', [AuthController::class, 'login']);

// ─── Mobile Auth (Public) ─────────────────────────────────────────────────────
// Login untuk aplikasi mobile: siswa, orang_tua, guru, karyawan
Route::post('/mobile/login', [AuthController::class, 'mobileLogin']);

// ─── Unified Login (Flutter App) ──────────────────────────────────────────────
// Flutter app memanggil /api/login (dengan field 'role' untuk mobile, tanpa 'role' untuk admin)
Route::post('/login', [AuthController::class, 'unifiedLogin']);

// Presensi Mesin Solution Cloud (public - tidak perlu auth)
Route::prefix('presensi-mesin')->group(function () {
    Route::post('/fetch-all', [PresensiMesinController::class, 'fetchAll']);
    Route::post('/fetch-by-index', [PresensiMesinController::class, 'fetchByIndex']);
});

// ─── Protected Routes (Sanctum Auth) ─────────────────────────────────────────
Route::middleware('auth:sanctum')->group(function () {

    // Auth (Web & Mobile)
    Route::post('/auth/logout', [AuthController::class, 'logout']);
    Route::get('/auth/me', [AuthController::class, 'me']);

    // Mobile Auth (protected)
    Route::post('/mobile/logout', [AuthController::class, 'logout']);
    Route::get('/mobile/me', [AuthController::class, 'me']);
    Route::post('/mobile/change-password', [AuthController::class, 'changePassword']);

    // Dashboard Admin
    Route::get('/dashboard', [DashboardController::class, 'getStats']);

    // Dashboard Siswa (Flutter)
    Route::get('/dashboard/siswa', [DashboardController::class, 'getSiswaDashboard']);
    Route::get('/siswa/akademik', [\App\Http\Controllers\Api\WaliController::class, 'akademik']);

    // Dashboard & Tagihan Wali (Flutter)
    Route::get('/wali/dashboard', [\App\Http\Controllers\Api\WaliController::class, 'dashboard']);
    Route::get('/wali/tagihan', [\App\Http\Controllers\Api\WaliController::class, 'tagihan']);
    Route::get('/wali/akademik', [\App\Http\Controllers\Api\WaliController::class, 'akademik']);

    // Dashboard Guru (Flutter)
    Route::get('/guru/jadwal-hari-ini', [\App\Http\Controllers\Api\GuruDashboardController::class, 'jadwalHariIni']);
    Route::get('/guru/jurnal-hari-ini', [\App\Http\Controllers\Api\GuruDashboardController::class, 'jurnalHariIni']);
    Route::get('/guru/jurnal-guru', [\App\Http\Controllers\Api\GuruDashboardController::class, 'jurnalGuru']);

    // Profil Lengkap Siswa (Flutter Mobile)
    Route::get('/mobile/siswa/profil', [\App\Http\Controllers\Api\SiswaProfilController::class, 'show']);
    Route::post('/mobile/siswa/profil', [\App\Http\Controllers\Api\SiswaProfilController::class, 'update']);
    Route::post('/mobile/siswa/foto', [\App\Http\Controllers\Api\SiswaProfilController::class, 'uploadFoto']);
    Route::get('/mobile/siswa/edit-akses', [\App\Http\Controllers\Api\SiswaProfilController::class, 'editAkses']);
    Route::get('/mobile/siswa/riwayat-kesehatan', [\App\Http\Controllers\Api\SiswaProfilController::class, 'getRiwayatKesehatan']);
    Route::post('/mobile/siswa/riwayat-kesehatan', [\App\Http\Controllers\Api\SiswaProfilController::class, 'storeRiwayatKesehatan']);
    Route::get('/siswa/log-kesehatan', [\App\Http\Controllers\Api\SiswaLogKesehatanController::class, 'index']);

    // Profil Guru (Flutter Mobile) — untuk foto_url & update no_hp
    Route::get('/mobile/guru/profil', [\App\Http\Controllers\Api\GuruProfilController::class, 'show']);
    Route::post('/mobile/guru/profil', [\App\Http\Controllers\Api\GuruProfilController::class, 'update']);
    Route::post('/mobile/guru/foto', [\App\Http\Controllers\Api\GuruProfilController::class, 'uploadFoto']);

    // Profil Karyawan (Flutter Mobile) — untuk foto_url
    Route::get('/mobile/karyawan/profil', [\App\Http\Controllers\Api\KaryawanProfilController::class, 'show']);
    Route::post('/mobile/karyawan/foto', [\App\Http\Controllers\Api\KaryawanProfilController::class, 'uploadFoto']);

    // Log Kesehatan UKS Guru & Karyawan (Flutter Mobile)
    Route::get('/gukar/log-kesehatan', [\App\Http\Controllers\Api\GukarLogKesehatanController::class, 'index']);

    // Gaya Belajar Siswa — Self-Assessment (Flutter Mobile)
    Route::get('/mobile/siswa/gaya-belajar', [\App\Http\Controllers\Api\SiswaGayaBelajarController::class, 'show']);
    Route::post('/mobile/siswa/gaya-belajar/submit', [\App\Http\Controllers\Api\SiswaGayaBelajarController::class, 'submit']);

    // Toggle edit detail siswa (admin only)
    Route::post('/admin/sekolah/toggle-edit-siswa', [\App\Http\Controllers\Api\SekolahController::class, 'toggleEditDetailSiswa']);

    // =========================================================================
    //  ATUR DATA
    // =========================================================================

    // 1. Data Sekolah
    Route::prefix('atur-data/sekolah')->group(function () {
        Route::get('/', [\App\Http\Controllers\Api\SekolahController::class, 'index']);
        Route::post('/', [\App\Http\Controllers\Api\SekolahController::class, 'store']);
        Route::get('/{id}', [\App\Http\Controllers\Api\SekolahController::class, 'show']);
        Route::post('/{id}', [\App\Http\Controllers\Api\SekolahController::class, 'update']);   // POST karena multipart/form-data
        Route::delete('/{id}', [\App\Http\Controllers\Api\SekolahController::class, 'destroy']);
    });

    // 1b. Backup & Restore Database
    Route::prefix('atur-data/backup-restore')->group(function () {
        Route::get('/', [\App\Http\Controllers\Api\BackupRestoreController::class, 'index']);
        Route::get('/export', [\App\Http\Controllers\Api\BackupRestoreController::class, 'export']);
        Route::post('/upload', [\App\Http\Controllers\Api\BackupRestoreController::class, 'uploadRestore']);
    });

    // 2. Tahun Ajaran & Semester
    Route::prefix('atur-data/tahun-ajaran')->group(function () {
        Route::get('/', [\App\Http\Controllers\Api\TahunSemesterController::class, 'indexTahun']);
        Route::post('/', [\App\Http\Controllers\Api\TahunSemesterController::class, 'storeTahun']);
        Route::get('/{id}', [\App\Http\Controllers\Api\TahunSemesterController::class, 'showTahun']);
        Route::put('/{id}', [\App\Http\Controllers\Api\TahunSemesterController::class, 'updateTahun']);
        Route::delete('/{id}', [\App\Http\Controllers\Api\TahunSemesterController::class, 'destroyTahun']);
    });

    Route::prefix('atur-data/semester')->group(function () {
        Route::get('/', [\App\Http\Controllers\Api\TahunSemesterController::class, 'indexSemester']);
        Route::post('/', [\App\Http\Controllers\Api\TahunSemesterController::class, 'storeSemester']);
        Route::get('/{id}', [\App\Http\Controllers\Api\TahunSemesterController::class, 'showSemester']);
        Route::put('/{id}', [\App\Http\Controllers\Api\TahunSemesterController::class, 'updateSemester']);
        Route::delete('/{id}', [\App\Http\Controllers\Api\TahunSemesterController::class, 'destroySemester']);
        Route::patch('/{id}/aktivasi', [\App\Http\Controllers\Api\TahunSemesterController::class, 'aktivasiSemester']);
    });

    // 3. Data Jurusan
    Route::prefix('atur-data/jurusan')->group(function () {
        Route::get('/', [\App\Http\Controllers\Api\JurusanController::class, 'index']);
        Route::post('/', [\App\Http\Controllers\Api\JurusanController::class, 'store']);
        Route::get('/{id}', [\App\Http\Controllers\Api\JurusanController::class, 'show']);
        Route::put('/{id}', [\App\Http\Controllers\Api\JurusanController::class, 'update']);
        Route::delete('/{id}', [\App\Http\Controllers\Api\JurusanController::class, 'destroy']);
    });

    // 4. Data Kelas
    Route::prefix('atur-data/kelas')->group(function () {
        Route::get('/', [\App\Http\Controllers\Api\KelasController::class, 'index']);
        Route::post('/', [\App\Http\Controllers\Api\KelasController::class, 'store']);
        Route::get('/{id}', [\App\Http\Controllers\Api\KelasController::class, 'show']);
        Route::put('/{id}', [\App\Http\Controllers\Api\KelasController::class, 'update']);
        Route::delete('/{id}', [\App\Http\Controllers\Api\KelasController::class, 'destroy']);
    });

    // 5. Data Siswa
    Route::prefix('atur-data/siswa')->group(function () {
        Route::get('/', [\App\Http\Controllers\Api\SiswaController::class, 'index']);
        Route::post('/', [\App\Http\Controllers\Api\SiswaController::class, 'store']);
        Route::get('/{nis}', [\App\Http\Controllers\Api\SiswaController::class, 'show']);
        Route::put('/{nis}', [\App\Http\Controllers\Api\SiswaController::class, 'update']);
        Route::delete('/{nis}', [\App\Http\Controllers\Api\SiswaController::class, 'destroy']);
        Route::patch('/{nis}/reset-password', [\App\Http\Controllers\Api\SiswaController::class, 'resetPassword']);
    });

    // 6. Data Guru
    Route::prefix('atur-data/guru')->group(function () {
        Route::get('/', [\App\Http\Controllers\Api\GuruController::class, 'index']);
        Route::post('/', [\App\Http\Controllers\Api\GuruController::class, 'store']);
        Route::get('/{id}', [\App\Http\Controllers\Api\GuruController::class, 'show']);
        Route::put('/{id}', [\App\Http\Controllers\Api\GuruController::class, 'update']);
        Route::delete('/{id}', [\App\Http\Controllers\Api\GuruController::class, 'destroy']);
        Route::patch('/{id}/reset-password', [\App\Http\Controllers\Api\GuruController::class, 'resetPassword']);
    });

    // 7. Data Mata Pelajaran
    Route::prefix('atur-data/mapel')->group(function () {
        Route::get('/', [\App\Http\Controllers\Api\MapelController::class, 'index']);
        Route::post('/', [\App\Http\Controllers\Api\MapelController::class, 'store']);
        Route::get('/{id}', [\App\Http\Controllers\Api\MapelController::class, 'show']);
        Route::put('/{id}', [\App\Http\Controllers\Api\MapelController::class, 'update']);
        Route::delete('/{id}', [\App\Http\Controllers\Api\MapelController::class, 'destroy']);
    });

    // 8. Data Wali Amanah (Wali Kelas)
    Route::prefix('atur-data/wali-kelas')->group(function () {
        Route::get('/', [\App\Http\Controllers\Api\WaliKelasController::class, 'index']);
        Route::get('/guru-tersedia', [\App\Http\Controllers\Api\WaliKelasController::class, 'guruTersedia']);
        Route::post('/{id_kelas}/tetapkan', [\App\Http\Controllers\Api\WaliKelasController::class, 'tetapkan']);
        Route::delete('/{id_kelas}/lepas', [\App\Http\Controllers\Api\WaliKelasController::class, 'lepas']);
    });

    // 9. Data Mesin Finger
    Route::prefix('atur-data/mesin-finger')->group(function () {
        Route::get('/', [\App\Http\Controllers\Api\DataMesinController::class, 'index']);
        Route::post('/', [\App\Http\Controllers\Api\DataMesinController::class, 'store']);
        Route::get('/{id}', [\App\Http\Controllers\Api\DataMesinController::class, 'show']);
        Route::put('/{id}', [\App\Http\Controllers\Api\DataMesinController::class, 'update']);
        Route::delete('/{id}', [\App\Http\Controllers\Api\DataMesinController::class, 'destroy']);
    });

    // 10. Tarik Data Finger (Log Absensi)
    Route::prefix('atur-data/tarik-finger')->group(function () {
        Route::get('/', [\App\Http\Controllers\Api\TarikDataFingerController::class, 'index']);
        Route::get('/rekap-mesin', [\App\Http\Controllers\Api\TarikDataFingerController::class, 'rekapMesin']);
        Route::post('/sinkronkan', [\App\Http\Controllers\Api\TarikDataFingerController::class, 'sinkronkan']);
        Route::delete('/hapus', [\App\Http\Controllers\Api\TarikDataFingerController::class, 'hapusByTanggal']);
    });

    // 11. Data Karyawan
    Route::prefix('atur-data/karyawan')->group(function () {
        Route::get('/', [\App\Http\Controllers\Api\KaryawanController::class, 'index']);
        Route::post('/', [\App\Http\Controllers\Api\KaryawanController::class, 'store']);
        Route::get('/{id}', [\App\Http\Controllers\Api\KaryawanController::class, 'show']);
        Route::put('/{id}', [\App\Http\Controllers\Api\KaryawanController::class, 'update']);
        Route::delete('/{id}', [\App\Http\Controllers\Api\KaryawanController::class, 'destroy']);
        Route::patch('/{id}/reset-password', [\App\Http\Controllers\Api\KaryawanController::class, 'resetPassword']);
    });

    // 12. Jam Pelajaran
    Route::prefix('atur-jam')->group(function () {
        Route::get('/', [\App\Http\Controllers\Api\JamPelajaranController::class, 'index']);
        Route::post('/', [\App\Http\Controllers\Api\JamPelajaranController::class, 'store']);
        Route::get('/{id}', [\App\Http\Controllers\Api\JamPelajaranController::class, 'show']);
        Route::put('/{id}', [\App\Http\Controllers\Api\JamPelajaranController::class, 'update']);
        Route::delete('/{id}', [\App\Http\Controllers\Api\JamPelajaranController::class, 'destroy']);
        Route::post('/update-aktif', [\App\Http\Controllers\Api\JamPelajaranController::class, 'updateAktif']);
    });

    // 13. Jadwal Mengajar Guru
    Route::prefix('jadwal-mengajar')->group(function () {
        Route::get('/template', [\App\Http\Controllers\Api\JadwalMengajarController::class, 'indexTemplate']);
        Route::post('/template', [\App\Http\Controllers\Api\JadwalMengajarController::class, 'storeTemplate']);
        Route::delete('/template/{id}', [\App\Http\Controllers\Api\JadwalMengajarController::class, 'destroyTemplate']);
        Route::post('/generate-harian', [\App\Http\Controllers\Api\JadwalMengajarController::class, 'generateHarian']);
    });

    // 14. Presensi Siswa
    Route::prefix('presensi-siswa')->group(function () {
        Route::get('/', [\App\Http\Controllers\Api\PresensiSiswaController::class, 'index']);
        Route::post('/', [\App\Http\Controllers\Api\PresensiSiswaController::class, 'store']);
        Route::get('/rekap', [\App\Http\Controllers\Api\PresensiSiswaController::class, 'rekap']);
        Route::get('/rekap/pdf', [\App\Http\Controllers\Api\PresensiSiswaController::class, 'rekapPdf']);
        Route::get('/by-month-year-nis', [\App\Http\Controllers\Api\PresensiSiswaController::class, 'getByMonthYearNis']);
        Route::get('/by-tanggal-nis', [\App\Http\Controllers\Api\PresensiSiswaController::class, 'getByTanggalNis']);
        Route::post('/bulk', [\App\Http\Controllers\Api\PresensiSiswaController::class, 'inputBulk']);
        Route::get('/{id}', [\App\Http\Controllers\Api\PresensiSiswaController::class, 'show']);
        Route::put('/{id}', [\App\Http\Controllers\Api\PresensiSiswaController::class, 'update']);
        Route::delete('/{id}', [\App\Http\Controllers\Api\PresensiSiswaController::class, 'destroy']);
    });

    // 14b. Presensi Siswa — Kalender Bulanan & PDF (endpoint khusus Flutter)
    Route::get('/presensi/siswa', [\App\Http\Controllers\Api\PresensiSiswaController::class, 'getMonthlyCalendar']);
    Route::get('/presensi/rekap/pdf', [\App\Http\Controllers\Api\PresensiSiswaController::class, 'rekapPdf']);


    // 15. Jurnal Guru
    Route::prefix('jurnal-guru')->group(function () {
        Route::get('/', [\App\Http\Controllers\Api\JurnalGuruController::class, 'index']);
        Route::post('/', [\App\Http\Controllers\Api\JurnalGuruController::class, 'store']);
        Route::get('/{id}', [\App\Http\Controllers\Api\JurnalGuruController::class, 'show']);
        Route::put('/{id}', [\App\Http\Controllers\Api\JurnalGuruController::class, 'update']);
        Route::delete('/{id}', [\App\Http\Controllers\Api\JurnalGuruController::class, 'destroy']);
        Route::post('/{id}/approve', [\App\Http\Controllers\Api\JurnalGuruController::class, 'approve']);
        Route::post('/{id}/reject', [\App\Http\Controllers\Api\JurnalGuruController::class, 'reject']);
    });

    // 15b. Laporan Jurnal Mengajar (untuk dashboard & PDF)
    Route::prefix('jurnal-laporan')->group(function () {
        Route::get('/dashboard', [\App\Http\Controllers\Api\JurnalLaporanController::class, 'dashboard']);
        Route::get('/report', [\App\Http\Controllers\Api\JurnalLaporanController::class, 'getReportData']);
        Route::get('/pdf', [\App\Http\Controllers\Api\JurnalLaporanController::class, 'downloadPdf']);
    });

    // 16. ISMUBA / Keagamaan
    Route::prefix('ismuba')->group(function () {
        // BTAQ
        Route::get('/btaq', [\App\Http\Controllers\Api\BtaqController::class, 'index']);
        Route::get('/btaq/master-data', [\App\Http\Controllers\Api\BtaqController::class, 'masterData']);
        Route::get('/btaq/by-nis/{nis}', [\App\Http\Controllers\Api\BtaqController::class, 'byNis']);
        Route::post('/btaq', [\App\Http\Controllers\Api\BtaqController::class, 'store']);
        Route::get('/btaq/{id}', [\App\Http\Controllers\Api\BtaqController::class, 'show']);
        Route::put('/btaq/{id}', [\App\Http\Controllers\Api\BtaqController::class, 'update']);
        Route::delete('/btaq/{id}', [\App\Http\Controllers\Api\BtaqController::class, 'destroy']);
    });

    // 16b. BTAQ — Endpoint khusus Flutter (siswa login / latest)
    Route::get('/btaq/siswa', [\App\Http\Controllers\Api\BtaqController::class, 'forSiswa']);
    Route::get('/btaq/latest', [\App\Http\Controllers\Api\BtaqController::class, 'latest']);

    // 16c. ISMUBA lanjutan
    Route::prefix('ismuba')->group(function () {
        // Tadarus
        Route::get('/tadarus', [\App\Http\Controllers\Api\TadarusController::class, 'index']);
        Route::post('/tadarus', [\App\Http\Controllers\Api\TadarusController::class, 'store']);
        Route::get('/tadarus/{id}', [\App\Http\Controllers\Api\TadarusController::class, 'show']);
        Route::put('/tadarus/{id}', [\App\Http\Controllers\Api\TadarusController::class, 'update']);
        Route::delete('/tadarus/{id}', [\App\Http\Controllers\Api\TadarusController::class, 'destroy']);

        // Ibadah
        Route::get('/ibadah', [\App\Http\Controllers\Api\IbadahController::class, 'index']);
        Route::post('/ibadah', [\App\Http\Controllers\Api\IbadahController::class, 'store']);
        Route::get('/ibadah/{id}', [\App\Http\Controllers\Api\IbadahController::class, 'show']);
        Route::put('/ibadah/{id}', [\App\Http\Controllers\Api\IbadahController::class, 'update']);
        Route::delete('/ibadah/{id}', [\App\Http\Controllers\Api\IbadahController::class, 'destroy']);

        // Jadwal Pengajian
        Route::get('/jadwal-pengajian', [\App\Http\Controllers\Api\JadwalPengajianController::class, 'index']);
        Route::post('/jadwal-pengajian', [\App\Http\Controllers\Api\JadwalPengajianController::class, 'store']);
        Route::get('/jadwal-pengajian/{id}', [\App\Http\Controllers\Api\JadwalPengajianController::class, 'show']);
        Route::put('/jadwal-pengajian/{id}', [\App\Http\Controllers\Api\JadwalPengajianController::class, 'update']);
        Route::delete('/jadwal-pengajian/{id}', [\App\Http\Controllers\Api\JadwalPengajianController::class, 'destroy']);
        Route::post('/jadwal-pengajian/{id}/kehadiran', [\App\Http\Controllers\Api\JadwalPengajianController::class, 'updateKehadiran']);
        Route::get('/pengajian/aktif', [\App\Http\Controllers\Api\JadwalPengajianController::class, 'aktif']);
        Route::post('/pengajian/absen', [\App\Http\Controllers\Api\JadwalPengajianController::class, 'absen']);

        // Presensi Siswa
        Route::get('/presensi', [\App\Http\Controllers\Api\PresensiController::class, 'index']);
        Route::get('/presensi/by-nis/{nis}', [\App\Http\Controllers\Api\PresensiController::class, 'byNis']);
        Route::post('/presensi', [\App\Http\Controllers\Api\PresensiController::class, 'store']);
        Route::get('/presensi/{id}', [\App\Http\Controllers\Api\PresensiController::class, 'show']);
        Route::put('/presensi/{id}', [\App\Http\Controllers\Api\PresensiController::class, 'update']);
        Route::delete('/presensi/{id}', [\App\Http\Controllers\Api\PresensiController::class, 'destroy']);

        // Laporan ISMUBA
        Route::get('/laporan', [\App\Http\Controllers\Api\LaporanIsmubaController::class, 'index']);
        Route::get('/dashboard', [\App\Http\Controllers\Api\LaporanIsmubaController::class, 'dashboard']);
    });

    // 17. BK (Bimbingan Konseling)
    Route::prefix('bk')->group(function () {
        Route::get('/dashboard', [\App\Http\Controllers\Api\BkDashboardController::class, 'index']);

        // ── Search/Autocomplete endpoints (must be before resource routes) ─────
        Route::get('/search-siswa', [\App\Http\Controllers\Api\SiswaController::class, 'searchSiswa']);
        Route::get('/kategori-pelanggaran/search', [\App\Http\Controllers\Api\KategoriPelanggaranController::class, 'search']);
        Route::get('/kategori-reward/search', [\App\Http\Controllers\Api\KategoriRewardController::class, 'search']);

        // Kategori Pelanggaran
        Route::get('/kategori-pelanggaran', [\App\Http\Controllers\Api\KategoriPelanggaranController::class, 'index']);
        Route::post('/kategori-pelanggaran', [\App\Http\Controllers\Api\KategoriPelanggaranController::class, 'store']);
        Route::get('/kategori-pelanggaran/{id}', [\App\Http\Controllers\Api\KategoriPelanggaranController::class, 'show']);
        Route::put('/kategori-pelanggaran/{id}', [\App\Http\Controllers\Api\KategoriPelanggaranController::class, 'update']);
        Route::delete('/kategori-pelanggaran/{id}', [\App\Http\Controllers\Api\KategoriPelanggaranController::class, 'destroy']);

        // Kategori Reward
        Route::get('/kategori-reward', [\App\Http\Controllers\Api\KategoriRewardController::class, 'index']);
        Route::post('/kategori-reward', [\App\Http\Controllers\Api\KategoriRewardController::class, 'store']);
        Route::get('/kategori-reward/{id}', [\App\Http\Controllers\Api\KategoriRewardController::class, 'show']);
        Route::put('/kategori-reward/{id}', [\App\Http\Controllers\Api\KategoriRewardController::class, 'update']);
        Route::delete('/kategori-reward/{id}', [\App\Http\Controllers\Api\KategoriRewardController::class, 'destroy']);

        // Pelanggaran
        Route::get('/pelanggaran', [\App\Http\Controllers\Api\PelanggaranController::class, 'index']);
        Route::post('/pelanggaran', [\App\Http\Controllers\Api\PelanggaranController::class, 'store']);
        Route::get('/pelanggaran/{id}', [\App\Http\Controllers\Api\PelanggaranController::class, 'show']);
        Route::delete('/pelanggaran/{id}', [\App\Http\Controllers\Api\PelanggaranController::class, 'destroy']);

        // Reward
        Route::get('/reward', [\App\Http\Controllers\Api\RewardController::class, 'index']);
        Route::post('/reward', [\App\Http\Controllers\Api\RewardController::class, 'store']);
        Route::get('/reward/{id}', [\App\Http\Controllers\Api\RewardController::class, 'show']);
        Route::delete('/reward/{id}', [\App\Http\Controllers\Api\RewardController::class, 'destroy']);

        // Buku Kasus
        Route::get('/buku-kasus', [\App\Http\Controllers\Api\BukuKasusController::class, 'index']);
        Route::post('/buku-kasus', [\App\Http\Controllers\Api\BukuKasusController::class, 'store']);
        Route::get('/buku-kasus/{id}', [\App\Http\Controllers\Api\BukuKasusController::class, 'show']);
        Route::put('/buku-kasus/{id}', [\App\Http\Controllers\Api\BukuKasusController::class, 'update']);
        Route::delete('/buku-kasus/{id}', [\App\Http\Controllers\Api\BukuKasusController::class, 'destroy']);

        // Buku Konsultasi
        Route::get('/buku-konsultasi', [\App\Http\Controllers\Api\BukuKonsulasiController::class, 'index']);
        Route::post('/buku-konsultasi', [\App\Http\Controllers\Api\BukuKonsulasiController::class, 'store']);
        Route::get('/buku-konsultasi/{id}', [\App\Http\Controllers\Api\BukuKonsulasiController::class, 'show']);
        Route::put('/buku-konsultasi/{id}', [\App\Http\Controllers\Api\BukuKonsulasiController::class, 'update']);
        Route::delete('/buku-konsultasi/{id}', [\App\Http\Controllers\Api\BukuKonsulasiController::class, 'destroy']);

        // Home Visit
        Route::get('/home-visit', [\App\Http\Controllers\Api\HomeVisitController::class, 'index']);
        Route::post('/home-visit', [\App\Http\Controllers\Api\HomeVisitController::class, 'store']);
        Route::get('/home-visit/{id}', [\App\Http\Controllers\Api\HomeVisitController::class, 'show']);
        Route::put('/home-visit/{id}', [\App\Http\Controllers\Api\HomeVisitController::class, 'update']);
        Route::delete('/home-visit/{id}', [\App\Http\Controllers\Api\HomeVisitController::class, 'destroy']);

        // Panggil Ortu
        Route::get('/panggil-ortu/siswa-detail', [\App\Http\Controllers\Api\PanggilOrtuController::class, 'getSiswaDetail']);
        Route::post('/panggil-ortu/preview', [\App\Http\Controllers\Api\PanggilOrtuController::class, 'previewPdf']);
        Route::get('/panggil-ortu/{id}/pdf', [\App\Http\Controllers\Api\PanggilOrtuController::class, 'downloadPdf']);
        Route::get('/panggil-ortu', [\App\Http\Controllers\Api\PanggilOrtuController::class, 'index']);
        Route::post('/panggil-ortu', [\App\Http\Controllers\Api\PanggilOrtuController::class, 'store']);
        Route::get('/panggil-ortu/{id}', [\App\Http\Controllers\Api\PanggilOrtuController::class, 'show']);
        Route::put('/panggil-ortu/{id}', [\App\Http\Controllers\Api\PanggilOrtuController::class, 'update']);
        Route::delete('/panggil-ortu/{id}', [\App\Http\Controllers\Api\PanggilOrtuController::class, 'destroy']);

        // Gaya Belajar
        Route::get('/gaya-belajar', [\App\Http\Controllers\Api\GayaBelajarController::class, 'index']);

        // Riwayat Poin & Reward (dari tabel riwayat_poin & riwayat_reward)
        Route::get('/riwayat-poin',    [\App\Http\Controllers\Api\BkRiwayatController::class, 'riwayatPoin']);
        Route::get('/riwayat-reward',  [\App\Http\Controllers\Api\BkRiwayatController::class, 'riwayatReward']);
        Route::get('/rekap-summary',   [\App\Http\Controllers\Api\BkRiwayatController::class, 'rekapSummary']);
        Route::post('/gaya-belajar', [\App\Http\Controllers\Api\GayaBelajarController::class, 'store']);
        Route::get('/gaya-belajar/{id}', [\App\Http\Controllers\Api\GayaBelajarController::class, 'show']);
        Route::delete('/gaya-belajar/{id}', [\App\Http\Controllers\Api\GayaBelajarController::class, 'destroy']);
    });

    Route::get('/kunjungan_uks/siswa', [\App\Http\Controllers\Api\UksKunjunganController::class, 'getKunjunganSiswaHistory']);

    // 18. UKS
    Route::prefix('uks')->group(function () {
        // Jenis Checkup
        Route::get('/jenis-checkup', [\App\Http\Controllers\Api\UksJenisCheckupController::class, 'index']);
        Route::post('/jenis-checkup', [\App\Http\Controllers\Api\UksJenisCheckupController::class, 'store']);
        Route::put('/jenis-checkup/{id}', [\App\Http\Controllers\Api\UksJenisCheckupController::class, 'update']);
        Route::delete('/jenis-checkup/{id}', [\App\Http\Controllers\Api\UksJenisCheckupController::class, 'destroy']);

        // Kunjungan
        Route::get('/kunjungan', [\App\Http\Controllers\Api\UksKunjunganController::class, 'index']);
        Route::post('/kunjungan', [\App\Http\Controllers\Api\UksKunjunganController::class, 'store']);
        Route::get('/kunjungan/{id}', [\App\Http\Controllers\Api\UksKunjunganController::class, 'show']);
        Route::put('/kunjungan/{id}', [\App\Http\Controllers\Api\UksKunjunganController::class, 'update']);
        Route::delete('/kunjungan/{id}', [\App\Http\Controllers\Api\UksKunjunganController::class, 'destroy']);

        // Checkup
        Route::get('/checkup', [\App\Http\Controllers\Api\UksCheckupController::class, 'index']);
        Route::post('/checkup', [\App\Http\Controllers\Api\UksCheckupController::class, 'store']);
        Route::get('/checkup/template/{id_kelas}', [\App\Http\Controllers\Api\UksCheckupController::class, 'downloadTemplate']);
        Route::post('/checkup/import', [\App\Http\Controllers\Api\UksCheckupController::class, 'importByKelas']);
        Route::get('/checkup/{id}', [\App\Http\Controllers\Api\UksCheckupController::class, 'show']);
        Route::put('/checkup/{id}', [\App\Http\Controllers\Api\UksCheckupController::class, 'update']);
        Route::delete('/checkup/{id}', [\App\Http\Controllers\Api\UksCheckupController::class, 'destroy']);

        // Checkup Gukar (Guru & Karyawan)
        Route::get('/gukar-list', [\App\Http\Controllers\Api\UksCheckupGukarController::class, 'gukarList']);
        Route::get('/checkup-gukar', [\App\Http\Controllers\Api\UksCheckupGukarController::class, 'index']);
        Route::post('/checkup-gukar', [\App\Http\Controllers\Api\UksCheckupGukarController::class, 'store']);
        Route::put('/checkup-gukar/{id}', [\App\Http\Controllers\Api\UksCheckupGukarController::class, 'update']);
        Route::delete('/checkup-gukar/{id}', [\App\Http\Controllers\Api\UksCheckupGukarController::class, 'destroy']);

        // Laporan
        Route::get('/laporan', [\App\Http\Controllers\Api\UksLaporanController::class, 'index']);

        // Dashboard Stats & Data Kesehatan Summary
        Route::get('/dashboard', [\App\Http\Controllers\Api\UksDashboardController::class, 'index']);
    });

    // 19. PKL (Praktik Kerja Lapangan)
    Route::prefix('pkl')->group(function () {
        // Gelombang
        Route::get('/gelombang', [\App\Http\Controllers\Api\PklGelombangController::class, 'index']);
        Route::post('/gelombang', [\App\Http\Controllers\Api\PklGelombangController::class, 'store']);
        Route::get('/gelombang/{id}', [\App\Http\Controllers\Api\PklGelombangController::class, 'show']);
        Route::put('/gelombang/{id}', [\App\Http\Controllers\Api\PklGelombangController::class, 'update']);
        Route::delete('/gelombang/{id}', [\App\Http\Controllers\Api\PklGelombangController::class, 'destroy']);

        // DUDI
        Route::get('/dudi', [\App\Http\Controllers\Api\PklDudiController::class, 'index']);
        Route::post('/dudi', [\App\Http\Controllers\Api\PklDudiController::class, 'store']);
        Route::get('/dudi/{id}', [\App\Http\Controllers\Api\PklDudiController::class, 'show']);
        Route::put('/dudi/{id}', [\App\Http\Controllers\Api\PklDudiController::class, 'update']);
        Route::delete('/dudi/{id}', [\App\Http\Controllers\Api\PklDudiController::class, 'destroy']);

        // Pembimbing
        Route::get('/pembimbing', [\App\Http\Controllers\Api\PklPembimbingController::class, 'index']);
        Route::post('/pembimbing', [\App\Http\Controllers\Api\PklPembimbingController::class, 'store']);
        Route::get('/pembimbing/{id}', [\App\Http\Controllers\Api\PklPembimbingController::class, 'show']);
        Route::put('/pembimbing/{id}', [\App\Http\Controllers\Api\PklPembimbingController::class, 'update']);
        Route::delete('/pembimbing/{id}', [\App\Http\Controllers\Api\PklPembimbingController::class, 'destroy']);

        // Penempatan
        Route::get('/penempatan', [\App\Http\Controllers\Api\PklPenempatanController::class, 'index']);
        Route::post('/penempatan', [\App\Http\Controllers\Api\PklPenempatanController::class, 'store']);
        Route::get('/penempatan/{id}', [\App\Http\Controllers\Api\PklPenempatanController::class, 'show']);
        Route::put('/penempatan/{id}', [\App\Http\Controllers\Api\PklPenempatanController::class, 'update']);
        Route::delete('/penempatan/{id}', [\App\Http\Controllers\Api\PklPenempatanController::class, 'destroy']);

        // Pindah Penempatan
        Route::get('/pindah-penempatan', [\App\Http\Controllers\Api\PklPindahPenempatanController::class, 'index']);
        Route::post('/pindah-penempatan', [\App\Http\Controllers\Api\PklPindahPenempatanController::class, 'store']);

        // Nomor Surat
        Route::get('/nomor-surat', [\App\Http\Controllers\Api\PklNomorSuratController::class, 'index']);
        Route::put('/nomor-surat/{jenis}', [\App\Http\Controllers\Api\PklNomorSuratController::class, 'update']);
        Route::post('/nomor-surat/{jenis}/reset', [\App\Http\Controllers\Api\PklNomorSuratController::class, 'resetCounter']);

        // Persuratan
        Route::get('/persuratan', [\App\Http\Controllers\Api\PklPersuratanController::class, 'index']);
        Route::post('/persuratan/generate', [\App\Http\Controllers\Api\PklPersuratanController::class, 'generate']);
        Route::get('/persuratan/{id}', [\App\Http\Controllers\Api\PklPersuratanController::class, 'show']);
        Route::delete('/persuratan/{id}', [\App\Http\Controllers\Api\PklPersuratanController::class, 'destroy']);

        // Laporan
        Route::get('/laporan', [\App\Http\Controllers\Api\PklLaporanController::class, 'index']);

        // Siswa Info (untuk siswa login - cek gelombang aktif)
        Route::get('/siswa/info', [\App\Http\Controllers\Api\PklSiswaController::class, 'info']);
    });

    // 20. Generator Soal AI (History & Detail)
    Route::prefix('generator-soal')->group(function () {
        Route::get('/soal', [\App\Http\Controllers\Api\GeneratorSoalController::class, 'historySoal']);
        Route::get('/soal/{id}', [\App\Http\Controllers\Api\GeneratorSoalController::class, 'showSoal']);
        Route::delete('/soal/{id}', [\App\Http\Controllers\Api\GeneratorSoalController::class, 'destroySoal']);
        Route::get('/kisi-kisi', [\App\Http\Controllers\Api\GeneratorSoalController::class, 'historyKisiKisi']);
        Route::get('/kisi-kisi/{id}', [\App\Http\Controllers\Api\GeneratorSoalController::class, 'showKisiKisi']);
        Route::delete('/kisi-kisi/{id}', [\App\Http\Controllers\Api\GeneratorSoalController::class, 'destroyKisiKisi']);
    });

    // 21. LMS (Learning Management System)
    Route::prefix('lms')->group(function () {
        Route::get('/kursus', [\App\Http\Controllers\Api\LmsController::class, 'indexKursus']);
        Route::post('/kursus', [\App\Http\Controllers\Api\LmsController::class, 'storeKursus']);
        Route::put('/kursus/{id}', [\App\Http\Controllers\Api\LmsController::class, 'updateKursus']);
        Route::delete('/kursus/{id}', [\App\Http\Controllers\Api\LmsController::class, 'destroyKursus']);
        Route::get('/tugas-belum', [\App\Http\Controllers\Api\LmsController::class, 'tugasBelum']);
        Route::get('/kursus/{id}/tugas', [\App\Http\Controllers\Api\LmsController::class, 'indexTugas']);
        Route::get('/tugas/{id}', [\App\Http\Controllers\Api\LmsController::class, 'showTugas']);
        Route::post('/tugas', [\App\Http\Controllers\Api\LmsController::class, 'storeTugas']);
        Route::put('/tugas/{id}', [\App\Http\Controllers\Api\LmsController::class, 'updateTugas']);
        Route::post('/tugas/{id}', [\App\Http\Controllers\Api\LmsController::class, 'updateTugas']);  // alias PUT untuk multipart upload
        Route::delete('/tugas/{id}', [\App\Http\Controllers\Api\LmsController::class, 'destroyTugas']);
        Route::post('/tugas/{id}/kumpulkan', [\App\Http\Controllers\Api\LmsController::class, 'kumpulkanTugas']);
        Route::get('/tugas/{id}/submisi', [\App\Http\Controllers\Api\LmsController::class, 'indexSubmisi']);
        Route::post('/submisi/{id}/nilai', [\App\Http\Controllers\Api\LmsController::class, 'nilaiSubmisi']);
    });

    // 22. Prayer Times
    Route::get('/prayer-times/bantul', [\App\Http\Controllers\Api\PrayerTimesController::class, 'getBantulTimes']);
});
