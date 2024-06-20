<?php

namespace Core\Exceptions;

use Exception;

class PersonTypeInvalidException extends Exception
{
    public function __construct(string $message = 'Person type is invalid')
    {
        parent::__construct($message, 400);
    }
}
