<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\DatabaseBackupService;
use Illuminate\Http\Request;
use Exception;

class BackupRestoreController extends Controller
{
    protected DatabaseBackupService $backupService;

    public function __construct(DatabaseBackupService $backupService)
    {
        $this->backupService = $backupService;
    }

    /**
     * Daftar file backup database yang tersimpan di server.
     * GET /api/atur-data/backup-restore
     */
    public function index()
    {
        $backups = $this->backupService->getBackupList();

        return response()->json([
            'success' => true,
            'data'    => $backups,
        ]);
    }

    /**
     * Unduh file SQL dump database langsung.
     * GET /api/atur-data/backup-restore/export
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
            return response()->json([
                'success' => false,
                'message' => 'Gagal membuat backup database: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Upload & Restore file SQL database.
     * POST /api/atur-data/backup-restore/upload
     */
    public function uploadRestore(Request $request)
    {
        $sqlContent = null;

        if ($request->hasFile('sql_file')) {
            $file = $request->file('sql_file');
            $sqlContent = file_get_contents($file->getRealPath());
        } elseif ($request->filled('sql_content')) {
            $sqlContent = $request->sql_content;
        }

        if (empty($sqlContent)) {
            return response()->json([
                'success' => false,
                'message' => 'Harap unggah file SQL (sql_file) atau kirimkan teks SQL (sql_content).'
            ], 422);
        }

        try {
            $this->backupService->restoreFromSql($sqlContent);

            return response()->json([
                'success' => true,
                'message' => 'Database berhasil direstore dari file SQL!'
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengembalikan database: ' . $e->getMessage()
            ], 500);
        }
    }
}
