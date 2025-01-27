<?php
require_once APPS_PATH . '/products/controllers.php';

$product_urls = [
    new Path('/catalog', new CatalogController(), 'catalog'),
    new Path('/product/[:string]', new SingleProductController(), 'single')
];
?>