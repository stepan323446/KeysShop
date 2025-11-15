<?php
require_once APPS_PATH . "/products/models.php";

function get_ajax_error($message, $error_code = 500) {
    http_response_code($error_code);

    $error = array();
    $error['error'] = $message;

    return json_encode($error, JSON_PRETTY_PRINT);
}

/**
 * Ajax actions
 */

/**
 * Wishlist button
 * @param array $args
 * @return string
 */
function ajax_wishlist($args) {
    $result = array();

    // User is not authorized
    if(empty(CURRENT_USER))
        return get_ajax_error("User is not authorized", 401); // 401 Unathourized

    // Product id is not number or empty
    if (!isset($args['product_id']) || !is_numeric($args['product_id'])) {
        return get_ajax_error("Product ID is invalid", 400); // 400 Bad Request
    }

    $product_id = (int)$args['product_id'];
    
    $wishlist = WishlistModel::get(
        array(
            [
                'name'  => 'obj.product_id',
                'value' => $product_id
            ],
            [
                'name'  => 'obj.user_id',
                'value' => CURRENT_USER->get_id()
            ]
        )
    );

    // If we have wishlist item, remove from list
    if($wishlist) {
        $wishlist->delete();
        $result['message'] = "The product has been removed from the wishlist";
    }
    else {
        $product = ProductModel::get(
            array(
                [
                    'name'  => 'obj.id',
                    'value' => $product_id
                ]
            )
        );
        if(empty($product))
            return get_ajax_error("Product not found", 404);

        // Save to list
        $wishlist = new WishlistModel(array(
            'product_id'    => $product->get_id(),
            'user_id'       => CURRENT_USER->get_id()
        ));
        $wishlist->save();

        $result['message'] = "The product has been added to the wishlist";
    }
    

    return json_encode($result, JSON_PRETTY_PRINT);
}

/**
 * Ajax search
 * @param array $args
 * @return string
 */
function ajax_search($args) {
    $result = array();

    $search_str = $args['search'] ?? '';

    $objects = ProductModel::filter(
        array(),
        array(),
        5,
        'AND',
        0,
        $search_str
    );

    $result['objects'] = array_map(function($product) {
        return array(
            'id'        => $product->get_id(),
            'title'     => $product->field_title,
            'image'     => $product->get_poster_url(),
            'price'     => $product->get_price_format(),
            'discount'  => $product->get_discount(),
            'permalink' => $product->get_absolute_url(),
            'platform_name' => $product->platform_title,
            'platform_icon' => $product->platform_icon,
            'is_available' => $product->is_available()
        );
    }, $objects);

    return json_encode($result, JSON_PRETTY_PRINT);
}
function ajax_cart($args) {
    $result = array();

    $type_to_cart = $args['type'] ?? 'info';

    // Validation for product_id
    $product_id = $args['product_id'] ?? null;
    if((!isset($product_id) || !is_numeric($product_id)) && $type_to_cart != 'info')
        return get_ajax_error("Product ID is invalid", 400);

    // Try to find current product
    $product = null;
    if($type_to_cart != 'info') {
        $product_id = (int)$product_id;
        $product = ProductModel::get(
            array(
                [
                    'name'      => 'obj.id',
                    'value'     => $product_id
                ]
            )
        );
        if(empty($product_id))
            return get_ajax_error("Product not found", 404);    
    }
    

    // Add, Remove or just show information
    if(!isset($_SESSION['cart']))
        $_SESSION['cart'] = array();

    switch ($type_to_cart) {
        case 'add':
            if(count($_SESSION['cart']) >= 10)
                return get_ajax_error("There should be no more than 10 products in the shopping cart.", 400);
            if(in_array($product->get_id(), $_SESSION['cart']))
                return get_ajax_error("The product is already in the shopping cart.", 400);

            $_SESSION['cart'][$product->get_id()] = $product->get_id();
            $result['message'] = "Product has been added to the cart";
            break;
        
        case 'remove':
            if (isset($_SESSION['cart'][$product_id])) {
                unset($_SESSION['cart'][$product_id]);
                $result['message'] = "Product has been removed from the cart";
            }
            break;
            
        case 'info':
            break;

        default:
            return get_ajax_error("Invalid cart operation.", 400);
    }

    // Show current information from cart
    $result['cart_information'] = get_cart_information();

    return json_encode($result, JSON_PRETTY_PRINT);
}