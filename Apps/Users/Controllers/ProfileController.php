<?php
namespace Apps\Users\Controllers;

use Includes\BaseController;



class ProfileController extends BaseController {
    protected string $template_name = APPS_PATH . '/Users/Templates/profile.php';
    protected ?string $allow_role = 'user';
}