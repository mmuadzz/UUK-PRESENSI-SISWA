<?php

declare(strict_types=1);

/** @var array<int, array<string, mixed>> $daftar */
/** @var array<string, string> $errors */
/** @var array<string, mixed> $old */
/** @var string|null $modal */

require __DIR__ . '/../layouts/header.php';
?>

<div class="page-head">
    <div>
        <h2 class="page-head-title">Data Kelas</h2>
        <p class="page-head-text">Kelola daftar kelas dan jurusan yang digunakan oleh data siswa.</p>
    </div>
    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalKelas">
        <i class="bi bi-plus-lg me-1" aria-hidden="true"></i>
        Tambah Kelas
    </button>
</div>

<div class="card app-card">
    <div class="card-header app-card-header">
        <div>
            <h3 class="app-card-title">Daftar Kelas</h3>
            <p class="app-card-subtitle"><?= e((string) count($daftar)) ?> kelas terdaftar.</p>
        </div>
    </div>

    <div class="table-responsive">
        <table class="table app-table align-middle mb-0">
            <thead>
                <tr>
                    <th scope="col" style="width: 60px;">No</th>
                    <th scope="col">Nama Kelas</th>
                    <th scope="col">Jurusan</th>
                    <th scope="col">Siswa</th>
                    <th scope="col" class="text-end" style="width: 130px;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($daftar === []): ?>
                    <tr>
                        <td colspan="5">
                            <div class="empty-state">
                                <i class="bi bi-buildings" aria-hidden="true"></i>
                                <p>Belum ada data kelas.</p>
                                <button type="button" class="btn btn-sm btn-primary"
                                        data-bs-toggle="modal" data-bs-target="#modalKelas">
                                    Tambah Kelas Pertama
                                </button>
                            </div>
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($daftar as $index => $kelas): ?>
                        <tr>
                            <td class="text-muted"><?= e((string) ($index + 1)) ?></td>
                            <td>
                                <div class="fw-semibold"><?= e($kelas['nama_kelas']) ?></div>
                            </td>
                            <td class="text-muted"><?= e($kelas['jurusan']) ?></td>
                            <td>
                                <span class="count-chip">
                                    <?= e((string) $kelas['jumlah_siswa']) ?> siswa
                                </span>
                            </td>
                            <td class="text-end">
                                <div class="d-inline-flex gap-1">
                                    <button type="button" class="btn btn-icon btn-soft-primary"
                                            title="Ubah kelas" aria-label="Ubah kelas <?= e($kelas['nama_kelas']) ?>"
                                            data-bs-toggle="modal" data-bs-target="#modalKelas"
                                            data-id="<?= e((string) $kelas['id']) ?>"
                                            data-nama="<?= e($kelas['nama_kelas']) ?>"
                                            data-jurusan="<?= e($kelas['jurusan']) ?>">
                                        <i class="bi bi-pencil-square" aria-hidden="true"></i>
                                    </button>

                                    <form method="post" action="<?= e(base_url('kelas/delete.php')) ?>"
                                          class="d-inline"
                                          data-confirm="Hapus kelas &quot;<?= e($kelas['nama_kelas']) ?>&quot;? Tindakan ini tidak dapat dibatalkan.">
                                        <?= csrf_field() ?>
                                        <input type="hidden" name="id" value="<?= e((string) $kelas['id']) ?>">
                                        <button type="submit" class="btn btn-icon btn-soft-danger"
                                                title="Hapus kelas" aria-label="Hapus kelas <?= e($kelas['nama_kelas']) ?>">
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
</div>

<!-- Modal tambah/ubah kelas -->
<div class="modal fade" id="modalKelas" tabindex="-1" aria-labelledby="modalKelasLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content app-modal">
            <form method="post" action="<?= e(base_url('kelas/store.php')) ?>" id="formKelas" novalidate>
                <?= csrf_field() ?>
                <div class="modal-header">
                    <div>
                        <h2 class="modal-title fs-5" id="modalKelasLabel">Tambah Kelas</h2>
                        <p class="modal-subtitle">Nama kelas harus unik dan jurusan wajib diisi.</p>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                </div>

                <div class="modal-body">
                    <?php if ($errors !== []): ?>
                        <div class="alert alert-danger py-2 px-3 small" id="kelasFormErrors" role="alert">
                            Periksa kembali isian pada form.
                        </div>
                    <?php endif; ?>

                    <div class="mb-3 d-none" id="kelasIdGroup">
                        <label for="kelasId" class="form-label">ID Kelas</label>
                        <input type="number" class="form-control" id="kelasId" name="id" readonly>
                    </div>

                    <div class="mb-3">
                        <label for="kelasNama" class="form-label">Nama Kelas <span class="text-danger">*</span></label>
                        <input type="text"
                               class="form-control<?= isset($errors['nama_kelas']) ? ' is-invalid' : '' ?>"
                               id="kelasNama" name="nama_kelas" maxlength="50"
                               placeholder="Contoh: X RPL 1" required>
                        <div class="invalid-feedback"><?= e($errors['nama_kelas'] ?? '') ?></div>
                    </div>

                    <div class="mb-1">
                        <label for="kelasJurusan" class="form-label">Jurusan <span class="text-danger">*</span></label>
                        <input type="text"
                               class="form-control<?= isset($errors['jurusan']) ? ' is-invalid' : '' ?>"
                               id="kelasJurusan" name="jurusan" maxlength="100"
                               placeholder="Contoh: Rekayasa Perangkat Lunak" required>
                        <div class="invalid-feedback"><?= e($errors['jurusan'] ?? '') ?></div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary" id="kelasSubmitLabel">Simpan Kelas</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const modalElement = document.getElementById('modalKelas');
    if (!modalElement) {
        return;
    }

    const form = document.getElementById('formKelas');
    const title = document.getElementById('modalKelasLabel');
    const idGroup = document.getElementById('kelasIdGroup');
    const idInput = document.getElementById('kelasId');
    const namaInput = document.getElementById('kelasNama');
    const jurusanInput = document.getElementById('kelasJurusan');
    const submitLabel = document.getElementById('kelasSubmitLabel');
    const errorBox = document.getElementById('kelasFormErrors');

    const storeUrl = <?= json_encode(base_url('kelas/store.php'), JSON_UNESCAPED_SLASHES | JSON_HEX_TAG | JSON_HEX_AMP) ?>;
    const updateUrl = <?= json_encode(base_url('kelas/update.php'), JSON_UNESCAPED_SLASHES | JSON_HEX_TAG | JSON_HEX_AMP) ?>;

    function fillCreate(values) {
        form.action = storeUrl;
        title.textContent = 'Tambah Kelas';
        submitLabel.textContent = 'Simpan Kelas';
        idGroup.classList.add('d-none');
        idInput.value = '';
        namaInput.value = values && values.nama_kelas ? values.nama_kelas : '';
        jurusanInput.value = values && values.jurusan ? values.jurusan : '';
    }

    function fillEdit(values) {
        form.action = updateUrl;
        title.textContent = 'Ubah Kelas';
        submitLabel.textContent = 'Simpan Perubahan';
        idGroup.classList.remove('d-none');
        idInput.value = values.id || '';
        namaInput.value = values.nama_kelas || '';
        jurusanInput.value = values.jurusan || '';
    }

    modalElement.addEventListener('show.bs.modal', function (event) {
        const button = event.relatedTarget;

        if (button && button.dataset.id) {
            fillEdit({
                id: button.dataset.id,
                nama_kelas: button.dataset.nama,
                jurusan: button.dataset.jurusan
            });
        } else if (button) {
            fillCreate();
        }

        // Saat dibuka manual oleh pengguna, bersihkan sisa pesan validasi.
        if (button) {
            form.querySelectorAll('.is-invalid').forEach(function (field) {
                field.classList.remove('is-invalid');
            });
            if (errorBox) {
                errorBox.remove();
            }
        }
    });

    <?php if ($modal === 'create'): ?>
    fillCreate(<?= json_encode($old, JSON_UNESCAPED_SLASHES | JSON_HEX_TAG | JSON_HEX_AMP) ?>);
    bootstrap.Modal.getOrCreateInstance(modalElement).show();
    <?php elseif ($modal === 'edit'): ?>
    fillEdit(<?= json_encode($old, JSON_UNESCAPED_SLASHES | JSON_HEX_TAG | JSON_HEX_AMP) ?>);
    bootstrap.Modal.getOrCreateInstance(modalElement).show();
    <?php endif; ?>
});
</script>

<?php require __DIR__ . '/../layouts/footer.php'; ?>
