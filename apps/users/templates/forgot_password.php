<?php the_header(
    'Password Recovery', 
    '', 
    'login-register', 
    [
        ['robots', 'nofollow, noindex']
]) ?>

<div class="container">
    <div class="login__inner">
        <h1 class="p-title">Password Recovery</h1>
        <p>Enter your email to receive a secure link to reset your password. The link will be valid for a limited time.</p>
        <form class="form" method="post">
        <?php
            if(isset($context['error_message']))
                the_alert($context['error_message']);

            if(isset($context['success_message']))
                the_alert($context['success_message'], 'success');
            ?>
            <label for="field_username">E-mail <span>*</span></label>
            <div class="input">
                <input type="email" name="email" id="field_username" required>
            </div>

            <div class="login-choice">
                <a href="<?php the_permalink('users:login') ?>">Login</a>
                <button class="btn btn-primary" type="submit">Send</button>
            </div>
        </form>
    </div>
    
</div>

<?php the_footer() ?>