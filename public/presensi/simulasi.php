<?php

declare(strict_types=1);

require dirname(__DIR__, 2) . '/app/bootstrap.php';

require_auth();

$controller = new PresensiController();
$data = $controller->simulasi();

$riwayat = $data['riwayat'];
$hasil = $data['hasil'];
$statistik = $data['statistik'];

$pageTitle = 'Simulasi Presensi';
$activeMenu = 'simulasi';

require APP_ROOT . '/app/views/presensi/simulasi.php';
