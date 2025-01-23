<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo get_title_website($title); ?></title>
    <link rel="shortcut icon" type="image/png" href="<?php echo ASSETS_PATH . '/favicon.png' ?>">

    <!-- Google fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Play:wght@400;700&family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&display=swap" rel="stylesheet">
    <!-- Google fonts/ -->

     <!-- Font Awesome -->
     <link href="<?php echo ASSETS_PATH . '/fontawesome-free-6.6.0-web/css/fontawesome.min.css' ?>" rel="stylesheet" />
    <link href="<?php echo ASSETS_PATH . '/fontawesome-free-6.6.0-web/css/brands.min.css' ?>" rel="stylesheet" />
    <link href="<?php echo ASSETS_PATH . '/fontawesome-free-6.6.0-web/css/solid.min.css' ?>" rel="stylesheet" />
    <!-- Font Awesome/ -->

    <link rel="stylesheet" href="<?php echo ASSETS_PATH . '/css/reset.css' ?>">
    <link rel="stylesheet" href="<?php echo ASSETS_PATH . '/css/style.css' ?>">
    <link rel="stylesheet" href="<?php echo ASSETS_PATH . '/css/admin.css' ?>">
</head>
<body>
    <header class="header-admin">
        <div class="logo">
            KeysShop Admin
        </div>
        <div class="header-admin__control">
            <div class="username">
                Hello, <span><?php echo CURRENT_USER->field_username ?></span>
            </div>
            <div class="links">
                <a href="<?php the_permalink('index:home') ?>">View site</a>
                 | 
                <a href="<?php the_permalink('users:logout') ?>">Log Out</a>
            </div>
        </div>
    </header>

    <div class="wrapper-admin">
        <aside class="admin-sidebar">
            <a href="<?php the_permalink('admin:product-new') ?>" class="btn btn-primary"><i class="fa-solid fa-plus"></i> New Product</a>
            <hr>
            <ul class="admin-sidebar__list">
                <li>
                    <a class="<?php echo Router::$current_router_name == 'admin:home' ? "active" : "" ?>" href="<?php the_permalink('admin:home') ?>">
                        <i class="fa-solid fa-house"></i> Dashboard
                    </a>
                </li>
                <li>
                    <a class="<?php echo Router::$current_router_name == 'admin:product-list' ? "active" : "" ?>" href="<?php the_permalink('admin:product-list') ?>">
                        <i class="fa-solid fa-cube"></i> Products
                    </a>
                </li>
                <li>
                    <a class="<?php echo Router::$current_router_name == 'admin:taxonomy-list' ? "active" : "" ?>" href="<?php the_permalink('admin:taxonomy-list') ?>">
                        <i class="fa-solid fa-tag"></i> Taxonomies
                    </a>
                </li>
                <li>
                    <a href="#">
                        <i class="fa-solid fa-truck"></i> Orders
                    </a>
                </li>
                <li>
                    <a class="<?php echo Router::$current_router_name == 'admin:user-list' ? "active" : "" ?>"  href="<?php the_permalink('admin:user-list') ?>">
                        <i class="fa-solid fa-users"></i> Users
                    </a>
                </li>
                <li>
                    <a class="<?php echo Router::$current_router_name == 'admin:feedback-list' ? "active" : "" ?>"  href="<?php the_permalink('admin:feedback-list') ?>">
                        <i class="fa-solid fa-envelope"></i> Feedbacks <?php echo $feedback_count > 0 ? "<span>" . $feedback_count . "</span>" : "" ?>
                    </a>
                </li>
            </ul>
        </aside>
        <div class="wrapper-admin__content">
            
        

    