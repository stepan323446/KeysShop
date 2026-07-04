<?php

use KeysShop\Apps\Admin\Controllers\{
    AdminHomeController,
    AdminDeleteController
};
use KeysShop\Apps\Admin\Controllers\Lists;
use KeysShop\Apps\Admin\Controllers\Singles;
use KeysShop\Includes\Routing\Path;


$admin_urls = [
    // Dashboard
    new Path('/admin', new AdminHomeController(), 'home'),
    
    // Lists
    new Path('/admin/users', new Lists\AdminUserListController(), 'user-list'),
    new Path('/admin/feedbacks', new Lists\AdminFeedbackListController(), 'feedback-list'),
    new Path('/admin/taxonomies', new Lists\AdminTaxonomyListController(), 'taxonomy-list'),
    new Path('/admin/products', new Lists\AdminProductListController(), 'product-list'),
    new Path('/admin/product/[:int]/keys', new Lists\AdminKeyListController(), 'key-list'),
    new Path('/admin/orders', new Lists\AdminOrderListController(), 'order-list'),

    ////// Single object ///////
    // User
    new Path('/admin/user/new', new Singles\AdminUserController(true), 'user-new'),
    new Path('/admin/user/[:int]', new Singles\AdminUserController(false), 'user'),
    
    // Feedback
    new Path('/admin/feedback/[:int]', new Singles\AdminFeedbackController(false), 'feedback'),

    // Taxonomy
    new Path('/admin/tax/new', new Singles\AdminTaxonomyController(true), 'tax-new'),
    new Path('/admin/tax/[:int]', new Singles\AdminTaxonomyController(false), 'tax'),

    // Product
    new Path('/admin/product/new', new Singles\AdminProductController(true), 'product-new'),
    new Path('/admin/product/[:int]', new Singles\AdminProductController(false), 'product'),

    // Order
    new Path('/admin/order/[:int]', new Singles\AdminOrderController(false), 'order'),

    // Key
    new Path('/admin/product/[:int]/key/new', new Singles\AdminKeyController(true), 'product-key-new'),
    new Path('/admin/product/key/[:int]', new Singles\AdminKeyController(false), 'product-key'),

    // Dynamic delete for every object type
    new Path('/admin/[:string]/[:int]/delete', new AdminDeleteController(), 'delete')
];