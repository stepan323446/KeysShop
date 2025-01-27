<?php the_header(
    'Catalog', 
    'A catalog of all products: games and programs where you can find anything', 
    'catalog', 
    [
        ['robots', 'nofollow, noindex'],
]) ?>

<div class="container">
    <div class="catalog-container">
        <div class="filters">
            <form method="get">
                <div class="search input">
                    <input type="text" name="s" placeholder="Search for products" value="<?php echo the_safe($_GET['s']) ?? '' ?>">
                    <button type="submit"><i class="fa-solid fa-search"></i></button>
                </div>
                <?php
                foreach($context['filters'] as $filter_name => $taxonomies):
                ?>
                <div class="filter">
                    <div class="filter-title"><?php echo ucfirst($filter_name) ?></div>
                    <div class="filter-list">
                        <?php foreach($taxonomies as $tax): ?>
                        <div class="input-checkbox">
                            <input id="tax_<?php echo $tax->get_id() ?>" name="<?php echo $filter_name ?>[]" value="<?php echo $tax->get_id() ?>" type="checkbox" <?php 
                                if(is_array($_GET[$filter_name]))
                                    if(in_array($tax->get_id(), $_GET[$filter_name]))
                                        echo 'checked';
                            ?>>
                            <label for="tax_<?php echo $tax->get_id() ?>"><?php echo $tax->field_name ?></label>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
                <?php endforeach; ?>
                <div class="filter">
                    <div class="filter-title">Sort by</div>
                    <div class="input-select">
                        <select name="sort-by">
                            <option value="title" <?php echo $context['sort_by'] == 'title' ? 'selected' : '' ?>>By title</option>
                            <option value="price" <?php echo $context['sort_by'] == 'price' ? 'selected' : '' ?>>By price</option>
                            <option value="date-created" <?php echo $context['sort_by'] == 'date-created' ? 'selected' : '' ?>>By date created</option>
                            <option value="date-updated" <?php echo $context['sort_by'] == 'date-updated' ? 'selected' : '' ?>>By date updated</option>
                        </select>
                    </div>
                    <div class="input-radio">
                        <input type="radio" name="sort-type" id="sort-asc" value="asc" <?php echo $context['sort_type'] == 'asc' ? 'checked' : '' ?>>
                        <label for="sort-asc">Ascending</label>
                    </div>
                    <div class="input-radio">
                        <input type="radio" name="sort-type" id="sort-desc" value="desc" <?php echo $context['sort_type'] == 'desc' ? 'checked' : '' ?>>
                        <label for="sort-desc">Descending</label>
                    </div>
                </div>
                <div class="filter">
                    <div class="filter-list">
                        <div class="input-checkbox">
                            <input id="only_available" name="only_available" type="checkbox" <?php echo isset($_GET['only_available']) ? 'checked' : '' ?>>
                            <label for="only_available">Only available</label>
                        </div>
                    </div>
                </div>
                <div class="btn-control">
                    <a href="<?php the_permalink('products:catalog') ?>" class="btn">Reset</a>
                    <button class="btn btn-primary" type="submit">Search</button>
                </div>
            </form>
        </div>
        <div class="grid-product-items">
            <?php
            foreach($context['products'] as $product) {
                the_product($product);
            }
            ?>
        </div>
        <span></span>
        <?php the_pagination($context['products_count'], CATALOG_MAX_PRODUCTS, $context["page"]) ?>
    </div>
    
    <div id="catalog-filter-btn" class="btn btn-primary">
        <i class="fa-solid fa-filter"></i>
    </div>
</div>

<?php the_footer(array(
    ASSETS_PATH . '/js/catalog.js'
)) ?>