<?php

use Apps\Index\Controllers\ErrorController;
use Includes\Routing\Router;

require APPS_PATH . '/Index/urls.php';
require APPS_PATH . '/Products/urls.php';
require APPS_PATH . '/Users/urls.php';
require APPS_PATH . '/Order/urls.php';
require APPS_PATH . '/Ajax/urls.php';
require APPS_PATH . '/Contacts/urls.php';
require APPS_PATH . '/Admin/urls.php';

Router::includes($index_urls, "index");
Router::includes($product_urls, 'products');
Router::includes($users_urls, 'users');
Router::includes($order_urls, 'order');
Router::includes($ajax_urls, 'ajax');
Router::includes($contacts_urls, 'contacts');
Router::includes($admin_urls, 'admin');

Router::set_error_controller('default', new ErrorController())
?>