<?php

class Character
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::getConnection();
    }

    public function allWithPlayer(): array
    {
        $sql = 'SELECT c.*, p.name AS kill_by_player_name
                FROM characters c
                LEFT JOIN players p ON p.id = c.kill_by_player_id
                ORDER BY c.id ASC';
        return $this->db->query($sql)->fetchAll();
    }

    public function findById(int $id): ?array
    {
        $sql = 'SELECT c.*, p.name AS kill_by_player_name
                FROM characters c
                LEFT JOIN players p ON p.id = c.kill_by_player_id
                WHERE c.id = :id LIMIT 1';
        $st = $this->db->prepare($sql);
        $st->execute(['id' => $id]);
        $row = $st->fetch();
        return $row ?: null;
    }

    public function create(array $d): int
    {
        $sql = 'INSERT INTO characters (name, level, hp, mana, kill_by_player_id)
                VALUES (:n, :lv, :hp, :mn, :kb)';
        $st = $this->db->prepare($sql);
        $st->execute([
            'n'   => $d['name'],
            'lv'  => $d['level'],
            'hp'  => $d['hp'],
            'mn'  => $d['mana'],
            'kb'  => $d['kill_by_player_id'],
        ]);
        return (int) $this->db->lastInsertId();
    }

    public function update(int $id, array $d): bool
    {
        $sql = 'UPDATE characters
                SET name = :n, level = :lv, hp = :hp, mana = :mn, kill_by_player_id = :kb
                WHERE id = :id';
        $st = $this->db->prepare($sql);
        return $st->execute([
            'n'   => $d['name'],
            'lv'  => $d['level'],
            'hp'  => $d['hp'],
            'mn'  => $d['mana'],
            'kb'  => $d['kill_by_player_id'],
            'id'  => $id,
        ]);
    }

    public function delete(int $id): bool
    {
        $sql = 'DELETE FROM characters WHERE id = :id';
        $st = $this->db->prepare($sql);
        return $st->execute(['id' => $id]);
    }

    public function searchById(int $id): array
    {
        $sql = 'SELECT c.*, p.name AS kill_by_player_name
                FROM characters c
                LEFT JOIN players p ON p.id = c.kill_by_player_id
                WHERE c.id = :id';
        $st = $this->db->prepare($sql);
        $st->execute(['id' => $id]);
        $row = $st->fetch();
        return $row ? [$row] : [];
    }

    public function getSkillsForCharacter(int $characterId): array
    {
        $sql = 'SELECT s.* FROM skills s
                INNER JOIN character_skills cs ON cs.skill_id = s.id
                WHERE cs.character_id = :cid
                ORDER BY s.name';
        $st = $this->db->prepare($sql);
        $st->execute(['cid' => $characterId]);
        return $st->fetchAll();
    }

    public function getItemsForCharacter(int $characterId): array
    {
        $sql = 'SELECT i.* FROM items i
                INNER JOIN character_items ci ON ci.item_id = i.id
                WHERE ci.character_id = :cid
                ORDER BY i.name';
        $st = $this->db->prepare($sql);
        $st->execute(['cid' => $characterId]);
        return $st->fetchAll();
    }

    public function attachSkill(int $characterId, int $skillId): bool
    {
        $sql = 'INSERT IGNORE INTO character_skills (character_id, skill_id) VALUES (:c, :s)';
        $st = $this->db->prepare($sql);
        return $st->execute(['c' => $characterId, 's' => $skillId]);
    }

    public function detachSkill(int $characterId, int $skillId): bool
    {
        $sql = 'DELETE FROM character_skills WHERE character_id = :c AND skill_id = :s';
        $st = $this->db->prepare($sql);
        return $st->execute(['c' => $characterId, 's' => $skillId]);
    }

    public function attachItem(int $characterId, int $itemId): bool
    {
        $sql = 'INSERT IGNORE INTO character_items (character_id, item_id) VALUES (:c, :i)';
        $st = $this->db->prepare($sql);
        return $st->execute(['c' => $characterId, 'i' => $itemId]);
    }

    public function detachItem(int $characterId, int $itemId): bool
    {
        $sql = 'DELETE FROM character_items WHERE character_id = :c AND item_id = :i';
        $st = $this->db->prepare($sql);
        return $st->execute(['c' => $characterId, 'i' => $itemId]);
    }

    public function setKillByPlayerId(int $characterId, ?int $playerId): bool
    {
        $sql = 'UPDATE characters SET kill_by_player_id = :pid WHERE id = :id';
        $st = $this->db->prepare($sql);
        return $st->execute([
            'pid' => $playerId,
            'id' => $characterId,
        ]);
    }

    public function clearKillByPlayerId(int $playerId, ?int $exceptCharacterId = null): bool
    {
        $sql = 'UPDATE characters SET kill_by_player_id = NULL WHERE kill_by_player_id = :pid';
        $params = ['pid' => $playerId];
        if ($exceptCharacterId !== null) {
            $sql .= ' AND id <> :cid';
            $params['cid'] = $exceptCharacterId;
        }
        $st = $this->db->prepare($sql);
        return $st->execute($params);
    }
}
