<?php
require_once INCLUDES_PATH . '/base_controller.php';

define('ORDER_TEMPLATES', APPS_PATH . '/order/templates');

require_once APPS_PATH . '/order/models.php';


class OrderController extends BaseController {
    protected $template_name = ORDER_TEMPLATES . '/order.php';
    protected $allow_role = "user";

    protected function distinct() {
        if(empty($_SESSION['cart']))
            throw new BadRequestHttp400("Your shopping cart is empty");
    }
    protected function post_before_context() {
        require_once APPS_PATH . '/order/functions.php';

        $method = $_POST['method'] ?? null;
        switch($method) {
            case 'test-method':
                method_test_method();
                break;

            default:
                throw new BadRequestHttp400();
        }
    }
    public function get_context_data() {
        $context = parent::get_context_data();

        set_order_data(get_cart_information());
        $context['order'] = get_order_data();

        return $context;
    }
}

class OrderSuccessController extends BaseController {
    protected $template_name = ORDER_TEMPLATES . '/payment-success.php';
}