
<?php
$cart_info = get_cart_information();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo get_title_website($title) ?></title>
    <link rel="shortcut icon" type="image/png" href="<?php echo ASSETS_PATH . '/favicon.png' ?>">

    <meta name="description" content="<?php echo $description ?>">

    <!-- Meta tags -->
    <meta name="robots" content="nofollow, noindex">

    <?php foreach($meta_tags as $tag): ?>
    <meta name="<?php echo $tag[0] ?>" content="<?php echo $tag[1] ?>">
    <?php endforeach; ?>

    <!-- Google fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Play:wght@400;700&family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&display=swap" rel="stylesheet">
    <!-- Google fonts/ -->

    <!-- Font Awesome -->
    <link href="<?php echo ASSETS_PATH . '/fontawesome-free-6.6.0-web/css/fontawesome.min.css' ?>" rel="stylesheet" />
    <link href="<?php echo ASSETS_PATH . '/fontawesome-free-6.6.0-web/css/brands.min.css' ?>" rel="stylesheet" />
    <link href="<?php echo ASSETS_PATH . '/fontawesome-free-6.6.0-web/css/solid.min.css' ?>" rel="stylesheet" />
    <link href="<?php echo ASSETS_PATH . '/fontawesome-free-6.6.0-web/css/regular.min.css' ?>" rel="stylesheet" />
    <!-- Font Awesome/ -->

    <!-- Other Libraries -->
    <link rel="stylesheet" href="<?php echo ASSETS_PATH . '/swiper/swiper-bundle.min.css' ?>">
    <link rel="stylesheet" href="<?php echo ASSETS_PATH . '/toastify/toastify.min.css' ?>">
    <!-- Other Libraries/ -->

    <link rel="stylesheet" href="<?php echo ASSETS_PATH . '/css/reset.css' ?>">
    <link rel="stylesheet" href="<?php echo ASSETS_PATH . '/css/style.css' ?>">
</head>
<body class="<?php echo $body_class ?>">
    <header class="header">
        <div class="container">
            <div class="header__inner">
                <div class="left">
                    <a href="<?php the_permalink('index:home') ?>" class="logo">KeysShop</a>
                </div>
                <div class="center">
                    <a class="header-nav-link" href="<?php the_permalink('products:catalog') ?>"><i class="fa-solid fa-gamepad"></i> All Products</a>
                    <form action="<?php the_permalink('products:catalog') ?>">
                        <div id="search-global" class="input">
                            <input type="text" name="s" placeholder="Search for products" value="<?php echo the_safe($_GET['s']) ?? '' ?>">
                            <button type="submit"><i class="fa-solid"></i></button>
                        </div>
                    </form>
                </div>
                <div class="right">
                    <a href="#" class="header-cart">
                        <span class="header-cart__price">
                            <?php echo $cart_info['total_price'] ?>$
                        </span>
                        <div class="header-cart__icon exists">
                            <i class="fa-solid fa-cart-shopping"></i>
                            <span id="header-cart-counter" <?php echo $cart_info['count'] > 0 ? '' : 'hidden' ?>><?php echo $cart_info['count'] ?></span>
                        </div>
                    </a>
                    <?php if(empty(CURRENT_USER)): ?>
                        <!-- If user is not authorized -->
                        <a href="<?php the_permalink('users:login') ?>" class="btn btn-primary header__login-btn">
                        Login</a>
                    <?php else: ?>
                        <!-- If user is authorized -->
                         <div id="header-avatar" class="image image-cover">
                            <img src="<?php echo ASSETS_PATH . '/images/user.png' ?>" alt="">

                            <div class="modal-profile">
                                <div class="modal-profile__welcome">
                                    <div class="modal-profile__inner">
                                        <span>Welcome</span>
                                        <div class="modal-profile__username clamp clamp-1">
                                            <?php echo CURRENT_USER->field_username ?>
                                        </div>
                                    </div>
                                    <hr>
                                    <div class="modal-profile__inner">
                                        <ul>
                                            <li><a href="<?php the_permalink('users:profile') ?>"><i class="fa-solid fa-circle-info"></i> Personal</a></li>
                                            <li><a href="<?php the_permalink('users:wishlist') ?>"><i class="fa-solid fa-heart"></i> My Wishlist</a></li>
                                            <li><a href="<?php the_permalink('users:orders') ?>"><i class="fa-solid fa-key"></i> My Orders</a></li>
                                        </ul>
                                    </div>
                                    <hr>
                                    <div class="modal-profile__inner modal-logout">
                                        <ul>
                                            <li><a href="<?php the_permalink('users:logout') ?>"><i class="fa-solid fa-door-open"></i> Logout</a></li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>                    
                </div>
            </div>
        </div>        
    </header>
    
    <div id="ajax-search-result" hidden>
        <div class="content">
        </div>
    </div>
    <div id="cart-container" class="background-screen">
        <div class="cart-wrapper">
            <div class="cart-header">
                <div class="cart-header__text">
                    My Cart
                </div>
                <div class="cart-header__close cart-close"><i class="fa-solid fa-x"></i></div>
            </div>
            <div class="nothing">You have no items in your shopping cart.</div>
            <div class="cart-items">
                
            </div>
            <div class="cart-btn">
                <div class="total-price">
                    <span>Total price:</span>
                    <span class="price">43.33$</span>
                </div>
                <a href="<?php the_permalink('order:index') ?>" class="btn btn-primary">Checkout</a>
                <button type="button" class="btn cart-close">Continue shopping</button>
            </div>
        </div>
    </div>

    <div class="wrapper-content">

    <?php if(!empty(MESSAGE_WARNING)): ?>
    <div class="container">    
        <?php the_alert("The site is for preview only. Keys are invalid, purchase in test mode", 'warning', 'test-mode-warning') ?>
    </div>
    <?php endif ?>