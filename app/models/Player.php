<?php

class Player
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::getConnection();
    }

    public function all(): array
    {
        $sql = 'SELECT p.*, c.name AS kill_character_name, i.name AS item_name
                FROM players p
                LEFT JOIN characters c ON c.id = p.kill_character_id
                LEFT JOIN items i ON i.id = p.item_id
                ORDER BY p.id ASC';
        return $this->db->query($sql)->fetchAll();
    }

    public function findById(int $id): ?array
    {
        $sql = 'SELECT p.*, c.name AS kill_character_name, i.name AS item_name
                FROM players p
                LEFT JOIN characters c ON c.id = p.kill_character_id
                LEFT JOIN items i ON i.id = p.item_id
                WHERE p.id = :id LIMIT 1';
        $st = $this->db->prepare($sql);
        $st->execute(['id' => $id]);
        $row = $st->fetch();
        return $row ?: null;
    }

    public function create(string $name, string $email, ?int $killCharacterId, ?int $itemId): int
    {
        $sql = 'INSERT INTO players (name, email, kill_character_id, item_id) VALUES (:n, :e, :k, :i)';
        $st = $this->db->prepare($sql);
        $st->execute([
            'n' => $name,
            'e' => $email,
            'k' => $killCharacterId,
            'i' => $itemId,
        ]);
        return (int) $this->db->lastInsertId();
    }

    public function update(int $id, string $name, string $email, ?int $killCharacterId, ?int $itemId): bool
    {
        $sql = 'UPDATE players
                SET name = :n, email = :e, kill_character_id = :k, item_id = :i
                WHERE id = :id';
        $st = $this->db->prepare($sql);
        return $st->execute([
            'n' => $name,
            'e' => $email,
            'k' => $killCharacterId,
            'i' => $itemId,
            'id' => $id,
        ]);
    }

    public function delete(int $id): bool
    {
        $sql = 'DELETE FROM players WHERE id = :id';
        $st = $this->db->prepare($sql);
        return $st->execute(['id' => $id]);
    }

    public function searchById(int $id): array
    {
        $sql = 'SELECT p.*, c.name AS kill_character_name, i.name AS item_name
                FROM players p
                LEFT JOIN characters c ON c.id = p.kill_character_id
                LEFT JOIN items i ON i.id = p.item_id
                WHERE p.id = :id';
        $st = $this->db->prepare($sql);
        $st->execute(['id' => $id]);
        $row = $st->fetch();
        return $row ? [$row] : [];
    }

    public function setKillCharacterId(int $playerId, ?int $characterId): bool
    {
        $sql = 'UPDATE players SET kill_character_id = :cid WHERE id = :id';
        $st = $this->db->prepare($sql);
        return $st->execute([
            'cid' => $characterId,
            'id' => $playerId,
        ]);
    }

    public function setItemId(int $playerId, ?int $itemId): bool
    {
        $sql = 'UPDATE players SET item_id = :iid WHERE id = :id';
        $st = $this->db->prepare($sql);
        return $st->execute([
            'iid' => $itemId,
            'id' => $playerId,
        ]);
    }

    public function clearKillCharacterByCharacterId(int $characterId, ?int $exceptPlayerId = null): bool
    {
        $sql = 'UPDATE players SET kill_character_id = NULL WHERE kill_character_id = :cid';
        $params = ['cid' => $characterId];
        if ($exceptPlayerId !== null) {
            $sql .= ' AND id <> :pid';
            $params['pid'] = $exceptPlayerId;
        }
        $st = $this->db->prepare($sql);
        return $st->execute($params);
    }

    public function getItemId(int $playerId): ?int
    {
        $sql = 'SELECT item_id FROM players WHERE id = :id LIMIT 1';
        $st = $this->db->prepare($sql);
        $st->execute(['id' => $playerId]);
        $row = $st->fetch();
        if (!$row || $row['item_id'] === null) {
            return null;
        }
        return (int) $row['item_id'];
    }
}
