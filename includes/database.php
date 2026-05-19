<?php
function site_config() {
    static $config = null;

    if ($config !== null) {
        return $config;
    }

    $localConfig = __DIR__ . '/../config.local.php';
    $exampleConfig = __DIR__ . '/../config.local.example.php';

    if (file_exists($localConfig)) {
        $config = require $localConfig;
    } elseif (file_exists($exampleConfig)) {
        $config = require $exampleConfig;
    } else {
        $config = [];
    }

    return is_array($config) ? $config : [];
}

function db() {
    static $pdo = null;

    if ($pdo !== null) {
        return $pdo;
    }

    $config = site_config();

    foreach (['db_host', 'db_name', 'db_user'] as $required) {
        if (empty($config[$required]) || str_starts_with((string)$config[$required], 'YOUR_')) {
            throw new RuntimeException('Database is not configured. Copy config.local.example.php to config.local.php and add your MySQL details.');
        }
    }

    $charset = $config['db_charset'] ?? 'utf8mb4';
    $dsn = 'mysql:host=' . $config['db_host'] . ';dbname=' . $config['db_name'] . ';charset=' . $charset;

    $pdo = new PDO($dsn, $config['db_user'], $config['db_pass'] ?? '', [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
    ]);

    return $pdo;
}

function db_available() {
    try {
        db();
        return true;
    } catch (Throwable $e) {
        return false;
    }
}
?>