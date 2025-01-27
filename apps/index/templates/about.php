<?php the_header(
    'About Us', 
    'Discover and purchase top-rated games and software to elevate your entertainment and productivity. Explore our curated selection for the best deals and exclusive offers!', 
    'about', 
    [
        ['robots', 'nofollow, noindex'],
        ['keywords', 'keys, programms, games, xbox, pc, playstation']
]) ?>

<div class="container">
    <section class="article-text about-section">
        <div class="left">
            <h1 class="p-title">KeysShop: Unlocking Digital Possibilities for Everyone</h1>
            <p>Welcome to KeysShop – your trusted online destination for premium software keys, gaming keys, and digital licenses.</p>
            <p>At KeysShop, we pride ourselves on delivering reliable, fast, and affordable solutions for all your software and gaming needs. </p>
            <p>Whether you're a tech enthusiast, a gamer, or a professional looking for authentic licenses, we've got you covered.</p>
        </div>
        <div class="right">
            <div class="image">
                <img src="<?php echo ASSETS_PATH . '/images/about/whats.png' ?>" alt="">
            </div>
        </div>
    </section>
    <section class="why-me">
        <div class="why-me__block">
            <div class="why-me__icon">
                <i class="fa-solid fa-money-bill"></i>
            </div>
            <div class="why-me__text">
                Save up to 90%
            </div>
        </div>
        <div class="why-me__block">
            <div class="why-me__icon">
                <i class="fa-solid fa-shield-halved"></i>
            </div>
            <div class="why-me__text">
                Save and Secure
            </div>
        </div>
        <div class="why-me__block">
            <div class="why-me__icon">
                <i class="fa-solid fa-gamepad"></i>
            </div>
            <div class="why-me__text">
                10 000+ Games
            </div>
        </div>
    </section>
    <section class="article-text about-section reverse">
        <div class="left">
            <h2 class="p-title">Secure payments via the Stripe system</h2>
            <p>We prioritize your security and convenience. All transactions on our platform are processed through the trusted and reliable Stripe payment system, ensuring your personal and financial information remains protected at all times. Shop with confidence!</p>
        </div>
        <div class="right">
            <div class="image">
                <img src="<?php echo ASSETS_PATH . '/images/about/stripe.png' ?>" alt="">
            </div>
        </div>
    </section>
</div>

<?php the_footer() ?>