<?php the_admin_header('Dashboard', $context['feedback_count']) ?>

<div class="admin-container delete">
    <h1 class="p-title">Delete Object</h1>
    <div class="meta-text">Do you really want to delete "<?php echo $context['model']->{$context['field']} ?>"(<?php echo $context['model']->get_id() ?>) from the "<?php echo $context['model']->get_table_name() ?>" table?</div>

    <form method="post" class="btn-control">
        <a href="<?php echo $context['back_url'] ?>" class="btn btn-primary">Back</a>
        <button type="submit" class="btn btn-danger">Delete</button>
    </form>
</div>

<?php the_admin_footer() ?>