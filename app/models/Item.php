<?php

class Item
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::getConnection();
    }

    public function all(): array
    {
        $sql = 'SELECT * FROM items ORDER BY id ASC';
        return $this->db->query($sql)->fetchAll();
    }

    public function findById(int $id): ?array
    {
        $sql = 'SELECT * FROM items WHERE id = :id LIMIT 1';
        $st = $this->db->prepare($sql);
        $st->execute(['id' => $id]);
        $row = $st->fetch();
        return $row ?: null;
    }

    public function create(string $name, string $type, int $power): int
    {
        $sql = 'INSERT INTO items (name, type, power) VALUES (:n, :t, :p)';
        $st = $this->db->prepare($sql);
        $st->execute(['n' => $name, 't' => $type, 'p' => $power]);
        return (int) $this->db->lastInsertId();
    }

    public function update(int $id, string $name, string $type, int $power): bool
    {
        $sql = 'UPDATE items SET name = :n, type = :t, power = :p WHERE id = :id';
        $st = $this->db->prepare($sql);
        return $st->execute(['n' => $name, 't' => $type, 'p' => $power, 'id' => $id]);
    }

    public function delete(int $id): bool
    {
        $sql = 'DELETE FROM items WHERE id = :id';
        $st = $this->db->prepare($sql);
        return $st->execute(['id' => $id]);
    }

    public function searchById(int $id): array
    {
        $sql = 'SELECT * FROM items WHERE id = :id';
        $st = $this->db->prepare($sql);
        $st->execute(['id' => $id]);
        $row = $st->fetch();
        return $row ? [$row] : [];
    }
}
