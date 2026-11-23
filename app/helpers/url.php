<?php

declare(strict_types=1);

/**
 * @return array<string, mixed>
 */
function app_config(): array
{
    static $config = null;

    if ($config === null) {
        $config = require APP_ROOT . '/app/config/app.php';
    }

    return $config;
}

/**
 * Menentukan basis URL aplikasi berdasarkan document root.
 * Contoh hasil: '' (document root = public/) atau '/presensi-smk/public'.
 */
function base_path(): string
{
    static $base = null;

    if ($base !== null) {
        return $base;
    }

    $configured = trim((string) (app_config()['base_url'] ?? ''));
    if ($configured !== '') {
        $base = '/' . trim($configured, '/');
        return $base === '/' ? '' : $base;
    }

    $publicDirectory = realpath(APP_ROOT . '/public');
    $documentRoot = realpath((string) ($_SERVER['DOCUMENT_ROOT'] ?? ''));

    if ($publicDirectory !== false && $documentRoot !== false) {
        $publicDirectory = str_replace('\\', '/', $publicDirectory);
        $documentRoot = rtrim(str_replace('\\', '/', $documentRoot), '/');

        if ($documentRoot !== '' && str_starts_with($publicDirectory, $documentRoot)) {
            $relative = substr($publicDirectory, strlen($documentRoot));
            $base = '/' . trim((string) $relative, '/');
            $base = $base === '/' ? '' : $base;

            return $base;
        }
    }

    // Cadangan: deteksi dari lokasi script yang sedang dijalankan.
    $scriptDirectory = rtrim(str_replace('\\', '/', dirname((string) ($_SERVER['SCRIPT_NAME'] ?? ''))), '/');

    if (($position = strpos($scriptDirectory, '/public')) !== false) {
        $base = substr($scriptDirectory, 0, $position + 7);
        return $base === '' ? '' : $base;
    }

    $base = $scriptDirectory === '/' ? '' : $scriptDirectory;

    return $base;
}

function base_url(string $path = ''): string
{
    $base = base_path();
    $path = ltrim($path, '/');

    if ($path === '') {
        return $base === '' ? '/' : $base . '/';
    }

    return $base . '/' . $path;
}

function asset_url(string $path): string
{
    return base_url('assets/' . ltrim($path, '/'));
}

function redirect(string $path): void
{
    header('Location: ' . base_url($path));
    exit;
}

/**
 * Kembali ke halaman sebelumnya bila masih berada di dalam aplikasi.
 */
function redirect_back(string $fallback = 'dashboard.php'): void
{
    $referer = (string) ($_SERVER['HTTP_REFERER'] ?? '');
    $refererPath = (string) (parse_url($referer, PHP_URL_PATH) ?? '');
    $base = base_path();

    if ($refererPath !== '') {
        $prefix = $base === '' ? '/' : $base . '/';
        $isLocal = $base === '' || str_starts_with($refererPath, $prefix);

        if ($isLocal) {
            header('Location: ' . $referer);
            exit;
        }
    }

    redirect($fallback);
}

/**
 * Menyusun query string dengan mempertahankan filter yang sedang aktif.
 *
 * @param array<string, scalar|null> $changes
 */
function query_string(array $changes): string
{
    $params = $_GET;

    foreach ($changes as $key => $value) {
        if ($value === null || $value === '') {
            unset($params[$key]);
        } else {
            $params[$key] = $value;
        }
    }

    return http_build_query($params);
}

function is_ajax(): bool
{
    $requestedWith = strtolower((string) ($_SERVER['HTTP_X_REQUESTED_WITH'] ?? ''));
    $accept = strtolower((string) ($_SERVER['HTTP_ACCEPT'] ?? ''));

    return $requestedWith === 'xmlhttprequest' || str_contains($accept, 'application/json');
}
