<?php

use KeysShop\Apps\Contacts\Models\FeedbackModel;
use KeysShop\Apps\Order\Models\OrderModel;
use KeysShop\Apps\Products\Models\KeyModel;
use KeysShop\Apps\Products\Models\ProductModel;
use KeysShop\Apps\Users\Models\UserModel;

function the_admin_header(string $title, string $feedback_count) {
    require_once APPS_PATH . '/Admin/Templates/components/admin-header.php';
}

function the_admin_footer() {
    require_once APPS_PATH . '/Admin/Templates/components/admin-footer.php';
}

/*
 * Widgets in the single object page
 */
function the_last_feedbacks(UserModel $user) {
    $last_feedbacks = FeedbackModel::filter(
        array(
            [
                'name'  => 'obj.email',
                'type'  => '=',
                'value' => $user->field_email
            ]
        ),
        ['-obj.created_at'],
        10,
        'OR'
    );
    if(empty($last_feedbacks))
        return;
    
    require_once APPS_PATH . '/Admin/Templates/widgets/last_feedbacks.php';
}
function the_related_user(FeedbackModel $feedback) {
    $related_user = UserModel::get(
        array(
            [
                'name' => 'obj.email',
                'type' => '=',
                'value' => $feedback->field_email
            ]
        )
    );
    if(empty($related_user))
        return;

    require_once APPS_PATH . '/Admin/Templates/widgets/feedback_related_user.php';
}
function the_list_keys_by_product(ProductModel $product) {
    $prod_id = $product->get_id();

    $keys = KeyModel::filter(
        array(
            [
                'name'  => 'obj.product_id',
                'type'  => '=',
                'value' => $prod_id
            ],
            [
                'name'  => 'obj.order_id',
                'type'  => 'IS',
                'value' => null
            ]
        ),
        ['obj.price']
    );

    require_once APPS_PATH . '/Admin/Templates/widgets/list_keys_by_product.php';
}
function the_related_product(KeyModel $key) {
    $product = ProductModel::get(
        array(
            [
                'name'  => 'obj.id',
                'value' => $key->field_product_id
            ]
        )
    );
    require_once APPS_PATH . '/Admin/Templates/widgets/related_product.php';
}
function the_order_info(OrderModel $order) {
    $keys = KeyModel::filter(
        array(
            [
                'name'      => 'obj.order_id',
                'value'     => $order->get_id()
            ]
        )
    );
    
    require_once APPS_PATH . '/Admin/Templates/widgets/order_info.php';
}