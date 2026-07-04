<?php

namespace Includes\Routing\HttpExceptions;

class ServerError500 extends PageError
{
    protected $page_error = 500;
    public function __construct($message = 'Server Error')
    {
        parent::__construct($message);
    }
}
