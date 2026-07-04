<?php
namespace Apps\Admin\Controllers\Singles;

use Apps\Admin\Controllers\Abstract\AdminSingleController;
use Apps\Products\Models\TaxonomyModel;

class AdminProductController extends AdminSingleController {
    protected string $model_сlass_name = "Apps\Products\Models\ProductModel";
    protected string $field_title = 'field_title';
    protected string $verbose_name = "product";
    protected ?string $object_router_name = 'admin:product';
    protected array $component_widgets = ['the_list_keys_by_product'];

    protected array $fields = array (
        [
            'model_field' => 'title',
            'input_type'  => 'text',
            'input_attrs' => ['required']
        ],
        [
            'model_field' => 'slug',
            'input_type'  => 'text',
            'input_attrs' => ['required']
        ],
        [
            'model_field' => 'excerpt',
            'input_type'  => 'text',
            'input_attrs' => ['required']
        ],
        [
            'model_field' => 'description',
            'input_type'  => 'textarea',
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
            'model_field' => 'original_url',
            'input_type'  => 'text',
            'input_attrs' => ['required']
        ],
        [
            'model_field' => 'original_price',
            'input_type'  => 'number',
            'input_attrs' => ['required']
        ],
        [
            'model_field' => 'edition',
            'input_type'  => 'text'
        ],
        [
            'model_field' => 'poster_url',
            'input_type'  => 'image',
            'input_label' => 'Poster',
        ],
        [
            'model_field' => 'image_url',
            'input_type'  => 'image',
            'input_label' => 'Image',
        ]
        
    );
    public function __construct($is_new = false) {
        parent::__construct($is_new);
        $this->fields[] = [
            'model_field' => 'platform_id',
            'input_type' => 'select',
            'input_label' => 'Platforms',
            'input_attrs' => ['required'],
            'input_values' => TaxonomyModel::get_type_values('platform', true)
        ];
        $this->fields[] = [
            'model_field' => 'region_id',
            'input_type' => 'select',
            'input_label' => 'Region',
            'input_attrs' => ['required'],
            'input_values' => TaxonomyModel::get_type_values('region', true)
        ];
    } 
}