<?php
require_once APPS_PATH . '/admin/controllers.php';

$admin_urls = [
    // Dashboard
    new Path('/admin', new AdminHomeController(), 'home'),
    
    // Lists
    new Path('/admin/users', new AdminUserListController(), 'user-list'),
    new Path('/admin/feedbacks', new AdminFeedbackListController(), 'feedback-list'),
    new Path('/admin/taxonomies', new AdminTaxonomyListController(), 'taxonomy-list'),
    new Path('/admin/products', new AdminProductListController(), 'product-list'),
    new Path('/admin/product/[:int]/keys', new AdminKeyListController(), 'key-list'),
    new Path('/admin/orders', new AdminOrderListController(), 'order-list'),

    ////// Single object ///////
    // User
    new Path('/admin/user/new', new AdminUserController(true), 'user-new'),
    new Path('/admin/user/[:int]', new AdminUserController(false), 'user'),
    
    // Feedback
    new Path('/admin/feedback/[:int]', new AdminFeedbackController(false), 'feedback'),

    // Taxonomy
    new Path('/admin/tax/new', new AdminTaxonomyController(true), 'tax-new'),
    new Path('/admin/tax/[:int]', new AdminTaxonomyController(false), 'tax'),

    // Product
    new Path('/admin/product/new', new AdminProductController(true), 'product-new'),
    new Path('/admin/product/[:int]', new AdminProductController(false), 'product'),

    // Order
    new Path('/admin/order/[:int]', new AdminOrderController(false), 'order'),

    // Key
    new Path('/admin/product/[:int]/key/new', new AdminKeyController(true), 'product-key-new'),
    new Path('/admin/product/key/[:int]', new AdminKeyController(false), 'product-key'),

    // Dynamic delete for every object type
    new Path('/admin/[:string]/[:int]/delete', new AdminDeleteController(), 'delete')
];