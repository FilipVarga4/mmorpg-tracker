<?php
declare(strict_types=1);

class Database {
    private static ?PDO $connection = null;

    private const HOST = 'localhost';
    private const DBNAME = 'mmorpg_tracker';
    private const USER = 'tracker_user';
    private const PASS = 'tracker123';

    public static function getConnection(): PDO {
        if (self::$connection === null) {
            try {
                $dsn = "mysql:host=" . self::HOST . ";dbname=" . self::DBNAME . ";charset=utf8mb4";
                self::$connection = new PDO($dsn, self::USER, self::PASS, [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                ]);
            } catch (PDOException $e) {
                die("Chyba pripojenia k databáze: " . $e->getMessage());
            }
        }
        return self::$connection;
    }
}