<?php

declare(strict_types=1);

function e(mixed $value): string
{
    return htmlspecialchars((string) $value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

function format_tanggal(?string $datetime, bool $denganHari = false): string
{
    if ($datetime === null || trim($datetime) === '') {
        return '-';
    }

    $timestamp = strtotime($datetime);
    if ($timestamp === false) {
        return '-';
    }

    $bulan = [
        1 => 'Januari',
        2 => 'Februari',
        3 => 'Maret',
        4 => 'April',
        5 => 'Mei',
        6 => 'Juni',
        7 => 'Juli',
        8 => 'Agustus',
        9 => 'September',
        10 => 'Oktober',
        11 => 'November',
        12 => 'Desember',
    ];

    $hari = [
        'Sunday' => 'Minggu',
        'Monday' => 'Senin',
        'Tuesday' => 'Selasa',
        'Wednesday' => 'Rabu',
        'Thursday' => 'Kamis',
        'Friday' => 'Jumat',
        'Saturday' => 'Sabtu',
    ];

    $teks = (int) date('j', $timestamp) . ' ' . $bulan[(int) date('n', $timestamp)] . ' ' . (int) date('Y', $timestamp);

    if ($denganHari) {
        $teks = ($hari[date('l', $timestamp)] ?? date('l', $timestamp)) . ', ' . $teks;
    }

    return $teks;
}

function format_jam(?string $datetime, bool $denganDetik = true): string
{
    if ($datetime === null || trim($datetime) === '') {
        return '-';
    }

    $timestamp = strtotime($datetime);
    if ($timestamp === false) {
        return '-';
    }

    return date($denganDetik ? 'H:i:s' : 'H:i', $timestamp) . ' WITA';
}

function format_datetime_id(?string $datetime, bool $denganHari = false): string
{
    if ($datetime === null || trim($datetime) === '') {
        return '-';
    }

    return format_tanggal($datetime, $denganHari) . ' · ' . format_jam($datetime);
}

function initials(string $nama): string
{
    $nama = trim($nama);
    if ($nama === '') {
        return '?';
    }

    $parts = preg_split('/\s+/u', $nama) ?: [];
    $parts = array_values(array_filter($parts, static fn (string $part): bool => $part !== ''));

    if (count($parts) === 1) {
        return mb_strtoupper(mb_substr($parts[0], 0, 2));
    }

    $first = mb_substr($parts[0], 0, 1);
    $second = mb_substr($parts[count($parts) - 1], 0, 1);

    return mb_strtoupper($first . $second);
}

function valid_date(string $tanggal): bool
{
    $date = DateTimeImmutable::createFromFormat('!Y-m-d', $tanggal);

    return $date instanceof DateTimeImmutable && $date->format('Y-m-d') === $tanggal;
}

/**
 * Avatar foto siswa atau inisial nama bila foto tidak tersedia.
 */
function avatar_html(?string $foto, string $nama, string $extraClass = ''): string
{
    $classes = trim('avatar ' . $extraClass);

    if ($foto !== null && $foto !== '') {
        return '<span class="' . e($classes) . '">'
            . '<img src="' . e(foto_url($foto)) . '" alt="Foto ' . e($nama) . '" loading="lazy">'
            . '</span>';
    }

    return '<span class="' . e($classes) . ' avatar-initials" aria-hidden="true">' . e(initials($nama)) . '</span>';
}
