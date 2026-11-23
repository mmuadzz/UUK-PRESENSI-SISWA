<?php

declare(strict_types=1);

/** @var array{total_siswa:int,total_kelas:int,hadir:int,belum:int} $statistik */
/** @var int $persentaseHadir */
/** @var array<int, array<string, mixed>> $terbaru */
/** @var string $tanggalHariIni */

$currentUser = auth_user();
$namaAdmin = (string) ($currentUser['nama_lengkap'] ?? 'Administrator');
$namaDepan = explode(' ', trim($namaAdmin))[0] ?: 'Administrator';

require __DIR__ . '/../layouts/header.php';
?>

<div class="page-intro dashboard-intro">
    <div class="min-w-0">
        <div class="page-eyebrow">Ringkasan hari ini</div>
        <h2 class="page-intro-title">Selamat datang, <?= e($namaDepan) ?></h2>
        <p class="page-intro-text">
            Pantau kehadiran siswa, kelola data, dan siapkan rekap presensi dari satu tempat.
        </p>
    </div>
    <div class="intro-clock">
        <span class="intro-clock-icon"><i class="bi bi-clock-history" aria-hidden="true"></i></span>
        <div>
            <div class="intro-clock-time" id="liveClock"><?= e(date('H:i:s')) ?> WITA</div>
            <div class="intro-clock-date" id="liveDate"><?= e($tanggalHariIni) ?></div>
        </div>
    </div>
</div>

<div class="stat-grid">
    <div class="stat-card">
        <span class="stat-icon stat-icon--blue"><i class="bi bi-people" aria-hidden="true"></i></span>
        <div>
            <div class="stat-label">Total Siswa</div>
            <div class="stat-value"><?= e((string) $statistik['total_siswa']) ?></div>
            <div class="stat-hint">Siswa terdaftar</div>
        </div>
    </div>

    <div class="stat-card">
        <span class="stat-icon stat-icon--indigo"><i class="bi bi-buildings" aria-hidden="true"></i></span>
        <div>
            <div class="stat-label">Total Kelas</div>
            <div class="stat-value"><?= e((string) $statistik['total_kelas']) ?></div>
            <div class="stat-hint">Kelas aktif</div>
        </div>
    </div>

    <div class="stat-card">
        <span class="stat-icon stat-icon--green"><i class="bi bi-check2-circle" aria-hidden="true"></i></span>
        <div>
            <div class="stat-label">Hadir Hari Ini</div>
            <div class="stat-value"><?= e((string) $statistik['hadir']) ?></div>
            <div class="stat-hint"><?= e((string) $persentaseHadir) ?>% dari total siswa</div>
        </div>
    </div>

    <div class="stat-card">
        <span class="stat-icon stat-icon--amber"><i class="bi bi-hourglass-split" aria-hidden="true"></i></span>
        <div>
            <div class="stat-label">Belum Presensi</div>
            <div class="stat-value"><?= e((string) $statistik['belum']) ?></div>
            <div class="stat-hint">Perlu ditindaklanjuti</div>
        </div>
    </div>
</div>

<div class="row g-4 mt-1">
    <div class="col-lg-8">
        <div class="card app-card h-100">
            <div class="card-header app-card-header">
                <div>
                    <h2 class="app-card-title">Presensi Terakhir</h2>
                    <p class="app-card-subtitle">Catatan presensi paling baru hari ini.</p>
                </div>
                <a class="btn btn-sm btn-outline-primary" href="<?= e(base_url('presensi/rekap.php')) ?>">
                    Lihat Rekap
                    <i class="bi bi-arrow-right ms-1" aria-hidden="true"></i>
                </a>
            </div>

            <div class="table-responsive">
                <table class="table app-table align-middle mb-0">
                    <thead>
                        <tr>
                            <th scope="col">Siswa</th>
                            <th scope="col">Kelas</th>
                            <th scope="col" class="text-end">Waktu</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($terbaru === []): ?>
                            <tr>
                                <td colspan="3">
                                    <div class="empty-state">
                                        <i class="bi bi-clipboard-x" aria-hidden="true"></i>
                                        <p>Belum ada catatan presensi.</p>
                                        <a class="btn btn-sm btn-primary" href="<?= e(base_url('presensi/simulasi.php')) ?>">
                                            Mulai Simulasi Tap
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($terbaru as $item): ?>
                                <tr>
                                    <td>
                                        <div class="person-cell">
                                            <?= avatar_html(isset($item['foto']) ? (string) $item['foto'] : null, (string) $item['nama'], 'avatar-sm') ?>
                                            <div class="min-w-0">
                                                <div class="person-name text-truncate"><?= e($item['nama']) ?></div>
                                                <div class="person-meta"><?= e($item['nisn']) ?></div>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="class-chip"><?= e($item['nama_kelas']) ?></span>
                                    </td>
                                    <td class="text-end">
                                        <div class="fw-semibold small"><?= e(format_jam((string) $item['tanggal_waktu'])) ?></div>
                                        <div class="text-muted" style="font-size: .75rem;">
                                            <?= e(format_tanggal((string) $item['tanggal_waktu'])) ?>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card app-card h-100">
            <div class="card-header app-card-header">
                <div>
                    <h2 class="app-card-title">Kehadiran Hari Ini</h2>
                    <p class="app-card-subtitle"><?= e($tanggalHariIni) ?></p>
                </div>
            </div>
            <div class="card-body">
                <div class="d-flex align-items-end justify-content-between mb-2">
                    <span class="text-muted small">Persentase kehadiran</span>
                    <span class="fs-4 fw-bold text-primary"><?= e((string) $persentaseHadir) ?>%</span>
                </div>
                <div class="progress app-progress mb-4" role="progressbar"
                     aria-label="Persentase kehadiran" aria-valuenow="<?= e((string) $persentaseHadir) ?>"
                     aria-valuemin="0" aria-valuemax="100">
                    <div class="progress-bar" style="width: <?= e((string) $persentaseHadir) ?>%"></div>
                </div>

                <div class="summary-row">
                    <span class="summary-dot summary-dot--green"></span>
                    <span>Hadir</span>
                    <strong class="ms-auto"><?= e((string) $statistik['hadir']) ?> siswa</strong>
                </div>
                <div class="summary-row">
                    <span class="summary-dot summary-dot--amber"></span>
                    <span>Belum presensi</span>
                    <strong class="ms-auto"><?= e((string) $statistik['belum']) ?> siswa</strong>
                </div>
                <div class="summary-row">
                    <span class="summary-dot summary-dot--blue"></span>
                    <span>Total siswa</span>
                    <strong class="ms-auto"><?= e((string) $statistik['total_siswa']) ?> siswa</strong>
                </div>

                <hr class="my-4">

                <div class="quick-actions">
                    <a href="<?= e(base_url('presensi/simulasi.php')) ?>" class="quick-action">
                        <span class="quick-action-icon"><i class="bi bi-upc-scan" aria-hidden="true"></i></span>
                        <span>
                            <span class="quick-action-title">Simulasi Tap</span>
                            <span class="quick-action-text">Catat presensi dengan NISN</span>
                        </span>
                        <i class="bi bi-chevron-right ms-auto" aria-hidden="true"></i>
                    </a>
                    <a href="<?= e(base_url('siswa/create.php')) ?>" class="quick-action">
                        <span class="quick-action-icon"><i class="bi bi-person-plus" aria-hidden="true"></i></span>
                        <span>
                            <span class="quick-action-title">Tambah Siswa</span>
                            <span class="quick-action-text">Lengkapi data siswa baru</span>
                        </span>
                        <i class="bi bi-chevron-right ms-auto" aria-hidden="true"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require __DIR__ . '/../layouts/footer.php'; ?>
