</div>

<footer>
    <div class="container">
        <div class="footer__inner">
            <div class="left">
                <nav class="footer-links">
                    <div class="footer-links__title">
                        <a href="<?php the_permalink('index:home') ?>" class="logo">KeysShop</a>
                    </div>
                    <ul>
                        <li>
                            <a href="<?php the_permalink('index:about') ?>">About Us</a>
                        </li>
                        <li>
                            <a href="<?php the_permalink('index:privacy') ?>">Privacy Policy</a>
                        </li>
                        <li>
                            <a href="<?php the_permalink('index:terms') ?>">Terms & Conditions</a>
                        </li>
                    </ul>
                </nav>

                <nav class="footer-links">
                    <div class="footer-links__title">
                        <span>My Account</span>
                    </div>
                    <ul>
                        <li>
                            <a href="<?php the_permalink("users:profile") ?>">My Account</a>
                        </li>
                        <li>
                            <a href="#">My orders</a>
                        </li>
                        <li>
                            <a href="<?php the_permalink('users:wishlist') ?>">My wishlist</a>
                        </li>
                    </ul>
                </nav>

                <nav class="footer-links">
                    <div class="footer-links__title">
                        <span>Support</span>
                    </div>
                    <ul>
                        <li>
                            <a href="<?php the_permalink('index:faq') ?>">FAQ</a>
                        </li>
                        <li>
                            <a href="<?php the_permalink('contacts:form') ?>">Contact Us</a>
                        </li>
                    </ul>
                </nav>
            </div>
            <div class="right">
                <div class="footer-description">
                    This site is not valid and was created only for informational purposes as a project for a personal portfolio. The keys are invalid, the payments are not real.
                </div>
                <div class="footer-social-links">
                    <a target="_blank" href="https://steve-dekart.xyz/">Website</a> |
                    <a target="_blank" href="#">GitHub</a> |
                    <a target="_blank" href="https://www.linkedin.com/in/stepan-turitsin-009354243/">LinkedIn</a>
                </div>
            </div>
            
        </div>
    </div>
    <hr>
    <div class="copyright">
        © Stepan Turitsin, 2024
    </div>
</footer>

<script src="<?php echo ASSETS_PATH . '/js/main.js' ?>"></script>
<script src="<?php echo ASSETS_PATH . '/toastify/toastify-js.js' ?>"></script>

<?php foreach($scripts as $script): ?>
<script src="<?php echo $script ?>"></script>
<?php endforeach; ?>

</body>
</html>