<?php
require __DIR__ . '/vendor/autoload.php';

use Includes\MigrationRunner;

define('BASE_PATH', __DIR__);
define('APPS_PATH', BASE_PATH . '/Apps');
define('INCLUDES_PATH', BASE_PATH . '/Includes');

require_once BASE_PATH . '/functions.php';
require_once BASE_PATH . '/config.php';
require_once BASE_PATH . '/db.php';

define('MIGRATION_DB_NAME', 'migrations');

try {
    $pdo->exec(
        'CREATE TABLE IF NOT EXISTS '. MIGRATION_DB_NAME .' (
            id      INT AUTO_INCREMENT PRIMARY KEY,
            app     VARCHAR(255) NOT NULL,
            name    VARCHAR(255) NOT NULL,
            applied DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
            UNIQUE KEY unique_migration (app, name)
        )'
    );

    $runner = new MigrationRunner($pdo, REGISTERED_APPS);
    $runner->run();

    echo "===================================\n";
    echo "Migrations was successfully applied\n";
} catch (PDOException $e) {
    die('Migration table creation failed: ' . $e->getMessage());
}