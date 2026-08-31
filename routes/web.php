<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AturData\SekolahController;
use App\Http\Controllers\AturData\WhatsappGatewayController;
use App\Http\Controllers\AturData\BackupRestoreController;
use App\Http\Controllers\AturData\TahunSemesterController;
use App\Http\Controllers\AturData\JurusanController;
use App\Http\Controllers\AturData\KelasController;
use App\Http\Controllers\AturData\SiswaController;
use App\Http\Controllers\AturData\GuruController;
use App\Http\Controllers\AturData\KaryawanController;
use App\Http\Controllers\AturData\MapelController;
use App\Http\Controllers\AturData\WaliKelasController;
use App\Http\Controllers\AturData\MesinFingerController;
use App\Http\Controllers\AturData\UserController;
use App\Http\Controllers\Ismuba\BtaqController;
use App\Http\Controllers\Ismuba\TadarusController;
use App\Http\Controllers\Ismuba\IbadahController;
use App\Http\Controllers\Ismuba\LaporanIsmubaController;
use App\Http\Controllers\Ismuba\JadwalPengajianController;
use App\Http\Controllers\Ismuba\DashboardIsmubaController;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);
});

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/layar-display', [\App\Http\Controllers\DisplayController::class, 'index'])->name('display.index');
    Route::get('/layar-display/data', [\App\Http\Controllers\DisplayController::class, 'getData'])->name('display.data');
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

    // =========================================================================
    //  ATUR DATA
    // =========================================================================

    // 1. Data Sekolah
    Route::prefix('atur-data/sekolah')->name('atur-data.sekolah')->group(function () {
        Route::get('/', [SekolahController::class, 'index'])->name('');
        Route::post('/', [SekolahController::class, 'store'])->name('.store');
        Route::post('/toggle-edit-siswa', [SekolahController::class, 'toggleEditDetailSiswa'])->name('.toggle-edit-siswa');
        Route::post('/{id}', [SekolahController::class, 'update'])->name('.update');
    });

    // 1b. WhatsApp Gateway
    Route::prefix('atur-data/whatsapp-gateway')->name('atur-data.whatsapp-gateway')->group(function () {
        Route::get('/', [WhatsappGatewayController::class, 'index'])->name('');
        Route::post('/update', [WhatsappGatewayController::class, 'update'])->name('.update');
        Route::post('/test', [WhatsappGatewayController::class, 'test'])->name('.test');
        Route::get('/device-status', [WhatsappGatewayController::class, 'deviceStatus'])->name('.device-status');
    });

    // 1c. Backup & Restore Database & Media
    Route::prefix('atur-data/backup-restore')->name('atur-data.backup-restore')->group(function () {
        Route::get('/', [BackupRestoreController::class, 'index'])->name('');
        Route::get('/export', [BackupRestoreController::class, 'export'])->name('.export');
        Route::get('/export-media', [BackupRestoreController::class, 'exportMedia'])->name('.export-media');
        Route::post('/store', [BackupRestoreController::class, 'store'])->name('.store');
        Route::post('/store-media', [BackupRestoreController::class, 'storeMedia'])->name('.store-media');
        Route::post('/upload-restore', [BackupRestoreController::class, 'uploadRestore'])->name('.upload-restore');
        Route::post('/upload-restore-media', [BackupRestoreController::class, 'uploadRestoreMedia'])->name('.upload-restore-media');
        Route::post('/restore-saved/{filename}', [BackupRestoreController::class, 'restoreSaved'])->name('.restore-saved');
        Route::get('/download-saved/{filename}', [BackupRestoreController::class, 'downloadSaved'])->name('.download-saved');
        Route::delete('/{filename}', [BackupRestoreController::class, 'destroy'])->name('.destroy');
    });

    // 1c. Atur User (Manajemen User & Role)
    Route::prefix('atur-data/user')->name('atur-data.user')->group(function () {
        Route::get('/', [UserController::class, 'index'])->name('');
        Route::post('/', [UserController::class, 'store'])->name('.store');
        Route::post('/bulk-destroy', [UserController::class, 'bulkDestroy'])->name('.bulk-destroy');
        Route::post('/{id}/reset-password', [UserController::class, 'resetPassword'])->name('.reset-password');
        Route::post('/{id}', [UserController::class, 'update'])->name('.update');
        Route::delete('/{id}', [UserController::class, 'destroy'])->name('.destroy');
    });

    // 2. Tahun Ajaran & Semester
    Route::prefix('atur-data/tahun-semester')->name('atur-data.tahun-semester')->group(function () {
        Route::get('/', [TahunSemesterController::class, 'index'])->name('');
        Route::post('/tahun', [TahunSemesterController::class, 'storeTahun'])->name('.tahun.store');
        Route::post('/tahun/{id}', [TahunSemesterController::class, 'updateTahun'])->name('.tahun.update');
        Route::delete('/tahun/{id}', [TahunSemesterController::class, 'destroyTahun'])->name('.tahun.destroy');
        Route::post('/semester', [TahunSemesterController::class, 'storeSemester'])->name('.semester.store');
        Route::post('/semester/{id}', [TahunSemesterController::class, 'updateSemester'])->name('.semester.update');
        Route::delete('/semester/{id}', [TahunSemesterController::class, 'destroySemester'])->name('.semester.destroy');
    });

    // 3. Data Jurusan
    Route::prefix('atur-data/jurusan')->name('atur-data.jurusan')->group(function () {
        Route::get('/', [JurusanController::class, 'index'])->name('');
        Route::post('/', [JurusanController::class, 'store'])->name('.store');
        Route::post('/bulk-destroy', [JurusanController::class, 'bulkDestroy'])->name('.bulk-destroy');
        Route::post('/{id}', [JurusanController::class, 'update'])->name('.update');
        Route::delete('/{id}', [JurusanController::class, 'destroy'])->name('.destroy');
    });

    // 4. Data Kelas
    Route::prefix('atur-data/kelas')->name('atur-data.kelas')->group(function () {
        Route::get('/', [KelasController::class, 'index'])->name('');
        Route::get('/tidak-aktif', [KelasController::class, 'indexTidakAktif'])->name('.tidak-aktif');
        Route::get('/json', [KelasController::class, 'indexJson'])->name('.json');
        Route::post('/', [KelasController::class, 'store'])->name('.store');
        Route::post('/naik-tingkat', [KelasController::class, 'naikTingkat'])->name('.naik-tingkat');
        Route::post('/bulk-destroy', [KelasController::class, 'bulkDestroy'])->name('.bulk-destroy');
        Route::post('/{id}', [KelasController::class, 'update'])->name('.update');
        Route::delete('/{id}', [KelasController::class, 'destroy'])->name('.destroy');
    });

    // 5. Data Siswa
    Route::prefix('atur-data/siswa')->name('atur-data.siswa')->group(function () {
        Route::get('/', [SiswaController::class, 'index'])->name('');
        Route::post('/', [SiswaController::class, 'store'])->name('.store');
        Route::get('/import-pilih-kelas', [SiswaController::class, 'importPilihKelas'])->name('.import-pilih-kelas');
        Route::get('/import', [SiswaController::class, 'importForm'])->name('.import');
        Route::post('/import', [SiswaController::class, 'importProcess'])->name('.import-process');
        Route::get('/import-template', [SiswaController::class, 'importTemplate'])->name('.import-template');
        Route::post('/bulk-destroy', [SiswaController::class, 'bulkDestroy'])->name('.bulk-destroy');
        // ⚠ Route statis harus SEBELUM wildcard /{nis}
        Route::post('/toggle-edit-akses', [SiswaController::class, 'toggleEditAkses'])->name('.toggle-edit-akses');
        Route::get('/{nis}/detail', [SiswaController::class, 'show'])->name('.show');
        Route::get('/{nis}/edit-detail', [SiswaController::class, 'editDetail'])->name('.edit-detail');
        Route::post('/{nis}/update-detail', [SiswaController::class, 'updateDetail'])->name('.update-detail');
        Route::post('/{nis}', [SiswaController::class, 'update'])->name('.update');
        Route::post('/{nis}/reset-password', [SiswaController::class, 'resetPassword'])->name('.reset-password');
        Route::delete('/{nis}', [SiswaController::class, 'destroy'])->name('.destroy');
    });

    // 6. Data Guru
    Route::prefix('atur-data/guru')->name('atur-data.guru')->group(function () {
        Route::get('/', [GuruController::class, 'index'])->name('');
        Route::post('/', [GuruController::class, 'store'])->name('.store');
        Route::post('/import', [GuruController::class, 'importProcess'])->name('.import-process');
        Route::get('/import-template', [GuruController::class, 'importTemplate'])->name('.import-template');
        Route::post('/bulk-destroy', [GuruController::class, 'bulkDestroy'])->name('.bulk-destroy');
        Route::post('/{id}', [GuruController::class, 'update'])->name('.update');
        Route::post('/{id}/reset-password', [GuruController::class, 'resetPassword'])->name('.reset-password');
        Route::post('/{id}/upload-foto', [GuruController::class, 'uploadFoto'])->name('.upload-foto');
        Route::delete('/{id}/foto', [GuruController::class, 'deleteFoto'])->name('.delete-foto');
        Route::delete('/{id}', [GuruController::class, 'destroy'])->name('.destroy');
    });

    // 6b. Data Karyawan
    Route::prefix('atur-data/karyawan')->name('atur-data.karyawan')->group(function () {
        Route::get('/', [KaryawanController::class, 'index'])->name('');
        Route::post('/', [KaryawanController::class, 'store'])->name('.store');
        Route::post('/import', [KaryawanController::class, 'importProcess'])->name('.import-process');
        Route::get('/import-template', [KaryawanController::class, 'importTemplate'])->name('.import-template');
        Route::post('/bulk-destroy', [KaryawanController::class, 'bulkDestroy'])->name('.bulk-destroy');
        Route::post('/{id}', [KaryawanController::class, 'update'])->name('.update');
        Route::post('/{id}/reset-password', [KaryawanController::class, 'resetPassword'])->name('.reset-password');
        Route::post('/{id}/upload-foto', [KaryawanController::class, 'uploadFoto'])->name('.upload-foto');
        Route::delete('/{id}/foto', [KaryawanController::class, 'deleteFoto'])->name('.delete-foto');
        Route::delete('/{id}', [KaryawanController::class, 'destroy'])->name('.destroy');
    });

    // 7. Data Mata Pelajaran
    Route::prefix('atur-data/mapel')->name('atur-data.mapel')->group(function () {
        Route::get('/', [MapelController::class, 'index'])->name('');
        Route::post('/', [MapelController::class, 'store'])->name('.store');
        Route::post('/import', [MapelController::class, 'importProcess'])->name('.import-process');
        Route::get('/import-template', [MapelController::class, 'importTemplate'])->name('.import-template');
        Route::post('/bulk-destroy', [MapelController::class, 'bulkDestroy'])->name('.bulk-destroy');
        Route::post('/{id}', [MapelController::class, 'update'])->name('.update');
        Route::delete('/{id}', [MapelController::class, 'destroy'])->name('.destroy');
    });

    // 8. Data Wali Amanah (Wali Kelas)
    Route::prefix('atur-data/wali-kelas')->name('atur-data.wali-kelas')->group(function () {
        Route::get('/', [WaliKelasController::class, 'index'])->name('');
        Route::post('/{id_kelas}/tetapkan', [WaliKelasController::class, 'tetapkan'])->name('.tetapkan');
        Route::post('/{id_kelas}/lepas', [WaliKelasController::class, 'lepas'])->name('.lepas');
    });

    // 9. Data Mesin Finger
    Route::prefix('atur-data/mesin-finger')->name('atur-data.mesin-finger')->group(function () {
        Route::get('/', [MesinFingerController::class, 'index'])->name('');
        Route::post('/', [MesinFingerController::class, 'store'])->name('.store');
        Route::post('/{id}', [MesinFingerController::class, 'update'])->name('.update');
        Route::delete('/{id}', [MesinFingerController::class, 'destroy'])->name('.destroy');
    });

    // 10. Tarik Data Finger
    Route::prefix('atur-data/tarik-finger')->name('atur-data.tarik-finger')->group(function () {
        Route::get('/', [MesinFingerController::class, 'tarikIndex'])->name('');
        Route::post('/sinkronkan', [MesinFingerController::class, 'sinkronkan'])->name('.sinkronkan');
        Route::post('/hapus', [MesinFingerController::class, 'hapusByTanggal'])->name('.hapus');
        Route::post('/update-schedule', [MesinFingerController::class, 'updateSchedule'])->name('.update-schedule');
        Route::post('/hapus-mesin', [MesinFingerController::class, 'hapusDataMesinFinger'])->name('.hapus-mesin');
        Route::post('/hapus-mesin/{id}', [MesinFingerController::class, 'hapusSingleMesin'])->name('.hapus-mesin-single');
        Route::post('/tarik-proses', [MesinFingerController::class, 'tarikProses'])->name('.tarik-proses');
    });

    // 11. Presensi Siswa
    Route::prefix('presensi-siswa')->name('presensi-siswa.')->group(function () {
        Route::get('/input', [\App\Http\Controllers\PresensiSiswaController::class, 'inputIndex'])->name('input');
        Route::post('/input', [\App\Http\Controllers\PresensiSiswaController::class, 'inputStore'])->name('input.store');
        Route::get('/rekap', [\App\Http\Controllers\PresensiSiswaController::class, 'rekapIndex'])->name('rekap');
        Route::get('/rekap/print', [\App\Http\Controllers\PresensiSiswaController::class, 'rekapPrint'])->name('rekap.print');
        Route::get('/laporan', [\App\Http\Controllers\PresensiSiswaController::class, 'laporanIndex'])->name('laporan');
        Route::get('/laporan/export-excel', [\App\Http\Controllers\PresensiSiswaController::class, 'laporanExportExcel'])->name('laporan.export-excel');
        Route::get('/laporan/print', [\App\Http\Controllers\PresensiSiswaController::class, 'laporanPrint'])->name('laporan.print');
        Route::get('/wa-monitoring', [\App\Http\Controllers\WaPresensiController::class, 'index'])->name('wa-monitoring');
        Route::post('/wa-monitoring/send-masal', [\App\Http\Controllers\WaPresensiController::class, 'sendMasal'])->name('wa-monitoring.send-masal');
        Route::post('/wa-monitoring/send-single', [\App\Http\Controllers\WaPresensiController::class, 'sendSingle'])->name('wa-monitoring.send-single');
        Route::post('/wa-monitoring/update-template', [\App\Http\Controllers\WaPresensiController::class, 'updateTemplate'])->name('wa-monitoring.update-template');
        Route::post('/wa-monitoring/update-no-wa', [\App\Http\Controllers\WaPresensiController::class, 'updateNoWa'])->name('wa-monitoring.update-no-wa');
        Route::post('/wa-monitoring/reset-status', [\App\Http\Controllers\WaPresensiController::class, 'resetStatusWa'])->name('wa-monitoring.reset-status');
    });

    // 12. Jurnal Guru
    Route::prefix('jurnal-guru')->name('jurnal-guru.')->group(function () {
        Route::get('/', [\App\Http\Controllers\JurnalGuruController::class, 'index'])->name('index');
        Route::post('/', [\App\Http\Controllers\JurnalGuruController::class, 'store'])->name('store');
        // Laporan Jurnal
        Route::get('/laporan', [\App\Http\Controllers\LaporanJurnalController::class, 'index'])->name('laporan');
        // AJAX: students by class — must be before /{id} routes
        Route::get('/students/{id_kelas}', [\App\Http\Controllers\JurnalGuruController::class, 'getStudentsByKelas'])->name('students');
        Route::post('/{id}/approve', [\App\Http\Controllers\JurnalGuruController::class, 'approve'])->name('approve');
        Route::post('/{id}/reject', [\App\Http\Controllers\JurnalGuruController::class, 'reject'])->name('reject');
        Route::post('/{id}', [\App\Http\Controllers\JurnalGuruController::class, 'update'])->name('update');
        Route::delete('/{id}', [\App\Http\Controllers\JurnalGuruController::class, 'destroy'])->name('destroy');
    });


    // 14. Atur Jam Pelajaran
    Route::prefix('atur-jam')->name('atur-jam.')->group(function () {
        Route::get('/', [\App\Http\Controllers\JamPelajaranController::class, 'index'])->name('index');
        Route::post('/', [\App\Http\Controllers\JamPelajaranController::class, 'store'])->name('store');
        Route::post('/update-aktif', [\App\Http\Controllers\JamPelajaranController::class, 'updateAktif'])->name('update-aktif');
        Route::post('/{id}', [\App\Http\Controllers\JamPelajaranController::class, 'update'])->name('update');
        Route::delete('/{id}', [\App\Http\Controllers\JamPelajaranController::class, 'destroy'])->name('destroy');
    });

    // 15. Atur Jadwal Mengajar Guru
    Route::prefix('jadwal-mengajar')->name('jadwal-mengajar.')->group(function () {
        Route::get('/', [\App\Http\Controllers\JadwalMengajarController::class, 'index'])->name('index');
        Route::post('/generate', [\App\Http\Controllers\JadwalMengajarController::class, 'generate'])->name('generate');
        Route::post('/clear', [\App\Http\Controllers\JadwalMengajarController::class, 'clear'])->name('clear');

        // AJAX endpoint: get occupied jam slots for a hari_siklus + id_kelas combo
        Route::get('/template/get-occupied-jam', [\App\Http\Controllers\JadwalMengajarController::class, 'getOccupiedJam'])->name('template.get-occupied-jam');

        // Template Management
        Route::get('/template', [\App\Http\Controllers\JadwalMengajarController::class, 'templateIndex'])->name('template');
        Route::get('/template/teacher-grid', [\App\Http\Controllers\JadwalMengajarController::class, 'getTeacherGrid'])->name('template.teacher-grid');
        Route::post('/template/save-grid', [\App\Http\Controllers\JadwalMengajarController::class, 'saveGrid'])->name('template.save-grid');
        Route::post('/template/delete-grid', [\App\Http\Controllers\JadwalMengajarController::class, 'deleteGrid'])->name('template.delete-grid');
        Route::post('/template/clear-grid', [\App\Http\Controllers\JadwalMengajarController::class, 'clearGrid'])->name('template.clear-grid');
        Route::post('/template', [\App\Http\Controllers\JadwalMengajarController::class, 'templateStore'])->name('template.store');
        Route::post('/template/{id}', [\App\Http\Controllers\JadwalMengajarController::class, 'templateUpdate'])->name('template.update');
        Route::delete('/template/{id}', [\App\Http\Controllers\JadwalMengajarController::class, 'templateDestroy'])->name('template.destroy');
    });

    // UKS
    Route::prefix('uks')->name('uks.')->group(function () {
        Route::get('dashboard', [\App\Http\Controllers\Uks\DashboardController::class, 'index'])->name('dashboard');
        Route::resource('kunjungan', \App\Http\Controllers\Uks\KunjunganController::class)->except(['create','edit']);
        Route::resource('kunjungan-gukar', \App\Http\Controllers\Uks\KunjunganGukarController::class)->except(['create','edit']);
        Route::get('checkup/template/{id_kelas}', [\App\Http\Controllers\Uks\CheckupController::class, 'downloadTemplate'])->name('checkup.template');
        Route::post('checkup/import', [\App\Http\Controllers\Uks\CheckupController::class, 'importExcel'])->name('checkup.import');
        Route::resource('checkup', \App\Http\Controllers\Uks\CheckupController::class)->except(['create','edit']);
        Route::get('checkup-gukar/template', [\App\Http\Controllers\Uks\CheckupGukarController::class, 'downloadTemplate'])->name('checkup-gukar.template');
        Route::post('checkup-gukar/import', [\App\Http\Controllers\Uks\CheckupGukarController::class, 'importExcel'])->name('checkup-gukar.import');
        Route::resource('checkup-gukar', \App\Http\Controllers\Uks\CheckupGukarController::class)->except(['create','edit']);
        Route::get('laporan/print', [\App\Http\Controllers\Uks\LaporanController::class, 'print'])->name('laporan.print');
        Route::get('laporan/print-kunjungan-gukar', [\App\Http\Controllers\Uks\LaporanController::class, 'printKunjunganGukar'])->name('laporan.print-kunjungan-gukar');
        Route::get('laporan/print-imt', [\App\Http\Controllers\Uks\LaporanController::class, 'printImt'])->name('laporan.print-imt');
        Route::get('laporan/print-gukar', [\App\Http\Controllers\Uks\LaporanController::class, 'printGukar'])->name('laporan.print-gukar');
        Route::get('laporan/semester-by-tahun', [\App\Http\Controllers\Uks\LaporanController::class, 'getSemesterByTahun'])->name('laporan.semester-by-tahun');
        Route::get('laporan', [\App\Http\Controllers\Uks\LaporanController::class, 'index'])->name('laporan.index');
    });

    // ISMUBA / Keagamaan
    Route::prefix('ismuba')->name('ismuba.')->group(function () {
        // Dashboard ISMUBA
        Route::get('/', [DashboardIsmubaController::class, 'index'])->name('dashboard');

        // Pantau BTAQ Siswa
        Route::get('btaq', [BtaqController::class, 'index'])->name('btaq.index');
        Route::post('btaq', [BtaqController::class, 'store'])->name('btaq.store');
        Route::post('btaq/{id}', [BtaqController::class, 'update'])->name('btaq.update');
        Route::delete('btaq/{id}', [BtaqController::class, 'destroy'])->name('btaq.destroy');

        // Pantau Tadarus Kelas
        Route::get('tadarus', [TadarusController::class, 'index'])->name('tadarus.index');
        Route::post('tadarus', [TadarusController::class, 'store'])->name('tadarus.store');
        Route::post('tadarus/{id}', [TadarusController::class, 'update'])->name('tadarus.update');
        Route::delete('tadarus/{id}', [TadarusController::class, 'destroy'])->name('tadarus.destroy');

        // Pantau Ibadah (Sholat Fardu, Sholat Jenazah, Gerakan Wudhu)
        Route::get('ibadah', [IbadahController::class, 'index'])->name('ibadah.index');
        Route::post('ibadah', [IbadahController::class, 'store'])->name('ibadah.store');
        Route::post('ibadah/{id}', [IbadahController::class, 'update'])->name('ibadah.update');
        Route::delete('ibadah/{id}', [IbadahController::class, 'destroy'])->name('ibadah.destroy');

        // Laporan ISMUBA
        Route::get('laporan', [LaporanIsmubaController::class, 'index'])->name('laporan.index');

        // Jadwal Pengajian
        Route::get('jadwal-pengajian/print', [JadwalPengajianController::class, 'print'])->name('jadwal-pengajian.print');
        Route::get('jadwal-pengajian', [JadwalPengajianController::class, 'index'])->name('jadwal-pengajian.index');
        Route::post('jadwal-pengajian', [JadwalPengajianController::class, 'store'])->name('jadwal-pengajian.store');
        Route::get('jadwal-pengajian/{id}/detail', [JadwalPengajianController::class, 'getDetail'])->name('jadwal-pengajian.detail');
        Route::post('jadwal-pengajian/{id}/kehadiran', [JadwalPengajianController::class, 'updateKehadiran'])->name('jadwal-pengajian.update-kehadiran');
        Route::post('jadwal-pengajian/{id}', [JadwalPengajianController::class, 'update'])->name('jadwal-pengajian.update');
        Route::delete('jadwal-pengajian/{id}', [JadwalPengajianController::class, 'destroy'])->name('jadwal-pengajian.destroy');
    });

    // BK / Guru BK
    Route::prefix('bk')->name('bk.')->group(function () {
        Route::get('dashboard', [\App\Http\Controllers\Bk\DashboardController::class, 'index'])->name('dashboard');
        Route::get('catat-pelanggaran/siswa-by-kelas', [\App\Http\Controllers\Bk\CatatPelanggaranController::class, 'getSiswaBykelas'])->name('catat-pelanggaran.siswa-by-kelas');
        Route::get('catat-pelanggaran/search-siswa', [\App\Http\Controllers\Bk\CatatPelanggaranController::class, 'searchSiswa'])->name('catat-pelanggaran.search-siswa');
        Route::get('catat-reward/siswa-by-kelas', [\App\Http\Controllers\Bk\CatatRewardController::class, 'getSiswaBykelas'])->name('catat-reward.siswa-by-kelas');
        Route::get('catat-reward/search-siswa', [\App\Http\Controllers\Bk\CatatRewardController::class, 'searchSiswa'])->name('catat-reward.search-siswa');
        Route::get('kategori-reward/search', [\App\Http\Controllers\Bk\KategoriRewardController::class, 'search'])->name('kategori-reward.search');
        Route::get('kategori-pelanggaran/search', [\App\Http\Controllers\Bk\KategoriPelanggaranController::class, 'search'])->name('kategori-pelanggaran.search');
        Route::get('buku-kasus/search-siswa', [\App\Http\Controllers\Bk\BukuKasusController::class, 'searchSiswa'])->name('buku-kasus.search-siswa');

        Route::resource('kategori-pelanggaran', \App\Http\Controllers\Bk\KategoriPelanggaranController::class)->except(['create','edit','show']);
        Route::resource('kategori-reward', \App\Http\Controllers\Bk\KategoriRewardController::class)->except(['create','edit','show']);
        Route::resource('catat-pelanggaran', \App\Http\Controllers\Bk\CatatPelanggaranController::class)->except(['create','edit','show']);
        Route::resource('catat-reward', \App\Http\Controllers\Bk\CatatRewardController::class)->except(['create','edit','show']);
        Route::resource('buku-kasus', \App\Http\Controllers\Bk\BukuKasusController::class)->except(['create','edit','show']);
        Route::resource('buku-konsultasi', \App\Http\Controllers\Bk\BukuKonsulasiController::class)->except(['create','edit','show']);
        Route::resource('home-visit', \App\Http\Controllers\Bk\HomeVisitController::class)->except(['create','edit','show']);
        Route::post('panggil-ortu/preview', [\App\Http\Controllers\Bk\PanggilOrtuController::class, 'preview'])->name('panggil-ortu.preview');
        Route::get('panggil-ortu/siswa-detail', [\App\Http\Controllers\Bk\PanggilOrtuController::class, 'getSiswaDetail'])->name('panggil-ortu.siswa-detail');
        Route::get('panggil-ortu/{id}/pdf', [\App\Http\Controllers\Bk\PanggilOrtuController::class, 'downloadPdf'])->name('panggil-ortu.pdf');
        Route::resource('panggil-ortu', \App\Http\Controllers\Bk\PanggilOrtuController::class)->except(['create','edit','show']);
        Route::patch('gaya-belajar/{id}/catatan', [\App\Http\Controllers\Bk\GayaBelajarController::class, 'updateCatatan'])->name('gaya-belajar.update-catatan');
        Route::resource('gaya-belajar', \App\Http\Controllers\Bk\GayaBelajarController::class)->except(['create','edit','show']);
        Route::get('laporan', [\App\Http\Controllers\Bk\LaporanController::class, 'index'])->name('laporan.index');
        Route::get('laporan/print', [\App\Http\Controllers\Bk\LaporanController::class, 'print'])->name('laporan.print');
    });

    // =========================================================================
    //  PKL (PRAKTIK KERJA LAPANGAN)
    // =========================================================================
    Route::prefix('pkl')->name('pkl.')->group(function () {

        // Dashboard PKL
        Route::get('/', [\App\Http\Controllers\Pkl\DashboardPklController::class, 'index'])->name('dashboard');

        // Gelombang PKL
        Route::get('gelombang', [\App\Http\Controllers\Pkl\GelombangController::class, 'index'])->name('gelombang.index');
        Route::post('gelombang', [\App\Http\Controllers\Pkl\GelombangController::class, 'store'])->name('gelombang.store');
        Route::put('gelombang/{id}', [\App\Http\Controllers\Pkl\GelombangController::class, 'update'])->name('gelombang.update');
        Route::delete('gelombang/{id}', [\App\Http\Controllers\Pkl\GelombangController::class, 'destroy'])->name('gelombang.destroy');
        Route::get('gelombang/{id}/kelas', [\App\Http\Controllers\Pkl\GelombangController::class, 'getKelasGelombang'])->name('gelombang.kelas');
        Route::get('gelombang/{id}/info', [\App\Http\Controllers\Pkl\GelombangController::class, 'getInfo'])->name('gelombang.info');

        // Data DUDI
        Route::get('dudi/template', [\App\Http\Controllers\Pkl\DudiController::class, 'downloadTemplate'])->name('dudi.template');
        Route::post('dudi/import', [\App\Http\Controllers\Pkl\DudiController::class, 'importExcel'])->name('dudi.import');
        Route::get('dudi', [\App\Http\Controllers\Pkl\DudiController::class, 'index'])->name('dudi.index');
        Route::post('dudi', [\App\Http\Controllers\Pkl\DudiController::class, 'store'])->name('dudi.store');
        Route::put('dudi/{id}', [\App\Http\Controllers\Pkl\DudiController::class, 'update'])->name('dudi.update');
        Route::delete('dudi/{id}', [\App\Http\Controllers\Pkl\DudiController::class, 'destroy'])->name('dudi.destroy');
        Route::get('dudi/by-gelombang', [\App\Http\Controllers\Pkl\DudiController::class, 'getByGelombang'])->name('dudi.by-gelombang');

        // Pembimbing PKL
        Route::get('pembimbing/cetak', [\App\Http\Controllers\Pkl\PembimbingController::class, 'cetak'])->name('pembimbing.cetak');
        Route::get('pembimbing', [\App\Http\Controllers\Pkl\PembimbingController::class, 'index'])->name('pembimbing.index');
        Route::post('pembimbing', [\App\Http\Controllers\Pkl\PembimbingController::class, 'store'])->name('pembimbing.store');
        Route::put('pembimbing/{id}', [\App\Http\Controllers\Pkl\PembimbingController::class, 'update'])->name('pembimbing.update');
        Route::delete('pembimbing/{id}', [\App\Http\Controllers\Pkl\PembimbingController::class, 'destroy'])->name('pembimbing.destroy');

        // Data Penempatan
        Route::get('penempatan/siswa-by-gelombang', [\App\Http\Controllers\Pkl\PenempatanController::class, 'getSiswaByGelombang'])->name('penempatan.siswa-by-gelombang');
        Route::get('penempatan/belum-ditempatkan', [\App\Http\Controllers\Pkl\PenempatanController::class, 'belumDitempatkan'])->name('penempatan.belum-ditempatkan');
        Route::post('penempatan/quick-place', [\App\Http\Controllers\Pkl\PenempatanController::class, 'quickPlace'])->name('penempatan.quick-place');
        Route::get('penempatan/dudi-by-jurusan', [\App\Http\Controllers\Pkl\PenempatanController::class, 'getDudiByJurusan'])->name('penempatan.dudi-by-jurusan');
        Route::get('penempatan', [\App\Http\Controllers\Pkl\PenempatanController::class, 'index'])->name('penempatan.index');
        Route::post('penempatan', [\App\Http\Controllers\Pkl\PenempatanController::class, 'store'])->name('penempatan.store');
        Route::put('penempatan/{id}', [\App\Http\Controllers\Pkl\PenempatanController::class, 'update'])->name('penempatan.update');
        Route::delete('penempatan/{id}', [\App\Http\Controllers\Pkl\PenempatanController::class, 'destroy'])->name('penempatan.destroy');

        // Pindah Penempatan PKL
        Route::get('pindah-penempatan/penempatan-aktif', [\App\Http\Controllers\Pkl\PindahPenempatanController::class, 'getPenempatanAktif'])->name('pindah-penempatan.penempatan-aktif');
        Route::get('pindah-penempatan/pembimbing-by-dudi', [\App\Http\Controllers\Pkl\PindahPenempatanController::class, 'getPembimbingByDudi'])->name('pindah-penempatan.pembimbing-by-dudi');
        Route::get('pindah-penempatan/search-siswa', [\App\Http\Controllers\Pkl\PindahPenempatanController::class, 'searchSiswaAktif'])->name('pindah-penempatan.search-siswa');
        Route::get('pindah-penempatan/history/{nis}', [\App\Http\Controllers\Pkl\PindahPenempatanController::class, 'historyByNis'])->name('pindah-penempatan.history');
        Route::get('pindah-penempatan', [\App\Http\Controllers\Pkl\PindahPenempatanController::class, 'index'])->name('pindah-penempatan.index');
        Route::post('pindah-penempatan', [\App\Http\Controllers\Pkl\PindahPenempatanController::class, 'store'])->name('pindah-penempatan.store');

        // Nomor Surat
        Route::get('nomor-surat', [\App\Http\Controllers\Pkl\NomorSuratController::class, 'index'])->name('nomor-surat.index');
        Route::post('nomor-surat/{jenis}', [\App\Http\Controllers\Pkl\NomorSuratController::class, 'update'])->name('nomor-surat.update');
        Route::post('nomor-surat/{jenis}/reset', [\App\Http\Controllers\Pkl\NomorSuratController::class, 'resetCounter'])->name('nomor-surat.reset');

        // Persuratan
        Route::get('persuratan/siswa-by-dudi', [\App\Http\Controllers\Pkl\PersuratanController::class, 'getSiswaByDudi'])->name('persuratan.siswa-by-dudi');
        Route::get('persuratan', [\App\Http\Controllers\Pkl\PersuratanController::class, 'index'])->name('persuratan.index');
        Route::post('persuratan/generate', [\App\Http\Controllers\Pkl\PersuratanController::class, 'generate'])->name('persuratan.generate');
        Route::post('persuratan/generate-masal', [\App\Http\Controllers\Pkl\PersuratanController::class, 'generateMasal'])->name('persuratan.generate-masal');
        Route::get('persuratan/cetak-masal', [\App\Http\Controllers\Pkl\PersuratanController::class, 'cetakMasal'])->name('persuratan.cetak-masal');
        Route::get('persuratan/{id}/cetak', [\App\Http\Controllers\Pkl\PersuratanController::class, 'cetak'])->name('persuratan.cetak');
        Route::delete('persuratan/{id}', [\App\Http\Controllers\Pkl\PersuratanController::class, 'destroy'])->name('persuratan.destroy');

        // Laporan & Rekap Data PKL
        Route::get('laporan', [\App\Http\Controllers\Pkl\LaporanPklController::class, 'index'])->name('laporan.index');
        Route::get('laporan/print', [\App\Http\Controllers\Pkl\LaporanPklController::class, 'print'])->name('laporan.print');
    });

    // ─── Generator Soal LLM ──────────────────────────────────────────────────
    Route::prefix('generator-soal')->name('generator-soal.')->group(function () {
        Route::get('/', [\App\Http\Controllers\GeneratorSoalController::class, 'index'])->name('index');
        Route::post('/generate', [\App\Http\Controllers\GeneratorSoalController::class, 'generate'])->name('generate');
        Route::get('/history/{id}', [\App\Http\Controllers\GeneratorSoalController::class, 'show'])->name('show');
        Route::delete('/history/{id}', [\App\Http\Controllers\GeneratorSoalController::class, 'destroy'])->name('destroy');
        Route::get('/pengaturan', [\App\Http\Controllers\GeneratorSoalController::class, 'pengaturan'])->name('pengaturan');
        Route::post('/pengaturan', [\App\Http\Controllers\GeneratorSoalController::class, 'simpanPengaturan'])->name('pengaturan.store');
        Route::post('/buat-tugas', [\App\Http\Controllers\GeneratorSoalController::class, 'buatTugas'])->name('buat-tugas');

        // Kisi-Kisi Penilaian
        Route::get('/kisi-kisi', [\App\Http\Controllers\GeneratorSoalController::class, 'kisikisiIndex'])->name('kisikisi.index');
        Route::post('/kisi-kisi/generate', [\App\Http\Controllers\GeneratorSoalController::class, 'kisikisiGenerate'])->name('kisikisi.generate');
        Route::get('/kisi-kisi/history/{id}', [\App\Http\Controllers\GeneratorSoalController::class, 'kisikisiShow'])->name('kisikisi.show');
        Route::delete('/kisi-kisi/history/{id}', [\App\Http\Controllers\GeneratorSoalController::class, 'kisikisiDestroy'])->name('kisikisi.destroy');

        // Generate Soal dari Kisi-Kisi
        Route::get('/from-kisikisi', [\App\Http\Controllers\GeneratorSoalController::class, 'fromKisiKisi'])->name('from-kisikisi.index');
        Route::get('/from-kisikisi/get-list', [\App\Http\Controllers\GeneratorSoalController::class, 'getKisiKisiByMapel'])->name('from-kisikisi.list');
        Route::post('/from-kisikisi/generate', [\App\Http\Controllers\GeneratorSoalController::class, 'generateFromKisiKisi'])->name('from-kisikisi.generate');
    });

    // ─── Guru Kelas — Pelanggaran Kelas ──────────────────────────────────────
    Route::prefix('guru-kelas')->name('guru-kelas.')->group(function () {
        Route::get('pelanggaran/rekap', [\App\Http\Controllers\Guru\PelanggaranKelasController::class, 'rekap'])->name('pelanggaran.rekap');
        Route::get('pelanggaran/siswa-by-kelas', [\App\Http\Controllers\Guru\PelanggaranKelasController::class, 'getSiswaByKelas'])->name('pelanggaran.siswa-by-kelas');
        Route::get('pelanggaran', [\App\Http\Controllers\Guru\PelanggaranKelasController::class, 'index'])->name('pelanggaran.index');
        Route::post('pelanggaran', [\App\Http\Controllers\Guru\PelanggaranKelasController::class, 'store'])->name('pelanggaran.store');
        Route::post('pelanggaran/{id}', [\App\Http\Controllers\Guru\PelanggaranKelasController::class, 'update'])->name('pelanggaran.update');
        Route::delete('pelanggaran/{id}', [\App\Http\Controllers\Guru\PelanggaranKelasController::class, 'destroy'])->name('pelanggaran.destroy');
    });

    // ─── Learning Management System (LMS) ────────────────────────────────────
    Route::prefix('lms')->name('lms.')->group(function () {
        // 0. Kursus
        Route::prefix('kursus')->name('kursus.')->group(function () {
            Route::get('/', [\App\Http\Controllers\Lms\KursusController::class, 'index'])->name('index');
            Route::post('/', [\App\Http\Controllers\Lms\KursusController::class, 'store'])->name('store');
            Route::post('/{id}', [\App\Http\Controllers\Lms\KursusController::class, 'update'])->name('update');
            Route::delete('/{id}', [\App\Http\Controllers\Lms\KursusController::class, 'destroy'])->name('destroy');
        });

        // 1. Tugas & Kuis
        Route::prefix('tugas')->name('tugas.')->group(function () {
            Route::get('/', [\App\Http\Controllers\Lms\TugasController::class, 'index'])->name('index');
            Route::get('/upload-kuis', [\App\Http\Controllers\Lms\TugasController::class, 'uploadKuisForm'])->name('upload-kuis');
            Route::get('/download-template', [\App\Http\Controllers\Lms\TugasController::class, 'downloadTemplate'])->name('download-template');
            Route::post('/upload-kuis', [\App\Http\Controllers\Lms\TugasController::class, 'processUploadKuis'])->name('process-upload-kuis');
            Route::post('/', [\App\Http\Controllers\Lms\TugasController::class, 'store'])->name('store');
            Route::get('/{id}', [\App\Http\Controllers\Lms\TugasController::class, 'show'])->name('show');
            Route::post('/{id}', [\App\Http\Controllers\Lms\TugasController::class, 'update'])->name('update');
            Route::delete('/{id}', [\App\Http\Controllers\Lms\TugasController::class, 'destroy'])->name('destroy');
        });

        // 2. Tagihan / Pengumpulan Tugas
        Route::prefix('tagihan')->name('tagihan.')->group(function () {
            Route::get('/', [\App\Http\Controllers\Lms\TagihanTugasController::class, 'index'])->name('index');
            Route::get('/{id}', [\App\Http\Controllers\Lms\TagihanTugasController::class, 'show'])->name('show');
            Route::post('/{id}/periksa', [\App\Http\Controllers\Lms\TagihanTugasController::class, 'periksa'])->name('periksa');
            Route::delete('/{id}', [\App\Http\Controllers\Lms\TagihanTugasController::class, 'destroy'])->name('destroy');
        });

        // 3. Soal Kuis (edit & hapus per butir soal)
        Route::prefix('soal')->name('soal.')->group(function () {
            Route::post('/upload-image', [\App\Http\Controllers\Lms\SoalController::class, 'uploadImage'])->name('upload-image');
            Route::post('/{id_soal}', [\App\Http\Controllers\Lms\SoalController::class, 'update'])->name('update');
            Route::delete('/{id_soal}', [\App\Http\Controllers\Lms\SoalController::class, 'destroy'])->name('destroy');
        });
    });
});






