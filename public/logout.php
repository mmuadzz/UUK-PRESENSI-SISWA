<?php

declare(strict_types=1);

require dirname(__DIR__) . '/app/bootstrap.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect(is_logged_in() ? 'dashboard.php' : 'login.php');
}

csrf_verify('dashboard.php');

logout_user();

// Session baru hanya untuk menampilkan pesan sukses di halaman login.
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
    session_regenerate_id(true);
}

flash('success', 'Anda telah keluar dari aplikasi.');
redirect('login.php');
