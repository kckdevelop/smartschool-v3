<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class GayaBelajarSeeder extends Seeder
{
    /**
     * Seed gaya belajar data for all active students.
     *
     * Distribution rationale (research-backed approximation):
     *   Visual      ~45%  — most common learning style
     *   Auditori    ~30%
     *   Kinestetik  ~25%
     *
     * Each student gets at most ONE gaya belajar record.
     * Already-existing records are skipped to allow re-running safely.
     */
    public function run(): void
    {
        // ── Configuration ──────────────────────────────────────────
        $gayaTypes = ['visual', 'auditori', 'kinestetik'];

        // Weighted probability distribution
        $weights = [
            'visual'     => 45,
            'auditori'   => 30,
            'kinestetik' => 25,
        ];

        // Sample minat per gaya belajar type
        $minatPool = [
            'visual'     => [
                'Menggambar & Desain', 'Fotografi', 'Video & Film', 'Komik & Ilustrasi',
                'Arsitektur', 'Matematika', 'Geometri', 'Peta & Navigasi', 'Infografis',
            ],
            'auditori'   => [
                'Musik & Instrumen', 'Menyanyi', 'Podcast', 'Debat & Diskusi',
                'Bahasa Asing', 'Puisi & Sastra', 'Drama & Teater', 'Radio',
            ],
            'kinestetik' => [
                'Olahraga', 'Sepak Bola', 'Basket', 'Voli', 'Bulu Tangkis',
                'Merakit Elektronik', 'Prakarya', 'Memasak', 'Berkebun', 'Otomotif',
            ],
        ];

        // Sample catatan per gaya belajar type
        $catatanPool = [
            'visual' => [
                'Siswa lebih mudah memahami materi dengan diagram dan gambar.',
                'Belajar efektif menggunakan peta konsep dan warna.',
                'Sangat terbantu dengan tampilan visual seperti tabel dan grafik.',
                'Lebih mudah mengingat materi jika ditampilkan secara visual.',
                'Sering membuat sketsa atau coretan saat mendengarkan penjelasan.',
                null,
            ],
            'auditori' => [
                'Siswa lebih mudah menyerap informasi melalui penjelasan lisan.',
                'Belajar efektif dengan diskusi kelompok dan tanya jawab.',
                'Sering mengulang materi dengan cara membacanya keras-keras.',
                'Mudah terganggu kebisingan, sebaiknya belajar di tempat tenang.',
                'Lebih menyukai penjelasan guru dibanding membaca modul sendiri.',
                null,
            ],
            'kinestetik' => [
                'Siswa belajar paling efektif melalui praktik langsung.',
                'Sulit diam dalam waktu lama, perlu jeda aktif saat belajar.',
                'Sangat antusias dalam kegiatan praktikum and demonstrasi.',
                'Belajar sambil bergerak atau menggunakan tangan lebih efektif.',
                'Lebih menyukai proyek nyata daripada teori semata.',
                null,
            ],
        ];

        // ── Get data ───────────────────────────────────────────────
        $siswaList = DB::table('user_siswa')
            ->where('status', 'aktif')
            ->pluck('nis')
            ->toArray();

        $guruIds = DB::table('guru')->pluck('id_guru')->toArray();

        if (empty($siswaList)) {
            $this->command->warn('Tidak ada siswa aktif ditemukan. Seeder dibatalkan.');
            return;
        }

        if (empty($guruIds)) {
            $this->command->warn('Tidak ada guru ditemukan. Seeder dibatalkan.');
            return;
        }

        // Get already seeded NIS to allow idempotent re-run
        $alreadySeeded = DB::table('gaya_belajar')
            ->pluck('nis')
            ->toArray();
        $alreadySeededSet = array_flip($alreadySeeded);

        // ── Build weighted random pool ─────────────────────────────
        $pool = [];
        foreach ($weights as $type => $weight) {
            for ($i = 0; $i < $weight; $i++) {
                $pool[] = $type;
            }
        }
        $poolSize = count($pool);

        // ── Seeding ────────────────────────────────────────────────
        $now = Carbon::now()->toDateTimeString();
        $toInsert = [];
        $skipped  = 0;

        foreach ($siswaList as $nis) {
            if (isset($alreadySeededSet[$nis])) {
                $skipped++;
                continue;
            }

            $gaya   = $pool[array_rand(array_keys($pool))];
            $minat  = $minatPool[$gaya][array_rand($minatPool[$gaya])];
            $catatan = $catatanPool[$gaya][array_rand($catatanPool[$gaya])];
            $guruId = $guruIds[array_rand($guruIds)];

            // Vary created_at for realistic spread (last 12 months)
            $daysAgo = rand(0, 365);
            $createdAt = Carbon::now()->subDays($daysAgo)->toDateTimeString();

            $toInsert[] = [
                'nis'          => $nis,
                'gaya_belajar' => $gaya,
                'minat'        => $minat,
                'catatan'      => $catatan,
                'id_guru'      => $guruId,
                'created_at'   => $createdAt,
                'updated_at'   => $createdAt,
            ];
        }

        // Insert in chunks of 100
        $inserted = count($toInsert);
        foreach (array_chunk($toInsert, 100) as $chunk) {
            DB::table('gaya_belajar')->insert($chunk);
        }

        // ── Summary ────────────────────────────────────────────────
        $this->command->info("✅ GayaBelajarSeeder selesai.");
        $this->command->info("   Inserted : {$inserted} records");
        $this->command->info("   Skipped  : {$skipped} (sudah ada)");

        // Print distribution
        $dist = DB::table('gaya_belajar')
            ->select('gaya_belajar', DB::raw('count(*) as total'))
            ->groupBy('gaya_belajar')
            ->get();

        $this->command->table(
            ['Gaya Belajar', 'Jumlah', 'Persentase'],
            $dist->map(function ($row) use ($inserted, $skipped) {
                $total = $inserted + $skipped;
                $pct   = $total > 0 ? round(($row->total / $total) * 100, 1) : 0;
                return [ucfirst($row->gaya_belajar), $row->total, $pct . '%'];
            })->toArray()
        );
    }
}
