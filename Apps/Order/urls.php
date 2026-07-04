<?php

use Apps\Order\Controllers;
use Includes\Routing\Path;

$order_urls = [
    new Path('/order', new Controllers\OrderController(), 'index'),
    new Path('/payment-success', new Controllers\OrderSuccessController(), 'success'),
    new Path('/payment-cancel', new Controllers\OrderCancelController(), 'cancel'),
    new Path('/order/stripe/webhook', new Controllers\OrderStripeWebhook(), 'stripe-webhook')
];