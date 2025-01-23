<?php the_admin_header('Edit ' . $context['user']->field_username, $context['feedback_count']) ?>

<div class="admin-container">
    <h1 class="p-title">
        <?php if($context['tax']->is_saved()): ?>
        Taxonomy "<?php echo $context['tax']->field_name ?>""
        <?php else: ?>
        New taxonomy
        <?php endif; ?>
    </h1>
    <form method="post" class="form admin-single">
        <div class="admin-single__form">
            <label for="name">Name <span>*</span></label>
            <div class="input">
                <input type="text" id="name" name="name" value="<?php echo $context['tax']->field_name ?>">
            </div>

            <label for="slug">Slug <span>*</span></label>
            <div class="input">
                <input type="text" id="slug" name="slug" value="<?php echo $context['tax']->field_slug ?>">
            </div>

            <label for="slug">Type <span>*</span></label>
            <div class="input-select">
                <select name="type" id="type">
                    <option value="platforms">Platforms</option>
                    <option value="genres">Genres</option>
                    <option value="region">Region</option>
                </select>
            </div>
        </div>
        <div class="admin-single__info">
            <div class="admin-block">
                <div class="admin-block__title">
                    <?php echo $context['tax']->is_saved() ? "Edit taxonomy" : "New taxonomy" ?>
                </div>
                <div class="admin-block__content">
                    <div class="btn-control">
                        <?php if($context['tax']->is_saved()): ?>
                        <a href="<?php the_permalink('admin:delete', ['tax', $context['tax']->get_id()]) ?>" class="btn btn-danger" type="submit">Delete</a>
                        <?php else: ?>
                        <span></span>
                        <?php endif; ?>
                        
                        <button class="btn btn-primary" type="submit">Save</button>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

<?php the_admin_footer() ?>