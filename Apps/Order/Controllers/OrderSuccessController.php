<?php
namespace Apps\Order\Controllers;

use Includes\BaseController;

class OrderSuccessController extends BaseController {
    protected string $template_name = APPS_PATH . '/Order/Templates/payment-success.php';
}