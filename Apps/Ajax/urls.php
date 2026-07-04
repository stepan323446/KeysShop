<?php

use Apps\Ajax\Controllers\AjaxController;
use Includes\Routing\Path;

$ajax_urls = array(
    new Path('/ajax', new AjaxController(), 'main'),
);