<?php

declare(strict_types=1);

if (PHP_SAPI !== 'cli') {
    http_response_code(403);
    exit("Script ini hanya dapat dijalankan melalui CLI.\n");
}

require dirname(__DIR__) . '/app/bootstrap.php';

$username = trim((string) ($argv[1] ?? 'admin'));
$password = (string) ($argv[2] ?? 'AdminDemo!2026');
$namaLengkap = trim((string) ($argv[3] ?? 'Administrator Sekolah'));

$errors = [];

if (!preg_match('/^[A-Za-z0-9._-]{3,50}$/', $username)) {
    $errors[] = 'Username harus 3-50 karakter dan hanya boleh berisi huruf, angka, titik, garis bawah, atau tanda hubung.';
}

if (strlen($password) < 8) {
    $errors[] = 'Password minimal 8 karakter.';
}

if (strlen($password) > 72) {
    $errors[] = 'Password maksimal 72 karakter (batas aman algoritma bcrypt).';
}

if ($namaLengkap === '' || mb_strlen($namaLengkap) > 100) {
    $errors[] = 'Nama lengkap wajib diisi dan maksimal 100 karakter.';
}

if ($errors !== []) {
    fwrite(STDERR, "Setup akun admin gagal:\n");
    foreach ($errors as $error) {
        fwrite(STDERR, ' - ' . $error . "\n");
    }
    exit(1);
}

try {
    $users = new User();
    $hash = password_hash($password, PASSWORD_DEFAULT);

    if ($hash === false) {
        throw new RuntimeException('Password hash gagal dibuat.');
    }

    $sudahAda = $users->existsByUsername($username);

    if ($sudahAda) {
        $users->updateCredentials($username, $hash, $namaLengkap);
        echo "Akun admin \"{$username}\" berhasil diperbarui.\n";
    } else {
        $users->create($username, $hash, $namaLengkap);
        echo "Akun admin \"{$username}\" berhasil dibuat.\n";
    }

    echo "Nama lengkap : {$namaLengkap}\n";
    echo "Username     : {$username}\n";
    echo "Password     : {$password}\n\n";
    echo "Segera ganti password demo ini sebelum aplikasi digunakan di produksi.\n";
} catch (Throwable $exception) {
    fwrite(STDERR, "Gagal membuat akun admin: " . $exception->getMessage() . "\n");
    fwrite(STDERR, "Pastikan database sudah dibuat dari database/schema.sql dan konfigurasi koneksi benar.\n");
    exit(1);
}
