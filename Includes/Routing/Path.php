<?php

namespace KeysShop\Includes\Routing;

use KeysShop\Includes\BaseController;

/**
 * Class representing a path in the router, including the URL pattern, controller, and optional name.
 */
class Path
{
    // The regex pattern generated from the path for URL matching.
    private string $__regex;

    // The controller responsible for handling requests for this path.
    private BaseController $__controller;

    // The URL path.
    public string $path;

    // The optional name of the path, used for generating URLs.
    public string $name;

    /**
     * Path constructor that initializes the path, controller, and optional name.
     * It also formats the path into a regex pattern.
     * @param string $path The URL path for the router, for example "/users/[:int]/change".
     * @param BaseController $controller The controller that handles requests for this path.
     * @param string|null $name The optional name for the path.
     */
    function __construct($path, $controller, $name = null)
    {
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
    function check_url($requestUri)
    {
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
    function get_url_path($args = array())
    {
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
        if ($this->check_url($result))
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
    private function __get_url_args($requestUri)
    {

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
    private function __format_path_to_regex()
    {
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
    function show_page($requestUri)
    {
        // Get the URL arguments from the request URI and assign them to the controller's url_context.
        $this->__controller->url_context = $this->__get_url_args($requestUri);

        // Call the controller's __display method to render the page with the extracted URL arguments.
        $this->__controller->__display();
    }
}
