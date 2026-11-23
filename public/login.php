<?php

declare(strict_types=1);

require dirname(__DIR__) . '/app/bootstrap.php';

if (is_logged_in()) {
    redirect('dashboard.php');
}

$controller = new AuthController();
$data = $controller->login();

$errors = $data['errors'];
$username = $data['username'];

require APP_ROOT . '/app/views/auth/login.php';
