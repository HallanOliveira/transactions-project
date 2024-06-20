<?php

namespace Core\Exceptions;

use Exception;

class DataNotFoundException extends Exception
{
    public function __construct(string $message = 'Data not found')
    {
        parent::__construct($message, 404);
    }
}
