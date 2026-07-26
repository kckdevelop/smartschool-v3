<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Exception;

class DatabaseBackupService
{
    protected string $storagePath = 'backups';

    public function __construct()
    {
        if (!Storage::disk('local')->exists($this->storagePath)) {
            Storage::disk('local')->makeDirectory($this->storagePath);
        }
    }

    /**
     * Generate SQL Dump content for the entire database.
     *
     * @return string
     */
    public function generateSqlDump(): string
    {
        $connection = DB::connection();
        $pdo = $connection->getPdo();
        $database = $connection->getDatabaseName();

        $sql  = "-- ========================================================\n";
        $sql .= "-- SmartSchool V3 Database Backup\n";
        $sql .= "-- Database: {$database}\n";
        $sql .= "-- Generated: " . date('Y-m-d H:i:s') . "\n";
        $sql .= "-- ========================================================\n\n";
        $sql .= "SET FOREIGN_KEY_CHECKS=0;\n";
        $sql .= "SET SQL_MODE = \"NO_AUTO_VALUE_ON_ZERO\";\n";
        $sql .= "SET time_zone = \"+00:00\";\n\n";

        // Get all tables in the database
        $tables = [];
        $driver = $connection->getDriverName();

        if ($driver === 'sqlite') {
            $results = DB::select("SELECT name FROM sqlite_master WHERE type='table' AND name NOT LIKE 'sqlite_%'");
            foreach ($results as $row) {
                $tables[] = $row->name;
            }
        } else {
            $results = DB::select('SHOW TABLES');
            foreach ($results as $row) {
                $rowArray = (array)$row;
                $tables[] = reset($rowArray);
            }
        }

        foreach ($tables as $table) {
            $sql .= "-- --------------------------------------------------------\n";
            $sql .= "-- Table structure for table `{$table}`\n";
            $sql .= "-- --------------------------------------------------------\n";
            $sql .= "DROP TABLE IF EXISTS `{$table}`;\n";

            if ($driver === 'sqlite') {
                $createSqlResult = DB::select("SELECT sql FROM sqlite_master WHERE type='table' AND name=?", [$table]);
                if (!empty($createSqlResult)) {
                    $sql .= $createSqlResult[0]->sql . ";\n\n";
                }
            } else {
                $createTableResult = DB::select("SHOW CREATE TABLE `{$table}`");
                if (!empty($createTableResult)) {
                    $createArray = (array)$createTableResult[0];
                    $createSql = $createArray['Create Table'] ?? reset($createArray);
                    $sql .= $createSql . ";\n\n";
                }
            }

            // Dump data for table
            $rows = DB::table($table)->get();
            if ($rows->count() > 0) {
                $sql .= "-- Dumping data for table `{$table}`\n";
                
                // Process in chunks of 50 rows per INSERT statement
                $chunkedRows = $rows->chunk(50);
                foreach ($chunkedRows as $chunk) {
                    $columnNames = array_keys((array)$chunk->first());
                    $escapedColumns = array_map(fn($col) => "`{$col}`", $columnNames);
                    $sql .= "INSERT INTO `{$table}` (" . implode(', ', $escapedColumns) . ") VALUES\n";

                    $valueLines = [];
                    foreach ($chunk as $row) {
                        $values = [];
                        foreach ((array)$row as $value) {
                            if (is_null($value)) {
                                $values[] = 'NULL';
                            } elseif (is_numeric($value)) {
                                $values[] = $value;
                            } elseif (is_bool($value)) {
                                $values[] = $value ? '1' : '0';
                            } else {
                                $values[] = $pdo->quote((string)$value);
                            }
                        }
                        $valueLines[] = "(" . implode(', ', $values) . ")";
                    }
                    $sql .= implode(",\n", $valueLines) . ";\n";
                }
                $sql .= "\n";
            }
        }

        $sql .= "SET FOREIGN_KEY_CHECKS=1;\n";
        return $sql;
    }

    /**
     * Create a backup file and save to local storage.
     *
     * @return array Metadata of the generated backup
     */
    public function createBackup(): array
    {
        $filename = 'backup_smartschool_' . date('Y-m-d_His') . '.sql';
        $path = $this->storagePath . '/' . $filename;
        $content = $this->generateSqlDump();

        Storage::disk('local')->put($path, $content);

        return [
            'filename'   => $filename,
            'path'       => $path,
            'size'       => Storage::disk('local')->size($path),
            'created_at' => date('Y-m-d H:i:s'),
        ];
    }

    /**
     * Restore database from a SQL string or file path.
     *
     * @param string $sqlContent Raw SQL query text
     * @return bool
     * @throws Exception
     */
    public function restoreFromSql(string $sqlContent): bool
    {
        if (empty(trim($sqlContent))) {
            throw new Exception("File SQL kosong atau tidak memiliki kueri valid.");
        }

        DB::beginTransaction();
        try {
            DB::statement('SET FOREIGN_KEY_CHECKS=0;');
            DB::unprepared($sqlContent);
            DB::statement('SET FOREIGN_KEY_CHECKS=1;');
            DB::commit();
            return true;
        } catch (Exception $e) {
            DB::rollBack();
            try {
                DB::statement('SET FOREIGN_KEY_CHECKS=1;');
            } catch (Exception $ex) {}
            throw new Exception("Gagal mengembalikan database: " . $e->getMessage());
        }
    }

    /**
     * Restore database from a saved backup filename.
     *
     * @param string $filename
     * @return bool
     * @throws Exception
     */
    public function restoreFromSavedBackup(string $filename): bool
    {
        $cleanFilename = basename($filename);
        $path = $this->storagePath . '/' . $cleanFilename;

        if (!Storage::disk('local')->exists($path)) {
            throw new Exception("File backup '{$cleanFilename}' tidak ditemukan.");
        }

        $sqlContent = Storage::disk('local')->get($path);
        return $this->restoreFromSql($sqlContent);
    }

    /**
     * Get list of all saved backup files.
     *
     * @return array
     */
    public function getBackupList(): array
    {
        $files = Storage::disk('local')->files($this->storagePath);
        $backups = [];

        foreach ($files as $file) {
            if (str_ends_with($file, '.sql')) {
                $filename = basename($file);
                $size = Storage::disk('local')->size($file);
                $time = Storage::disk('local')->lastModified($file);

                $backups[] = [
                    'filename'      => $filename,
                    'size'          => $size,
                    'size_formatted'=> $this->formatBytes($size),
                    'created_at'    => date('Y-m-d H:i:s', $time),
                    'timestamp'     => $time,
                ];
            }
        }

        // Sort descending by timestamp
        usort($backups, fn($a, $b) => $b['timestamp'] <=> $a['timestamp']);

        return $backups;
    }

    /**
     * Delete a saved backup file.
     *
     * @param string $filename
     * @return bool
     */
    public function deleteBackup(string $filename): bool
    {
        $cleanFilename = basename($filename);
        $path = $this->storagePath . '/' . $cleanFilename;

        if (Storage::disk('local')->exists($path)) {
            return Storage::disk('local')->delete($path);
        }

        return false;
    }

    /**
     * Format bytes to human readable string.
     */
    protected function formatBytes(int $bytes, int $precision = 2): string
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];

        $bytes = max($bytes, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);

        $bytes /= pow(1024, $pow);

        return round($bytes, $precision) . ' ' . $units[$pow];
    }
}
