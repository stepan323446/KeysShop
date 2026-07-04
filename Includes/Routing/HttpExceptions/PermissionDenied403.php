<?php

namespace Includes\Routing\HttpExceptions;

class PermissionDenied403 extends PageError
{
    protected $page_error = 403;
    public function __construct($message = 'Forbidden')
    {
        parent::__construct($message);
    }
}
