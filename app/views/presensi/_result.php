<?php

declare(strict_types=1);

/** @var array<string, mixed> $hasil */

$status = (string) ($hasil['status'] ?? 'error');
$siswa = isset($hasil['siswa']) && is_array($hasil['siswa']) ? $hasil['siswa'] : null;

$variant = match ($status) {
    'success' => 'success',
    'duplicate' => 'warning',
    'invalid', 'not_found' => 'danger',
    default => 'danger',
};

$icon = match ($status) {
    'success' => 'bi-check-circle-fill',
    'duplicate' => 'bi-clock-history',
    'not_found' => 'bi-person-x-fill',
    'invalid' => 'bi-exclamation-circle-fill',
    default => 'bi-exclamation-octagon-fill',
};

$title = match ($status) {
    'success' => 'Presensi berhasil',
    'duplicate' => 'Presensi sudah tercatat',
    'not_found' => 'Siswa tidak ditemukan',
    'invalid' => 'NISN tidak valid',
    default => 'Terjadi kesalahan',
};
?>
<div class="tap-result tap-result--<?= e($variant) ?>" role="status">
    <span class="tap-result-icon"><i class="bi <?= e($icon) ?>" aria-hidden="true"></i></span>
    <div class="tap-result-body">
        <div class="tap-result-title"><?= e($title) ?></div>
        <p class="tap-result-message"><?= e((string) ($hasil['message'] ?? '')) ?></p>

        <?php if ($siswa !== null): ?>
            <div class="tap-result-person">
                <?php if (!empty($siswa['foto_url'])): ?>
                    <span class="avatar avatar-lg">
                        <img src="<?= e($siswa['foto_url']) ?>" alt="Foto <?= e($siswa['nama']) ?>">
                    </span>
                <?php else: ?>
                    <span class="avatar avatar-lg avatar-initials">
                        <?= e(initials((string) $siswa['nama'])) ?>
                    </span>
                <?php endif; ?>
                <div class="min-w-0">
                    <div class="fw-semibold"><?= e($siswa['nama']) ?></div>
                    <div class="text-muted small">
                        <?= e($siswa['nisn']) ?> &middot; <?= e($siswa['nama_kelas']) ?>
                    </div>
                    <?php if (!empty($hasil['waktu_teks'])): ?>
                        <div class="tap-result-time">
                            <?= $status === 'duplicate' ? 'Tercatat' : 'Dicatat' ?>
                            <?php if (!empty($hasil['tanggal_teks'])): ?>
                                pada <?= e($hasil['tanggal_teks']) ?>
                            <?php endif; ?>
                            &middot; <?= e($hasil['waktu_teks']) ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>
