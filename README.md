# Aplikasi Presensi Siswa — SMK TI Airlangga Samarinda

Aplikasi web administrasi kehadiran siswa untuk kebutuhan ujian praktik/MVP. Administrator dapat mengelola data kelas dan siswa, mencatat presensi melalui **simulasi tap kartu (input NISN)**, serta melihat rekap kehadiran berdasarkan tanggal dan kelas.

Aplikasi dibangun dengan **PHP Native berorientasi objek**, **PDO**, **MySQL**, **Bootstrap 5**, dan JavaScript tanpa framework backend.

> Seluruh data pada `database/seed.sql` adalah **data fiktif** untuk keperluan demo. Jangan memasukkan data pribadi siswa sungguhan ke repository publik.

---

## 1. Fitur

- Login dan logout administrator dengan password hashing.
- Proteksi seluruh halaman admin menggunakan session.
- Dashboard berisi total siswa, total kelas, jumlah hadir hari ini, jumlah belum presensi, dan presensi terakhir.
- CRUD data kelas dengan validasi nama unik dan penolakan hapus kelas yang masih memiliki siswa.
- CRUD data siswa dengan pencarian NISN/nama, filter kelas, dan pagination.
- Upload/ubah foto siswa (JPG/JPEG/PNG, maksimal 2 MB, nama file acak).
- Simulasi tap kartu menggunakan NISN (mendukung tombol Enter dan AJAX).
- Pencegahan presensi ganda per siswa per tanggal (validasi aplikasi + unique key database).
- Rekap presensi per tanggal dan kelas, menampilkan status **Hadir** dan **Tidak Hadir**.
- Tampilan responsif dengan sidebar yang berubah menjadi offcanvas pada layar kecil.

---

## 2. Teknologi

| Bagian | Teknologi |
| --- | --- |
| Backend | PHP Native 8.x (OOP) |
| Database access | PDO prepared statement |
| Database | MySQL 8.x / MariaDB 10.4+ |
| Frontend | HTML5, CSS3, Bootstrap 5.3, Bootstrap Icons |
| Interaksi | JavaScript (vanilla) |
| Server lokal | Apache (XAMPP/Laragon) atau PHP built-in server |
| Zona waktu | `Asia/Makassar` (WITA, UTC+8) |

---

## 3. Persyaratan

- PHP **8.0+** dengan ekstensi:
  - `pdo_mysql`
  - `mbstring`
  - `fileinfo` (umumnya aktif secara bawaan)
- MySQL 8.x atau MariaDB 10.4+ (mendukung generated column).
- Web server Apache/Nginx, atau PHP built-in server untuk pengembangan.
- Browser modern (Chrome, Edge, Firefox, Safari).

Aplikasi menggunakan Bootstrap dan font Inter melalui CDN, sehingga koneksi internet diperlukan saat pertama kali membuka halaman. Jika ingin sepenuhnya offline, unduh aset CDN tersebut ke `public/assets/`.

---

## 4. Struktur Direktori

```text
App_Presensi/
├── app/
│   ├── bootstrap.php
│   ├── config/
│   │   ├── app.php
│   │   └── database.php
│   ├── controllers/
│   │   ├── AuthController.php
│   │   ├── KelasController.php
│   │   ├── PresensiController.php
│   │   └── SiswaController.php
│   ├── core/
│   │   ├── BaseModel.php
│   │   └── Database.php
│   ├── helpers/
│   │   ├── auth.php
│   │   ├── csrf.php
│   │   ├── flash.php
│   │   ├── form.php
│   │   ├── format.php
│   │   ├── upload.php
│   │   └── url.php
│   ├── models/
│   │   ├── Kelas.php
│   │   ├── Presensi.php
│   │   ├── Siswa.php
│   │   └── User.php
│   └── views/
│       ├── auth/, dashboard/, errors/, kelas/, layouts/, presensi/, siswa/
├── database/
│   ├── schema.sql
│   └── seed.sql
├── docs/screenshots/
├── public/
│   ├── assets/css/app.css
│   ├── assets/js/app.js
│   ├── uploads/siswa/
│   ├── kelas/, siswa/, presensi/
│   ├── dashboard.php
│   ├── index.php
│   ├── login.php
│   └── logout.php
├── scripts/setup_admin.php
├── .gitignore
├── index.php
└── README.md
```

**Penting:** arahkan document root web server ke folder `public/` agar file konfigurasi dan kode internal tidak dapat diakses langsung dari browser. Alternatif lain: buka `http://localhost/App_Presensi/public/` (detail di bawah).

---

## 5. Cara Instalasi

### 5.1 Menyiapkan database

1. Jalankan MySQL melalui XAMPP/Laragon.
2. Impor struktur database:

   **phpMyAdmin:** buka phpMyAdmin → tab **Import** → pilih `database/schema.sql` → **Go**.

   **Terminal (Windows/Linux/macOS):**
   ```bash
   mysql -u root -p < database/schema.sql
   mysql -u root -p < database/seed.sql
   ```
   Jika user `root` tidak memakai password (bawaan XAMPP):
   ```bash
   mysql -u root < database/schema.sql
   mysql -u root < database/seed.sql
   ```

3. `schema.sql` membuat database `presensi_smk` beserta empat tabel: `users`, `kelas`, `siswa`, `presensi`.
   > Perhatian: `schema.sql` menghapus tabel lama (reset data). `seed.sql` mengosongkan data kelas/siswa/presensi lalu mengisi data demo.

### 5.2 Konfigurasi koneksi

Sesuaikan `app/config/database.php`, atau gunakan environment variable:

| Variable | Nilai bawaan |
| --- | --- |
| `DB_HOST` | `127.0.0.1` |
| `DB_PORT` | `3306` |
| `DB_NAME` | `presensi_smk` |
| `DB_USER` | `root` |
| `DB_PASSWORD` | *(kosong)* |

Jangan menyimpan kredensial produksi di repository. Gunakan environment variable pada server.

### 5.3 Membuat akun admin

Akun admin **tidak** disimpan di file SQL. Jalankan script setup agar password di-hash dengan `password_hash()`:

```bash
# Akun demo bawaan: admin / AdminDemo!2026 / Administrator Sekolah
php scripts/setup_admin.php
```

Atau tentukan kredensial sendiri:

```powershell
# PowerShell
php scripts/setup_admin.php admin 'PasswordBaru!2026' 'Administrator Sekolah'
```

```bash
# CMD / Linux
php scripts/setup_admin.php admin "PasswordBaru!2026" "Administrator Sekolah"
```

Script aman dijalankan ulang: jika username sudah ada, password dan nama akan diperbarui.

### 5.4 Menjalankan aplikasi

**Opsi A — PHP built-in server (paling cepat):**
```bash
php -S localhost:8000 -t public
```
Buka <http://localhost:8000>.

**Opsi B — Apache dengan document root `public/` (disarankan):**
- Letakkan folder proyek di `htdocs/App_Presensi`.
- Atur VirtualHost `DocumentRoot` ke `E:/App_Presensi/public` (Windows) atau gunakan konfigurasi hosting yang mengarah ke `public/`.
- Buka `http://localhost/`.

**Opsi C — Apache tanpa mengubah document root:**
- Buka `http://localhost/App_Presensi/public/`.
- Deteksi `base_url` berjalan otomatis berdasarkan document root.
- Jika URL masih salah, set environment `APP_BASE_URL=/App_Presensi/public` atau isi `base_url` pada `app/config/app.php`.

### 5.5 Kredensial demo

| Field | Nilai |
| --- | --- |
| URL | `http://localhost:8000` (built-in server) atau `/App_Presensi/public/` |
| Username | `admin` |
| Password | `AdminDemo!2026` |

> Ganti atau hapus akun demo sebelum aplikasi digunakan di lingkungan produksi.

---

## 6. Alur Penggunaan Singkat

1. Login sebagai administrator.
2. Periksa ringkasan pada **Dashboard**.
3. Tambahkan/ubah **Data Kelas**.
4. Tambahkan/ubah **Data Siswa**, termasuk unggah foto opsional.
5. Buka **Simulasi Presensi**, masukkan NISN siswa (contoh: `0061842301`), lalu tekan **Enter** atau klik **Simulasikan Tap**.
6. Ulangi NISN yang sama untuk melihat pesan bahwa presensi hari ini sudah tercatat.
7. Buka **Rekap Presensi**, pilih tanggal/kelas, lalu periksa status Hadir dan Tidak Hadir.

---

## 7. Aturan Bisnis dan Keamanan

### Aturan utama

- NISN wajib tepat **10 digit angka** dan unik.
- Nama kelas wajib unik.
- Kelas yang masih memiliki siswa tidak dapat dihapus.
- Satu siswa hanya boleh memiliki **satu presensi per tanggal**.
- Waktu presensi diambil dari server, bukan dari browser.
- Status **Hadir** berasal dari keberadaan record presensi; **Tidak Hadir** jika record tidak ditemukan.
- Penghapusan siswa menghapus riwayat presensinya melalui `ON DELETE CASCADE`.
- Semua waktu memakai zona `Asia/Makassar`; koneksi MySQL juga diatur ke `+08:00`.

### Keamanan yang diterapkan

- Semua query dengan input memakai **PDO prepared statement** dan `ATTR_EMULATE_PREPARES = false`.
- Password disimpan dengan `password_hash(..., PASSWORD_DEFAULT)` dan diverifikasi dengan `password_verify()`.
- Session ID diregenerasi setelah login; cookie `HttpOnly` + `SameSite=Lax`, `Secure` otomatis jika HTTPS.
- Semua form perubahan data memakai **token CSRF**; logout juga memakai POST + CSRF.
- Output dari database di-escape dengan `htmlspecialchars()` melalui helper `e()`.
- Validasi dilakukan di server; validasi JavaScript hanya membantu pengalaman pengguna.
- Upload foto memeriksa MIME asli (`getimagesize`), ekstensi, ukuran maksimal 2 MB, dan menyimpan dengan nama acak di `public/uploads/siswa/`.
- Folder `app/` dan `database/` dilindungi `.htaccess`; folder uploads menolak eksekusi skrip.

---

## 8. Pengujian Manual

| ID | Skenario | Hasil yang diharapkan |
| --- | --- | --- |
| TC-01 | Login dengan akun valid | Masuk ke dashboard |
| TC-02 | Login dengan password salah | Ditolak dengan pesan umum |
| TC-03 | Buka dashboard tanpa login | Dialihkan ke halaman login |
| TC-04 | Logout lalu buka dashboard | Dialihkan ke halaman login |
| TC-05 | Tambah kelas valid | Kelas tersimpan |
| TC-06 | Tambah kelas dengan nama duplikat | Ditolak dengan pesan validasi |
| TC-07 | Hapus kelas yang masih memiliki siswa | Penghapusan ditolak dengan pesan jelas |
| TC-08 | Tambah siswa NISN 10 digit | Siswa tersimpan |
| TC-09 | Tambah siswa NISN kurang/lebih dari 10 digit | Ditolak |
| TC-10 | Tambah siswa dengan NISN duplikat | Ditolak |
| TC-11 | Upload file selain JPG/JPEG/PNG | Ditolak |
| TC-12 | Simulasi tap NISN terdaftar | Presensi tersimpan, waktu tampil |
| TC-13 | Simulasi tap NISN tidak terdaftar | Pesan siswa tidak ditemukan |
| TC-14 | Tap NISN yang sama dua kali di hari yang sama | Tidak membuat record kedua |
| TC-15 | Rekap tanggal dan kelas tertentu | Data sesuai filter |
| TC-16 | Rekap siswa tanpa presensi | Status Tidak Hadir |
| TC-17 | Input berisi karakter SQL berbahaya | Query tetap aman (prepared statement) |
| TC-18 | Submit form tanpa token CSRF | Request ditolak / redirect dengan pesan keamanan |

### Data demo untuk pengujian

- NISN contoh: `0061842301`, `0061842302`, `0051732201`.
- Beberapa siswa sudah memiliki presensi demo pada tanggal import (lihat `seed.sql`).
- NISN `0061842304` (Aulia Rahmawati) belum memiliki presensi demo — cocok untuk TC-12, sedangkan `0061842301` sudah punya presensi hari ini — cocok untuk TC-14.

---

## 9. Dokumentasi Screenshot

Simpan screenshot ke `docs/screenshots/` dengan urutan yang disarankan:

1. Halaman login.
2. Dashboard.
3. Daftar kelas.
4. Modal tambah/edit kelas.
5. Daftar siswa.
6. Form tambah/edit siswa.
7. Simulasi tap berhasil.
8. Notifikasi tap kedua (presensi sudah tercatat).
9. Rekap yang menampilkan Hadir dan Tidak Hadir.

Jangan menampilkan password, kredensial database, atau data pribadi siswa sungguhan pada screenshot maupun repository.

---

## 10. Troubleshooting

| Masalah | Solusi |
| --- | --- |
| `Tidak dapat terhubung ke database` | Periksa MySQL aktif, nama database `presensi_smk`, dan kredensial di `app/config/database.php`. |
| `Table 'presensi_smk.users' doesn't exist` | Impor `database/schema.sql`, lalu jalankan `scripts/setup_admin.php`. |
| Login selalu gagal | Pastikan akun dibuat via `scripts/setup_admin.php` (bukan INSERT password plaintext). |
| Foto gagal diunggah | Pastikan folder `public/uploads/siswa/` dapat ditulis dan ukuran ≤ 2 MB. |
| Halaman tampil tanpa CSS | Periksa koneksi internet (CDN) atau `base_url`. Bisa juga set `APP_BASE_URL`. |
| Pesan “Sesi keamanan telah berakhir” | Muat ulang halaman agar token CSRF diperbarui, lalu ulangi submit. |
| Jam presensi tidak sesuai | Pastikan `date_default_timezone_set` tetap `Asia/Makassar`; koneksi PDO sudah menyetel `time_zone = '+08:00'`. |

---

## 11. Catatan Pengembangan Lanjutan

Di luar ruang lingkup MVP, pengembangan berikutnya yang relevan:

1. Integrasi RFID fisik (tabel pemetaan UID kartu ↔ NISN).
2. Status terlambat, izin, sakit, dan alpa.
3. Presensi pulang.
4. Ekspor Excel/PDF.
5. Role guru/wali kelas/kepala sekolah.
6. Audit log perubahan data.

---

## 12. Lisensi dan Kredit

Dibuat untuk kebutuhan ujian praktik **SMK TI Airlangga Samarinda**. Data siswa pada repository ini adalah data dummy. Silakan sesuaikan kebijakan penggunaan dengan kebutuhan sekolah.
