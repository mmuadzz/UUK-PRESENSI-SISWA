# Product Requirements Document (PRD)

## Aplikasi Presensi Siswa --- SMK TI Airlangga Samarinda

  Informasi           Detail
  ------------------- ------------------------------------
  Produk              Aplikasi Presensi Siswa
  Institusi           SMK TI Airlangga Samarinda
  Versi dokumen       1.0
  Jenis proyek        Ujian praktik / MVP
  Durasi pengerjaan   2 hari --- total 8 jam
  Backend             PHP Native dengan OOP
  Database            MySQL
  Frontend            HTML, CSS, Bootstrap 5, JavaScript
  Bahasa antarmuka    Bahasa Indonesia
  Zona waktu          Asia/Makassar (WITA)

------------------------------------------------------------------------

## 1. Ringkasan Produk

Aplikasi Presensi Siswa adalah aplikasi web sederhana untuk membantu
administrator sekolah mengelola data kelas, siswa, dan kehadiran harian.
Siswa melakukan presensi dengan menempelkan kartu RFID pada perangkat
pembaca. Untuk kebutuhan ujian praktik dan demo tanpa perangkat RFID,
aplikasi menyediakan **mode simulasi tap kartu** dengan memasukkan NISN
siswa.

Setiap presensi menyimpan waktu tap secara otomatis. Pada halaman rekap,
administrator dapat melihat siswa yang hadir dan siswa yang belum
memiliki catatan presensi pada tanggal terpilih.

Aplikasi dibangun menggunakan PHP Native berorientasi objek, MySQL,
Bootstrap 5, dan JavaScript tanpa framework backend.

### 1.1 Tujuan

-   Mempercepat pencatatan kehadiran siswa.
-   Mengurangi pencatatan presensi manual.
-   Memudahkan admin mengelola data kelas dan siswa.
-   Menyediakan rekap kehadiran berdasarkan tanggal dan kelas.
-   Menyiapkan struktur yang dapat dikembangkan untuk terhubung ke
    perangkat RFID sungguhan.

### 1.2 Indikator Keberhasilan

Aplikasi dianggap memenuhi kebutuhan MVP apabila:

1.  Admin dapat login dan logout.
2.  Admin dapat melakukan CRUD kelas.
3.  Admin dapat melakukan CRUD siswa, termasuk unggah foto.
4.  Simulasi tap menggunakan NISN berhasil mencatat waktu presensi.
5.  Satu siswa tidak dapat menghasilkan presensi ganda pada tanggal yang
    sama.
6.  Rekap menampilkan status Hadir atau Tidak Hadir sesuai data
    presensi.
7.  Seluruh query database menggunakan prepared statement.
8.  Password disimpan menggunakan `password_hash()` dan diverifikasi
    dengan `password_verify()`.
9.  Aplikasi dapat dijalankan mengikuti instruksi pada README.

------------------------------------------------------------------------

## 2. Ruang Lingkup

### 2.1 Fitur dalam MVP

  -----------------------------------------------------------------------
  Modul                   Fitur                   Prioritas
  ----------------------- ----------------------- -----------------------
  Autentikasi             Login admin             Wajib

  Autentikasi             Logout admin            Wajib

  Dashboard               Ringkasan jumlah siswa, Wajib
                          kelas, dan presensi     
                          hari ini                

  Data kelas              Lihat, tambah, ubah,    Wajib
                          hapus kelas             

  Data siswa              Lihat, tambah, ubah,    Wajib
                          hapus siswa             

  Data siswa              Simpan NISN, nama,      Wajib
                          alamat, kelas, dan foto 

  Presensi                Simulasi tap            Wajib
                          menggunakan NISN        

  Presensi                Simpan tanggal dan      Wajib
                          waktu tap otomatis      

  Rekap                   Filter tanggal dan      Wajib
                          kelas                   

  Rekap                   Tampilkan Hadir dan     Wajib
                          Tidak Hadir             
  -----------------------------------------------------------------------

### 2.2 Di Luar Ruang Lingkup MVP

Fitur berikut tidak dikerjakan dalam batas waktu 8 jam, tetapi dapat
menjadi pengembangan lanjutan:

-   Integrasi langsung dengan perangkat RFID fisik.
-   Pengelolaan banyak role seperti guru, wali kelas, dan kepala
    sekolah.
-   Presensi pulang.
-   Pengaturan jam terlambat dan dispensasi.
-   Notifikasi WhatsApp, email, atau SMS.
-   Ekspor Excel/PDF.
-   Grafik analitik lanjutan.
-   API mobile application.
-   Sinkronisasi offline.

------------------------------------------------------------------------

## 3. Pengguna dan Hak Akses

MVP hanya memiliki satu jenis pengguna, yaitu **Administrator**.

  ------------------------------------------------------------------------
  Aktivitas               Administrator                   Siswa
  ---------------- --------------------------- ---------------------------
  Login ke panel               Ya                         Tidak
  admin                                        

  Melihat                      Ya                         Tidak
  dashboard                                    

  Mengelola kelas              Ya                         Tidak

  Mengelola siswa              Ya                         Tidak

  Menjalankan                  Ya                         Tidak
  simulasi tap                                 

  Melihat rekap                Ya                         Tidak
  presensi                                     

  Melakukan             Melalui simulasi       Melalui kartu RFID (rencana
  presensi                                             integrasi)
  ------------------------------------------------------------------------

Siswa tidak memerlukan akun untuk melakukan presensi menggunakan kartu.

------------------------------------------------------------------------

## 4. Alur Penggunaan

### 4.1 Alur Administrator

1.  Admin membuka halaman login.
2.  Admin memasukkan username dan password.
3.  Sistem memvalidasi kredensial.
4.  Jika valid, sistem mengarahkan admin ke dashboard.
5.  Admin dapat mengelola kelas dan data siswa.
6.  Admin membuka halaman simulasi presensi untuk demo atau pengujian.
7.  Admin membuka halaman rekap untuk memeriksa kehadiran.

### 4.2 Alur Presensi

1.  Siswa menempelkan kartu RFID ke reader.
2.  Pada versi MVP, proses tersebut disimulasikan dengan memasukkan NISN
    pada form.
3.  Sistem mencari siswa berdasarkan NISN.
4.  Jika siswa ditemukan, sistem memeriksa apakah siswa sudah presensi
    pada tanggal tersebut.
5.  Jika belum, sistem menyimpan waktu saat ini sebagai waktu presensi.
6.  Sistem menampilkan nama siswa dan konfirmasi berhasil.
7.  Jika siswa sudah presensi, sistem menampilkan informasi bahwa
    presensi hari tersebut telah tercatat.

### 4.3 Aturan Status Kehadiran

-   **Hadir:** terdapat catatan presensi untuk NISN siswa pada tanggal
    yang dipilih.
-   **Tidak Hadir:** tidak terdapat catatan presensi untuk NISN siswa
    pada tanggal yang dipilih.
-   Status dihitung dari data presensi, bukan disimpan sebagai kolom
    terpisah.
-   MVP tidak membedakan terlambat, izin, sakit, atau alpa.
-   Rekap menampilkan seluruh siswa pada kelas terpilih, termasuk yang
    belum melakukan presensi.

------------------------------------------------------------------------

## 5. Kebutuhan Fungsional

### FR-01 --- Login

-   Sistem menyediakan form username dan password.
-   Sistem memvalidasi password menggunakan `password_verify()`.
-   Sistem menolak kredensial yang salah dengan pesan yang tidak
    membocorkan apakah username atau password yang keliru.
-   Session ID diperbarui setelah login berhasil.
-   Halaman admin hanya dapat diakses oleh session yang terautentikasi.

**Kriteria penerimaan:** - Login dengan akun valid berhasil. - Login
dengan password salah ditolak. - Pengguna yang belum login diarahkan ke
halaman login ketika membuka halaman admin.

### FR-02 --- Logout

-   Sistem menyediakan tombol logout.
-   Logout menghapus data session dan menghancurkan session.
-   Setelah logout, halaman admin tidak dapat diakses tanpa login
    kembali.

### FR-03 --- CRUD Kelas

Data kelas memiliki atribut: - ID kelas. - Nama kelas. - Jurusan.

Admin dapat: - Melihat daftar kelas. - Menambah kelas. - Mengubah nama
kelas dan jurusan. - Menghapus kelas jika belum digunakan oleh data
siswa.

**Validasi:** - Nama kelas wajib diisi. - Jurusan wajib diisi. - Nama
kelas tidak boleh duplikat. - Penghapusan kelas yang masih digunakan
siswa harus ditolak dengan pesan yang jelas.

### FR-04 --- CRUD Siswa

Data siswa memiliki atribut: - NISN. - Nama lengkap. - Alamat. -
Kelas. - Foto (opsional).

Admin dapat: - Melihat daftar siswa. - Mencari siswa berdasarkan NISN
atau nama. - Menambah siswa. - Mengubah data siswa. - Menghapus siswa. -
Mengunggah atau mengganti foto siswa.

**Validasi:** - NISN wajib diisi dan harus tepat 10 digit angka. - NISN
harus unik. - Nama wajib diisi. - Kelas harus dipilih dari data kelas
yang tersedia. - Foto hanya menerima JPG, JPEG, atau PNG. - Ukuran foto
maksimal 2 MB. - Nama file disimpan menggunakan nama acak, bukan nama
asli dari pengguna. - Foto tidak wajib diisi; gunakan placeholder jika
foto tidak tersedia.

### FR-05 --- Simulasi Tap Kartu

-   Halaman menyediakan input NISN.
-   Input dapat dikirim dengan tombol atau tombol Enter.
-   Sistem mencari siswa berdasarkan NISN.
-   Jika ditemukan dan belum presensi hari ini, sistem menyimpan waktu
    presensi.
-   Jika sudah presensi, sistem tidak membuat record kedua.
-   Jika NISN tidak ditemukan, sistem menampilkan pesan bahwa data siswa
    tidak ditemukan.
-   Setelah berhasil, sistem menampilkan nama, NISN, kelas, foto (jika
    tersedia), dan waktu presensi.
-   Form kembali siap menerima NISN berikutnya.

**Catatan integrasi RFID:** Pada implementasi perangkat nyata, reader
RFID dapat mengirim UID kartu ke endpoint yang sama. Relasi UID kartu ke
NISN perlu ditambahkan sebagai pengembangan berikutnya. Simulasi NISN
pada MVP bukan pengganti permanen UID RFID.

### FR-06 --- Rekap Presensi

-   Admin memilih tanggal.
-   Admin dapat memilih kelas tertentu atau semua kelas.
-   Sistem menampilkan seluruh siswa sesuai filter.
-   Sistem menampilkan NISN, nama, kelas, waktu presensi, dan status.
-   Siswa tanpa record presensi ditampilkan sebagai Tidak Hadir.
-   Sistem menampilkan ringkasan jumlah Hadir dan Tidak Hadir.

**Kriteria penerimaan:** - Filter tanggal mengubah data rekap sesuai
tanggal. - Filter kelas hanya menampilkan siswa dari kelas yang
dipilih. - Siswa yang belum melakukan tap tetap muncul pada rekap.

### FR-07 --- Dashboard

Dashboard menampilkan informasi ringkas: - Total siswa aktif. - Total
kelas. - Jumlah siswa hadir hari ini. - Jumlah siswa belum presensi hari
ini. - Beberapa presensi terakhir.

Angka dashboard harus dihitung dari database, bukan angka statis.

------------------------------------------------------------------------

## 6. Kebutuhan Non-Fungsional

### 6.1 Keamanan

-   Semua query yang menerima input menggunakan prepared statement PDO.
-   Password dibuat dengan `password_hash($password, PASSWORD_DEFAULT)`.
-   Password diverifikasi dengan `password_verify()`.
-   Session menggunakan konfigurasi cookie `HttpOnly` dan
    `SameSite=Lax`; aktifkan `Secure` ketika aplikasi menggunakan HTTPS.
-   Session ID diregenerasi setelah login.
-   Semua halaman admin dilindungi pemeriksaan autentikasi.
-   Form perubahan data menggunakan token CSRF.
-   Output teks dari database di-escape menggunakan
    `htmlspecialchars()`.
-   Validasi dilakukan di sisi server, bukan hanya JavaScript.
-   File upload memeriksa MIME type, ekstensi, dan ukuran.
-   File upload menggunakan nama acak dan disimpan pada direktori
    khusus.
-   Jangan menyimpan password database atau secret di repository publik.

### 6.2 Performa dan Kompatibilitas

-   Aplikasi ditujukan untuk penggunaan skala kecil di lingkungan
    sekolah.
-   Gunakan pagination jika daftar siswa mulai panjang.
-   Gunakan indeks pada kolom yang sering dicari.
-   Aplikasi harus berjalan pada lingkungan PHP dan MySQL yang
    kompatibel.
-   Gunakan zona waktu `Asia/Makassar` untuk konsistensi waktu presensi.

### 6.3 Kemudahan Penggunaan

-   Label form jelas dan menggunakan Bahasa Indonesia.
-   Pesan sukses dan gagal mudah dibedakan.
-   Form menampilkan validasi dekat dengan field terkait.
-   Tombol hapus meminta konfirmasi.
-   Tabel tetap nyaman dibaca pada layar laptop dan tablet.
-   Halaman simulasi tap dibuat sederhana dan cepat digunakan.

------------------------------------------------------------------------

## 7. Rancangan UI/UX

### 7.1 Arah Visual

Gunakan gaya **administrative dashboard** yang bersih, tenang, dan
fungsional. Hindari tampilan yang terlalu dekoratif atau terlihat
seperti template dashboard generik.

**Palet warna: cool blue menuju putih**

  Token         Warna       Penggunaan
  ------------- ----------- ------------------------------
  Primary 900   `#12345A`   Sidebar, heading penting
  Primary 800   `#174A78`   Tombol utama, navigasi aktif
  Primary 700   `#21649A`   Link dan aksen
  Primary 500   `#438FC4`   Elemen interaktif sekunder
  Primary 100   `#DCECF7`   Background aksen
  Primary 050   `#F2F7FB`   Background halaman
  White         `#FFFFFF`   Card dan area konten
  Text          `#263746`   Teks utama
  Muted         `#6B7C8F`   Teks sekunder
  Border        `#DCE4EB`   Garis pemisah

Warna status: - Hadir: hijau lembut dengan kontras teks yang cukup. -
Tidak Hadir: merah lembut dengan kontras teks yang cukup. - Informasi:
biru muda.

Jangan menggunakan gradien berlebihan, glassmorphism, neon, atau terlalu
banyak warna aksen.

### 7.2 Tipografi dan Komponen

-   Gunakan Bootstrap 5 sebagai dasar layout dan komponen.
-   Gunakan font sistem atau `Inter` jika tersedia.
-   Gunakan ukuran heading yang konsisten.
-   Gunakan Bootstrap Icons bila diperlukan; hindari emoji sebagai ikon
    navigasi.
-   Gunakan jarak antar elemen yang konsisten.
-   Gunakan border tipis dan shadow ringan, bukan card dengan efek
    berlebihan.

### 7.3 Struktur Navigasi

Sidebar: 1. Dashboard 2. Data Kelas 3. Data Siswa 4. Simulasi Presensi
5. Rekap Presensi 6. Logout

Pada layar kecil, sidebar berubah menjadi offcanvas atau navigasi yang
dapat dibuka melalui tombol menu.

### 7.4 Rancangan Halaman

**A. Login** - Logo/nama sekolah. - Judul "Administrator Presensi". -
Input username. - Input password dengan opsi tampilkan/sembunyikan. -
Tombol masuk. - Pesan validasi.

**B. Dashboard** - Sapaan singkat kepada admin. - Empat kartu
ringkasan. - Tabel presensi terakhir. - Tanggal dan waktu saat ini.

**C. Data Kelas** - Judul dan deskripsi singkat. - Tombol "Tambah
Kelas". - Tabel nama kelas dan jurusan. - Aksi edit dan hapus. - Modal
untuk tambah/edit.

**D. Data Siswa** - Tombol "Tambah Siswa". - Kolom pencarian. - Filter
kelas. - Tabel foto, NISN, nama, kelas, dan aksi. - Form tambah/edit
dengan preview foto.

**E. Simulasi Presensi** - Input NISN yang cukup besar dan mudah
difokuskan. - Tombol "Simulasikan Tap". - Area hasil presensi
terakhir. - Riwayat tap terbaru. - Fokus input dikembalikan setelah
proses selesai agar simulasi dapat digunakan berulang.

**F. Rekap Presensi** - Filter tanggal. - Filter kelas. - Ringkasan
jumlah Hadir dan Tidak Hadir. - Tabel daftar siswa dengan status dan
waktu presensi. - Tampilan kosong yang informatif jika belum ada data.

------------------------------------------------------------------------

## 8. Teknologi dan Arsitektur

### 8.1 Stack

  Bagian            Teknologi
  ----------------- ------------------------
  Backend           PHP Native 8.x
  Paradigma         OOP
  Database          MySQL 8.x
  Database access   PDO
  Frontend          HTML5, CSS3
  UI Framework      Bootstrap 5
  Interaksi         JavaScript
  Server lokal      Apache (XAMPP/Laragon)
  Version control   Git dan GitHub

### 8.2 Pola OOP

Gunakan class `BaseModel` sebagai fondasi akses database. Model lain
mewarisi class tersebut.

Contoh pembagian class:

-   `Database`: membuat koneksi PDO.
-   `BaseModel`: menyimpan koneksi dan menyediakan fungsi dasar model.
-   `User`: proses autentikasi admin.
-   `Kelas`: operasi data kelas.
-   `Siswa`: operasi data siswa.
-   `Presensi`: proses pencatatan dan rekap presensi.

Controller atau file handler bertugas menerima request, memvalidasi
input, memanggil model, lalu menentukan response atau redirect.

**Ketentuan penting:** jangan menaruh seluruh query, validasi, dan HTML
dalam satu file. Pisahkan tanggung jawab agar kode mudah diuji dan
dipahami.

### 8.3 Struktur Direktori yang Disarankan

``` text
presensi-smk/
├── app/
│   ├── config/
│   │   └── database.php
│   ├── core/
│   │   ├── Database.php
│   │   └── BaseModel.php
│   ├── models/
│   │   ├── User.php
│   │   ├── Kelas.php
│   │   ├── Siswa.php
│   │   └── Presensi.php
│   ├── controllers/
│   │   ├── AuthController.php
│   │   ├── KelasController.php
│   │   ├── SiswaController.php
│   │   └── PresensiController.php
│   └── helpers/
│       ├── auth.php
│       ├── csrf.php
│       └── upload.php
├── public/
│   ├── assets/
│   │   ├── css/
│   │   ├── js/
│   │   └── img/
│   ├── uploads/
│   │   └── siswa/
│   ├── index.php
│   ├── login.php
│   ├── logout.php
│   ├── dashboard.php
│   ├── kelas/
│   ├── siswa/
│   └── presensi/
├── database/
│   ├── schema.sql
│   └── seed.sql
├── README.md
└── .gitignore
```

Untuk hosting, arahkan document root ke folder `public/` agar file
internal aplikasi dan konfigurasi tidak dapat diakses langsung dari web.

------------------------------------------------------------------------

## 9. Rancangan Database

Meskipun deskripsi awal menyebut "3 tabel utama", daftar tabel yang
diminta sebenarnya terdiri dari **4 tabel**: `users`, `kelas`, `siswa`,
dan `presensi`. MVP menggunakan keempat tabel tersebut.

### 9.1 Relasi

``` text
users
  └── digunakan untuk autentikasi admin

kelas (1) ───────── (N) siswa
                         |
                         |
                       (1:N)
                         |
                      presensi
```

Relasi: - Satu kelas memiliki banyak siswa. - Satu siswa dapat memiliki
banyak catatan presensi pada tanggal yang berbeda. - Satu siswa hanya
boleh memiliki satu catatan presensi per tanggal. - `users` berdiri
sendiri untuk autentikasi admin.

### 9.2 Struktur Tabel

#### `users`

  Kolom          Tipe              Keterangan
  -------------- ----------------- -----------------------------
  id             BIGINT UNSIGNED   Primary key, auto increment
  username       VARCHAR(50)       Unik
  password       VARCHAR(255)      Password hash
  nama_lengkap   VARCHAR(100)      Nama admin
  created_at     TIMESTAMP         Waktu dibuat

#### `kelas`

  Kolom        Tipe              Keterangan
  ------------ ----------------- ------------------
  id           BIGINT UNSIGNED   Primary key
  nama_kelas   VARCHAR(50)       Nama kelas, unik
  jurusan      VARCHAR(100)      Nama jurusan
  created_at   TIMESTAMP         Waktu dibuat
  updated_at   TIMESTAMP         Waktu diperbarui

#### `siswa`

  Kolom        Tipe              Keterangan
  ------------ ----------------- -----------------------
  nisn         CHAR(10)          Primary key, 10 digit
  nama         VARCHAR(120)      Nama lengkap
  alamat       TEXT              Alamat siswa
  id_kelas     BIGINT UNSIGNED   Foreign key ke kelas
  foto         VARCHAR(255)      Path foto, nullable
  created_at   TIMESTAMP         Waktu dibuat
  updated_at   TIMESTAMP         Waktu diperbarui

NISN disimpan sebagai `CHAR(10)`, bukan integer, agar format identitas
10 digit tetap konsisten dan tidak mengalami masalah konversi angka.

#### `presensi`

  Kolom           Tipe              Keterangan
  --------------- ----------------- ----------------------
  id              BIGINT UNSIGNED   Primary key
  nisn            CHAR(10)          Foreign key ke siswa
  tanggal_waktu   DATETIME          Waktu tap
  created_at      TIMESTAMP         Waktu record dibuat

Tambahkan unique key pada kombinasi tanggal dan NISN agar satu siswa
hanya memiliki satu presensi per hari.

### 9.3 SQL Schema

Simpan sebagai `database/schema.sql`.

``` sql
CREATE DATABASE IF NOT EXISTS presensi_smk
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci;

USE presensi_smk;

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
```

### 9.4 Data Dummy yang Realistis

Data dummy harus digunakan hanya untuk kebutuhan pengembangan dan demo.
Gunakan nama, alamat, dan identitas fiktif; jangan memasukkan data
pribadi siswa sungguhan ke repository publik.

Simpan sebagai `database/seed.sql`.

``` sql
USE presensi_smk;

INSERT INTO kelas (nama_kelas, jurusan) VALUES
('X RPL 1', 'Rekayasa Perangkat Lunak'),
('X TKJ 1', 'Teknik Komputer dan Jaringan'),
('XI RPL 1', 'Rekayasa Perangkat Lunak'),
('XI TKJ 1', 'Teknik Komputer dan Jaringan'),
('XII RPL 1', 'Rekayasa Perangkat Lunak');

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

-- Contoh presensi untuk data demo.
-- Gunakan tanggal relatif saat demo atau sesuaikan dengan tanggal pengujian.
INSERT INTO presensi (nisn, tanggal_waktu) VALUES
('0061842301', CONCAT(CURDATE(), ' 07:02:15')),
('0061842302', CONCAT(CURDATE(), ' 07:05:41')),
('0061842303', CONCAT(CURDATE(), ' 07:11:08')),
('0061842311', CONCAT(CURDATE(), ' 07:08:22')),
('0051732201', CONCAT(CURDATE(), ' 06:58:34')),
('0051732202', CONCAT(CURDATE(), ' 07:14:03')),
('0051732211', CONCAT(CURDATE(), ' 07:06:19'));
```

**Akun admin dummy:** buat melalui script setup PHP yang memanggil
`password_hash()`. Jangan menulis password plaintext ke tabel atau
menyimpan hash yang belum diverifikasi di file SQL.

Contoh pembuatan hash:

``` php
$hash = password_hash('AdminDemo!2026', PASSWORD_DEFAULT);

// Gunakan prepared statement untuk menyimpan akun admin.
$stmt = $pdo->prepare(
    'INSERT INTO users (username, password, nama_lengkap)
     VALUES (:username, :password, :nama)'
);

$stmt->execute([
    'username' => 'admin',
    'password' => $hash,
    'nama' => 'Administrator Sekolah'
]);
```

Akun demo tersebut harus diganti atau dihapus sebelum aplikasi digunakan
di lingkungan produksi.

------------------------------------------------------------------------

## 10. Aturan Bisnis dan Validasi Penting

  -----------------------------------------------------------------------
  Kode                                Aturan
  ----------------------------------- -----------------------------------
  BR-01                               NISN harus terdiri dari tepat 10
                                      digit angka.

  BR-02                               Satu NISN hanya boleh terdaftar
                                      satu kali.

  BR-03                               Satu nama kelas tidak boleh
                                      terdaftar dua kali.

  BR-04                               Siswa harus terhubung dengan kelas
                                      yang tersedia.

  BR-05                               Siswa hanya dapat presensi satu
                                      kali per tanggal kalender sekolah.

  BR-06                               Waktu presensi diambil dari server,
                                      bukan dari input browser.

  BR-07                               Status Hadir ditentukan dari
                                      keberadaan record presensi.

  BR-08                               Status Tidak Hadir ditentukan jika
                                      record presensi tidak ditemukan.

  BR-09                               Kelas yang masih memiliki siswa
                                      tidak dapat dihapus.

  BR-10                               Penghapusan siswa menghapus catatan
                                      presensinya melalui foreign key
                                      cascade.

  BR-11                               Foto siswa tidak wajib diisi.

  BR-12                               Semua waktu aplikasi menggunakan
                                      zona waktu Asia/Makassar.
  -----------------------------------------------------------------------

**Catatan teknis presensi harian:** set zona waktu PHP ke
`Asia/Makassar` dan pastikan konfigurasi MySQL konsisten. Unique key
pada `(nisn, tanggal_presensi)` menjadi perlindungan terakhir terhadap
tap ganda, termasuk ketika dua request masuk hampir bersamaan.

------------------------------------------------------------------------

## 11. Rencana Implementasi --- 2 Hari / 8 Jam

Target dibuat sebagai MVP yang dapat didemokan. Pembagian waktu harus
disiplin; fitur di luar ruang lingkup tidak boleh mengganggu
penyelesaian fitur wajib.

### Hari 1 --- Fondasi, Database, dan CRUD (4 jam)

  -----------------------------------------------------------------------
  Waktu                   Pekerjaan               Hasil
  ----------------------- ----------------------- -----------------------
  00:00--00:30            Inisialisasi project,   Project siap dijalankan
                          struktur folder,        
                          konfigurasi MySQL       

  00:30--01:15            Membuat schema, relasi, Database siap
                          dan data dummy          

  01:15--02:00            Membuat koneksi PDO,    Login/logout berfungsi
                          `BaseModel`, dan        
                          autentikasi             

  02:00--03:00            Membuat CRUD kelas dan  Modul kelas selesai
                          layout dashboard        

  03:00--04:00            Membuat CRUD siswa dan  Modul siswa selesai
                          upload foto             
  -----------------------------------------------------------------------

### Hari 2 --- Presensi, Rekap, Pengujian, dan Dokumentasi (4 jam)

  -----------------------------------------------------------------------
  Waktu                   Pekerjaan               Hasil
  ----------------------- ----------------------- -----------------------
  04:00--05:00            Membuat simulasi tap    Presensi berfungsi
                          dan validasi presensi   
                          ganda                   

  05:00--06:00            Membuat rekap           Rekap berfungsi
                          berdasarkan tanggal dan 
                          kelas                   

  06:00--07:00            Pengujian alur,         MVP stabil
                          validasi, keamanan      
                          dasar, dan perbaikan UI 

  07:00--08:00            README, screenshot,     Paket pengumpulan siap
                          GitHub, hosting/demo    
  -----------------------------------------------------------------------

### Prioritas Jika Waktu Tidak Cukup

Urutan prioritas: 1. Login/logout dan proteksi halaman. 2. Database dan
`BaseModel`. 3. CRUD kelas. 4. CRUD siswa. 5. Simulasi tap. 6. Rekap
presensi. 7. Penyempurnaan visual dan dokumentasi.

Jangan mengorbankan prepared statement, password hashing, atau validasi
server hanya demi menyelesaikan tampilan.

------------------------------------------------------------------------

## 12. Rencana Pengujian

  -----------------------------------------------------------------------
  ID                      Skenario                Hasil yang Diharapkan
  ----------------------- ----------------------- -----------------------
  TC-01                   Login dengan akun valid Masuk ke dashboard

  TC-02                   Login dengan password   Login ditolak
                          salah                   

  TC-03                   Buka dashboard tanpa    Dialihkan ke login
                          login                   

  TC-04                   Logout lalu membuka     Dialihkan ke login
                          dashboard               

  TC-05                   Tambah kelas dengan     Kelas tersimpan
                          data valid              

  TC-06                   Tambah kelas dengan     Ditolak dengan pesan
                          nama duplikat           validasi

  TC-07                   Hapus kelas yang masih  Penghapusan ditolak
                          memiliki siswa          

  TC-08                   Tambah siswa dengan     Siswa tersimpan
                          NISN 10 digit           

  TC-09                   Tambah siswa dengan     Ditolak
                          NISN kurang dari 10     
                          digit                   

  TC-10                   Tambah siswa dengan     Ditolak
                          NISN duplikat           

  TC-11                   Upload file selain      Ditolak
                          JPG/PNG                 

  TC-12                   Simulasi tap dengan     Presensi tersimpan
                          NISN terdaftar          

  TC-13                   Simulasi tap dengan     Pesan siswa tidak
                          NISN tidak terdaftar    ditemukan

  TC-14                   Tap NISN yang sama dua  Tidak membuat presensi
                          kali pada hari yang     kedua
                          sama                    

  TC-15                   Rekap tanggal dan kelas Data sesuai filter
                          tertentu                

  TC-16                   Rekap siswa tanpa       Status Tidak Hadir
                          presensi                

  TC-17                   Input berisi karakter   Tidak mengubah
                          SQL berbahaya           struktur/query database

  TC-18                   Submit form tanpa token Request ditolak
                          CSRF                    
  -----------------------------------------------------------------------

------------------------------------------------------------------------

## 13. Output dan Kriteria Pengumpulan

### 13.1 Aplikasi

-   Aplikasi dapat dijalankan secara lokal atau melalui hosting.
-   Database MySQL tersedia dalam file `schema.sql` dan `seed.sql`.
-   Akun demo tersedia melalui proses setup yang aman.
-   Seluruh fitur wajib dapat dicoba.

### 13.2 README.md

README minimal memuat: - Deskripsi aplikasi. - Fitur. - Teknologi yang
digunakan. - Persyaratan PHP dan MySQL. - Cara instalasi. - Cara membuat
database dan mengimpor SQL. - Cara konfigurasi koneksi. - Cara membuat
akun admin demo. - URL aplikasi. - Kredensial demo jika memang
disediakan untuk penilaian. - Catatan bahwa data yang digunakan adalah
data dummy.

### 13.3 Dokumentasi Screenshot

Screenshot yang disarankan: 1. Halaman login. 2. Dashboard. 3. Daftar
kelas. 4. Form tambah/edit kelas. 5. Daftar siswa. 6. Form tambah/edit
siswa. 7. Halaman simulasi tap dengan hasil berhasil. 8. Notifikasi
ketika siswa melakukan tap kedua. 9. Halaman rekap yang menampilkan
Hadir dan Tidak Hadir.

Jangan menampilkan password, kredensial database, atau data pribadi
siswa sungguhan.

### 13.4 Repository GitHub

Repository minimal memiliki: - Source code aplikasi. -
`database/schema.sql`. - `database/seed.sql`. - `README.md`. -
`.gitignore`. - Screenshot dokumentasi (misalnya di folder
`docs/screenshots/`).

Jangan commit: - Password asli. - File `.env` atau konfigurasi
rahasia. - Dump database berisi data pribadi. - File upload yang memuat
foto siswa sungguhan.

### 13.5 Video Demo (Opsional)

Durasi yang disarankan: 2--4 menit.

Urutan demo: 1. Login sebagai admin. 2. Tampilkan dashboard. 3. Tambah
atau edit kelas. 4. Tambah atau edit siswa. 5. Lakukan simulasi tap
dengan NISN yang terdaftar. 6. Ulangi tap untuk menunjukkan pencegahan
presensi ganda. 7. Buka rekap dan tunjukkan status Hadir serta Tidak
Hadir. 8. Logout.

------------------------------------------------------------------------

## 14. Risiko dan Mitigasi

  -----------------------------------------------------------------------
  Risiko                  Dampak                  Mitigasi
  ----------------------- ----------------------- -----------------------
  Perangkat RFID tidak    Integrasi fisik tidak   Gunakan simulasi NISN
  tersedia                dapat didemokan         

  Waktu pengerjaan        Fitur wajib tidak       Ikuti prioritas MVP
  terbatas                selesai                 

  Tap ganda               Data presensi tidak     Unique key per siswa
                          akurat                  per tanggal

  Foto terlalu besar atau Risiko penyalahgunaan   Validasi MIME, ukuran,
  format berbahaya        upload                  dan nama file

  Query tidak aman        Risiko SQL injection    PDO prepared statement

  Session tidak aman      Akses admin dapat       Regenerasi session dan
                          disalahgunakan          proteksi halaman

  Data demo dianggap data Kesalahan saat          Beri label data dummy
  asli                    presentasi              

  Zona waktu berbeda      Jam presensi tidak      Konsisten memakai
                          sesuai                  Asia/Makassar
  -----------------------------------------------------------------------

------------------------------------------------------------------------

## 15. Definition of Done

Pekerjaan dinyatakan selesai apabila:

-   [ ] Database MySQL memiliki empat tabel sesuai rancangan.
-   [ ] Data dummy dapat diimpor tanpa error.
-   [ ] Class `BaseModel` digunakan oleh model aplikasi.
-   [ ] Koneksi database menggunakan PDO.
-   [ ] Semua query dengan input menggunakan prepared statement.
-   [ ] Login dan logout berjalan.
-   [ ] Password disimpan menggunakan password hashing.
-   [ ] CRUD kelas berjalan.
-   [ ] CRUD siswa berjalan.
-   [ ] Validasi NISN 10 digit berjalan.
-   [ ] Upload foto berjalan dengan pembatasan tipe dan ukuran.
-   [ ] Simulasi tap dapat mencatat waktu presensi.
-   [ ] Presensi ganda pada tanggal yang sama dicegah.
-   [ ] Rekap menampilkan Hadir dan Tidak Hadir.
-   [ ] Filter tanggal dan kelas berjalan.
-   [ ] Layout responsif dengan Bootstrap 5.
-   [ ] README dan screenshot tersedia.
-   [ ] Repository GitHub siap diperiksa.
-   [ ] Aplikasi dapat dijalankan dari instruksi instalasi.

------------------------------------------------------------------------

## 16. Catatan Pengembangan Lanjutan

Jika MVP berhasil diselesaikan, pengembangan berikutnya yang paling
relevan adalah:

1.  **Integrasi RFID:** menambahkan tabel kartu RFID yang menghubungkan
    UID kartu dengan NISN siswa. UID sebaiknya tidak disamakan dengan
    NISN.
2.  **Aturan keterlambatan:** menentukan jam masuk dan status terlambat
    berdasarkan kebijakan sekolah.
3.  **Presensi pulang:** menambahkan jenis presensi masuk dan pulang.
4.  **Ekspor laporan:** menyediakan unduhan rekap dalam Excel atau PDF.
5.  **Hak akses:** menambahkan role guru, wali kelas, dan administrator.
6.  **Audit log:** mencatat perubahan data penting yang dilakukan admin.

Fitur lanjutan tersebut tidak menjadi syarat kelulusan MVP dua hari.

------------------------------------------------------------------------

**Arah akhir produk:** aplikasi administrasi sekolah yang sederhana,
cepat dipahami, konsisten, aman untuk kebutuhan dasar, dan realistis
diselesaikan dalam 8 jam. Fokus utama adalah alur presensi yang benar
dan data yang konsisten, bukan jumlah fitur atau dekorasi antarmuka.
