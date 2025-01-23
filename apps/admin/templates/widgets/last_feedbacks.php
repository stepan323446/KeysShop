<div class="admin-block">
    <div class="admin-block__title">Last 10 Feedbacks</div>
    <div class="admin-block__table">
        <?php foreach($last_feedbacks as $feedback): ?>
        <div class="row"><a href="<?php the_permalink('admin:feedback', [$feedback->get_id()]) ?>"><?php echo $feedback->field_created_at ?></a></div>
        <?php endforeach; ?>
    </div>
</div>