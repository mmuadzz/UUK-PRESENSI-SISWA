<?php

declare(strict_types=1);

/**
 * Menyimpan error validasi dan isian lama agar bisa ditampilkan kembali
 * setelah redirect (dipakai form berbasis modal seperti data kelas).
 *
 * @param array<string, string> $errors
 * @param array<string, mixed> $old
 */
function remember_form_state(array $errors, array $old = [], ?string $modal = null): void
{
    $_SESSION['form_state'] = [
        'errors' => $errors,
        'old' => $old,
        'modal' => $modal,
    ];
}

/**
 * @return array{errors:array<string,string>,old:array<string,mixed>,modal:?string}
 */
function pull_form_state(): array
{
    $state = $_SESSION['form_state'] ?? [];
    unset($_SESSION['form_state']);

    if (!is_array($state)) {
        $state = [];
    }

    return [
        'errors' => is_array($state['errors'] ?? null) ? $state['errors'] : [],
        'old' => is_array($state['old'] ?? null) ? $state['old'] : [],
        'modal' => is_string($state['modal'] ?? null) ? $state['modal'] : null,
    ];
}
