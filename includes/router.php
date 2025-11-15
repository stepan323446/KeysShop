<?php
class Router {
    // An array that holds all registered router paths.
    static $routers = array();

    // An array that maps HTTP error codes to their respective controllers.
    // Example: array(404 => new NotFoundController())
    static $error_controllers = array();

    // The name of the currently active router path, if any
    static $current_router_name = null;

    /**
     * A static method to retrieve the URL for a given router name and arguments.
     * @param string $name
     * @param array $args
     * @return string tring The generated URL or a placeholder message if arguments or router name are not found.
     */
    static function get_url($name, $args = array()) {
        if(isset(self::$routers[$name])) {
            // Get the URL path from the router, passing in any provided arguments for controller $url_context.
            $url = self::$routers[$name]->get_url_path($args);

            // If a valid URL is returned, prepend the base URL (HOME_URL) and return the full URL.
            if($url !== false)
                return HOME_URL . $url;
            else
                // If the URL is invalid, return a placeholder message.
                return 'argument-not-found';
        }
            
        else
            // If the router name doesn't exist, return an empty string.
            return '';
    }

    /**
     * Collect path array using includes and path
     * This method processes each path in the provided $paths array and optionally adds a prefix to the path name.
     * @param array $paths
     * @param string $name
     * @return void
     */
    static function includes($paths, $name = null) {
        foreach ($paths as $path) {
            // If a $name is provided, prepend it to the path name as prefix.
            if($name)
                $path->name = $name . ":" . $path->name;

            // Set the path in the router
            self::path($path);
        }
    }

    /**
     * Sets the given path in the router.
     * @param Path $path
     * @return void
     */
    static function path($path) {
        // If the path has a name, store it in the $routers array with the name as the key for search.
        if($path->name)
            self::$routers[$path->name] = $path;
        else
            self::$routers[] = $path;
    }
    
    /**
     * Display the page based on the requested URL.
     * This method processes the request URI, attempts to find a matching router, 
     * and displays the corresponding page. If an error occurs, it is handled and displayed.
     * @return void
     */
    static function display_page() {
        // Get the requested URL without any query parameters (without GET).
        $requestUri = strtok($_SERVER['REQUEST_URI'], '?');

        // Parse the base domain URL to extract the path.
        $parsedDomainUrl = parse_url(HOME_URL);
        $defaultDomainPath = $parsedDomainUrl['path'] ?? '';
        
        // Remove the domain's base path from the requested URI.
        $requestUri = str_replace($defaultDomainPath, '', $requestUri);
        // Remove trailing slashes from the URI
        $requestUri = rtrim($requestUri, '/');
        
        try {
            // Flag to track if a matching router is found.
            $request_found = false;

            // Iterate over all registered routers to find a match.
            foreach(self::$routers as $router_name => $router) {
                if($router->check_url($requestUri)) {
                    // Set the current router name if a match is found.
                    self::$current_router_name = $router_name;
                    
                    $request_found = true;
    
                     // Display the page using the matched router.
                    $router->show_page($requestUri);
                    break;
                }
            }
             // If no matching router is found, throw a 404 error.
            if(!$request_found)
                throw new NotFoundHttp404();
        }
        catch(PageError $httpError) {
             // Display the error page if a PageError exception is caught.
            static::display_error($httpError);
        }
        catch(Exception $ex) {
            // Handle other exceptions, displaying detailed error information in debug mode.
            if(DEBUG_MODE) {
                echo $ex->getMessage() . '<br>';
                echo $ex->getTraceAsString();
            }
            else {
                // In non-debug mode, display a generic error page.
                $httpError = new PageError();
                static::display_error($httpError);
            }
        }
    }

    /**
     * Set an error handling controller for a specific error code.
     * @param int|string $error_code Code number (404, 500,...) or 'default'
     * @param BaseController $controller
     * @return void
     */
    static function set_error_controller($error_code, $controller) {
        self::$error_controllers[$error_code] = $controller;
    }
    /**
     * Display the error page corresponding to the provided PageError object.
     * @param PageError $page_error
     * @return void
     */
    static function display_error($page_error) {
         // Get the HTTP error code from the PageError object.
        $error_code = $page_error->get_http_error();

        // Set the HTTP response code to match the error code.
        http_response_code($error_code);

        // Check if a custom controller exists for the error code.
        if(isset(self::$error_controllers[$error_code])) {
            // Add the error model to the controller's context and display the error page.
            self::$error_controllers[$error_code]->context += array(
                'error_model' => $page_error
            );
            self::$error_controllers[$error_code]->__display();
        }
        // Check if a custom controller exists as default error controller.
        elseif(isset(self::$error_controllers['default'])) {
            self::$error_controllers['default']->context += array(
                'error_model' => $page_error
            );
            self::$error_controllers['default']->__display();
        }
        else {
            // If no custom controller exists, display a generic error message.
            echo 'Error ' . $error_code;
        }
    }
}

/**
 * Class representing a path in the router, including the URL pattern, controller, and optional name.
 */
class Path {
     // The regex pattern generated from the path for URL matching.
    private $__regex;

    // The controller responsible for handling requests for this path.
    private $__controller;

    // The URL path.
    public $path;

    // The optional name of the path, used for generating URLs.
    public $name;

    /**
     * Path constructor that initializes the path, controller, and optional name.
     * It also formats the path into a regex pattern.
     * @param string $path The URL path for the router, for example "/users/[:int]/change".
     * @param BaseController $controller The controller that handles requests for this path.
     * @param string|null $name The optional name for the path.
     */
    function __construct($path, $controller, $name = null) {
        $this->path = $path;

        // Convert the path into a regex pattern (e.g., "/users/[:int]/change" becomes "#^/users/([0-9]+)/change$#").
        $this->__regex = $this->__format_path_to_regex();
        $this->__controller = $controller;
        $this->name = $name;
    }
    
    /**
      * Checks if the given URL matches the stored path regex.
     * @param string $requestUri
     * @return bool|int
     */
    function check_url($requestUri) {
        return preg_match($this->__regex, $requestUri);
    }

    /**
     * Generates a URL path by replacing placeholders with provided arguments.
     * This method takes the defined path, replaces placeholder tokens (e.g., '[:int]') with actual values
     * from the `$args` array, and returns the constructed URL path. If the path is invalid after substitution,
     * it returns `false`.
     * 
     * @param array $args The array of arguments to replace placeholders in the path.
     * @return string|false The generated URL path or `false` if the path is invalid.
     */
    function get_url_path($args = array()) {
        $result = $this->path;
        
        // Replace placeholder tokens for string and integer with '[:val]'.
        $result = str_replace('[:string]', '[:val]', $result);
        $result = str_replace('[:int]', '[:val]', $result);

        // Iterate through the arguments and replace '[:val]' placeholders with actual values.
        foreach ($args as $arg) {
            $position = strpos($result, '[:val]');
            $result = substr_replace($result, $arg, $position, strlen('[:val]'));
        }

        // Check if the generated URL path matches the original path's regex pattern.
        if($this->check_url($result))
            return $result;
        else
            return false;
    }
    /**
     * Extracts arguments from the given URL based on the stored regex pattern.
     * This method matches the provided `requestUri` against the route's regex and extracts the arguments
     * @param string $requestUri
     * @return array An associative array of extracted arguments (e.g., ['url_1' => '5']) or an empty array if no match is found.
     */
    private function __get_url_args($requestUri) {
        
        if (preg_match($this->__regex, $requestUri, $matches)) {
            
            $url_args = array();
            for ($i = 1; $i < count($matches); $i++) { 
                $url_args['url_' . $i] = $matches[$i];
            }
            return $url_args;
        } else {
            return array();
        }
    }
    /**
     * Converts the path into a regular expression for URL matching.
     * @return string
     */
    private function __format_path_to_regex() {
        $reg = $this->path;

        $reg = str_replace('[:string]', '([a-zA-Z0-9\-]+)', $reg);
        $reg = str_replace('[:int]', '([0-9]+)', $reg);

        $reg = '#^' . $reg . '$#';
        return $reg;
    }
    
    /**
     * Displays the page corresponding to the given request URI.
     * @param string $requestUri
     * @return void
     */
    function show_page($requestUri) {
        // Get the URL arguments from the request URI and assign them to the controller's url_context.
        $this->__controller->url_context = $this->__get_url_args($requestUri);

        // Call the controller's __display method to render the page with the extracted URL arguments.
        $this->__controller->__display();
    }
}
class NotFoundHttp404 extends PageError {
    protected $page_error = 404;
    public function __construct($message = 'Page not found') {
        parent::__construct($message);
    }
}
class UnauthorizedHttp401 extends PageError {
    protected $page_error = 401;
    public function __construct($message = 'Unauthorized') {
        parent::__construct($message);
    }
}
class BadRequestHttp400 extends PageError {
    protected $page_error = 400;
    public function __construct($message = 'Bad Request') {
        parent::__construct($message);
    }
}
class PermissionDeniedHttp403 extends PageError { 
    protected $page_error = 403;
    public function __construct($message = 'Forbidden') {
        parent::__construct($message);
    }
}

/**
 * Class PageError
 * Represents a general HTTP error.
 * Inherits from the Exception class and provides an error code for HTTP errors.
 */
class PageError extends Exception {
    // Default HTTP error code for Unexpected Error
    protected $page_error = 500;

    public function __construct($message = "Unexpected error") {
        $this->message = $message;
    }
     /**
     * Returns the HTTP error code for the current error.
     *
     * @return int The HTTP error code.
     */
    public function get_http_error() {
        return $this->page_error;
    }
}
?>