<?php
require __DIR__ . '/vendor/autoload.php';

use Includes\MigrationRunner;

define('BASE_PATH', __DIR__);
define('APPS_PATH', BASE_PATH . '/Apps');
define('INCLUDES_PATH', BASE_PATH . '/Includes');

require_once BASE_PATH . '/functions.php';
require_once BASE_PATH . '/config.php';
require_once BASE_PATH . '/db.php';
require_once BASE_PATH . '/Includes/bootstrap_migrations.php';

define('MIGRATION_DB_NAME', 'migrations');

try {
    global $pdo;

    ensure_migrations_table($pdo);

    $runner = new MigrationRunner($pdo, REGISTERED_APPS);
    $runner->run();

    echo "======================================\n";
    echo "Migrations was successfully applied\n";
} catch (PDOException $e) {
    die('Migration table creation failed: ' . $e->getMessage());
}