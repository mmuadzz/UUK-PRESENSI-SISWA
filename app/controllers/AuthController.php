<?php

declare(strict_types=1);

/**
 * Menangani proses login administrator.
 */
final class AuthController
{
    /**
     * Hash dummy agar waktu verifikasi tetap konsisten ketika username tidak ada.
     * (Hash dari string "password" — tidak digunakan untuk autentikasi.)
     */
    private const DUMMY_HASH = '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi';

    private User $users;

    public function __construct()
    {
        $this->users = new User();
    }

    /**
     * @return array{errors:array<string,string>,username:string}
     */
    public function login(): array
    {
        $errors = [];
        $username = '';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            csrf_verify('login.php');

            $username = trim((string) ($_POST['username'] ?? ''));
            $password = (string) ($_POST['password'] ?? '');

            if ($username === '') {
                $errors['username'] = 'Username wajib diisi.';
            }

            if ($password === '') {
                $errors['password'] = 'Password wajib diisi.';
            }

            if ($errors === []) {
                $user = $this->users->findByUsername($username);
                $hash = $user !== null ? (string) $user['password'] : self::DUMMY_HASH;
                $passwordValid = password_verify($password, $hash);

                if ($user !== null && $passwordValid) {
                    login_user($user);
                    flash('success', 'Selamat datang, ' . $user['nama_lengkap'] . '.');
                    redirect('dashboard.php');
                }

                $errors['login'] = 'Username atau password salah.';
            }
        }

        return [
            'errors' => $errors,
            'username' => $username,
        ];
    }
}
