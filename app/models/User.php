<?php

declare(strict_types=1);

/**
 * Model autentikasi administrator.
 */
final class User extends BaseModel
{
    protected string $table = 'users';

    /**
     * @return array<string, mixed>|null
     */
    public function findByUsername(string $username): ?array
    {
        return $this->fetchOne(
            'SELECT id, username, password, nama_lengkap
             FROM users
             WHERE username = :username
             LIMIT 1',
            ['username' => $username]
        );
    }

    public function existsByUsername(string $username): bool
    {
        return $this->fetchOne(
            'SELECT id FROM users WHERE username = :username LIMIT 1',
            ['username' => $username]
        ) !== null;
    }

    public function create(string $username, string $passwordHash, string $namaLengkap): int
    {
        $this->execute(
            'INSERT INTO users (username, password, nama_lengkap)
             VALUES (:username, :password, :nama_lengkap)',
            [
                'username' => $username,
                'password' => $passwordHash,
                'nama_lengkap' => $namaLengkap,
            ]
        );

        return $this->lastInsertId();
    }

    public function updateCredentials(string $username, string $passwordHash, string $namaLengkap): void
    {
        $this->execute(
            'UPDATE users
             SET password = :password, nama_lengkap = :nama_lengkap
             WHERE username = :username',
            [
                'username' => $username,
                'password' => $passwordHash,
                'nama_lengkap' => $namaLengkap,
            ]
        );
    }
}
