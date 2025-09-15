<?php
class Database {
    private static $db;

    public static function getConnection() {
        if (!self::$db) {
            $host = "localhost";
            $dbname = "inesbenygzer";
            $username = "root";
            $password = "";

            try {
                self::$db = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
                self::$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            } catch (PDOException $e) {
                die("Database connection failed: " . $e->getMessage());
            }
        }
        return self::$db;
    }
}
