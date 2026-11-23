<?php

declare(strict_types=1);

function flash(string $type, string $message): void
{
    $_SESSION['flash'][] = [
        'type' => $type,
        'message' => $message,
    ];
}

/**
 * @return array<int, array{type:string,message:string}>
 */
function get_flashes(): array
{
    $flashes = $_SESSION['flash'] ?? [];
    unset($_SESSION['flash']);

    if (!is_array($flashes)) {
        return [];
    }

    return array_values(array_filter($flashes, static function (mixed $flash): bool {
        return is_array($flash) && isset($flash['type'], $flash['message']);
    }));
}

function alert_class(string $type): string
{
    return match ($type) {
        'success' => 'success',
        'error', 'danger' => 'danger',
        'warning' => 'warning',
        default => 'info',
    };
}
