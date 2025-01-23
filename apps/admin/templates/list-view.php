<?php the_admin_header(ucfirst($context['verbose_name_multiply']), $context['feedback_count']) ?>

<div class="admin-container">
    <h1 class="p-title"><?php echo ucfirst($context['verbose_name_multiply']) ?></h1>

    <section>
        <div id="quicktools">
            <?php if(isset($context['create_router_name'])): ?>
            <a href="<?php the_permalink($context['create_router_name']) ?>" class="btn">
                <i class="fa-solid fa-plus"></i> New <?php echo ucfirst($context['verbose_name']) ?>
            </a>
            <?php else: ?>
                <span></span>
            <?php endif; ?>
            <form method="get">
                <div class="input">
                    <input type="text" name="s" placeholder="Search for <?php echo $context['verbose_name_multiply'] ?>" value="<?php echo isset($_GET['s']) ? $_GET['s'] : '' ?>">
                    <button type="submit"><i class="fa-solid fa-magnifying-glass"></i></button>
                </div>
            </form>
        </div>
    </section>

    <section class="admin-list">
        <table class="admin-table">
            <thead>
                <tr>
                    <?php foreach($context['table_fields'] as $field): ?>
                        <th><?php echo $field['field_title'] ?></th>
                    <?php endforeach; ?>
                </tr>
            </thead>
            <tbody>
                <?php foreach($context['objects'] as $object): ?>
                <tr>
                    <?php for($i = 0; $i < count($context['table_fields']); $i++) {
                        $field_value = '';
                        if($context['table_fields'][$i]['is_func'])
                            $field_value = $object->{$context['table_fields'][$i]['field_name']}();
                        else
                            $field_value = $object->{$context['table_fields'][$i]['field_name']};
                    ?>
                    <td>
                        <?php if($i == 0): ?>
                        <a href="<?php the_permalink($context['single_router_name'], [$object->get_id()]) ?>"><?php the_safe($field_value) ?></a>
                        <?php else: ?>
                        
                        <?php 
                            if(gettype($field_value) == 'boolean')
                                echo $field_value ? 'Yes' : 'No';
                            else
                                the_safe($field_value);
                             ?>
                        <?php endif; ?>
                    </td>
                    <?php } ?>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <?php the_pagination($context['count'], $context['elem_per_page'], $context['page']) ?>
        
        <?php if(empty($context['objects'])): ?>
            <div class="nothing">
                Nothing was found
            </div>
        <?php endif; ?>
    </section>
</div>

<?php the_admin_footer() ?>