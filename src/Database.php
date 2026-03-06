<?php

namespace App;

use PDO;

class Database {
    public static function connect() {
        $host = getenv('DB_HOST') ?: 'localhost';
        $dbname = getenv('DB_NAME');
        $user = getenv('DB_USER') ?: 'root';
        $pass = getenv('DB_PASS') ?: '';
        
        if (!$dbname) {
            throw new \Exception('DB_NAME non configurato! Esegui setup.sh per configurare il database.');
        }
        
        return new PDO(
            "mysql:host=$host;dbname=$dbname;charset=utf8mb4",
            $user,
            $pass,
            [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
        );
    }
}
