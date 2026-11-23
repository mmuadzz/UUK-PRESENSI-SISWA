<?php

declare(strict_types=1);

/** @var array<int, array<string, mixed>> $daftar */
/** @var int $total */
/** @var int $page */
/** @var int $total_pages */
/** @var int $per_page */
/** @var string $q */
/** @var int|null $kelas_id */
/** @var array<int, array<string, mixed>> $daftar_kelas */

require __DIR__ . '/../layouts/header.php';

$start = $total > 0 ? (($page - 1) * $per_page) + 1 : 0;
$end = min($total, $page * $per_page);
$firstPage = max(1, $page - 2);
$lastPage = min($total_pages, $page + 2);
$adaFilter = $q !== '' || $kelas_id !== null;
?>

<div class="page-head">
    <div>
        <h2 class="page-head-title">Data Siswa</h2>
        <p class="page-head-text">Kelola identitas siswa, kelas, dan foto untuk kebutuhan presensi.</p>
    </div>
    <a href="<?= e(base_url('siswa/create.php')) ?>" class="btn btn-primary">
        <i class="bi bi-person-plus me-1" aria-hidden="true"></i>
        Tambah Siswa
    </a>
</div>

<div class="card app-card">
    <div class="card-body pb-3">
        <form method="get" action="<?= e(base_url('siswa/index.php')) ?>" class="row g-3 align-items-end">
            <div class="col-md-5">
                <label for="q" class="form-label">Cari siswa</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="bi bi-search" aria-hidden="true"></i></span>
                    <input type="search" class="form-control" id="q" name="q"
                           value="<?= e($q) ?>" placeholder="NISN atau nama siswa" maxlength="100">
                </div>
            </div>

            <div class="col-md-4">
                <label for="filterKelas" class="form-label">Kelas</label>
                <select class="form-select" id="filterKelas" name="kelas">
                    <option value="">Semua kelas</option>
                    <?php foreach ($daftar_kelas as $kelas): ?>
                        <option value="<?= e((string) $kelas['id']) ?>"
                                <?= $kelas_id === (int) $kelas['id'] ? 'selected' : '' ?>>
                            <?= e($kelas['nama_kelas']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="col-md-3 d-flex gap-2">
                <button type="submit" class="btn btn-primary flex-fill">
                    <i class="bi bi-funnel me-1" aria-hidden="true"></i>
                    Terapkan
                </button>
                <?php if ($adaFilter): ?>
                    <a href="<?= e(base_url('siswa/index.php')) ?>" class="btn btn-outline-secondary" title="Reset filter">
                        <i class="bi bi-arrow-counterclockwise" aria-hidden="true"></i>
                    </a>
                <?php endif; ?>
            </div>
        </form>
    </div>

    <div class="table-responsive">
        <table class="table app-table align-middle mb-0">
            <thead>
                <tr>
                    <th scope="col" style="width: 64px;">Foto</th>
                    <th scope="col">NISN</th>
                    <th scope="col">Nama</th>
                    <th scope="col">Kelas</th>
                    <th scope="col" class="d-none d-xl-table-cell">Alamat</th>
                    <th scope="col" class="text-end" style="width: 120px;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($daftar === []): ?>
                    <tr>
                        <td colspan="6">
                            <div class="empty-state">
                                <i class="bi bi-person-x" aria-hidden="true"></i>
                                <?php if ($adaFilter): ?>
                                    <p>Tidak ada siswa yang cocok dengan pencarian atau filter kelas.</p>
                                    <a href="<?= e(base_url('siswa/index.php')) ?>" class="btn btn-sm btn-outline-primary">
                                        Reset Filter
                                    </a>
                                <?php else: ?>
                                    <p>Belum ada data siswa.</p>
                                    <a href="<?= e(base_url('siswa/create.php')) ?>" class="btn btn-sm btn-primary">
                                        Tambah Siswa Pertama
                                    </a>
                                <?php endif; ?>
                            </div>
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($daftar as $siswa): ?>
                        <tr>
                            <td>
                                <?= avatar_html(isset($siswa['foto']) ? (string) $siswa['foto'] : null, (string) $siswa['nama'], 'avatar-sm') ?>
                            </td>
                            <td class="font-monospace small"><?= e($siswa['nisn']) ?></td>
                            <td>
                                <div class="fw-semibold"><?= e($siswa['nama']) ?></div>
                            </td>
                            <td><span class="class-chip"><?= e($siswa['nama_kelas']) ?></span></td>
                            <td class="d-none d-xl-table-cell text-muted small address-cell">
                                <?= e($siswa['alamat'] !== '' ? $siswa['alamat'] : '-') ?>
                            </td>
                            <td class="text-end">
                                <div class="d-inline-flex gap-1">
                                    <a href="<?= e(base_url('siswa/edit.php?nisn=' . urlencode((string) $siswa['nisn']))) ?>"
                                       class="btn btn-icon btn-soft-primary" title="Ubah data siswa"
                                       aria-label="Ubah data <?= e($siswa['nama']) ?>">
                                        <i class="bi bi-pencil-square" aria-hidden="true"></i>
                                    </a>

                                    <form method="post" action="<?= e(base_url('siswa/delete.php')) ?>"
                                          class="d-inline"
                                          data-confirm="Hapus data siswa &quot;<?= e($siswa['nama']) ?>&quot;? Riwayat presensinya juga akan terhapus.">
                                        <?= csrf_field() ?>
                                        <input type="hidden" name="nisn" value="<?= e($siswa['nisn']) ?>">
                                        <button type="submit" class="btn btn-icon btn-soft-danger"
                                                title="Hapus siswa" aria-label="Hapus data <?= e($siswa['nama']) ?>">
                                            <i class="bi bi-trash3" aria-hidden="true"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <div class="card-footer app-card-footer">
        <div class="small text-muted">
            <?php if ($total > 0): ?>
                Menampilkan <?= e((string) $start) ?>&ndash;<?= e((string) $end) ?> dari <?= e((string) $total) ?> siswa
            <?php else: ?>
                Tidak ada data untuk ditampilkan
            <?php endif; ?>
        </div>

        <?php if ($total_pages > 1): ?>
            <nav aria-label="Navigasi halaman data siswa">
                <ul class="pagination pagination-sm mb-0">
                    <li class="page-item<?= $page <= 1 ? ' disabled' : '' ?>">
                        <a class="page-link" href="?<?= e(query_string(['page' => max(1, $page - 1)])) ?>"
                           aria-label="Halaman sebelumnya">
                            <i class="bi bi-chevron-left" aria-hidden="true"></i>
                        </a>
                    </li>

                    <?php if ($firstPage > 1): ?>
                        <li class="page-item">
                            <a class="page-link" href="?<?= e(query_string(['page' => 1])) ?>">1</a>
                        </li>
                        <?php if ($firstPage > 2): ?>
                            <li class="page-item disabled"><span class="page-link">&hellip;</span></li>
                        <?php endif; ?>
                    <?php endif; ?>

                    <?php for ($pageNumber = $firstPage; $pageNumber <= $lastPage; $pageNumber++): ?>
                        <li class="page-item<?= $pageNumber === $page ? ' active' : '' ?>">
                            <a class="page-link" href="?<?= e(query_string(['page' => $pageNumber])) ?>"
                               <?= $pageNumber === $page ? 'aria-current="page"' : '' ?>>
                                <?= e((string) $pageNumber) ?>
                            </a>
                        </li>
                    <?php endfor; ?>

                    <?php if ($lastPage < $total_pages): ?>
                        <?php if ($lastPage < $total_pages - 1): ?>
                            <li class="page-item disabled"><span class="page-link">&hellip;</span></li>
                        <?php endif; ?>
                        <li class="page-item">
                            <a class="page-link" href="?<?= e(query_string(['page' => $total_pages])) ?>">
                                <?= e((string) $total_pages) ?>
                            </a>
                        </li>
                    <?php endif; ?>

                    <li class="page-item<?= $page >= $total_pages ? ' disabled' : '' ?>">
                        <a class="page-link" href="?<?= e(query_string(['page' => min($total_pages, $page + 1)])) ?>"
                           aria-label="Halaman berikutnya">
                            <i class="bi bi-chevron-right" aria-hidden="true"></i>
                        </a>
                    </li>
                </ul>
            </nav>
        <?php endif; ?>
    </div>
</div>

<?php require __DIR__ . '/../layouts/footer.php'; ?>
