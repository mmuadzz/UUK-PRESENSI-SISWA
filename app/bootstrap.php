<?php

declare(strict_types=1);

/**
 * Bootstrap aplikasi: konfigurasi, autoload, session, helper, dan error handler.
 * Semua halaman public/ memuat file ini paling awal.
 */

define('APP_ROOT', dirname(__DIR__));

$appConfig = require APP_ROOT . '/app/config/app.php';

define('APP_NAME', (string) $appConfig['name']);
define('APP_SHORT_NAME', (string) $appConfig['short_name']);
define('APP_SCHOOL', (string) $appConfig['school']);
define('APP_DEBUG', (bool) $appConfig['debug']);

date_default_timezone_set((string) $appConfig['timezone']);

spl_autoload_register(static function (string $class): void {
    foreach (['core', 'models', 'controllers'] as $directory) {
        $file = APP_ROOT . '/app/' . $directory . '/' . $class . '.php';
        if (is_file($file)) {
            require $file;
            return;
        }
    }
});

if (PHP_SAPI !== 'cli' && session_status() !== PHP_SESSION_ACTIVE) {
    $isHttps = (!empty($_SERVER['HTTPS']) && strtolower((string) $_SERVER['HTTPS']) !== 'off')
        || ((int) ($_SERVER['SERVER_PORT'] ?? 0) === 443);

    session_name('presensi_session');
    session_set_cookie_params([
        'lifetime' => 0,
        'path' => '/',
        'domain' => '',
        'secure' => $isHttps,
        'httponly' => true,
        'samesite' => 'Lax',
    ]);
    ini_set('session.use_strict_mode', '1');
    ini_set('session.use_only_cookies', '1');

    session_start();
}

require APP_ROOT . '/app/helpers/url.php';
require APP_ROOT . '/app/helpers/csrf.php';
require APP_ROOT . '/app/helpers/flash.php';
require APP_ROOT . '/app/helpers/auth.php';
require APP_ROOT . '/app/helpers/form.php';
require APP_ROOT . '/app/helpers/format.php';
require APP_ROOT . '/app/helpers/upload.php';

set_exception_handler(static function (Throwable $exception): void {
    error_log('Unhandled error: ' . $exception->getMessage() . ' in ' . $exception->getFile() . ':' . $exception->getLine());

    http_response_code(500);

    if (is_ajax()) {
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode([
            'status' => 'error',
            'message' => 'Terjadi kesalahan pada server. Silakan coba lagi.',
        ]);
        return;
    }

    $message = APP_DEBUG
        ? $exception->getMessage()
        : 'Terjadi kesalahan pada server. Silakan coba lagi atau hubungi administrator.';

    require APP_ROOT . '/app/views/errors/500.php';
});
