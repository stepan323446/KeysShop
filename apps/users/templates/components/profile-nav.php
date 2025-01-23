<?php
$user_nav = array(
    'users:profile' => 'Information',
    'users:wishlist' => 'My wishlist',
    'users:orders' => 'My orders',
    'users:cart' => 'My cart'
);
?>

<div class="profile-tabs">
    <?php foreach ($user_nav as $router_name => $title): ?>
        <?php if($router_name == Router::$current_router_name): ?>

            <div class="btn btn-tab active"><?php echo $title ?></div>

        <?php else: ?>

            <a href="<?php the_permalink($router_name) ?>" class="btn btn-tab"><?php echo $title ?></a>

        <?php endif; ?>
    <?php endforeach; ?>
</div>