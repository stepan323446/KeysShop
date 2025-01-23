<?php the_header(
    'Change email ' . CURRENT_USER->field_username, 
    'View and manage your personal information, update account settings, and track your activity on the Profile page. Customize your preferences and keep your details up-to-date easily.', 
    'profile-edit', 
    [
        ['robots', 'nofollow, noindex']
]) ?>

<div class="container">
    <h1 class="p-title">Edit E-mail</h1>

    <form class="form form-edit" method="post">
        <?php
            if(isset($context['error_message']))
                the_alert($context['error_message']);
        ?>

        <label for="field_email">E-mail <span>*</span></label>
        <div class="input">
            <input type="email" name="email" id="field_email" value="<?php the_safe(CURRENT_USER->field_email) ?>" required>
        </div>

        <label for="field_fname">Your password <span>*</span></label>
        <div class="input">
            <input type="password" name="password" id="field_password" required>
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