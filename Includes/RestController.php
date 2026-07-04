<?php
namespace Includes;

use Includes\BaseController;
use Includes\Routing\HttpExceptions\BadRequest400;
use Override;

abstract class RestController extends BaseController {
    /**
     * Function if we have PUT request
     * @return array|null
     */
    protected function put(): ?array { return null; }

    /**
     * Function if we have PATCH request
     * @return array|null
     */
    protected function patch(): ?array { return null; }

    /**
     * Function if we have PUT/PATCH request
     * @return array|null
     */
    protected function update(): ?array { return null; }

    /**
     * Function if we have DELETE request
     * @return array|null
     */
    protected function delete(): ?array { return null; }

    /**
     * Function if we have GET request
     * @return array|null
     */
    protected function get(): ?array { return null; }

    /**
     * Function if we have POST request
     * @return array|null
     */
    protected function post(): ?array { return null; }

    protected function get_json_body(): array {
        $raw = file_get_contents('php://input');

        if (empty($raw))
            return [];

        $data = json_decode($raw, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new BadRequest400('Invalid JSON body');
        }

        return $data ?? [];
    }
    
    protected function display_settings()
    {
        header('Content-Type: application/json; charset=utf-8');
    }

    protected function __display_template() {
        
    }

    public function __display() {
        $this->display_settings();
        return parent::__display();
    }

    protected function call_requrest_method() {
        $result = null;

        switch ($_SERVER['REQUEST_METHOD']) {
            case 'POST':
                $result = $this->post();
                break;
            case 'PUT':
                $result = $this->put();
                $result = $this->update() ?? $result;
                break;
            case 'PATCH':
                $result = $this->patch();
                $result = $this->update() ?? $result;
                break;
            case 'DELETE':
                $result = $this->delete();
                break;

            default:
                $result = $this->get();
                break;
        }
        echo json_encode($result ?? []);
        exit;
    }
}