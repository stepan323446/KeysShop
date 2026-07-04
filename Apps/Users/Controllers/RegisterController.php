<?php
namespace Apps\Users\Controllers;

use Exception;
use Apps\Users\Models\UserModel;
use Includes\BaseController;
use Includes\Model\ValidationError;



class RegisterController extends BaseController {
    protected string $template_name = APPS_PATH . '/Users/Templates/register.php';

    protected function distinct() {
        // If current user is authorized, redirect to homepage
        if(!empty(CURRENT_USER)) {
            $home = get_permalink('index:home');
            redirect_to($home);
        }
    }

    protected function post() {
        $username = $_POST['username'] ?? null;
        $email = $_POST['email'] ?? null;
        $pass = $_POST['password'] ?? null;
        $repeat = $_POST['repeat'] ?? null;

        $fname = $_POST['fname'] ?? '';
        $lname = $_POST['lname'] ?? '';

        // If password is not valid
        if(UserModel::valid_password($pass) !== true) {
            $context['error_form'] = new ValidationError(UserModel::valid_password($pass));
            return;
        }
        if($pass != $repeat) {
            $context['error_form'] = new ValidationError(["Passwords don't match"]);
            return;
        }
        $pass_hash = UserModel::password_hash($pass);
        
        $new_user = new UserModel(array(
            'username' => $username,
            'email' => $email,
            'password' => $pass_hash,
            'fname' => $fname,
            'lname' => $lname
        ));
        try {
            $new_user->save();
            set_auth_user($new_user->get_id());
            redirect_to(get_permalink('index:home'));
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