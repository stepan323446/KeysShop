<?php the_email_header($preheader) ?>

<p>Hi <?php echo $username ?>,</p>
<p>We wanted to let you know that your password has been successfully updated. If you made this change, no further action is required.</p>

<p>If you did not request a password change, please contact our support team immediately to secure your account</p>

<p>For any questions or assistance, feel free to reach out to us at <a href="<?php the_permalink('contacts:form') ?>">contact form</a>.</p>

<p>Thank you for taking steps to keep your account secure.</p>

<p>Thanks,<br>The KeysShop Website</p>

<?php the_email_footer() ?>