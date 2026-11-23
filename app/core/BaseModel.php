<?php

declare(strict_types=1);

/**
 * Fondasi model: menyediakan koneksi PDO dan helper query yang aman.
 */
abstract class BaseModel
{
    protected PDO $db;

    protected string $table = '';

    public function __construct(?PDO $db = null)
    {
        $this->db = $db ?? Database::connection();
    }

    /**
     * @param array<string, mixed> $params
     * @return array<int, array<string, mixed>>
     */
    protected function fetchAll(string $sql, array $params = []): array
    {
        $statement = $this->db->prepare($sql);
        $statement->execute($params);

        return $statement->fetchAll();
    }

    /**
     * @param array<string, mixed> $params
     * @return array<string, mixed>|null
     */
    protected function fetchOne(string $sql, array $params = []): ?array
    {
        $statement = $this->db->prepare($sql);
        $statement->execute($params);
        $row = $statement->fetch();

        return $row === false ? null : $row;
    }

    /**
     * @param array<string, mixed> $params
     */
    protected function execute(string $sql, array $params = []): int
    {
        $statement = $this->db->prepare($sql);
        $statement->execute($params);

        return $statement->rowCount();
    }

    /**
     * @param array<string, mixed> $params
     */
    protected function scalar(string $sql, array $params = []): int
    {
        $statement = $this->db->prepare($sql);
        $statement->execute($params);
        $value = $statement->fetchColumn();

        return $value === false ? 0 : (int) $value;
    }

    protected function lastInsertId(): int
    {
        return (int) $this->db->lastInsertId();
    }
}
