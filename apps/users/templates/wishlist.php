<?php the_header(
    "My Wishlist", 
    '', 
    'profile', 
    [
        ['robots', 'nofollow, noindex']
]) ?>

<div class="container">
    <?php the_user_nav() ?>

    <h1 class="p-title">My Wishlist</h1>

    <div class="grid-product-items">
        <?php foreach ($context['products'] as $prod) {
            the_product($prod);
        } ?>
    </div>
</div>

<?php the_footer() ?>