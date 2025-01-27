<div class="product-item" product-id="<?php echo $product->get_id() ?>" price="<?php echo $product->get_price() ?>">
    <a href="<?php echo $product->get_absolute_url(); ?>" class="product-item__image image image-cover">
        <img src="<?php echo $product->get_poster_url() ?>" alt="">

        <?php if($product->get_discount() != 0): ?>
        <div class="discount"><?php echo  '-' . $product->get_discount() . "%" ?></div>
        <?php endif; ?>

        <?php if(isset($product->platform_icon)): ?>
            <div class="product-icon" style="background: <?php echo $product->platform_background ?>;">
                <?php echo $product->platform_icon ?>
            </div>
        <?php endif; ?>
    </a>
    <div class="product-item__inner">
        <div class="top">
            <a href="<?php echo $product->get_absolute_url(); ?>" class="product-item__title clamp clamp-2">
                <?php echo $product->field_title ?>
            </a>
            <div class="product-item__excerpt clamp clamp-4">
                <?php echo $product->field_excerpt; ?>
            </div>
        </div>
        
        <div class="bottom">
            <hr>
            <div class="product-control">
                <div class="product-item__price">
                    <?php echo $product->get_price_format() . "$" ?>
                </div>
                <div class="right">
                    <button type="button" class="btn btn-info btn-wishlist" product-id="<?php echo $product->get_id() ?>"><i class="<?php echo empty($product->is_in_wishlist) ? 'fa-regular' : 'fa-solid' ?> fa-heart"></i></button>
                    
                    <?php if($product->is_available()): ?>
                    <button type="button" class="btn btn-primary"><i class="fa-solid fa-cart-shopping"></i> Buy now</button>
                    <?php else: ?>
                    <button type="button" class="btn btn-gray" disabled>Out of Stock</button>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>