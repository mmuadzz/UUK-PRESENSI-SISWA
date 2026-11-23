<?php

declare(strict_types=1);

require dirname(__DIR__, 2) . '/app/bootstrap.php';

require_auth();

$controller = new PresensiController();
$data = $controller->rekap();

$tanggal = $data['tanggal'];
$kelas_id = $data['kelas_id'];
$daftar_kelas = $data['daftar_kelas'];
$rows = $data['rows'];
$hadir = $data['hadir'];
$tidak_hadir = $data['tidak_hadir'];
$total = $data['total'];

$pageTitle = 'Rekap Presensi';
$activeMenu = 'rekap';

require APP_ROOT . '/app/views/presensi/rekap.php';
