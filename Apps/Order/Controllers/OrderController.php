<?php
namespace Apps\Order\Controllers;

use Includes\BaseController;
use Includes\Routing\HttpExceptions\BadRequest400;

class OrderController extends BaseController {
    protected string $template_name = APPS_PATH . '/Order/Templates/order.php';
    protected ?string $allow_role = "user";

    protected function distinct() {
        if(empty($_SESSION['cart']))
            throw new BadRequest400("Your shopping cart is empty");
    }
    protected function post_before_context() {
        require_once APPS_PATH . '/Order/functions.php';

        $method = $_POST['method'] ?? null;
        switch($method) {
            case 'test-method':
                method_test_method();
                break;

            default:
                throw new BadRequest400();
        }
    }
    public function get_context_data() {
        $context = parent::get_context_data();

        set_order_data(get_cart_information());
        $context['order'] = get_order_data();

        return $context;
    }
}