<?php

declare(strict_types=1);

/**
 * Model data siswa.
 */
final class Siswa extends BaseModel
{
    protected string $table = 'siswa';

    /**
     * @return array{data:array<int, array<string, mixed>>,total:int,page:int,per_page:int,total_pages:int}
     */
    public function paginate(string $search = '', ?int $kelasId = null, int $page = 1, int $perPage = 10): array
    {
        $perPage = max(1, min(50, $perPage));
        $page = max(1, $page);

        $conditions = [];
        $params = [];

        if ($search !== '') {
            $conditions[] = '(s.nisn LIKE :search_nisn OR s.nama LIKE :search_nama)';
            $params['search_nisn'] = '%' . $search . '%';
            $params['search_nama'] = '%' . $search . '%';
        }

        if ($kelasId !== null) {
            $conditions[] = 's.id_kelas = :kelas_id';
            $params['kelas_id'] = $kelasId;
        }

        $where = $conditions === [] ? '' : ' WHERE ' . implode(' AND ', $conditions);

        $total = $this->scalar('SELECT COUNT(*) FROM siswa s' . $where, $params);
        $totalPages = max(1, (int) ceil($total / $perPage));

        if ($page > $totalPages) {
            $page = $totalPages;
        }

        $offset = ($page - 1) * $perPage;

        $rows = $this->fetchAll(
            'SELECT s.nisn, s.nama, s.alamat, s.id_kelas, s.foto, k.nama_kelas
             FROM siswa s
             INNER JOIN kelas k ON k.id = s.id_kelas'
            . $where .
            ' ORDER BY s.nama ASC
              LIMIT ' . $perPage . ' OFFSET ' . $offset,
            $params
        );

        return [
            'data' => $rows,
            'total' => $total,
            'page' => $page,
            'per_page' => $perPage,
            'total_pages' => $totalPages,
        ];
    }

    /**
     * @return array<string, mixed>|null
     */
    public function find(string $nisn): ?array
    {
        return $this->fetchOne(
            'SELECT s.nisn, s.nama, s.alamat, s.id_kelas, s.foto,
                    k.nama_kelas, k.jurusan
             FROM siswa s
             INNER JOIN kelas k ON k.id = s.id_kelas
             WHERE s.nisn = :nisn
             LIMIT 1',
            ['nisn' => $nisn]
        );
    }

    public function nisnExists(string $nisn, ?string $exceptNisn = null): bool
    {
        $sql = 'SELECT nisn FROM siswa WHERE nisn = :nisn';
        $params = ['nisn' => $nisn];

        if ($exceptNisn !== null) {
            $sql .= ' AND nisn <> :except_nisn';
            $params['except_nisn'] = $exceptNisn;
        }

        $sql .= ' LIMIT 1';

        return $this->fetchOne($sql, $params) !== null;
    }

    /**
     * @param array{nisn:string,nama:string,alamat:string,id_kelas:int,foto:?string} $data
     */
    public function create(array $data): void
    {
        $this->execute(
            'INSERT INTO siswa (nisn, nama, alamat, id_kelas, foto)
             VALUES (:nisn, :nama, :alamat, :id_kelas, :foto)',
            [
                'nisn' => $data['nisn'],
                'nama' => $data['nama'],
                'alamat' => $data['alamat'],
                'id_kelas' => $data['id_kelas'],
                'foto' => $data['foto'],
            ]
        );
    }

    /**
     * @param array{nisn:string,nama:string,alamat:string,id_kelas:int,foto:?string} $data
     */
    public function update(string $oldNisn, array $data): void
    {
        $this->execute(
            'UPDATE siswa
             SET nisn = :nisn,
                 nama = :nama,
                 alamat = :alamat,
                 id_kelas = :id_kelas,
                 foto = :foto
             WHERE nisn = :old_nisn',
            [
                'nisn' => $data['nisn'],
                'nama' => $data['nama'],
                'alamat' => $data['alamat'],
                'id_kelas' => $data['id_kelas'],
                'foto' => $data['foto'],
                'old_nisn' => $oldNisn,
            ]
        );
    }

    public function delete(string $nisn): void
    {
        $this->execute('DELETE FROM siswa WHERE nisn = :nisn', ['nisn' => $nisn]);
    }

    public function count(): int
    {
        return $this->scalar('SELECT COUNT(*) FROM siswa');
    }
}
