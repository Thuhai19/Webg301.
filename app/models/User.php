<?php

class User
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::getConnection();
    }

    public function findByUsername(string $username): ?array
    {
        $sql = 'SELECT id, username, password FROM users WHERE username = :u LIMIT 1';
        $st = $this->db->prepare($sql);
        $st->execute(['u' => $username]);
        $row = $st->fetch();
        return $row ?: null;
    }
}
