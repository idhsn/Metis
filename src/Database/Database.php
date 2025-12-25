<?php

class Database {
    private static ?PDO $pdo = null;
    public static function getConnection(): PDO {
        if (self::$pdo == null){
            try {
                self::$pdo = new PDO(
                    'mysql:host=localhost;dbname=metis',
                    'root',
                    '',
                    [
                        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    ]
                );
                return self::$pdo;
            } catch (PDOException) {
                die("Database connection failed");
            }
        }
        return self::$pdo;
    }
}
