<?php

require_once APPS_PATH . '/order/controllers.php';

$order_urls = [
    new Path('/order', new OrderController(), 'index'),
    new Path('/payment-success', new OrderSuccessController(), 'success')
];