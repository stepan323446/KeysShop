<?php 
the_header("Order", "", "order");
?>

<div class="container">
    <div class="order-form">
        <div class="order-payment-select">
            <h1 class="p-title">Select payment method</h1>

            <div class="order-methods">

                <!-- Test method -->
                <div class="order-method">
                    <div class="order-method__title"><div class="toggle"></div> Test method</div>
                    <div class="order-method__form">
                        <form class="form" method="post">
                            <input type="text" name="method"  value="test-method" hidden>
                            <p>This payment method is created for testing only and does not perform any real transactions.</p>
                            <br>
                            <button type="submit" class="btn btn-primary">Pay Now</button>
                        </form>
                    </div>
                </div>
                <!-- Test method /-->

                <!-- Stripe -->
                <div class="order-method">
                    <div class="order-method__title"><div class="toggle"></div> Stripe method</div>
                    <div class="order-method__form">
                        <form class="form" method="post">
                            <input type="text" name="method"  value="stripe" hidden>
                            <p>This payment method is created for testing only and does not perform any real transactions.</p>
                            <br>
                            <button type="submit" class="btn btn-primary">Pay with Stripe</button>
                        </form>
                    </div>
                </div>
                <!-- Stripe /-->
            </div>
        </div>
        <div class="order-summary">
            <h2>Order Summary</h2>

            <div class="order-items">
                <?php foreach ($context['order']['products'] as $product): ?>
                <div class="order-item">
                    <div class="image image-cover">
                        <img src="<?php echo $product['image_cover'] ?>" alt="">
                    </div>
                    <div class="order-item__info">
                        <div class="order-title"><?php echo $product['title'] ?></div>
                        <div class="order-meta">
                            <div class="order-meta__info">
                                <?php echo $product['platform_name'] . ' | ' . $product['region_title'] ?>
                            </div>
                            <div class="order-meta__price">
                                <?php echo $product['price'] . '$' ?>
                            </div>
                        </div>
                    </div>
                    
                </div>
                <?php endforeach; ?>
                <div class="order-price">
                    <span>Order total:</span> <span><?php echo $context['order']['total_price'] ?>$</span>
                </div>
            </div>
        </div>
    </div>
</div>

<?php the_footer(array(
    ASSETS_PATH . '/js/order.js'
)) ?>