<?php

namespace App\Http\Controllers\AturData;

use App\Http\Controllers\Controller;
use App\Services\DatabaseBackupService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Exception;

class BackupRestoreController extends Controller
{
    protected DatabaseBackupService $backupService;

    public function __construct(DatabaseBackupService $backupService)
    {
        $this->backupService = $backupService;
    }

    /**
     * Tampilkan halaman utama Backup & Restore DB.
     */
    public function index()
    {
        $backups = $this->backupService->getBackupList();
        return view('atur-data.backup-restore.index', compact('backups'));
    }

    /**
     * Buat backup baru dan langsung unduh (Direct Export & Download SQL).
     */
    public function export()
    {
        try {
            $sqlContent = $this->backupService->generateSqlDump();
            $filename = 'backup_smartschool_' . date('Y-m-d_His') . '.sql';

            return response($sqlContent, 200)
                ->header('Content-Type', 'application/sql')
                ->header('Content-Disposition', 'attachment; filename="' . $filename . '"');
        } catch (Exception $e) {
            return redirect()->route('atur-data.backup-restore')
                ->with('error', 'Gagal membuat file backup SQL: ' . $e->getMessage());
        }
    }

    /**
     * Buat backup baru dan simpan di folder server (storage/app/backups/).
     */
    public function store()
    {
        try {
            $backup = $this->backupService->createBackup();
            return redirect()->route('atur-data.backup-restore')
                ->with('success', "File backup database '{$backup['filename']}' berhasil dibuat dan disimpan di server.");
        } catch (Exception $e) {
            return redirect()->route('atur-data.backup-restore')
                ->with('error', 'Gagal menyimpan backup database: ' . $e->getMessage());
        }
    }

    /**
     * Unggah file SQL eksternal dan lakukan restore database.
     */
    public function uploadRestore(Request $request)
    {
        $request->validate([
            'sql_file' => 'required|file|max:102400', // Maks 100MB
        ], [
            'sql_file.required' => 'Harap pilih file SQL untuk diunggah.',
            'sql_file.max'      => 'Ukuran file SQL maksimal 100MB.',
        ]);

        try {
            $file = $request->file('sql_file');
            $extension = strtolower($file->getClientOriginalExtension());

            if ($extension !== 'sql' && $file->getClientMimeType() !== 'text/x-sql' && $file->getClientMimeType() !== 'text/plain') {
                return redirect()->route('atur-data.backup-restore')
                    ->with('error', 'File yang diunggah harus berformat SQL (.sql).');
            }

            $sqlContent = file_get_contents($file->getRealPath());
            $this->backupService->restoreFromSql($sqlContent);

            return redirect()->route('atur-data.backup-restore')
                ->with('success', 'Database berhasil direstore dari file SQL yang diunggah!');
        } catch (Exception $e) {
            return redirect()->route('atur-data.backup-restore')
                ->with('error', 'Gagal melakukan restore database: ' . $e->getMessage());
        }
    }

    /**
     * Restore database dari riwayat file backup yang tersimpan di server.
     */
    public function restoreSaved($filename)
    {
        try {
            $this->backupService->restoreFromSavedBackup($filename);
            return redirect()->route('atur-data.backup-restore')
                ->with('success', "Database berhasil direstore dari file '{$filename}'!");
        } catch (Exception $e) {
            return redirect()->route('atur-data.backup-restore')
                ->with('error', 'Gagal mengembalikan database: ' . $e->getMessage());
        }
    }

    /**
     * Unduh file backup yang tersimpan di server.
     */
    public function downloadSaved($filename)
    {
        $cleanFilename = basename($filename);
        $path = 'backups/' . $cleanFilename;

        if (!Storage::disk('local')->exists($path)) {
            return redirect()->route('atur-data.backup-restore')
                ->with('error', 'File backup tidak ditemukan.');
        }

        return Storage::disk('local')->download($path, $cleanFilename, [
            'Content-Type' => 'application/sql',
        ]);
    }

    /**
     * Hapus file backup yang tersimpan di server.
     */
    public function destroy($filename)
    {
        try {
            $deleted = $this->backupService->deleteBackup($filename);

            if ($deleted) {
                return redirect()->route('atur-data.backup-restore')
                    ->with('success', "File backup '{$filename}' berhasil dihapus.");
            }

            return redirect()->route('atur-data.backup-restore')
                ->with('error', 'File backup tidak ditemukan.');
        } catch (Exception $e) {
            return redirect()->route('atur-data.backup-restore')
                ->with('error', 'Gagal menghapus file backup: ' . $e->getMessage());
        }
    }
}
