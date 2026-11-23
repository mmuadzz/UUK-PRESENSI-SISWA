<?php

declare(strict_types=1);

require dirname(__DIR__, 2) . '/app/bootstrap.php';

require_auth();

$controller = new SiswaController();
$controller->delete();
