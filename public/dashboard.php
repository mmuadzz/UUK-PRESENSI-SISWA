<?php

declare(strict_types=1);

require dirname(__DIR__) . '/app/bootstrap.php';

require_auth();

$presensiModel = new Presensi();
$siswaModel = new Siswa();
$kelasModel = new Kelas();

$today = date('Y-m-d');

$statistik = [
    'total_siswa' => $siswaModel->count(),
    'total_kelas' => $kelasModel->count(),
    'hadir' => $presensiModel->countHadir($today),
    'belum' => $presensiModel->countBelum($today),
];

$persentaseHadir = $statistik['total_siswa'] > 0
    ? (int) round(($statistik['hadir'] / $statistik['total_siswa']) * 100)
    : 0;

$terbaru = $presensiModel->latest(8);

$pageTitle = 'Dashboard';
$activeMenu = 'dashboard';
$tanggalHariIni = format_tanggal($today, true);

require APP_ROOT . '/app/views/dashboard/index.php';
