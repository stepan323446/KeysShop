<?php

require __DIR__ . '/vendor/autoload.php';

use KeysShop\Includes\Routing\Router;

define('BASE_PATH', __DIR__);
define('APPS_PATH', BASE_PATH . '/Apps');
define('INCLUDES_PATH', BASE_PATH . '/Includes');

require_once BASE_PATH . '/functions.php';
require_once BASE_PATH . '/config.php';
require_once BASE_PATH . '/db.php';


define('ASSETS_PATH', HOME_URL . '/Assets');
define('CURRENT_USER', get_auth_user());

date_default_timezone_set(SERVER_TIMEZONE);

require BASE_PATH . '/urls.php';

try {
    Router::display_page();
    
} catch (Exception $e) {
    echo 'Error: ' . $e->getMessage();
} finally {
    if (isset($db)) {
        $db->close();
    }
}
?>