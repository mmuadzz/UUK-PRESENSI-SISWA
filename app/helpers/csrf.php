<?php

declare(strict_types=1);

function csrf_token(): string
{
    if (empty($_SESSION['csrf_token']) || !is_string($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }

    return $_SESSION['csrf_token'];
}

function csrf_field(): string
{
    return '<input type="hidden" name="csrf_token" value="' . e(csrf_token()) . '">';
}

function csrf_is_valid(mixed $token): bool
{
    return is_string($token) && $token !== '' && hash_equals(csrf_token(), $token);
}

/**
 * Menghentikan request jika token CSRF tidak valid.
 */
function csrf_verify(string $fallbackPath = 'dashboard.php'): void
{
    $token = $_POST['csrf_token'] ?? ($_SERVER['HTTP_X_CSRF_TOKEN'] ?? null);

    if (csrf_is_valid($token)) {
        return;
    }

    if (is_ajax()) {
        http_response_code(419);
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode([
            'status' => 'error',
            'message' => 'Sesi keamanan telah berakhir. Muat ulang halaman lalu coba lagi.',
        ]);
        exit;
    }

    flash('error', 'Sesi keamanan telah berakhir. Silakan muat ulang halaman lalu coba lagi.');
    redirect_back($fallbackPath);
}
