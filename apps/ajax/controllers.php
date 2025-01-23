<?php
require_once INCLUDES_PATH . '/base_controller.php';

class AjaxController extends BaseController {
    protected $template_name = APPS_PATH . '/ajax/templates/ajax-result.php';

    protected function restrict() {

    }

    public function get_context_data() {
        require_once APPS_PATH . '/ajax/functions.php';
        $context['result'] = "";

        $json = file_get_contents('php://input');
        $data = json_decode($json, true);

        $action = $data['action'] ?? false;

        // If request from other site
        if (!in_array($_SERVER['HTTP_HOST'], ALLOWED_HOSTS)) {
            $context['result'] = get_ajax_error("403 Forbidden", 403);
            return $context;
        }

        // if don't receive action method
        if(empty($action)) {
            $context['result'] = get_ajax_error("The action field indicating the function is not specified");
            return $context;
        }
        $action = "ajax_" . $action;

        try {
            $context['result'] = $action($data['args']);
        }
        catch(Exception $ex) {
            $context['result'] = get_ajax_error($ex->getMessage());
        }
        

        return $context;
    }
}