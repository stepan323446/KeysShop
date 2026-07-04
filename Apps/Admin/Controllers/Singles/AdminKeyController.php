<?php
namespace Apps\Admin\Controllers\Singles;

use Apps\Admin\Controllers\Abstract\AdminSingleController;


class AdminKeyController extends AdminSingleController {
    protected string $model_сlass_name = "Apps\Products\Models\KeyModel";
    protected string $field_title = 'field_created_at';
    protected string $verbose_name = "key";
    protected ?string $object_router_name = 'admin:product-key';
    protected array $component_widgets = ['the_related_product'];
    protected array $fields = array(
        [
            'model_field' => 'product_id',
            'input_type'  => 'number',
            'dynamic_save' => false,
            'input_label' => 'Product id',
            'input_attrs' => ['required', 'disabled']
        ],
        [
            'model_field' => 'key_code',
            'input_type'  => 'text',
            'input_label' => 'Key',
            'input_attrs' => ['required']
        ],
        [
            'model_field' => 'price',
            'input_type'  => 'number',
            'input_attrs' => ['required']
        ],
        [
            'model_field' => 'original_price',
            'input_type'  => 'number',
            'input_label' => 'Original price',
            'input_attrs' => ['required']
        ],
        [
            'model_field' => 'created_at',
            'input_type' => 'text',
            'dynamic_save'  => false,
            'input_label' => 'Created at',
            'input_attrs' => ['disabled']
        ],
        [
            'model_field' => 'order_id',
            'input_type'  => 'number',
            'dynamic_save' => false,
            'input_label' => 'Order id',
            'input_attrs' => ['disabled']
        ],
        [
            'model_field' => 'bought_at',
            'input_type' => 'text',
            'dynamic_save'  => false,
            'input_label' => 'Bought at',
            'input_attrs' => ['disabled']
        ],
    );
    protected function get_model() {
        $model = parent::get_model();

        if($this->context['is_new'])
            $model->field_product_id = (int)$this->url_context['url_1'];

        return $model;
    }
}