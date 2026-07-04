<?php
namespace Apps\Index\Controllers;

use Apps\Products\Models\ProductModel;
use Includes\BaseController;

require_once APPS_PATH . '/Products/components.php';

class HomepageController extends BaseController {
    protected string $template_name = APPS_PATH . '/Index/Templates/index.php';

    public function get_context_data() {
        $context = parent::get_context_data();
        
        $context['hot_products'] = ProductModel::filter(
            array(
                [
                    'name'  => 'keys_count',
                    'type'  => '>',
                    'value' => 0,
                    'is_having' => true
                ]
            ),
            ['-obj.sales'],
            10
        );
        $context['comming_soon'] = ProductModel::filter(
            array(
                [
                    'name'  => 'keys_count',
                    'value' => 0,
                    'is_having' => true
                ]
            ),
            ['-obj.sales'],
            5
        );
        $context['latest_changes'] = ProductModel::filter(
            array(
                [
                    'name'  => 'keys_count',
                    'type'  => '>',
                    'value' => 0,
                    'is_having' => true
                ]
            ),
            ['-obj.updated_at'],
            7
        );

        return $context;
    }
}