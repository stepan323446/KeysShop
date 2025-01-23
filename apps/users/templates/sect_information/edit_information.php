<?php the_header(
    'Edit ' . CURRENT_USER->field_username, 
    'View and manage your personal information, update account settings, and track your activity on the Profile page. Customize your preferences and keep your details up-to-date easily.', 
    'profile-edit', 
    [
        ['robots', 'nofollow, noindex']
]) ?>

<div class="container">
    <h1 class="p-title">Edit contact information</h1>

    <form class="form form-edit" method="post">
        <?php
            if(isset($context['error_message']))
                the_alert($context['error_message']);
        ?>

        <label for="field_username">Username <span>*</span></label>
        <div class="input">
            <input type="text" name="username" id="field_username" value="<?php the_safe(CURRENT_USER->field_username) ?>" required>
        </div>

        <label for="field_fname">First Name</label>
        <div class="input">
            <input type="text" name="fname" id="field_fname" value="<?php the_safe(CURRENT_USER->field_fname ?? '') ?>">
        </div>

        <label for="field_lname">Last Name</label>
        <div class="input">
            <input type="text" name="lname" id="field_lname" value="<?php the_safe(CURRENT_USER->field_lname ?? '') ?>">
        </div>

        <?php
                if(isset($context['error_form']))
                    $context['error_form']->display_error();
                ?>
        
        <div class="btn-control">
            <a class="a-back" href="<?php the_permalink('users:profile') ?>">< Back</a>
            <button class="btn btn-primary" type="submit">Save</button>
        </div>
        
    </form>
</div>

<?php the_footer() ?>