<?php the_header(
    'Password Recovery', 
    '', 
    'login-register', 
    [
        ['robots', 'nofollow, noindex']
]) 
?>

<div class="container">
    <div class="login__inner" <?php if(isset($context['not_available'])) echo 'style="text-align: center;"'; ?>>
        <h1 class="p-title">Reset Password</h1>
        <?php if(!isset($context['not_available'])): ?>
        <p>Enter a new password for your account.</p>
        <?php else: ?>
        <p><?php echo $context['not_available'] ?></p>
        <?php endif; ?>

        <?php if(!isset($context['not_available'])): ?>
        <form class="form" method="post">
        <?php
            if(isset($context['error_message']))
                the_alert($context['error_message']);
            ?>
            <label for="field_pass">New password <span>*</span></label>
            <div class="input">
                <input type="password" name="password" id="field_pass" required>
            </div>

            <label for="field_repeat_pass">Repeat new password <span>*</span></label>
            <div class="input">
                <input type="password" name="repeat" id="field_repeat_pass" required>
            </div>

            <?php
                if(isset($context['error_form']))
                    $context['error_form']->display_error();
                ?>

            <div class="login-choice">
                <span></span>
                <button class="btn btn-primary" type="submit">Reset password</button>
            </div>
        </form>
        <?php endif; ?>
    </div>
    
</div>

<?php the_footer() ?>