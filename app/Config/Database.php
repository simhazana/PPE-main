<?php
namespace Config;
use PDO;

final class Database {
    private static ?PDO $pdo = null;
    public static function get(): PDO {
        if (self::$pdo) return self::$pdo;
        $dsn  = sprintf('mysql:host=%s;port=%s;dbname=%s;charset=utf8mb4',
            getenv('DB_HOST') ?: 'localhost',
            getenv('DB_PORT') ?: '3306',
            getenv('DB_NAME') ?: 'gestion_des_frais'
        );
        $user = getenv('DB_USER') ?: 'root';
        $pass = getenv('DB_PASS') ?: '';
        self::$pdo = new PDO($dsn, $user, $pass, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ]);
        return self::$pdo;
    }
}

