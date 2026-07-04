<?php
function get_recovery_email_template(string $username, string $url_to_recovery) {
    $preheader = 'Reset your password quickly and securely. Click the link to regain access to your account.';

    ob_start();
    require APPS_PATH . '/Users/Templates//email_templates/recovery_pass.php';
    return ob_get_clean();
}

function get_recovery_email_alt_template(string $username, string $url_to_recovery) {
    ob_start();
    require APPS_PATH . '/Users/Templates//email_templates/recovery_pass_alt.php';
    return ob_get_clean();
}

function get_reset_completed_email_template(string $username) {
    $preheader = 'Your Password Has Been Successfully Updated.';

    ob_start();
    require APPS_PATH . '/Users/Templates//email_templates/reset_password_compl.php';
    return ob_get_clean();
}
function get_reset_completed_email_alt_template(string $username) {
    ob_start();
    require APPS_PATH . '/Users/Templates//email_templates/reset_password_compl_alt.php';
    return ob_get_clean();
}
?>