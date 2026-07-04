<?php
namespace Apps\Users\Controllers;

use Apps\Users\Models\UserModel;
use Includes\BaseController;



class LoginController extends BaseController {
    protected string $template_name = APPS_PATH . '/Users/Templates/login.php';

    protected function post() {
        $username = $_POST['username'] ?? null;
        $password = $_POST['password'] ?? null;

        if(!empty($username) && !empty($password)) {
            // Try to find user with $username
            $user = UserModel::get(array(
                [
                    'name' => 'obj.username',
                    'type' => '=',
                    'value' => $username
                ]
            ));
            if(empty($user)) {
                $this->context['error_message'] = 'User not found';
                return;
            }
            // Check password is correct or not
            $is_correct_pass = $user->password_verify($password);
            if($is_correct_pass === true) {
                set_auth_user($user->get_id());
                redirect_to(get_permalink('index:home'));
            }
            else {
                $this->context['error_message'] = 'You entered an incorrect password.';
                return;
            }

        }
    }

    protected function distinct() {
        // If current user is authorized, redirect to homepage
        if(!empty(CURRENT_USER)) {
            $home = get_permalink('index:home');
            redirect_to($home);
        }
    }
}