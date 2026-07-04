<?php
// Server timezone for correct time
define('SERVER_TIMEZONE', 'Europe/Belgrade');

// Allowed hosts for ajax
define('ALLOWED_HOSTS', ['localhost:8000', 'example.com']);

// Home url (without / on the end)
define('HOME_URL', 'http://localhost:8000');

// Upload folder for content files
// Upload folder for content files
define('MEDIA_ROOT', BASE_PATH . '/media/');
define('MEDIA_URL', '/media/');

// Database
define('DB_AUTH', array(
    'db_name'       => '<YOUR_DATABASE_NAME>',
    'db_host'       => '<YOUR_DATABASE_HOST>',
    'db_username'   => '<YOUR_USERNAME>',
    'db_password'   => '<YOUR_PASSWORD>',
    'db_charset'    => '<YOUR_DB_CHARSET>'
));

// A warning message for the entire site
define('MESSAGE_WARNING', '');

// Email settings to send system messages
define('EMAIL_SETTINGS', array(
    'host'              => 'smtp.example.com',
    'smtp_auth'         => true,
    'username'          => 'example@example.com',
    'password'          => '',
    'smtp_secure'       => 'ssl',
    'port'              => 465,
    'from_title'        => 'KeysShop'
));

// Registered apps with ordering for dependencies
define('REGISTERED_APPS', [
    'Users',
    'Contacts',
    'Order',
    'Products',
    'Index',
    'Ajax',
    'Admin'
]);

// Enable debug mode
define('DEBUG_MODE', true);

// Chat ID for Telegram Bot
define('TELEGRAM_BOT_CHATID', '<CHAT_ID_BOT>');

// Telegram bot token for sending messages
define('TELEGRAM_BOT_TOKEN', '<BOT_TOKEN>');
?>