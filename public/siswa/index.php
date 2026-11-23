<?php

declare(strict_types=1);

require dirname(__DIR__, 2) . '/app/bootstrap.php';

require_auth();

$controller = new SiswaController();
$data = $controller->index();

$daftar = $data['daftar'];
$total = $data['total'];
$page = $data['page'];
$total_pages = $data['total_pages'];
$per_page = $data['per_page'];
$q = $data['q'];
$kelas_id = $data['kelas_id'];
$daftar_kelas = $data['daftar_kelas'];

$pageTitle = 'Data Siswa';
$activeMenu = 'siswa';

require APP_ROOT . '/app/views/siswa/index.php';
