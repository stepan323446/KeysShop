<?php 
$error = $context['error_model'];

the_header(
    $error->get_http_error(), 
    '', 
    'error', 
    [
        ['robots', 'nofollow, noindex']
    ]); 

    /**
     * @var PageError
     */
    
?>

<div class="error-page">
    <div class="error-code"><?php echo $error->get_http_error() ?></div>
    <div class="error-message"><?php echo $error->getMessage() ?></div>
</div>

<?php the_footer() ?>