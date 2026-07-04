<?php
namespace Apps\Admin\Controllers\Lists;

use Apps\Admin\Controllers\Abstract\AdminListController;

class AdminTaxonomyListController extends AdminListController {
    protected string $model_сlass_name = "Apps\Products\Models\TaxonomyModel";
    protected array $table_fields = array(
        'Name' => 'field_name',
        'Slug' => 'field_slug',
        'Type' => 'field_type',
    );
    protected string $single_router_name = 'admin:tax';
    protected ?string $create_router_name = 'admin:tax-new';
    protected string $verbose_name = "taxonomy";
    protected string $verbose_name_multiply = "taxonomies";
    protected array $sort_by = ['obj.type', 'obj.name'];
}