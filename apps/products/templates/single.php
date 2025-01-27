<?php the_header(
    $context['product']->field_title, 
    $context['product']->field_excerpt, 
    'product', 
    [
        ['robots', 'nofollow, noindex'],
]) ?>

<div class="container">
    <div class="product-header">
        <div class="left">
            <h2 class="p-title"><?php echo $context['product']->field_title ?></h2>
            <div class="image image-cover">
                <img src="<?php echo $context['product']->get_poster_url(); ?>" alt="">
            </div>
        </div>
        <div class="right">
            <h1 class="p-title"><?php echo $context['product']->field_title ?></h1>
            <div class="product-excerpt">
                <?php echo $context['product']->field_excerpt ?>
            </div>
            <div class="product-stats">
                <div class="product-stat">
                    <div class="left">
                        Type: <span><?php echo $context['product']->field_edition ?></span>
                    </div>
                    <div class="right">
                        <div class="icon" style="background: #DB8000;"><i class="fa-solid fa-layer-group"></i></div>
                    </div>
                </div>
                <?php
                $status_icon = '<i class="fa-solid fa-x"></i>';
                $status_color = "#f21c1c";
                $status_text = "Out of Stock";

                if($context['product']->keys_count > 5) {
                    $status_icon = '<i class="fa-solid fa-check"></i>';
                    $status_color = "#00A930";
                    $status_text = "Available";
                } else if ($context['product']->keys_count <= 5 && $context['product']->keys_count > 0) {
                    $status_icon = '<i class="fa-solid fa-triangle-exclamation"></i>';
                    $status_color = "#DB8000";
                    $status_text = "Few left in stock!";
                }
                ?>
                <div class="product-stat">
                    <div class="left">
                        Status: <span><?php echo $status_text ?></span>
                    </div>
                    <div class="right">
                        <div class="icon" style="background: <?php echo $status_color ?>;"><?php echo $status_icon ?></div>
                    </div>
                </div>
                <div class="product-stat">
                    <div class="left">
                        Region: <span><?php echo $context['region']->field_name ?></span>
                    </div>
                    <div class="right">
                        <div class="icon" style="background: #009EA9;"><i class="fa-solid fa-globe"></i></div>
                    </div>
                </div>
                <div class="product-stat">
                    <div class="left">
                        Platform: <span><?php echo $context['product']->platform_title ?></span>
                    </div>
                    <div class="right">
                        <div class="icon" style="background: <?php echo $context['product']->platform_background ?>;"><?php echo $context['product']->platform_icon ?></div>
                    </div>
                </div>
            </div>
            <div class="product-single-control">
                <button type="button" class="btn <?php echo $context['product']->is_available() ? 'btn-primary' : 'btn-gray' ?> btn-price" <?php echo $context['product']->is_available() ? '' : 'disabled' ?>>
                    <i class="fa-solid fa-cart-shopping"></i> <?php echo $context['product']->get_price_format() . "$" ?>
                    
                    <div class="meta-price">
                        <?php if($context['product']->get_discount() > 0): ?>
                        <span class="discount"><?php echo  "-" . $context['product']->get_discount() . "%" ?></span>
                        <?php endif ?>
                        
                        <?php if($context['product']->get_price() != $context['product']->field_original_price): ?>
                        <span class="old-price"><?php echo  $context['product']->field_original_price . "$" ?></span>
                        <?php endif ?>
                    </div>
                </button>
                <button type="button" class="btn btn-info btn-wishlist" product-id="<?php echo $context['product']->get_id() ?>"><i class="<?php echo empty($context['product']->is_in_wishlist) ? 'fa-regular' : 'fa-solid' ?> fa-heart"></i> <span><?php echo empty($context['product']->is_in_wishlist) ? 'Add to Wishlist' : 'Remove from List' ?></span></button>
                <a href="<?php echo $context['product']->field_original_url ?>" target="_blank" class="btn"><i class="fa-solid fa-arrow-up-right-from-square"></i> Original product</a>
            </div>
            <div class="product-region-alert">
            * When buying, pay attention to the region
            </div>
        </div>
    </div>
    <div class="product-descr">
        <?php echo $context['product']->get_description() ?>
    </div>
    <div class="products-other">
        <h2>You may also like</h2>

        <div class="swiper-container">
            <div class="swiper-wrapper">
                <?php foreach ($context['recommendations'] as $prod): ?>
                <div class="swiper-slide">
                    <?php the_product($prod) ?>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</div>

<?php the_footer(array(
    ASSETS_PATH . '/swiper/swiper-bundle.min.js',
    ASSETS_PATH . '/js/product.js'
)) ?>