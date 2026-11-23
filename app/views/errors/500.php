<?php

declare(strict_types=1);

/** @var string $message */
$message = $message ?? 'Terjadi kesalahan pada server.';
?>
<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Kesalahan Server &middot; <?= e(APP_SHORT_NAME) ?></title>
    <style>
        body {
            margin: 0;
            min-height: 100vh;
            display: grid;
            place-items: center;
            background: #F2F7FB;
            color: #263746;
            font-family: system-ui, -apple-system, "Segoe UI", sans-serif;
            padding: 24px;
        }
        .error-card {
            width: 100%;
            max-width: 520px;
            background: #FFFFFF;
            border: 1px solid #DCE4EB;
            border-radius: 16px;
            padding: 32px;
            box-shadow: 0 8px 24px rgba(18, 52, 90, 0.08);
            text-align: center;
        }
        .error-icon {
            width: 56px;
            height: 56px;
            margin: 0 auto 16px;
            border-radius: 50%;
            display: grid;
            place-items: center;
            background: #FCE8E8;
            color: #B23A3A;
            font-size: 24px;
            font-weight: 700;
        }
        h1 { font-size: 1.25rem; margin: 0 0 8px; color: #12345A; }
        p { color: #6B7C8F; line-height: 1.6; margin: 0 0 20px; }
        a {
            display: inline-block;
            background: #174A78;
            color: #FFFFFF;
            text-decoration: none;
            padding: 10px 18px;
            border-radius: 8px;
            font-weight: 600;
        }
        a:hover { background: #21649A; }
    </style>
</head>
<body>
    <div class="error-card">
        <div class="error-icon">!</div>
        <h1>Terjadi kesalahan</h1>
        <p><?= e($message) ?></p>
        <a href="<?= e(base_url('dashboard.php')) ?>">Kembali ke Dashboard</a>
    </div>
</body>
</html>
