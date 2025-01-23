<?php
define('BASE_PATH', __DIR__);
define('APPS_PATH', BASE_PATH . '/apps');
define('INCLUDES_PATH', BASE_PATH . '/includes');

require BASE_PATH . '/functions.php';
require BASE_PATH . '/config.php';
require BASE_PATH . '/db.php';

// PHPMailer
// https://github.com/PHPMailer/PHPMailer
require BASE_PATH . '/includes/PHPMailer/src/PHPMailer.php';
require BASE_PATH . '/includes/PHPMailer/src/SMTP.php';
require BASE_PATH . '/includes/PHPMailer/src/Exception.php';


define('ASSETS_PATH', HOME_URL . '/assets');
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