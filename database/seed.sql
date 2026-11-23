-- =====================================================================
-- Data dummy demo - Aplikasi Presensi Siswa SMK TI Airlangga
-- =====================================================================
-- Seluruh nama, NISN, dan alamat pada file ini adalah data fiktif.
-- Jalankan setelah database/schema.sql.
--
-- PERHATIAN: file ini mengosongkan tabel presensi, siswa, dan kelas
-- sebelum mengisi ulang data demo. Akun admin (tabel users) tidak diubah.

USE presensi_smk;

DELETE FROM presensi;
DELETE FROM siswa;
DELETE FROM kelas;

ALTER TABLE kelas AUTO_INCREMENT = 1;

INSERT INTO kelas (id, nama_kelas, jurusan) VALUES
(1, 'X RPL 1', 'Rekayasa Perangkat Lunak'),
(2, 'X TKJ 1', 'Teknik Komputer dan Jaringan'),
(3, 'XI RPL 1', 'Rekayasa Perangkat Lunak'),
(4, 'XI TKJ 1', 'Teknik Komputer dan Jaringan'),
(5, 'XII RPL 1', 'Rekayasa Perangkat Lunak');

ALTER TABLE kelas AUTO_INCREMENT = 6;

INSERT INTO siswa (nisn, nama, alamat, id_kelas) VALUES
('0061842301', 'Muhammad Fajar Ramadhan', 'Jl. Pangeran Antasari, Samarinda', 1),
('0061842302', 'Nadia Putri Lestari', 'Jl. Lambung Mangkurat, Samarinda', 1),
('0061842303', 'Rizky Maulana Saputra', 'Jl. DI Panjaitan, Samarinda', 1),
('0061842304', 'Aulia Rahmawati', 'Jl. Juanda, Samarinda', 1),
('0061842305', 'Andi Pratama', 'Jl. AW Syahranie, Samarinda', 1),
('0061842311', 'Dimas Aditya Nugraha', 'Jl. PM Noor, Samarinda', 2),
('0061842312', 'Siti Nurhaliza', 'Jl. Mugirejo, Samarinda', 2),
('0061842313', 'Muhammad Farhan', 'Jl. Perjuangan, Samarinda', 2),
('0051732201', 'Rafi Alamsyah', 'Jl. Kadrie Oening, Samarinda', 3),
('0051732202', 'Intan Permata Sari', 'Jl. Pramuka, Samarinda', 3),
('0051732203', 'Fikri Hidayatullah', 'Jl. Suryanata, Samarinda', 3),
('0051732204', 'Nabila Khairunnisa', 'Jl. PM Noor, Samarinda', 3),
('0051732211', 'Yoga Saputra', 'Jl. Rapak Indah, Samarinda', 4),
('0051732212', 'Dewi Anggraini', 'Jl. Sentosa, Samarinda', 4),
('0041622101', 'Ardiansyah Putra', 'Jl. M. Yamin, Samarinda', 5),
('0041622102', 'Melati Safitri', 'Jl. Siradj Salman, Samarinda', 5);

-- Contoh presensi untuk data demo (tanggal mengikuti tanggal import).
INSERT INTO presensi (nisn, tanggal_waktu) VALUES
('0061842301', CONCAT(CURDATE(), ' 07:02:15')),
('0061842302', CONCAT(CURDATE(), ' 07:05:41')),
('0061842303', CONCAT(CURDATE(), ' 07:11:08')),
('0061842311', CONCAT(CURDATE(), ' 07:08:22')),
('0051732201', CONCAT(CURDATE(), ' 06:58:34')),
('0051732202', CONCAT(CURDATE(), ' 07:14:03')),
('0051732211', CONCAT(CURDATE(), ' 07:06:19'));
