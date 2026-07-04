<?php
namespace KeysShop\Apps\Admin\Controllers\Singles;

use KeysShop\Apps\Admin\Controllers\Abstract\AdminSingleController;
use KeysShop\Apps\Products\Models\TaxonomyModel;

class AdminTaxonomyController extends AdminSingleController {
    protected string $model_сlass_name = "KeysShop\Apps\Products\Models\TaxonomyModel";
    protected string $field_title = 'field_name';
    protected string $verbose_name = 'taxonomy';
    protected ?string $object_router_name = 'admin:tax';

    protected array $fields = array(
        [
            'model_field' => 'name',
            'input_type' => 'text',
            'input_attrs' => ['required']
        ],
        [
            'model_field' => 'slug',
            'input_type' => 'text',
            'input_attrs' => ['required']
        ],
        [
            'model_field' => 'type',
            'input_type' => 'select',
            'input_attrs' => ['required'],
            'input_values' => TaxonomyModel::TYPES
        ],
        [
            'model_field' => 'icon_html',
            'input_label' => 'Icon HTML',
            'input_type' => 'textarea'
        ],
        [
            'model_field' => 'background_color',
            'input_label' => 'Background color',
            'input_type' => 'color'
        ]
    );
}
