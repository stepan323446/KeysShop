<?php
namespace Apps\Admin\Controllers\Singles;

use Apps\Admin\Controllers\Abstract\AdminSingleController;

class AdminOrderController extends AdminSingleController {
    protected string $model_сlass_name = "Apps\Order\Models\OrderModel";
    protected string $field_title = 'field_order_number';
    protected string $verbose_name = 'order';
    protected ?string $object_router_name = 'admin:order';
    protected array $component_widgets = ['the_order_info'];
    protected bool $can_save = false;

    protected array $fields = array(
        [
            'model_field' => 'method',
            'input_type' => 'text',
            'input_attrs' => ['required']
        ],
        [
            'model_field' => 'order_number',
            'input_type' => 'text',
            'input_attrs' => ['required']
        ],
        [
            'model_field' => 'created_at',
            'input_type' => 'text',
            'input_attrs' => ['required']
        ],
        [
            'model_field' => 'user_id',
            'input_type' => 'text',
            'input_attrs' => ['required']
        ]
    );
}