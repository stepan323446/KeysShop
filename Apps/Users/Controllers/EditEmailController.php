<?php

namespace Apps\Users\Controllers;

use Exception;
use Includes\BaseController;
use Includes\Model\ValidationError;
use Includes\Routing\HttpExceptions\Unauthorized401;



class EditEmailController extends BaseController {
    protected string $template_name = APPS_PATH . '/Users/Templates/sect_information/edit_email.php';
    protected ?string $allow_role = 'user';

    protected function post() {
        if(!CURRENT_USER)
            throw new Unauthorized401();

        $email = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? '';

        $curr_user = CURRENT_USER;

        $curr_user->field_email = $email;

        try {
            if($curr_user->password_verify($password)) {
                $curr_user->save();
                redirect_to(get_permalink('users:profile'));
            }
            else {
                $this->context['error_message'] = 'Invalid password';    
            }
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