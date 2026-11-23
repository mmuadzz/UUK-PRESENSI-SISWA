<?php

declare(strict_types=1);

require dirname(__DIR__, 2) . '/app/bootstrap.php';

require_auth();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    if (is_ajax()) {
        http_response_code(405);
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode([
            'status' => 'error',
            'message' => 'Metode request tidak didukung.',
        ]);
        exit;
    }

    redirect('presensi/simulasi.php');
}

$controller = new PresensiController();
$result = $controller->tap();

if (!is_ajax()) {
    // Fallback tanpa JavaScript: simpan hasil lalu tampilkan di halaman simulasi.
    $_SESSION['tap_result'] = $result;
    redirect('presensi/simulasi.php');
}

header('Content-Type: application/json; charset=utf-8');
header('Cache-Control: no-store');
echo json_encode($result);
