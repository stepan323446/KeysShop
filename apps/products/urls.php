<?php
require_once APPS_PATH . '/products/controllers.php';

$product_urls = [
    new Path('/item/[:string]', new SingleProductController(), 'single')
];
?>