<div class="admin-block">
    <div class="admin-block__title">Order info</div>
    <div class="admin-block__content order-content">
        <h2 class="order-stat p-title">
            <span>Total price: </span><span><?php echo $order->get_total_price() . '$' ?></span>
        </h2>
        <div class="order-stat">
            <span>Buyer: </span><span><a href="<?php the_permalink('admin:user', [$order->field_user_id]) ?>"><?php echo $order->get_buyer_username() ?></a></span>
        </div>

        <div class="admin-block__table">
            <?php foreach($keys as $key): ?>
            <div class="row">
                <div class="column"><a href="<?php the_permalink('admin:product-key', [$key->get_id()]) ?>"><?php echo $key->product_name ?></a></div>
                <div class="column"><?php echo $key->field_price . '$' ?></div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>