<?php

namespace App\Models;

use PDO;

class PDOFactory
{
    public function create(array $config): PDO
    {
        [
            'dbhost' => $dbhost,
            'dbname' => $dbname,
            'dbuser' => $dbuser,
            'dbpass' => $dbpass
        ] = $config;

        $dsn = "pgsql:host={$dbhost};port=5432;dbname={$dbname};";
        return new PDO($dsn, $dbuser, $dbpass, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
        ]);
    }
}

