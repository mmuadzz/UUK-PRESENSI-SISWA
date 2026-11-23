<?php

declare(strict_types=1);

/** @var string $mode */
/** @var array<string, string> $errors */
/** @var array<string, mixed> $old */
/** @var string|null $foto_lama */
/** @var array<int, array<string, mixed>> $daftar_kelas */
/** @var string|null $nisn_lama */

$isEdit = $mode === 'edit';
$nisn_lama = $nisn_lama ?? '';
$formAction = $isEdit
    ? base_url('siswa/edit.php?nisn=' . urlencode($nisn_lama))
    : base_url('siswa/create.php');

require __DIR__ . '/../layouts/header.php';
?>

<div class="page-head">
    <div>
        <a class="back-link" href="<?= e(base_url('siswa/index.php')) ?>">
            <i class="bi bi-arrow-left" aria-hidden="true"></i>
            Kembali ke Data Siswa
        </a>
        <h2 class="page-head-title"><?= $isEdit ? 'Ubah Data Siswa' : 'Tambah Siswa' ?></h2>
        <p class="page-head-text">
            <?= $isEdit
                ? 'Perbarui identitas siswa. NISN dapat diubah selama belum digunakan siswa lain.'
                : 'Lengkapi identitas siswa. Foto bersifat opsional dan dapat ditambahkan nanti.' ?>
        </p>
    </div>
</div>

<form method="post" action="<?= e($formAction) ?>" enctype="multipart/form-data" class="row g-4" novalidate>
    <?= csrf_field() ?>

    <div class="col-lg-8">
        <div class="card app-card h-100">
            <div class="card-header app-card-header">
                <div>
                    <h3 class="app-card-title">Identitas Siswa</h3>
                    <p class="app-card-subtitle">Field bertanda <span class="text-danger">*</span> wajib diisi.</p>
                </div>
            </div>

            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-5">
                        <label for="nisn" class="form-label">NISN <span class="text-danger">*</span></label>
                        <input type="text"
                               class="form-control font-monospace<?= isset($errors['nisn']) ? ' is-invalid' : '' ?>"
                               id="nisn" name="nisn" value="<?= e($old['nisn'] ?? '') ?>"
                               inputmode="numeric" maxlength="10" pattern="\d{10}"
                               placeholder="10 digit angka" required <?= $isEdit ? '' : 'autofocus' ?>>
                        <?php if (isset($errors['nisn'])): ?>
                            <div class="invalid-feedback"><?= e($errors['nisn']) ?></div>
                        <?php else: ?>
                            <div class="form-text">Tepat 10 digit angka dan tidak boleh duplikat.</div>
                        <?php endif; ?>
                    </div>

                    <div class="col-md-7">
                        <label for="nama" class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                        <input type="text"
                               class="form-control<?= isset($errors['nama']) ? ' is-invalid' : '' ?>"
                               id="nama" name="nama" value="<?= e($old['nama'] ?? '') ?>"
                               maxlength="120" placeholder="Nama lengkap siswa" required
                               <?= $isEdit ? 'autofocus' : '' ?>>
                        <?php if (isset($errors['nama'])): ?>
                            <div class="invalid-feedback"><?= e($errors['nama']) ?></div>
                        <?php endif; ?>
                    </div>

                    <div class="col-12">
                        <label for="id_kelas" class="form-label">Kelas <span class="text-danger">*</span></label>
                        <select class="form-select<?= isset($errors['id_kelas']) ? ' is-invalid' : '' ?>"
                                id="id_kelas" name="id_kelas" required
                                <?= $daftar_kelas === [] ? 'disabled' : '' ?>>
                            <option value="">Pilih kelas</option>
                            <?php foreach ($daftar_kelas as $kelas): ?>
                                <option value="<?= e((string) $kelas['id']) ?>"
                                        <?= (string) $kelas['id'] === (string) ($old['id_kelas'] ?? '') ? 'selected' : '' ?>>
                                    <?= e($kelas['nama_kelas']) ?> &mdash; <?= e($kelas['jurusan']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <?php if (isset($errors['id_kelas'])): ?>
                            <div class="invalid-feedback"><?= e($errors['id_kelas']) ?></div>
                        <?php endif; ?>
                        <?php if ($daftar_kelas === []): ?>
                            <div class="form-text text-danger">
                                Belum ada kelas tersedia.
                                <a href="<?= e(base_url('kelas/index.php')) ?>">Tambahkan kelas terlebih dahulu</a>.
                            </div>
                        <?php endif; ?>
                    </div>

                    <div class="col-12">
                        <label for="alamat" class="form-label">Alamat</label>
                        <textarea class="form-control<?= isset($errors['alamat']) ? ' is-invalid' : '' ?>"
                                  id="alamat" name="alamat" rows="3" maxlength="1000"
                                  placeholder="Alamat tempat tinggal siswa"><?= e($old['alamat'] ?? '') ?></textarea>
                        <?php if (isset($errors['alamat'])): ?>
                            <div class="invalid-feedback"><?= e($errors['alamat']) ?></div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <div class="card-footer app-form-actions">
                <a href="<?= e(base_url('siswa/index.php')) ?>" class="btn btn-light">Batal</a>
                <button type="submit" class="btn btn-primary" <?= $daftar_kelas === [] ? 'disabled' : '' ?>>
                    <i class="bi bi-check2 me-1" aria-hidden="true"></i>
                    <?= $isEdit ? 'Simpan Perubahan' : 'Simpan Siswa' ?>
                </button>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card app-card h-100">
            <div class="card-header app-card-header">
                <div>
                    <h3 class="app-card-title">Foto Siswa</h3>
                    <p class="app-card-subtitle">Opsional, maksimal 2 MB.</p>
                </div>
            </div>
            <div class="card-body">
                <div class="photo-preview" id="photoPreview">
                    <?php if ($foto_lama !== null && $foto_lama !== ''): ?>
                        <img src="<?= e((string) foto_url($foto_lama)) ?>" alt="Foto <?= e($old['nama'] ?? 'siswa') ?>">
                    <?php else: ?>
                        <span class="photo-placeholder"><i class="bi bi-person-bounding-box" aria-hidden="true"></i></span>
                    <?php endif; ?>
                </div>

                <label for="fotoInput" class="form-label mt-3">Pilih foto</label>
                <input type="file"
                       class="form-control<?= isset($errors['foto']) ? ' is-invalid' : '' ?>"
                       id="fotoInput" name="foto"
                       accept=".jpg,.jpeg,.png,image/jpeg,image/png">
                <?php if (isset($errors['foto'])): ?>
                    <div class="invalid-feedback d-block"><?= e($errors['foto']) ?></div>
                <?php endif; ?>
                <div class="form-text" id="fotoHelp">
                    Format JPG, JPEG, atau PNG. Nama file disimpan secara acak.
                </div>
                <div class="text-danger small mt-1" id="fotoError" role="alert"></div>

                <?php if ($isEdit && $foto_lama !== null && $foto_lama !== ''): ?>
                    <p class="text-muted small mt-3 mb-0">
                        Pilih file baru untuk mengganti foto saat ini. Foto lama akan dihapus setelah data tersimpan.
                    </p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</form>

<?php require __DIR__ . '/../layouts/footer.php'; ?>
