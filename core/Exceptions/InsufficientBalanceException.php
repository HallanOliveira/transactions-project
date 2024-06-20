<?php

namespace Core\Exceptions;

use Exception;

class InsufficientBalanceException extends Exception
{
    public function __construct(string $message = 'Insufficient balance')
    {
        parent::__construct($message, 400);
    }
}
