<?php

declare(strict_types=1);

/**
 * Membuat dan menyimpan satu koneksi PDO untuk seluruh request.
 */
final class Database
{
    private static ?PDO $connection = null;

    private function __construct()
    {
    }

    public static function connection(): PDO
    {
        if (self::$connection instanceof PDO) {
            return self::$connection;
        }

        /** @var array{host:string,port:int,database:string,username:string,password:string,charset:string} $config */
        $config = require dirname(__DIR__) . '/config/database.php';

        $dsn = sprintf(
            'mysql:host=%s;port=%d;dbname=%s;charset=%s',
            $config['host'],
            $config['port'],
            $config['database'],
            $config['charset']
        );

        try {
            self::$connection = new PDO($dsn, $config['username'], $config['password'], [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false,
            ]);

            // Menjaga konsistensi waktu presensi dengan zona Asia/Makassar (UTC+8).
            self::$connection->exec("SET time_zone = '+08:00'");
        } catch (PDOException $exception) {
            error_log('Koneksi database gagal: ' . $exception->getMessage());

            throw new RuntimeException(
                'Tidak dapat terhubung ke database. Periksa konfigurasi pada app/config/database.php.',
                0,
                $exception
            );
        }

        return self::$connection;
    }
}
