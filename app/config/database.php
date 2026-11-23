<?php

declare(strict_types=1);

// Nilai bawaan mengikuti konfigurasi XAMPP/Laragon (root tanpa password).
// Untuk produksi, gunakan environment variable dan jangan commit kredensial.
return [
    'host' => getenv('DB_HOST') ?: '127.0.0.1',
    'port' => (int) (getenv('DB_PORT') ?: 3306),
    'database' => getenv('DB_NAME') ?: 'presensi_smk',
    'username' => getenv('DB_USER') ?: 'root',
    'password' => getenv('DB_PASSWORD') !== false ? (string) getenv('DB_PASSWORD') : '',
    'charset' => 'utf8mb4',
];
