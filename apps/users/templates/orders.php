<?php the_header(
    "My orders", 
    '', 
    'profile', 
    [
        ['robots', 'nofollow, noindex']
]) ?>

<div class="container">
    <?php the_user_nav() ?>

    <h1 class="p-title">My Orders</h1>

    <div class="my-orders">
        <?php
        if(empty($context['keys']))
            echo '<div class="nothing">There are no orders</div>';
        ?>
        <?php foreach ($context['keys'] as $key):
            $product = $context['products'][$key->field_product_id];
            ?>    
        
        <div class="my-order">
            <div class="order-info">
                <a href="<?php echo $product->get_absolute_url() ?>" class="image image-cover">
                    <img src="<?php echo $product->get_poster_url() ?>" alt="">
                </a>
                <div class="order-info__info">
                    <a href="<?php echo $product->get_absolute_url() ?>" class="p-title clamp clamp-1"><?php echo $product->field_title ?></a>
                    <div class="order-info__table">
                        <div class="order-info__line">
                            Price: <span><?php echo number_format($key->field_price, 2) . '$' ?></span>
                        </div>
                        <div class="order-info__line">
                            Platform: <span><?php echo $product->platform_title ?></span>
                        </div>
                        <div class="order-info__line">
                            Bought at: <span><?php echo $key->field_bought_at->format('d.m.Y') ?></span>
                        </div>
                        <div class="order-info__line">
                            Region: <span><?php echo $product->region_title ?></span>
                        </div>
                        <div class="order-info__line">
                            Method: <span><?php echo $key->order_method ?></span>
                        </div>
                    </div>
                    <div class="order-info__line">
                        Order number: <span><?php echo $key->order_number ?></span>
                    </div>
                    <div class="btn-control">
                        <span></span>
                        <button class="btn btn-primary" type="button">Show key</button>
                    </div>
                </div>
            </div>
            <div class="order-key">
                <h2 class="p-title">Your key</h2>
                <div class="input">
                    <input type="text" value="<?php echo $key->field_key_code ?>" readonly>
                    <button class="btn-copy" type="button"><i class="fa-solid fa-copy"></i></button>
                </div>
            </div>

        </div>

        <?php endforeach; ?>

        <?php the_pagination($context['keys_count'], MAX_ORDER_PER_PAGE, $context['page']) ?>
    </div>
</div>

<?php the_footer(array(
    ASSETS_PATH . '/js/my-orders.js'
)) ?>