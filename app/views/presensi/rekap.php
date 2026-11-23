<?php

declare(strict_types=1);

/** @var string $tanggal */
/** @var int|null $kelas_id */
/** @var array<int, array<string, mixed>> $daftar_kelas */
/** @var array<int, array<string, mixed>> $rows */
/** @var int $hadir */
/** @var int $tidak_hadir */
/** @var int $total */

require __DIR__ . '/../layouts/header.php';
?>

<div class="page-head">
    <div>
        <h2 class="page-head-title">Rekap Presensi</h2>
        <p class="page-head-text">
            Pilih tanggal dan kelas untuk melihat status kehadiran seluruh siswa.
        </p>
    </div>
</div>

<div class="card app-card mb-4">
    <div class="card-body">
        <form method="get" action="<?= e(base_url('presensi/rekap.php')) ?>" class="row g-3 align-items-end">
            <div class="col-md-4">
                <label for="tanggal" class="form-label">Tanggal</label>
                <input type="date" class="form-control" id="tanggal" name="tanggal"
                       value="<?= e($tanggal) ?>" required>
            </div>

            <div class="col-md-4">
                <label for="kelas" class="form-label">Kelas</label>
                <select class="form-select" id="kelas" name="kelas">
                    <option value="">Semua kelas</option>
                    <?php foreach ($daftar_kelas as $kelas): ?>
                        <option value="<?= e((string) $kelas['id']) ?>"
                                <?= $kelas_id === (int) $kelas['id'] ? 'selected' : '' ?>>
                            <?= e($kelas['nama_kelas']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="col-md-4 d-flex gap-2">
                <button type="submit" class="btn btn-primary flex-fill">
                    <i class="bi bi-funnel me-1" aria-hidden="true"></i>
                    Terapkan Filter
                </button>
                <?php if ($kelas_id !== null || $tanggal !== date('Y-m-d')): ?>
                    <a href="<?= e(base_url('presensi/rekap.php')) ?>" class="btn btn-outline-secondary"
                       title="Kembali ke hari ini">
                        <i class="bi bi-arrow-counterclockwise" aria-hidden="true"></i>
                    </a>
                <?php endif; ?>
            </div>
        </form>
    </div>
</div>

<div class="stat-grid stat-grid--three mb-4">
    <div class="stat-card">
        <span class="stat-icon stat-icon--blue"><i class="bi bi-people" aria-hidden="true"></i></span>
        <div>
            <div class="stat-label">Total Siswa</div>
            <div class="stat-value"><?= e((string) $total) ?></div>
            <div class="stat-hint">Sesuai filter</div>
        </div>
    </div>

    <div class="stat-card">
        <span class="stat-icon stat-icon--green"><i class="bi bi-check2-circle" aria-hidden="true"></i></span>
        <div>
            <div class="stat-label">Hadir</div>
            <div class="stat-value"><?= e((string) $hadir) ?></div>
            <div class="stat-hint">Memiliki catatan presensi</div>
        </div>
    </div>

    <div class="stat-card">
        <span class="stat-icon stat-icon--red"><i class="bi bi-x-circle" aria-hidden="true"></i></span>
        <div>
            <div class="stat-label">Tidak Hadir</div>
            <div class="stat-value"><?= e((string) $tidak_hadir) ?></div>
            <div class="stat-hint">Belum ada catatan presensi</div>
        </div>
    </div>
</div>

<div class="card app-card">
    <div class="card-header app-card-header">
        <div>
            <h3 class="app-card-title">Daftar Kehadiran</h3>
            <p class="app-card-subtitle">
                <?= e(format_tanggal($tanggal, true)) ?>
                <?php if ($kelas_id !== null): ?>
                    &middot;
                    <?php foreach ($daftar_kelas as $kelas): ?>
                        <?php if ((int) $kelas['id'] === $kelas_id): ?>
                            <?= e($kelas['nama_kelas']) ?>
                        <?php endif; ?>
                    <?php endforeach; ?>
                <?php else: ?>
                    &middot; Semua kelas
                <?php endif; ?>
            </p>
        </div>
        <span class="count-chip"><?= e((string) $hadir) ?> hadir / <?= e((string) $total) ?> siswa</span>
    </div>

    <div class="table-responsive">
        <table class="table app-table align-middle mb-0">
            <thead>
                <tr>
                    <th scope="col" style="width: 60px;">No</th>
                    <th scope="col">Siswa</th>
                    <th scope="col">Kelas</th>
                    <th scope="col">Waktu Presensi</th>
                    <th scope="col" class="text-end">Status</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($rows === []): ?>
                    <tr>
                        <td colspan="5">
                            <div class="empty-state">
                                <i class="bi bi-inbox" aria-hidden="true"></i>
                                <p>Belum ada data siswa untuk filter ini.</p>
                                <a href="<?= e(base_url('siswa/create.php')) ?>" class="btn btn-sm btn-primary">
                                    Tambah Siswa
                                </a>
                            </div>
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($rows as $index => $row): ?>
                        <?php $isHadir = $row['tanggal_waktu'] !== null; ?>
                        <tr>
                            <td class="text-muted"><?= e((string) ($index + 1)) ?></td>
                            <td>
                                <div class="person-cell">
                                    <?= avatar_html(isset($row['foto']) ? (string) $row['foto'] : null, (string) $row['nama'], 'avatar-sm') ?>
                                    <div class="min-w-0">
                                        <div class="person-name text-truncate"><?= e($row['nama']) ?></div>
                                        <div class="person-meta font-monospace"><?= e($row['nisn']) ?></div>
                                    </div>
                                </div>
                            </td>
                            <td><span class="class-chip"><?= e($row['nama_kelas']) ?></span></td>
                            <td>
                                <?php if ($isHadir): ?>
                                    <span class="fw-semibold small"><?= e(format_jam((string) $row['tanggal_waktu'])) ?></span>
                                <?php else: ?>
                                    <span class="text-muted">&mdash;</span>
                                <?php endif; ?>
                            </td>
                            <td class="text-end">
                                <?php if ($isHadir): ?>
                                    <span class="status-badge status-badge--hadir">
                                        <i class="bi bi-check-lg" aria-hidden="true"></i> Hadir
                                    </span>
                                <?php else: ?>
                                    <span class="status-badge status-badge--absen">
                                        <i class="bi bi-dash-lg" aria-hidden="true"></i> Tidak Hadir
                                    </span>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <div class="card-footer app-card-footer">
        <p class="small text-muted mb-0">
            Status <strong>Hadir</strong> dihitung dari catatan presensi pada tanggal terpilih.
            <strong>Tidak Hadir</strong> berarti belum ada catatan presensi.
        </p>
    </div>
</div>

<?php require __DIR__ . '/../layouts/footer.php'; ?>
