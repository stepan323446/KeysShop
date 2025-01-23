<?php
class BaseController
{
    public $url_context = array();
    public $context = array();
    protected $template_name;

    protected $allow_role = null;

    protected $__model = null;

    /**
     * Get model for controller
     * @return mixed|null
     */
    protected function get_model() {
        if(isset($this->__model))
            return $this->__model;

        // Set model to __model and return it
    }

    public function get_context_data() {
        return array();
    }
    /**
     * Restricts access to the page based on user roles.
     * @return void
     */
    protected function restrict() {
        switch ($this->allow_role) {
            case 'user':
                // If user is not authorized
                if(!CURRENT_USER)
                    throw new UnauthorizedHttp401();
                break;

            case 'admin':
                // If user is not authorized
                if(!CURRENT_USER)
                    throw new UnauthorizedHttp401();

                // // If user is not an admin
                if(!CURRENT_USER->field_is_admin)
                    throw new PermissionDeniedHttp403();
                break;
            
            default:
                // Nothing
                break;
        }
    }
    /**
     * Some function before display content
     */
    protected function distinct() {

    }
    /**
     * Function if we have POST request
     * @return void
     */
    protected function post() {
        
    }
    
    public function __display()
    {
        $this->restrict();
        
        // Collect all context vars
        $this->context += $this->url_context + $this->get_context_data();

        // Some func before display
        $this->distinct();

        // Post method
        if($_SERVER['REQUEST_METHOD'] == 'POST')
            $this->post();
        
        $context = $this->context;
        require_once $this->template_name;
    }
}