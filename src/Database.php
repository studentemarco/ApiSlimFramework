<?php

namespace App;

use PDO;

class Database {
    public static function connect() {
        return new PDO(
            "mysql:host=localhost;dbname=ApiSlimFramework;charset=utf8mb4",
            "utente_phpmyadmin",
            "password_sicura",
            [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
        );
    }
}