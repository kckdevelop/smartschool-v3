<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

/**
 * MasterDataSeeder v2
 * ─────────────────────────────────────────────────────────────────────────────
 * Mengisi SEMUA fitur SmartSchool dengan data dummy realistis (50+ per fitur):
 *  - Guru, Jurusan, Kelas, Siswa
 *  - Presensi (300+)
 *  - UKS: Jenis Checkup, Kunjungan UKS, Data Checkup
 *  - BK: Jenis Pelanggaran, Reward, Riwayat Poin, Riwayat Reward,
 *        Buku Kasus, Bimbingan Konseling, Home Visit, Gaya Belajar
 *  - ISMUBA: BTAQ (120+), Tadarus (60+), Pantau Ibadah (150+)
 *  - PKL: DUDI (10), Gelombang (3), Kelas Gelombang, Pembimbing (10),
 *         Penempatan (60), Persuratan (16)
 */
class MasterDataSeeder extends Seeder
{
    private array $namaL   = ['Ahmad','Bagas','Chandra','Danu','Eko','Fajar','Guntur','Hadi','Ilham','Joko','Kurnia','Lukman','Mulyono','Nugroho','Oki','Prabowo','Rian','Setyo','Taufik','Umar','Wahyu','Yanto','Zulkifli','Arif','Bagus','Dimas','Erfan','Galih','Hendra','Ivan'];
    private array $namaP   = ['Ani','Citra','Dewi','Endah','Fitri','Gita','Hana','Indah','Kartika','Laras','Mega','Novi','Putri','Rina','Siti','Tari','Utami','Wulan','Yuni','Zahra','Lia','Rara','Ayu','Bella','Cahya','Diana','Eka','Fani','Hesti','Intan'];
    private array $namaBlk = ['Santoso','Wibowo','Pratama','Hidayat','Saputra','Kusuma','Sari','Lestari','Putra','Setiawan','Nugraha','Wijaya','Siregar','Harahap','Nasution','Ginting','Sembiring','Ramadhan','Firmansyah','Purnama'];
    private array $kota    = ['Yogyakarta','Sleman','Bantul','Kulon Progo','Gunungkidul','Surakarta','Klaten','Magelang','Semarang','Purworejo'];

    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        $this->seedGuru();
        $this->seedJurusanKelas();
        $this->seedSiswa();
        $this->seedPresensi();
        $this->seedUks();
        $this->seedBk();
        $this->seedIsmuba();
        $this->seedPkl();

        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        $this->command->info('✅ MasterDataSeeder selesai — semua fitur terisi data.');
    }

    // ══════════════════════════════════════════════════════════════
    //  1. GURU
    // ══════════════════════════════════════════════════════════════
    private function seedGuru(): void
    {
        $existing = DB::table('guru')->count();
        if ($existing >= 15) {
            $this->command->info('   [Guru] sudah cukup (' . $existing . '), skip.');
            return;
        }

        $guruData = [
            [1001,'Budi Santoso, S.Pd.','tidak','tidak'],
            [1002,'Sri Wahyuni, M.Pd.','ya','tidak'],
            [1003,'Agus Priyanto, S.T.','tidak','tidak'],
            [1004,'Dewi Kartika, S.Pd.','tidak','tidak'],
            [1005,'Hendra Saputra, M.Pd.','tidak','tidak'],
            [1006,'Ratna Wulandari, S.Pd.','ya','tidak'],
            [1007,'Fauzi Rahman, S.Kom.','tidak','tidak'],
            [1008,'Siti Aisyah, S.Ag.','tidak','ya'],
            [1009,'Nugroho Aji, S.Pd.','tidak','ya'],
            [1010,'Mega Lestari, M.Pd.','tidak','tidak'],
            [1011,'Dian Kusuma, S.Pd.','tidak','tidak'],
            [1012,'Bagas Firmansyah, S.T.','tidak','tidak'],
            [1013,'Fitri Handayani, S.Pd.','ya','tidak'],
            [1014,'Eko Prasetyo, S.Pd.','tidak','tidak'],
            [1015,'Yanti Purnama, M.Pd.','tidak','tidak'],
            [1016,'Rudi Hartono, S.Pd.','tidak','ya'],
            [1017,'Intan Pertiwi, S.Pd.','tidak','tidak'],
            [1018,'Gita Cahyani, S.Pd.','tidak','tidak'],
            [1019,'Wahyu Hidayat, S.Pd.','tidak','tidak'],
            [1020,'Tari Anggita, S.Pd.','tidak','tidak'],
        ];
        $rows = [];
        foreach ($guruData as [$noid, $nama, $bk, $ismuba]) {
            $rows[] = [
                'no_id'       => $noid,
                'nama_guru'   => $nama,
                'guru_bk'     => $bk,
                'guru_ismuba' => $ismuba,
                'status'      => 'aktif',
                'password'    => Hash::make('123456'),
            ];
        }
        DB::table('guru')->insertOrIgnore($rows);
        $this->command->info('   [Guru] ' . count($rows) . ' guru di-insert.');
    }

    // ══════════════════════════════════════════════════════════════
    //  2. JURUSAN & KELAS
    // ══════════════════════════════════════════════════════════════
    private function seedJurusanKelas(): void
    {
        $jurusanDef = [
            ['RPL','Rekayasa Perangkat Lunak'],
            ['TKJ','Teknik Komputer & Jaringan'],
            ['TSM','Teknik Sepeda Motor'],
            ['AKL','Akuntansi & Keuangan Lembaga'],
            ['OTKP','Otomatisasi Tata Kelola Perkantoran'],
        ];
        foreach ($jurusanDef as [$kode, $nama]) {
            if (!DB::table('jurusan')->where('kode_jurusan', $kode)->exists()) {
                DB::table('jurusan')->insert(['kode_jurusan'=>$kode,'nama_jurusan'=>$nama,'status'=>'aktif']);
            }
        }

        $jurusanList = DB::table('jurusan')->get();
        $guruIds     = DB::table('guru')->pluck('id_guru')->toArray();
        $guruIdx     = 0;

        foreach ($jurusanList as $jur) {
            for ($r = 1; $r <= 2; $r++) {
                $rombel = $jur->kode_jurusan . ' ' . $r;
                if (!DB::table('kelas')->where('id_jurusan',$jur->id_jurusan)->where('rombel',$rombel)->where('tingkat',11)->exists()) {
                    DB::table('kelas')->insert([
                        'tahun_masuk' => '2025',
                        'tingkat'     => 11,
                        'id_jurusan'  => $jur->id_jurusan,
                        'rombel'      => $rombel,
                        'walikelas'   => $guruIds[$guruIdx % count($guruIds)] ?? null,
                        'status'      => 'aktif',
                    ]);
                    $guruIdx++;
                }
            }
        }
        $this->command->info('   [Kelas] Jurusan & kelas selesai.');
    }

    // ══════════════════════════════════════════════════════════════
    //  3. SISWA (120+)
    // ══════════════════════════════════════════════════════════════
    private function seedSiswa(): void
    {
        $kelasIds = DB::table('kelas')->where('tingkat',11)->pluck('id_kelas')->toArray();
        if (empty($kelasIds)) return;

        $maxNis   = DB::table('user_siswa')->max('nis') ?? 2026999;
        $nis      = max((int)$maxNis, 2027000);
        $students = [];
        $details  = [];

        foreach ($kelasIds as $kelasId) {
            for ($i = 0; $i < 13; $i++) {
                $nis++;
                $jenkel    = ($i % 3 === 0) ? 'P' : 'L';
                $firstName = $jenkel === 'L'
                    ? $this->namaL[array_rand($this->namaL)]
                    : $this->namaP[array_rand($this->namaP)];
                $lastName  = $this->namaBlk[array_rand($this->namaBlk)];
                $kotaItem  = $this->kota[array_rand($this->kota)];

                $students[] = [
                    'nis'           => $nis,
                    'password'      => '7c4a8d09ca3762af61e59520943dc26494f8941b',
                    'password_wali' => '7c4a8d09ca3762af61e59520943dc26494f8941b',
                    'id_kelas'      => $kelasId,
                    'nama_siswa'    => $firstName . ' ' . $lastName,
                    'jenkel'        => $jenkel,
                    'tempat_lahir'  => $kotaItem,
                    'tgl_lahir'     => Carbon::create(2008, rand(1,12), rand(1,28))->toDateString(),
                    'kelengkapan'   => 1,
                    'status'        => 'aktif',
                ];
                $details[] = [
                    'nis'            => $nis,
                    'alamat'         => 'Jl. Raya No.' . rand(1,100) . ', ' . $kotaItem,
                    'agama'          => 'Islam',
                    'golongan_darah' => ['A','B','AB','O'][rand(0,3)],
                    'nama_ayah'      => $this->namaL[array_rand($this->namaL)] . ' ' . $this->namaBlk[array_rand($this->namaBlk)],
                    'pekerjaan_ayah' => ['Wiraswasta','PNS','Karyawan','Buruh','Pedagang'][rand(0,4)],
                    'no_telp_ayah'   => '0812' . rand(10000000, 99999999),
                    'nama_ibu'       => $this->namaP[array_rand($this->namaP)] . ' ' . $this->namaBlk[array_rand($this->namaBlk)],
                    'pekerjaan_ibu'  => ['IRT','Karyawan','PNS','Wiraswasta'][rand(0,3)],
                    'no_telp_ibu'    => '0857' . rand(10000000, 99999999),
                    'nama_wali'      => null,
                    'pekerjaan_wali' => null,
                    'no_telp_wali'   => null,
                    'created_at'     => now(),
                    'updated_at'     => now(),
                ];
            }
        }

        foreach (array_chunk($students, 50) as $chunk) {
            DB::table('user_siswa')->insertOrIgnore($chunk);
        }
        foreach (array_chunk($details, 50) as $chunk) {
            DB::table('detail_siswa')->insertOrIgnore($chunk);
        }
        $this->command->info('   [Siswa] ' . count($students) . ' siswa di-insert.');
    }

    // ══════════════════════════════════════════════════════════════
    //  4. PRESENSI (300+)
    // ══════════════════════════════════════════════════════════════
    private function seedPresensi(): void
    {
        if (DB::table('presensi')->count() >= 200) {
            $this->command->info('   [Presensi] sudah ada ' . DB::table('presensi')->count() . ' data, skip.');
            return;
        }

        $nisList    = DB::table('user_siswa')->where('status','aktif')->pluck('nis')->toArray();
        $statusPool = [1,1,1,1,1,1,2,3,4]; // hadir dominan

        $rows = [];
        for ($day = 30; $day >= 1; $day--) {
            $date = Carbon::today()->subDays($day)->toDateString();
            $dow  = Carbon::parse($date)->dayOfWeek;
            if ($dow === 0 || $dow === 6) continue;

            $sample = array_slice($nisList, 0, min(45, count($nisList)));
            foreach ($sample as $nis) {
                $status = $statusPool[array_rand($statusPool)];
                $rows[] = [
                    'nis'        => $nis,
                    'tanggal'    => $date,
                    'jam'        => $status == 2 ? '07:' . rand(16,45) . ':00' : '07:0' . rand(1,9) . ':00',
                    'status'     => $status,
                    'keterangan' => $status == 3 ? ['Sakit','Izin','Keperluan Keluarga'][rand(0,2)] : null,
                    'file'       => null,
                ];
            }
        }

        foreach (array_chunk($rows, 200) as $chunk) {
            DB::table('presensi')->insert($chunk);
        }
        $this->command->info('   [Presensi] ' . count($rows) . ' record di-insert.');
    }

    // ══════════════════════════════════════════════════════════════
    //  5. UKS
    // ══════════════════════════════════════════════════════════════
    private function seedUks(): void
    {
        $nisList = DB::table('user_siswa')->where('status','aktif')->pluck('nis')->toArray();

        // ── Jenis Checkup ─────────────────────────────────────────
        // Schema: jenis_checkup (string 50), status enum
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

        // ── Kunjungan UKS (80) ────────────────────────────────────
        if (DB::table('kunjungan_uks')->count() < 50) {
            $keluhan  = ['Pusing dan mual','Sakit perut','Demam ringan','Lemas','Mimisan','Cedera olahraga','Sakit gigi','Alergi kulit','Batuk pilek','Mata merah'];
            $diagnosa = ['Dehidrasi','Gastritis','Febris','Kelelahan','Epistaksis','Contusio','Pulpitis','Dermatitis','ISPA','Konjungtivitis'];
            $tindakan = ['Istirahat + minum','Antasida oral','Parasetamol','Vitamin B','Penekanan','Kompres dingin','Berkumur','Salep antihistamin','Sirup OBH','Tetes mata'];
            $rows = [];
            for ($i = 0; $i < 80; $i++) {
                $idx = rand(0, count($keluhan)-1);
                $rows[] = [
                    'nis'      => $nisList[array_rand($nisList)],
                    'tanggal'  => Carbon::today()->subDays(rand(0,60))->toDateString(),
                    'jam'      => sprintf('%02d:%02d:00', rand(7,14), rand(0,59)),
                    'keluhan'  => $keluhan[$idx],
                    'diagnosa' => $diagnosa[$idx],
                    'tindakan' => $tindakan[$idx],
                ];
            }
            DB::table('kunjungan_uks')->insert($rows);
            $this->command->info('   [UKS Kunjungan] ' . count($rows) . ' record di-insert.');
        }

        // ── Data Checkup ──────────────────────────────────────────
        // Schema: tanggal, jam, nis, jenis_checkup (string), nilai (int), satuan
        if (DB::table('data_checkup')->count() < 50) {
            $jenisPool = ['Penimbangan Berat Badan','Pengukuran Tinggi Badan','Tes Tajam Penglihatan','Pemeriksaan Tekanan Darah','Pemeriksaan Gigi & Mulut'];
            $rows = [];
            $sample = array_slice($nisList, 0, min(80, count($nisList)));
            foreach ($sample as $nis) {
                $jenis = $jenisPool[array_rand($jenisPool)];
                [$nilai, $satuan] = match($jenis) {
                    'Penimbangan Berat Badan'    => [rand(45,75), 'kg'],
                    'Pengukuran Tinggi Badan'    => [rand(150,175), 'cm'],
                    'Tes Tajam Penglihatan'      => [rand(6,10), '/10'],
                    'Pemeriksaan Tekanan Darah'  => [rand(110,130), 'mmHg'],
                    default                      => [rand(1,10), 'skor'],
                };
                $rows[] = [
                    'nis'           => $nis,
                    'tanggal'       => Carbon::today()->subDays(rand(0,90))->toDateString(),
                    'jam'           => sprintf('%02d:%02d:00', rand(8,14), rand(0,59)),
                    'jenis_checkup' => $jenis,
                    'nilai'         => $nilai,
                    'satuan'        => $satuan,
                ];
            }
            DB::table('data_checkup')->insert($rows);
            $this->command->info('   [UKS Checkup] ' . count($rows) . ' record di-insert.');
        }
    }

    // ══════════════════════════════════════════════════════════════
    //  6. BK
    // ══════════════════════════════════════════════════════════════
    private function seedBk(): void
    {
        $nisList  = DB::table('user_siswa')->where('status','aktif')->pluck('nis')->toArray();
        $guruIds  = DB::table('guru')->pluck('id_guru')->toArray();
        $bkGuruId = DB::table('guru')->where('guru_bk','ya')->value('id_guru') ?? $guruIds[0];

        // ── Jenis Pelanggaran ─────────────────────────────────────
        // Schema: jenis_pelanggaran (string 50) only — no kategori/poin in original
        if (DB::table('jenis_pelanggaran')->count() < 5) {
            DB::table('jenis_pelanggaran')->insertOrIgnore([
                ['jenis_pelanggaran' => 'Terlambat Masuk Sekolah'],
                ['jenis_pelanggaran' => 'Tidak Membawa Buku'],
                ['jenis_pelanggaran' => 'Seragam Tidak Lengkap'],
                ['jenis_pelanggaran' => 'Berkelahi'],
                ['jenis_pelanggaran' => 'Membawa HP Saat Pelajaran'],
                ['jenis_pelanggaran' => 'Membolos'],
                ['jenis_pelanggaran' => 'Merokok'],
                ['jenis_pelanggaran' => 'Tidak Mengerjakan PR'],
                ['jenis_pelanggaran' => 'Tidak Ikut Upacara'],
                ['jenis_pelanggaran' => 'Merusak Fasilitas Sekolah'],
            ]);
        }

        // ── Reward ────────────────────────────────────────────────
        // Schema: detail_reward (string 100), skor (int)
        if (DB::table('reward')->count() < 5) {
            DB::table('reward')->insertOrIgnore([
                ['detail_reward'=>'Juara 1 Kelas','skor'=>20],
                ['detail_reward'=>'Juara Olimpiade Sains','skor'=>30],
                ['detail_reward'=>'Atlet Berprestasi','skor'=>25],
                ['detail_reward'=>'Siswa Berprestasi Semester','skor'=>15],
                ['detail_reward'=>'Aktif OSIS','skor'=>10],
                ['detail_reward'=>'Juara Lomba Seni','skor'=>20],
                ['detail_reward'=>'Absensi Terbaik Bulan Ini','skor'=>10],
            ]);
        }

        // ── Riwayat Poin (Pelanggaran) ────────────────────────────
        // Schema: tgl_input, nis, tingkat, pelanggaran (string), poin, id_guru
        if (DB::table('riwayat_poin')->count() < 50) {
            $jenisList = DB::table('jenis_pelanggaran')->get();
            $poinMap   = ['Terlambat Masuk Sekolah'=>5,'Tidak Membawa Buku'=>5,'Seragam Tidak Lengkap'=>10,'Berkelahi'=>30,'Membawa HP Saat Pelajaran'=>15,'Membolos'=>20,'Merokok'=>40,'Tidak Mengerjakan PR'=>5,'Tidak Ikut Upacara'=>15,'Merusak Fasilitas Sekolah'=>35];
            $rows = [];
            for ($i = 0; $i < 80; $i++) {
                $jenis = $jenisList->random();
                $rows[] = [
                    'tgl_input'   => Carbon::today()->subDays(rand(0,90))->toDateString(),
                    'nis'         => $nisList[array_rand($nisList)],
                    'tingkat'     => 11,
                    'pelanggaran' => $jenis->jenis_pelanggaran,
                    'poin'        => $poinMap[$jenis->jenis_pelanggaran] ?? 10,
                    'id_guru'     => $bkGuruId,
                ];
            }
            DB::table('riwayat_poin')->insert($rows);
            $this->command->info('   [BK Pelanggaran] ' . count($rows) . ' record di-insert.');
        }

        // ── Riwayat Reward ────────────────────────────────────────
        // Schema: tgl_input, nis, tingkat, reward (string), point_reward, id_guru
        if (DB::table('riwayat_reward')->count() < 30) {
            $rewardList = DB::table('reward')->get();
            $rows = [];
            for ($i = 0; $i < 50; $i++) {
                $reward = $rewardList->random();
                $rows[] = [
                    'tgl_input'    => Carbon::today()->subDays(rand(0,90))->toDateString(),
                    'nis'          => $nisList[array_rand($nisList)],
                    'tingkat'      => 11,
                    'reward'       => $reward->detail_reward,
                    'point_reward' => $reward->skor,
                    'id_guru'      => $bkGuruId,
                ];
            }
            DB::table('riwayat_reward')->insert($rows);
            $this->command->info('   [BK Reward] ' . count($rows) . ' record di-insert.');
        }

        // ── Buku Kasus ────────────────────────────────────────────
        if (DB::table('buku_kasus')->count() < 30) {
            $judulPool  = ['Perkelahian antar siswa','Pelanggaran seragam berulang','Membolos 3 hari','Membawa benda terlarang','Merusak inventaris kelas','Intimidasi teman','Ketidakhadiran berulang','Menyebarkan hoaks'];
            $uraianPool = ['Siswa dilaporkan oleh wali kelas karena ','Pihak keamanan menemukan siswa ','Guru piket mendapati siswa '];
            $tindakPool = ['Dipanggil ke ruang BK','Surat peringatan ke orang tua','Skorsing 1 hari','Mediasi antar pihak','Pembinaan wali kelas'];
            $rows = [];
            for ($i = 0; $i < 55; $i++) {
                $rows[] = [
                    'nis'           => $nisList[array_rand($nisList)],
                    'tanggal'       => Carbon::today()->subDays(rand(0,90))->toDateString(),
                    'judul_kasus'   => $judulPool[array_rand($judulPool)],
                    'uraian_kasus'  => $uraianPool[array_rand($uraianPool)] . 'melakukan pelanggaran di lingkungan sekolah.',
                    'tindak_lanjut' => $tindakPool[array_rand($tindakPool)],
                    'status'        => ['proses','selesai'][rand(0,1)],
                    'id_guru'       => $bkGuruId,
                    'created_at'    => now(),
                    'updated_at'    => now(),
                ];
            }
            DB::table('buku_kasus')->insert($rows);
            $this->command->info('   [BK Buku Kasus] ' . count($rows) . ' record di-insert.');
        }

        // ── Bimbingan Konseling ───────────────────────────────────
        // Schema: tanggal, nis, jenis_masalah, uraian, tindak_lanjut, status, id_guru
        if (DB::table('bimbingan_konseling')->count() < 30) {
            $masalahPool = ['Masalah Keluarga','Masalah Pergaulan','Masalah Akademik','Kesehatan Mental','Konflik Teman','Kesulitan Belajar','Karir & Minat'];
            $uraianPool  = ['Siswa mengalami kesulitan dalam penyesuaian diri.','Orang tua melaporkan perubahan perilaku siswa.','Siswa mengeluhkan tekanan dari teman sebaya.','Wali kelas merujuk siswa untuk konseling.'];
            $tindakPool  = ['Konseling individual','Konseling kelompok','Rujuk ke psikolog','Koordinasi dengan orang tua','Pemantauan berkala'];
            $rows = [];
            for ($i = 0; $i < 60; $i++) {
                $rows[] = [
                    'tanggal'       => Carbon::today()->subDays(rand(0,90))->toDateString(),
                    'nis'           => $nisList[array_rand($nisList)],
                    'jenis_masalah' => $masalahPool[array_rand($masalahPool)],
                    'uraian'        => $uraianPool[array_rand($uraianPool)],
                    'tindak_lanjut' => $tindakPool[array_rand($tindakPool)],
                    'status'        => ['proses','selesai'][rand(0,1)],
                    'id_guru'       => $bkGuruId,
                ];
            }
            DB::table('bimbingan_konseling')->insert($rows);
            $this->command->info('   [BK Bimbingan Konseling] ' . count($rows) . ' record di-insert.');
        }

        // ── Home Visit ────────────────────────────────────────────
        // Schema: tanggal_visit, nis, alamat, tujuan_kunjungan, hasil_kunjungan, tindak_lanjut, status, id_guru
        if (DB::table('home_visit')->count() < 20) {
            $tujuanPool = ['Monitoring siswa bermasalah','Koordinasi dengan orang tua','Konfirmasi ketidakhadiran berulang','Pendampingan psikologis','Verifikasi kondisi keluarga'];
            $hasilPool  = ['Orang tua kooperatif, akan dimonitor','Siswa dalam kondisi baik','Koordinasi berjalan lancar','Diperlukan tindak lanjut intensif','Keluarga membutuhkan pendampingan'];
            $rows = [];
            for ($i = 0; $i < 35; $i++) {
                $nis = $nisList[array_rand($nisList)];
                $detail = DB::table('detail_siswa')->where('nis', $nis)->first();
                $rows[] = [
                    'tanggal_visit'    => Carbon::today()->subDays(rand(0,90))->toDateString(),
                    'nis'              => $nis,
                    'alamat'           => $detail->alamat ?? 'Jl. Raya No.' . rand(1,100),
                    'tujuan_kunjungan' => $tujuanPool[array_rand($tujuanPool)],
                    'hasil_kunjungan'  => $hasilPool[array_rand($hasilPool)],
                    'tindak_lanjut'    => ['Jadwalkan pertemuan ulang','Monitor mingguan','Koordinasi guru BK'][rand(0,2)],
                    'status'           => ['dijadwalkan','selesai','batal'][rand(0,2)],
                    'id_guru'          => $bkGuruId,
                    'created_at'       => now(),
                    'updated_at'       => now(),
                ];
            }
            DB::table('home_visit')->insert($rows);
            $this->command->info('   [BK Home Visit] ' . count($rows) . ' record di-insert.');
        }

        // ── Gaya Belajar ──────────────────────────────────────────
        // Schema: nis, gaya_belajar, minat, catatan, id_guru
        if (DB::table('gaya_belajar')->count() < 50) {
            $gayaPool  = ['visual','auditori','kinestetik'];
            $minatPool = ['Menggambar','Musik','Olahraga','Fotografi','Debat','Memasak','Komputer','Membaca','Robotika','Seni Tari'];
            $rows = [];
            $sample = array_slice($nisList, 0, min(100, count($nisList)));
            foreach ($sample as $nis) {
                if (DB::table('gaya_belajar')->where('nis',$nis)->exists()) continue;
                $gaya   = $gayaPool[array_rand($gayaPool)];
                $rows[] = [
                    'nis'          => $nis,
                    'gaya_belajar' => $gaya,
                    'minat'        => $minatPool[array_rand($minatPool)],
                    'catatan'      => 'Siswa cenderung belajar dengan gaya ' . $gaya . '.',
                    'id_guru'      => $bkGuruId,
                    'created_at'   => now()->subDays(rand(0,180)),
                    'updated_at'   => now(),
                ];
            }
            if (!empty($rows)) {
                DB::table('gaya_belajar')->insert($rows);
            }
            $this->command->info('   [BK Gaya Belajar] ' . count($rows) . ' record di-insert.');
        }
    }

    // ══════════════════════════════════════════════════════════════
    //  7. ISMUBA
    // ══════════════════════════════════════════════════════════════
    private function seedIsmuba(): void
    {
        $nisList   = DB::table('user_siswa')->where('status','aktif')->pluck('nis')->toArray();
        $kelasList = DB::table('kelas')->where('tingkat',11)->get();
        $ismubaId  = DB::table('guru')->where('guru_ismuba','ya')->value('id_guru')
                     ?? DB::table('guru')->value('id_guru');

        // ── BTAQ (120+) ───────────────────────────────────────────
        // Schema: tanggal, nis, id_kelas, level, awal, akhir, id_guru
        if (DB::table('btaq')->count() < 80) {
            $iqroPages  = DB::table('tabel_iqro')->get();
            $quranPages = DB::table('tabel_alquran')->take(200)->get(); // ambil 200 record saja

            $rows   = [];
            $sample = array_slice($nisList, 0, min(60, count($nisList)));
            foreach ($sample as $nis) {
                $kelasId = DB::table('user_siswa')->where('nis',$nis)->value('id_kelas');
                for ($s = 0; $s < rand(2,3); $s++) {
                    if (rand(0,1) && $quranPages->isNotEmpty()) {
                        $pg    = $quranPages->random();
                        $awal  = $pg->surat . ':' . $pg->ayat;
                        $akhir = $pg->surat . ':' . min($pg->ayat + rand(2,5), 286);
                        $level = 'alquran';
                    } elseif ($iqroPages->isNotEmpty()) {
                        $pg    = $iqroPages->random();
                        $awal  = 'Jilid ' . $pg->jilid . ' Hal.' . $pg->halaman;
                        $akhir = 'Jilid ' . $pg->jilid . ' Hal.' . min($pg->halaman + rand(1,3), 10);
                        $level = 'iqro' . $pg->jilid;
                    } else {
                        continue;
                    }
                    $rows[] = [
                        'tanggal'  => Carbon::today()->subDays(rand(0,60))->toDateString(),
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
        }

        // ── Tadarus (60+) ─────────────────────────────────────────
        // Schema: tanggal, id_kelas, awal_surat, awal_ayat, akhir_surat, akhir_ayat, id_guru
        if (DB::table('tadarus')->count() < 50) {
            $surahDef = [
                ['Al-Baqarah',286],['Ali Imran',200],['An-Nisa',176],
                ['Al-Maidah',120],['Al-Kahf',110],['Ya Sin',83],
                ['Ar-Rahman',78],['Al-Waqiah',96],['Al-Mulk',30],
                ['Al-Ikhlas',4],
            ];
            $rows = [];
            foreach ($kelasList as $kelas) {
                for ($day = 1; $day <= 25; $day++) {
                    $date = Carbon::today()->subDays($day)->toDateString();
                    $dow  = Carbon::parse($date)->dayOfWeek;
                    if ($dow === 0 || $dow === 6) continue;
                    if (DB::table('tadarus')->where('tanggal',$date)->where('id_kelas',$kelas->id_kelas)->exists()) continue;

                    [$surahName, $maxAyat] = $surahDef[array_rand($surahDef)];
                    $awalAyat  = rand(1, max(1, intval($maxAyat/2)));
                    $akhirAyat = min($awalAyat + rand(3,10), $maxAyat);

                    $rows[] = [
                        'tanggal'    => $date,
                        'id_kelas'   => $kelas->id_kelas,
                        'awal_surat' => $surahName,
                        'awal_ayat'  => $awalAyat,
                        'akhir_surat'=> $surahName,
                        'akhir_ayat' => $akhirAyat,
                        'id_guru'    => $ismubaId,
                    ];
                }
            }
            if (!empty($rows)) {
                foreach (array_chunk($rows, 100) as $chunk) {
                    DB::table('tadarus')->insert($chunk);
                }
            }
            $this->command->info('   [ISMUBA Tadarus] ' . count($rows) . ' record di-insert.');
        }

        // ── Pantau Ibadah (150+) ──────────────────────────────────
        // Schema: tanggal, nis, id_kelas, id_guru, jenis_ibadah, nilai, catatan
        if (DB::table('pantau_ibadah')->count() < 100) {
            $jenisPool = ['sholat_fardu','sholat_jenazah','gerakan_wudhu'];
            $nilaiPool = ['A','A','A','B','B','C'];
            $catatanPool = [null,'Perlu perbaikan bacaan','Gerakan sudah baik','Perlu latihan','Sangat baik'];
            $rows   = [];
            $sample = array_slice($nisList, 0, min(50, count($nisList)));
            foreach ($sample as $nis) {
                $kelasId = DB::table('user_siswa')->where('nis',$nis)->value('id_kelas');
                foreach ($jenisPool as $jenis) {
                    $rows[] = [
                        'tanggal'      => Carbon::today()->subDays(rand(0,30))->toDateString(),
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
        }
    }

    // ══════════════════════════════════════════════════════════════
    //  8. PKL
    // ══════════════════════════════════════════════════════════════
    private function seedPkl(): void
    {
        $guruIds = DB::table('guru')->pluck('id_guru')->toArray();

        // ── Nomor Surat ───────────────────────────────────────────
        DB::table('pkl_nomor_surat')->insertOrIgnore([
            ['jenis_surat'=>'permohonan','format_nomor'=>'{NO}/PM/PKL/SMKM1/{BULAN-ROMAWI}/{TAHUN}','prefix'=>null,'counter_terakhir'=>0,'tahun_reset'=>date('Y'),'created_at'=>now(),'updated_at'=>now()],
            ['jenis_surat'=>'penempatan','format_nomor'=>'{NO}/PP/PKL/SMKM1/{BULAN-ROMAWI}/{TAHUN}','prefix'=>null,'counter_terakhir'=>0,'tahun_reset'=>date('Y'),'created_at'=>now(),'updated_at'=>now()],
            ['jenis_surat'=>'penarikan','format_nomor'=>'{NO}/PT/PKL/SMKM1/{BULAN-ROMAWI}/{TAHUN}','prefix'=>null,'counter_terakhir'=>0,'tahun_reset'=>date('Y'),'created_at'=>now(),'updated_at'=>now()],
        ]);

        // ── DUDI (10) ─────────────────────────────────────────────
        if (DB::table('pkl_dudi')->count() < 8) {
            $dudis = [
                ['PT Solusi Teknologi Nusantara','IT & Software House','Jl. Kaliurang KM 10','Sleman','Budi Prasetyo','HR Manager',8],
                ['CV Nusa Computindo','Jaringan & IT Support','Jl. Gejayan No. 12','Sleman','Sri Astuti','Supervisor IT',5],
                ['PT Arindo Data Prima','Data Center & Cloud','Jl. Monjali No. 5','Yogyakarta','Hendra Wijaya','IT Director',6],
                ['PT Maju Bersama Digital','Aplikasi Mobile','Jl. Sudirman No. 18','Yogyakarta','Intan Cahya','PM',5],
                ['CV Karya Inovasi','Web Development','Jl. Seturan Raya No.7','Sleman','Dimas Pratama','CTO',4],
                ['PT Global Niaga Nusantara','E-Commerce','Jl. Ring Road Utara No.12','Yogyakarta','Eko Saputra','HRD',7],
                ['Bengkel Agung Motor Utama','Otomotif & Service','Jl. Magelang KM 5','Yogyakarta','Agus Budiman','Kepala Bengkel',4],
                ['UD Mandiri Jaya','Teknik & Konstruksi','Jl. Wates No. 20','Kulon Progo','Setyo Nugroho','Owner',3],
                ['PT Sigma Consulindo','Konsultan IT','Jl. Palagan No. 9','Sleman','Fitri Rahayu','Manajer',5],
                ['CV Kreasi Grafis','Desain Grafis & Multimedia','Jl. Bantul KM 3','Bantul','Gita Cahyani','Direktur',4],
            ];
            $dudiRows = [];
            foreach ($dudis as $d) {
                $dudiRows[] = [
                    'nama_dudi'    => $d[0],
                    'bidang_usaha' => $d[1],
                    'alamat'       => $d[2],
                    'kota'         => $d[3],
                    'nama_pic'     => $d[4],
                    'jabatan_pic'  => $d[5],
                    'kuota_siswa'  => $d[6],
                    'no_telepon'   => '0274-' . rand(100000,999999),
                    'email'        => strtolower(preg_replace('/[^a-z0-9]/i','',substr($d[0],0,15))) . '@email.com',
                    'no_hp_pic'    => '0812' . rand(10000000,99999999),
                    'status'       => 'aktif',
                    'created_at'   => now(),
                    'updated_at'   => now(),
                ];
            }
            DB::table('pkl_dudi')->insertOrIgnore($dudiRows);
            $this->command->info('   [PKL DUDI] ' . count($dudiRows) . ' DUDI di-insert.');
        }

        // ── Gelombang ─────────────────────────────────────────────
        DB::table('pkl_gelombang')->insertOrIgnore([
            ['id_gelombang'=>1,'nama_gelombang'=>'PKL Gelombang I 2026','tahun_ajaran'=>'2025/2026','tanggal_mulai'=>'2026-07-01','tanggal_selesai'=>'2026-09-30','status'=>'aktif','keterangan'=>'Gelombang utama semester ganjil 2026','created_at'=>now(),'updated_at'=>now()],
            ['id_gelombang'=>2,'nama_gelombang'=>'PKL Gelombang II 2026','tahun_ajaran'=>'2025/2026','tanggal_mulai'=>'2026-10-01','tanggal_selesai'=>'2026-12-31','status'=>'draft','keterangan'=>'Gelombang cadangan semester genap','created_at'=>now(),'updated_at'=>now()],
            ['id_gelombang'=>3,'nama_gelombang'=>'PKL Gelombang I 2025','tahun_ajaran'=>'2024/2025','tanggal_mulai'=>'2025-07-01','tanggal_selesai'=>'2025-09-30','status'=>'selesai','keterangan'=>'Gelombang tahun lalu sudah selesai','created_at'=>now(),'updated_at'=>now()],
        ]);

        // ── Mapping Kelas ke Gelombang ────────────────────────────
        $kelasIds = DB::table('kelas')->where('tingkat',11)->pluck('id_kelas')->toArray();
        foreach ($kelasIds as $kelasId) {
            DB::table('pkl_kelas_gelombang')->insertOrIgnore([
                'id_gelombang' => 1,
                'id_kelas'     => $kelasId,
                'created_at'   => now(),
                'updated_at'   => now(),
            ]);
        }

        // ── Pembimbing PKL ────────────────────────────────────────
        $dudiIds = DB::table('pkl_dudi')->pluck('id_dudi')->toArray();
        if (DB::table('pkl_pembimbing')->count() < 8) {
            $guruShuffled = $guruIds;
            shuffle($guruShuffled);
            $pembimbingRows = [];
            foreach ($dudiIds as $i => $dudiId) {
                $pembimbingRows[] = [
                    'id_gelombang' => 1,
                    'id_guru'      => $guruShuffled[$i % count($guruShuffled)],
                    'id_dudi'      => $dudiId,
                    'created_at'   => now(),
                    'updated_at'   => now(),
                ];
            }
            DB::table('pkl_pembimbing')->insertOrIgnore($pembimbingRows);
            $this->command->info('   [PKL Pembimbing] ' . count($pembimbingRows) . ' record di-insert.');
        }

        // ── Penempatan (60) ───────────────────────────────────────
        if (DB::table('pkl_penempatan')->count() < 40) {
            $nisList       = DB::table('user_siswa')->whereIn('id_kelas',$kelasIds)->where('status','aktif')->pluck('nis')->toArray();
            $pembimbingIds = DB::table('pkl_pembimbing')->where('id_gelombang',1)->pluck('id_pembimbing')->toArray();

            // Build kuota tracker
            $kuotaTracker = [];
            foreach ($dudiIds as $did) {
                $kuotaTracker[$did] = DB::table('pkl_dudi')->where('id_dudi',$did)->value('kuota_siswa') ?? 5;
            }

            $usedNis = [];
            $rows    = [];
            shuffle($nisList);

            foreach ($nisList as $nis) {
                if (isset($usedNis[$nis])) continue;

                // Cari DUDI yang masih ada kuota
                $availDudi = array_filter($dudiIds, fn($d) => ($kuotaTracker[$d] ?? 0) > 0);
                if (empty($availDudi)) break;
                $dudiId = $availDudi[array_rand($availDudi)];
                $kuotaTracker[$dudiId]--;

                $usedNis[$nis] = true;
                $status = ['aktif','aktif','aktif','aktif','selesai'][rand(0,4)];

                $rows[] = [
                    'id_gelombang'   => 1,
                    'id_dudi'        => $dudiId,
                    'nis'            => $nis,
                    'id_pembimbing'  => !empty($pembimbingIds) ? $pembimbingIds[array_rand($pembimbingIds)] : null,
                    'tanggal_masuk'  => '2026-07-01',
                    'tanggal_keluar' => '2026-09-30',
                    'status'         => $status,
                    'keterangan'     => null,
                    'created_at'     => now(),
                    'updated_at'     => now(),
                ];

                if (count($rows) >= 60) break;
            }

            foreach (array_chunk($rows, 50) as $chunk) {
                DB::table('pkl_penempatan')->insertOrIgnore($chunk);
            }
            $this->command->info('   [PKL Penempatan] ' . count($rows) . ' record di-insert.');
        }

        // ── Persuratan ────────────────────────────────────────────
        if (DB::table('pkl_persuratan')->count() < 10) {
            $sampleDudis = array_slice($dudiIds, 0, 8);
            $roman = ['I','II','III','IV','V','VI','VII','VIII','IX','X','XI','XII'][date('n')-1];
            $rows    = [];
            $counter = 1;
            foreach ($sampleDudis as $dudiId) {
                foreach (['permohonan','penempatan'] as $jenis) {
                    $abbr  = $jenis === 'permohonan' ? 'PM' : 'PP';
                    $rows[] = [
                        'nomor_surat'   => sprintf('%03d/%s/PKL/SMKM1/%s/%s', $counter++, $abbr, $roman, date('Y')),
                        'jenis_surat'   => $jenis,
                        'id_gelombang'  => 1,
                        'id_dudi'       => $dudiId,
                        'tanggal_surat' => Carbon::today()->subDays(rand(1,30))->toDateString(),
                        'hal'           => ucfirst($jenis) . ' PKL Siswa SMK Muhammadiyah 1 Yogyakarta',
                        'file_pdf'      => null,
                        'dicetak_oleh'  => 1,
                        'created_at'    => now(),
                        'updated_at'    => now(),
                    ];
                }
            }
            DB::table('pkl_persuratan')->insert($rows);
            $this->command->info('   [PKL Persuratan] ' . count($rows) . ' surat di-insert.');
        }
    }
}
