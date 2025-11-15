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
                    <div class="value"><?php echo number_format($context['total_sales'], 2) ?>$</div>
                </div>
            </div>

            <div class="dashboard-stats__item profit">
                <div class="icon">
                    <i class="fa-solid fa-money-bill-trend-up"></i>
                </div>
                <div class="info">
                    <div class="label">Profit</div>
                    <div class="value"><?php echo number_format($context['total_profit'], 2) ?>$</div>
                </div>
            </div>

            <div class="dashboard-stats__item orders">
                <div class="icon">
                    <i class="fa-solid fa-cart-shopping"></i>
                </div>
                <div class="info">
                    <div class="label">Orders</div>
                    <div class="value"><?php echo $context['total_orders'] ?></div>
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

    <table class="admin-table">
        <thead>
            <tr>
                <th>Order number</th>
                <th>Method</th>
                <th>Total price</th>
                <th>Buyer</th>
                <th>Created at</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($context['last_orders'] as $order): ?>
            <tr>
                <td><a href="<?php the_permalink('admin:order', [$order->get_id()]) ?>"><?php echo $order->field_order_number ?></a></td>
                <td><?php echo $order->field_method ?></td>
                <td><?php echo $order->get_total_price() ?></td>
                <td><?php echo $order->get_buyer_username() ?></td>
                <td><?php echo $order->field_created_at ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<?php the_admin_footer() ?>