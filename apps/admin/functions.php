<?php
require_once APPS_PATH . "/contacts/models.php";
define('ADMIN_MAX_ELEMENTS', value: 20);

class AdminBaseController extends BaseController {
    protected $allow_role = 'admin';

    public function get_context_data() {
        $context  = array();
        $context['feedback_count'] = (int)(db_query("SELECT COUNT(*) FROM " . FeedbackModel::get_table_name() . "  WHERE `is_read` = 0")->fetch_row()[0]);

        return $context;
    }
}
class AdminListController extends AdminBaseController {
    protected $template_name = ADMIN_TEMPLATES . '/list-view.php';

    // Model related with BaseModel
    protected $model_сlass_name;
    /**
     * Fields to show in the table
     * array(
     *  "title_1" => "field_name_1",
     *  "title_2" => "field_name_2",
     * )
     * @var array
     */
    protected $table_fields;
    protected $single_router_name;
    protected $create_router_name;
    protected $verbose_name = "object";
    protected $verbose_name_multiply = "objects";
    protected $sort_by = array();

    public function custom_filter_fields() {
        return array();
    }
    public function get_context_data() {
        $context = parent::get_context_data();

        $search = $_GET['s'] ?? '';
        $page = $_GET['page'] ? (int)$_GET['page'] : 1;

        // Get objects by sort and search
        $context['objects'] = $this->model_сlass_name::filter(
            $this->custom_filter_fields(), 
            $this->sort_by,
            ADMIN_MAX_ELEMENTS,
            'OR',
            calc_page_offset(ADMIN_MAX_ELEMENTS, $page),
            $search
        );
        $context['page'] = $page;
        $context['elem_per_page'] = ADMIN_MAX_ELEMENTS;
        $context['count'] = $this->model_сlass_name::count(
            $this->custom_filter_fields(),
            $search
        );

        // Get fields to display in the table
        $table_fields = array();
        foreach ($this->table_fields as $title => $field) {
            // Check field is function or not
            $is_func = false;
            if(str_ends_with($field, "()")) {
                $is_func = true;
                $field = substr($field,  0, -strlen('()'));
            }
                

            $table_fields[] = array(
                'field_title' => $title,
                'is_func' => $is_func,
                'field_name' => $field
            );
        }
        $context['table_fields'] = $table_fields;

        // Verbose names
        $context['verbose_name'] = $this->verbose_name;
        $context['verbose_name_multiply'] = $this->verbose_name_multiply;

        // Router name to link every object and link to create object
        $context['single_router_name'] = $this->single_router_name;
        $context['create_router_name'] = $this->create_router_name;

        return $context;
    }
}
class AdminSingleController extends AdminBaseController {
    protected $template_name = ADMIN_TEMPLATES . '/single-view.php';

    protected $model_сlass_name;

    /**
     * Router name to redirect new object
     * @var string
     */
    protected $object_router_name;

    /**
     * Relation form fields and model fields
     * array(
     *      [
     *          'model_field' => 'username',
     *          'input_type' => 'text',
     *          'dynamic_save'  => false,       // Default is true. If false, field won't save automatically. You can use before_save()
     *          'input_label' => 'My Username', // If not set, default is 'model_field' with Capitalize
     *          'input_attrs' => ['disabled', 'required', 'maxlength="50"'], // Default empty
     *          'input_values' => [ ['value_1', 'My Value 1'], ... ] // For select field. 
     *      ]
     * )
     * @var array
     */
    protected $fields = array();
    protected $field_title = 'field_id';
    protected $edit_title_template = 'Edit [:verbose] "[:field]"';
    protected $can_save = true;

    /**
     * Function names with $object attribute.
     * ['the_last_feedbacks']
     * @var array
     */
    protected $component_widgets = array();
    protected $verbose_name = 'object';
    
    /**
     * Show model in admin panel
     * @param bool $is_new is new model (Create)?
     */
    public function __construct($is_new = false) {
        $this->context['is_new'] = $is_new;
    }

    /**
     * Custom model object
     * @return mixed
     */
    protected function get_model() {
        if(isset($this->__model))
            return $this->__model;
        
        if($this->context['is_new']) {
            $this->__model = new $this->model_сlass_name();
        }
        else {
            $this->__model = $this->model_сlass_name::get(
                array(
                    [
                        'name' => 'obj.id',
                        'type' => '=',
                        'value' => $this->url_context['url_1']
                    ]
                )
            );
            if(empty($this->__model))
                throw new NotFoundHttp404();

        }
        return $this->__model;
    }

    public function get_context_data() {
        $context = parent::get_context_data();

        $object = $this->get_model();

        $context['object'] = $object;
        $context['verbose_name'] = $this->verbose_name;

        $context['edit_title'] = str_replace('[:verbose]', $this->verbose_name, $this->edit_title_template);
        $context['edit_title'] = str_replace('[:field]', $object->{$this->field_title}, $context['edit_title']);
        $context['object_table_name'] = $object->get_table_name();

        $context['fields'] = $this->fields;
        $context['can_save'] = $this->can_save;
        $context['component_widgets'] = $this->component_widgets;

        return $context;
    }
    /**
     * Some code for updating object before save
     * @param mixed $object
     * @return void
     */
    protected function before_save(&$object) { }
    
    protected function post() {
        $object = $this->get_model();

        foreach ($this->fields as $field) {
            // Exceptions
            if(gettype($field) == 'string')
                continue;

            if(isset($field['dynamic_save']))
                if($field['dynamic_save'] === false)
                    continue;

            // Get value from fields
            $field_value = null;
            switch ($field['input_type']) {
                case 'checkbox':
                    $field_value = $_POST[$field['model_field']] ? true : false;
                    break;
                case 'image':
                    $file = $_FILES[$field['model_field']];
                    if(isset($file)) {
                        $path = upload_file($file, $this->model_сlass_name . '/', 'image');

                        if(!empty($path)) {
                            $field_value = $path;
                        }
                    }
                    break;
                default:
                    $field_value = $_POST[$field['model_field']] ?? null;
                    break;
            }

            // Set value to object
            $object->{'field_' . $field['model_field']} = $field_value;
        }

        try {
            $this->before_save($object);
            $object->save();

            // If object is new, redirect to his own page
            if($this->context['is_new']) {
                $url = get_permalink($this->object_router_name, [$object->get_id()]);
                redirect_to($url);
            }

            $this->context['success_message'] = "The " . $this->verbose_name . " has been saved";
        }
        catch(ValidationError $ex) {
            $this->context['error_form'] = $ex;
        }
        catch(Exception $ex) {
            if(DEBUG_MODE)
                $this->context['error_message'] = $ex->getMessage();
            else
                $this->context['error_message'] = "Unexpected Error";
        }
    }
}