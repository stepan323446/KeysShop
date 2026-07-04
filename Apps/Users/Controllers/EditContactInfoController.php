<?php

namespace KeysShop\Apps\Users\Controllers;

use Exception;
use KeysShop\Includes\BaseController;
use KeysShop\Includes\Model\ValidationError;
use KeysShop\Includes\Routing\HttpExceptions\Unauthorized401;



class EditContactInfoController extends BaseController {
    protected string $template_name = APPS_PATH . '/Users/Templates/sect_information/edit_information.php';
    protected ?string $allow_role = 'user';

    protected function post() {
        if(!CURRENT_USER)
            throw new Unauthorized401();

        $username = $_POST['username'] ?? '';
        $fname = $_POST['fname'] ?? '';
        $lname = $_POST['lname'] ?? '';

        $curr_user = CURRENT_USER;

        $curr_user->field_username = $username;
        $curr_user->field_fname = $fname;
        $curr_user->field_lname = $lname;

        try {
            $curr_user->save();
            redirect_to(get_permalink('users:profile'));
        }
        catch(ValidationError $ex) {
            $this->context['error_form'] = $ex;
            return;
        }
        catch(Exception $ex) {
            $this->context['error_message'] = 'Unexpected error';
            return;
        }
    }
}