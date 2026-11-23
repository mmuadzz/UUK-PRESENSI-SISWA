<?php

declare(strict_types=1);

require dirname(__DIR__, 2) . '/app/bootstrap.php';

require_auth();

$controller = new KelasController();
$data = $controller->index();

$daftar = $data['daftar'];
$errors = $data['errors'];
$old = $data['old'];
$modal = $data['modal'];

$pageTitle = 'Data Kelas';
$activeMenu = 'kelas';

require APP_ROOT . '/app/views/kelas/index.php';
