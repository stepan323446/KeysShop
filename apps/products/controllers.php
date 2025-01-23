<?php
require_once INCLUDES_PATH . '/base_controller.php';
define('PROD_TEMPLATES', APPS_PATH . '/products/templates');

class SingleProductController extends BaseController
{
    protected $template_name = PROD_TEMPLATES . '/single.php';
}

