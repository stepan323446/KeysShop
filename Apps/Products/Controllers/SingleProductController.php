<?php
namespace Apps\Products\Controllers;

use Apps\Products\Models\{
    ProductModel,
    TaxonomyModel
};
use Includes\BaseController;
use Includes\Routing\HttpExceptions\NotFound404;

class SingleProductController extends BaseController
{
    protected string $template_name = APPS_PATH . '/Products/Templates/single.php';

    protected function get_model() {
        if(isset($this->__model))
            return $this->__model;

        $this->__model = ProductModel::get(
            array(
                [
                    'name'      => 'obj.slug',
                    'value'     => $this->url_context['url_1'] // slug
                ]
            )
        );
        if(empty($this->__model))
            throw new NotFound404('Product not found');

        return $this->__model;
    }

    public function get_context_data() {
        $context = parent::get_context_data();

        $context['product'] = $this->get_model();
        $context['region'] = TaxonomyModel::get(
            array(
                [
                    'name'  => 'obj.id',
                    'value' => $context['product']->field_region_id
                ]
            )
        );
        $context['recommendations'] = ProductModel::filter(
            array(
                [
                    'name'  => 'keys_count',
                    'type'  => '>',
                    'value' => 0,
                    'is_having' => true
                ]
                ),
                ['-obj.sales'],
                5
        );

        return $context;
    }
}