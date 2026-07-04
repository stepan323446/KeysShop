<?php
namespace KeysShop\Apps\Users\Controllers;

use KeysShop\Apps\Products\Models\{
    KeyModel,
    ProductModel
};
use KeysShop\Includes\BaseController;


define('MAX_ORDER_PER_PAGE', 20);

class MyOrdersController extends BaseController {
    protected string $template_name = APPS_PATH . '/Users/Templates/orders.php';
    protected ?string $allow_role = 'user';

    public function get_context_data() {
        $context = parent::get_context_data();

        $page = $_GET['page'] ?? 1;
        $page = (int)$page;
        $context['page'] = $page;

        $filter_fields = array(
            [
                'name'      => 'buyer_id',
                'value'     => CURRENT_USER->get_id(),
                'is_having' => true
            ]
        );
        $key_add_fields = array(
            [
                "field"         => [
                    "ord.order_number AS order_number",
                    "ord.method AS order_method",
                ],
                "join_table"    => "orders ord ON ord.id = obj.order_id"
            ]
            );

        $context['keys'] = KeyModel::filter(
            $filter_fields,
            ['-obj.bought_at'],
            MAX_ORDER_PER_PAGE,
            'AND',
            calc_page_offset(MAX_ORDER_PER_PAGE, $page),
            '',
            $key_add_fields
        );
        $context['keys_count'] = KeyModel::count($filter_fields, '', $key_add_fields);

        $keys_ids = array_map(function($key) {
            return $key->field_product_id;
        }, $context['keys']);

        if(!empty($keys_ids)) {
            $products = ProductModel::filter(
                array(
                    [
                        'name'      => 'obj.id',
                        'type'      => 'IN',
                        'value'     => $keys_ids
                    ]
                ),
                array(),
                MAX_ORDER_PER_PAGE,
                'AND',
                0,
                '',
                array(
                    [
                        "field"         => [
                            "tb3.name AS region_title",
                        ],
                        "join_table"    => "taxonomies tb3 ON tb3.id = obj.region_id"
                    ]
                )
            );
        }
        $products_key_val = array();
        foreach ($products as $product) {
            $products_key_val[$product->get_id()] = $product;
        }
        $context['products'] = $products_key_val;

        return $context;
    }
}