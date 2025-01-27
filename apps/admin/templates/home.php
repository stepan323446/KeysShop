<?php the_admin_header('Dashboard', $context['feedback_count']) ?>

<div class="admin-container">
    <section>
        <h2>Stats per month</h2>

        <div id="dashboard-stats">
            <div class="dashboard-stats__item top-sales">
                <div class="icon">
                    <i class="fa-solid fa-bag-shopping"></i>
                </div>
                <div class="info">
                    <div class="label">Total sales</div>
                    <div class="value">$2,325</div>
                </div>
            </div>

            <div class="dashboard-stats__item profit">
                <div class="icon">
                    <i class="fa-solid fa-money-bill-trend-up"></i>
                </div>
                <div class="info">
                    <div class="label">Profit</div>
                    <div class="value">$1,654</div>
                </div>
            </div>

            <div class="dashboard-stats__item orders">
                <div class="icon">
                    <i class="fa-solid fa-cart-shopping"></i>
                </div>
                <div class="info">
                    <div class="label">Orders</div>
                    <div class="value">78</div>
                </div>
            </div>

            <div class="dashboard-stats__item new-users">
                <div class="icon">
                    <i class="fa-solid fa-user"></i>
                </div>
                <div class="info">
                    <div class="label">New users</div>
                    <div class="value"><?php echo $context['new_user_count'] ?></div>
                </div>
            </div>
        </div>
    </section>

    <section>
        <h2>Quick tools</h2>

        <div id="quicktools">
            <a href="<?php the_permalink('admin:product-new') ?>" class="btn">
                <i class="fa-solid fa-plus"></i> New Product
            </a>
            <form action="<?php the_permalink('admin:product-list') ?>" method="get">
                <div class="input">
                    <input type="text" name="s" placeholder="Search for products">
                    <button type="submit"><i class="fa-solid fa-magnifying-glass"></i></button>
                </div>
            </form>
            
        </div>
    </section>

    <h2>Latest orders</h2>
</div>

<?php the_admin_footer() ?>