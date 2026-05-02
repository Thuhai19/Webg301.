<?php
/**
 * Kết nối MySQL bằng PDO (singleton đơn giản)
 */
require_once __DIR__ . '/config.php';

class Database
{
    private static ?PDO $instance = null;

    private const HOST = '127.0.0.1';
    private const DB_NAME = 'rpg_game_db';
    private const USER = 'root';
    private const PASS = '';
    private const CHARSET = 'utf8mb4';

    public static function getConnection(): PDO
    {
        if (self::$instance === null) {
            $dsn = 'mysql:host=' . self::HOST . ';dbname=' . self::DB_NAME . ';charset=' . self::CHARSET;
            $options = [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES   => false,
            ];
            self::$instance = new PDO($dsn, self::USER, self::PASS, $options);
        }
        return self::$instance;
    }
}
