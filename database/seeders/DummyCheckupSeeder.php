<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DummyCheckupSeeder extends Seeder
{
    public function run(): void
    {
        $hitungIMT = function ($tb, $bb) {
            if (!$tb || !$bb || $tb <= 0 || $bb <= 0) return [null, null];
            $tbM = $tb / 100;
            $imt = round($bb / ($tbM * $tbM), 1);
            if ($imt < 18.5)      $kategori = 'Kurus';
            elseif ($imt <= 25.0) $kategori = 'Normal';
            elseif ($imt <= 27.0) $kategori = 'Gemuk';
            else                   $kategori = 'Obesitas';
            return [$imt, $kategori];
        };

        // ══ 1. DATA CHECK-UP SISWA (50 records) ══
        $siswaList = DB::table('user_siswa')
            ->where('status', 'aktif')
            ->inRandomOrder()
            ->limit(50)
            ->pluck('nis')
            ->toArray();

        if (empty($siswaList)) {
            $this->command->warn('Tidak ada data siswa aktif. Tambahkan data siswa terlebih dahulu.');
        } else {
            $now = Carbon::now();
            $jenisCheckup = ['Pemeriksaan Umum', 'Pemeriksaan Tekanan Darah', 'Penimbangan Berat Badan'];
            $checkupSiswaData = [];
            $count = min(50, count($siswaList));
            for ($i = 0; $i < $count; $i++) {
                $nis   = $siswaList[$i];
                $tb    = rand(145, 180);
                $bb    = rand(38, 90);
                $jenis = $jenisCheckup[array_rand($jenisCheckup)];
                [$imt, $kategori] = $hitungIMT($tb, $bb);
                $tanggal = $now->copy()->subDays(rand(0, 180))->format('Y-m-d');
                $jam     = sprintf('%02d:%02d:%02d', rand(7, 14), rand(0, 59), rand(0, 59));
                $checkupSiswaData[] = [
                    'tanggal'       => $tanggal,
                    'jam'           => $jam,
                    'nis'           => $nis,
                    'jenis_checkup' => $jenis,
                    'nilai'         => ($jenis === 'Pemeriksaan Tekanan Darah') ? rand(90, 140) : null,
                    'satuan'        => ($jenis === 'Pemeriksaan Tekanan Darah') ? 'mmHg' : null,
                    'tinggi_badan'  => $tb,
                    'berat_badan'   => $bb,
                    'imt'           => $imt,
                    'kategori'      => $kategori,
                ];
            }
            DB::table('data_checkup')->insert($checkupSiswaData);
            $this->command->info("{$count} data check-up siswa berhasil dibuat.");
        }

        // ══ 2. DATA CHECK-UP GURU DAN KARYAWAN (50 records) ══
        $guruList     = DB::table('guru')->where('status', 'aktif')->pluck('id_guru')->toArray();
        $karyawanList = DB::table('karyawan')->where('status', 'aktif')->pluck('id_karyawan')->toArray();

        if (empty($guruList) && empty($karyawanList)) {
            $this->command->warn('Tidak ada data guru/karyawan aktif.');
            return;
        }

        $gukarEntries = [];
        foreach ($guruList as $id)     $gukarEntries[] = ['type' => 'guru',     'id' => $id];
        foreach ($karyawanList as $id) $gukarEntries[] = ['type' => 'karyawan', 'id' => $id];
        shuffle($gukarEntries);

        $now = Carbon::now();
        $checkupGukarData = [];
        for ($i = 0; $i < 50; $i++) {
            $entry    = $gukarEntries[$i % count($gukarEntries)];
            $tb       = rand(150, 185);
            $bb       = rand(45, 100);
            [$imt, $kategori] = $hitungIMT($tb, $bb);
            $sistole  = rand(85, 160);
            $diastole = rand(55, 100);
            $tekanan  = "{$sistole}/{$diastole}";
            $kolesterol = rand(120, 280);
            $gulaDarah  = rand(65, 200);
            $asamUrat   = round(rand(25, 120) / 10, 1);
            $tanggal  = $now->copy()->subDays(rand(0, 180))->format('Y-m-d');
            $jam      = sprintf('%02d:%02d:%02d', rand(7, 16), rand(0, 59), rand(0, 59));
            $checkupGukarData[] = [
                'id_guru'      => $entry['type'] === 'guru'     ? $entry['id'] : null,
                'id_karyawan'  => $entry['type'] === 'karyawan' ? $entry['id'] : null,
                'tanggal'      => $tanggal,
                'jam'          => $jam,
                'tinggi_badan' => $tb,
                'berat_badan'  => $bb,
                'imt'          => $imt,
                'kategori'     => $kategori,
                'tekanan_darah'=> $tekanan,
                'kolesterol'   => $kolesterol,
                'gula_darah'   => $gulaDarah,
                'asam_urat'    => $asamUrat,
            ];
        }
        DB::table('data_checkup_gukar')->insert($checkupGukarData);
        $this->command->info('50 data check-up Guru dan Karyawan berhasil dibuat.');
    }
}
