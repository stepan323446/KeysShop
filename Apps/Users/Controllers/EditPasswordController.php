<?php

namespace Apps\Users\Controllers;

use Exception;
use Apps\Users\Models\UserModel;
use Includes\BaseController;
use Includes\Model\ValidationError;
use Includes\Routing\HttpExceptions\Unauthorized401;



class EditPasswordController extends BaseController {
    protected string $template_name = APPS_PATH . '/Users/Templates/sect_information/edit_password.php';
    protected ?string $allow_role = 'user';

    protected function post() {
        if(!CURRENT_USER)
            throw new Unauthorized401();

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