<?php
namespace Apps\Admin\Controllers\Lists;

use Apps\Admin\Controllers\Abstract\AdminListController;

class AdminFeedbackListController extends AdminListController {
    protected string $model_сlass_name = "Apps\Contacts\Models\FeedbackModel";
    protected array $table_fields = array(
        'Name' => 'field_name',
        'E-mail' => 'field_email',
        'Created at	' => 'field_created_at',
        'Is Read?' => 'field_is_read',
    );
    protected string $single_router_name = 'admin:feedback';
    protected string $verbose_name = "feedback";
    protected string $verbose_name_multiply = "feedbacks";
    protected array $sort_by = ['obj.is_read', '-obj.created_at'];
}