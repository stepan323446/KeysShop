<?php
require_once APPS_PATH . '/ajax/controllers.php';

$ajax_urls = array(
    new Path('/ajax', new AjaxController(), 'main'),
);