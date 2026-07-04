<?php
function the_email_header(string $preheader) {
    include APPS_PATH . '/Index/Templates/components/header_email.php';
}
function the_email_footer() {
    include APPS_PATH . '/Index/Templates/components/footer_email.php';
}

/**
 * Display alert element
 * @param string $text
 * @param string $type
 * @param string $support_classes
 */
function the_alert(string $text, $type = 'warning', $support_classes = '') {
    $alert_class_icon = 'fa-circle-info';
    switch ($type) {
        case 'warning':
            $alert_class_icon = 'fa-circle-info';
            break;
        case 'success':
            $alert_class_icon = 'fa-circle-check';
            break;
    }

    include APPS_PATH . '/Index/Templates/components/alert.php';
}