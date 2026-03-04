<?php

define('ROOTDIR', __DIR__ . DIRECTORY_SEPARATOR);

require_once ROOTDIR . 'vendor/autoload.php';
require_once ROOTDIR . 'app/functions.php';

$dotenv = Dotenv\Dotenv::createImmutable(ROOTDIR);
$dotenv->load();

try {
    $PDO = (new App\Models\PDOFactory())->create([
        'dbhost' => $_ENV['DB_HOST'] ?? 'db',
        'dbname' => $_ENV['DB_NAME'] ?? $_ENV['POSTGRES_DB'] ?? 'store_ct275',
        'dbuser' => $_ENV['DB_USER'] ?? $_ENV['POSTGRES_USER'],
        'dbpass' => $_ENV['DB_PASS'] ?? $_ENV['POSTGRES_PASSWORD'],
    ]);
} catch (Exception $ex) {
    echo 'Cannot connect to PostgreSQL. Please check your database configuration.<br>';
    dd($ex);
}

$AUTHGUARD = new App\SessionGuard();

