<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Kelas;
use App\Models\UserSiswa;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Illuminate\Support\Facades\DB;

class ImportSiswaKelasXISeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $filePath = 'd:/laravel12/api-smartschool/public/Daftar Siswa Kelas XI 25-26 20251103.xlsx';

        if (!file_exists($filePath)) {
            $this->command->error("File tidak ditemukan di: $filePath");
            return;
        }

        $this->command->info("Membaca file Excel Kelas XI...");
        $spreadsheet = IOFactory::load($filePath);
        $sheetNames = $spreadsheet->getSheetNames();

        $jurusanMap = [
            'RPL'  => 1,
            'TSM'  => 3,
            'TBSM' => 3,
            'TPM'  => 4,
            'TAV'  => 5,
            'TKR'  => 6,
            'TKRO' => 6,
        ];

        DB::beginTransaction();

        try {
            $totalClassesCreated = 0;
            $totalStudentsImported = 0;

            foreach ($sheetNames as $sheetName) {
                // Skip admin/summary sheets
                if (in_array(strtolower($sheetName), ['stat', 'total', 'alamat', 'master'])) {
                    continue;
                }

                // Determine Jurusan
                preg_match('/^[A-Z]+/i', $sheetName, $matches);
                $prefix = strtoupper($matches[0] ?? '');
                $id_jurusan = $jurusanMap[$prefix] ?? null;

                if (!$id_jurusan) {
                    $this->command->warn("Jurusan tidak dikenali untuk tab '$sheetName' (prefix: '$prefix'), melewati...");
                    continue;
                }

                // Create or find Class for tingkat 11 (tahun_masuk 2024)
                $kelas = Kelas::firstOrCreate([
                    'tingkat' => 11,
                    'rombel' => $sheetName,
                    'tahun_masuk' => 2024,
                ], [
                    'id_jurusan' => $id_jurusan,
                    'status' => 'aktif',
                ]);

                if ($kelas->wasRecentlyCreated) {
                    $totalClassesCreated++;
                }

                $sheet = $spreadsheet->getSheetByName($sheetName);
                $highestRow = $sheet->getHighestRow();

                for ($row = 1; $row <= $highestRow; $row++) {
                    $valA = trim((string)$sheet->getCell('A' . $row)->getValue());
                    $valB = trim((string)$sheet->getCell('B' . $row)->getValue());
                    $valC = trim((string)$sheet->getCell('C' . $row)->getValue());

                    // A student row has numeric A (No Urut) and numeric B (NIS) and non-empty C (Nama)
                    if (is_numeric($valA) && is_numeric($valB) && !empty($valC)) {
                        $isBold = $sheet->getStyle('C' . $row)->getFont()->getBold();
                        $jenkel = $isBold ? 'P' : 'L';
                        
                        $passwordHashed = sha1($valB);

                        UserSiswa::updateOrCreate([
                            'nis' => (int)$valB,
                        ], [
                            'password' => $passwordHashed,
                            'password_wali' => $passwordHashed,
                            'id_kelas' => $kelas->id_kelas,
                            'nama_siswa' => $valC,
                            'jenkel' => $jenkel,
                            'kelengkapan' => 0,
                            'status' => 'aktif',
                        ]);

                        $totalStudentsImported++;
                    }
                }
            }

            DB::commit();

            $this->command->info("=== PROSES IMPORT KELAS XI SELESAI ===");
            $this->command->info("Total Kelas Baru Dibuat: $totalClassesCreated");
            $this->command->info("Total Siswa Berhasil Diimport: $totalStudentsImported");

        } catch (\Exception $e) {
            DB::rollBack();
            $this->command->error("Terjadi kesalahan: " . $e->getMessage());
        }
    }
}
