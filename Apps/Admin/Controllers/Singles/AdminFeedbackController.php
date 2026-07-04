<?php
namespace Apps\Admin\Controllers\Singles;

use Apps\Admin\Controllers\Abstract\AdminSingleController;

class AdminFeedbackController extends AdminSingleController {
    protected string $model_сlass_name = "Apps\Contacts\Models\FeedbackModel";
    protected string $field_title = 'field_name';
    protected string $verbose_name = 'feedback';
    protected array $component_widgets = [ 'the_related_user' ];
    protected ?string $object_router_name = 'admin:feedback';
    protected bool $can_save = false;
    protected array $fields = array(
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
    protected string $edit_title_template = 'Feedback by [:field]';

    public function distinct() {
        $feedback = $this->get_model();

        if(!$feedback->field_is_read) {
            $feedback->field_is_read = true;
            $feedback->save();
        }
    }
}