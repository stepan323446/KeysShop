<?php
require INCLUDES_PATH . '/router.php';
require APPS_PATH . '/index/urls.php';
require APPS_PATH . '/contacts/urls.php';
require APPS_PATH . '/products/urls.php';
require APPS_PATH . '/users/urls.php';
require APPS_PATH . '/admin/urls.php';
require APPS_PATH . '/ajax/urls.php';
require APPS_PATH . '/order/urls.php';

require_once APPS_PATH . '/index/controllers.php';

Router::includes($index_urls, "index");
Router::includes($product_urls, 'products');
Router::includes($contacts_urls, 'contacts');
Router::includes($users_urls, 'users');
Router::includes($admin_urls, 'admin');
Router::includes($ajax_urls, 'ajax');
Router::includes($order_urls, 'order');

Router::set_error_controller('default', new ErrorController())
?>