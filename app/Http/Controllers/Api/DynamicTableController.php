<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class DynamicTableController extends Controller
{
    /**
     * List of tables that are restricted from direct API access for safety.
     */
    private array $restrictedTables = [
        'migrations',
        'personal_access_tokens',
        'sessions',
        'cache',
        'cache_locks',
        'password_reset_tokens'
    ];

    /**
     * Display a listing of the resource.
     */
    public function index($table, Request $request)
    {
        if ($this->isRestricted($table)) {
            return response()->json([
                'status' => 'error',
                'message' => 'Access denied to this table.'
            ], 403);
        }

        if (!Schema::hasTable($table)) {
            return response()->json([
                'status' => 'error',
                'message' => "Table '{$table}' not found."
            ], 404);
        }

        $limit = (int) $request->input('limit', 15);
        $orderBy = $request->input('order_by');
        $orderDir = $request->input('order_dir', 'asc');

        $query = DB::table($table);

        // Dynamically apply filters based on query parameters matching columns
        $columns = Schema::getColumnListing($table);
        foreach ($request->except(['page', 'limit', 'order_by', 'order_dir']) as $key => $value) {
            if (in_array($key, $columns) && $value !== null && $value !== '') {
                $query->where($key, $value);
            }
        }

        // Apply ordering if requested
        if ($orderBy && in_array($orderBy, $columns)) {
            $query->orderBy($orderBy, $orderDir);
        }

        $data = $query->paginate($limit);

        return response()->json([
            'status' => 'success',
            'data' => $data
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show($table, $id)
    {
        if ($this->isRestricted($table)) {
            return response()->json([
                'status' => 'error',
                'message' => 'Access denied to this table.'
            ], 403);
        }

        if (!Schema::hasTable($table)) {
            return response()->json([
                'status' => 'error',
                'message' => "Table '{$table}' not found."
            ], 404);
        }

        $primaryKey = $this->getPrimaryKey($table);
        $record = DB::table($table)->where($primaryKey, $id)->first();

        if (!$record) {
            return response()->json([
                'status' => 'error',
                'message' => 'Record not found.'
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'data' => $record
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store($table, Request $request)
    {
        if ($this->isRestricted($table)) {
            return response()->json([
                'status' => 'error',
                'message' => 'Access denied to this table.'
            ], 403);
        }

        if (!Schema::hasTable($table)) {
            return response()->json([
                'status' => 'error',
                'message' => "Table '{$table}' not found."
            ], 404);
        }

        $columns = Schema::getColumnListing($table);
        $primaryKey = $this->getPrimaryKey($table);

        // Filter request parameters to keep only valid table columns
        $data = [];
        foreach ($request->all() as $key => $value) {
            if (in_array($key, $columns)) {
                $data[$key] = $value;
            }
        }

        if (empty($data)) {
            return response()->json([
                'status' => 'error',
                'message' => 'No valid data provided.'
            ], 400);
        }

        try {
            // Check if primary key is manually provided
            if (isset($data[$primaryKey])) {
                DB::table($table)->insert($data);
                $id = $data[$primaryKey];
            } else {
                $id = DB::table($table)->insertGetId($data);
            }

            $record = DB::table($table)->where($primaryKey, $id)->first();

            return response()->json([
                'status' => 'success',
                'message' => 'Record created successfully.',
                'data' => $record
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to create record.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update($table, $id, Request $request)
    {
        if ($this->isRestricted($table)) {
            return response()->json([
                'status' => 'error',
                'message' => 'Access denied to this table.'
            ], 403);
        }

        if (!Schema::hasTable($table)) {
            return response()->json([
                'status' => 'error',
                'message' => "Table '{$table}' not found."
            ], 404);
        }

        $primaryKey = $this->getPrimaryKey($table);
        $record = DB::table($table)->where($primaryKey, $id)->first();

        if (!$record) {
            return response()->json([
                'status' => 'error',
                'message' => 'Record not found.'
            ], 404);
        }

        $columns = Schema::getColumnListing($table);

        $data = [];
        foreach ($request->all() as $key => $value) {
            if (in_array($key, $columns) && $key !== $primaryKey) {
                $data[$key] = $value;
            }
        }

        if (empty($data)) {
            return response()->json([
                'status' => 'error',
                'message' => 'No valid update data provided.'
            ], 400);
        }

        try {
            DB::table($table)->where($primaryKey, $id)->update($data);
            $updatedRecord = DB::table($table)->where($primaryKey, $id)->first();

            return response()->json([
                'status' => 'success',
                'message' => 'Record updated successfully.',
                'data' => $updatedRecord
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to update record.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($table, $id)
    {
        if ($this->isRestricted($table)) {
            return response()->json([
                'status' => 'error',
                'message' => 'Access denied to this table.'
            ], 403);
        }

        if (!Schema::hasTable($table)) {
            return response()->json([
                'status' => 'error',
                'message' => "Table '{$table}' not found."
            ], 404);
        }

        $primaryKey = $this->getPrimaryKey($table);
        $record = DB::table($table)->where($primaryKey, $id)->first();

        if (!$record) {
            return response()->json([
                'status' => 'error',
                'message' => 'Record not found.'
            ], 404);
        }

        try {
            DB::table($table)->where($primaryKey, $id)->delete();

            return response()->json([
                'status' => 'success',
                'message' => 'Record deleted successfully.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to delete record.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Determine if table is restricted.
     */
    private function isRestricted(string $table): bool
    {
        return in_array(strtolower($table), $this->restrictedTables);
    }

    /**
     * Resolve the primary key of a table dynamically.
     */
    private function getPrimaryKey(string $table): string
    {
        $driver = DB::connection()->getDriverName();

        if ($driver === 'sqlite') {
            $columns = DB::select("PRAGMA table_info(`$table`)");
            foreach ($columns as $column) {
                if ($column->pk) {
                    return $column->name;
                }
            }
        } else {
            // MySQL/MariaDB query
            $columns = DB::select("
                SELECT COLUMN_NAME 
                FROM INFORMATION_SCHEMA.COLUMNS 
                WHERE TABLE_SCHEMA = DATABASE() 
                AND TABLE_NAME = ? 
                AND COLUMN_KEY = 'PRI'
            ", [$table]);

            if (!empty($columns)) {
                return $columns[0]->COLUMN_NAME;
            }
        }

        // Fallback checks
        $columns = Schema::getColumnListing($table);
        if (in_array('id', $columns)) {
            return 'id';
        }
        foreach ($columns as $col) {
            if (str_starts_with($col, 'id_') || $col === 'nis') {
                return $col;
            }
        }

        return 'id';
    }
}
