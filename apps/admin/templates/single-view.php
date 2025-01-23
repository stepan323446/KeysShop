<?php the_admin_header(
    $context['is_new'] ?  'New ' . $context['verbose_name'] : get_the_safe($context['edit_title']), 
    $context['feedback_count']);
    
$disabled_attr = $context['can_save'] ? '' : 'disabled';
?>

<div class="admin-container">
    <h1 class="p-title">
        <?php if($context['object']->is_saved()): ?>
        <?php the_safe($context['edit_title']) ?>
        <?php else: ?>
        New <?php echo $context['verbose_name'] ?>
        <?php endif; ?>
    </h1>

    <form class="form admin-single" method="post" enctype="multipart/form-data">
        <div class="admin-single__form">
            <?php
            if(isset($context['error_message']))
                the_alert($context['error_message'], 'warning', 'form-alert');

            if(isset($context['success_message']))
                the_alert($context['success_message'], 'success', 'form-alert');
            ?>

            <?php foreach($context['fields'] as $field): ?>
                <?php
                // If field is html string
                if(gettype($field) == 'string') {
                    echo $field;
                    continue;
                }    
                ?>

                <!-- Label for every field (except checkbox) -->
                <?php 
                $label = isset($field['input_label']) ? $field['input_label'] : ucfirst($field['model_field']);
                if($field['input_type'] != 'checkbox'): ?>
                <label for="<?php echo $field['model_field'] ?>">
                    <?php echo $label ?> <?php echo in_array('required', $field['input_attrs'] ?? array()) ? '<span>*</span>' : '' ?>
                </label>
                <?php endif; ?>

                <?php
                // Input field
                $attrs_str = isset($field['input_attrs']) ? implode(" ", $field['input_attrs']) : '';
                $val = $context['object']->{'field_' . $field['model_field']} ?? '';

                switch($field['input_type']) {
                    case 'text':
                    case 'number':
                    case 'email':
                    case 'color':
                        ?>
                        <div class="input <?php echo $attrs_str . ' ' . $disabled_attr . ' ' . $field['input_type'] ?>">
                            <input type="<?php echo $field['input_type'] ?>" id="<?php echo $field['model_field'] ?>" name="<?php echo $field['model_field'] ?>" value="<?php the_safe($val) ?>" <?php echo $attrs_str . ' ' . $disabled_attr ?> step="0.01">
                        </div>
                        <?php
                        break;
                    case 'checkbox':
                        ?>
                        <div class="input-checkbox <?php echo $attrs_str ?>">
                            <input type="checkbox" id="<?php echo $field['model_field'] ?>" name="<?php echo $field['model_field'] ?>" value="on" <?php echo $attrs_str ?> <?php echo $val ? 'checked' : '' ?>>
                            <label for="<?php echo $field['model_field'] ?>"><?php echo $label ?></label>
                        </div>
                        <?php
                        break;
                    case 'textarea':
                        ?>
                        <div class="input <?php echo $attrs_str . ' ' . $disabled_attr ?>">
                            <textarea name="<?php echo $field['model_field'] ?>" id="<?php echo $field['model_field'] ?>" <?php echo $attrs_str . ' ' . $disabled_attr ?> rows="7"><?php the_safe($val) ?></textarea>
                        </div>
                        <?php
                        break;
                    case 'select':
                        ?>
                        <div class="input-select <?php echo $attrs_str . ' ' . $disabled_attr ?>">
                            <select name="<?php echo $field['model_field'] ?>" id="<?php echo $field['model_field'] ?>" <?php echo $attrs_str . ' ' . $disabled_attr ?>>
                                <?php foreach($field['input_values'] as $option): ?>
                                    <option value="<?php echo $option[0] ?>" <?php echo $val == $option[0] ? 'selected' : '' ?>>
                                        <?php echo $option[1] ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <?php
                        break;

                    case 'image':
                        ?>
                        <div class="input-file">
                            <input type="file" id="<?php echo $field['model_field'] ?>" name="<?php echo $field['model_field'] ?>" accept="image/*" <?php echo $attrs_str . ' ' . $disabled_attr ?>>

                            <?php if(!empty($val)): ?>
                            <div class="image">
                                <img src="<?php echo MEDIA_URL . $val ?>" alt="">
                            </div>
                            <?php endif; ?>
                        </div>
                        <?php
                        break;

                }?>
            <?php endforeach; ?>
        </div>
        <div class="admin-single__info">
            <div class="admin-block">
                <div class="admin-block__title">
                    <?php echo $context['object']->is_saved() ? "Edit " . $context['verbose_name'] : "New " . $context['verbose_name'] ?>
                </div>
                <div class="admin-block__content">
                    <div class="btn-control">
                        <?php if($context['object']->is_saved()): ?>
                        <a href="<?php the_permalink('admin:delete', [$context['object']->get_table_name(), $context['object']->get_id()]) ?>" class="btn btn-danger" type="submit">Delete</a>
                        <?php else: ?>
                        <span></span>
                        <?php endif; ?>
                        
                        <button class="btn btn-primary" type="submit" <?php echo $disabled_attr ?>>Save</button>
                    </div>
                </div>
            </div>
            <?php
            if(isset($context['error_form']))
                $context['error_form']->display_error();
            ?>

            <?php
            if($context['object']->is_saved()) {
                foreach ($context['component_widgets'] as $widget) {
                    $widget($context['object']);
                }
            }
            
            ?>

        </div>
    </form>
</div>

<?php the_admin_footer() ?>