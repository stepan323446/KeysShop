<?php

namespace KeysShop\Includes\Routing\HttpExceptions;

class PageError extends \Exception
{
    // Default HTTP error code for Unexpected Error
    protected $page_error = 500;

    public function __construct($message = "Unexpected error")
    {
        $this->message = $message;
    }
    /**
     * Returns the HTTP error code for the current error.
     *
     * @return int The HTTP error code.
     */
    public function get_http_error()
    {
        return $this->page_error;
    }
}
