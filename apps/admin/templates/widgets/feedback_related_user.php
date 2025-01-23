<div class="admin-block">
    <div class="admin-block__title">Related user</div>
    <div class="admin-block__table">
        <div class="row"><a href="<?php the_permalink('admin:user', [$related_user->get_id()]) ?>"><?php the_safe($related_user->field_username) ?></a></div>
    </div>
</div>