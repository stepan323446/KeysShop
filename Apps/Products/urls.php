<?php

use Apps\Products\Controllers\{
    CatalogController,
    SingleProductController
};
use Includes\Routing\Path;


$product_urls = [
    new Path('/catalog', new CatalogController(), 'catalog'),
    new Path('/product/[:string]', new SingleProductController(), 'single')
];
?>