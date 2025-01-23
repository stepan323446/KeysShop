<div class="admin-block">
    <div class="admin-block__title">Last 10 keys</div>
    <div class="admin-block__content">
        <div class="admin-block__table">
            <?php if(!empty($keys)): ?>
            <?php  foreach ($keys as $key): ?>
            <div class="row">
                <div class="column"><a href="<?php the_permalink('admin:product-key', [$key->get_id()]) ?>"><?php echo $key->show_secret_key() ?></a></div>
                <div class="column"><?php echo $key->field_price ?></div>
            </div>
            <?php endforeach; ?>
            <?php else: ?>
                <div class="nothing">No available keys found</div>
            <?php endif ?>
        </div>
        <div class="btn-control">
            <a href="<?php the_permalink('admin:key-list', [$prod_id]) ?>" class="btn">Show all</a>
            <a href="<?php the_permalink('admin:product-key-new', [$prod_id]) ?>" class="btn btn-primary">New key</a>
        </div>
    </div>
</div>