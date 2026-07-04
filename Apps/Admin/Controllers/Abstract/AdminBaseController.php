<?php
namespace Apps\Admin\Controllers\Abstract;

use Apps\Contacts\Models\FeedbackModel;
use Includes\BaseController;

require_once APPS_PATH . '/Admin/components.php';

define('ADMIN_MAX_ELEMENTS', value: 20);

class AdminBaseController extends BaseController {
    protected ?string $allow_role = 'admin';

    public function get_context_data() {
        $context  = array();
        $context['feedback_count'] = FeedbackModel::count(array(
            [
                'name'      => 'is_read',
                'value'     => 0
            ]
        ));

        return $context;
    }
}