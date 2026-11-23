<?php

declare(strict_types=1);

/**
 * Menangani CRUD data siswa dan upload foto.
 */
final class SiswaController
{
    private Siswa $siswa;

    private Kelas $kelas;

    public function __construct()
    {
        $this->siswa = new Siswa();
        $this->kelas = new Kelas();
    }

    /**
     * @return array<string, mixed>
     */
    public function index(): array
    {
        $search = trim((string) ($_GET['q'] ?? ''));
        if (mb_strlen($search) > 100) {
            $search = mb_substr($search, 0, 100);
        }

        $kelasParam = (string) ($_GET['kelas'] ?? '');
        $kelasId = ctype_digit($kelasParam) && (int) $kelasParam > 0 ? (int) $kelasParam : null;

        if ($kelasId !== null && $this->kelas->find($kelasId) === null) {
            $kelasId = null;
        }

        $page = max(1, (int) ($_GET['page'] ?? 1));
        $result = $this->siswa->paginate($search, $kelasId, $page, 10);

        return [
            'daftar' => $result['data'],
            'total' => $result['total'],
            'page' => $result['page'],
            'total_pages' => $result['total_pages'],
            'per_page' => $result['per_page'],
            'q' => $search,
            'kelas_id' => $kelasId,
            'daftar_kelas' => $this->kelas->all(),
        ];
    }

    /**
     * @return array<string, mixed>
     */
    public function create(): array
    {
        $errors = [];
        $old = ['nisn' => '', 'nama' => '', 'alamat' => '', 'id_kelas' => ''];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            csrf_verify('siswa/create.php');

            $old = $this->inputFromRequest();
            $errors = $this->validate($old, null);

            $fotoBaru = null;

            if ($errors === []) {
                $fotoError = null;
                $fotoBaru = upload_foto($_FILES['foto'] ?? null, $fotoError);

                if ($fotoError !== null) {
                    $errors['foto'] = $fotoError;
                }
            }

            if ($errors === []) {
                try {
                    $this->siswa->create([
                        'nisn' => $old['nisn'],
                        'nama' => $old['nama'],
                        'alamat' => $old['alamat'],
                        'id_kelas' => (int) $old['id_kelas'],
                        'foto' => $fotoBaru,
                    ]);

                    flash('success', 'Data siswa "' . $old['nama'] . '" berhasil ditambahkan.');
                    redirect('siswa/index.php');
                } catch (PDOException $exception) {
                    if ($fotoBaru !== null) {
                        delete_upload($fotoBaru);
                    }

                    if ($exception->getCode() === '23000') {
                        $errors['nisn'] = 'NISN sudah terdaftar. Gunakan NISN lain.';
                    } else {
                        throw $exception;
                    }
                }
            }
        }

        return [
            'errors' => $errors,
            'old' => $old,
            'foto_lama' => null,
            'mode' => 'create',
            'daftar_kelas' => $this->kelas->all(),
        ];
    }

    /**
     * @return array<string, mixed>
     */
    public function edit(string $nisn): array
    {
        $siswa = $this->siswa->find($nisn);

        if ($siswa === null) {
            flash('error', 'Data siswa tidak ditemukan.');
            redirect('siswa/index.php');
        }

        $errors = [];
        $old = [
            'nisn' => (string) $siswa['nisn'],
            'nama' => (string) $siswa['nama'],
            'alamat' => (string) $siswa['alamat'],
            'id_kelas' => (string) $siswa['id_kelas'],
        ];
        $fotoLama = $siswa['foto'];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            csrf_verify('siswa/index.php');

            $old = $this->inputFromRequest();
            $errors = $this->validate($old, $nisn);

            $fotoBaru = null;

            if ($errors === []) {
                $fotoError = null;
                $fotoBaru = upload_foto($_FILES['foto'] ?? null, $fotoError);

                if ($fotoError !== null) {
                    $errors['foto'] = $fotoError;
                }
            }

            if ($errors === []) {
                try {
                    $this->siswa->update($nisn, [
                        'nisn' => $old['nisn'],
                        'nama' => $old['nama'],
                        'alamat' => $old['alamat'],
                        'id_kelas' => (int) $old['id_kelas'],
                        'foto' => $fotoBaru ?? $fotoLama,
                    ]);

                    if ($fotoBaru !== null) {
                        delete_upload($fotoLama);
                    }

                    flash('success', 'Data siswa "' . $old['nama'] . '" berhasil diperbarui.');
                    redirect('siswa/index.php');
                } catch (PDOException $exception) {
                    if ($fotoBaru !== null) {
                        delete_upload($fotoBaru);
                    }

                    if ($exception->getCode() === '23000') {
                        $errors['nisn'] = 'NISN sudah terdaftar. Gunakan NISN lain.';
                    } else {
                        throw $exception;
                    }
                }
            }
        }

        return [
            'errors' => $errors,
            'old' => $old,
            'foto_lama' => $fotoLama,
            'mode' => 'edit',
            'daftar_kelas' => $this->kelas->all(),
        ];
    }

    public function delete(): void
    {
        csrf_verify('siswa/index.php');

        $nisn = trim((string) ($_POST['nisn'] ?? ''));
        $siswa = $this->siswa->find($nisn);

        if ($siswa === null) {
            flash('error', 'Data siswa tidak ditemukan.');
            redirect('siswa/index.php');
        }

        $this->siswa->delete($nisn);
        delete_upload((string) $siswa['foto']);

        flash('success', 'Data siswa "' . $siswa['nama'] . '" berhasil dihapus.');
        redirect('siswa/index.php');
    }

    /**
     * @return array{nisn:string,nama:string,alamat:string,id_kelas:string}
     */
    private function inputFromRequest(): array
    {
        return [
            'nisn' => trim((string) ($_POST['nisn'] ?? '')),
            'nama' => trim((string) ($_POST['nama'] ?? '')),
            'alamat' => trim((string) ($_POST['alamat'] ?? '')),
            'id_kelas' => trim((string) ($_POST['id_kelas'] ?? '')),
        ];
    }

    /**
     * @param array{nisn:string,nama:string,alamat:string,id_kelas:string} $data
     * @return array<string, string>
     */
    private function validate(array $data, ?string $exceptNisn): array
    {
        $errors = [];

        if ($data['nisn'] === '') {
            $errors['nisn'] = 'NISN wajib diisi.';
        } elseif (!preg_match('/^\d{10}$/', $data['nisn'])) {
            $errors['nisn'] = 'NISN harus terdiri dari tepat 10 digit angka.';
        } elseif ($this->siswa->nisnExists($data['nisn'], $exceptNisn)) {
            $errors['nisn'] = 'NISN sudah terdaftar. Gunakan NISN lain.';
        }

        if ($data['nama'] === '') {
            $errors['nama'] = 'Nama lengkap wajib diisi.';
        } elseif (mb_strlen($data['nama']) > 120) {
            $errors['nama'] = 'Nama lengkap maksimal 120 karakter.';
        }

        if (mb_strlen($data['alamat']) > 1000) {
            $errors['alamat'] = 'Alamat maksimal 1000 karakter.';
        }

        $kelasId = (int) $data['id_kelas'];

        if ($kelasId <= 0) {
            $errors['id_kelas'] = 'Kelas wajib dipilih.';
        } elseif ($this->kelas->find($kelasId) === null) {
            $errors['id_kelas'] = 'Kelas yang dipilih tidak tersedia.';
        }

        return $errors;
    }
}
