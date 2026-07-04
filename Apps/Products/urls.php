<?php

use KeysShop\Apps\Products\Controllers\{
    CatalogController,
    SingleProductController
};
use KeysShop\Includes\Routing\Path;


$product_urls = [
    new Path('/catalog', new CatalogController(), 'catalog'),
    new Path('/product/[:string]', new SingleProductController(), 'single')
];
?>