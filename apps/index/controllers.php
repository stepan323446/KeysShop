<?php
require_once INCLUDES_PATH . '/base_controller.php';
define('INDEX_TEMPLATES', APPS_PATH . '/index/templates');
define('CURR_APP_PATH', APPS_PATH . '/index');

require_once APPS_PATH . '/products/models.php';
require_once APPS_PATH . '/products/components.php';

class HomepageController extends BaseController {
    protected $template_name = INDEX_TEMPLATES . '/index.php';

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
class PrivacyController extends BaseController {
    protected $template_name = INDEX_TEMPLATES . '/privacy_policy.php';
}
class AboutController extends BaseController {
    protected $template_name = INDEX_TEMPLATES . '/about.php';
}
class TermsController extends BaseController {
    protected $template_name = INDEX_TEMPLATES . '/terms.php';
}
class FaqController extends BaseController {
    protected $template_name = INDEX_TEMPLATES . '/faq.php';
}
class ErrorController extends BaseController {
    protected $template_name = INDEX_TEMPLATES . '/error.php';
}

?>