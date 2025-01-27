<?php
require_once INCLUDES_PATH . '/base_controller.php';
require_once APPS_PATH . '/products/components.php';
require_once APPS_PATH . '/products/models.php';

define('PROD_TEMPLATES', APPS_PATH . '/products/templates');
define('CATALOG_MAX_PRODUCTS', 20);



class CatalogController extends BaseController {
    protected $template_name = PROD_TEMPLATES . '/catalog.php';

    public function get_context_data() {
        $context = parent::get_context_data();

        $context["page"] = isset($_GET['page']) ? (int)$_GET['page'] : 1;

        // Show all taxonomies
        $context['filters'] = array();
        $taxonomies = TaxonomyModel::filter(
            array(),
            array(),
            200
        );
        // Add taxonomies to specific category to display
        foreach ($taxonomies as $obj) {
            if(!isset($context['filters'][$obj->field_type]))
                $context['filters'][$obj->field_type] = array();


            $context['filters'][$obj->field_type][] = $obj;
        }

        // filters
        $filters = array();

        $filters['obj.platform_id'] = is_array($_GET['platform']) ? $_GET['platform'] : null;
        $filters['obj.region_id'] = is_array($_GET['region']) ? $_GET['region'] : null;

        $fields = array();
        foreach ($filters as $obj_field => $selected_values) {
            if(!isset($selected_values))
                continue;

            $fields[] = array(
                'name'      => $obj_field,
                'type'      => 'IN',
                'value'     => $selected_values
            );
        }
        $only_available = isset($_GET['only_available']) ? true : false;
        if($only_available) {
            $fields[] = array(
                'name'      => 'keys_count',
                'type'      => '>',
                'value'     => 0,
                'is_having' => true
            );
        }

        // Sort by
        $sort_result = '';
        
        $sort_selected = $_GET['sort-by'] ?? 'date-created';
        $sort_type = $_GET['sort-type'] ?? 'asc';

        switch ($sort_selected) {
            case 'title':
                $sort_result = 'obj.title';
                break;
            case 'price':
                $sort_result = 'result_price';
                break;
            case 'date-updated':
                $sort_result = 'obj.updated_at';
                break;
            case 'date-created':
            default:
                $sort_result = 'obj.created_at';
                break;
        }
        $context['sort_by'] = $sort_selected;
        $context['sort_type'] = $sort_type;
        if($sort_type == 'desc')
            $sort_result = '-' . $sort_result;

        // Show products
        $context['products'] = ProductModel::filter(
            $fields,
            [$sort_result],
            CATALOG_MAX_PRODUCTS,
            'AND',
            calc_page_offset(CATALOG_MAX_PRODUCTS, $context["page"]),
            $_GET['s'] ?? ''
        );
        $context['products_count'] = ProductModel::count(
            $fields,
            $_GET['s'] ?? ''
        );

        return $context;
    }
}
class SingleProductController extends BaseController
{
    protected $template_name = PROD_TEMPLATES . '/single.php';

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
            throw new NotFoundHttp404('Product not found');

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

