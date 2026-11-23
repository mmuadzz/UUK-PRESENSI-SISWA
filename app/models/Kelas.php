<?php

declare(strict_types=1);

/**
 * Model data kelas.
 */
final class Kelas extends BaseModel
{
    protected string $table = 'kelas';

    /**
     * @return array<int, array<string, mixed>>
     */
    public function all(): array
    {
        return $this->fetchAll(
            'SELECT k.id, k.nama_kelas, k.jurusan, COUNT(s.nisn) AS jumlah_siswa
             FROM kelas k
             LEFT JOIN siswa s ON s.id_kelas = k.id
             GROUP BY k.id, k.nama_kelas, k.jurusan
             ORDER BY k.nama_kelas ASC'
        );
    }

    /**
     * @return array<string, mixed>|null
     */
    public function find(int $id): ?array
    {
        return $this->fetchOne(
            'SELECT id, nama_kelas, jurusan
             FROM kelas
             WHERE id = :id
             LIMIT 1',
            ['id' => $id]
        );
    }

    public function namaExists(string $namaKelas, ?int $exceptId = null): bool
    {
        $sql = 'SELECT id FROM kelas WHERE nama_kelas = :nama_kelas';
        $params = ['nama_kelas' => $namaKelas];

        if ($exceptId !== null) {
            $sql .= ' AND id <> :except_id';
            $params['except_id'] = $exceptId;
        }

        $sql .= ' LIMIT 1';

        return $this->fetchOne($sql, $params) !== null;
    }

    public function create(string $namaKelas, string $jurusan): int
    {
        $this->execute(
            'INSERT INTO kelas (nama_kelas, jurusan) VALUES (:nama_kelas, :jurusan)',
            [
                'nama_kelas' => $namaKelas,
                'jurusan' => $jurusan,
            ]
        );

        return $this->lastInsertId();
    }

    public function update(int $id, string $namaKelas, string $jurusan): void
    {
        $this->execute(
            'UPDATE kelas
             SET nama_kelas = :nama_kelas, jurusan = :jurusan
             WHERE id = :id',
            [
                'id' => $id,
                'nama_kelas' => $namaKelas,
                'jurusan' => $jurusan,
            ]
        );
    }

    public function delete(int $id): void
    {
        $this->execute('DELETE FROM kelas WHERE id = :id', ['id' => $id]);
    }

    public function jumlahSiswa(int $id): int
    {
        return $this->scalar(
            'SELECT COUNT(*) FROM siswa WHERE id_kelas = :id',
            ['id' => $id]
        );
    }

    public function count(): int
    {
        return $this->scalar('SELECT COUNT(*) FROM kelas');
    }
}
