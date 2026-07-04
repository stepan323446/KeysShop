<?php
namespace KeysShop\Apps\Order\Controllers;

use KeysShop\Includes\BaseController;

class OrderSuccessController extends BaseController {
    protected string $template_name = APPS_PATH . '/Order/Templates/payment-success.php';
}