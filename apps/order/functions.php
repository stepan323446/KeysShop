<?php
require_once APPS_PATH . '/products/models.php';

function method_test_method() {
    $order_data = get_order_data();
    $product_key_price = array();

    foreach ($order_data['products'] as $product) {
        $product_key_price[$product['id']] = (float)$product['price'];
    }
    $products = ProductModel::filter(
        array(
            [
                'name'      => 'obj.id',
                'type'      => 'IN',
                'value'     => array_keys($product_key_price)
            ]
            ),
            array(),
            10,
            'AND',
            0,
            '',

            array(
                [
                    'field'   => [
                        "(SELECT tb1.id FROM product_keys tb1 WHERE tb1.product_id = obj.id AND tb1.order_id IS NULL ORDER BY tb1.price ASC LIMIT 1) AS min_price_key_id"
                    ]
                ]
            )
    );
    $keys_id = array();
    foreach ($products as $product) {
        if($product->get_price() != $product_key_price[$product->get_id()]) {
            throw new BadRequestHttp400('Prices have been updated');
        }
        $keys_id[] = $product->min_price_key_id;
    }
    $order = new OrderModel(array(
        'method'    => 'test-method',
        'order_number' => generate_uuid(),
        'user_id'   => CURRENT_USER->get_id(),
        'created_at'    => new CustomDateTime()
    ));
    $order->save();
    
    // Save keys to order
    $sql = "UPDATE product_keys 
        SET order_id = ?, 
            bought_at = ? 
        WHERE id IN (" . implode(',', array_fill(0, count($keys_id), '?')) . ")";
    $params = array_merge([$order->get_id(), (string)$order->field_created_at], $keys_id);
    $result = db_prepare($sql, $params);

    // Clear session for cart and order
    $_SESSION['cart'] = array();
    set_order_data(array());

    redirect_to(get_permalink('order:success'));

    exit;
}