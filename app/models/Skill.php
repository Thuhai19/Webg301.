<?php

class Skill
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::getConnection();
    }

    public function all(): array
    {
        $sql = 'SELECT * FROM skills ORDER BY id ASC';
        return $this->db->query($sql)->fetchAll();
    }

    public function findById(int $id): ?array
    {
        $sql = 'SELECT * FROM skills WHERE id = :id LIMIT 1';
        $st = $this->db->prepare($sql);
        $st->execute(['id' => $id]);
        $row = $st->fetch();
        return $row ?: null;
    }

    public function create(string $name, int $damage, int $manaCost): int
    {
        $sql = 'INSERT INTO skills (name, damage, mana_cost) VALUES (:n, :d, :m)';
        $st = $this->db->prepare($sql);
        $st->execute(['n' => $name, 'd' => $damage, 'm' => $manaCost]);
        return (int) $this->db->lastInsertId();
    }

    public function update(int $id, string $name, int $damage, int $manaCost): bool
    {
        $sql = 'UPDATE skills SET name = :n, damage = :d, mana_cost = :m WHERE id = :id';
        $st = $this->db->prepare($sql);
        return $st->execute(['n' => $name, 'd' => $damage, 'm' => $manaCost, 'id' => $id]);
    }

    public function delete(int $id): bool
    {
        $sql = 'DELETE FROM skills WHERE id = :id';
        $st = $this->db->prepare($sql);
        return $st->execute(['id' => $id]);
    }

    public function searchById(int $id): array
    {
        $sql = 'SELECT * FROM skills WHERE id = :id';
        $st = $this->db->prepare($sql);
        $st->execute(['id' => $id]);
        $row = $st->fetch();
        return $row ? [$row] : [];
    }
}
