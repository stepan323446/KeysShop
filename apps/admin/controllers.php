<?php

require_once INCLUDES_PATH . '/base_controller.php';
require_once APPS_PATH . '/admin/components.php';
require_once APPS_PATH . '/admin/functions.php';

// models
require_once APPS_PATH . '/users/models.php';
require_once APPS_PATH . '/contacts/models.php';
require_once APPS_PATH . '/products/models.php';

define('ADMIN_TEMPLATES', APPS_PATH . '/admin/templates');


class AdminHomeController extends AdminBaseController {
    protected $template_name = ADMIN_TEMPLATES . '/home.php';
    
    public function get_context_data() {
        $context = parent::get_context_data();

        $datetime_month_ago = new DateTime();
        $datetime_month_ago->modify("-1 month");
        $context['new_user_count'] = db_query('SELECT COUNT(*) FROM ' . UserModel::get_table_name() .' WHERE `register_at` > \'' . $datetime_month_ago->format('Y-m-d H:i:s' . '\''))->fetch_row()[0];

        

        return $context;
    }
}

/*
 * Display Lists for different models
 */
class AdminUserListController extends AdminListController {
    protected $model_сlass_name = "UserModel";
    protected $table_fields = array(
        'Username' => 'field_username',
        'Public Name	' => 'get_public_name()',
        'E-mail	' => 'field_email',
        'Status	' => 'get_role()',
    );
    protected $single_router_name = 'admin:user';
    protected $create_router_name = 'admin:user-new';
    protected $verbose_name = "user";
    protected $verbose_name_multiply = "users";
}
class AdminFeedbackListController extends AdminListController {
    protected $model_сlass_name = "FeedbackModel";
    protected $table_fields = array(
        'Name' => 'field_name',
        'E-mail' => 'field_email',
        'Created at	' => 'field_created_at',
        'Is Read?' => 'field_is_read',
    );
    protected $single_router_name = 'admin:feedback';
    protected $verbose_name = "feedback";
    protected $verbose_name_multiply = "feedbacks";
    protected $sort_by = ['obj.is_read', '-obj.created_at'];
}
class AdminTaxonomyListController extends AdminListController {
    protected $model_сlass_name = "TaxonomyModel";
    protected $table_fields = array(
        'Name' => 'field_name',
        'Slug' => 'field_slug',
        'Type' => 'field_type',
    );
    protected $single_router_name = 'admin:tax';
    protected $create_router_name = 'admin:tax-new';
    protected $verbose_name = "taxonomy";
    protected $verbose_name_multiply = "taxonomies";
    protected $sort_by = ['obj.type', 'obj.name'];
}
class AdminProductListController extends AdminListController {
    protected $model_сlass_name = "ProductModel";
    protected $table_fields = array(
        'Title'             => 'field_title',
        'Original price'    => 'field_original_price',
        'Price'             => 'get_price_format()',
        'Available Keys'    => 'keys_count',
        'Platform'          => 'platform_title',
        'Created at'        => 'field_created_at'
    );
    protected $verbose_name = "product";
    protected $verbose_name_multiply = "products";
    protected $single_router_name = 'admin:product';
    protected $create_router_name = 'admin:product-new';
    protected $sort_by = [ '-keys_count' ];
}
class AdminKeyListController extends AdminListController {
    protected $model_сlass_name = "KeyModel";
    protected $table_fields = array(
        'Code'              => 'show_secret_key()',
        'Price'             => 'field_price',
        'Original price'    => 'field_original_price',
        'Is available'      => 'is_available()',
        'Created at'        => 'field_created_at',
    );
    protected $verbose_name = "key";
    protected $verbose_name_multiply = "keys";
    protected $single_router_name = 'admin:product-key';
    protected $sort_by = ['obj.price'];

    public function custom_filter_fields() {
        $id_product = $this->url_context['url_1'];
        return array(
            [
                'name'  => 'obj.product_id',
                'value' => $id_product
            ]
        );
    }
}

/*
 * Display single page for every model
 */
class AdminKeyController extends AdminSingleController {
    protected $model_сlass_name = "KeyModel";
    protected $field_title = 'field_created_at';
    protected $verbose_name = "key";
    protected $object_router_name = 'admin:product-key';
    protected $component_widgets = ['the_related_product'];
    protected $fields = array(
        [
            'model_field' => 'product_id',
            'input_type'  => 'number',
            'dynamic_save' => false,
            'input_label' => 'Product id',
            'input_attrs' => ['required', 'disabled']
        ],
        [
            'model_field' => 'key_code',
            'input_type'  => 'text',
            'input_label' => 'Key',
            'input_attrs' => ['required']
        ],
        [
            'model_field' => 'price',
            'input_type'  => 'number',
            'input_attrs' => ['required']
        ],
        [
            'model_field' => 'original_price',
            'input_type'  => 'number',
            'input_label' => 'Original price',
            'input_attrs' => ['required']
        ],
        [
            'model_field' => 'created_at',
            'input_type' => 'text',
            'dynamic_save'  => false,
            'input_label' => 'Created at',
            'input_attrs' => ['disabled']
        ],
        [
            'model_field' => 'order_id',
            'input_type'  => 'number',
            'dynamic_save' => false,
            'input_label' => 'Order id',
            'input_attrs' => ['disabled']
        ],
        [
            'model_field' => 'bought_at',
            'input_type' => 'text',
            'dynamic_save'  => false,
            'input_label' => 'Bought at',
            'input_attrs' => ['disabled']
        ],
    );
    protected function get_model() {
        $model = parent::get_model();

        if($this->context['is_new'])
            $model->field_product_id = (int)$this->url_context['url_1'];

        return $model;
    }
}
class AdminProductController extends AdminSingleController {
    protected $model_сlass_name = "ProductModel";
    protected $field_title = 'field_title';
    protected $verbose_name = "product";
    protected $object_router_name = 'admin:product';
    protected $component_widgets = ['the_list_keys_by_product'];

    protected $fields = array (
        [
            'model_field' => 'title',
            'input_type'  => 'text',
            'input_attrs' => ['required']
        ],
        [
            'model_field' => 'slug',
            'input_type'  => 'text',
            'input_attrs' => ['required']
        ],
        [
            'model_field' => 'excerpt',
            'input_type'  => 'text',
            'input_attrs' => ['required']
        ],
        [
            'model_field' => 'description',
            'input_type'  => 'textarea',
            'input_attrs' => ['required']
        ],
        [
            'model_field' => 'created_at',
            'input_type' => 'text',
            'dynamic_save'  => false,
            'input_label' => 'Created at',
            'input_attrs' => ['disabled']
        ],
        [
            'model_field' => 'original_url',
            'input_type'  => 'text',
            'input_attrs' => ['required']
        ],
        [
            'model_field' => 'original_price',
            'input_type'  => 'number',
            'input_attrs' => ['required']
        ],
        [
            'model_field' => 'edition',
            'input_type'  => 'text'
        ],
        [
            'model_field' => 'poster_url',
            'input_type'  => 'image',
            'input_label' => 'Poster',
        ],
        [
            'model_field' => 'image_url',
            'input_type'  => 'image',
            'input_label' => 'Image',
        ]
        
    );
    public function __construct($is_new = false) {
        parent::__construct($is_new);
        $this->fields[] = [
            'model_field' => 'platform_id',
            'input_type' => 'select',
            'input_label' => 'Platforms',
            'input_attrs' => ['required'],
            'input_values' => TaxonomyModel::get_type_values('platform', true)
        ];
        $this->fields[] = [
            'model_field' => 'region_id',
            'input_type' => 'select',
            'input_label' => 'Region',
            'input_attrs' => ['required'],
            'input_values' => TaxonomyModel::get_type_values('region', true)
        ];
    } 
}

class AdminUserController extends AdminSingleController {
    protected $model_сlass_name = "UserModel";
    protected $field_title = 'field_username';
    protected $verbose_name = 'user';
    protected $component_widgets = ['the_last_feedbacks'];
    protected $object_router_name = 'admin:user';
    protected $fields = array(
        [
            'model_field' => 'username',
            'input_type' => 'text',
            'input_attrs' => ['required']
        ],
        [
            'model_field' => 'email',
            'input_type' => 'email',
            'input_label' => 'E-mail',
            'input_attrs' => ['required']
        ],
        [
            'model_field' => 'password',
            'input_type' => 'text',
            'dynamic_save' => false,
            'input_attrs' => ['required']
        ],
        [
            'model_field' => 'is_admin',
            'input_type' => 'checkbox',
            'input_label' => 'Is admin'
        ],
        [
            'model_field' => 'register_at',
            'input_type' => 'text',
            'dynamic_save'  => false,
            'input_label' => 'Register at',
            'input_attrs' => ['disabled']
        ],
        '<hr>',
        [
            'model_field' => 'fname',
            'input_type' => 'text',
            'input_label' => 'First name',
        ],
        [
            'model_field' => 'lname',
            'input_type' => 'text',
            'input_label' => 'Last name',
        ]
    );
    /**
     * Some code for updating object before save
     * @param UserModel $object
     * @return void
     */
    protected function before_save(&$object) {
        $password = $_POST['password'] ?? '';

        // If password doesn't changed
        if($password === $object->field_password && $this->context['is_new'] == false)
            return;

        $result_valid = UserModel::valid_password($password);

        // If password is valid - hash password and save to object
        if($result_valid === true) {
            $password_hash = UserModel::password_hash($password);
            $object->field_password = $password_hash;
        }
        // if not, throw ValidationError
        else {
            throw new ValidationError($result_valid);
        }
    }
}

class AdminFeedbackController extends AdminSingleController {
    protected $model_сlass_name = "FeedbackModel";
    protected $field_title = 'field_name';
    protected $verbose_name = 'feedback';
    protected $component_widgets = [ 'the_related_user' ];
    protected $object_router_name = 'admin:feedback';
    protected $can_save = false;
    protected $fields = array(
        [
            'model_field' => 'name',
            'input_type' => 'text',
            'input_attrs' => ['required']
        ],
        [
            'model_field' => 'email',
            'input_type' => 'email',
            'input_attrs' => ['required']
        ],
        [
            'model_field' => 'content',
            'input_type' => 'textarea',
            'input_attrs' => ['required']
        ]
    );
    protected $edit_title_template = 'Feedback by [:field]';

    public function distinct() {
        $feedback = $this->get_model();

        if(!$feedback->field_is_read) {
            $feedback->field_is_read = true;
            $feedback->save();
        }
    }
}

class AdminTaxonomyController extends AdminSingleController {
    protected $model_сlass_name = "TaxonomyModel";
    protected $field_title = 'field_name';
    protected $verbose_name = 'taxonomy';
    protected $object_router_name = 'admin:tax';

    protected $fields = array(
        [
            'model_field' => 'name',
            'input_type' => 'text',
            'input_attrs' => ['required']
        ],
        [
            'model_field' => 'slug',
            'input_type' => 'text',
            'input_attrs' => ['required']
        ],
        [
            'model_field' => 'type',
            'input_type' => 'select',
            'input_attrs' => ['required'],
            'input_values' => TaxonomyModel::TYPES
        ],
        [
            'model_field' => 'icon_html',
            'input_label' => 'Icon HTML',
            'input_type' => 'textarea'
        ],
        [
            'model_field' => 'background_color',
            'input_label' => 'Background color',
            'input_type' => 'color'
        ]
    );
}

/**
 * Controller to delete every object model using className and id
 */
class AdminDeleteController extends AdminBaseController {
    protected $template_name = ADMIN_TEMPLATES . '/delete.php';

    /**
     * @return null|UserModel|FeedbackModel
     */
    protected function get_model() {
        if(isset($this->__model))
            return $this->__model;

        $id = $this->url_context['url_2'];
        $model_class = '';

        switch($this->url_context['url_1']) {
            case 'users':
                $model_class = "UserModel";
                break;
            case 'products':
                $model_class = "ProductModel";
                break;
            case 'feedbacks':
                $model_class = "FeedbackModel";
                break;
            case 'taxonomies':
                $model_class = "TaxonomyModel";
                break;
            
            default:
                return null;
        }
        return $model_class::get(array(
            [
                'name' => 'obj.id',
                'type' => '=',
                'value' => $id
            ]
            ));
    }
    public function get_context_data() {
        $context = parent::get_context_data();

        $model = $this->get_model();
        if(empty($model))
            throw new NotFoundHttp404();

        // Display field to show what's model
        $field = '';
        $back_url = '';
        switch($this->url_context['url_1']) {
            case 'users':
                $back_url = get_permalink('admin:user', [$model->get_id()]);
                $field = 'field_username';
                break;
            case 'feedbacks':
                $back_url = get_permalink('admin:feedback', [$model->get_id()]);
                $field = 'field_created_at';
                break;
            case 'taxonomies':
                $back_url = get_permalink('admin:tax', [$model->get_id()]);
                $field = 'field_name';
                break;
            case 'products':
                $back_url = get_permalink('admin:product', [$model->get_id()]);
                $field = 'field_title';
                break;
        }
        
        $context['back_url'] = $back_url;
        $context['model'] = $model;
        $context['field'] = $field;

        return $context;
    }
    protected function post() {
        $model = $this->get_model();
        
        if(empty($model))
            throw new NotFoundHttp404();

        $model->delete();

        // Redirect after delete
        $type_model = $this->url_context['url_1'];
        switch($type_model) {
            case 'users':
                $link = get_permalink('admin:user-list');
                break;
            case 'feedbacks':
                $link = get_permalink('admin:feedback-list');
                break;
            case 'tax':
                $link = get_permalink('admin:taxonomy-list');
                break;
            case 'products':
                $link = get_permalink('admin:product-list');
                break;
            default:
                $link = get_permalink('admin:home');
                break;
        }
        redirect_to($link);
    }
}