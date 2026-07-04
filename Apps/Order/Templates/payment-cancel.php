<?php 
the_header(
    "Payment Cancelled", 
    '', 
    'cancel', 
    [
        ['robots', 'nofollow, noindex']
    ]);     
?>

<div class="order-success-page">
    <h1 class="p-title">Your purchase was cancelled</h1>
    <div class="message">You will be redirected to your order for 3 seconds.</div>
</div>

<script>
    setTimeout(() => {
        window.location.replace("<?php the_permalink('index:home') ?>");
    }, 3000);
</script>

<?php the_footer() ?>