<?php the_header(
    'Welcome', 
    'Discover and purchase top-rated games and software to elevate your entertainment and productivity. Explore our curated selection for the best deals and exclusive offers!', 
    'frontpage', 
    [
        ['robots', 'nofollow, noindex'],
        ['keywords', 'keys, programms, games, xbox, pc, playstation']
]) ?>

<div class="container">
    <?php if(!empty($context['latest_changes'])): ?>
    <div class="swiper-container recent-keys">
        <div class="swiper-wrapper">
            <?php foreach($context['latest_changes'] as $product): ?>
            <div class="swiper-slide" style="background-image: url(<?php echo $product->get_image_url() ?>);">
                <a href="#" class="recent-keys-item">
                    <div class="recent-keys__title clamp clamp-1">
                        <?php echo $product->field_title ?>
                    </div>
                    <div class="bottom">
                        <div class="recent-kets__excerpt clamp clamp-2">
                            <?php echo $product->field_excerpt ?>
                        </div>
                        <div class="btn btn-price btn-gray">
                            <?php echo $product->get_price_format() . "$" ?>
                            <span><?php echo '-' . $product->get_discount() . '%' ?></span>
                        </div>
                    </div>
                </a>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
    <?php endif; ?>

    <h2 class="p-title">Hot Offers</h2>
    <div class="grid-product-items">
        <?php 
        foreach($context['hot_products'] as $product) {
            the_product($product);
        }
        ?>
    </div>
    
    <?php if(!empty($context['comming_soon'])): ?>
    <h2 class="p-title">Comming soon</h2>
        <div class="grid-product-items">
        <?php
        foreach($context['comming_soon'] as $product) {
            the_product($product);
        }
        ?>
        </div>
    <?php endif; ?>
</div>

<?php the_footer(array(
    ASSETS_PATH . '/swiper/swiper-bundle.min.js',
    ASSETS_PATH . '/js/index.js',
)); ?>