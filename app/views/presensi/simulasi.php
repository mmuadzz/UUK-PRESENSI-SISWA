<?php

declare(strict_types=1);

/** @var array<int, array<string, mixed>> $riwayat */
/** @var array<string, mixed>|null $hasil */
/** @var array{hadir:int,total:int} $statistik */

require __DIR__ . '/../layouts/header.php';

$persentase = $statistik['total'] > 0
    ? (int) round(($statistik['hadir'] / $statistik['total']) * 100)
    : 0;
?>

<div class="page-head">
    <div>
        <h2 class="page-head-title">Simulasi Presensi</h2>
        <p class="page-head-text">
            Simulasikan tap kartu dengan memasukkan NISN siswa. Waktu presensi diambil otomatis dari server.
        </p>
    </div>
    <a href="<?= e(base_url('presensi/rekap.php')) ?>" class="btn btn-outline-primary">
        <i class="bi bi-clipboard-data me-1" aria-hidden="true"></i>
        Buka Rekap
    </a>
</div>

<div class="row g-4">
    <div class="col-xl-7">
        <div class="card app-card tap-card">
            <div class="card-body">
                <div class="tap-card-head">
                    <span class="tap-card-icon"><i class="bi bi-upc-scan" aria-hidden="true"></i></span>
                    <div>
                        <h3 class="app-card-title">Tap Kartu / Input NISN</h3>
                        <p class="app-card-subtitle">Fokus pada input, masukkan NISN, lalu tekan Enter.</p>
                    </div>
                </div>

                <form method="post" action="<?= e(base_url('presensi/tap.php')) ?>" id="tapForm" novalidate>
                    <?= csrf_field() ?>

                    <label for="nisnInput" class="form-label">NISN Siswa</label>
                    <input type="text"
                           class="form-control tap-input"
                           id="nisnInput" name="nisn"
                           inputmode="numeric" maxlength="10" pattern="\d{10}"
                           placeholder="0061842301"
                           autocomplete="off" autocapitalize="off" spellcheck="false"
                           aria-describedby="tapHelp"
                           required autofocus>

                    <div class="tap-error" id="tapError" role="alert" hidden></div>

                    <button type="submit" class="btn btn-primary btn-lg w-100 mt-3" id="tapSubmit">
                        <span class="tap-submit-label">
                            <i class="bi bi-upc-scan" aria-hidden="true"></i>
                            Simulasikan Tap
                        </span>
                        <span class="tap-submit-loading d-none">
                            <span class="spinner-border spinner-border-sm" aria-hidden="true"></span>
                            Memproses&hellip;
                        </span>
                    </button>

                    <p class="form-text text-center mt-2 mb-0" id="tapHelp">
                        Simulasi ini menggantikan pembacaan kartu RFID pada perangkat nyata.
                    </p>
                </form>

                <div class="tap-result-area" id="tapResult" aria-live="polite">
                    <?php if ($hasil !== null): ?>
                        <?php require __DIR__ . '/_result.php'; ?>
                    <?php else: ?>
                        <div class="tap-result-placeholder">
                            <i class="bi bi-card-checklist" aria-hidden="true"></i>
                            <p>Hasil presensi terakhir akan muncul di sini.</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-5">
        <div class="card app-card mb-4">
            <div class="card-body">
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <div>
                        <h3 class="app-card-title mb-0">Kehadiran Hari Ini</h3>
                        <p class="app-card-subtitle mb-0"><?= e(format_tanggal(date('Y-m-d'), true)) ?></p>
                    </div>
                    <span class="badge rounded-pill text-bg-light"><?= e((string) $persentase) ?>%</span>
                </div>

                <div class="d-flex align-items-baseline gap-2">
                    <span class="fs-3 fw-bold text-primary"><?= e((string) $statistik['hadir']) ?></span>
                    <span class="text-muted">dari <?= e((string) $statistik['total']) ?> siswa</span>
                </div>

                <div class="progress app-progress mt-3" role="progressbar"
                     aria-label="Kehadiran hari ini" aria-valuenow="<?= e((string) $persentase) ?>"
                     aria-valuemin="0" aria-valuemax="100">
                    <div class="progress-bar" style="width: <?= e((string) $persentase) ?>%"></div>
                </div>
            </div>
        </div>

        <div class="card app-card">
            <div class="card-header app-card-header">
                <div>
                    <h3 class="app-card-title">Riwayat Tap Terbaru</h3>
                    <p class="app-card-subtitle">Maksimal delapan tap terakhir hari ini.</p>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table app-table app-table-compact align-middle mb-0">
                    <thead>
                        <tr>
                            <th scope="col">Siswa</th>
                            <th scope="col" class="text-end">Waktu</th>
                        </tr>
                    </thead>
                    <tbody id="tapHistoryBody">
                        <?php if ($riwayat === []): ?>
                            <tr id="tapHistoryEmpty">
                                <td colspan="2">
                                    <div class="empty-state empty-state--sm">
                                        <i class="bi bi-clock-history" aria-hidden="true"></i>
                                        <p>Belum ada tap hari ini.</p>
                                    </div>
                                </td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($riwayat as $item): ?>
                                <tr>
                                    <td>
                                        <div class="person-cell">
                                            <?= avatar_html(isset($item['foto']) ? (string) $item['foto'] : null, (string) $item['nama'], 'avatar-sm') ?>
                                            <div class="min-w-0">
                                                <div class="person-name text-truncate"><?= e($item['nama']) ?></div>
                                                <div class="person-meta"><?= e($item['nama_kelas']) ?></div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="text-end">
                                        <span class="fw-semibold small"><?= e(format_jam((string) $item['tanggal_waktu'], false)) ?></span>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<template id="tapResultTemplate">
    <div class="tap-result">
        <span class="tap-result-icon"><i class="bi bi-check-circle-fill" aria-hidden="true"></i></span>
        <div class="tap-result-body">
            <div class="tap-result-title"></div>
            <p class="tap-result-message"></p>
            <div class="tap-result-person d-none">
                <span class="avatar avatar-lg avatar-initials" data-field="avatar">?</span>
                <div class="min-w-0">
                    <div class="fw-semibold" data-field="nama"></div>
                    <div class="text-muted small" data-field="meta"></div>
                    <div class="tap-result-time" data-field="waktu"></div>
                </div>
            </div>
        </div>
    </div>
</template>

<?php require __DIR__ . '/../layouts/footer.php'; ?>
