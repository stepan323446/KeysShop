<?php

use KeysShop\Apps\Order\Controllers;
use KeysShop\Includes\Routing\Path;

$order_urls = [
    new Path('/order', new Controllers\OrderController(), 'index'),
    new Path('/payment-success', new Controllers\OrderSuccessController(), 'success')
];