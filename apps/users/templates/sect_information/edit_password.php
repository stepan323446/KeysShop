<?php the_header(
    'Change password ' . CURRENT_USER->field_username, 
    'View and manage your personal information, update account settings, and track your activity on the Profile page. Customize your preferences and keep your details up-to-date easily.', 
    'profile-edit', 
    [
        ['robots', 'nofollow, noindex']
]) ?>

<div class="container">
    <h1 class="p-title">Change password</h1>

    <form class="form form-edit" method="post">
        <?php
            if(isset($context['error_message']))
                the_alert($context['error_message']);
        ?>

        <label for="field_old_password">Old Password <span>*</span></label>
        <div class="input">
            <input type="password" name="old_password" id="field_old_password" required>
        </div>

        <label for="field_new_password">New password <span>*</span></label>
        <div class="input">
            <input type="password" name="new_password" id="field_new_password" required>
        </div>

        <label for="field_repeat_password">Repeat new password <span>*</span></label>
        <div class="input">
            <input type="password" name="repeat_password" id="field_repeat_password" required>
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