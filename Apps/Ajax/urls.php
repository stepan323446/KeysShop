<?php

use KeysShop\Apps\Ajax\Controllers\AjaxController;
use KeysShop\Includes\Routing\Path;

$ajax_urls = array(
    new Path('/ajax', new AjaxController(), 'main'),
);