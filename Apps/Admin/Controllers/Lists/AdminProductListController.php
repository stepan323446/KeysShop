<?php
namespace KeysShop\Apps\Admin\Controllers\Lists;

use KeysShop\Apps\Admin\Controllers\Abstract\AdminListController;

class AdminProductListController extends AdminListController {
    protected string $model_сlass_name = "KeysShop\Apps\Products\Models\ProductModel";
    protected array $table_fields = array(
        'Title'             => 'field_title',
        'Original price'    => 'field_original_price',
        'Price'             => 'get_price_format()',
        'Available Keys'    => 'keys_count',
        'Platform'          => 'platform_title',
        'Created at'        => 'field_created_at'
    );
    protected string $verbose_name = "product";
    protected string $verbose_name_multiply = "products";
    protected string $single_router_name = 'admin:product';
    protected ?string $create_router_name = 'admin:product-new';
    protected array $sort_by = [ '-keys_count' ];
}