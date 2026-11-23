-- =====================================================================
-- Aplikasi Presensi Siswa - SMK TI Airlangga Samarinda
-- Struktur database (MySQL 8 / MariaDB 10.4+)
-- =====================================================================
-- PERHATIAN: file ini menghapus tabel lama lalu membuat ulang.
-- Gunakan hanya untuk instalasi awal atau reset data demo.

CREATE DATABASE IF NOT EXISTS presensi_smk
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci;

USE presensi_smk;

DROP TABLE IF EXISTS presensi;
DROP TABLE IF EXISTS siswa;
DROP TABLE IF EXISTS kelas;
DROP TABLE IF EXISTS users;

CREATE TABLE users (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    nama_lengkap VARCHAR(100) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE kelas (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    nama_kelas VARCHAR(50) NOT NULL UNIQUE,
    jurusan VARCHAR(100) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE siswa (
    nisn CHAR(10) PRIMARY KEY,
    nama VARCHAR(120) NOT NULL,
    alamat TEXT NOT NULL,
    id_kelas BIGINT UNSIGNED NOT NULL,
    foto VARCHAR(255) DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        ON UPDATE CURRENT_TIMESTAMP,
    CONSTRAINT fk_siswa_kelas
        FOREIGN KEY (id_kelas) REFERENCES kelas(id)
        ON UPDATE CASCADE
        ON DELETE RESTRICT,
    INDEX idx_siswa_nama (nama),
    INDEX idx_siswa_kelas (id_kelas)
) ENGINE=InnoDB;

CREATE TABLE presensi (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    nisn CHAR(10) NOT NULL,
    tanggal_waktu DATETIME NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_presensi_siswa
        FOREIGN KEY (nisn) REFERENCES siswa(nisn)
        ON UPDATE CASCADE
        ON DELETE CASCADE,
    INDEX idx_presensi_waktu (tanggal_waktu),
    INDEX idx_presensi_nisn_waktu (nisn, tanggal_waktu)
) ENGINE=InnoDB;

-- Mencegah lebih dari satu presensi untuk siswa yang sama pada tanggal yang sama.
-- Generated column menghasilkan tanggal dari tanggal_waktu.
ALTER TABLE presensi
ADD COLUMN tanggal_presensi DATE
    GENERATED ALWAYS AS (DATE(tanggal_waktu)) STORED,
ADD UNIQUE KEY uq_presensi_siswa_harian (nisn, tanggal_presensi);
