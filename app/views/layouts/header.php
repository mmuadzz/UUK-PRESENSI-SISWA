<?php

declare(strict_types=1);

/** @var string|null $pageTitle */
/** @var string|null $activeMenu */

$pageTitle = $pageTitle ?? 'Dashboard';
$activeMenu = $activeMenu ?? '';
$currentUser = auth_user();
?>
<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="theme-color" content="#12345A">
    <title><?= e($pageTitle) ?> &middot; <?= e(APP_SHORT_NAME) ?></title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link rel="stylesheet" href="<?= e(asset_url('css/app.css')) ?>">
</head>
<body>
<div class="app-shell">
    <?php require __DIR__ . '/sidebar.php'; ?>

    <div class="app-main">
        <header class="app-topbar">
            <div class="d-flex align-items-center gap-3 min-w-0">
                <button class="btn btn-icon btn-light d-lg-none" type="button"
                        data-bs-toggle="offcanvas" data-bs-target="#appSidebar"
                        aria-controls="appSidebar" aria-label="Buka menu">
                    <i class="bi bi-list"></i>
                </button>
                <div class="min-w-0">
                    <h1 class="app-page-title text-truncate"><?= e($pageTitle) ?></h1>
                    <div class="app-page-date d-none d-sm-block"><?= e(format_tanggal(date('Y-m-d'), true)) ?></div>
                </div>
            </div>

            <div class="app-topbar-user">
                <span class="avatar avatar-sm avatar-initials">
                    <?= e(initials((string) ($currentUser['nama_lengkap'] ?? 'Administrator'))) ?>
                </span>
                <div class="d-none d-md-block lh-sm">
                    <div class="fw-semibold small"><?= e($currentUser['nama_lengkap'] ?? 'Administrator') ?></div>
                    <div class="text-muted" style="font-size: .75rem;">Administrator</div>
                </div>
            </div>
        </header>

        <main class="app-content">
            <?php require __DIR__ . '/alerts.php'; ?>
