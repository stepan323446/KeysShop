<?php
namespace KeysShop\Apps\Admin\Controllers\Lists;

use KeysShop\Apps\Admin\Controllers\Abstract\AdminListController;

class AdminKeyListController extends AdminListController {
    protected string $model_сlass_name = "KeysShop\Apps\Products\Models\KeyModel";
    protected array $table_fields = array(
        'Code'              => 'show_secret_key()',
        'Price'             => 'field_price',
        'Original price'    => 'field_original_price',
        'Is available'      => 'is_available()',
        'Created at'        => 'field_created_at',
    );
    protected string $verbose_name = "key";
    protected string $verbose_name_multiply = "keys";
    protected string $single_router_name = 'admin:product-key';
    protected array $sort_by = ['obj.price'];

    public function custom_filter_fields() {
        $id_product = $this->url_context['url_1'];
        return array(
            [
                'name'  => 'obj.product_id',
                'value' => $id_product
            ]
        );
    }
}