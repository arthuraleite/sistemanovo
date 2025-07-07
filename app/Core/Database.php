<?php

namespace App\Core;

use PDO;
use App\Config\Config;

class Database
{
    public static function conectar()
    {
        $dsn = 'mysql:host=' . Config::DB_HOST . ';dbname=' . Config::DB_NAME . ';charset=utf8';
        return new PDO($dsn, Config::DB_USER, Config::DB_PASS, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
        ]);
    }
}
