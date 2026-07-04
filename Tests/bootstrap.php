<?php
require_once __DIR__ . '/../vendor/autoload.php';

use Includes\MigrationRunner;

define('BASE_PATH', __DIR__ . '/..');
define('APPS_PATH', BASE_PATH . '/Apps');
define('INCLUDES_PATH', BASE_PATH . '/Includes');

require_once BASE_PATH . '/config.testing.php';
require_once BASE_PATH . '/db.php';
require_once BASE_PATH . '/Includes/bootstrap_migrations.php';

define('ASSETS_PATH', HOME_URL . '/Assets');
define('CURRENT_USER', get_auth_user());

date_default_timezone_set(SERVER_TIMEZONE);

global $pdo;
$GLOBALS['pdo'] = $pdo ?? $GLOBALS['pdo'] ?? null;

ensure_migrations_table($pdo);
$runner = new MigrationRunner($pdo, REGISTERED_APPS);
$runner->run();
echo "======================================\n";
echo "Migrations was successfully applied\n";
echo "======================================\n";