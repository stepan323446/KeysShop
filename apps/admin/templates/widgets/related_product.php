<div class="admin-block">
    <div class="admin-block__title">Related product</div>
    <div class="admin-block__content">
        <a href="<?php the_permalink('admin:product', [$product->get_id()]) ?>"><?php echo $product->field_title ?></a>
    </div>
</div>