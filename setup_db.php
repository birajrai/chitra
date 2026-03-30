<?php

require_once __DIR__ . '/vendor/autoload.php';

use Dotenv\Dotenv;
use App\Core\Database;

$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();

$db = Database::getInstance()->getConnection();
$schema = file_get_contents(__DIR__ . '/database/schema.sql');

try {
    $db->exec($schema);
    echo "Database initialized successfully.\n";
} catch (PDOException $e) {
    echo "Error initializing database: " . $e->getMessage() . "\n";
}
