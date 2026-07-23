<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

/**
 * MobileTestSeeder
 * ─────────────────────────────────────────────────────────────────────────────
 * Seeder khusus untuk pengujian aplikasi mobile SmartSchool.
 * Mengisi / melengkapi seluruh data dummy yang dibutuhkan API:
 *
 *  1. Reset password (siswa, orang_tua, guru, karyawan) → "password123"
 *  2. detail_siswa  — profil lengkap untuk semua siswa
 *  3. mapel         — mata pelajaran (10 mapel)
 *  4. jam_pelajaran — 10 jam + variasi upacara & puasa
 *  5. jadwal_mengajar_template — template siklus
 *  6. jadwal_mengajar_harian   — 30 hari ke belakang
 *  7. presensi      — 30 hari data harian (300+ record)
 *  8. UKS           — kunjungan_uks, data_checkup
 *  9. BK            — riwayat_poin, riwayat_reward, buku_kasus,
 *                     bimbingan_konseling, home_visit, panggil_ortu
 * 10. ISMUBA        — btaq, tadarus, pantau_ibadah
 * 11. jadwal_pengajian + kehadiran_pengajian
 * 12. tugas + tagihan_tugas
 *
 * Password test yang digunakan: password123 (bcrypt)
 * SHA1 dari "password123" = cbfdac6008f9cab4083784cbd1874f76618d2a97
 */
class MobileTestSeeder extends Seeder
{
    // ─── Pool Nama ──────────────────────────────────────────────────────────
    private array $namaL   = ['Ahmad','Bagas','Chandra','Danu','Eko','Fajar','Guntur','Hadi','Ilham','Joko','Kurnia','Lukman','Mulyono','Nugroho','Oki','Prabowo','Rian','Setyo','Taufik','Wahyu'];
    private array $namaP   = ['Ani','Citra','Dewi','Endah','Fitri','Gita','Hana','Indah','Kartika','Laras','Mega','Novi','Putri','Rina','Siti','Tari','Utami','Wulan','Yuni','Zahra'];
    private array $namaBlk = ['Santoso','Wibowo','Pratama','Hidayat','Saputra','Kusuma','Sari','Lestari','Putra','Setiawan','Nugraha','Wijaya','Ramadhan','Firmansyah','Purnama'];
    private array $kota    = ['Yogyakarta','Sleman','Bantul','Kulon Progo','Gunungkidul','Surakarta','Klaten','Magelang'];

    // Password bersama untuk semua akun testing
    private string $plainPassword = 'password123';
    // SHA1 dari "password123"
    private string $sha1Password  = 'cbfdac6008f9cab4083784cbd1874f76618d2a97';

    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        $this->command->info('🚀 MobileTestSeeder dimulai...');

        $this->resetPasswords();
        $this->seedDetailSiswa();
        $this->seedMapel();
        $this->seedJamPelajaran();
        $this->seedJadwalTemplate();
        $this->seedJadwalHarian();
        $this->seedPresensi();
        $this->seedUks();
        $this->seedBk();
        $this->seedIsmuba();
        $this->seedPengajian();
        $this->seedTugasTagihan();

        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $this->command->info('');
        $this->command->info('✅ MobileTestSeeder selesai! Semua data sudah siap untuk testing.');
        $this->command->info('');
        $this->command->info('══════════════ AKUN TESTING MOBILE ══════════════');
        $this->printSampleAccounts();
        $this->command->info('══════════════════════════════════════════════════');
        $this->command->info('Password semua akun: password123');
    }

    // ══════════════════════════════════════════════════════════════
    //  1. RESET PASSWORDS
    // ══════════════════════════════════════════════════════════════
    private function resetPasswords(): void
    {
        $bcrypt = Hash::make($this->plainPassword);

        // Siswa & Orang Tua — gunakan SHA1 agar ringan di DB lama
        $updatedSiswa = DB::table('user_siswa')->update([
            'password'      => $this->sha1Password,
            'password_wali' => $this->sha1Password,
        ]);
        $this->command->info("   [Password] {$updatedSiswa} siswa direset → {$this->plainPassword}");

        // Guru — gunakan bcrypt
        $updatedGuru = DB::table('guru')->update([
            'password' => $bcrypt,
        ]);
        $this->command->info("   [Password] {$updatedGuru} guru direset → {$this->plainPassword}");

        // Karyawan — gunakan bcrypt
        $updatedKaryawan = DB::table('karyawan')->update([
            'password' => $bcrypt,
        ]);
        $this->command->info("   [Password] {$updatedKaryawan} karyawan direset → {$this->plainPassword}");

        // Staff admin — gunakan SHA1
        DB::table('user_smartschool')->update([
            'password' => $this->sha1Password,
        ]);
        $this->command->info("   [Password] Staff/Admin direset → {$this->plainPassword}");
    }

    // ══════════════════════════════════════════════════════════════
    //  2. DETAIL SISWA
    // ══════════════════════════════════════════════════════════════
    private function seedDetailSiswa(): void
    {
        $existing = DB::table('detail_siswa')->count();
        if ($existing > 100) {
            $this->command->info("   [Detail Siswa] sudah ada {$existing} data, skip.");
            return;
        }

        // Hapus existing yang mungkin tidak lengkap
        DB::table('detail_siswa')->truncate();

        $nisList = DB::table('user_siswa')->pluck('nis')->toArray();
        $rows = [];

        foreach ($nisList as $nis) {
            $kota  = $this->kota[array_rand($this->kota)];
            $rows[] = [
                'nis'            => $nis,
                'alamat'         => 'Jl. ' . ['Kaliurang','Palagan','Magelang','Wates','Bantul','Gejayan','Monjali','Seturan'][rand(0,7)] . ' No.' . rand(1, 150) . ', ' . $kota,
                'agama'          => ['Islam','Islam','Islam','Islam','Kristen','Katolik'][rand(0,5)],
                'golongan_darah' => ['A','B','AB','O'][rand(0, 3)],
                'nama_ayah'      => $this->namaL[array_rand($this->namaL)] . ' ' . $this->namaBlk[array_rand($this->namaBlk)],
                'pekerjaan_ayah' => ['Wiraswasta','PNS','Karyawan Swasta','Buruh','Pedagang','TNI/Polri'][rand(0, 5)],
                'no_telp_ayah'   => '0812' . rand(10000000, 99999999),
                'nama_ibu'       => $this->namaP[array_rand($this->namaP)] . ' ' . $this->namaBlk[array_rand($this->namaBlk)],
                'pekerjaan_ibu'  => ['Ibu Rumah Tangga','Karyawan Swasta','PNS','Wiraswasta','Pedagang'][rand(0, 4)],
                'no_telp_ibu'    => '0857' . rand(10000000, 99999999),
                'nama_wali'      => null,
                'pekerjaan_wali' => null,
                'no_telp_wali'   => null,
                'created_at'     => now()->subDays(rand(30, 365)),
                'updated_at'     => now(),
            ];
        }

        foreach (array_chunk($rows, 100) as $chunk) {
            DB::table('detail_siswa')->insertOrIgnore($chunk);
        }
        $this->command->info('   [Detail Siswa] ' . count($rows) . ' record di-insert.');
    }

    // ══════════════════════════════════════════════════════════════
    //  3. MAPEL (Mata Pelajaran)
    // ══════════════════════════════════════════════════════════════
    private function seedMapel(): void
    {
        $mapelDef = [
            ['MTK', 'Matematika'],
            ['IND', 'Bahasa Indonesia'],
            ['ING', 'Bahasa Inggris'],
            ['IPA', 'Ilmu Pengetahuan Alam'],
            ['IPS', 'Ilmu Pengetahuan Sosial'],
            ['PKN', 'Pendidikan Kewarganegaraan'],
            ['PAI', 'Pendidikan Agama Islam'],
            ['PJOK', 'Pend. Jasmani, OR & Kes.'],
            ['SBD', 'Seni Budaya & Desain'],
            ['RPL', 'Rekayasa Perangkat Lunak'],
            ['BDS', 'Basis Data'],
            ['JWB', 'Jaringan & Web'],
            ['BIND', 'Bahasa Jawa'],
            ['KWU', 'Kewirausahaan'],
            ['PKK', 'Produk Kreatif & Kewirausahaan'],
        ];

        foreach ($mapelDef as [$kode, $nama]) {
            DB::table('mapel')->insertOrIgnore([
                'kode_mapel' => $kode,
                'nama_mapel' => $nama,
            ]);
        }
        $this->command->info('   [Mapel] Mata pelajaran di-insert (skip jika sudah ada).');
    }

    // ══════════════════════════════════════════════════════════════
    //  4. JAM PELAJARAN
    // ══════════════════════════════════════════════════════════════
    private function seedJamPelajaran(): void
    {
        if (DB::table('jam_pelajaran')->count() >= 10) {
            $this->command->info('   [Jam Pelajaran] sudah cukup, skip.');
            return;
        }

        DB::table('jam_pelajaran')->truncate();

        $jamDef = [
            // jam_ke, normal_mulai, normal_selesai, upacara_mulai, upacara_selesai, puasa_mulai, puasa_selesai
            [1,  '07:00', '07:45', '07:45', '08:25', '07:00', '07:40'],
            [2,  '07:45', '08:30', '08:25', '09:05', '07:40', '08:20'],
            [3,  '08:30', '09:15', '09:05', '09:45', '08:20', '09:00'],
            [4,  '09:15', '10:00', '09:45', '10:25', '09:00', '09:40'],
            [5,  '10:15', '11:00', '10:40', '11:20', '09:55', '10:35'],
            [6,  '11:00', '11:45', '11:20', '12:00', '10:35', '11:15'],
            [7,  '12:30', '13:15', '12:30', '13:15', '11:30', '12:10'],
            [8,  '13:15', '14:00', '13:15', '14:00', '12:10', '12:50'],
            [9,  '14:00', '14:45', '14:00', '14:45', '12:50', '13:30'],
            [10, '14:45', '15:30', '14:45', '15:30', '13:30', '14:10'],
        ];

        $rows = [];
        foreach ($jamDef as [$ke, $nm, $ns, $um, $us, $pm, $ps]) {
            $rows[] = [
                'jam_ke'          => $ke,
                'normal_mulai'    => $nm,
                'normal_selesai'  => $ns,
                'upacara_mulai'   => $um,
                'upacara_selesai' => $us,
                'puasa_mulai'     => $pm,
                'puasa_selesai'   => $ps,
            ];
        }
        DB::table('jam_pelajaran')->insert($rows);
        $this->command->info('   [Jam Pelajaran] 10 jam pelajaran di-insert.');
    }

    // ══════════════════════════════════════════════════════════════
    //  5. JADWAL MENGAJAR TEMPLATE
    // ══════════════════════════════════════════════════════════════
    private function seedJadwalTemplate(): void
    {
        if (DB::table('jadwal_mengajar_template')->count() >= 20) {
            $this->command->info('   [Jadwal Template] sudah cukup, skip.');
            return;
        }

        $guruIds  = DB::table('guru')->where('status', 'aktif')->pluck('id_guru')->toArray();
        $kelasIds = DB::table('kelas')->where('status', 'aktif')->pluck('id_kelas')->toArray();
        $mapelIds = DB::table('mapel')->pluck('id_mapel')->toArray();

        if (empty($guruIds) || empty($kelasIds) || empty($mapelIds)) {
            $this->command->warn('   [Jadwal Template] data guru/kelas/mapel tidak cukup, skip.');
            return;
        }

        $hariSiklus = ['D1','D2','D3','D4','D5'];
        $rows = [];
        $guruIdx = 0;

        foreach (array_slice($kelasIds, 0, 10) as $kelasId) {
            foreach ($hariSiklus as $hari) {
                // 5-6 jam per hari per kelas
                for ($jamKe = 1; $jamKe <= 5; $jamKe++) {
                    $rows[] = [
                        'id_guru'    => $guruIds[$guruIdx % count($guruIds)],
                        'id_kelas'   => $kelasId,
                        'id_mapel'   => $mapelIds[array_rand($mapelIds)],
                        'hari_siklus'=> $hari,
                        'jam_ke'     => $jamKe,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                    $guruIdx++;
                }
            }
        }

        foreach (array_chunk($rows, 100) as $chunk) {
            DB::table('jadwal_mengajar_template')->insert($chunk);
        }
        $this->command->info('   [Jadwal Template] ' . count($rows) . ' record di-insert.');
    }

    // ══════════════════════════════════════════════════════════════
    //  6. JADWAL MENGAJAR HARIAN (dari jadwal_siklus)
    // ══════════════════════════════════════════════════════════════
    private function seedJadwalHarian(): void
    {
        if (DB::table('jadwal_mengajar_harian')->count() >= 50) {
            $this->command->info('   [Jadwal Harian] sudah cukup, skip.');
            return;
        }

        $templates  = DB::table('jadwal_mengajar_template')->get();
        $siklusList = DB::table('jadwal_siklus')
            ->whereBetween('tanggal', [
                Carbon::today()->subDays(30)->toDateString(),
                Carbon::today()->toDateString()
            ])
            ->where('status', 'KBM')
            ->get();

        if ($templates->isEmpty() || $siklusList->isEmpty()) {
            $this->command->warn('   [Jadwal Harian] template/siklus kosong, membuat data sederhana...');
            $this->seedJadwalHarianSimple();
            return;
        }

        $rows = [];
        foreach ($siklusList as $siklus) {
            $hariKe = $siklus->hari_ke;
            $hariTemplates = $templates->where('hari_siklus', $hariKe);

            foreach ($hariTemplates as $tpl) {
                if (DB::table('jadwal_mengajar_harian')
                    ->where('tanggal', $siklus->tanggal)
                    ->where('id_guru', $tpl->id_guru)
                    ->where('id_kelas', $tpl->id_kelas)
                    ->where('jam_ke', $tpl->jam_ke)
                    ->exists()) {
                    continue;
                }

                $rows[] = [
                    'tanggal'    => $siklus->tanggal,
                    'id_guru'    => $tpl->id_guru,
                    'id_kelas'   => $tpl->id_kelas,
                    'id_mapel'   => $tpl->id_mapel,
                    'jam_ke'     => $tpl->jam_ke,
                    'status'     => 'KBM',
                    'keterangan' => null,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
        }

        foreach (array_chunk($rows, 200) as $chunk) {
            DB::table('jadwal_mengajar_harian')->insert($chunk);
        }
        $this->command->info('   [Jadwal Harian] ' . count($rows) . ' record di-insert.');
    }

    private function seedJadwalHarianSimple(): void
    {
        $guruIds  = DB::table('guru')->where('status','aktif')->take(10)->pluck('id_guru')->toArray();
        $kelasIds = DB::table('kelas')->where('status','aktif')->take(5)->pluck('id_kelas')->toArray();
        $mapelIds = DB::table('mapel')->pluck('id_mapel')->toArray();

        if (empty($guruIds) || empty($kelasIds) || empty($mapelIds)) return;

        $rows = [];
        for ($day = 30; $day >= 1; $day--) {
            $date = Carbon::today()->subDays($day)->toDateString();
            $dow  = Carbon::parse($date)->dayOfWeek;
            if ($dow === 0 || $dow === 6) continue;

            foreach (array_slice($kelasIds, 0, 3) as $kelasId) {
                for ($jamKe = 1; $jamKe <= 5; $jamKe++) {
                    $rows[] = [
                        'tanggal'    => $date,
                        'id_guru'    => $guruIds[array_rand($guruIds)],
                        'id_kelas'   => $kelasId,
                        'id_mapel'   => $mapelIds[array_rand($mapelIds)],
                        'jam_ke'     => $jamKe,
                        'status'     => 'KBM',
                        'keterangan' => null,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                }
            }
        }

        foreach (array_chunk($rows, 200) as $chunk) {
            DB::table('jadwal_mengajar_harian')->insert($chunk);
        }
        $this->command->info('   [Jadwal Harian Simple] ' . count($rows) . ' record di-insert.');
    }

    // ══════════════════════════════════════════════════════════════
    //  7. PRESENSI (300+ records, 30 hari)
    // ══════════════════════════════════════════════════════════════
    private function seedPresensi(): void
    {
        if (DB::table('presensi')->count() >= 200) {
            $this->command->info('   [Presensi] sudah cukup, skip.');
            return;
        }

        // Hapus data minimal yang sudah ada
        DB::table('presensi')->truncate();

        $nisList    = DB::table('user_siswa')->where('status','aktif')->pluck('nis')->toArray();
        $statusPool = [1,1,1,1,1,1,1,2,3,4]; // 70% hadir, 10% terlambat, 10% izin, 10% alfa
        $ket = [
            1 => null,
            2 => null,
            3 => ['Sakit','Izin Keluarga','Keperluan Pribadi','Acara Keluarga'],
            4 => null,
        ];

        $rows = [];
        for ($day = 45; $day >= 1; $day--) {
            $date = Carbon::today()->subDays($day)->toDateString();
            $dow  = Carbon::parse($date)->dayOfWeek;
            if ($dow === 0 || $dow === 6) continue;

            $sample = array_slice($nisList, 0, min(60, count($nisList)));
            foreach ($sample as $nis) {
                $status = $statusPool[array_rand($statusPool)];
                $rows[] = [
                    'nis'        => $nis,
                    'tanggal'    => $date,
                    'jam'        => $status === 2
                        ? sprintf('07:%02d:00', rand(16, 45))
                        : sprintf('07:%02d:00', rand(0, 10)),
                    'status'     => $status,
                    'keterangan' => $status === 3 ? $ket[3][array_rand($ket[3])] : null,
                    'file'       => null,
                ];
            }
        }

        foreach (array_chunk($rows, 300) as $chunk) {
            DB::table('presensi')->insert($chunk);
        }
        $this->command->info('   [Presensi] ' . count($rows) . ' record di-insert.');
    }

    // ══════════════════════════════════════════════════════════════
    //  8. UKS
    // ══════════════════════════════════════════════════════════════
    private function seedUks(): void
    {
        $nisList = DB::table('user_siswa')->where('status','aktif')->pluck('nis')->toArray();

        // Jenis Checkup
        if (DB::table('jenis_checkup')->count() < 5) {
            DB::table('jenis_checkup')->insertOrIgnore([
                ['jenis_checkup'=>'Penimbangan Berat Badan','status'=>'aktif'],
                ['jenis_checkup'=>'Pengukuran Tinggi Badan','status'=>'aktif'],
                ['jenis_checkup'=>'Tes Tajam Penglihatan','status'=>'aktif'],
                ['jenis_checkup'=>'Pemeriksaan Tekanan Darah','status'=>'aktif'],
                ['jenis_checkup'=>'Pemeriksaan Gigi & Mulut','status'=>'aktif'],
                ['jenis_checkup'=>'Pemeriksaan Golongan Darah','status'=>'aktif'],
                ['jenis_checkup'=>'Tes Hemoglobin (Hb)','status'=>'aktif'],
            ]);
        }

        // Kunjungan UKS (100 records)
        if (DB::table('kunjungan_uks')->count() < 50) {
            $keluhan  = ['Pusing dan mual','Sakit perut','Demam ringan','Lemas','Mimisan','Cedera olahraga','Sakit gigi','Alergi kulit','Batuk pilek','Mata merah','Mual','Sakit kepala','Kram perut','Bengkak'];
            $diagnosa = ['Dehidrasi','Gastritis','Febris','Kelelahan','Epistaksis','Contusio','Pulpitis','Dermatitis','ISPA','Konjungtivitis','Nausea','Tension Headache','Dysmenorrhea','Edema'];
            $tindakan = ['Istirahat + minum air','Antasida oral','Parasetamol 500mg','Vitamin B komplex','Penekanan hidung','Kompres dingin','Berkumur antiseptik','Salep antihistamin','Sirup OBH','Tetes mata','Kompres panas','Analgesik oral','Minyak angin','Perban elastis'];

            $rows = [];
            for ($i = 0; $i < 100; $i++) {
                $idx = rand(0, count($keluhan)-1);
                $rows[] = [
                    'nis'      => $nisList[array_rand($nisList)],
                    'tanggal'  => Carbon::today()->subDays(rand(0, 90))->toDateString(),
                    'jam'      => sprintf('%02d:%02d:00', rand(7, 14), rand(0, 59)),
                    'keluhan'  => $keluhan[$idx],
                    'diagnosa' => $diagnosa[$idx],
                    'tindakan' => $tindakan[$idx],
                ];
            }
            DB::table('kunjungan_uks')->insert($rows);
            $this->command->info('   [UKS Kunjungan] 100 record di-insert.');
        } else {
            $this->command->info('   [UKS Kunjungan] sudah ada data, skip.');
        }

        // Data Checkup (100 records)
        if (DB::table('data_checkup')->count() < 50) {
            $jenisPool = ['Penimbangan Berat Badan','Pengukuran Tinggi Badan','Tes Tajam Penglihatan','Pemeriksaan Tekanan Darah','Pemeriksaan Gigi & Mulut'];
            $rows = [];
            $sample = array_slice($nisList, 0, min(100, count($nisList)));
            foreach ($sample as $nis) {
                $jenis = $jenisPool[array_rand($jenisPool)];
                [$nilai, $satuan] = match($jenis) {
                    'Penimbangan Berat Badan'   => [rand(45, 75), 'kg'],
                    'Pengukuran Tinggi Badan'   => [rand(150, 178), 'cm'],
                    'Tes Tajam Penglihatan'     => [rand(5, 10), '/10'],
                    'Pemeriksaan Tekanan Darah' => [rand(100, 130), 'mmHg'],
                    default                     => [rand(1, 10), 'skor'],
                };
                $rows[] = [
                    'nis'           => $nis,
                    'tanggal'       => Carbon::today()->subDays(rand(0, 90))->toDateString(),
                    'jam'           => sprintf('%02d:%02d:00', rand(8, 14), rand(0, 59)),
                    'jenis_checkup' => $jenis,
                    'nilai'         => $nilai,
                    'satuan'        => $satuan,
                ];
            }
            DB::table('data_checkup')->insert($rows);
            $this->command->info('   [UKS Checkup] ' . count($rows) . ' record di-insert.');
        } else {
            $this->command->info('   [UKS Checkup] sudah ada data, skip.');
        }
    }

    // ══════════════════════════════════════════════════════════════
    //  9. BK (Bimbingan Konseling)
    // ══════════════════════════════════════════════════════════════
    private function seedBk(): void
    {
        $nisList  = DB::table('user_siswa')->where('status','aktif')->pluck('nis')->toArray();
        $guruIds  = DB::table('guru')->where('status','aktif')->pluck('id_guru')->toArray();
        $bkGuruId = DB::table('guru')->where('guru_bk','ya')->value('id_guru') ?? $guruIds[0] ?? 1;

        // Riwayat Poin Pelanggaran (80 records)
        if (DB::table('riwayat_poin')->count() < 50) {
            $jenisList = DB::table('jenis_pelanggaran')->get();
            if ($jenisList->isNotEmpty()) {
                $poinMap = [
                    'Terlambat Masuk Sekolah'    => 5,
                    'Tidak Membawa Buku'          => 5,
                    'Seragam Tidak Lengkap'       => 10,
                    'Berkelahi'                   => 30,
                    'Membawa HP Saat Pelajaran'   => 15,
                    'Membolos'                    => 20,
                    'Merokok'                     => 40,
                    'Tidak Mengerjakan PR'        => 5,
                    'Tidak Ikut Upacara'          => 15,
                    'Merusak Fasilitas Sekolah'   => 35,
                ];
                $rows = [];
                for ($i = 0; $i < 80; $i++) {
                    $jenis = $jenisList->random();
                    $rows[] = [
                        'tgl_input'   => Carbon::today()->subDays(rand(0, 90))->toDateString(),
                        'nis'         => $nisList[array_rand($nisList)],
                        'tingkat'     => 11,
                        'pelanggaran' => $jenis->jenis_pelanggaran,
                        'poin'        => $poinMap[$jenis->jenis_pelanggaran] ?? 10,
                        'id_guru'     => $bkGuruId,
                    ];
                }
                DB::table('riwayat_poin')->insert($rows);
                $this->command->info('   [BK Pelanggaran] 80 record di-insert.');
            }
        } else {
            $this->command->info('   [BK Pelanggaran] sudah ada data, skip.');
        }

        // Riwayat Reward (50 records)
        if (DB::table('riwayat_reward')->count() < 30) {
            $rewardList = DB::table('reward')->get();
            if ($rewardList->isNotEmpty()) {
                $rows = [];
                for ($i = 0; $i < 50; $i++) {
                    $reward = $rewardList->random();
                    $rows[] = [
                        'tgl_input'    => Carbon::today()->subDays(rand(0, 90))->toDateString(),
                        'nis'          => $nisList[array_rand($nisList)],
                        'tingkat'      => 11,
                        'reward'       => $reward->detail_reward,
                        'point_reward' => $reward->skor,
                        'id_guru'      => $bkGuruId,
                    ];
                }
                DB::table('riwayat_reward')->insert($rows);
                $this->command->info('   [BK Reward] 50 record di-insert.');
            }
        } else {
            $this->command->info('   [BK Reward] sudah ada data, skip.');
        }

        // Buku Kasus (55 records)
        if (DB::table('buku_kasus')->count() < 30) {
            $judulPool  = ['Perkelahian antar siswa','Pelanggaran seragam berulang','Membolos 3 hari berturut','Membawa benda terlarang','Merusak inventaris kelas','Intimidasi teman sebaya','Ketidakhadiran berulang','Menyebarkan hoaks di media sosial'];
            $uraianPool = ['Siswa dilaporkan oleh wali kelas karena melakukan pelanggaran.','Pihak keamanan menemukan siswa sedang melanggar tata tertib.','Guru piket mendapati siswa berperilaku tidak sesuai aturan.','Wali kelas melaporkan keluhan terkait perilaku siswa di kelas.'];
            $tindakPool = ['Dipanggil ke ruang BK untuk konseling','Surat peringatan dikirim ke orang tua','Skorsing 1 hari + pembinaan','Mediasi antar pihak oleh guru BK','Pembinaan intensif oleh wali kelas'];
            $rows = [];
            for ($i = 0; $i < 55; $i++) {
                $rows[] = [
                    'nis'           => $nisList[array_rand($nisList)],
                    'tanggal'       => Carbon::today()->subDays(rand(0, 90))->toDateString(),
                    'judul_kasus'   => $judulPool[array_rand($judulPool)],
                    'uraian_kasus'  => $uraianPool[array_rand($uraianPool)],
                    'tindak_lanjut' => $tindakPool[array_rand($tindakPool)],
                    'status'        => ['proses','selesai'][rand(0, 1)],
                    'id_guru'       => $bkGuruId,
                    'created_at'    => now()->subDays(rand(0, 90)),
                    'updated_at'    => now(),
                ];
            }
            DB::table('buku_kasus')->insert($rows);
            $this->command->info('   [BK Buku Kasus] 55 record di-insert.');
        } else {
            $this->command->info('   [BK Buku Kasus] sudah ada data, skip.');
        }

        // Bimbingan Konseling (60 records)
        if (DB::table('bimbingan_konseling')->count() < 30) {
            $masalahPool = ['Masalah Keluarga','Masalah Pergaulan','Masalah Akademik','Kesehatan Mental','Konflik Teman','Kesulitan Belajar','Karir & Minat Studi'];
            $uraianPool  = ['Siswa mengalami kesulitan dalam penyesuaian diri di lingkungan sekolah.','Orang tua melaporkan adanya perubahan perilaku signifikan pada siswa.','Siswa mengeluhkan tekanan berlebih dari teman sebaya.','Wali kelas merujuk siswa karena penurunan prestasi akademik.'];
            $tindakPool  = ['Konseling individual selama 1 jam','Konseling kelompok bersama teman sebaya','Rujukan ke psikolog sekolah','Koordinasi intensif dengan orang tua','Pemantauan berkala mingguan'];
            $rows = [];
            for ($i = 0; $i < 60; $i++) {
                $rows[] = [
                    'tanggal'       => Carbon::today()->subDays(rand(0, 90))->toDateString(),
                    'nis'           => $nisList[array_rand($nisList)],
                    'jenis_masalah' => $masalahPool[array_rand($masalahPool)],
                    'uraian'        => $uraianPool[array_rand($uraianPool)],
                    'tindak_lanjut' => $tindakPool[array_rand($tindakPool)],
                    'status'        => ['proses','selesai'][rand(0, 1)],
                    'id_guru'       => $bkGuruId,
                ];
            }
            DB::table('bimbingan_konseling')->insert($rows);
            $this->command->info('   [BK Konseling] 60 record di-insert.');
        } else {
            $this->command->info('   [BK Konseling] sudah ada data, skip.');
        }

        // Home Visit (35 records)
        if (DB::table('home_visit')->count() < 20) {
            $tujuanPool = ['Monitoring siswa bermasalah','Koordinasi dengan orang tua','Konfirmasi ketidakhadiran berulang','Pendampingan psikologis','Verifikasi kondisi keluarga'];
            $hasilPool  = ['Orang tua kooperatif, akan dimonitor mingguan','Siswa dalam kondisi baik di rumah','Koordinasi berjalan lancar','Diperlukan tindak lanjut intensif','Keluarga membutuhkan pendampingan lanjutan'];
            $rows = [];
            for ($i = 0; $i < 35; $i++) {
                $nis = $nisList[array_rand($nisList)];
                $detail = DB::table('detail_siswa')->where('nis', $nis)->first();
                $rows[] = [
                    'tanggal_visit'    => Carbon::today()->subDays(rand(0, 90))->toDateString(),
                    'nis'              => $nis,
                    'alamat'           => $detail->alamat ?? 'Jl. Raya No.' . rand(1,100) . ', Yogyakarta',
                    'tujuan_kunjungan' => $tujuanPool[array_rand($tujuanPool)],
                    'hasil_kunjungan'  => $hasilPool[array_rand($hasilPool)],
                    'tindak_lanjut'    => ['Jadwalkan pertemuan ulang','Monitor mingguan','Koordinasi guru BK'][rand(0, 2)],
                    'status'           => ['dijadwalkan','selesai','batal'][rand(0, 2)],
                    'id_guru'          => $bkGuruId,
                    'created_at'       => now()->subDays(rand(0, 90)),
                    'updated_at'       => now(),
                ];
            }
            DB::table('home_visit')->insert($rows);
            $this->command->info('   [BK Home Visit] 35 record di-insert.');
        } else {
            $this->command->info('   [BK Home Visit] sudah ada data, skip.');
        }

        // Panggil Orang Tua (20 records)
        if (DB::table('panggil_ortu')->count() < 10) {
            $alasanPool = ['Pelanggaran berulang tata tertib sekolah','Penurunan drastis nilai akademik','Ketidakhadiran melebihi batas','Laporan bullying','Perkelahian di lingkungan sekolah'];
            $jenisPanggilan = ['panggilan_biasa','sp_1','sp_2'];
            $rows = [];
            for ($i = 0; $i < 20; $i++) {
                $nis    = $nisList[array_rand($nisList)];
                $date   = Carbon::today()->subDays(rand(0, 90))->toDateString();
                $noSurat = 'PS/' . str_pad($i + 1, 3, '0', STR_PAD_LEFT) . '/VII/2026';
                $detail  = DB::table('detail_siswa')->where('nis', $nis)->first();
                $rows[] = [
                    'no_surat'          => $noSurat,
                    'nis'               => $nis,
                    'tanggal_panggil'   => $date,
                    'waktu_pertemuan'   => sprintf('%02d:00:00', rand(9, 14)),
                    'lokasi_pertemuan'  => 'Ruang Bimbingan Konseling (BK)',
                    'nama_ortu'         => $detail->nama_ayah ?? 'Orang Tua Siswa',
                    'no_hp_ortu'        => $detail->no_telp_ayah ?? ('0812' . rand(10000000, 99999999)),
                    'jenis_panggilan'   => $jenisPanggilan[array_rand($jenisPanggilan)],
                    'alasan_panggil'    => $alasanPool[array_rand($alasanPool)],
                    'hasil_pertemuan'   => null,
                    'status'            => 'belum_hadir',
                    'id_guru'           => $bkGuruId,
                    'created_at'        => now()->subDays(rand(0, 90)),
                    'updated_at'        => now(),
                ];
            }
            DB::table('panggil_ortu')->insertOrIgnore($rows);
            $this->command->info('   [BK Panggil Ortu] 20 record di-insert.');
        } else {
            $this->command->info('   [BK Panggil Ortu] sudah ada data, skip.');
        }
    }

    // ══════════════════════════════════════════════════════════════
    // 10. ISMUBA
    // ══════════════════════════════════════════════════════════════
    private function seedIsmuba(): void
    {
        $nisList   = DB::table('user_siswa')->where('status','aktif')->pluck('nis')->toArray();
        $kelasList = DB::table('kelas')->where('status','aktif')->get();
        $ismubaId  = DB::table('guru')->where('guru_ismuba','ya')->value('id_guru')
                     ?? DB::table('guru')->value('id_guru');

        if (!$ismubaId) {
            $this->command->warn('   [ISMUBA] tidak ada guru, skip.');
            return;
        }

        // BTAQ (120+ records)
        if (DB::table('btaq')->count() < 80) {
            $iqroPages  = DB::table('tabel_iqro')->get();
            $quranPages = DB::table('tabel_alquran')->take(300)->get();

            $rows   = [];
            $sample = array_slice($nisList, 0, min(80, count($nisList)));
            foreach ($sample as $nis) {
                $kelasId = DB::table('user_siswa')->where('nis', $nis)->value('id_kelas');
                for ($s = 0; $s < rand(2, 4); $s++) {
                    if (rand(0, 1) && $quranPages->isNotEmpty()) {
                        $pg    = $quranPages->random();
                        $awal  = $pg->surat . ':' . $pg->ayat;
                        $akhir = $pg->surat . ':' . min($pg->ayat + rand(2, 5), 286);
                        $level = 'alquran';
                    } elseif ($iqroPages->isNotEmpty()) {
                        $pg    = $iqroPages->random();
                        $awal  = 'Jilid ' . $pg->jilid . ' Hal.' . $pg->halaman;
                        $akhir = 'Jilid ' . $pg->jilid . ' Hal.' . min($pg->halaman + rand(1, 3), 10);
                        $level = 'iqro' . $pg->jilid;
                    } else {
                        continue;
                    }
                    $rows[] = [
                        'tanggal'  => Carbon::today()->subDays(rand(0, 60))->toDateString(),
                        'nis'      => $nis,
                        'id_kelas' => $kelasId,
                        'level'    => $level,
                        'awal'     => $awal,
                        'akhir'    => $akhir,
                        'id_guru'  => $ismubaId,
                    ];
                }
            }
            foreach (array_chunk($rows, 100) as $chunk) {
                DB::table('btaq')->insert($chunk);
            }
            $this->command->info('   [ISMUBA BTAQ] ' . count($rows) . ' record di-insert.');
        } else {
            $this->command->info('   [ISMUBA BTAQ] sudah ada data, skip.');
        }

        // Tadarus (60+ records)
        if (DB::table('tadarus')->count() < 50) {
            $surahDef = [
                ['Al-Baqarah', 286],['Ali Imran', 200],['An-Nisa', 176],
                ['Al-Maidah', 120],['Al-Kahf', 110],['Ya Sin', 83],
                ['Ar-Rahman', 78],['Al-Waqiah', 96],['Al-Mulk', 30],['Al-Ikhlas', 4],
            ];
            $rows = [];
            foreach ($kelasList->take(10) as $kelas) {
                for ($day = 1; $day <= 30; $day++) {
                    $date = Carbon::today()->subDays($day)->toDateString();
                    $dow  = Carbon::parse($date)->dayOfWeek;
                    if ($dow === 0 || $dow === 6) continue;
                    if (DB::table('tadarus')->where('tanggal', $date)->where('id_kelas', $kelas->id_kelas)->exists()) continue;

                    [$surahName, $maxAyat] = $surahDef[array_rand($surahDef)];
                    $awalAyat  = rand(1, max(1, intval($maxAyat / 2)));
                    $akhirAyat = min($awalAyat + rand(3, 10), $maxAyat);
                    $rows[] = [
                        'tanggal'     => $date,
                        'id_kelas'    => $kelas->id_kelas,
                        'awal_surat'  => $surahName,
                        'awal_ayat'   => $awalAyat,
                        'akhir_surat' => $surahName,
                        'akhir_ayat'  => $akhirAyat,
                        'id_guru'     => $ismubaId,
                    ];
                }
            }
            if (!empty($rows)) {
                foreach (array_chunk($rows, 100) as $chunk) {
                    DB::table('tadarus')->insert($chunk);
                }
            }
            $this->command->info('   [ISMUBA Tadarus] ' . count($rows) . ' record di-insert.');
        } else {
            $this->command->info('   [ISMUBA Tadarus] sudah ada data, skip.');
        }

        // Pantau Ibadah (150+ records)
        if (DB::table('pantau_ibadah')->count() < 100) {
            $jenisPool   = ['sholat_fardu','sholat_jenazah','gerakan_wudhu'];
            $nilaiPool   = ['A','A','A','B','B','C'];
            $catatanPool = [null,'Perlu perbaikan bacaan','Gerakan sudah baik','Sangat baik','Perlu latihan lebih'];
            $rows   = [];
            $sample = array_slice($nisList, 0, min(60, count($nisList)));
            foreach ($sample as $nis) {
                $kelasId = DB::table('user_siswa')->where('nis', $nis)->value('id_kelas');
                foreach ($jenisPool as $jenis) {
                    $rows[] = [
                        'tanggal'      => Carbon::today()->subDays(rand(0, 30))->toDateString(),
                        'nis'          => $nis,
                        'id_kelas'     => $kelasId,
                        'id_guru'      => $ismubaId,
                        'jenis_ibadah' => $jenis,
                        'nilai'        => $nilaiPool[array_rand($nilaiPool)],
                        'catatan'      => $catatanPool[array_rand($catatanPool)],
                    ];
                }
            }
            foreach (array_chunk($rows, 200) as $chunk) {
                DB::table('pantau_ibadah')->insert($chunk);
            }
            $this->command->info('   [ISMUBA Ibadah] ' . count($rows) . ' record di-insert.');
        } else {
            $this->command->info('   [ISMUBA Ibadah] sudah ada data, skip.');
        }
    }

    // ══════════════════════════════════════════════════════════════
    // 11. JADWAL PENGAJIAN + KEHADIRAN
    // ══════════════════════════════════════════════════════════════
    private function seedPengajian(): void
    {
        $guruIds     = DB::table('guru')->where('status','aktif')->pluck('id_guru')->toArray();
        $karyawanIds = DB::table('karyawan')->where('status','aktif')->pluck('id_karyawan')->toArray();

        // Tambah beberapa jadwal pengajian
        $jadwalPool = [
            ['Pengajian Rutin Juni Minggu 1',  'Masjid Al-Falah, Sekolah',        'Guru & Karyawan', '2026-06-01'],
            ['Pengajian Rutin Juni Minggu 3',  'Aula Utama SMK Muh 1',             'Guru & Karyawan', '2026-06-15'],
            ['Pengajian Rutin Juli Minggu 1',  'Masjid Hidayatullah, Sleman',      'Guru & Karyawan', '2026-07-01'],
            ['Kajian Islam Spesial',           'Masjid At-Taqwa, Kaliurang',       'Guru',            '2026-06-08'],
            ['Pengajian Karyawan Juni',        'Aula Perpustakaan',                'Karyawan',        '2026-06-22'],
        ];

        foreach ($jadwalPool as [$nama, $tempat, $penerima, $tgl]) {
            if (!DB::table('jadwal_pengajian')->where('tanggal', $tgl)->where('tempat', $tempat)->exists()) {
                $idJadwal = DB::table('jadwal_pengajian')->insertGetId([
                    'nama_kegiatan' => $nama,
                    'tanggal'       => $tgl,
                    'tempat'        => $tempat,
                    'lokasi_gmaps'  => null,
                    'keterangan'    => 'Pengajian rutin bulanan ' . $penerima,
                    'created_at'    => now(),
                    'updated_at'    => now(),
                ]);

                // Generate kehadiran
                $kehadiranRows = [];
                $statusPool    = ['hadir','hadir','hadir','hadir','ijin','alpha'];

                if (str_contains($penerima, 'Guru')) {
                    foreach ($guruIds as $idGuru) {
                        $kehadiranRows[] = [
                            'id_jadwal'    => $idJadwal,
                            'id_guru'      => $idGuru,
                            'id_karyawan'  => null,
                            'status'       => $statusPool[array_rand($statusPool)],
                            'jam_absen'    => rand(0, 1) ? sprintf('%02d:%02d:00', rand(7,9), rand(0,59)) : null,
                            'foto'         => null,
                            'lokasi_gmaps' => null,
                            'keterangan'   => null,
                            'created_at'   => now(),
                            'updated_at'   => now(),
                        ];
                    }
                }

                if (str_contains($penerima, 'Karyawan')) {
                    foreach ($karyawanIds as $idKaryawan) {
                        $kehadiranRows[] = [
                            'id_jadwal'    => $idJadwal,
                            'id_guru'      => null,
                            'id_karyawan'  => $idKaryawan,
                            'status'       => $statusPool[array_rand($statusPool)],
                            'jam_absen'    => rand(0, 1) ? sprintf('%02d:%02d:00', rand(7,9), rand(0,59)) : null,
                            'foto'         => null,
                            'lokasi_gmaps' => null,
                            'keterangan'   => null,
                            'created_at'   => now(),
                            'updated_at'   => now(),
                        ];
                    }
                }

                if (!empty($kehadiranRows)) {
                    foreach (array_chunk($kehadiranRows, 100) as $chunk) {
                        DB::table('kehadiran_pengajian')->insert($chunk);
                    }
                }
            }
        }
        $totalJadwal    = DB::table('jadwal_pengajian')->count();
        $totalKehadiran = DB::table('kehadiran_pengajian')->count();
        $this->command->info("   [Pengajian] {$totalJadwal} jadwal + {$totalKehadiran} kehadiran di-insert.");
    }

    // ══════════════════════════════════════════════════════════════
    // 12. TUGAS + TAGIHAN TUGAS
    // ══════════════════════════════════════════════════════════════
    private function seedTugasTagihan(): void
    {
        $guruIds  = DB::table('guru')->where('status','aktif')->pluck('id_guru')->toArray();
        $kelasList = DB::table('kelas')->where('status','aktif')->get();
        $mapelIds  = DB::table('mapel')->pluck('id_mapel')->toArray();

        if (empty($guruIds) || $kelasList->isEmpty() || empty($mapelIds)) {
            $this->command->warn('   [Tugas] data tidak cukup, skip.');
            return;
        }

        $existingTugas = DB::table('tugas')->count();
        if ($existingTugas >= 20) {
            $this->command->info("   [Tugas] sudah ada {$existingTugas} data, skip.");
            return;
        }

        $judulPool = [
            'Latihan Soal BAB 3', 'Proyek Akhir Semester', 'Resume Materi',
            'Tugas Kelompok', 'Presentasi Hasil Observasi', 'Analisis Kasus',
            'Praktikum Lab', 'Laporan Kegiatan', 'Soal Ulangan Harian', 'Esai Argumentatif'
        ];

        $tugasIds = [];
        $tugasRows = [];
        foreach ($kelasList->take(8) as $kelas) {
            for ($t = 0; $t < 3; $t++) {
                $idGuru = $guruIds[array_rand($guruIds)];
                $tglTugas = Carbon::today()->subDays(rand(3, 30))->toDateString();
                $tugasRows[] = [
                    'tanggal'    => $tglTugas,
                    'id_guru'    => $idGuru,
                    'judul_tugas'=> $judulPool[array_rand($judulPool)],
                    'id_kelas'   => $kelas->id_kelas,
                    'deskripsi'  => 'Kerjakan tugas ini dengan baik dan benar. Deadline sesuai tanggal yang tertera.',
                    'lampiran'   => null,
                    'status'     => 'aktif',
                ];
            }
        }

        foreach ($tugasRows as $tugas) {
            $idTugas = DB::table('tugas')->insertGetId($tugas);
            $tugasIds[] = ['id' => $idTugas, 'id_kelas' => $tugas['id_kelas']];
        }
        $this->command->info('   [Tugas] ' . count($tugasRows) . ' tugas di-insert.');

        // Generate tagihan per siswa dalam kelas
        $tagihanRows = [];
        foreach ($tugasIds as $tugas) {
            $siswaKelas = DB::table('user_siswa')
                ->where('id_kelas', $tugas['id_kelas'])
                ->where('status', 'aktif')
                ->pluck('nis');

            foreach ($siswaKelas as $nis) {
                $statusPool = ['belum','belum','belum','sudah','cek'];
                $tagihanRows[] = [
                    'id_tugas'     => $tugas['id'],
                    'nis'          => $nis,
                    'deskripsi'    => null,
                    'upload_tugas' => null,
                    'status_tugas' => $statusPool[array_rand($statusPool)],
                ];
            }
        }

        foreach (array_chunk($tagihanRows, 200) as $chunk) {
            DB::table('tagihan_tugas')->insertOrIgnore($chunk);
        }
        $this->command->info('   [Tagihan Tugas] ' . count($tagihanRows) . ' tagihan di-insert.');
    }

    // ══════════════════════════════════════════════════════════════
    // HELPER: Tampilkan sample akun
    // ══════════════════════════════════════════════════════════════
    private function printSampleAccounts(): void
    {
        // Siswa
        $siswa = DB::table('user_siswa')->where('status','aktif')->first();
        if ($siswa) {
            $kelas = DB::table('kelas')->where('id_kelas', $siswa->id_kelas)->first();
            $this->command->info("  [SISWA]");
            $this->command->info("    role: siswa");
            $this->command->info("    id (NIS): {$siswa->nis}");
            $this->command->info("    nama: {$siswa->nama_siswa}");
            $this->command->info("    kelas: " . ($kelas->rombel ?? '-'));
        }

        // Orang Tua (sama akun siswa, password berbeda)
        if ($siswa) {
            $this->command->info("  [ORANG TUA]");
            $this->command->info("    role: orang_tua");
            $this->command->info("    id (NIS anak): {$siswa->nis}");
            $this->command->info("    nama anak: {$siswa->nama_siswa}");
        }

        // Guru
        $guru = DB::table('guru')->where('status','aktif')->first();
        if ($guru) {
            $this->command->info("  [GURU]");
            $this->command->info("    role: guru");
            $this->command->info("    id (no_id): {$guru->no_id}");
            $this->command->info("    nama: {$guru->nama_guru}");
        }

        // Karyawan
        $karyawan = DB::table('karyawan')->where('status','aktif')->first();
        if ($karyawan) {
            $this->command->info("  [KARYAWAN]");
            $this->command->info("    role: karyawan");
            $this->command->info("    id (no_id): {$karyawan->no_id}");
            $this->command->info("    nama: {$karyawan->nama_karyawan}");
        }
    }
}
