<?php

declare(strict_types=1);

const UPLOAD_FOTO_MAX_BYTES = 2 * 1024 * 1024;
const UPLOAD_FOTO_ALLOWED_EXTENSIONS = ['jpg', 'jpeg', 'png'];

function upload_siswa_dir(): string
{
    return APP_ROOT . '/public/uploads/siswa';
}

/**
 * Memvalidasi dan menyimpan foto siswa dengan nama file acak.
 * Mengembalikan path relatif baru, atau null bila tidak ada file yang diunggah.
 * Bila gagal, $error berisi pesan dalam Bahasa Indonesia.
 *
 * @param array<string, mixed>|null $file
 */
function upload_foto(?array $file, ?string &$error): ?string
{
    $error = null;

    if ($file === null || !isset($file['error']) || (int) $file['error'] === UPLOAD_ERR_NO_FILE) {
        return null;
    }

    $uploadError = (int) $file['error'];

    if ($uploadError !== UPLOAD_ERR_OK) {
        $error = match ($uploadError) {
            UPLOAD_ERR_INI_SIZE, UPLOAD_ERR_FORM_SIZE => 'Ukuran foto melebihi batas 2 MB.',
            UPLOAD_ERR_PARTIAL => 'Foto gagal diunggah secara lengkap. Silakan coba lagi.',
            default => 'Terjadi kesalahan saat mengunggah foto.',
        };

        return null;
    }

    $name = is_string($file['name'] ?? null) ? $file['name'] : '';
    $temporaryName = is_string($file['tmp_name'] ?? null) ? $file['tmp_name'] : '';
    $size = (int) ($file['size'] ?? 0);

    if ($temporaryName === '' || !is_uploaded_file($temporaryName)) {
        $error = 'Berkas foto tidak valid.';
        return null;
    }

    if ($size <= 0 || $size > UPLOAD_FOTO_MAX_BYTES) {
        $error = 'Ukuran foto maksimal 2 MB.';
        return null;
    }

    $extension = strtolower((string) pathinfo($name, PATHINFO_EXTENSION));
    if (!in_array($extension, UPLOAD_FOTO_ALLOWED_EXTENSIONS, true)) {
        $error = 'Foto harus berformat JPG, JPEG, atau PNG.';
        return null;
    }

    $imageInfo = @getimagesize($temporaryName);
    if ($imageInfo === false) {
        $error = 'Berkas yang diunggah bukan gambar yang valid.';
        return null;
    }

    $mimeToExtension = [
        'image/jpeg' => 'jpg',
        'image/png' => 'png',
    ];

    $mime = (string) ($imageInfo['mime'] ?? '');
    if (!isset($mimeToExtension[$mime])) {
        $error = 'Foto harus berformat JPG, JPEG, atau PNG.';
        return null;
    }

    $directory = upload_siswa_dir();
    if (!is_dir($directory) && !mkdir($directory, 0775, true) && !is_dir($directory)) {
        $error = 'Folder penyimpanan foto tidak dapat dibuat.';
        return null;
    }

    $fileName = bin2hex(random_bytes(16)) . '.' . $mimeToExtension[$mime];
    $destination = $directory . '/' . $fileName;

    if (!move_uploaded_file($temporaryName, $destination)) {
        $error = 'Foto gagal disimpan. Silakan coba lagi.';
        return null;
    }

    return 'uploads/siswa/' . $fileName;
}

/**
 * Menghapus foto lama dari folder upload (hanya file di uploads/siswa/).
 */
function delete_upload(?string $relativePath): void
{
    if ($relativePath === null || $relativePath === '') {
        return;
    }

    $relativePath = str_replace('\\', '/', $relativePath);
    if (!str_starts_with($relativePath, 'uploads/siswa/')) {
        return;
    }

    $fileName = basename($relativePath);
    if ($fileName === '' || $fileName === '.' || $fileName === '..') {
        return;
    }

    $file = upload_siswa_dir() . '/' . $fileName;
    if (is_file($file)) {
        @unlink($file);
    }
}

function foto_url(?string $path): ?string
{
    if ($path === null || trim($path) === '') {
        return null;
    }

    return base_url($path);
}
