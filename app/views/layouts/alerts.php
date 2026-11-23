<?php

declare(strict_types=1);

$flashes = get_flashes();

if ($flashes === []) {
    return;
}
?>
<div class="flash-stack">
    <?php foreach ($flashes as $flash): ?>
        <?php
        $type = alert_class((string) $flash['type']);
        $icon = match ($type) {
            'success' => 'bi-check-circle-fill',
            'danger' => 'bi-exclamation-octagon-fill',
            'warning' => 'bi-exclamation-triangle-fill',
            default => 'bi-info-circle-fill',
        };
        ?>
        <div class="alert alert-<?= e($type) ?> alert-dismissible fade show app-alert" role="alert">
            <i class="bi <?= e($icon) ?>" aria-hidden="true"></i>
            <div class="app-alert-message"><?= e($flash['message']) ?></div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Tutup"></button>
        </div>
    <?php endforeach; ?>
</div>
