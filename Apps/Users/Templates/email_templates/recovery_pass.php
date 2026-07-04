<?php the_email_header($preheader) ?>

<p>Hi <?php echo $username ?>,</p>
<p>We received a request to reset your password. Click the button below to proceed:</p>
<p>
    <a href="<?php echo $url_to_recovery ?>"><?php echo $url_to_recovery ?></a>
</p>
<p>If you did not request a password reset, you can safely ignore this email. Your password will remain unchanged.</p>
<p>Thanks,<br>The KeysShop Website</p>

<?php the_email_footer() ?>