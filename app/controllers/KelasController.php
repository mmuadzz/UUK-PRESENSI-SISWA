<?php

declare(strict_types=1);

/**
 * Menangani CRUD data kelas.
 */
final class KelasController
{
    private Kelas $kelas;

    public function __construct()
    {
        $this->kelas = new Kelas();
    }

    /**
     * @return array{daftar:array<int, array<string, mixed>>,errors:array<string,string>,old:array<string,mixed>,modal:?string}
     */
    public function index(): array
    {
        $state = pull_form_state();

        return [
            'daftar' => $this->kelas->all(),
            'errors' => $state['errors'],
            'old' => $state['old'],
            'modal' => $state['modal'],
        ];
    }

    public function store(): void
    {
        csrf_verify('kelas/index.php');

        $namaKelas = trim((string) ($_POST['nama_kelas'] ?? ''));
        $jurusan = trim((string) ($_POST['jurusan'] ?? ''));

        $errors = $this->validate($namaKelas, $jurusan, null);

        if ($errors !== []) {
            remember_form_state(
                $errors,
                ['nama_kelas' => $namaKelas, 'jurusan' => $jurusan],
                'create'
            );
            flash('error', 'Periksa kembali data kelas yang diisi.');
            redirect('kelas/index.php');
        }

        try {
            $this->kelas->create($namaKelas, $jurusan);
        } catch (PDOException $exception) {
            if ($exception->getCode() === '23000') {
                remember_form_state(
                    ['nama_kelas' => 'Nama kelas sudah digunakan.'],
                    ['nama_kelas' => $namaKelas, 'jurusan' => $jurusan],
                    'create'
                );
                flash('error', 'Nama kelas sudah digunakan. Gunakan nama lain.');
                redirect('kelas/index.php');
            }

            throw $exception;
        }

        flash('success', 'Kelas "' . $namaKelas . '" berhasil ditambahkan.');
        redirect('kelas/index.php');
    }

    public function update(): void
    {
        csrf_verify('kelas/index.php');

        $id = (int) ($_POST['id'] ?? 0);
        $namaKelas = trim((string) ($_POST['nama_kelas'] ?? ''));
        $jurusan = trim((string) ($_POST['jurusan'] ?? ''));

        $kelas = $this->kelas->find($id);
        if ($kelas === null) {
            flash('error', 'Data kelas tidak ditemukan.');
            redirect('kelas/index.php');
        }

        $old = ['id' => $id, 'nama_kelas' => $namaKelas, 'jurusan' => $jurusan];
        $errors = $this->validate($namaKelas, $jurusan, $id);

        if ($errors !== []) {
            remember_form_state($errors, $old, 'edit');
            flash('error', 'Periksa kembali data kelas yang diisi.');
            redirect('kelas/index.php');
        }

        try {
            $this->kelas->update($id, $namaKelas, $jurusan);
        } catch (PDOException $exception) {
            if ($exception->getCode() === '23000') {
                remember_form_state(
                    ['nama_kelas' => 'Nama kelas sudah digunakan.'],
                    $old,
                    'edit'
                );
                flash('error', 'Nama kelas sudah digunakan. Gunakan nama lain.');
                redirect('kelas/index.php');
            }

            throw $exception;
        }

        flash('success', 'Data kelas berhasil diperbarui.');
        redirect('kelas/index.php');
    }

    public function delete(): void
    {
        csrf_verify('kelas/index.php');

        $id = (int) ($_POST['id'] ?? 0);
        $kelas = $this->kelas->find($id);

        if ($kelas === null) {
            flash('error', 'Data kelas tidak ditemukan.');
            redirect('kelas/index.php');
        }

        $jumlahSiswa = $this->kelas->jumlahSiswa($id);
        if ($jumlahSiswa > 0) {
            flash(
                'error',
                'Kelas "' . $kelas['nama_kelas'] . '" tidak dapat dihapus karena masih memiliki '
                . $jumlahSiswa . ' siswa. Pindahkan siswa terlebih dahulu.'
            );
            redirect('kelas/index.php');
        }

        try {
            $this->kelas->delete($id);
        } catch (PDOException $exception) {
            if ($exception->getCode() === '23000') {
                flash('error', 'Kelas tidak dapat dihapus karena masih digunakan oleh data siswa.');
                redirect('kelas/index.php');
            }

            throw $exception;
        }

        flash('success', 'Kelas "' . $kelas['nama_kelas'] . '" berhasil dihapus.');
        redirect('kelas/index.php');
    }

    /**
     * @return array<string, string>
     */
    private function validate(string $namaKelas, string $jurusan, ?int $exceptId): array
    {
        $errors = [];

        if ($namaKelas === '') {
            $errors['nama_kelas'] = 'Nama kelas wajib diisi.';
        } elseif (mb_strlen($namaKelas) > 50) {
            $errors['nama_kelas'] = 'Nama kelas maksimal 50 karakter.';
        } elseif ($this->kelas->namaExists($namaKelas, $exceptId)) {
            $errors['nama_kelas'] = 'Nama kelas sudah digunakan.';
        }

        if ($jurusan === '') {
            $errors['jurusan'] = 'Jurusan wajib diisi.';
        } elseif (mb_strlen($jurusan) > 100) {
            $errors['jurusan'] = 'Jurusan maksimal 100 karakter.';
        }

        return $errors;
    }
}
