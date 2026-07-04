<?php
namespace Apps\Users\Controllers;

use Includes\BaseController;



class LogoutController extends BaseController {
    protected string $template_name = APPS_PATH . '/Users/Templates/forgot_password.php';
    
    protected function distinct() {
        logout();
        redirect_to(get_permalink('index:home'));
    }
}
