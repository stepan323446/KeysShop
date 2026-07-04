<?php

namespace Includes\Routing;

use Includes\Routing\Path;
use Includes\BaseController;
use Includes\Routing\HttpExceptions;

class Router
{
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
    static function get_url($name, $args = array())
    {
        if (isset(self::$routers[$name])) {
            // Get the URL path from the router, passing in any provided arguments for controller $url_context.
            $url = self::$routers[$name]->get_url_path($args);

            // If a valid URL is returned, prepend the base URL (HOME_URL) and return the full URL.
            if ($url !== false)
                return HOME_URL . $url;
            else
                // If the URL is invalid, return a placeholder message.
                return 'argument-not-found';
        } else
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
    static function includes($paths, $name = null)
    {
        foreach ($paths as $path) {
            // If a $name is provided, prepend it to the path name as prefix.
            if ($name)
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
    static function path($path)
    {
        // If the path has a name, store it in the $routers array with the name as the key for search.
        if ($path->name)
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
    static function display_page()
    {
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
            foreach (self::$routers as $router_name => $router) {
                if ($router->check_url($requestUri)) {
                    // Set the current router name if a match is found.
                    self::$current_router_name = $router_name;

                    $request_found = true;

                    // Display the page using the matched router.
                    $router->show_page($requestUri);
                    break;
                }
            }
            // If no matching router is found, throw a 404 error.
            if (!$request_found)
                throw new HttpExceptions\NotFound404();
            
        } catch (HttpExceptions\PageError $httpError) {
            // Display the error page if a PageError exception is caught.
            static::display_error($httpError);
        } catch (\Exception $ex) {
            // Handle other exceptions, displaying detailed error information in debug mode.
            if (DEBUG_MODE) {
                echo $ex->getMessage() . '<br>';
                echo $ex->getTraceAsString();
            } else {
                // In non-debug mode, display a generic error page.
                $httpError = new HttpExceptions\PageError();
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
    static function set_error_controller($error_code, $controller)
    {
        self::$error_controllers[$error_code] = $controller;
    }
    /**
     * Display the error page corresponding to the provided PageError object.
     * @param HttpExceptions\PageError $page_error
     * @return void
     */
    static function display_error($page_error)
    {
        // Get the HTTP error code from the PageError object.
        $error_code = $page_error->get_http_error();

        // Set the HTTP response code to match the error code.
        http_response_code($error_code);

        // Check if a custom controller exists for the error code.
        if (isset(self::$error_controllers[$error_code])) {
            // Add the error model to the controller's context and display the error page.
            self::$error_controllers[$error_code]->context += array(
                'error_model' => $page_error
            );
            self::$error_controllers[$error_code]->__display();
        }
        // Check if a custom controller exists as default error controller.
        elseif (isset(self::$error_controllers['default'])) {
            self::$error_controllers['default']->context += array(
                'error_model' => $page_error
            );
            self::$error_controllers['default']->__display();
        } else {
            // If no custom controller exists, display a generic error message.
            echo 'Error ' . $error_code;
        }
    }
}
