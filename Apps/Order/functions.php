<?php

use Apps\Order\Models\OrderModel;
use Apps\Products\Models\KeyModel;
use Includes\Model\CustomDateTime;
use Includes\Routing\HttpExceptions\BadRequest400;
use Includes\Routing\HttpExceptions\ServerError500;

function method_test_method() {
    global $pdo;

    $pdo->beginTransaction();
    try {
        $now = new CustomDateTime();
        $prd_with_keys = get_order_with_keys();
        $order = new OrderModel(array(
            'method'    => 'test-method',
            'order_number' => generate_uuid(),
            'user_id'   => CURRENT_USER->get_id(),
            'created_at'    => $now
        ));
        $order->save();

        $keys_ids = array_map(fn($item) => $item['key_id'], $prd_with_keys);
        $keys = KeyModel::filter(array(
            [
                'name'  => 'obj.id',
                'type'  => 'IN',
                'value' => $keys_ids
            ]
        ), count: count($keys_ids), block_for_update: true);
        foreach ($keys as $key) {
            if(!$key->is_available()) {
                throw new BadRequest400('Product is already bought');
            }
            $key->field_order_id = $order->get_id();
            $key->field_bought_at = $now;
            $key->save();
        }
        $pdo->commit();
    }
    catch(Exception $ex) {
        $pdo->rollBack();
        if(DEBUG_MODE) {
            print_r($ex);
            exit;
        }
        else {
            throw new ServerError500();
        }
    }

    // Clear session for cart and order
    $_SESSION['cart'] = array();
    set_order_data(array());

    redirect_to(get_permalink('order:success'));
}
function method_stripe_init() {
    $prd_with_keys = get_order_with_keys();

    \Stripe\Stripe::setApiKey(STRIPE_SECRET_KEY);
    $line_items = [];

    foreach ($prd_with_keys as $item) {
        $line_items[] = [
            'price_data' => [
                'currency' => 'usd',
                'product_data' => [
                    'name' => $item['product']->field_title,
                ],
                'unit_amount' => (int) round($item['product']->get_price() * 100),
            ],
            'quantity' => 1,
        ];
    }


    $session = \Stripe\Checkout\Session::create([
        'payment_method_types' => ['card'],
        'line_items' => $line_items,
        'mode' => 'payment',
        'payment_intent_data' => [
            'capture_method' => 'manual',
            'metadata' => [
                'user_id' => CURRENT_USER->get_id(),
                'key_ids' => implode(',', array_map(fn($item) => $item['key_id'], $prd_with_keys)),
            ],
        ],
        'success_url' => get_permalink('order:success'),
        'cancel_url' => get_permalink('order:cancel'),
    ]);
    // Clear session for cart and order
    $_SESSION['cart'] = array();
    set_order_data(array());
    
    redirect_to($session->url);
}