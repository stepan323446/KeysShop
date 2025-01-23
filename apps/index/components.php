<?php
function the_header($title, $description, $body_class = '', $meta_tags = array()) {
    include APPS_PATH . '/index/templates/components/header.php';
}
function the_email_header($preheader) {
    include APPS_PATH . '/index/templates/components/header_email.php';
}
function the_email_footer() {
    include APPS_PATH . '/index/templates/components/footer_email.php';
}
function the_footer($scripts = array()) {
    include APPS_PATH . '/index/templates/components/footer.php';
}
/**
 * Display alert element
 * @param string $text
 * @param string $type
 * @param string $support_classes
 */
function the_alert($text, $type = 'warning', $support_classes = '') {
    $alert_class_icon = 'fa-circle-info';
    switch ($type) {
        case 'warning':
            $alert_class_icon = 'fa-circle-info';
            break;
        case 'success':
            $alert_class_icon = 'fa-circle-check';
            break;
    }

    include APPS_PATH . '/index/templates/components/alert.php';
}