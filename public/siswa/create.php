<?php

declare(strict_types=1);

require dirname(__DIR__, 2) . '/app/bootstrap.php';

require_auth();

$controller = new SiswaController();
$data = $controller->create();

$errors = $data['errors'];
$old = $data['old'];
$foto_lama = $data['foto_lama'];
$mode = $data['mode'];
$daftar_kelas = $data['daftar_kelas'];

$pageTitle = 'Tambah Siswa';
$activeMenu = 'siswa';

require APP_ROOT . '/app/views/siswa/form.php';
