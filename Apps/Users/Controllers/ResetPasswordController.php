<?php
namespace Apps\Users\Controllers;

use Apps\Users\Models\UserModel;
use Includes\BaseController;
use Includes\Model\ValidationError;
use RecoveryPassModel;



class ResetPasswordController extends BaseController {
    protected string $template_name = APPS_PATH . '/Users/Templates/reset_password.php';

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