-- Patch: Tambah nilai 'kuis' ke kolom ENUM 'tipe' di tabel lms_tugas
-- Jalankan query ini di phpMyAdmin atau MySQL console pada server hosting

ALTER TABLE `lms_tugas`
MODIFY COLUMN `tipe` ENUM('pdf', 'gambar', 'teks', 'kuis') NOT NULL DEFAULT 'pdf';

-- Verifikasi hasil:
-- SHOW COLUMNS FROM `lms_tugas` LIKE 'tipe';
