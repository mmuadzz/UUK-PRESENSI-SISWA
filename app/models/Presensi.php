<?php

declare(strict_types=1);

/**
 * Model pencatatan dan rekap presensi.
 */
final class Presensi extends BaseModel
{
    protected string $table = 'presensi';

    /**
     * Mencatat presensi hari ini untuk NISN tertentu.
     *
     * @return array{status:string,message:string,siswa?:array<string,mixed>,waktu?:string}
     */
    public function tap(string $nisn): array
    {
        $siswaModel = new Siswa();
        $siswa = $siswaModel->find($nisn);

        if ($siswa === null) {
            return [
                'status' => 'not_found',
                'message' => 'Siswa dengan NISN ' . $nisn . ' tidak ditemukan.',
            ];
        }

        $today = date('Y-m-d');
        $existing = $this->findByNisnAndDate($nisn, $today);

        if ($existing !== null) {
            return [
                'status' => 'duplicate',
                'message' => 'Presensi hari ini sudah tercatat. Tidak ada data ganda yang dibuat.',
                'siswa' => $siswa,
                'waktu' => (string) $existing['tanggal_waktu'],
            ];
        }

        $now = date('Y-m-d H:i:s');

        try {
            $this->execute(
                'INSERT INTO presensi (nisn, tanggal_waktu) VALUES (:nisn, :tanggal_waktu)',
                [
                    'nisn' => $nisn,
                    'tanggal_waktu' => $now,
                ]
            );
        } catch (PDOException $exception) {
            // Unique key (nisn, tanggal_presensi) menjadi perlindungan terakhir
            // ketika dua request masuk hampir bersamaan.
            if ($exception->getCode() === '23000') {
                $existing = $this->findByNisnAndDate($nisn, $today);

                if ($existing !== null) {
                    return [
                        'status' => 'duplicate',
                        'message' => 'Presensi hari ini sudah tercatat. Tidak ada data ganda yang dibuat.',
                        'siswa' => $siswa,
                        'waktu' => (string) $existing['tanggal_waktu'],
                    ];
                }
            }

            throw $exception;
        }

        return [
            'status' => 'success',
            'message' => 'Presensi berhasil dicatat.',
            'siswa' => $siswa,
            'waktu' => $now,
        ];
    }

    /**
     * @return array<string, mixed>|null
     */
    public function findByNisnAndDate(string $nisn, string $tanggal): ?array
    {
        return $this->fetchOne(
            'SELECT id, nisn, tanggal_waktu
             FROM presensi
             WHERE nisn = :nisn AND tanggal_presensi = :tanggal
             LIMIT 1',
            [
                'nisn' => $nisn,
                'tanggal' => $tanggal,
            ]
        );
    }

    /**
     * Rekap seluruh siswa (termasuk yang belum presensi) pada tanggal tertentu.
     *
     * @return array{rows:array<int, array<string, mixed>>,hadir:int,tidak_hadir:int,total:int}
     */
    public function rekap(string $tanggal, ?int $kelasId = null): array
    {
        $sql = 'SELECT s.nisn, s.nama, s.foto, s.id_kelas, k.nama_kelas, p.tanggal_waktu
                FROM siswa s
                INNER JOIN kelas k ON k.id = s.id_kelas
                LEFT JOIN presensi p
                    ON p.nisn = s.nisn AND p.tanggal_presensi = :tanggal';

        $params = ['tanggal' => $tanggal];

        if ($kelasId !== null) {
            $sql .= ' WHERE s.id_kelas = :kelas_id';
            $params['kelas_id'] = $kelasId;
        }

        $sql .= ' ORDER BY k.nama_kelas ASC, s.nama ASC';

        $rows = $this->fetchAll($sql, $params);

        $hadir = 0;
        foreach ($rows as $row) {
            if ($row['tanggal_waktu'] !== null) {
                $hadir++;
            }
        }

        return [
            'rows' => $rows,
            'hadir' => $hadir,
            'tidak_hadir' => count($rows) - $hadir,
            'total' => count($rows),
        ];
    }

    public function countHadir(string $tanggal): int
    {
        return $this->scalar(
            'SELECT COUNT(*) FROM presensi WHERE tanggal_presensi = :tanggal',
            ['tanggal' => $tanggal]
        );
    }

    public function countBelum(string $tanggal): int
    {
        return $this->scalar(
            'SELECT COUNT(*)
             FROM siswa s
             LEFT JOIN presensi p
                ON p.nisn = s.nisn AND p.tanggal_presensi = :tanggal
             WHERE p.id IS NULL',
            ['tanggal' => $tanggal]
        );
    }

    /**
     * Presensi terbaru lintas tanggal untuk kartu dashboard.
     *
     * @return array<int, array<string, mixed>>
     */
    public function latest(int $limit = 8): array
    {
        $limit = max(1, min(50, $limit));

        return $this->fetchAll(
            'SELECT p.id, p.nisn, p.tanggal_waktu, s.nama, s.foto, k.nama_kelas
             FROM presensi p
             INNER JOIN siswa s ON s.nisn = p.nisn
             INNER JOIN kelas k ON k.id = s.id_kelas
             ORDER BY p.tanggal_waktu DESC
             LIMIT ' . $limit
        );
    }

    /**
     * Riwayat tap pada hari ini untuk halaman simulasi.
     *
     * @return array<int, array<string, mixed>>
     */
    public function historyToday(int $limit = 8): array
    {
        $limit = max(1, min(50, $limit));

        return $this->fetchAll(
            'SELECT p.id, p.nisn, p.tanggal_waktu, s.nama, s.foto, k.nama_kelas
             FROM presensi p
             INNER JOIN siswa s ON s.nisn = p.nisn
             INNER JOIN kelas k ON k.id = s.id_kelas
             WHERE p.tanggal_presensi = :tanggal
             ORDER BY p.tanggal_waktu DESC
             LIMIT ' . $limit,
            ['tanggal' => date('Y-m-d')]
        );
    }
}
