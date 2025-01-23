<?php
require_once INCLUDES_PATH . '/base_controller.php';
require_once APPS_PATH . '/users/models.php';
require_once APPS_PATH . '/users/functions.php';
require_once APPS_PATH . '/users/components.php';

define('USER_TEMPLATES', APPS_PATH . '/users/templates');


class LoginController extends BaseController {
    protected $template_name = USER_TEMPLATES . '/login.php';

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
class RegisterController extends BaseController {
    protected $template_name = USER_TEMPLATES . '/register.php';

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
class ForgotPasswordController extends BaseController {
    protected $template_name = USER_TEMPLATES . '/forgot_password.php';

    protected function distinct() {
        // If current user is authorized, redirect to homepage
        if(!empty(CURRENT_USER)) {
            $home = get_permalink('index:home');
            redirect_to($home);
        }
    }
    protected function post() {
        $email = $_POST['email'];
        if(!isset($email)) {
            $this->context['error_message'] = 'Enter your e-mail address';
            return;
        }
        $user = UserModel::get(array(
            [
                'name' => 'obj.email',
                'type' => '=',
                'value' => $email
            ]
        ));
        // If user is exists
        if($user) {
            try
            {   
                // Check if there was a previous request.
                if(!RecoveryPassModel::is_cooldown_available($user->get_id())) {
                    $this->context['error_message'] = 'You have already sent a request recently. Check your email or wait ' . RecoveryPassModel::get_cooldown_modifier() . '.';
                    return;
                }
                
                // Send new recovery password email
                $recoveryModel = new RecoveryPassModel(array(
                    'user_id' => $user->get_id(),
                    'recovery_slug' => generate_uuid()
                ));
                $recoveryModel->save();

                $link_to_recovery = get_permalink('users:reset', [$recoveryModel->field_recovery_slug]);
                

                $body = get_recovery_email_template(
                    $user->get_public_name(), 
                    $link_to_recovery);

                $altBody = get_recovery_email_alt_template(
                    $user->get_public_name(), 
                    $link_to_recovery);;

                send_email(
                    'Reset Password',
                    $body,
                    $altBody,
                    $user->field_email,
                    $user->get_public_name()
                );
                $this->context['success_message'] = 'The email was sent successfully';
            }
            catch(Exception $ex)
            {
                $this->context['error_message'] = 'Unknown error. Please try again.';    
            }
        }
        else {
            $this->context['error_message'] = 'The user with this e-mail was not found.';
        }
    }
}

class ResetPasswordController extends BaseController {
    protected $template_name = USER_TEMPLATES . '/reset_password.php';

    protected function get_model() {
        if(isset($this->__model))
            return $this->__model;

        $this->__model = RecoveryPassModel::get(array(
            [
                'name' => 'obj.recovery_slug',
                'type' => '=',
                'value' => $this->context['url_1']
            ]
        ));
        return $this->__model;
    }

    protected function distinct() {
        $recoveryModel = $this->get_model();

        // Check that recoveryModel is available or not
        if($recoveryModel) {
            if(!$recoveryModel->is_available())
                $this->context['not_available'] = 'The link is no longer valid. Please try again';
        }
        else
        {
            $this->context['not_available'] = 'The link does not exist';
        }
        
    }
    protected function post() {
        $pass = $_POST['password'] ?? null;
        $repeat = $_POST['repeat'] ?? null;

        $recovery_model = $this->get_model();

        // Validate password
        if(empty($pass) || empty($repeat)) {
            $this->context['error_form'] = new ValidationError(["You did not provide a password in the fields."]);
            return;
        }
        if($pass != $repeat) {
            $this->context['error_form'] = new ValidationError(["Passwords don't match"]);
            return;
        }
        if(UserModel::valid_password($pass) !== true) {
            $this->context['error_form'] = new ValidationError(UserModel::valid_password($pass));
            return;
        }
        if(!$recovery_model->is_available())
            return;

        // Set new password
        $pass_hash = UserModel::password_hash($pass);

        if($recovery_model) {
            $user = UserModel::get(array(
                [
                    'name' => 'obj.id',
                    'type' => '=',
                    'value' => $recovery_model->field_user_id
                ]
            ));

            // Save new password
            $user->field_password = $pass_hash;
            $user->save();
            
            // Set RecoveryModel as used
            $recovery_model->field_is_used = true;
            $recovery_model->save();

            // Send message to email
            send_email(
                'Password Updated',
                get_reset_completed_email_template($user->get_public_name()),
                get_reset_completed_email_alt_template($user->get_public_name()),
                $user->field_email,
                $user->get_public_name()
            );

            // Show message and do form as unavailable
            $this->context['not_available'] = 'Your password has been successfully changed';
        }
        
    }
}

class LogoutController extends BaseController {
    protected $template_name = USER_TEMPLATES . '/forgot_password.php';
    
    protected function distinct() {
        logout();
        redirect_to(get_permalink('index:home'));
    }
}

class ProfileController extends BaseController {
    protected $template_name = USER_TEMPLATES . '/profile.php';
    protected $allow_role = 'user';
}

class EditContactInfoController extends BaseController {
    protected $template_name = USER_TEMPLATES . '/sect_information/edit_information.php';
    protected $allow_role = 'user';

    protected function post() {
        if(!CURRENT_USER)
            throw new UnauthorizedHttp401();

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

class EditEmailController extends BaseController {
    protected $template_name = USER_TEMPLATES . '/sect_information/edit_email.php';
    protected $allow_role = 'user';

    protected function post() {
        if(!CURRENT_USER)
            throw new UnauthorizedHttp401();

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
class EditPasswordController extends BaseController {
    protected $template_name = USER_TEMPLATES . '/sect_information/edit_password.php';
    protected $allow_role = 'user';

    protected function post() {
        if(!CURRENT_USER)
            throw new UnauthorizedHttp401();

        $old_password = $_POST['old_password'] ?? '';
        $new_password = $_POST['new_password'] ?? '';
        $repeat_password = $_POST['repeat_password'] ?? '';

        // Check password
        if(!CURRENT_USER->password_verify($old_password)) {
            $this->context['error_form'] = new ValidationError(['You entered your old password incorrectly.']);
            return;
        }
        if($new_password != $repeat_password) {
            $this->context['error_form'] = new ValidationError(['Passwords don\'t match']);
            return;
        }
        try {
            $curr_user = CURRENT_USER;
            if(UserModel::valid_password($new_password) !== true) {
                $errors = UserModel::valid_password($new_password);
                throw new ValidationError($errors);
            }
            // After validations, save new password
            $curr_user->field_password = UserModel::password_hash($new_password);
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