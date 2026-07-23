# Dokumentasi API SmartSchool

Dokumentasi ini menjelaskan penggunaan antarmuka pemrograman aplikasi (API) untuk SmartSchool. Seluruh endpoint dilindungi menggunakan otentikasi berbasis **Laravel Sanctum** (token bearer), kecuali beberapa endpoint publik yang ditandai khusus.

---

## 🔒 Otentikasi & Autorisasi

Sebelum memanggil endpoint yang dilindungi, Anda harus mendapatkan token akses terlebih dahulu menggunakan endpoint **Login**.

### 1. Login Pengguna (Web / Dashboard)
Mengembalikan token otentikasi dan informasi profil singkat untuk pengguna web (staff/admin) dan siswa.

*   **URL:** `/api/auth/login`
*   **Method:** `POST`
*   **Headers:** `Content-Type: application/x-www-form-urlencoded` atau `application/json`
*   **Request Body:**
    *   `username` (string, required): Nomor Induk Siswa (NIS) untuk siswa, atau username staf/guru.
    *   `password` (string, required): Password akun.
*   **Response (200 OK):**
    ```json
    {
        "success": true,
        "token": "3|AbCdEfGhIjKlMnOpQrStUvWxYz1234567890",
        "user": {
            "id": 123456,
            "username": "123456",
            "nama": "Siswa Cerdas",
            "role": "siswa",
            "type": "siswa"
        }
    }
    ```

### 2. Login Mobile (Siswa, Orang Tua, Guru, Karyawan)
Otentikasi khusus aplikasi mobile dengan membedakan metode otentikasi berdasarkan peran.

*   **URL:** `/api/mobile/login`
*   **Method:** `POST`
*   **Headers:** `Content-Type: application/json`
*   **Request Body:**
    *   `role` (string, required): `"siswa"`, `"orang_tua"`, `"guru"`, atau `"karyawan"`.
    *   `id` (string, required): NIS (untuk `siswa`/`orang_tua`) atau Nomor ID/NBM (untuk `guru`/`karyawan`).
    *   `password` (string, required): Password masing-masing user.
*   **Response (200 OK):**
    *   **Role: Siswa**
        ```json
        {
            "success": true,
            "message": "Login berhasil.",
            "token": "3|AbCdEf...",
            "user": {
                "id": 12345,
                "nis": 12345,
                "nama": "Siswa Cerdas",
                "role": "siswa",
                "jenkel": "L",
                "id_kelas": 1,
                "kelas": "X-RPL"
            }
        }
        ```
    *   **Role: Orang Tua**
        ```json
        {
            "success": true,
            "message": "Login berhasil.",
            "token": "3|AbCdEf...",
            "user": {
                "id": 12345,
                "nis": 12345,
                "nama": "Wali / Orang Tua Siswa Cerdas",
                "nama_anak": "Siswa Cerdas",
                "role": "orang_tua",
                "id_kelas": 1,
                "kelas": "X-RPL"
            }
        }
        ```
    *   **Role: Guru**
        ```json
        {
            "success": true,
            "message": "Login berhasil.",
            "token": "3|AbCdEf...",
            "user": {
                "id": 1,
                "no_id": "19800101",
                "nama": "Budi Utomo, S.Pd.",
                "role": "guru",
                "guru_bk": 0,
                "guru_ismuba": 0
            }
        }
        ```
    *   **Role: Karyawan**
        ```json
        {
            "success": true,
            "message": "Login berhasil.",
            "token": "3|AbCdEf...",
            "user": {
                "id": 1,
                "no_id": "20201010",
                "nama": "Siti Rahma",
                "role": "karyawan"
            }
        }
        ```

### 3. Dapatkan Profil Login
Mendapatkan info profil pengguna yang aktif berdasarkan token Bearer yang dikirim. Mendukung endpoint Web (`/api/auth/me`) dan Mobile (`/api/mobile/me`).

*   **URL:** `/api/auth/me` atau `/api/mobile/me`
*   **Method:** `GET`
*   **Headers:** `Authorization: Bearer <your_token>`
*   **Response (200 OK):**
    *   **User: Siswa**
        ```json
        {
            "success": true,
            "data": {
                "id": 12345,
                "nis": 12345,
                "nama": "Siswa Cerdas",
                "role": "siswa",
                "type": "siswa",
                "id_kelas": 1
            }
        }
        ```
    *   **User: Orang Tua**
        ```json
        {
            "success": true,
            "data": {
                "id": 12345,
                "nis": 12345,
                "nama": "Wali / Orang Tua Siswa Cerdas",
                "nama_anak": "Siswa Cerdas",
                "role": "orang_tua",
                "type": "siswa",
                "id_kelas": 1
            }
        }
        ```
    *   **User: Guru**
        ```json
        {
            "success": true,
            "data": {
                "id": 1,
                "no_id": "19800101",
                "nama": "Budi Utomo, S.Pd.",
                "role": "guru",
                "type": "guru",
                "guru_bk": 0,
                "guru_ismuba": 0
            }
        }
        ```
    *   **User: Karyawan**
        ```json
        {
            "success": true,
            "data": {
                "id": 1,
                "no_id": "20201010",
                "nama": "Siti Rahma",
                "role": "karyawan",
                "type": "karyawan"
            }
        }
        ```
    *   **User: Staff/Admin**
        ```json
        {
            "success": true,
            "data": {
                "id": 12,
                "username": "admin",
                "nama": "Administrator",
                "role": "admin",
                "type": "staff"
            }
        }
        ```

### 4. Logout
Mencabut token yang digunakan saat ini. Mendukung endpoint Web (`/api/auth/logout`) dan Mobile (`/api/mobile/logout`).

*   **URL:** `/api/auth/logout` atau `/api/mobile/logout`
*   **Method:** `POST`
*   **Headers:** `Authorization: Bearer <your_token>`
*   **Response (200 OK):**
    ```json
    {
        "success": true,
        "message": "Logged out successfully"
    }
    ```

---

## 🏫 Modul Atur Data Lanjutan

### 1. Data Karyawan
*   **GET `/api/atur-data/karyawan`**: Menampilkan daftar karyawan (bisa difilter `search` & `status`).
*   **POST `/api/atur-data/karyawan`**: Menambahkan karyawan baru.
    *   *Body:* `no_id` (int), `nama_karyawan` (string), `status` ('aktif'/'tidak'), `password` (string).
*   **GET `/api/atur-data/karyawan/{id}`**: Detail karyawan.
*   **PUT `/api/atur-data/karyawan/{id}`**: Perbarui data karyawan.
*   **DELETE `/api/atur-data/karyawan/{id}`**: Hapus karyawan.
*   **PATCH `/api/atur-data/karyawan/{id}/reset-password`**: Reset password karyawan.

### 2. Jam Pelajaran
*   **GET `/api/atur-jam`**: Daftar jam pelajaran. Filter `aktif=1` untuk jam aktif saja.
*   **POST `/api/atur-jam`**: Tambah jam pelajaran.
    *   *Body:* `jam_ke` (int), `label` (string), `jam_mulai` (format `HH:MM`), `jam_selesai` (format `HH:MM`), `aktif` (bool).
*   **POST `/api/atur-jam/update-aktif`**: Set status aktif jam pelajaran secara bulk.
    *   *Body:* `jam_ids` (array of integers, e.g. `[1, 2, 3]`).

### 3. Jadwal Mengajar Guru
*   **GET `/api/jadwal-mengajar/template`**: Daftar acuan jadwal template siklus mingguan (bisa difilter `id_guru`, `id_kelas`, `hari_siklus`).
*   **POST `/api/jadwal-mengajar/template`**: Tambah item jadwal template baru (dilengkapi pengecekan bentrok guru & kelas otomatis).
*   **POST `/api/jadwal-mengajar/generate-harian`**: Generate jadwal harian riil dari template ke kalender akademik.
    *   *Body:* `tanggal_mulai` (date), `tanggal_selesai` (date).

---

## 📚 Modul Akademik & Presensi

### 1. Presensi Siswa
*   **GET `/api/presensi-siswa`**: List data presensi siswa harian (bisa difilter `tanggal`, `id_kelas`, `nis`, `status`).
*   **POST `/api/presensi-siswa`**: Input data presensi individu.
*   **POST `/api/presensi-siswa/bulk`**: Input presensi masal satu kelas sekaligus.
    *   *Body:*
        ```json
        {
            "tanggal": "2026-07-04",
            "presensi": [
                { "nis": 12345, "status": "Hadir", "keterangan": "Masuk tepat waktu" },
                { "nis": 12346, "status": "Izin", "keterangan": "Acara keluarga" }
            ]
        }
        ```
*   **GET `/api/presensi-siswa/rekap`**: Rekap total status (Hadir, Sakit, Izin, Alfa) per siswa dalam satu kelas.
    *   *Query Params:* `id_kelas` (required), `bulan` (required, format `YYYY-MM`).

### 2. Jurnal Guru
*   **GET `/api/jurnal-guru`**: Menampilkan daftar jurnal mengajar guru (bisa difilter `tanggal`, `id_kelas`, `status`).
*   **POST `/api/jurnal-guru`**: Membuat jurnal mengajar baru.
*   **POST `/api/jurnal-guru/{id}/approve`**: Persetujuan jurnal (oleh wali kelas/staf).
*   **POST `/api/jurnal-guru/{id}/reject`**: Penolakan jurnal dengan menyertakan `catatan_penolakan`.

---

## 🕌 Modul ISMUBA (Keagamaan)

### 1. BTAQ (Baca Tulis Al-Quran)
*   **GET `/api/ismuba/btaq`**: List riwayat kemajuan bacaan BTAQ siswa (Iqro/Al-Quran).
*   **POST `/api/ismuba/btaq`**: Input perkembangan BTAQ siswa.
    *   *Body:* `nis`, `tanggal`, `tipe` ('iqro'/'alquran'), `jilid_surat` (string), `halaman_ayat` (string), `keterangan` (string).

### 2. Tadarus Kelas
*   **GET `/api/ismuba/tadarus`**: Catatan tadarus kelas harian.
*   **POST `/api/ismuba/tadarus`**: Tambah catatan tadarus kelas (menyimpan info surat/ayat mulai & selesai beserta pembaca terakhir).

### 3. Pantauan Ibadah Harian
*   **GET `/api/ismuba/ibadah`**: Daftar pantauan ibadah harian siswa.
*   **POST `/api/ismuba/ibadah`**: Catat kepatuhan ibadah (Shubuh, Dhuhur, Ashar, Maghrib, Isya dengan opsi `Berjamaah`/`Munfarid`/`Tidak`, serta checklist `tadarus`, `dhuha`, `tahajud`).

### 4. Jadwal Pengajian
*   **GET `/api/ismuba/jadwal-pengajian`**: Daftar pengajian guru dan karyawan.
*   **POST `/api/ismuba/jadwal-pengajian`**: Buat kegiatan pengajian baru (secara otomatis men-generate catatan kehadiran berstatus `alpha` untuk semua staf aktif).
*   **POST `/api/ismuba/jadwal-pengajian/{id}/kehadiran`**: Simpan rekap absen pengajian guru & karyawan.
    *   *Body:* `kehadiran` (array of `id_kehadiran`, `status` ['hadir'/'ijin'/'alpha'], `keterangan`).

### 5. Laporan ISMUBA
*   **GET `/api/ismuba/laporan`**: Rekap keaktifan BTAQ & sholat berjamaah siswa dalam 1 kelas per bulan.
    *   *Query Params:* `id_kelas`, `bulan` (`YYYY-MM`).

---

## 🧑‍💼 Modul BK (Bimbingan Konseling)

### 1. Pelanggaran & Reward
*   **GET `/api/bk/kategori-pelanggaran`** & **GET `/api/bk/kategori-reward`**: List Master poin pelanggaran dan reward.
*   **POST `/api/bk/pelanggaran`**: Catat pelanggaran siswa (menambahkan total skor poin pelanggaran siswa bersangkutan secara otomatis).
*   **POST `/api/bk/reward`**: Catat penghargaan prestasi siswa (secara otomatis mengurangi akumulasi poin pelanggaran siswa).

### 2. Buku Kasus & Konsultasi
*   **GET `/api/bk/buku-kasus`**: Log kasus siswa bermasalah dan tindak lanjutnya.
*   **POST `/api/bk/buku-konsultasi`**: Catat log konseling/bimbingan privat siswa dengan guru BK.

### 3. Home Visit & Panggilan Orang Tua
*   **GET `/api/bk/home-visit`**: Catatan kunjungan rumah oleh guru BK.
*   **POST `/api/bk/panggil-ortu`**: Buat surat pemanggilan orang tua siswa.
    *   *Body:* `nis`, `tanggal`, `alasan`, `no_surat` (unique), `keterangan`.

### 4. Gaya Belajar Siswa
*   **POST `/api/bk/gaya-belajar`**: Set atau perbarui hasil tes diagnosis gaya belajar siswa (Visual, Auditori, atau Kinestetik).

---

## 🏥 Modul UKS (Unit Kesehatan Sekolah)

*   **GET `/api/uks/jenis-checkup`**: Master parameter jenis pemeriksaan kesehatan UKS (e.g. Tinggi Badan, Berat Badan, Mata, dll).
*   **POST `/api/uks/kunjungan`**: Catat kunjungan siswa sakit ke ruang UKS beserta keluhan, obat, dan tindakan yang diberikan.
*   **POST `/api/uks/checkup`**: Catat hasil pemeriksaan fisik berkala siswa.
*   **GET `/api/uks/laporan`**: Laporan bulanan rekap kesehatan siswa per kelas (menghitung frekuensi kunjungan & pemeriksaan).

---

## 🏭 Modul PKL (Praktik Kerja Lapangan)

*   **GET `/api/pkl/gelombang`**: Daftar gelombang/periode waktu pelaksanaan PKL.
*   **GET `/api/pkl/dudi`**: Daftar industri/mitra DUDI tempat magang.
*   **GET `/api/pkl/pembimbing`**: Daftar guru yang ditugaskan sebagai pembimbing PKL.
*   **POST `/api/pkl/penempatan`**: Tempatkan siswa di industri dengan pembimbing tertentu.
*   **POST `/api/pkl/pindah-penempatan`**: Pindahkan siswa yang sedang aktif magang ke lokasi industri baru (secara otomatis memutasi status penempatan lama menjadi 'pindah' dan mencatat log mutasi).
*   **POST `/api/pkl/persuratan/generate`**: Generate surat pengantar/tugas format otomatis.

---

## 🤖 Modul Generator Soal AI

*   **GET `/api/generator-soal/soal`**: Riwayat hasil generate paket soal ujian yang dibuat menggunakan modul AI.
*   **GET `/api/generator-soal/kisi-kisi`**: Riwayat dokumen kisi-kisi instrumen ujian yang telah di-generate sebelumnya.

---

## 💡 Petunjuk Penggunaan (Contoh Request)

Menggunakan program **cURL** di Terminal:

```bash
# 1. Login untuk mendapatkan Token
curl -X POST http://127.0.0.1:8000/api/auth/login \
  -H "Content-Type: application/json" \
  -d '{"username": "admin", "password": "password123"}'

# 2. Ambil data Siswa menggunakan Token (Ganti <TOKEN> dengan token hasil login)
curl -X GET http://127.0.0.1:8000/api/atur-data/siswa \
  -H "Authorization: Bearer <TOKEN>" \
  -H "Accept: application/json"
```
