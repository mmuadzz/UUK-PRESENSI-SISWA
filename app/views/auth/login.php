<?php

declare(strict_types=1);

/** @var array<string, string> $errors */
/** @var string $username */

$flashes = get_flashes();
?>
<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="theme-color" content="#12345A">
    <title>Masuk &middot; <?= e(APP_SHORT_NAME) ?></title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link rel="stylesheet" href="<?= e(asset_url('css/app.css')) ?>">
</head>
<body class="login-page">
<div class="login-orbit login-orbit--one" aria-hidden="true"></div>
<div class="login-orbit login-orbit--two" aria-hidden="true"></div>

<main class="login-wrapper">
    <div class="login-card">
        <div class="login-brand">
            <span class="brand-mark brand-mark--login"><i class="bi bi-mortarboard-fill"></i></span>
            <div>
                <div class="login-school"><?= e(APP_SCHOOL) ?></div>
                <div class="login-product">Administrator Presensi</div>
            </div>
        </div>

        <h1 class="login-title">Masuk ke panel admin</h1>
        <p class="login-subtitle">Kelola data kelas, siswa, dan kehadiran harian.</p>

        <?php if ($flashes !== []): ?>
            <?php foreach ($flashes as $flash): ?>
                <?php $type = alert_class((string) $flash['type']); ?>
                <div class="alert alert-<?= e($type) ?> app-alert py-2 px-3 mb-3" role="alert">
                    <i class="bi <?= $type === 'success' ? 'bi-check-circle-fill' : 'bi-info-circle-fill' ?>" aria-hidden="true"></i>
                    <div class="app-alert-message"><?= e($flash['message']) ?></div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>

        <?php if (isset($errors['login'])): ?>
            <div class="alert alert-danger app-alert py-2 px-3 mb-3" role="alert">
                <i class="bi bi-exclamation-octagon-fill" aria-hidden="true"></i>
                <div class="app-alert-message"><?= e($errors['login']) ?></div>
            </div>
        <?php endif; ?>

        <form method="post" action="<?= e(base_url('login.php')) ?>" novalidate>
            <?= csrf_field() ?>

            <div class="mb-3">
                <label for="username" class="form-label">Username</label>
                <input type="text"
                       class="form-control<?= isset($errors['username']) ? ' is-invalid' : '' ?>"
                       id="username" name="username" value="<?= e($username) ?>"
                       autocomplete="username" placeholder="Masukkan username" required autofocus>
                <?php if (isset($errors['username'])): ?>
                    <div class="invalid-feedback"><?= e($errors['username']) ?></div>
                <?php endif; ?>
            </div>

            <div class="mb-4">
                <label for="password" class="form-label">Password</label>
                <div class="input-group">
                    <input type="password"
                           class="form-control<?= isset($errors['password']) ? ' is-invalid' : '' ?>"
                           id="password" name="password"
                           autocomplete="current-password" placeholder="Masukkan password" required>
                    <button class="btn btn-outline-secondary password-toggle" type="button"
                            data-toggle-password="#password" aria-label="Tampilkan password">
                        <i class="bi bi-eye" aria-hidden="true"></i>
                    </button>
                </div>
                <?php if (isset($errors['password'])): ?>
                    <div class="invalid-feedback d-block"><?= e($errors['password']) ?></div>
                <?php endif; ?>
            </div>

            <button type="submit" class="btn btn-primary btn-lg w-100">
                <i class="bi bi-box-arrow-in-right me-1" aria-hidden="true"></i>
                Masuk
            </button>
        </form>

        <p class="login-footnote">
            Gunakan akun administrator yang dibuat melalui
            <code>scripts/setup_admin.php</code>.
        </p>
    </div>

    <p class="login-copyright">&copy; <?= e((string) date('Y')) ?> <?= e(APP_SCHOOL) ?></p>
</main>

<script src="<?= e(asset_url('js/app.js')) ?>"></script>
</body>
</html>
