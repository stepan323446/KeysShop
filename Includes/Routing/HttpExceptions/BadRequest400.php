<?php

namespace KeysShop\Includes\Routing\HttpExceptions;

class BadRequest400 extends PageError
{
    protected $page_error = 400;
    public function __construct($message = 'Bad Request')
    {
        parent::__construct($message);
    }
}
