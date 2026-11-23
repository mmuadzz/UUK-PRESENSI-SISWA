<?php

declare(strict_types=1);

function auth_user(): ?array
{
    $user = $_SESSION['auth_user'] ?? null;

    return is_array($user) ? $user : null;
}

function is_logged_in(): bool
{
    return auth_user() !== null;
}

/**
 * @param array<string, mixed> $user
 */
function login_user(array $user): void
{
    if (session_status() === PHP_SESSION_ACTIVE) {
        session_regenerate_id(true);
    }

    $_SESSION['auth_user'] = [
        'id' => (int) ($user['id'] ?? 0),
        'username' => (string) ($user['username'] ?? ''),
        'nama_lengkap' => (string) ($user['nama_lengkap'] ?? ''),
    ];
}

function logout_user(): void
{
    $_SESSION = [];

    if (ini_get('session.use_cookies')) {
        $params = session_get_cookie_params();
        setcookie(session_name(), '', [
            'expires' => time() - 42000,
            'path' => (string) ($params['path'] ?? '/'),
            'domain' => (string) ($params['domain'] ?? ''),
            'secure' => (bool) ($params['secure'] ?? false),
            'httponly' => (bool) ($params['httponly'] ?? true),
            'samesite' => (string) ($params['samesite'] ?? 'Lax'),
        ]);
    }

    if (session_status() === PHP_SESSION_ACTIVE) {
        session_destroy();
    }
}

function require_auth(): void
{
    if (is_logged_in()) {
        return;
    }

    if (is_ajax()) {
        http_response_code(401);
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode([
            'status' => 'error',
            'message' => 'Sesi Anda telah berakhir. Muat ulang halaman lalu masuk kembali.',
        ]);
        exit;
    }

    flash('warning', 'Silakan masuk terlebih dahulu untuk mengakses halaman administrator.');
    redirect('login.php');
}
