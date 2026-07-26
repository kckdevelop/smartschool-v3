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
        $sql .= "SET SQL_MODE = \"NO_AUTO_VALUE_ON_ZERO,NO_ENGINE_SUBSTITUTION\";\n";
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
                            } elseif ($value instanceof \DateTimeInterface) {
                                $values[] = $pdo->quote($value->format('Y-m-d H:i:s'));
                            } else {
                                $strVal = (string)$value;
                                // Clean up trailing ISO UTC timezone tags if present
                                $cleanVal = preg_replace('/^(\d{4}-\d{2}-\d{2})[T\s]+(\d{2}:\d{2}:\d{2})(?:\.\d+)?(?:\s*[\+\-]\d{2}:?\d{2}|\s*[\+\-]\d{4})?\s*(?:UTC|Z)?$/i', '$1 $2', $strVal);
                                $values[] = $pdo->quote($cleanVal);
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
     * Clean and sanitize SQL string content to fix non-standard datetime formats.
     *
     * @param string $sqlContent
     * @return string
     */
    public function sanitizeSqlContent(string $sqlContent): string
    {
        // 1. Fix datetime formatted with +0000 UTC / ISO timezone suffixes
        // e.g., '2026-07-13 00:00:00 +0000 UTC' => '2026-07-13 00:00:00'
        // e.g., '2026-07-13 00:00:00+00:00' => '2026-07-13 00:00:00'
        // e.g., '2026-07-13T00:00:00.000000Z' => '2026-07-13 00:00:00'
        $sqlContent = preg_replace(
            "/'(\d{4}-\d{2}-\d{2})[T\s]+(\d{2}:\d{2}:\d{2})(?:\.\d+)?(?:\s*[\+\-]\d{2}:?\d{2}|\s*[\+\-]\d{4})?\s*(?:UTC|Z)?'/i",
            "'$1 $2'",
            $sqlContent
        );

        // 2. Fix date without time formatted with +0000 UTC
        // e.g., '2026-07-13 +0000 UTC' => '2026-07-13'
        $sqlContent = preg_replace(
            "/'(\d{4}-\d{2}-\d{2})\s*(?:[\+\-]\d{2}:?\d{2}|[\+\-]\d{4})?\s*UTC'/i",
            "'$1'",
            $sqlContent
        );

        return $sqlContent;
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

        // Sanitize SQL content before running
        $sqlContent = $this->sanitizeSqlContent($sqlContent);

        DB::beginTransaction();
        try {
            $this->executeSqlStatements($sqlContent);
            DB::commit();
            return true;
        } catch (Exception $e) {
            DB::rollBack();
            throw new Exception("Gagal mengembalikan database: " . $e->getMessage());
        }
    }

    /**
     * Execute SQL statements with session settings & fallback statement splitter.
     *
     * @param string $sqlContent
     * @return void
     */
    protected function executeSqlStatements(string $sqlContent): void
    {
        try {
            DB::statement("SET SESSION sql_mode = 'NO_AUTO_VALUE_ON_ZERO,NO_ENGINE_SUBSTITUTION'");
        } catch (Exception $e) {}

        try {
            DB::statement('SET FOREIGN_KEY_CHECKS=0');
            DB::statement('SET UNIQUE_CHECKS=0');
        } catch (Exception $e) {}

        try {
            DB::unprepared($sqlContent);
        } catch (Exception $e) {
            // Fallback: Split statements and execute individually
            $statements = $this->splitSqlStatements($sqlContent);
            foreach ($statements as $statement) {
                $trimmed = trim($statement);
                if (!empty($trimmed)) {
                    try {
                        DB::unprepared($trimmed);
                    } catch (Exception $ex) {
                        // Rethrow if failure is critical
                        if (!str_contains($ex->getMessage(), 'Unknown table') &&
                            !str_contains($ex->getMessage(), 'does not exist')) {
                            throw $ex;
                        }
                    }
                }
            }
        } finally {
            try {
                DB::statement('SET FOREIGN_KEY_CHECKS=1');
                DB::statement('SET UNIQUE_CHECKS=1');
            } catch (Exception $e) {}
        }
    }

    /**
     * Split multi-statement SQL script cleanly.
     *
     * @param string $sql
     * @return array
     */
    protected function splitSqlStatements(string $sql): array
    {
        // Strip SQL comments
        $sql = preg_replace('/^--.*$/m', '', $sql);
        $sql = preg_replace('/\/\*.*?\*\//s', '', $sql);

        $tokens = preg_split('/;\s*$/m', $sql);
        if (!$tokens) {
            return array_filter(explode(';', $sql));
        }

        return array_filter(array_map('trim', $tokens));
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
