<?php

use Apps\Products\Models\ProductModel;

function the_product(ProductModel $product) {
    include APPS_PATH . '/Products/Templates/components/product-item.php';
}
