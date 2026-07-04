<?php
namespace Apps\Admin\Controllers\Lists;

use Apps\Admin\Controllers\Abstract\AdminListController;

class AdminOrderListController extends AdminListController {
    protected string $model_сlass_name = "Apps\Order\Models\OrderModel";
    protected array $table_fields = array(
        'Order number'      => 'field_order_number',
        'Method'            => 'field_method',
        'Total price'        => 'get_total_price()',
        'Buyer'             => 'get_buyer_username()',
        'Created at'        => 'field_created_at'
    );
    protected string $verbose_name = "order";
    protected string $verbose_name_multiply = "orders";
    protected array $sort_by = ['-obj.created_at'];
    protected string $single_router_name = 'admin:order';
}